<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
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
        // 確認該使用者是否有管理員權限
        if (!!\Auth::user()->is_admin !== TRUE) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
