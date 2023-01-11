<?php
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-12 15:47:05
 * @LastEditTime : 2022-12-21 23:13:07
 * @FilePath     : \ioucode_auth\app\admin\controller\Login.php
 */

declare(strict_types=1);

namespace app\admin\controller;

use app\admin\model\Admin;
use app\BaseController;
use dh2y\qrcode\QRcode;
use QRcode as GlobalQRcode;
use think\Request;
use think\facade\Session;
use think\facade\View;

class Login extends BaseController
{

    /**
     * 初始化
     */
    public function __construct()
    {
        // 随机返回一串字符串破坏返回内容的长度防止爆破分析
        view::assign('_tokens_', key_code());
        $request = $this->request;
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return View::fetch();
    }

    public function indexs()
    {
        return View::fetch();
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read()
    {
        $in = input();

        $res = Admin::where(['username' => $in['username'], 'password' => $in['password']])->find();
        if (empty($res)) return showmsg(201, '用户名或密码有误');
        $data = [
            'username' => $in['username'],
            'time' => time(),
            'token' => key_code(),
            'code'  =>  200,
            'msg'   =>  '登录成功'
        ];
        Session::set('auth', $data);
        return json(Session::get('auth', $data));
    }

    public function logout()
    {
        Session::clear();
        return redirect('../');
    }

    public function qrcode()
    {
        header("Content-type:image/png");
        require app()->getRootPath()."/vendor/phpqrcode/phpqrcode.php";
        $qRcode = new GlobalQRcode('');
        $data = strval(time());
        // 纠错级别：L、M、Q、H
        $level = 'L';
        // 点的大小：1到10,用于手机端4就可以了
        $size = 4;
        // 生成的文件名
        $qRcode->png($data, false, $level, $size);
        $imagestring = base64_encode(ob_get_contents());
        ob_end_clean();
        return showmsg(200,'获取成功',['img'=>$imagestring]);
    }

    public function qr_check()
    {
        return showmsg(200,'授权成功');
    }
}
