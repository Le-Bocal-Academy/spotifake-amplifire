<?php

namespace App\Http\Controllers;

use App\Models\Track;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
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
            return response()->json(['erreur' => 'La chanson n\'existe pas'], 404);
        } catch (Exception $e) {

            Log::error($e);
            return response(['erreur' => 'Une erreur s\'est produite'], 500);
        }
    }
}
