<?php namespace App\Http\Controllers;

use Validator;
use App\Student;
use App\UploadData;
use App\Modeling;
use App\Experiment;
use App\ModelingLabel;
use App\CsvUpload;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Debugbar;

class ContestController extends Controller {

    //遇到error顯示session中error訊息
    public function getError(Request $request)
    {
        if ($request->session()->has('error_message')) {
            return view('contest.contest-error')->with('error_message', $request->session()->get('error_message'));
        } else {
            return redirect('/');
        }
    }

    //進入填資料的頁面
    public function getRule()
    {
        //從資料庫取得建立的實驗名稱
        $tables = Experiment::All();
        $experiment_name = collect();
        //下拉選單顯示資料庫內有的實驗
        foreach ($tables as $experiment_table) {
           $experiment_name->push($experiment_table->experiment);
        }
        //debug用
        Debugbar::info($experiment_name->all());
        //顯示contest.contest-rule.blade.php的頁面
        return view('contest.contest-rule')->with('experiments', $experiment_name);
    }

    //提交填資料的頁面（點選開始建模按鈕）
    public function postRule(Request $request) {

        //取得學生輸入的學號
        $student_number = $request->input('id');

        //檢查所有欄位是否都有填寫
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'id' => 'required',
            'school' => 'required',
            'experiment' => 'required',
        ]);

        //如果檢查沒過發出提醒
        if ($validator->fails()) {
            // redirect our user back to the form with the errors from the validator
            return redirect('/')
                ->withInput()
                ->withErrors($validator);
        } else {
            //從資料庫取得Student項目的資料
            $records = Student::where('student_number', $student_number);
            //檢查Student項目是否有該學生的資料，沒有的話宣告record儲存Student module的資料（Student在/app裡建立）
            //儲存的內容設定要在/database/migration裡設定
            $record = ($records->count() > 0)? $records->first(): new Student;
            //record儲存學生的學號、姓名、學校、ip位址
            $record->student_number = $student_number;
            $record->name = $request->input('name');
            $record->school = $request->input('school');
            $record->ip = implode(",", $request->ips());
            //將record的內容儲存到資料庫裡
            $record->save();
            //取得一個token，之後的建模過程都認這個token
            $old_token = $request->session()->get('_token');
            $request->session()->flush();
            //將token、學號、名字、實驗項目、學號儲存到session
            $request->session()->put('_token', $old_token);
            $request->session()->put('student_number', $student_number);
            $request->session()->put('name', $request->input('name'));
            $request->session()->put('experiment', $request->input('experiment'));
            $request->session()->put('student_id', $record->id);
            //導向import，如果要改變import設定要在/app/Http/routes.php設定
            return redirect('import');
        }
    }

    //進入上傳資料的頁面
    public function getImport()
    {
        //顯示contest.contest-import.blade.php的頁面
        return view('contest.contest-import');
    }

    //提交上傳資料的頁面（點選上傳資料按鈕）
    public function postImport(Request $request) {
        //判斷session裡面是否有學生資料，沒有的話顯示錯誤
        if ($request->session()->has('student_number')) {
            $student_number = $request->session()->get('student_number');
        } else {
            $message = "請先填寫資料。再開始建模";
            $request->session()->put('error_message', $message);
            return redirect('error');
        }

        //建立data儲存上傳檔案裡面的資料，上傳功能的程式碼在contest-import.blade.php
        $data['data'] = $request->input('data');

        $csv_obj = json_decode($request->input('data'), true);
       
        $record = new CsvUpload;
        // 如果資料庫沒有此欄位，則產生欄位並將值初始為0
        Schema::table($record->getTable() , function (Blueprint $table) use ($csv_obj, $record) {
            // 取得資料庫現有欄位名稱
            $table_col = Schema::getColumnListing($record->getTable());
            $table_col = array_flip($table_col);
            foreach($csv_obj as $col) {
                $tmp = $col['title'].'('.$col['unit'].')';
                if(!isset($table_col[$tmp])) {
                    $table->boolean($tmp)->default(0);
                }
            }
        });
        // 如果CSV檔內有此欄位則將值設定為1
        foreach($csv_obj as $col) {
            $tmp = $col['title'].'('.$col['unit'].')';
            $record->$tmp = 1;
        }
        $record->student_id = $request->session()->get('student_id');
        $record->student_number = $request->session()->get('student_number');
        $record->name = $request->session()->get('name');
        $record->experiment = $request->session()->get('experiment');
        $record->raw_data = $request->input('data');
        $record->save();

        //將數據儲存到session、這樣重新整理數據才不會消失
        $request->session()->put('upload_data_id', $record->id);
        $request->session()->put('data', $data['data']);
        //導向draw，如果要改變draw設定要在/app/Http/routes.php設定
        return redirect('draw');
    }


    //進入建模頁面
    public function getDraw(Request $request)
    {
        $request->session()->reflash();
        //建立data儲存上傳的實驗資料
        $data = array();
        $data['time'] = array();
        $data['value'] = array();
        
        $data['time'] = $request->session()->get('data_time');
        $data['value'] = $request->session()->get('data_value');
        $data['data'] = $request->session()->get('data');
        //從資料庫取得實驗的單位
        $units = Experiment::where('experiment', $request->session()->get('experiment'))->first();
        $data['units'] = $units;
        //顯示contest.contest-draw.blade.php的頁面
        return view('contest.contest-draw', $data);
    }




    //提交建模頁面
    public function postDraw(Request $request) {
        $request->session()->reflash();
        //宣告record儲存Modeling module的資料（Modeling在/app裡建立）
        $record = new Modeling;
        //record儲存id、學號、姓名、實驗，id用來比對上傳的資料跟建模歷程
        $record->student_id = $request->session()->get('student_id');
        $record->upload_data_id = $request->session()->get('upload_data_id');
        $record->student_number = $request->session()->get('student_number');
        $record->name = $request->session()->get('name');
        $record->experiment = $request->session()->get('experiment');
        $record->xLabel = $request->input('xLabel');
        $record->xUnit = $request->input('xUnit');
        $record->yLabel = $request->input('yLabel');
        $record->yUnit = $request->input('yUnit');
        //如果提交時final = 1，結果儲存到final_formula and error
        if ($request->input('final') == 1) {
            $record->final_formula = $request->input('formula');
            $record->final_error = $request->input('error');
            $record->save();
            return 'success';
        }
        //如果提交時final ！= 1，結果儲存到formula and error
        else {
            $record->formula = $request->input('formula');
            $record->error = $request->input('error');
            $record->save();
            Debugbar::info($request);
            return 'success';
        }
    }

    // 提交切換XY軸內容
    public function postLabel(Request $request) {
        $request->session()->reflash();
        //宣告record儲存Modeling module的資料（Modeling在/app裡建立）
        $record = new ModelingLabel;
        //record儲存id、學號、姓名、實驗，id用來比對上傳的資料跟建模歷程
        $record->student_id = $request->session()->get('student_id');
        $record->upload_data_id = $request->session()->get('upload_data_id');
        $record->student_number = $request->session()->get('student_number');
        $record->name = $request->session()->get('name');
        $record->experiment = $request->session()->get('experiment');
        $record->xLabel = $request->input('xLabel');
        $record->xUnit = $request->input('xUnit');
        $record->yLabel = $request->input('yLabel');
        $record->yUnit = $request->input('yUnit');
        $record->save();
    }
}
