<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Debugbar;

use App\Http\Controllers\Response\JsonMsgResponse;
use App\QuizQuestion;

class QuizEditorContorller extends Controller {

    public function __construct()
    {
        $this->middleware('admin');
    }

    public function postQuestion(Request $request) {
        Debugbar::info($request->all());
        $data = $request->all();

        // 有傳入id，表示修改
        if($data['id']) {
            $record = QuizQuestion::find($data['id']);
        }
        // 表示新增
        else{
            Debugbar::info('no id');
            $record = new QuizQuestion;
        }

        $record->question = $data['question'];
        $record->choices = $data['choices'];
        $record->answer_id = $data['answer_id'];
        $record->qt_id = 1;
        $record->order = $data['order'];
        // 新增到資料庫
        $record->save();

        return JsonMsgResponse::response('ok', 200);
    }

    public function getQuestions() {
        $rows = QuizQuestion::where('qt_id', 1)->select('id', 'answer_id', 'question', 'choices', 'order')->get();
        Debugbar::info($rows);
        return response()->json($rows, 200);
    }

}
