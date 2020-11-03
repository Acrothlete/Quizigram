<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->integer('test_id');
            $table->string('test_name');
            $table->unsignedBigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');            $table->integer('obtained_marks');
            $table->integer('total_marks');
            $table->tinyInteger('obtained_percentage');
            $table->string('result');
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
        Schema::dropIfExists('results');
    }
}
