<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('major_id');
            $table->integer('A1')->default(0);
            $table->integer('A2')->default(0);
            $table->integer('A3')->default(0);
            $table->integer('A4')->default(0);
            $table->integer('A5')->default(0);
            $table->integer('A6')->default(0);
            $table->integer('A7')->default(0);
            $table->integer('A8')->default(0);
            $table->integer('A9')->default(0);
            $table->integer('A10')->default(0);
            $table->integer('A11')->default(0);
            $table->integer('A12')->default(0);
            $table->integer('A13')->default(0);
            $table->integer('A14')->default(0);
            $table->integer('A15')->default(0);
            $table->integer('Point')->default(0);
            $table->timestamp('tanggal');
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
        Schema::dropIfExists('skors');
    }
}
