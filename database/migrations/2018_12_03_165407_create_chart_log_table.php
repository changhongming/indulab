<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChartLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chart_log', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('student_id');
            $table->integer('upload_data_id');
            $table->string('student_number', 10);
            $table->string('name', 20);
            $table->string('experiment', 100);
            // 定義一個10進制的欄位，並且整數最多能表示9個位數，小數點能表示4個位數
            $table->decimal('x_pre',8,4)->nullable();
            $table->decimal('y_pre',8,4)->nullable();
            $table->decimal('x_exp',8,4)->nullable();
            $table->decimal('y_exp',8,4)->nullable();
            $table->string('event',10)->nullable();
            $table->timestamp('record_at')->nullable();
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
        Schema::dropIfExists('chart_log');
    }
}
