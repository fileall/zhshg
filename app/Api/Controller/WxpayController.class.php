<?php
namespace Api\Controller;

use Home\Controller\HomeController;


class WxpayController extends HomeController {

    public function _initialize()
    {
        $this->uid = 1;
    }



    /**
     * 统一支付接口，该接口是不需要证书
     */
    public function orderParameter($data) 
    {
        header("Content-Type:text/html;charset=utf-8");

        //导入微信支付sdk
        import("Vendor.Wxpay.lib.WxPay#Api", '', '.php');
        import("Vendor.Wxpay.example.WxPay#JsApiPay", '', '.php'); 
		
		
        //②、统一下单 支付接口
        $input = new \WxPayUnifiedOrder();   //统一下单输入对象  WxPay.Data.php

        $input->SetBody($data['body']); //商品描述
        $input->SetAttach($data['attach']); //附加参数 
        $input->SetOut_trade_no($data['number'].rand(0, 9)); //商户订单号 此处有坑,取消支付又重新发起支付商户订单号重复 
//      $input->SetOut_trade_no($data['number']); //商户订单号 此处有坑,取消支付又重新发起支付商户订单号重复 
        $input->SetTotal_fee($data['price']); //标价金额 为分 

        $input->SetTime_start(date("YmdHis")); //订单起时间 非必须参数
        $input->SetTime_expire(date("YmdHis", time() + 600)); //订单过期时间 非必须参数

//        $input->SetGoods_tag("优惠");//订单优惠标记 非必须参数

        $input->SetNotify_url("http://".$_SERVER['HTTP_HOST']."/notify.php"); //通知地址  http://zhshg.0791jr.com/notify.php
        $input->SetTrade_type("JSAPI"); //交易类型 公众号支付
        $input->SetOpenid(trim($data['openid'])); //用户标识 当为jspai 时必须
        $order = \WxPayApi::unifiedOrder($input);
        if ($order['return_code'] == 'fail') {
            return array('err_code'=>1, 'err_msg'=>$order['return_msg']);
        }
        $tools = new \JsApiPay();
		
        $jsApiParameters = $tools->GetJsApiParameters($order);  

        return $jsApiParameters; 
    }



/**
     * 生成支付二维码模式2
     * WxPay->micropay
     */
    public function pay_code($data) 
    {
        header("Content-Type:text/html;charset=utf-8");
        //导入微信支付sdk
        import("Vendor.Wxpay.lib.WxPay#Api", '', '.php');
        import("Vendor.Wxpay.example.WxPay#NativePay", '', '.php'); 
        //其中的 setOut_trade_no 和 setTotal_fee 和 setProduct_id 这三个参数是你可以随便填写的(其他参数默认就可以)
        $input = new \WxPayUnifiedOrder();
		$input->SetBody('预订'.'111'.'订单');
		$input->SetAttach('预订'.'111'.'订单');
		$input->SetOut_trade_no("11111");//订单号
		$input->SetTotal_fee(0.01 * 100);//金额
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag("");
        $input->SetNotify_url("http://zhshg.0791jr.com/notify.php"); //通知地址  
		$input->SetTrade_type("NATIVE");
		$input->SetProduct_id('239');//$product_info???
		$notify = new \NativePay();
		$result = $notify->GetPayUrl($input);
		dump($result);die;  
		dump($result["code_url"]);die;
		
		$code_url = $result["code_url"];

    }

     /**
     * 生成支付二维码模式1
     * WxPay->micropay
     */
    public function pay_code1($data) 
    {
        header("Content-Type:text/html;charset=utf-8");

        //导入微信支付sdk
        import("Vendor.Wxpay.lib.WxPay#Api", '', '.php');
        import("Vendor.Wxpay.example.WxPay#NativePay", '', '.php'); 
		
		
		
	    $notify = new \NativePay();
		$url1 = $notify->GetPrePayUrl("123456789");
		return $url1;
    }

    //异步通知地址
    public function notify() 
    {
		return 222;
    }



}