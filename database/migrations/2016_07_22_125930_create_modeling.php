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
            $table->string('formula',100)->default('');
            $table->string('error',10)->default('');
            $table->string('final_formula',100)->default('');
            $table->string('final_error',10)->default('');
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
        Schema::drop('modelings');//
    }
}
