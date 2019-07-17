<?php

use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::transaction(function () {
            DB::table('quiz_db')->insert([
                ['class_text' => '斜坡運動前測'],
                ['class_text' => '斜坡運動後測']
            ]);


            DB::table('quiz_question')->insert([
                ['qd_id' => '1', 'qs_text' => '今有兩台滑車，並且兩台滑車的重量並不相同，請問哪一台滑車<span style="background:yellow;">較快從相同高度的斜面滑下</span>？'],
                ['qd_id' => '1', 'qs_text' => '斜坡運動前測問題1？']
            ]);

            DB::table('quiz_choice')->insert([
                ['qq_id' => '1', 'ans_text' => '重的快'],
                ['qq_id' => '1', 'ans_text' => '輕的快'],
                ['qq_id' => '1', 'ans_text' => '一樣快'],
                ['qq_id' => '2', 'ans_text' => '問題1答案1'],
                ['qq_id' => '2', 'ans_text' => '問題1答案2'],
                ['qq_id' => '2', 'ans_text' => '問題1答案3'],
            ]);


            DB::table('quiz_answer')->insert([
                ['qq_id' => '1', 'qc_id' => '3'],
                ['qq_id' => '1', 'qc_id' => '2']
            ]);

        });


    }
}
