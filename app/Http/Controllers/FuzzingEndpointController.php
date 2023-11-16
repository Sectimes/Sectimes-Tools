<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class FuzzingEndpointController extends Controller
{
    private static $counter = 1;

    public static function execute($cmd): string
    {
        $process = Process::fromShellCommandline($cmd);

        $processOutput = '';

        $captureOutput = function ($type, $line) use (&$processOutput) {
            $processOutput .= $line;
        };

        $process->setTimeout(null)
            ->run($captureOutput);

        // if ($process->getExitCode()) {
        //     $exception = new ShellCommandFailedException($cmd . " - " . $processOutput);
        //     report($exception);

        //     throw $exception;
        // }

        return $processOutput;
    }

    public function index(Request $request) {
        // Render fuzzing-endpoints.blade.php
        $checked = '';
        $filename_endpoint = '';
        $target_id = $request->query('target_id');

        return view('fuzzing-endpoints', compact('target_id', 'checked', 'filename_endpoint'));
    }

    public function fuzz(Request $request) {
        $input = $request->all();
        $endpoint = $request->input('endpoint');
        $wordlists = $request->input('wordlist');

        $endpoint_fuzz = preg_replace('/=([^&]+)/', '=FUZZ', $endpoint);

        // Parsel URL to get only the hostname and check if we can extract the hostname or not
        $parseUrl = parse_url($endpoint);
        if (isset($parseUrl['host'])) {
            $hostname = $parseUrl['host'];
        } else {
            $hostname = "default-hostname-" . self::$counter;
            self::$counter++;
        }
        
        // Condition on reqresp box
        $reqrespChecked = $request->has('reqresp');
        $checked = '';
        $filename_endpoint = null;

        if ($reqrespChecked) {
            $checked = 'true';
            $result_ffuf = $this->execute("ffuf -w '" . base_path('wordlist') . "/SQLi/ALL.txt' -u '$endpoint_fuzz' -od " . base_path('public') . "/reqresp/$hostname/");
        } else {
            $filename_endpoint = $hostname . "-" . md5($endpoint);
            $checked = 'false';
            $result_ffuf = $this->execute("ffuf -w '" . base_path('wordlist') . "/SQLi/ALL.txt' -u '$endpoint_fuzz' -of html -o " . base_path('public') . "/result-ffuf/$filename_endpoint.html");
        }

        return view('fuzzing-endpoints', compact('endpoint', 'filename_endpoint', 'hostname', 'checked'));
    }

    public function reqrespListing() {
        $directory = public_path('reqresp');

        if (File::isDirectory($directory)) {
            $files = File::files($directory);
            $folders = File::directories($directory);
    
            $filenames = array_map(function ($file) {
                return pathinfo($file)['basename'];
            }, $files);
            $foldernames = array_map(function ($folder) {
                return pathinfo($folder)['basename'];
            }, $folders);

            return view('directory-listing', compact('filenames', 'foldernames'));
        } else {
            abort(404);
        }
    }

    public function reqrespSpecificHostnameListing($hostOrFilename) {
        $directory = public_path('reqresp/' . $hostOrFilename);

        if (File::isDirectory($directory)) {
            $files = File::files($directory);
    
            $filenames = array_map(function ($file) {
                return pathinfo($file)['basename'];
            }, $files);

            return view('directory-listing', compact('filenames', 'hostOrFilename'));
        } elseif (!File::isDirectory($directory)) {
            $filePath = public_path('reqresp/' . $hostOrFilename);

            if (file_exists($filePath)) {
                return response()->file($filePath);
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }

    public function reqrespSpecificFilenameListing($hostname, $filename) {
        $filePath = public_path('reqresp/' . $hostname . '/' . $filename);

        if (file_exists($filePath)) {
            $content = File::get($filePath);
            return response($content, 200)->header('Content-Type', 'text/plain');
        } else {
            abort(404);
        }
    }
}