<?php

namespace App\Http\Controllers;

use App\Models\Track;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

class TrackController extends Controller
{
    public function play($id)
    {
        try {
            $track = Track::findOrFail($id);
            $fileName = $track->file;

            if (Storage::disk('s3')->exists($fileName)) {
                return response(Storage::disk('s3')->get($fileName), 200)->header('Content-Type', 'audio/mpeg');
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Track not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }
}
