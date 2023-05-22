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

        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nickname');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email');
            $table->string('password');
            $table->string('remember_token');
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
        Schema::dropIfExists('account');
    }
};
