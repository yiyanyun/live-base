<?php
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-10 15:59:12
 * @LastEditTime : 2022-12-14 20:42:21
 * @FilePath     : \ioucode_auth\app\admin\model\Agent.php
 */
declare (strict_types = 1);

namespace app\admin\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * @mixin \think\Model
 */
class Agent extends Model
{
    use SoftDelete;

    protected $delete_Time = 'delete_Time';
}
