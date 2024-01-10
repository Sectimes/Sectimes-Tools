<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ExecuteShellCommandController;
use App\Jobs\ProcessOneClickScan;

class OneClickScanController extends Controller
{
    public function index() {
        return view('oneClickScan');
    }

    public function scan(Request $request) {
        $target = $request->input('target');
        $network = $request->input('network');
        $subdomain = $request->input('subdomain');
        $directory = $request->input('directory');
        $wapplyzer = $request->input('wapplyzer');
        $ip = $request->input('ip');

        // Validate URL
        $parseUrl = parse_url($target);
        if (isset($parseUrl['host'])) {
            $hostname = $parseUrl['host'];
        } else {
            abort(500, "Invalid Target Url!");
        }

        if (isset($network)) {
            $network = "on";
        }

        // Put the process into queue
        ProcessOneClickScan::dispatch($hostname, $network, $subdomain, $directory, $wapplyzer, $ip)->onQueue('queue3');
        return view('oneClickScan', compact('hostname'));
    }
}
