<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Style;
use App\Models\Track;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SongController extends Controller
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

  public function search(Request $request)
  {
    try {
      $query = $request->input('query');

      $tracks = Track::where('title', 'like', "%$query%")->get()->map(function ($track) {
        $album = Album::findOrFail($track->album_id);
        $artist = Artist::findOrFail($album->artist_id);

        $track->album_title = $album->title;
        $track->artist_name = $artist->name;

        unset($track->album_id);

        return $track;
      });

      $albums = Album::where('title', 'like', "%$query%")->get()->map(function ($album) {
        $artist = Artist::findOrFail($album->artist_id);

        $album->artist_name = $artist->name;

        unset($album->artist_id);

        return $album;
      });

      $artists = Artist::where('name', 'like', "%$query%")->get()->map(function ($artist) {
        $artist->artist_tracks = collect();
        $albums = Album::where('artist_id', $artist->id)->get();
        foreach ($albums as $album) {
          $tracks = Track::where('album_id', $album->id)->get()->map(function ($track) use ($album) {
            $track->album_name = $album->title;
            unset($track->album_id);
            return $track;
          });
          $artist->artist_tracks = $artist->artist_tracks->merge($tracks);
        }

        return $artist;
      });

      $styles = Style::where('style', 'like', "%$query%")->get()->map(function ($style) {
        $style->albums = Album::whereHas('styles', function ($query) use ($style) {
          $query->where('style_id', $style->id);
          unset($style->id);
        })->get();
        foreach ($style->albums as $album) {
          $album->artist_name = Artist::findOrFail($album->artist_id)->name;
          unset($album->artist_id);
        }

        return $style;
      });

      $data = [
        'tracks' => $tracks,
        'albums' => $albums,
        'artists' => $artists,
        'styles' => $styles
      ];

      $isEmpty = collect($data)->flatten()->isEmpty();

      if ($isEmpty) {
        return response()->json(['erreur' => 'Aucune correspondance touvée'], 404);
      }

      return response(['data' => $data], 200);
    } catch (Exception $e) {

      Log::error($e);
      return response(['erreur' => 'Une erreur s\'est produite'], 500);
    }
  }

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
