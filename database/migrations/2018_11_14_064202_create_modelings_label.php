<?php

use App\ModelingLabel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModelingsLabel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('modelings_label')) {
            Schema::create('modeling_label', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id')->unsigned();
                $table->integer('student_id');
                $table->integer('upload_data_id');
                $table->string('student_number', 10);
                $table->string('name', 20);
                $table->string('experiment', 100);
                $table->string('xLabel',10)->default('NULL')->nullable();
                $table->string('xUnit',10)->default('NULL')->nullable();
                $table->string('yLabel',10)->default('NULL')->nullable();
                $table->string('yUnit',10)->default('NULL')->nullable();
                $table->char('changeAxis',1)->default('')->nullable();
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modeling_label');
    }
}
