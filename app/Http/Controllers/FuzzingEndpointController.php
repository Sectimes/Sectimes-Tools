<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class FuzzingEndpointController extends Controller
{
    public static function execute($cmd): string
    {
        $process = Process::fromShellCommandline($cmd);

        $processOutput = '';

        $captureOutput = function ($type, $line) use (&$processOutput) {
            $processOutput .= $line;
        };

        $process->setTimeout(null)
            ->run($captureOutput);

        if ($process->getExitCode()) {
            $exception = new ShellCommandFailedException($cmd . " - " . $processOutput);
            report($exception);

            throw $exception;
        }

        return $processOutput;
    }

    public function index() {
        return view('fuzzing-endpoints');
    }

    public function fuzz(Request $request) {
        $input = $request->all();
        $endpoint = $request->input('endpoint');
        $wordlists = $request->input('wordlist');

        // $endpoint_fuzz = preg_replace('/=([^&]+)/', '=FUZZ', $endpoint);
        // $endpoint_fuzz = "http://csid.hcmtelecom.vn/gian-hang?p_p_id=FUZZ&p_p_lifecycle=0&p_p_state=normal&p_p_mode=view&p_p_col_id=column-1&p_p_col_pos=3&p_p_col_count=12&_dsgianhangclient_WAR_hcmqldoanhnghiepportlet_nganhngheId=4&_dsgianhangclient_WAR_hcmqldoanhnghiepportlet_mvcPath=/html/client/DsGianHang/view.jsp&_dsgianhangclient_WAR_hcmqldoanhnghiepportlet_nganhngheTen=Cơ khí - Điện";

        // $result_ffuf = $this->execute("ffuf -w ../wordlist/SQLi/ALL.txt -u " . $endpoint_fuzz);

        // $result_ffuf = $this->execute("cat ../wordlist/SQLi/ALL.txt");

        $result = Process::run('ls -la', function (string $type, string $output) {
            echo $output;
        });
        // dd($result_ffuf);

        // if (isset($cookies)) {
        //     $result = followRedirectsWithCookies($target, $cookies);
        //     $htmlContent = $result['response'];
        // } else {
        //     // Create a new Guzzle Client
        //     $client = new Client();

        //     $response = $client->get($target);
        //     $htmlContent = $response->getBody()->getContents();
        // }

        // $endpoints = $this->urlFromDOM($htmlContent);

        // return view('tracking-endpoints', compact('endpoints', 'target'));
    }
}