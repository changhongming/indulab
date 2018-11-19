<?php

use App\CsvUpload;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCsvUploadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('csv_upload', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('student_id');
            $table->string('student_number', 10);
            $table->string('name', 20);
            $table->string('experiment', 20);
            $table->text('raw_data');
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
        Schema::dropIfExists('csv_upload');
    }
}
