<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
  use HasFactory;

  protected $fillable = ['name'];

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
    return $this->hasMany(Album::class);
  }

  public function tracks()
  {
    return $this->hasMany(Track::class);
  }
}
