<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class PlaylistTrack extends Model
{
  use HasFactory, Notifiable, HasApiTokens;

  protected $table = 'playlist_track';

  protected $fillable = [
    'playlist_id',
    'track_id',
  ];

  protected $hidden = [
    'updated_at',
    'created_at',
  ];
}
