<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Log;

class BurpSuiteController extends Controller
{
    public function burpProxyConnect() {
        $client = new Client([
            'proxy' => 'http://192.168.2.22:8080',
            'verify' => false
        ]);
        $response = $client->get('https://example.com');
    }
}
