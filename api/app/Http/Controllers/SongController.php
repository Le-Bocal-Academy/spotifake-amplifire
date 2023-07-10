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

      // Vérifie si le fichier existe dans le stockage S3
      if (Storage::disk('s3')->exists($fileName)) {
        return response(Storage::disk('s3')->get($fileName), 200);
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

      // récupère les sons correspondant à une valeur reçus, puis boucle sur chacun
      $tracks = Track::where('title', 'like', "%$query%")->get()->map(function ($track) {
        // Récupère l'album et l'artiste associé au son
        $album = Album::findOrFail($track->album_id);
        $artist = Artist::findOrFail($album->artist_id);

        // Ajout des informations de l'album et de l'artiste à la piste
        $track->album_title = $album->title;
        $track->artist_name = $artist->name;

        unset($track->album_id);

        return $track;
      });

      $albums = Album::where('title', 'like', "%$query%")->get()->map(function ($album) {
        $artist = Artist::findOrFail($album->artist_id);
        unset($album->year, $album->description);

        $album->artist_name = $artist->name;

        unset($album->artist_id);

        return $album;
      });

      // récupère les artistes correspondant à une valeur reçus, puis boucle sur chacun
      $artists = Artist::where('name', 'like', "%$query%")->get()->map(function ($artist) {
        // Récupère les artistes associés
        $artist->artist_tracks = Track::whereHas('album.artist', function ($query) use ($artist) {
          $query->where('id', $artist->id);
        })->get()->map(function ($track) {
          // Récupère l'album associé au son et ajoute le titre de l'album à la piste
          $album = Album::findOrFail($track->album_id);
          $track->album_title = $album->title;

          $artist = Artist::findOrFail($album->artist_id);
          $track->artist_name = $artist->name;

          unset($track->album_id);
          return $track;
        });

        $artist->artist_albums = Album::where('artist_id', $artist->id)->get()->map(function ($album) use ($artist) {
          $album->artist_name = $artist->name;
          unset($album->artist_id);
          return $album;
        });

        return $artist;
      });

      // récupère les styles correspondant à une valeur reçus, puis boucle sur chacun
      $styles = Style::where('style', 'like', "%$query%")->get()->map(function ($style) {
        // Récupère les albums associés
        $style->albums = Album::whereHas('styles', function ($query) use ($style) {
          $query->where('style_id', $style->id);
          unset($style->id);
        })->get();

        // Parcourt chaque album pour récupérer le nom de l'artiste associé
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

      // Vérification si toutes les collections dans $data sont vides
      $isEmpty = collect($data)->flatten()->isEmpty();
      if ($isEmpty) {
        // Si toutes les collections sont vides, renvoie une réponse d'erreur
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
      // Récupération l'album par son id et récupère l'artiste, les sons et les styles associé à l'album
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
      // Récupération de tous les albums
      $albums = Album::all();

      foreach ($albums as $album) {
        // Récupère l'artiste, les sons et les styles associé à chaque album
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
