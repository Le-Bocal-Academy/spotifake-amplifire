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
      // Validation des données envoyées
      $request->validate([
        'name' => 'required|string|max:30',
      ]);

      // Création de la playlist en base de données
      $playlist = Playlist::create([
        'name' => $request->name,
        'account_id' => $request->user()->id,
      ]);

      // Suppression de l'id de l'utilisateur
      unset($playlist->account_id);

      return response(['message' => 'La playlist a été créée', 'data' => $playlist], 201);
      // Gestion des erreurs
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
      // Récupération de toutes les playlists de l'utilisateur
      $playlists = Playlist::where('account_id', $request->user()->id)->get();

      // Récupération des titres de chaque playlist
      foreach ($playlists as $playlist) {
        $playlist->tracks = $playlist->tracks;

        // Suppression de l'id de l'utilisateur
        unset($playlist->account_id);

        // Suppression de la table pivot
        foreach ($playlist->tracks as $track) {
          unset($track->pivot);
        }
      }

      return response(['data' => $playlists], 200);
      // Gestion des erreurs
    } catch (Exception $e) {

      Log::error($e);
      return response(['message' => 'Une erreur s\'est produite'], 500);
    }
  }


  public function addTrack(Request $request)
  {
    try {
      // Validation des données envoyées
      $request->validate([
        'playlist_id' => 'required|numeric',
        'track_id' => 'required|numeric',
      ]);

      // Récupération de toutes les playlists de l'utilisateur
      $playlists = Playlist::where('account_id', $request->user()->id)->get();

      // Initialisation du tableau qui contiendra les titres de la playlist 
      $playlistTrack = [];

      // Vérification que la playlist existe et ajout du titre
      foreach ($playlists as $playlist) {
        if ($playlist->id === $request->playlist_id) {

          // Vérification que le titre n'est pas déjà dans la playlist
          $allTrackPlaylist = PlaylistTrack::where('playlist_id', $request->playlist_id)->get();

          foreach ($allTrackPlaylist as $track) {
            if ($track->track_id === $request->track_id) {
              return response(['message' => 'Le titre est déjà dans la playlist'], 409);
            }
          }

          // Ajout du titre dans la playlist
          $playlistTrack = PlaylistTrack::create([
            'playlist_id' => $request->playlist_id,
            'track_id' => $request->track_id,
          ]);
        }
      }

      // Gestion des erreurs
      if ($playlistTrack === []) {
        return response(['message' => 'La playlist n\'existe pas'], 404);
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
      // Validation des données envoyées
      $request->validate([
        'playlist_id' => 'required|numeric',
        'track_id' => 'required|numeric',
      ]);

      // Suppression du titre de la playlist
      $playlist = PlaylistTrack::where(['playlist_id' => $request->playlist_id, 'track_id' => $request->track_id])
        ->delete();

      // Gestion des erreurs
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
    // Validation des données envoyées
    try {
      $request->validate([
        'playlist_id' => 'required|numeric',
      ]);

      // Suppression de la playlist
      $playlist = Playlist::where('id', $request->playlist_id)->delete();

      // Gestion des erreurs
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
    // Validation des données envoyées
    try {
      $request->validate([
        'playlist_id' => 'required|numeric',
        'name' => 'required|string|max:30',
      ]);

      // Renommage de la playlist
      $playlist = Playlist::where('id', $request->playlist_id)->update(['name' => $request->name]);

      // Gestion des erreurs
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
