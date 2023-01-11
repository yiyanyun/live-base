<?php 
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-16 10:58:00
 * @LastEditTime : 2022-12-16 11:46:38
 * @FilePath     : \ioucode_auth\app\agent\controller\Login.php
 */

namespace app\agent\controller;

use app\agent\model\Agent;
use think\facade\Session;
use think\facade\View;

class Login 
{
    public function index()
    {
        return View::fetch();
    }

    public function indexs()
    {
        $user = Agent::find(2);
        echo dump($user);
        return Session::set('agent_auth',$user);
    }

    public function check()
    {
        $in = input();
        $user = Agent::where(['username'=>$in['username'],'password'=>$in['password']])->find();
        if (empty($user)) {
            return showmsg(201,'用户名或密码有误');
        }else{
            $data = [
                'code'=>200,
                'msg'=>'成功',
                'id'=>$user['id'],
                'username'=>$user['username']
            ];
            $user->save(['record'=>$user['record']+1]);
            Session::set('agent_auth',$data);
            return Session::get('agent_auth');
        }
    }

    public function logout()
    {
        Session::clear();
        return redirect('../');
    }
}