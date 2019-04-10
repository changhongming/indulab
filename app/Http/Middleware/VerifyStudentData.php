<?php

namespace App\Http\Middleware;

use Closure;
class VerifyStudentData
{
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
        // 取得是否有個人資料，如果沒有的話則重定向到輸入個人資料頁面
        if(empty($studentId) && $method == 'GET' && $uri != '/') {
            return redirect('/')->with('error-msg', '請先輸入個人資料後再繼續')->with('req-uri', $uri);
        }

        // 轉移到下一個路由
        return $next($request);
    }
}
