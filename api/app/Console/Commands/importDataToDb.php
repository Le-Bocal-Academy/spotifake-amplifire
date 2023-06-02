<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class importDataToDb extends Command
{
    protected $signature = 'data:import';

    protected $description = 'Import data from json file to database';

    public function handle()
    {
        $datasJson = file_get_contents('/api/app/Console/Commands/amplifireData.json');
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
                ]);
            }
        }
    }
}
