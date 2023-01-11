<?php
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-10 14:17:25
 * @LastEditTime : 2022-12-10 14:38:49
 * @FilePath     : \ioucode_auth\app\admin\model\User.php
 */
declare (strict_types = 1);

namespace app\admin\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * @mixin \think\Model
 */
class User extends Model
{
    use SoftDelete;
    protected $delete_Time = 'delete_time';
}
