<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Controllers\Response\JsonMsgResponse;

use App\Slope;
use App\SurveyFeedback;

use Debugbar;

class SlopeController extends Controller
{
    public function postSlope(Request $request) {
        // 檢查Session是否有student_id欄位
        if($request->session()->has('student_id')) {
            // 取得所有請求欄位與資料
            $input = $request->all();

            // 建構一個Slope模型
            $record = new Slope;
            $record->student_id = $request->session()->get('student_id');
            $record->mass = $input['mass'];
            $record->angle = $input['angle'];
            $record->length = $input['length'];

            Debugbar::info($record);

            // 新增到資料庫
            $record->save();

            return JsonMsgResponse::response('ok', 200);

        }
        return JsonMsgResponse::response('the student session is not found', 404);    }

    public function postSimSlopeSurvey(Request $request) {
        // 檢查Session是否有student_id欄位
        if($request->session()->has('student_id')) {
            // 取得所有請求欄位與資料
            $input = $request->all();

            // 取得學生id
            $student_id = $request->session()->get('student_id');

            // 創建一個ORM資料陣列
            $record = array();
            // 將請求得資料進行重組
            foreach ($input as $key => $value) {
                array_push($record, ['survey_id' => $key, 'score' => $value, 'student_id' => $student_id]);
            }

            Debugbar::info($record);

            // 新增到資料庫
            SurveyFeedback::insert($record);

            return response('ok', 200);
        }
        return response('the student session is not find', 404);
    }

    public function getSurveyQuestion(Request $request, $class) {
        if($request->session()->has('student_id')) {
            $db_table = \App\SurveyQuestion::where('class', $class)->select('question', 'number', 'id');
            $survey_data = $db_table->get();
            //$this.unicodeDecode($survey_data);
            Debugbar::info(json_decode($survey_data));
            if($db_table->count() == 0) {
                return JsonMsgResponse::response('the survey class not found', 404);
            }
            // 回傳Json格式的字串給前端，JSON_UNESCAPED_UNICODE為不使用unicode編碼，即是顯示中文字而不是unicode的碼。
            return response(json_encode($survey_data, JSON_UNESCAPED_UNICODE), 200)->header('Content-Type', 'Application/json');
        }
        return JsonMsgResponse::response('the student session is not found', 404);
    }
}

