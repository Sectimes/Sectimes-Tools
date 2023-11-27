<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

class ListingFfufResultController extends Controller
{
    public function resultFfufListing() {
        $directory = public_path('result-ffuf');

        if (File::isDirectory($directory)) {
            $files = File::files($directory);
            // $folders = File::directories($directory);
    
            $filenames = array_map(function ($file) {
                return pathinfo($file)['basename'];
            }, $files);
            // $foldernames = array_map(function ($folder) {
                // return pathinfo($folder)['basename'];
            // }, $folders);

            return view('result-ffuf-listing', compact('filenames'));
        } else {
            abort(404);
        }
    }

    public function resultFfufSpecificFilenameListing($filename) {
        $filePath = public_path("result-ffuf/$filename");

        if (file_exists($filePath)) {
            $content = File::get($filePath);
            return response($content, 200)->header('Content-Type', 'text/html');
        } else {
            abort(404);
        }
    }
}
