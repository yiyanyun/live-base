<?php
declare (strict_types = 1);

namespace app\admin\controller;

use app\admin\model\Project as ModelProject;
use think\facade\View;
use think\Request;

class Project
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return view::fetch();
    }

    public function res()
    {
        $row = ModelProject::select();
        $total = ModelProject::count();
        $data = [
            'rows'  =>  $row,
            'total' =>  $total
        ];
        return json($data);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $in = input();
        $res = new ModelProject;
        $res->save([
            'name'  =>  $in['name'],
            'key'  =>  key_code()
        ]);
        if (!$res) {
            return showmsg(201);
        }else{
            return showmsg(200);
        }
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        $info = ModelProject::find($id);
        return showmsg(200,'',$info);
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $res = ModelProject::destroy($id);
        if (!$res) {
            return showmsg(201);
        }else{
            return showmsg();
        }
    }

    /**
     * 设备配置
     */
    public function info_a($id)
    {
        $in = input();
        $res = ModelProject::find($id);
        $res->save([
            'clock_vip'=> $in['clock_vip'],
            'login_interval'=>$in['login_interval'],
            'user_mac'=>$in['user_mac'],
            'card_mac'=>$in['card_mac'],
            'status'=>$in['status']
        ]);
        return showmsg();
    }

    public function info_b($id)
    {
        $in = input();
        $res = ModelProject::find($id);
        $res->save([
            'encryption_type'=> $in['encryption_type'],
            'encryption_key'=>$in['encryption_key'],
        ]);
        return showmsg();
    }
}
