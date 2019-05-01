<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;


use Debugbar;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        $login = request()->input('login');
        // 如果使用者輸入信箱格式，則用信箱驗證；反之用學號欄位驗證
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'student_id';

        // 置放請求欄位
        request()->merge([$field => $login]);
        return $field;
    }

    protected function validateLogin(Request $request)
    {
        // 驗證帳號密碼是否有輸入，如果沒有則返回
        $this->validate(
            $request,
            [
                'login' => 'required|string',
                'password' => 'required|string',
            ],
            [
                'login.required' => '請輸入學號或信箱',
                'password.required' => '請輸入密碼',
            ]
        );
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $errors = ['message'=> '帳號或密碼不正確'];

        // Load user from database
        $user = \App\User::where($this->username(), $request->{$this->username()})->first();

        // Check if user was successfully loaded, that the password matches
        // and active is not 1. If so, override the default error message.
        // if ($user && \Hash::check($request->password, $user->password) && $user->active != 1) {
        //     $errors = [$this->username() => trans('auth.notactivated')];
        // }

        if ($request->expectsJson()) {
            return response()->json($errors, 422);
        }
        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors($errors);
    }
}
