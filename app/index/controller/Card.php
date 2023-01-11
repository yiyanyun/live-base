<?php
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-13 20:01:13
 * @LastEditTime : 2022-12-16 08:05:08
 * @FilePath     : \ioucode_auth\app\index\controller\Card.php
 */


namespace app\index\controller;

use app\BaseController;
use app\index\model\Card as ModelCard;
use think\facade\View;

class Card extends BaseController
{
    public function index()
    {
        return View::fetch();
    }

    public function find()
    {
        $in = input();
        $this->validate($in['code'], [
            'captcha' => 'require|captcha'
        ]);
        $res = ModelCard::where('card', $in['card'])->find();
        $data = [
            'data' => $res
        ];
        return showmsg(200, '', $data);
    }
}
