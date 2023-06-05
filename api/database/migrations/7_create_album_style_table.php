<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{

  public function up()
  {
    Schema::create('album_style', function (Blueprint $table) {
      $table->bigIncrements('id');

      $table->unsignedBigInteger('album_id');
      $table->foreign('album_id')->references('id')->on('albums')->onDelete('cascade');
      $table->unsignedBigInteger('style_id');
      $table->foreign('style_id')->references('id')->on('styles')->onDelete('cascade');
    });
  }

  public function down()
  {
    Schema::dropIfExists('album_style');
  }
};
