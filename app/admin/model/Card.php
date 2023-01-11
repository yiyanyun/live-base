<?php
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-10 15:54:16
 * @LastEditTime : 2022-12-14 20:30:21
 * @FilePath     : \ioucode_auth\app\admin\model\Card.php
 */

declare(strict_types=1);

namespace app\admin\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * @mixin \think\Model
 */
class Card extends Model
{
    use SoftDelete;

    protected $delete_Time = 'delete_Time';

    protected $type = [
        'start_time'        => 'timestamp',
        'end_time'          => 'timestamp'
    ];
}
