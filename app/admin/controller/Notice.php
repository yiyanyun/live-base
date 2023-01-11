<?php
declare (strict_types = 1);

namespace app\admin\controller;

use app\admin\model\Notice as ModelNotice;
use app\admin\model\Project;
use think\facade\View;
use think\Request;

class Notice
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $pro = Project::select();
        $data = [
            'pro'=>$pro
        ];
        return View::fetch('',$data);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function res()
    {
        $row = ModelNotice::select();
        $total = ModelNotice::count();
        $pro = Project::select();
        $data = [
            'rows'  =>  $row,
            'total' =>  $total,
            'pro'=>$pro
        ];
        return json($data);
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save()
    {
        $in = input();
        $res = new ModelNotice;
        $res->save([
            'project_id'    =>  $in['project_id'],
            'title'         =>  $in['title'],
            'content'       =>  $in['content'],
            'author'        =>  '管理员'
        ]);
        if (!$res) {
            return showmsg(201);
        }else{
            return showmsg();
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
        $info = ModelNotice::find($id);
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
        $res = [
            'title'=>$in['title'],
            'content'=>$in['content']
        ];
        $row = ModelNotice::find($id);
        $row->save($res);
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
        $res = ModelNotice::find($id);
        $res->delete();
        return showmsg();
    }
}
