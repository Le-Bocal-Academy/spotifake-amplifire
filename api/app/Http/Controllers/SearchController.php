<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Track;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        try {
            $query = $request->input('query');

            $tracks = Track::where('title', 'like', "%$query%")->get();
            $albums = Album::where('title', 'like', "%$query%")->get();
            $artists = Artist::where('name', 'like', "%$query%")->get();

            $data = [
                'tracks' => $tracks,
                'albums' => $albums,
                'artists' => $artists,
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
}
