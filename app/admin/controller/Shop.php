<?php 
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-22 15:48:24
 * @LastEditTime : 2022-12-22 17:48:39
 * @FilePath     : \ioucode_auth\app\admin\controller\Shop.php
 */

namespace app\admin\controller;

use app\admin\model\GoodsApp;
use app\admin\model\GoodsType;
use app\admin\model\Project;
use app\admin\model\Order;
use think\facade\View;

class Shop
{
    public function index()
    {
        $pro = Project::order('id desc')->select();
        return View::fetch('',['pro'=>$pro]);
    }

    public function res()
    {
        $row = GoodsApp::order('id desc')->select();
        $total = GoodsApp::count();
        $data = [
            'rows'  =>  $row,
            'total' =>  $total
        ];
        return json($data);
    }

    public function save()
    {
        $res = new GoodsApp;
        $res->save(['project_id'=>input('project_id')]);
        return showmsg();
    }

    public function read($id)
    {
        $res = GoodsApp::find($id);
        return showmsg(200,'',$res);
    }

    public function update($id)
    {
        $res = GoodsApp::find($id);
        $res->save(['status'=>input('status')]);
        return showmsg();
    }

    public function delete($id)
    {
        $res = GoodsApp::find($id);
        $res->delete();
        return showmsg();
    }

    
    public function type()
    {
        $pro = GoodsApp::order('id desc')->select();
        return view::fetch('',['pro'=>$pro]);
    }

    public function type_save()
    {
        $in = input();
        $res = new GoodsType;
        $res->save([
            'project_id'=>$in['project_id'],
            'type'=>$in['type'],
            'coin'=>$in['coin']
        ]);
        return showmsg();
    }

    public function type_res()
    {
        $row = GoodsType::order('id desc')->select();
        $total = GoodsType::count();
        $data = [
            'rows'  =>  $row,
            'total' =>  $total
        ];
        return json($data);
    }

    public function type_read($id)
    {
        $res = GoodsType::find($id);
        return showmsg(200,'',$res);
    }

    public function type_update($id)
    {
        $in = input();
        $res = GoodsType::find($id);
        $res->save([
            'name'=>$in['name'],
            'explain'=>$in['explain'],
            'type'=>$in['type'],
            'coin'=>$in['coin'],
            'status'=>$in['status']
        ]);
        return showmsg();
    }

    public function type_delete($id)
    {
        $res = GoodsType::find($id);
        $res->delete();
        return showmsg();
    }

    public function order()
    {
        return view::fetch();
    }

    public function order_res()
    {
        $row = Order::order('id desc')->select();
        $total = Order::count();
        $data = [
            'rows'  =>  $row,
            'total' =>  $total
        ];
        return json($data);
    }
}
