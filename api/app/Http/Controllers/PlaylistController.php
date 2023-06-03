<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\PlaylistTrack;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PlaylistController extends Controller
{

  public function createPlaylist(Request $request)
  {
    try {
      $request->validate([
        'name' => 'required|string|max:30',
      ]);

      $playlist = Playlist::create([
        'name' => $request->name,
        'account_id' => $request->user()->id,
      ]);

      unset($playlist->account_id);

      return response(['message' => 'La playlist a été créée', 'data' => $playlist], 201);
    } catch (ValidationException $e) {

      return response(['erreur' => $e->getMessage()], 422);
    } catch (Exception $e) {

      Log::error($e);
      return response(['erreur' => 'Une erreur s\'est produite'], 500);
    }
  }

  public function getAllPlaylist(Request $request)
  {
    try {

      $playlists = Playlist::where('account_id', $request->user()->id)->get();

      foreach ($playlists as $playlist) {
        $playlist->tracks = $playlist->tracks;

        unset($playlist->account_id);

        foreach ($playlist->tracks as $track) {
          unset($track->pivot);
        }
      }

      return response(['data' => $playlist], 200);
    } catch (Exception $e) {

      Log::error($e);
      return response(['message' => 'Une erreur s\'est produite'], 500);
    }
  }


  public function addTrack(Request $request)
  {
    try {

      $request->validate([
        'playlist_id' => 'required|numeric',
        'track_id' => 'required|numeric',
      ]);

      $playlists = Playlist::where('account_id', $request->user()->id)->get();

      $playlistTrack = [];

      foreach ($playlists as $playlist) {
        if ($playlist->id === $request->playlist_id) {

          $allTrackPlaylist = PlaylistTrack::where('playlist_id', $request->playlist_id)->get();

          foreach ($allTrackPlaylist as $track) {
            if ($track->track_id === $request->track_id) {
              return response(['message' => 'Le titre est déjà dans la playlist'], 409);
            }
          }

          $playlistTrack = PlaylistTrack::create([
            'playlist_id' => $request->playlist_id,
            'track_id' => $request->track_id,
          ]);
        }
      }

      if ($playlistTrack === []) {
        return response(['message' => 'La playlist n\'existe pas'], 409);
      }

      return response(['message' => 'Le titre a été ajouté à la playlist'], 201);
    } catch (ValidationException $e) {

      return response(['erreur' => $e->getMessage()], 422);
    } catch (Exception $e) {

      Log::error($e);
      return response(['erreur' => 'Une erreur s\'est produite'], 500);
    }
  }

  public function deleteTrack(Request $request)
  {

    try {
      $request->validate([
        'playlist_id' => 'required|numeric',
        'track_id' => 'required|numeric',
      ]);

      $playlist = PlaylistTrack::where(['playlist_id' => $request->playlist_id, 'track_id' => $request->track_id])
        ->delete();

      if ($playlist === 0) {
        return response(['message' => 'Le titre n\'est pas présent dans la playlist'], 404);
      }

      return response(['message' => 'Le titre a été supprimé de la playlist'], 200);
    } catch (ValidationException $e) {

      return response(['message' => $e->getMessage()], 422);
    } catch (Exception $e) {

      Log::error($e);
      return response(['message' => 'Une erreur s\'est produite'], 500);
    }
  }

  public function deletePlaylist(Request $request)
  {
    try {
      $request->validate([
        'playlist_id' => 'required|numeric',
      ]);

      $playlist = Playlist::where('id', $request->playlist_id)->delete();

      if ($playlist === 0) {
        return response(['message' => 'La playlist n\'existe pas'], 404);
      }

      return response(['message' => 'La playlist a été supprimée'], 200);
    } catch (ValidationException $e) {

      return response(['message' => $e->getMessage()], 422);
    } catch (Exception $e) {

      Log::error($e);
      return response(['message' => 'Une erreur s\'est produite'], 500);
    }
  }

  public function renamePlaylist(Request $request)
  {
    try {
      $request->validate([
        'playlist_id' => 'required|numeric',
        'name' => 'required|string|max:30',
      ]);

      $playlist = Playlist::where('id', $request->playlist_id)->update(['name' => $request->name]);

      if ($playlist === 0) {
        return response(['message' => 'La playlist n\'existe pas'], 404);
      }

      return response(['message' => 'La playlist a été renommée'], 200);
    } catch (ValidationException $e) {

      return response(['message' => $e->getMessage()], 422);
    } catch (Exception $e) {

      Log::error($e);
      return response(['message' => 'Une erreur s\'est produite'], 500);
    }
  }
}
