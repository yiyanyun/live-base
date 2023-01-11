<?php
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-16 10:01:55
 * @LastEditTime : 2022-12-16 11:13:29
 * @FilePath     : \ioucode_auth\app\agent\controller\Card.php
 */
declare (strict_types = 1);

namespace app\agent\controller;

use app\agent\model\Agent;
use app\agent\model\AgentData;
use app\agent\model\Card as ModelCard;
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
        $rows = ModelCard::where('operator',get_id())->select();
        $total = ModelCard::where('operator',get_id())->count();
        $data = [
            'rows'=>$rows,
            'total'=>$total
        ];
        return json($data);
    }

    public function create()
    {
        $pro = AgentData::where('agent_id',get_id())->select();
        return view::fetch('',['pro'=>$pro]);
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
        // 开始判断面值
        if ($in['value'] == 1) {
            $value = '1';
            $type = 'type_a';
        } elseif ($in['value'] == 2) {
            $value = '7';
            $type = 'type_b';
        } elseif ($in['value'] == 3) {
            $value = '30';
            $type = 'type_c';
        } else {
            return showmsg(201, '没有你想要的类型');
        }
        $pro = AgentData::where('agent_id',get_id())->find();
        if (empty($pro)) return showmsg(201,'项目不存在');
        
        $mo = $pro[$type];
        $zong = $mo * $in['number'];
        // 得到总价
        $info = Agent::find(get_id());
        $kouchu = $info['coin'] - $zong;
        if ($kouchu < 0) {
            return showmsg(201,'余额不足');
        }
        for ($i=0; $i < $in['number']; $i++) { 
            $data = [
                'project_id'=>$pro['id'],
                'agent_id'=>get_id(),
                'value'=>$value,
                'operator'  =>  get_id(),
                'card'=>key_code()
            ];
            $res = new ModelCard;
            $res->save($data);
            if (empty($res)) {
                return showmsg(201);
            }
        }
        $user = Agent::find(get_id());
        $user->save(['coin'=>$kouchu]);
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
        $rows = ModelCard::where('operator',get_id())->find($id);
        return showmsg(200,'',$rows);
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
        $rows = ModelCard::where('operator',get_id())->find($id);
        $rows->save([
            'end_time'=>strtotime($request->post('end_time'))
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
        $rows = ModelCard::where('operator',get_id())->find($id);
        $rows->delete();
        return showmsg();
    }
}
