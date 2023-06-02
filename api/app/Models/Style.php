<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Style extends Model
{
  use HasFactory;

  protected $fillable = ['style', 'album_id'];

  public function albums()
  {
    return $this->belongsToMany(Album::class);
  }
}
