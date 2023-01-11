<?php
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-16 11:44:24
 * @LastEditTime : 2022-12-16 11:45:59
 * @FilePath     : \ioucode_auth\app\agent\middleware\Auth.php
 */
declare (strict_types = 1);

namespace app\agent\middleware;

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
        if ($action !== 'login' && $action !== 'lock') {
            if (empty(Session::get('agent_auth'))) {
                // 判断是否为Ajax
                if (!$request->isAjax()) {
                    redirect('/agent/login')->send();
                    exit;
                }
            }
        }
        return $next($request);
    }
}
