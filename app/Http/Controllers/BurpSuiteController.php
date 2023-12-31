<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BurpSuiteController extends Controller
{
    public function burpProxyConnect() {
        $requestFilename = request('request-file-name');

        // Check if there exists Path Traversal vulnerability
        $checkFilePath = realpath('reqresp/' . $requestFilename);
        
        // Get the content of the file
        if (strpos($checkFilePath, public_path('reqresp/' . $requestFilename)) === 0) {
            $fileContent = File::get($checkFilePath);
        } else {
            abort(500, 'Internal Server Error');
        }
        
        // Get the plaintext request from provided file
        $divider = strpos($fileContent, '---- ↑ Request ---- Response ↓ ----');
        try {
            if ($divider !== false) {
                $getRequest = substr($fileContent, 0, $divider);
            } else {
                $getRequest = $divider;
            }
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }

        // Using regex to get Host and Path value
        try {
            if (preg_match('/Host: (\S+)/', $getRequest, $matches)) {
                $host = $matches[1];
            }
            if (preg_match('/GET (\S+) HTTP/', $getRequest, $matches)) {
                $path = $matches[1];
            }
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
        return $this->sendToBurp($host, $path);
    }

    public function sendToBurp($host, $path) {

        // Please change your Host IP in .env file
        $hostIP = env('HOST_MACHINE_IP');
        $proxy = "http://$hostIP:8080";
        $client = new Client([
            'base_uri' => 'http://' . $host,
            'proxy' => $proxy,
            'verify' => false
        ]);
        try {
            $client->request('GET', $path);
            return response("Request is sent to target through Burp", 200);
        } catch (\Throwable $e) {
            return response("Request is sent to target through Burp", 200);
        }
    }
}
