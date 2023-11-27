<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

class ListingRequestResponseController extends Controller
{
    public function reqrespListing() {
        $directory = public_path('reqresp');

        if (File::isDirectory($directory)) {
            $files = File::files($directory);
            $folders = File::directories($directory);
    
            $filenames = array_map(function ($file) {
                return pathinfo($file)['basename'];
            }, $files);
            $foldernames = array_map(function ($folder) {
                return pathinfo($folder)['basename'];
            }, $folders);

            return view('directory-listing', compact('filenames', 'foldernames'));
        } else {
            abort(404);
        }
    }

    public function reqrespSpecificHostnameListing($hostOrFilename) {
        $directory = public_path('reqresp/' . $hostOrFilename);

        if (File::isDirectory($directory)) {
            $files = File::files($directory);
    
            $filenames = array_map(function ($file) {
                return pathinfo($file)['basename'];
            }, $files);

            return view('directory-listing', compact('filenames', 'hostOrFilename'));
        } elseif (!File::isDirectory($directory)) {
            $filePath = public_path('reqresp/' . $hostOrFilename);

            if (file_exists($filePath)) {
                return response()->file($filePath);
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }

    public function reqrespSpecificFilenameListing($hostname, $filename) {
        $filePath = public_path('reqresp/' . $hostname . '/' . $filename);

        if (file_exists($filePath)) {
            $content = File::get($filePath);
            return response($content, 200)->header('Content-Type', 'text/plain');
        } else {
            abort(404);
        }
    }
}
