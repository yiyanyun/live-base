<?php
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-10 12:08:49
 * @LastEditTime : 2022-12-27 22:23:09
 * @FilePath     : \ioucode_auth\app\common.php
 */
// 应用公共文件

use app\shop\model\System;
use think\facade\Session;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * 公用的方法  返回json数据，进行信息的提示
 * @param int $code
 * @param null $message 提示信息
 * @param array $data 返回数据
 */
function showmsg($code = 200, $message = null, $data = array())
{
    header('Content-type: application/json');
    if ($code == 201) {
        if ($message == null) {
            $message = "操作失败";
        }
    } elseif ($code == 200) {
        if ($message == null) {
            $message = '操作成功';
        }
    }
    $result = array(
        'code' => $code,
        'msg' => $message,
        'data' => $data,
        'time' => time()
    );
    echo json_encode($result);
    exit;
}

/**
 * @method s(int $code,array $pro,string $msg,array $data = []) 接口返回信息封装方法
 */
function s($code = 200, $pro = null, $msg = null, $data = null)
{
    header('Content-type: application/json');
    if (empty($msg)) {
        $msg = '成功';
    }
    $res = [
        'code' => $code,
        'msg' => $msg,
        'data' => $data,
        'time' => time()
    ];
    // 预编译
    $res = json_encode($res);
    $sgin = md5($res);
    // 取得校验值后再次编译
    $res = [
        'code' => $code,
        'msg' => $msg,
        'data' => $data,
        'time' => time(),
        'sgin' => $sgin
    ];
    $res = json_encode($res);
    if ($pro['encryption_type'] == 1) {
        $result = base64_encode($res);
    } elseif ($pro['encryption_type'] == 2) {
        $result = openssl_encrypt($res, 'AES-128-ECB', $pro['encryption_key'], 0);
    } else {
        $result = $res;
    }
    return $result;
}

/**
 * 获取一串字符串
 * @return string
 */
function key_code($strs = true)
{
    $str = md5(uniqid(md5(microtime(true)), true));
    $str = sha1($str); //加密
    if ($strs) {
        return strtoupper($str);
    } else {
        return $str;
    }
}

/**
 * 获取一串字符串[其实和下方的key_code是差不多得到只是多了一个长度选择]
 * @param int $length 字符串获取的长度
 * @param bool $case true为大小写混一起，false为全部为大写
 */
function get_code($length, $case = true)
{
    $str = null;
    $strpol = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghizklmnopqrstuvwsyz0123456789";
    $max = strlen($strpol) - 1;
    for ($i = 0; $i < $length; $i++) {
        $str .= $strpol[rand(0, $max)];
    }
    // 判断是否是输出大小写格式
    if ($case == true) {
        return $str;
    } else {
        return strtoupper($str);
    }
}

/**
 * get cilnet ip
 * @return 1.1.1.1
 */
function getip()
{
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknow")) {
        $ip = getenv("HTTP_CLIENT_IP");
    } else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknow")) {
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    } else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknow")) {
        $ip = getenv("REMOTE_ADDR");
    } else if (isset($_SERVER["REMOTE_ADDR"]) && $_SERVER["REMOTE_ADDR"] && strcasecmp($_SERVER["REMOTE_ADDR"], "unknow")) {
        $ip = $_SERVER["REMOTE_ADDR"];
    } else {
        $ip = "unknow";
    }
    return $ip;
}

/**
 * get times
 */
function get_time($status = true)
{
    // 2022-12-11 14:24:47
    if ($status == true) {
        $time = date('Y-m-d', time()) . ' 00:00:00';
    } else {
        $time = date('Y-m-d', time()) . ' 23:59:59';
    }
    return $time;
}

/**
 * send a post
 */
function send_post($url, $param)
{
    if (!is_array($param)) return '参数必须为array';
    $httph = curl_init($url);
    curl_setopt($httph, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($httph, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($httph, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($httph, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)");
    curl_setopt($httph, CURLOPT_POST, 1);
    curl_setopt($httph, CURLOPT_POSTFIELDS, $param);
    curl_setopt($httph, CURLOPT_RETURNTRANSFER, 1);
    $rst = curl_exec($httph);
    curl_close($httph);
    return $rst;
}

/**
 * Get Identity
 * @return string username
 */
function get_auth()
{
    $auth = Session::get('auth');
    if (empty($auth)) {
        return 'null';
    }
    return $auth['username'];
}

function get_id()
{
    $id = Session::get('agent_auth');
    $id = $id['id'];
    return $id;
}

function txt_array($txt)
{
    $text = explode('&', $txt);
    $data = [];
    foreach ($text as $value) {
        $array = explode('=', $value);
        if (is_array($array) && count($array) == 2) {
            $data = array_merge($data, [$array[0] => $array[1]]);
        }
    }
    return $data;
}

/**
 * 生成订单号
 */
function get_order()
{
    $res = date('Ymdihs') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    return $res;
}

/**
 * 获取商品类型数值
 */
function getTypeValue($value)
{
    if ($value == 1) {
        $res = 1;
    }elseif($value == 2){
        $res = 7;
    }elseif ($value == 3){
        $res = 30;
    }elseif($value == 4){
        $res = 365;
    }
    return $res;
}


/**
 * 邮件发送
 * @param string $to_mail 目标邮箱
 * @param string $Content 邮件主体内容 支持Html标签
 * @param string $title 邮件标题 
 * @param string $user
 * @return bool true or false
 */
function mail_send($to_mail = '', $Content = '', $title = '', $user = '')
{
    $mail = new PHPMailer(); //实例化
    $mail->IsSMTP(); // 启用SMTP
    $mail->Host = config('web.smtp_host'); //SMTP服务器 以163邮箱为例子
    $mail->Port = config('smtp_port');  //邮件发送端口
    $mail->SMTPAuth   = true;  //启用SMTP认证
    $mail->CharSet  = "UTF-8"; //字符集
    $mail->Encoding = "base64"; //编码方式
    $mail->Username = config('web.smtp_user');  //你的邮箱
    $mail->Password = config('web.smtp_pass');  //你的密码
    $mail->Subject = "你好"; //邮件标题
    $mail->From = config('web.smtp_user');  //发件人地址
    $mail->FromName = "易验云";  //发件人姓名
    $address = $to_mail; //收件人email
    $mail->AddAddress($address, "Q"); //添加收件人（地址，昵称）
    $mail->IsHTML(true); //支持html格式内容
    $mail->AddEmbeddedImage("logo.jpg", "my-attach", "logo.jpg"); //设置邮件中的图片
    $mail->Body = '<div>
    <includetail>
        <div style="font:Verdana normal 14px;color:#000;">
            <div style="position:relative;">
                <style>
                    .title_bold {
                        font-family: PingFangSC-Medium, "STHeitiSC-Light", BlinkMacSystemFont, "Helvetica", "lucida Grande", "SCHeiti", "Microsoft YaHei";
                        font-weight: bold;
                    }

                    .mail_bg {
                        background-color: #F5F5F5;
                    }

                    .mail_cnt {
                        padding: 60px 0 160px;
                        max-width: 700px;
                        margin: auto;
                        color: #2b2b2b;
                        -webkit-font-smoothing: antialiased;
                    }

                    .mail_container {
                        background-color: #fff;
                        margin: auto;
                        max-width: 702px;
                        border-radius: 2px;
                    }

                    .eml_content {
                        padding: 0 50px 30px 50px;
                        font-family: "Helvetica Neue", "Arial", "PingFang SC", "Hiragino Sans GB", "STHeiti", "Microsoft YaHei", sans-serif;
                    }

                    .mail_header {
                        text-align: right;
                    }

                    .top_line_left {
                        width: 88%;
                        height: 3px;
                        background-color: #FF0000;
                        float: left;
                        margin-right: 1px;
                        border-top-left-radius: 2px;
                        display: inline-block;
                    }

                    .top_line_right {
                        width: 12%;
                        height: 3px;
                        background-color: #8470FF;
                        float: right;
                        border-top-right-radius: 2px;
                        margin-top: -3px;
                    }

                    .main_title {
                        font-size: 16px;
                        line-height: 24px;
                    }

                    .main_subtitle {
                        line-height: 28px;
                        font-size: 16px;
                        margin-bottom: 12px;
                    }

                    .item_level_1 {
                        margin-top: 60px;
                    }

                    .item_level_2 {
                        margin-top: 40px;
                    }

                    .level_1_title {
                        font-size: 16px;
                        line-height: 28px;
                    }

                    .level_2_title {
                        font-size: 14px;
                        line-height: 28px;
                        font-weight: 600;
                    }

                    .item_txt {
                        font-size: 14px;
                        line-height: 28px;
                    }

                    .mail_footer {
                        font-size: 12px;
                        line-height: 17px;
                        color: #bebebe;
                        margin-top: 60px;
                        letter-spacing: 1px;
                    }

                    .mail_logo {
                        /*这里修改LOGO*/
                        background-image: url("https://s1.ax1x.com/2022/04/05/qOY2Gt.png");
                        background-size: 34px 24px;
                        width: 34px;
                        height: 24px;
                        background-repeat: no-repeat;
                        display: inline-block;
                        margin: 27px 0 20px 0;
                        clear: left;
                    }

                    .img_position {
                        max-width: 100%;
                    }

                    .normalTxt {
                        font-size: 14px;
                        line-height: 24px;
                        margin-top: 10px;
                    }

                    @media (max-width: 768px) {
                        .top_line {
                            display: none;
                        }

                        .mail_bg {
                            background: #fff;
                        }

                        .mail_cnt {
                            padding-bottom: 0;
                            padding-top: 0;
                        }

                        .eml_content {
                            padding: 0 12px 50px;
                        }

                        .phoneFontSizeTitle {
                            font-size: 18px !important;
                        }

                        .phoneFontSizeContent {
                            font-size: 16px !important;
                            line-height: 28px !important;
                        }

                        .phoneFontSizeTitleLarge {
                            font-size: 20px !important;
                        }

                        .phoneFontSizeTips {
                            font-size: 14px !important;
                        }
                    }
                </style>

                <div class="qmbox">
                    <div class="mail_bg">
                        <div class="mail_cnt">
                            <div class="mail_container">
                                <div class="top_line">
                                    <div class="top_line_left"></div>
                                    <div class="top_line_right"></div>
                                </div>
                                <div class="eml_content">
                                    <div class="mail_header">
                                        <div class="mail_logo"></div>
                                    </div>
                                    <div class="">
                                        <p style="font-size: 16px;margin-top:20px;" class="phoneFontSizeTitle">
                                            尊敬的：收件者您好
                                        </p>

                                        <div style="margin-bottom: 40px;margin-top: 30px;overflow: hidden;">
                                            <div style="font-size: 16px;line-height: 28px;margin-bottom: 10px;" class="title_bold phoneFontSizeTitle">' . $title . '</div>
                                            <div style="font-size: 14px;line-height: 24px;" class="phoneFontSizeContent">· ' . $Content . '</div>
                                            <div style="display: inline-block;margin-top: 20px;margin-bottom: 20px;">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="mail_footer">
                                        易验云
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </includetail>
</div>
'; //邮件主体内容
    //发送
    return $mail->Send();
}


function get_system()
{
    $config = System::find(1);
    return $config;
}