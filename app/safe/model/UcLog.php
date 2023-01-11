<?php
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-11 13:09:53
 * @LastEditTime : 2022-12-11 15:12:10
 * @FilePath     : \ioucode_auth\app\safe\model\UcLog.php
 */
declare (strict_types = 1);

namespace app\safe\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class UcLog extends Model
{
    public function user()
    {
        return $this->hasOne(User::class,'id','res_id');
        // return $this->hasOne(User::class,'res_id','id');
    }
}
