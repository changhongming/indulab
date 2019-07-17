<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use DB;
use Debugbar;

class ChangePasswordController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showChangePasswordView()
    {
        return view('auth.change-pssword');
    }

    public function changePassword() {

        //檢查所有欄位是否都有填寫
        $validator = Validator::make(request()->all(), [
            'current_password' => 'current_password',
            'new_password' => 'required|confirmed|min:4',
            'new_password_confirmation' => 'required|min:4'
        ]);

        // 驗證失敗
        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors($validator);
        }

        // 修改新密碼到資料庫
        $user = \Auth::user();
        $user->password = Hash::make(request()->input('new_password'));
        $user->save();

        return redirect()
                ->back()
                ->with(['success' => '修改密碼成功!']);
    }
}
