<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Style extends Model
{
  use HasFactory;

  protected $fillable = ['style'];

  /**
   * 
   * @var array<int, string>
   */
  protected $hidden = [
    'created_at',
    'updated_at',
  ];

  public function albums()
  {
    return $this->belongsToMany(Album::class, 'album_style', 'style_id', 'album_id');
  }
}
