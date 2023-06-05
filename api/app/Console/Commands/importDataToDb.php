<?php

namespace App\Console\Commands;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Style;
use App\Models\Track;
use Illuminate\Console\Command;

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
            $album = Album::firstOrCreate([
                'title' => $albumData['title'],
                // 'artist' => $albumData['artist'],
                'year' => $albumData['year'],
                'description' => $albumData['description'],
                'artist_id' => $artist->id,
            ]);
            // Style::firstOrCreate($albumData['styles']);
            $styles = [];
            foreach ($albumData['styles'] as $style) {
                $styleModel = Style::firstOrCreate(['style' => $style]);
                $styles[] = $styleModel->id;
            }

            $album->styles()->attach($styles);

            foreach ($albumData['tracks'] as $trackData) {
                Track::firstOrCreate([
                    'title' => $trackData['title'],
                    'duration' => $trackData['duration'],
                    'file' => $trackData['file'],
                    'album_id' => $album->id,
                ]);
            }
        }
    }
}
