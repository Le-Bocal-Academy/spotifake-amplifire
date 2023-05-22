<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('playlists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id');
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->string('name');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('playlists');
    }
};
