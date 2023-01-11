<?php
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-11 12:53:55
 * @LastEditTime : 2022-12-11 12:54:06
 * @FilePath     : \ioucode_auth\app\safe\validate\User.php
 */

declare(strict_types=1);

namespace app\safe\validate;

use think\Validate;

class User extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'username' => 'require|min:5|max:20|alphaNum',
        'password' => 'require|min:5|max:20',
        'useremail' => 'email',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [];
}
