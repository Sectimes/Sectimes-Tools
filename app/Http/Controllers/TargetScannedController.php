<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrackingEndpointResults;
use App\Models\TrackingEndpointTarget;

class TargetScannedController extends Controller
{
    public function index() {
        $targets = TrackingEndpointTarget::all();

        return view('target-scanned', compact('targets'));
    }

    public function show(TrackingEndpointTarget $target_id) {
        $targetScanned = $target_id->target;
        $endpointsScanned = TrackingEndpointResults::where('target', $targetScanned)->get();
        
        return view('target-scanned', compact('endpointsScanned'));
    }
}
