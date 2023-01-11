<?php 
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-12 19:43:45
 * @LastEditTime : 2023-01-11 20:31:55
 * @FilePath     : \ioucode_auth\app\admin\controller\Cloud.php
 */

namespace app\admin\controller;

use think\facade\View;

class Cloud 
{
    public function index()
    {
        $res = send_post(config('system.host').'api/news',[]);
        if(empty($res)) return '云端连接失败';
        $res = json_decode($res,true);
        return View::fetch('',['new'=>$res]);
    }

    public function up()
    {
        return '请前往云端了解详情信息！！~';
    }
}