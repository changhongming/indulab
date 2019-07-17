<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMicroworldToSurveyQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 新增問卷題目資料
        $data = array(
            array('class'=>'simslope', 'number'=> '1', 'question'=> '這個學習系統可以幫助我學習實作實驗時的技巧與方法。'),
            array('class'=>'simslope', 'number'=> '2', 'question'=> '這個學習系統可以提升我在實作實驗時的學習效率。'),
            array('class'=>'simslope', 'number'=> '3', 'question'=> '這個學習系統可以幫助我更容易建置實作實驗的環境。'),
            array('class'=>'simslope', 'number'=> '4', 'question'=> '這個學習系統可以幫助我更快建置實作實驗的環境。'),
            array('class'=>'simslope', 'number'=> '5', 'question'=> '這個學習系統可以幫助我更容易了解實驗的變項關係。'),
            array('class'=>'simslope', 'number'=> '6', 'question'=> '整體而言，這個學習系統可以幫助我更了解實作實驗。'),
            array('class'=>'simslope', 'number'=> '7', 'question'=> '這個學習系統所提供的功能，很容易完成想要做的事。'),
            array('class'=>'simslope', 'number'=> '8', 'question'=> '這個學習系統所提供的功能，很容易使用。'),
            array('class'=>'simslope', 'number'=> '9', 'question'=> '我覺得這是一個聰明的數位學習系統。'),
            array('class'=>'simslope', 'number'=> '10', 'question'=> '我覺得這是一個吸引人的數位學習系統。'),
            array('class'=>'simslope', 'number'=> '11', 'question'=> '我覺得這是一個用起來令人愉快的數位學習系統。'),
            array('class'=>'simslope', 'number'=> '12', 'question'=> '如果有機會，我希望能經常使用這個學習系統。'),
            array('class'=>'simslope', 'number'=> '13', 'question'=> '如果有機會，我樂於使用這個學習系統。'),
        );
        DB::table('survey_question')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 刪除本次遷移問卷題目資料
        $class = 'simslope';
        $numbers = array(
            '1','2','3','4','5','6','7','8','9','10','11','12','13'
        );
        DB::table('survey_question')->where('class', $class)->whereIn('number', $numbers)->delete();
    }
}
