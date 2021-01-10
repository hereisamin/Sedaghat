<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateToQuizzesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('to_quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('user2_id');
            $table->foreignId('group_id')->nullable();
            $table->boolean('private_type')->default(0);

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('user2_id')->references('id')->on('users');
            $table->foreign('group_id')->references('id')->on('groups');        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('to_quizzes');
    }
}
