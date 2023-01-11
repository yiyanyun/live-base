<?php
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-11 11:24:37
 * @LastEditTime : 2022-12-11 13:12:55
 * @FilePath     : \ioucode_auth\app\safe\route\app.php
 */

use think\facade\Route;

Route::any('/:id/:action', 'index/index');
