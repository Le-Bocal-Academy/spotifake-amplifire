<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
  use HasFactory;

  protected $fillable = ['title', 'duration', 'file', 'album_id'];

  protected $hidden = [
    'updated_at',
    'created_at',
  ];

  public function album()
  {
    return $this->belongsTo(Album::class);
  }

  public function artist()
  {
    return $this->belongsTo(Artist::class);
  }

  public function playlists()
  {
    return $this->belongsToMany(Playlist::class, 'playlist_track', 'track_id', 'playlist_id');
  }
}
