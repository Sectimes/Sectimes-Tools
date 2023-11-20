<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessRequestToTarget;
use App\Models\TrackingEndpointResults;
use App\Models\TrackingEndpointTarget;
use DOMXPath;
use Exception;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use DOMDocument;

class TrackingEndpointController extends Controller
{
    public function index() {
        // Render tracking-endpoints.blade.php
        return view('tracking-endpoints');
    }
    
    public function requestToTarget() {
        $target = request('target-url');
        $cookies = request('cookies');

        // Add target URL to tracking_endpoint_target table
        TrackingEndpointTarget::updateOrCreate(
            ['target' => $target],
            ['num_of_results' => null]
        );

        ProcessRequestToTarget::dispatch($target, $cookies);

        $endpoints = TrackingEndpointResults::where('target', $target)->get();

        return view('tracking-endpoints', compact('endpoints', 'target'));
    }

    public function validateRequest() {
        return request()->validate( [
            'target-url' => 'required'
        ] );
    }
}