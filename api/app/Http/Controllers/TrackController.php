<?php

namespace App\Http\Controllers;

use App\Models\Track;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TrackController extends Controller
{
    public function show($id)
    {
        $track = Track::findOrfail($id);
        $fileName = $track->file;

        if (Storage::disk('s3')->exists($fileName)) {
            return response(Storage::disk('s3')->get($fileName), 200)->header('Content-Type', 'audio/mpeg');
        } else {
            return response()->json(['message' => 'Track not found'], 400);
        }

        // $track = Track::findOrfail($id);
        // $filePath = $track->file;

        // if (Storage::disk('s3')->exists($filePath)) {
        //     return response()->streamDownload(function () use ($filePath) {
        //         echo Storage::disk('s3')->get($filePath);
        //     }, $track->file, [
        //         'Content-Type' => 'audio/mpeg',
        //         'Content-Disposition' => 'attachment; filename="' . $track->file . '"',
        //     ]);
        // } else {
        //     return response()->json(['message' => 'Track not found'], 400);
        // }
    }
}
