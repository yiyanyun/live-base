<?php
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-16 08:05:37
 * @LastEditTime : 2022-12-16 08:07:19
 * @FilePath     : \ioucode_auth\app\index\model\Card.php
 */
declare (strict_types = 1);

namespace app\index\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class Card extends Model
{
    protected $type = [
        'start_time'        => 'timestamp',
        'end_time'          => 'timestamp'
    ];
}
