<?php
/*
 * @Author       : Lucifer
 * @Date         : 2022-12-22 00:12:24
 * @LastEditTime : 2022-12-24 21:56:58
 * @FilePath     : \ioucode_auth\app\shop\controller\Pay.php
 */

declare(strict_types=1);

namespace app\shop\controller;

use alipay\lib\AlipayQuery;
use alipay\lib\AlipayService;
use app\admin\model\GoodsType;
use app\shop\model\Card;
use app\shop\model\Order;
use think\facade\View;
use think\Request;

class Pay
{
    /**
     * 显示资源列表
     * 通过前台下单后全程只使用【订单号】交互，安全防护。
     * @return \think\Response
     */
    public function index()
    {
        $in = input();
        $order = Order::where('order_id', $in['order'])->find();
        $pay_config = get_system();
        include_once app()->getRootPath() . 'extend\ailpay\lib\service.class.php';
        $payAmount = $order['coin'];
        $orderName = '云商城购物';
        $Orderid = $in['order'];
        $aliPay = new AlipayService();
        $aliPay->setAppid($pay_config['alipay_appid']);
        $aliPay->setNotifyUrl('');
        $aliPay->setRsaPrivateKey($pay_config['alipay_private_key']);
        $aliPay->setTotalFee($payAmount);
        $aliPay->setOutTradeNo($Orderid);
        $aliPay->setOrderName($orderName);
        $result = $aliPay->doPay();
        $result = $result['alipay_trade_precreate_response'];
        // 最后结果返回的是：array(4) { ["code"]=> string(5) "10000" ["msg"]=> string(7) "Success" ["out_trade_no"]=> string(13) "63a3f7824a283" ["qr_code"]=> string(46) "https://qr.alipay.com/bax07505iu5sinzhbr6j002f" }
        $data = [
            'qr' => $result,
            'order' => $order['order_id']
        ];
        return view::fetch('', ['data' => $data]);
    }

    public function check()
    {
        include_once app()->getRootPath() . 'extend\ailpay\lib\query.class.php';
        $pay_config = get_system();
        $outTradeNo = input('order');
        $order = Order::where('order_id', $outTradeNo)->find();
        if (empty($order)) return showmsg(201, '订单号不存在');
        $aliPay = new AlipayQuery();
        $aliPay->setAppid($pay_config['alipay_appid']);
        $aliPay->setRsaPrivateKey($pay_config['alipay_private_key']);
        $aliPay->setOutTradeNo($outTradeNo);
        $result = $aliPay->doQuery();
        if ($result['alipay_trade_query_response']['code'] != '10000') {
            $value = array('code' => 202, 'msg' => $result['alipay_trade_query_response']['sub_msg']);
        } else {
            switch ($result['alipay_trade_query_response']['trade_status']) {
                case 'WAIT_BUYER_PAY':
                    $value = array('code' => 202, 'msg' => '等待买家付款');
                    break;
                case 'TRADE_CLOSED':
                    $value = array('code' => 202, 'msg' => '未付款交易超时关闭');
                    break;
                case 'TRADE_SUCCESS':
                    $value = array('code' => 200, 'msg' => '支付成功');
                    // 开始业务代码
                    $goodstype = GoodsType::where('id', $order['goods_type_id'])->find();
                    // 获取商品面值
                    $card = Card::order('id desc')->where('sell', 'n')->when('value', getTypeValue($goodstype['type']))->find();
                    $info =  $card;
                    $card->save(['sell' => 'y']);
                    // 结束订单号
                    $order = Order::where('order_id', $outTradeNo)->find();
                    $order->save([
                        'status' => 'y',
                        'res' => $info['card']
                    ]);
                    $data = [
                        'card' => $info
                    ];
                    return showmsg(200, '支付成功', ['data' => $data]);
                    break;
                case 'TRADE_FINISHED':
                    $value = array('code' => 202, 'msg' => '交易结束');
                    break;
                default:
                    $value = array('code' => 202, 'msg' => '未知状态');
                    break;
            }
        }
        return showmsg(201, '', $value);
    }

    /**
     * 支付成功页面
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function success(Request $request)
    {
        $in = input();
        $order = Order::where('order_id', $in['order'])->find();
        return view::fetch('', ['data' => $order]);
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
