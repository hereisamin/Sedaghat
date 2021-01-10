<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizzesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenge_id');
            $table->foreignId('question_id');
            $table->boolean('answer1')->nullable();
            $table->boolean('answer2')->nullable();
            $table->integer('likes1')->default(0);
            $table->integer('likes2')->default(0);
            $table->timestamps();

            $table->foreign('challenge_id')->references('id')->on('challenges');
            $table->foreign('question_id')->references('id')->on('questions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quizzes');
    }
}
