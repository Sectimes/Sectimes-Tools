<?php

namespace App\Jobs;

use App\Events\JobDoneEvent;
use App\Http\Controllers\ExecuteShellCommandController;
use Illuminate\Bus\Queueable;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessFuzzingEndpoint implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $reqrespChecked;

    public $endpoint;
    
    public $wordlists;

    private static $counter = 1;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($reqrespChecked, $endpoint, $wordlists)
    {
        $this->reqrespChecked = $reqrespChecked;
        $this->endpoint = $endpoint;
        $this->wordlists = $wordlists;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $shellCommand = new ExecuteShellCommandController();

        $endpoint_fuzz = preg_replace('/=([^&]+)/', '=FUZZ', $this->endpoint);
    
        // Parsel URL to get only the hostname and check if we can extract the hostname or not
        $parseUrl = parse_url($this->endpoint);
        if (isset($parseUrl['host'])) {
            $hostname = $parseUrl['host'];
        } else {
            $hostname = "default-hostname-" . self::$counter;
            self::$counter++;
        }
        
        $filename_endpoint = null;
    
        $sqliWL = base_path('wordlist') . "/SQLi/ALL.txt";
        $xssWL = base_path('wordlist') . "/XSS/ALL.txt";
        $dirWL = base_path('wordlist') . "/DirFuzzing/basic.txt";
        $cmdiWL = base_path('wordlist') . "/CMDi/unix.txt";
    
        if ($this->reqrespChecked) {
            $ffufCommand = "ffuf -u '$endpoint_fuzz' -od " . base_path('public') . "/reqresp/$hostname/";
            
            foreach ($this->wordlists as $wordlist) {
                switch($wordlist) {
                    case "SQLi":
                        $ffufCommand .= " -w '$sqliWL'";
                        break;
                    case "XSS":
                        $ffufCommand .= " -w '$xssWL'";
                        break;
                    case "CMDi":
                        $ffufCommand .= " -w '$cmdiWL'";
                        break;
                    case "Dir":
                        $ffufCommand .= " -w '$dirWL'";
                        break;
                    default:
                        $ffufCommand .= "";
                        break;
                }
            }
            $shellCommand->execute($ffufCommand);
        } else {
            $filename_endpoint = $hostname . "-" . md5($this->endpoint);
            $ffufCommand = "ffuf -u '$endpoint_fuzz' -of json -o " . base_path('public') . "/result-ffuf/$filename_endpoint.json";
    
            foreach ($this->wordlists as $wordlist) {
                switch($wordlist) {
                    case "SQLi":
                        $ffufCommand .= " -w '$sqliWL'";
                        break;
                    case "XSS":
                        $ffufCommand .= " -w '$xssWL'";
                        break;
                    case "CMDi":
                        $ffufCommand .= " -w '$cmdiWL'";
                        break;
                    case "Dir":
                        $ffufCommand .= " -w '$dirWL'";
                        break;
                    default:
                        $ffufCommand .= "";
                        break;
                }
            }
            $shellCommand->execute($ffufCommand);
        }
    
        // Dispatch the event when the job is done
        event(new JobDoneEvent($this->endpoint));
    }

    
}
