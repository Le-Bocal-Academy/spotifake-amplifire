<?php

namespace App\Console\Commands;

use App\Models\Album;
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
        $datasJson = file_get_contents(storage_path('data.json'));
        $datas = json_decode($datasJson, true);

        foreach ($datas['albums'] as $albumData) {
            $album = Album::create([
                'title' => $albumData['title'],
                'artist' => $albumData['artist'],
                'year' => $albumData['year'],
                'description' => $albumData['description'],
            ]);

            foreach ($albumData['styles'] as $styleName) {
                $style = Style::firstOrCreate(['name' => $styleName]);
                $album->styles()->attach($style);
            }

            foreach ($albumData['tracks'] as $trackData) {
                $track = Track::create([
                    'title' => $trackData['title'],
                    'duration' => $trackData['duration'],
                    'file' => $trackData['file'],
                    'album_id' => $album->id,
                ]);
                $album->tracks()->attach($track);
            }
        }
    }
}
