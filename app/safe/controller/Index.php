<?php
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-11 11:21:48
 * @LastEditTime : 2022-12-28 14:28:36
 * @FilePath     : \ioucode_auth\app\safe\controller\Index.php
 */

declare(strict_types=1);

namespace app\safe\controller;

use app\safe\model\Card;
use app\safe\model\Clock;
use app\safe\model\Notice;
use app\safe\model\Project;
use app\safe\model\UcLog;
use app\safe\model\User as ModelUser;
use app\safe\validate\User;
use think\exception\ValidateException;
use think\facade\View;
use think\Request;

// 签名方法就是所有参数加密后的值然后再取MD5的值

class Index
{
    /**
     * @var \think\Request Request实例
     */
    protected $request;

    /**
     * 构造方法
     * @param Request $request Request对象
     * @access public
     */
    public function __construct(Request $request)
    {
        return $this->request = $request;
    }

    public function index($id = '', $action = '')
    {
        $in = input();
        $pro = Project::find($id);
        // if (empty($pro)) return view::fetch();
        if ($pro['status'] == 'n') return s(201, $pro, '项目已关闭');
        // 开始判断通信类型
        if ($pro['encryption_type'] == 0) {
            // 不加密
            $data = input();
        } elseif ($pro['encryption_type'] == 1) {
            // base64编码
            $data = base64_decode($in['data']);
            $sign = md5($in['data']);
            $datas = txt_array($data);
            $time = time() - $datas['time'];
            if (!$sign == $in['sgin']) {
                return s(201, $pro, '签名值有误');
            } else if ($time >= 10) {
                return s(201, $pro, '数据已过期');
            } else {
                $data = txt_array($data);
            }
        } else {
            //AES 加密
            $data = $in['data'];
            $sign = md5($in['data']);
            $data_arr = openssl_decrypt($data, 'AES-128-ECB', $pro['encryption_key'], 0);
            $data = txt_array($data_arr);
            $time = time() - $data['time'];
            if (!$sign == $in['sgin']) {
                return s(201, $pro, '签名值有误');
            } else if ($time >= 10) {
                return s(201, $pro, '数据已过期');
            } else {
                $data = txt_array($data);
            }
        }

        switch ($action) {
            case 'login':
                // 登录
                return $this->login($pro, $data);
                break;
            case 'register':
                // 注册
                return $this->register($pro, $data);
                break;
            case 'clock':
                // 签到
                return $this->clock($pro, $data);
                break;
            case 'info':
                // 获取用户信息
                return $this->info($pro, $data);
                break;
            case 'pic_up':
                // 头像上传
                return $this->pic_up($pro, $data);
                break;
            case 'up_pass':
                // 修改密码
                return $this->up_pass($pro, $data);
                break;
            case 'user_active':
                // 用户心跳
                return $this->active($pro, $data);
                break;
            case 'card_login':
                // 卡密登录
                return $this->card_login($pro, $data);
                break;
            case 'card_clock':
                // 卡密签到
                return $this->card_clock($pro, $data);
                break;
            case 'card_info':
                // 获取卡密信息
                return $this->card_info($pro, $data);
                break;
            case 'card_unbind':
                // 卡密解绑
                return $this->card_unbind($pro, $data);
                break;
            case 'card_active':
                // 卡密心跳
                return $this->card_active($pro, $data);
                break;
            case 'notice':
                // 获取系统公告
                return $this->notice($pro, $data);
                break;
            case 'test':
                return $this->test($pro, $data);
                break;
            default:
                return '操作不存在';
                return view::fetch();
                break;
        }
    }

    function test($pro)
    {
        $a = UcLog::where('token', 'CD5BCD3F70E4E48D30FF1D38A24127D1F57C7E30')->find();
        $a->user->username;
        return json($a);
    }

    public function notice($pro)
    {
        $notice = Notice::where('project_id', $pro['id'])->find();
        return s(200, $pro, '', $notice);
    }

    function register($pro, $data)
    {
        $in = $data;
        try {
            validate(User::class)->check([
                'username' => $in['username'],
                'usereamil' => $in['useremail'],
                'password' => $in['password'],
            ]);
        } catch (ValidateException $e) {
            return s(201, $pro, $e->getError());
        }
        if (!empty(ModelUser::where('username', $in['username'])->find())) return s(201, $pro, '用户名已存在');
        $data = [
            'username' => $in['username'],
            'useremail' => $in['useremail'],
            'password' => $in['password'],
            'project_id' => $pro['id'],
            'vip_time' => time(),
            'namenick' => '这个人没有昵称~',
            'ip' => getip(),
        ];
        $res = new ModelUser;
        $res->save($data);
        if ($res) {
            return s(200, $pro);
        } else {
            return s(201, $pro, '注册失败请重试');
        }
    }

    function login($pro, $data)
    {
        $in = $data;
        $user = ModelUser::where([
            'project_id' => $pro['id'],
            'username' => $in['username'],
            'password' => $in['password']
        ])->find();
        $token = key_code();
        if (empty($user)) return s(201, $pro, '用户或密码有误');
        $log = UcLog::where(['project_id' => $pro['id'], 'res_id' => $user['id']])->count();
        // 当登录数已经大于或等于最大登录设备数时
        if ($log >= $pro['user_mac']) {
            $eq = UcLog::where(['res_id' => $user['id'], 'mac' => $in['mac']])->find();
            // $eq = UcLog::where(['res_id' => $user['id'], 'mac' => $in['mac']])->count();
            if (empty($eq)) {
                // 没有相同的设备
                $log = UcLog::where(['res_id' => $user['id']])->order('create_time asc')->find();
                $time = time() - strtotime($log['create_time']);
                if ($time > $pro['login_interval']) {
                    $logs = new UcLog;
                    $logs->save(['res_id' => $user['id'], 'type' => 'login', 'mac' => $in['mac'], 'ip' => getip(), 'token' => $token]);
                    $info = ['token' => $token];
                    return s(200, $pro, '', $info);
                } else {
                    return s(201, $pro, '登录间隔时间未到,请【' . $pro['login_interval'] - $time . '】秒后再次尝试登录');
                }
            } else {
                // 已有相同的设备
                $logs = UcLog::find($eq['id']);
                $logs->token = $token;
                $logs->ip = getip();
                $logs->save();
                $info = ['token' => $token];
                return s(200, $pro, '', $info);
            }
        } else {
            $eq = UcLog::where(['res_id' => $user['id'], 'mac' => $in['mac']])->find();
            if (empty($eq)) {
                // 没有相同的设备
                $logs = new UcLog;
                $logs->save(['project_id' => $pro['id'], 'type' => 'login', 'res_id' => $user['id'], 'mac' => $in['mac'], 'ip' => getip(), 'token' => $token]);
                $info = ['token' => $token];
                return s(200, $pro, '', $info);
            } else {
                // 已有相同的设备
                $logs = UcLog::find($eq['id']);
                $logs->token = $token;
                $logs->ip = getip();
                $logs->save();
                $info = ['token' => $token];
                return s(200, $pro, '', $info);
            }
        }
    }

    function info($pro, $data)
    {
        $in = $data;
        // $user = ModelUser::find($in['id']);
        $user = UcLog::where('token', $in['token'])->find();
        $user->user->username;
        $info = $user['user'];
        if (empty($user)) {
            return s(201, $pro, '用户不存在');
        } else {
            return s(200, $pro, '', $info);
        }
    }

    function pic_up($pro, $data)
    {
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');
        try {
            validate(['image' => [
                'fileSize' => 410241024,
                'fileExt' => 'jpg,jpeg,png',
                'fileMime' => 'image/jpeg,image/png,image/gif',
            ]])->check(['image' => $file]);
            $saveName = \think\facade\Filesystem::disk('public')->putFile('topic', $file);
            $in = $data;
            $user = UcLog::where('token', $in['token'])->find();
            $user->user->username;
            $info = $user['user'];
            $pic = ModelUser::find($info['id']);
            $pic->save(['pic' => $saveName]);
            return s(200, $pro,'', $saveName);
        } catch (\Exception $e) {
            // 验证失败 输出错误信息
            return s(201, $pro, $e->getMessage());
        }
    }

    public function up_pass($pro, $data)
    {
        $in = $data;
        $user = UcLog::where('token', $in['token'])->find();
        $user->user->username;
        $info = $user['user'];
        $up = ModelUser::find($info['id']);
        $up->save([
            'password' => $in['password']
        ]);
        return s(200, $pro);
    }

    function clock($pro, $data)
    {
        $in = $data;
        $user = UcLog::where('token', $in['token'])->find();
        if (empty($user)) return s(201, $pro, '令牌已过期或不存在');
        $user_info = ModelUser::find($user['res_id']);
        $vip_time = $user_info['vip_time'] + $pro['clock_vip'] * 3600;
        $res = Clock::where('res_id', $user['res_id'])
            ->whereBetweenTime('create_time', get_time(), get_time(false))
            ->select()
            ->toArray();
        if (empty($res)) {
            return s(201, $pro, '今日已签到');
        } else {
            $vip = ModelUser::find($user_info['id']);
            $vip->vip_time = $vip_time;
            $vip->save();
            if (!$vip) {
                s(201, $pro, '系统异常请重试');
            }
            $clock = new Clock;
            $clock->save([
                'project_id' => $pro['id'],
                'res_id' => $user['res_id'],
                'ip' => getip()
            ]);
            return s(200, $pro, '签到成功');
        }
    }

    function active($pro, $data)
    {
        $in = $data;
        $token = key_code();
        $res = UcLog::where(['token' => $in['token'], 'mac' => $in['mac']])->find();
        if (empty($res)) return s(201, $pro, '身份有误');
        $res->save([
            'token' => $token
        ]);
        if (!$res) {
            return s(201, $pro, '操作失败请重试');
        } else {
            return s(200, $pro);
        }
    }

    /**
     * 卡密登录
     */
    function card_login($pro, $data)
    {
        $in = $data;
        $res = Card::where('card', $in['card'])->find();
        $token = key_code();
        if (empty($res)) return s(201, $pro, '激活码不存在');
        $end = $res['value'] * 86400 + time();
        $macs = UcLog::where(['project_id' => $pro['id'], 'res_id' => $res['id']])->count();
        if ($res['end_time'] == '') {
            $logs = new UcLog;
            $logs->save(['res_id' => $res['id'], 'res_type' => 'login', 'mac' => $in['mac'], 'ip' => getip(), 'token' => $token]);
            $info = ['token' => $token];
            $cards = Card::find($res['id']);
            $cards->save([
                'start_time' => time(),
                'end_time' => $end
            ]);
            return s(200, $pro, '', $info);
        }
        if ($res['end_time'] <= time()) {
            return s(201, $pro, '激活码已到期');
        }
        if ($macs >= $pro['card_mac']) {
            $eq = UcLog::where(['project_id' => $pro['id'], 'mac' => $in['mac'], 'res_id' => $res['id']])->find();
            if (empty($eq)) {
                $log = UcLog::where(['res_id' => $res['id']])->order('create_time asc')->find();
                $time = time() - strtotime($log['create_time']);
                if ($time > $pro['login_interval']) {
                    $logs = new UcLog;
                    $logs->save(['res_id' => $res['id'], 'res_type' => 'login', 'mac' => $in['mac'], 'ip' => getip(), 'token' => $token]);
                    $info = ['token' => $token];
                    return s(200, $pro, '', $info);
                } else {
                    return s(201, $pro, '登录设备间隔时间未到,请【' . $pro['login_interval'] - $time . '】秒后再次尝试登录');
                }
            } else {
                // 已有相同的设备
                $logs = UcLog::find($eq['id']);
                $logs->token = $token;
                $logs->ip = getip();
                $logs->save();
                $info = ['token' => $token];
                return s(200, $pro, '', $info);
            }
        } else {
            $eq = UcLog::where(['res_id' => $res['id'], 'mac' => $in['mac']])->find();
            if (empty($eq)) {
                // 没有相同的设备
                $logs = new UcLog;
                $logs->save(['project_id' => $pro['id'], 'res_type' => 'login', 'res_id' => $res['id'], 'mac' => $in['mac'], 'ip' => getip(), 'token' => $token]);
                $info = ['token' => $token];
                return s(200, $pro, '', $info);
            } else {
                // 已有相同的设备
                $logs = UcLog::find($eq['id']);
                $logs->token = $token;
                $logs->ip = getip();
                $logs->save();
                $info = ['token' => $token];
                return s(200, $pro, '', $info);
            }
        }
    }

    function card_clock($pro, $data)
    {
        $in = $data;
        $user = UcLog::where('token', $in['token'])->find();
        if (empty($user)) return s(201, $pro, '令牌已过期或不存在');
        $card_info = ModelUser::find($user['res_id']);
        $vip_time = $card_info['end_time'] + $pro['clock_vip'] * 3600;
        $res = Clock::where('res_id', $user['res_id'])->whereBetweenTime('create_time', get_time(), get_time(false))->select()->toArray();
        if (empty($res)) {
            return s(201, $pro, '今日已签到');
        } else {
            $vip = ModelUser::find($card_info['id']);
            $vip->vip_time = $vip_time;
            $vip->save();
            if (!$vip) {
                s(201, $pro, '系统异常请重试');
            }
            $clock = new Clock;
            $clock->save([
                'project_id' => $pro['id'],
                'res_id' => $user['res_id'],
                'ip' => getip()
            ]);
            return s(200, $pro, '签到成功');
        }
    }

    function card_active($pro, $data)
    {
        $in = $data;
        $token = key_code();
        $res = UcLog::where(['token' => $in['token'], 'mac' => $in['mac']])->find();
        if (empty($res)) return s(201, $pro, '身份有误');
        $res->save([
            'token' => $token
        ]);
        if (!$res) {
            return s(201, $pro, '操作失败');
        } else {
            return s(200, $pro);
        }
    }

    function card_unbind($pro, $data)
    {
        $in = $data();
        $res = UcLog::where(['token' => $in['token'], 'mac' => $in['mac']])->find();
        $res->save([
            'mac_status' => 'n'
        ]);
        return s(200, $pro);
    }

    function card_info($pro, $data)
    {
        $in = $data;
        $user = UcLog::where('token', $in['token'])->find();
        if (empty($user)) return s(201, $pro, '令牌已过期或不存在');
        $info = Card::find($user['res_id']);
        return s(200, $pro, '', $info);
    }
}
