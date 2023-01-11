<?php
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-10 12:08:49
 * @LastEditTime : 2022-12-18 10:51:53
 * @FilePath     : \ioucode_auth\config\session.php
 */
// +----------------------------------------------------------------------
// | 会话设置
// +----------------------------------------------------------------------

return [
    // session name
    'name'           => 'PHPSESSID',
    // SESSION_ID的提交变量,解决flash上传跨域
    'var_session_id' => '',
    // 驱动方式 支持file cache
    'type'           => 'file',
    // 存储连接标识 当type使用cache的时候有效
    'store'          => null,
    // 过期时间
    'expire'         => 86400,//让其session保护时间更长【登录有效时间】
    // 'expire'         => 1440,
    // 前缀
    'prefix'         => '',
];
