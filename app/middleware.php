<?php
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-10 12:08:49
 * @LastEditTime : 2022-12-13 20:29:36
 * @FilePath     : \ioucode_auth\app\middleware.php
 */
// 全局中间件定义文件
return [
    // 全局请求缓存
    // \think\middleware\CheckRequestCache::class,
    // 多语言加载
    \think\middleware\LoadLangPack::class,
    // Session初始化
    \think\middleware\SessionInit::class
];
