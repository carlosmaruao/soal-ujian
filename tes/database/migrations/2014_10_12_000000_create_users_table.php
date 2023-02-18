<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username', 30)->unique(); //nickname
            $table->string('kelas', 20)->nullable();
            $table->string('email')->unique();
            $table->string('telepon')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('thumbnail')->nullable();
            $table->boolean('status')->nullable(); //status member
            $table->boolean('kelengkapan')->nullable(); //kelengkapan berkas
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
