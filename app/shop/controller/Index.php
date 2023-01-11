<?php
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-22 00:10:17
 * @LastEditTime : 2022-12-25 21:40:13
 * @FilePath     : \ioucode_auth\app\shop\controller\Index.php
 */
declare (strict_types = 1);

namespace app\shop\controller;

use app\admin\model\GoodsType;
use app\shop\model\GoodsApp;
use app\shop\model\Order;
use app\shop\model\System;
use think\facade\View;

class Index
{
    public function index()
    {
        $system = System::find(1);
        $app = GoodsApp::where('status','y')->select();
        $data = [
            'app'=>$app,
            'system'=>$system
        ];
        return view::fetch('',['data'=>$data]);
        // $app = GoodsApp::where('status','y')->select();
        // // 查询支持网上购卡的项目
        // return $app;
        // return view::fetch();
    }

    /**
     * 获取商品
     */
    public function getType()
    {
        $in = input();
        $select = '';
        $type = GoodsType::where('goods_app_id',$in['tyid'])->select();
        foreach ($type as $key) {
            $select .= '<option value="'.$key['id'].'">'.$key['name'].'</option>';
        }
        return showmsg(200,$select);
    }

    public function submit()
    {
        $in = input();
        $order = new Order;
        $order->save([
            'order_id'=>get_order(),
            'project_id'=>1,
            // 'project_id'=>$in['project_id'],
            'coin'=>'0.01',
            'ip'=>getip(),
        ]);
        return;
    }

    public function find_order()
    {
        $or = Order::where('order_id',input('find_order'))->find();
        if (empty($or)) {
            return showmsg(201,'订单不存在');
        }
        return showmsg(200,'查询成功',$or);
    }

    public function pay_buy()
    {
        $in = input();
        $id = input('goodstype');
        $goods = GoodsType::find($id);
        $orders = get_order();
        $order = new Order;
        $data = [
            'order_id'=>$orders,
            'project_id'=>$goods['project_id'],
            'coin'=>$goods['coin'],
            'ip'=>getip(),
            'mail'=>$in['mail'],
            'goods_type_id'=>$goods['id']
        ];
        $order->save($data);
        return showmsg(200,'',['order'=>$order]);
    }
}
