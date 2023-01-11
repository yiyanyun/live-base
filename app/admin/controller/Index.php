<?php
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-10 12:10:55
 * @LastEditTime : 2022-12-21 23:35:12
 * @FilePath     : \ioucode_auth\app\admin\controller\Index.php
 */
declare (strict_types = 1);

namespace app\admin\controller;

use app\admin\model\Agent;
use app\admin\model\Card;
use app\admin\model\Project;
use app\admin\model\User;
use think\facade\View;

class Index
{
    public function index()
    {
        // 返回一个通用模板页面，其他将基于模板页面加载框架
        return View::fetch('public/index');
    }

    public function main()
    {
        $user = User::count();
        $pro = Project::count();
        $card = Card::count();
        $agent = Agent::count();
        $user_time = [];
        for ($i=0; $i <= 6; $i++) { 
            $time = $i * 86400;
            $day = time() - $time;
            $start = date("Y-m-d", $day) . ' 00:00:00';
            $end = date("Y-m-d", $day) . '23:59:59';
            $users = User::whereBetweenTime('create_time',$start,$end)->count();
            array_push($user_time,$users);
        }
        $card_time = [];
        for ($i=0; $i <= 6; $i++) { 
            $time = $i * 86400;
            $day = time() - $time;
            $start = date("Y-m-d", $day) . ' 00:00:00';
            $end = date("Y-m-d", $day) . '23:59:59';
            $users = Card::whereBetweenTime('create_time',$start,$end)->count();
            array_push($card_time,$users);
        }
        $data = [
            'user'  =>  $user,
            'project'=>$pro,
            'card' => $card,
            'agent'=>$agent,
            'user_data'=>json_encode($user_time),
            'card_data'=>json_encode($card_time)
        ];
        return view::fetch('',['data'=>$data]);
    }
}
