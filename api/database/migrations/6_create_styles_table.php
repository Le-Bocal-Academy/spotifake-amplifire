<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{

  public function up()
  {
    Schema::create('styles', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('style');

      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('styles');
  }
};
