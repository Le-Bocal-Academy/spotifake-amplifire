<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\PlaylistTrack;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PlaylistController extends Controller
{


  public function createPlaylist(Request $request)
  {
    try {

      $playlist = Playlist::create([
        'name' => $request->name,
        'account_id' => $request->user()->id,
      ]);

      unset($playlist->account_id);


      return $playlist;
    } catch (Exception $e) {
      Log::error($e);
      return response(['message' => 'Une erreur s\'est produite'], 500);
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

      return $playlists;
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
      ]);

      $playlists = Playlist::where('account_id', $request->user()->id)->get();

      $playlistTrack = [];

      foreach ($playlists as $playlist) {
        if ($playlist->id === $request->playlist_id) {

          $allTrackPlaylist = PlaylistTrack::where('playlist_id', $request->playlist_id)->get();

          foreach ($allTrackPlaylist as $track) {
            if ($track->track_id === $request->track_id) {
              return response(['message' => 'Le titre est déjà dans la playlist'], 500);
            }
          }

          $playlistTrack = PlaylistTrack::create([
            'playlist_id' => $request->playlist_id,
            'track_id' => $request->track_id,
          ]);
        }
      }

      if ($playlistTrack === []) {
        return response(['message' => 'La playlist n\'existe pas'], 500);
      }

      return $playlistTrack;
    } catch (Exception $e) {

      Log::error($e);
      return response(['message' => 'Une erreur s\'est produite'], 500);
    }
  }

  public function deleteTrack(Request $request)
  {
    // TODO try catch, validate, return message
    $playlist = PlaylistTrack::where(['playlist_id' => $request->playlist_id, 'track_id' => $request->track_id])
      ->delete();

    return $playlist;
  }

  public function deletePlaylist(Request $request)
  {
  }

  public function renamePlaylist(Request $request)
  {
  }
}
