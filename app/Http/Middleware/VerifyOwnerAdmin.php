<?php

namespace App\Http\Middleware;

use Closure;
use Debugbar;

class VerifyOwnerAdmin
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

            $user_id = $request->route()->parameters()['user_id'];
            $user = \Auth::user();

            // 如果是管理員或擁有者則繼續處理路由
            if($user->id == $user_id || !!$user->is_admin) {
                return $next($request);
            }
            else {
                // 返回沒有權限
                abort(403, 'Unauthorized action.');
            }

        }
        // 出現例外直接返回沒有權限
        catch(\Exception $e) {
            abort(403, 'Unauthorized action.');
        }

    }
}
