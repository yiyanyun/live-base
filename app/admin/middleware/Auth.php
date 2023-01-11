<?php
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-16 11:53:03
 * @LastEditTime : 2022-12-16 11:56:28
 * @FilePath     : \ioucode_auth\app\admin\middleware\Auth.php
 */

declare(strict_types=1);

namespace app\admin\middleware;

use think\facade\Session;

class Auth
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        $action = $request->pathInfo();
        if ($action !== 'login') {
            if (empty(Session::get('auth'))) {
                // 判断是否为Ajax
                if (!$request->isAjax()) {
                    redirect('/admin/login')->send();
                    exit;
                }
            }
        }
        return $next($request);
    }
}
