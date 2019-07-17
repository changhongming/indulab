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
        try {
            // 確認該使用者是否有管理員權限
            if (!!\Auth::user()->is_admin !== TRUE) {
                abort(403, 'Unauthorized action.');
            }
        }
        // 如果出現例外，則直接擲回沒有權限
        catch(\Exception $e) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
