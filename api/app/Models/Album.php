<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
  use HasFactory;

  protected $fillable = ['title', 'artist_id', 'year', 'description'];

  public function artist()
  {
    return $this->belongsTo(Artist::class);
  }

  public function tracks()
  {
    return $this->hasMany(Track::class, 'album_id');
  }

  public function styles()
  {
    return $this->belongsToMany(Style::class, 'album_style', 'album_id', 'style_id');
  }
}
