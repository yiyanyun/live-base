<?php
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-10 12:10:58
 * @LastEditTime : 2022-12-14 15:14:35
 * @FilePath     : \ioucode_auth\app\index\controller\Index.php
 */
declare (strict_types = 1);

namespace app\index\controller;

use app\BaseController;

class Index extends BaseController
{
    public function index()
    {
        echo '<a href="/admin">主程序</a>';
        return ;
    }

    public function test()
    {
        // $log = $this->request->action();
        // $log = $this->log();
        // return $log;
    }
}
