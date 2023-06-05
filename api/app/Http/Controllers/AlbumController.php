<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Track;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AlbumController extends Controller
{
    public function getAlbum($id)
    {
        try {
            $album = Album::findOrFail($id);
            $album->artist = Artist::findOrFail($album->artist_id);
            $album->tracks = Track::where('album_id', $album->id)->get();

            unset($album->artist_id);

            return response(['data' => $album], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Album not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }
}
