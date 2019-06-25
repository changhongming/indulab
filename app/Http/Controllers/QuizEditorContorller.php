<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Debugbar;

use App\Http\Controllers\Response\JsonMsgResponse;
use App\QuizQuestion;
use App\QuizTest;

class QuizEditorContorller extends Controller {

    public function __construct()
    {
        $this->middleware('admin');
    }

    public function postQuestion(Request $request) {
        Debugbar::info($request->all());
        $data = $request->all();

        // 有傳入id，表示修改
        if(array_key_exists('id', $data)) {
            $record = QuizQuestion::find($data['id']);
        }
        // 表示新增
        else{
            Debugbar::info('no id');
            $record = new QuizQuestion;
        }

        $record->question = $data['question'];
        $record->choices = $data['choices'];
        $record->answer = $data['answer'];
        $record->qt_id = $data['qid'];
        $record->order = $data['order'];
        $record->wronganswer = $data['wronganswer'];
        // 新增到資料庫
        $record->save();
        Debugbar::info($record);
        return response()->json(['id' => $record->id], 200);
    }

    public function getQuestionEditorView(Request $request) {
        $req_id = $request->query('id');
        if(!$req_id) {
            return JsonMsgResponse::response('Need to provide query id value', 400);
        }
        return view('quiz.edit')->with('id', $req_id);
    }

    public function getQuestions(Request $request) {
        $req_id = $request->query('id');
        if(!$req_id) {
            return JsonMsgResponse::response('Need to provide query id value', 400);
        }
        $rows = QuizQuestion::where('qt_id', $req_id)->select('id', 'answer', 'question', 'choices', 'order', 'wronganswer')->get();
        Debugbar::info($rows);
        return response()->json($rows, 200);
    }

    public function getQuizes() {
        $rows = QuizTest::get(['id', 'test_name', 'test_time']);

        Debugbar::info($rows);
        return view('quiz.index')->with('quizs', $rows);
        // return response()->json($rows, 200);
    }

    public function deleteQuestion($id) {
        Debugbar::info($id);
        $question = QuizQuestion::find($id);

        // 刪除使用者
        $question->delete();

        Debugbar::info($question);

        return JsonMsgResponse::response('ok', 200);
    }

    public function getQuestionCreateView() {
        return view('quiz.create');
    }

    public function postTest(Request $request) {
        $data = $request->all();
        $record = new QuizTest;
        $record->test_name = $data['test_name'];
        $record->test_time = $data['test_time'];
        $record->save();
        return $this->getQuizes();
    }

    public function deleteTest($id) {
        Debugbar::info($id);
        $quiz = QuizTest::find($id);

        // 刪除使用者
        $quiz->delete();

        Debugbar::info($quiz);

        return $this->getQuizes();
    }

    public function changeTest(Request $request) {
        $data = $request->all();
        Debugbar::info($data);
        $record = QuizTest::find($data['id']);
        $record->test_name = $data['test_name'];
        $record->test_time = $data['test_time'];
        $record->save();
        return $this->getQuizes();
    }
    public function getEditTestView($id) {
        $row = QuizTest::find($id);

        Debugbar::info($row);
        return view('quiz.edit_quiz')->with('quiz', $row);
    }
}
