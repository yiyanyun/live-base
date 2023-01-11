<?php
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-10 15:18:18
 * @LastEditTime : 2022-12-26 14:40:11
 * @FilePath     : \ioucode_auth\app\admin\controller\System.php
 */
declare (strict_types = 1);

namespace app\admin\controller;

use app\admin\model\ActionLog;
use app\admin\model\Admin;
use app\admin\model\System as ModelSystem;
use app\BaseController;
use think\facade\View;
use think\Request;
use think\service\ModelService;

class System extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $res = Admin::find(1);
        return View::fetch('',['data'=>$res]);
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update()
    {
        $input = input();
        $old = Admin::where('id', 1)->find();
        if ($input['old_password'] != $old['password']) return showmsg(201, "原始密码不正确");
        if ($input['old_password'] == $input['confirm_password']) return showmsg(201, "不能与原密码一致");
        if ($input['new_password'] != $input['confirm_password']) return showmsg(201, "二次密码与一次密码不正确");
        if (empty($input['confirm_password'])) return showmsg(201, "密码不能为空");
        $data = [
            'username'     =>      $input['username'],
            'password'  =>      $input['new_password']
        ];
        $res = Admin::find(1);
        $res->save($data);
        if ($res) {
            return showmsg(200, "修改成功");
        } else {
            return showmsg(201, "修改失败");
        }
    }

    public function log()
    {
        return view::fetch();
    }

    public function res()
    {
        $row = ActionLog::order('id desc')->select();
        $total = ActionLog::count();
        $data = [
            'rows'  =>  $row,
            'total' =>  $total
        ];
        return json($data);
    }

    public function recovery()
    {
        return view::fetch();
    }

    public function pay()
    {
        $sys = ModelSystem::find(1);
        return view::fetch('',['data'=>$sys]);
    }

    public function pay_update()
    {
        $in = input();
        $sys = ModelSystem::find(1);
        $sys->save([
            'alipay_appid'=>$in['alipay_appid'],
            'alipay_public_key'=>$in['alipay_public_key'],
            'alipay_private_key'=>$in['alipay_private_key'],
            'alipay_sgintype'=>$in['alipay_sgintype']
        ]);
        return showmsg();
    }
}
