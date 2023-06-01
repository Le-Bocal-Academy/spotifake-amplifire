<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Test extends Controller
{
    public function index(Request $request)
    {
        Log::info('autorisé');
        return response()->json([
            'message' => 'Hello World!'
        ]);
    }
}
