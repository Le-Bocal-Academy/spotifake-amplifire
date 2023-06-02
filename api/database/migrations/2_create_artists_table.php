<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('artists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('artists');
    }
};
