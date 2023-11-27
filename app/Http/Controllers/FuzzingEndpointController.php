<?php

namespace App\Http\Controllers;

use App\Events\JobDoneEvent;
use App\Models\Counter;
use App\Jobs\ProcessFuzzingEndpoint;
use App\Models\JobStatus;
use Illuminate\Http\Request;

class FuzzingEndpointController extends Controller
{
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
        $reqrespChecked = $request->has('reqresp');

        $successCounter = Counter::where('id', 1)->value('success_counter');
        $counter = Counter::where('id', 1)->value('counter');

        $parseUrl = parse_url($endpoint);
        if (isset($parseUrl['host'])) {
            $hostname = $parseUrl['host'] . "-" . $successCounter;
            $successCounter++;
            Counter::where('id', 1)->update(['success_counter' => $successCounter]);
        } else {
            $hostname = "default-hostname-" . $counter;
            $counter++;
            Counter::where('id', 1)->update(['counter' => $counter]);
        }

        ProcessFuzzingEndpoint::dispatch($reqrespChecked, $endpoint, $wordlists)->onQueue('queue2');

        // return view('fuzzing-endpoints', compact('endpoint', 'filename_endpoint', 'hostname', 'checked'));
        return view('fuzzing-endpoints', compact('hostname'));
    }

    public function checkJobStatus($jobName) {
        $isDone = JobStatus::where('job_name', $jobName)->value('is_done');
        // JobStatus::destroy($jobName);
        return response()->json(['jobDone' => $isDone]);
    }
}