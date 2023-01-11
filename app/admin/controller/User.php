<?php
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-10 13:51:22
 * @LastEditTime : 2022-12-17 11:18:57
 * @FilePath     : \ioucode_auth\app\admin\controller\User.php
 */
declare (strict_types = 1);

namespace app\admin\controller;

use app\admin\model\User as ModelUser;
use think\facade\View;
use think\Request;

class User
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
        $row = ModelUser::order('id desc')->select();
        $total = ModelUser::count();
        $data = [
            'rows'  =>  $row,
            'total' =>  $total
        ];
        return json($data);
    }

    /**
     * 显示创建用户统计页.
     *
     * @return \think\Response
     */
    public function analysis()
    {
        $input = input();
        return view::fetch();
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        $info = ModelUser::find($id);
        return showmsg(200,'',$info);
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
        $res = ModelUser::find($id);
        $res->save([
            'vip_time' => strtotime($in['vip_time'])
        ]);
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
        $res = ModelUser::find($id);
        $res->delete();
        return showmsg();
    }
}
