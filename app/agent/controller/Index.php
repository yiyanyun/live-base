<?php
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-14 20:34:55
 * @LastEditTime : 2022-12-16 10:56:58
 * @FilePath     : \ioucode_auth\app\agent\controller\Index.php
 */
declare (strict_types = 1);

namespace app\agent\controller;

use app\agent\model\Agent;
use app\agent\model\AgentData;
use app\agent\model\Card;
use think\facade\View;

class Index
{
    public function index()
    {
        return View::fetch('public/index');
    }

    public function main()
    {
        $pro = AgentData::count();
        $card = Card::count();
        $info = Agent::find(get_id());
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
            'project'=>$pro,
            'card' => $card,
            'card_data'=>json_encode($card_time),
            'info'=>$info
        ];
        return view::fetch('',['data'=>$data]);
    }
}
