<?php
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-14 20:35:51
 * @LastEditTime : 2022-12-17 11:49:23
 * @FilePath     : \ioucode_auth\app\admin\controller\Agent.php
 */
declare (strict_types = 1);

namespace app\admin\controller;

use app\admin\model\Agent as ModelAgent;
use think\facade\View;
use think\Request;

class Agent
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return View::fetch();
    }

    public function res()
    {
        $row = ModelAgent::select();
        $total = ModelAgent::count();
        $data = [
            'rows'  =>  $row,
            'total' =>  $total
        ];
        return json($data);
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $in = $request->post();
        $data = [
            'username'  =>  $in['username'],
            'password'  =>  $in['password'],
            'coin'      =>  $in['coin']
        ];
        $res = new ModelAgent;
        $res->save($data);
        return showmsg();
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        $res = ModelAgent::find($id);
        return showmsg(200,'',$res);
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        $in = input();
        $data = [
            'coin'  =>  $in['coin'],
            'type_a'=>  $in['type_a'],
            'type_b'=>  $in['type_b'],
            'type_c'=>  $in['type_c'],
        ];
        $res = ModelAgent::find($id);
        $res->save($data);
        return showmsg();
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $res = ModelAgent::find($id);
        $res->delete();
        return showmsg();
    }
}
