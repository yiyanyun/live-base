<?php
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-10 12:10:58
 * @LastEditTime : 2023-01-12 10:00:43
 * @FilePath     : \ioucode_auth\app\index\controller\Index.php
 */
declare (strict_types = 1);

namespace app\index\controller;

use app\BaseController;

class Index extends BaseController
{
    public function index()
    {
        return 'Hello';
        echo '<a href="/admin">主程序</a>';
        return ;
    }
}
