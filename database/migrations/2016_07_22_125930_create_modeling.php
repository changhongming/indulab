<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModeling extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        Schema::create('modelings', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('student_id');
            $table->integer('upload_data_id');
            $table->string('student_number', 10);
            $table->string('name', 20);
            $table->string('experiment', 100);
            $table->text('formula');
            $table->text('error');
            $table->text('final_formula');
            $table->text('final_error');
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
        Schema::drop('modelings');//
    }
}
