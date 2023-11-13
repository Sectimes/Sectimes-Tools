<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrackingEndpointResults;
use App\Models\TrackingEndpointTarget;

class TargetScannedController extends Controller
{
    public function index() {
        return view('target-scanned');
    }
}
