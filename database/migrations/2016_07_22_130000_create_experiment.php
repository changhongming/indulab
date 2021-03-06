<?php

use App\Experiment;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExperiment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('experiments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('experiment', 20);
            $table->string('x_unit', 20);
            $table->string('y_unit', 20);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });

        $data = array(
            array('experiment'=>'單擺運動實驗', 'x_unit'=> '時間 t (ms)', 'y_unit'=> '擺長 L (cm)'),
            array('experiment'=>'自由落體實驗', 'x_unit'=> '時間 t (ms)', 'y_unit'=> '擺長 L (cm)'),
            array('experiment'=>'RC直流暫態實驗', 'x_unit'=> '時間 t (ms)', 'y_unit'=> '電壓 V (V)'),
            array('experiment'=>'斜坡運動實驗', 'x_unit'=> '時間 t (ms)', 'y_unit'=> '擺長 L (cm)'),
            array('experiment'=>'拋體運動實驗', 'x_unit'=> '時間 t (ms)', 'y_unit'=> '擺長 L (cm)'),
            array('experiment'=>'飛上天空觀測氣象', 'x_unit'=> '高度 h (m)', 'y_unit'=> '時間 t (ms)'),
            array('experiment'=>'火車的行進', 'x_unit'=> '加速度 a (m^2/s)', 'y_unit'=> '位置 p (m)'),
            array('experiment'=>'從鷹架掉落的磚頭', 'x_unit'=> '加速度 a (m^2/s)', 'y_unit'=> '位置 p (m)'),
        );
        DB::table('experiments')->insert($data);
    }


    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('experiments');
    }
}
