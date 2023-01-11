<?php
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-10 14:39:40
 * @LastEditTime : 2022-12-10 14:39:54
 * @FilePath     : \ioucode_auth\app\admin\model\Project.php
 */
declare (strict_types = 1);

namespace app\admin\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * @mixin \think\Model
 */
class Project extends Model
{
    use SoftDelete;

    protected $delete_Time = 'delete_time';
}
