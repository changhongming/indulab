<?php

namespace App\Http\Middleware;

use Closure;
use Debugbar;
use Illuminate\Support\Facades\Auth;

class VerifyStudentProfileSession
{
    private $white_list_uri = ['/', '/login', '/password/reset'];
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $studentId = $request->session()->get('student_id');
        $uri = $request->getRequestUri();
        $method = $request->getMethod();

        if(Auth::check()) {
            $user = Auth::user();
            $old_token = $request->session()->get('_token');
            $request->session()->put('_token', $old_token);
            $request->session()->put('student_number', $user->email);
            $request->session()->put('name', $user->name);
            return $next($request);
        }
        // 取得是否有個人資料，如果沒有的話則重定向到輸入個人資料頁面
        if(empty($studentId) && $method == 'GET' && !in_array($uri, $this->white_list_uri)) {
            return redirect('/')->with('error-msg', '請先輸入個人資料後再繼續')->with('req-uri', $uri);
        }

        // 轉移到下一個路由
        return $next($request);
    }
}
