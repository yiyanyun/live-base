<?php
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-10 13:57:31
 * @LastEditTime : 2022-12-23 21:30:33
 * @FilePath     : \ioucode_auth\app\admin\controller\Card.php
 */
declare (strict_types = 1);

namespace app\admin\controller;

use app\admin\model\Card as ModelCard;
use app\admin\model\Project;
use think\facade\View;
use think\Request;

class Card
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
        $row = ModelCard::order('id desc')->select();
        $total = ModelCard::count();
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
        $pro = Project::select();
        $data = [
            'pro'=>$pro
        ];
        return view::fetch('',$data);
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
        for ($i = 1; $i <= $in['number']; $i++) {
            $card = get_code($in['status'],false);
            // 组合数据写入数据库
            $data = [
                'project_id' => $in['project_id'],
                'card' => $card,
                'value' => getTypeValue($in['value']),
                // 'value' => $in['value'],
                'operator' => '管理员',
                'describe'=>$in['describe']
            ];
            $res = new ModelCard;
            $res->save($data);
            if (empty($res)) {
                return showmsg(201, '生成失败');
            }
        }
        return showmsg(200);
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        $info = ModelCard::find($id);
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
        $data = [
            'end_time'=>strtotime($request->post('end_time'))
        ];
        $res = ModelCard::find($id);
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
        $res = ModelCard::find($id);
        $res->delete();
        return showmsg();
    }
}
