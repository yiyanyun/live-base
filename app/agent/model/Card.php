<?php
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-16 09:59:21
 * @LastEditTime : 2022-12-16 11:48:35
 * @FilePath     : \ioucode_auth\app\agent\model\Card.php
 */
declare (strict_types = 1);

namespace app\agent\model;

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
