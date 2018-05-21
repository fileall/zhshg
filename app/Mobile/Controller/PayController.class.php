<?php

namespace Mobile\Controller;

use Admin\Org\Image;

class PayController extends HomeController {

    public $APPSECRET = 'fd78a530db36628ad8b3e48348d90231';

    public $APPID     = 'wx5bb60c27fa07f4ca';

    public function _initialize() {

        parent::_initialize();
        $this->uid=is_login();
        !$this->uid&& $this->redirect('Login/enter');

        $this->_member=D('Member');
    }



    //扫码支付、3个币种支付:生成订单
    public function check_pay_order(){
        $pos = I('post.');
        $uid=$this->uid;
        $member=$this->_member->find($uid);
        $shop = D('Merchant')->where(array('tel'=>$pos['tel'],'status'=>2))->find();
        (!$shop)&& exit(json_encode(array('status'=>0,'msg'=>'对方号码输入有误')));
        (!$member['paypassword'])&& exit(json_encode(array('status'=>2,'msg'=>'支付密码未设置')));
        ($member['paypassword'] != st_md5($pos['pw']))&& exit(json_encode(array('status'=>0,'msg'=>'支付密码有误')));
        $now=time();

        //不允许3s内重复生成类似订单
        $map=['totalprices'=>$pos['prices'],'status'=>1,'uid'=>$uid,'shop_id'=>$shop['id']];
        $map['add_time']=['gt',$now-3];
        $over_order=M('order_line')->where($map)->count();
        $over_order&& $this->ajaxReturn(['status'=>0,'msg'=>'请勿重复提交订单','dingdan'=>0]);

        //前端数据支付方式$pos['zftype']  1元宝3金果
        $zftype=$pos['zftype'];
        if($zftype==1){
            $str='1';
            $data['memos']='线下消费:金元宝支付';
            $no_enough='金元宝余额不足';
            $acer='gold_acer';
            $data['zftype']=1;
        }
        if($zftype==3){
            $str='3';
            $data['memos']='线下消费:金果支付';
            $no_enough='金果余额不足';
            $acer='gold_fruit';
            $data['zftype']=2;//'支付方式 1微信 2.支付宝 3余额 （4金宝 5元宝 6金果）
        }

        //支付方式判断
        (strpos($shop['zftype'],$str) === false)&& exit(json_encode(array('status'=>0,'msg'=>'该店家不支持此支付方式')));

        //余额判断
        if($member[$acer] < $pos['prices']){
            $this->ajaxReturn(['status'=>0,'msg'=>$no_enough]);
        }


        $dingdan=substr_replace($_SERVER['REQUEST_TIME'].rand(10000,99999), 88,1,5);//生成订单号
        $data['dingdan']=$dingdan;
        $data['uid'] = $uid;
        $data['shop_id']=$shop['id'];
        $data['totalprices'] = $pos['prices'];
        $data['status'] = 1;// 1未付款 2已付款
        $data['add_time']=$now;


        $yd=M('order_line')->data($data)->add(); //生成临时订单
        if($yd){
           $this->ajaxReturn(['status'=>1,'msg'=>'操作成功','dingdan'=>$dingdan]);
        }else{
           $this->ajaxReturn(['status'=>0,'msg'=>'操作失败,请重试','dingdan'=>0]);
        }

    }


}