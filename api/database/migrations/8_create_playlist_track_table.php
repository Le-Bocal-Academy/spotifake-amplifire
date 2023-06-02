<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{

  public function up()
  {
    Schema::create('playlist_track', function (Blueprint $table) {
      $table->bigIncrements('id');

      $table->unsignedBigInteger('playlist_id');
      $table->foreign('playlist_id')->references('id')->on('playlists')->onDelete('cascade');

      $table->unsignedBigInteger('track_id');
      $table->foreign('track_id')->references('id')->on('styles')->onDelete('cascade');
    });
  }


  public function down()
  {
    Schema::dropIfExists('playlist_track');
  }
};
