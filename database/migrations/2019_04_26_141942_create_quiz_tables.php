<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 創建測驗題庫資料表
        Schema::create('quiz_db', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('class_text',20)->comment('問題表單名稱');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });

        // 創建測驗問題資料表
        Schema::create('quiz_question', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('qd_id')->unsigned();
            $table->foreign('qd_id')->references('id')->on('quiz_db')->onDelete('cascade')->onUpdate('cascade')->comment('對應到quiz_db的id');
            $table->text('qs_text')->comment('問題題目內容');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });

        // 創建測驗問題選項資料表
        Schema::create('quiz_choice', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('qq_id')->unsigned();
            $table->foreign('qq_id')->references('id')->on('quiz_question')->onDelete('cascade')->onUpdate('cascade')->comment('對應到quiz_question的id');
            $table->text('ans_text')->comment('選項答案內容');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });

        // 創建測驗答案資料表
        Schema::create('quiz_answer', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('qq_id')->unsigned();
            $table->integer('qc_id')->unsigned();
            $table->foreign('qq_id')->references('id')->on('quiz_question')->onDelete('cascade')->onUpdate('cascade')->comment('對應到quiz_question的id');
            $table->foreign('qc_id')->references('id')->on('quiz_choice')->onDelete('cascade')->onUpdate('cascade')->comment('對應到quiz_choice的id，表示選項內的正確答案');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quiz_answer');
        Schema::dropIfExists('quiz_choice');
        Schema::dropIfExists('quiz_question');
        Schema::dropIfExists('quiz_db');
    }
}
