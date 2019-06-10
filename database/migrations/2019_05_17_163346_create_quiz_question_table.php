<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('qt_id')->unsigned();
            $table->foreign('qt_id')->references('id')->on('quiz_test')->onDelete('cascade')->onUpdate('cascade')->comment('對應到quiz_test的id');
            $table->uuid('answer');
            $table->longText('question');
            $table->longText('choices');
            $table->integer('order')->unsigned();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quiz_questions');
    }
}
