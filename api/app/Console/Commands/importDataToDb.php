<?php

namespace App\Console\Commands;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Style;
use App\Models\Track;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class importDataToDb extends Command
{
    protected $signature = 'data:import';

    protected $description = 'Import data from json file to database';

    public function handle()
    {
        $datasJson = file_get_contents(storage_path('app/public/data.json'));
        $datas = json_decode($datasJson, true);



        foreach ($datas['albums'] as $albumData) {
            $artist = Artist::firstOrCreate(['name' => $albumData['artist']]);
            Log::info($artist);
            $album = Album::create([
                'title' => $albumData['title'],
                'artist' => $albumData['artist'],
                'year' => $albumData['year'],
                'description' => $albumData['description'],
                'artist_id' => $artist->id,
            ]);


            foreach ($albumData['styles'] as $styleName) {
                $style = Style::create(['style' => $styleName, 'album_id' => $album->id,]);
                $album->styles()->attach($style);
            }

            foreach ($albumData['tracks'] as $trackData) {
                Track::create([
                    'title' => $trackData['title'],
                    'duration' => $trackData['duration'],
                    'file' => $trackData['file'],
                    'album_id' => $album->id,
                ]);
                // $album->tracks()->attach($track);
            }
        }
    }
}
