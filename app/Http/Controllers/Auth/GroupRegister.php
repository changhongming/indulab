<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use DB;
use Debugbar;

class GroupRegister extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function showRegisterView()
    {
        return view('Auth\register-group');
    }

    public function register() {

        //檢查所有欄位是否都有填寫
        $validator = Validator::make(request()->all(), [
            'groupdata' => 'required',
            'school' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator);
        }


        $csvData = request()->groupdata;
        $lines = explode(PHP_EOL, $csvData);
        $array = array();
        foreach ($lines as $line) {
            $array[] = str_getcsv($line);
        }

        Debugbar::info($array);

        DB::beginTransaction();
        try{
            foreach ($array as $user) {
                $validator = Validator::make($user, [
                    0 => 'required|string',
                    1 => 'required|string'
                ]);
                // 單一欄位資料驗證失敗，擲回錯誤
                if($validator->fails()) {
                    throw new \Exception('出現錯誤資料 => '.implode(',', $user));
                }
                error_log(request()->input('school'));
                User::create([
                    'student_id' => $user[0],
                    'school' => request()->input('school'),
                    'name' => $user[1],
                    'password' => Hash::make($user[0]),
                ]);
            }
            DB::commit();
        }
        catch(\PDOException $e) {
            // Debugbar::info('PDOException');
            Debugbar::error($e);
            DB::rollback();
            return redirect(\Request::url())
                            ->withInput()
                            ->withErrors(
                                ['message' => '錯誤：' . $e->errorInfo[1] . ' - ' . $e->errorInfo[2]]
                            );
        }
        catch(\Exception $e) {
            // Debugbar::info('Exception');
            Debugbar::error($e);
            DB::rollback();
            return redirect(\Request::url())
                            ->withInput()
                            ->withErrors(
                                ['message' => '錯誤：' . $e->getMessage()]
                            );
        }

        return redirect($this->redirectTo);
    }
}
