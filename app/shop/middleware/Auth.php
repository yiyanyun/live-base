<?php
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-25 10:59:30
 * @LastEditTime : 2022-12-25 14:13:51
 * @FilePath     : \ioucode_auth\app\shop\middleware\Auth.php
 */

declare(strict_types=1);

namespace app\shop\middleware;

use think\facade\View;

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
        if ($action !== 'auth') {
            // code
            $cloud = send_post('http://127.0.0.1:9000/api/index/check', ['ser_ip' => $_SERVER['REMOTE_ADDR']]);
            $cloud = json_decode($cloud, true);
            if ($cloud['code'] != 200) {
                redirect('/shop/auth')->send();
                exit;
            }
        }
        return $next($request);
    }
}
