<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DiskController extends Controller
{
    public function index()
    {
        // Get all files from the disk and return url to access them
        $tracks = Storage::disk('s3')->allFiles();
        if ($tracks) {
            $tracksUrl = [];
            foreach ($tracks as $track) {
                $url = Storage::disk('s3')->url($track);
                array_push($tracksUrl, $url);
            }
            return response()->json($tracksUrl, 200);
        } else {
            return response()->json(['message' => 'No tracks found'], 400);
        }
    }
}
