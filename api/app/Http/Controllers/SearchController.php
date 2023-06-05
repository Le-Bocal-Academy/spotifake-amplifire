<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Style;
use App\Models\Track;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

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
                return response()->json(['message' => 'No match found'], 404);
            }

            return response(['data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }
}
