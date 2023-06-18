<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Playlist extends Model
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'account_id',
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
    ];

    public function tracks()
    {
        return $this->belongsToMany(Track::class, 'playlist_track', 'playlist_id', 'track_id');
    }
}
