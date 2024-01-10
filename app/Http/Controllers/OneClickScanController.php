<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ExecuteShellCommandController;

class OneClickScanController extends Controller
{
    public function index() {
        return view('oneClickScan');
    }

    public function scan(Request $request) {
        $shellCommand = new ExecuteShellCommandController();
        $scanResult = public_path("one-click-scan") . "/test.txt";

        putenv('HOME=/home/sectimes');

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

        // Run NMap Scan
        if (isset($network)) {
            $shellCommand->execute("echo '============================== NMap Scanning ==============================' >> $scanResult");
            $shellCommand->execute("nmap -sC -sV -O $hostname >> $scanResult");
        }

        // Run Subdomain Scan
        if (isset($subdomain)) {
            $shellCommand->execute("echo '\n============================== Subdomain Scanning ==============================' >> $scanResult");
            $shellCommand->execute("HOME=/home/sectimes subfinder -d $hostname >> $scanResult");
        }

        if (isset($directory)) {
            $shellCommand->execute("echo '\n============================== Directory Scanning ==============================' >> $scanResult");
            $shellCommand->execute("echo 'TODO: Fuzzing Directory Later' >> $scanResult");
        }

        if (isset($wapplyzer)) {
            $shellCommand->execute("echo '\n============================== Wapplyzer Scanning ==============================' >> $scanResult");
            $shellCommand->execute("echo '| Stacks-cli | >> $scanResult'");
            $shellCommand->execute("stacks-cli https://$hostname >> $scanResult");
            $shellCommand->execute("echo '\n| Webanalyze | >> $scanResult'");
            $shellCommand->execute("HOME=/home/sectimes /var/www/webanalyze/webanalyze -host $hostname -crawl 2");
        }

        if (isset($ip)) {
            $shellCommand->execute("echo '\n============================== IP Scanning ==============================' >> $scanResult");
            $shellCommand->execute("echo '| Dig | >> $scanResult'");
            $shellCommand->execute("dig $hostname >> $scanResult"); # Add +short to get only IP
            $shellCommand->execute("echo '\n| Nslookup |' >> $scanResult");
            $shellCommand->execute("nslookup $hostname >> $scanResult");
        }

    }
}
