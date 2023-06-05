<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Style;
use App\Models\Track;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class AlbumController extends Controller
{
    public function getAlbumById($id)
    {
        try {
            $album = Album::findOrFail($id);
            $album->artist = Artist::findOrFail($album->artist_id);
            $album->tracks = Track::where('album_id', $album->id)->get();
            $album->styles = Style::whereHas('albums', function ($query) use ($album) {
                $query->where('album_id', $album->id);
            })->get();

            unset($album->artist_id);

            return response(['data' => $album], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['erreur' => 'L\'album n\'existe pas'], 404);
        } catch (Exception $e) {

            Log::error($e);
            return response(['erreur' => 'Une erreur s\'est produite'], 500);
        }
    }

    public function getAlbums()
    {
        try {
            $albums = Album::all();

            foreach ($albums as $album) {
                $album->artist = Artist::findOrFail($album->artist_id);
                $album->tracks = Track::where('album_id', $album->id)->get();
                $album->styles = Style::whereHas('albums', function ($query) use ($album) {
                    $query->where('album_id', $album->id);
                })->get();
                unset($album->artist_id);
            }

            return response(['data' => $albums], 200);
        } catch (Exception $e) {

            Log::error($e);
            return response(['erreur' => 'Une erreur s\'est produite'], 500);
        }
    }
}
