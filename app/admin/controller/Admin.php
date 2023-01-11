<?php 
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-10 15:17:06
 * @LastEditTime : 2022-12-17 20:07:02
 * @FilePath     : \ioucode_auth\app\admin\controller\Admin.php
 */
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-10 15:17:06
 * @LastEditTime : 2022-12-10 15:17:13
 * @FilePath     : \ioucode_auth\app\admin\controller\Admin.php
 */

namespace app\admin\controller;

use think\facade\View;

class Admin
{
    public function index()
    {
        return View::fetch();
    }
}