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
}
