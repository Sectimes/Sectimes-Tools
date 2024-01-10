<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\ExecuteShellCommandController;
use Illuminate\Support\Facades\Log;

class ProcessOneClickScan implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $hostname, $network, $subdomain, $directory, $wapplyzer, $ip;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($hostname, $network, $subdomain, $directory, $wapplyzer, $ip)
    {
        $this->hostname = $hostname;
        $this->network = $network;
        $this->subdomain = $subdomain;
        $this->directory = $directory;
        $this->wapplyzer = $wapplyzer;
        $this->ip = $ip;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $shellCommand = new ExecuteShellCommandController();
        $scanResult = public_path("one-click-scan") . "/test.txt";

        putenv('HOME=/home/sectimes');

        $hostname = $this->hostname;

        // Run NMap Scan
        if (isset($this->network)) {
            $shellCommand->execute("echo '============================== NMap Scanning ==============================' >> $scanResult");
            $shellCommand->execute("nmap -sC -sV -O $hostname >> $scanResult");
        }

        // Run Subdomain Scan
        if (isset($this->subdomain)) {
            $shellCommand->execute("echo '\n============================== Subdomain Scanning ==============================' >> $scanResult");
            $shellCommand->execute("HOME=/home/sectimes subfinder -d $hostname >> $scanResult");
        }

        if (isset($this->directory)) {
            $shellCommand->execute("echo '\n============================== Directory Scanning ==============================' >> $scanResult");
            $shellCommand->execute("echo 'TODO: Fuzzing Directory Later' >> $scanResult");
        }

        if (isset($this->wapplyzer)) {
            $shellCommand->execute("echo '\n============================== Wapplyzer Scanning ==============================' >> $scanResult");
            Log::info('Wapplyzer Command: ' . "echo '| Stacks-cli |' >> $scanResult && stacks-cli https://$hostname >> $scanResult && echo '\n| Webanalyze |' >> $scanResult && HOME=/home/sectimes /var/www/webanalyze/webanalyze -host $hostname -crawl 2 >> $scanResult");
            $shellCommand->execute("echo '| Stacks-cli |' >> $scanResult && stacks-cli https://$hostname >> $scanResult && echo '\n| Webanalyze |' >> $scanResult && HOME=/home/sectimes /var/www/webanalyze/webanalyze -host $hostname -crawl 2 >> $scanResult");
            // $shellCommand->execute("stacks-cli https://$hostname >> $scanResult");
            // $shellCommand->execute("echo '\n| Webanalyze | >> $scanResult'");
            // $shellCommand->execute("HOME=/home/sectimes /var/www/webanalyze/webanalyze -host $hostname -crawl 2");
        }

        if (isset($this->ip)) {
            $shellCommand->execute("echo '\n============================== IP Scanning ==============================' >> $scanResult");
            $shellCommand->execute("echo '| Dig | >> $scanResult'");
            $shellCommand->execute("dig $hostname >> $scanResult"); # Add +short to get only IP
            $shellCommand->execute("echo '\n| Nslookup |' >> $scanResult");
            $shellCommand->execute("nslookup $hostname >> $scanResult");
        }
    }
}
