<?php
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-14 15:07:27
 * @LastEditTime : 2022-12-14 17:20:12
 * @FilePath     : \ioucode_auth\app\admin\middleware\Lgo.php
 */
declare (strict_types = 1);

namespace app\admin\middleware;

use app\admin\model\ActionLog;

class Lgo
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
        /**
         * 使用后置中间件记录操作日志
         */
        $response = $next($request);
        $controller =$request->controller();
        $action = $request->action();
        $log = $controller.'/'.$action;
        $ac = new ActionLog;
        $ac->save([
            'username' => get_auth(),
            'ip' => getip(),
            'action' => $log
        ]);
        return $response;
    }
}
