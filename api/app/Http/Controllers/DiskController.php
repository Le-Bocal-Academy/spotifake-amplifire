<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DiskController extends Controller
{
    public function index()
    {
        $files = Storage::disk('s3')->allFiles();
        var_dump("fichiers S3", $files);
        // $file = Storage::disk('s3')->get($files[0]);

    }




    // Get all files from the disk and return url to access them
    // $tracks = Storage::disk('s3')->allFiles();
    // if ($tracks) {
    //     $tracksUrl = [];
    //     foreach ($tracks as $track) {
    //         $url = Storage::disk('s3')->url($track);
    //         array_push($tracksUrl, $url);
    //     }
    //     return response()->json($tracksUrl, 200);
    // } else {
    //     return response()->json(['message' => 'No tracks found'], 400);
    // }

    // $tracks = Storage::disk('s3')->allFiles();
    // if ($tracks) {
    //     foreach ($tracks as $track) {
    //         $url = Storage::disk('s3')->get($track);
    //         $trackName = explode('/', $track);
    //         $trackName = $trackName[count($trackName) - 1];
    //         var_dump($trackName);
    //         $tracksUrl[$trackName] = $url;
    //     }
    // }

    // $tracks = Storage::disk('s3')->allFiles();
    // if ($tracks) {
    //     foreach ($tracks as $track) {
    //         return response($track, 200)->header('Content-Type', 'audio/mpeg');
    //     }
    // }


    // $tracks = Storage::disk('s3')->allFiles();
    // if ($tracks) {
    //     foreach ($tracks as $track) {
    //         $trackName = Storage::disk('s3')->get($track);
    //     }
    //     return $trackName;
    //     return response($trackName, 200)->header('Content-Type', 'audio/mpeg');
    // } else {
    //     return response()->json(['message' => 'No tracks found'], 400);
    // }


}
