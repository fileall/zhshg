<?php
namespace Mobile\Controller;
/**订单
 * Class OrderController
 * @package Mobile\Controller
 */
class OrderController extends HomeController {
    public function _initialize() {
        parent::_initialize();
        $this->uid=is_login();
        if(!$this->uid){
            $this->redirect('Login/enter');
        }
        $this->_item=D('Item');
        $this->_item_attr=D('ItemAttr');
        $this->_order=D('Order');
        $this->_order_list=D('OrderList');
        $this->_member=D('Member');

    }

    //提交订单=>生成订单
    public function order_submit(){
        $uid=$this->uid;
        $parm=json_decode(cookie('cart_ids'),true);
        if(!$parm){//cookie不存在
            $this->ajaxReturn(['status'=>-1,'msg'=>'您访问的网页已过期','url'=>U('index/index')]);
        }
        if(!$parm['item_id']){//购物车提交
            $cart_ids=$parm['cart_ids'];
        }

        (!$addr_id=I('addr_id')) &&$this->ajaxReturn(['status'=>0,'msg'=>'请选择收货地址']);
        //生成订单
        $data['memos']=I('memos');
        $address=M('member_address')->find($addr_id);
        $data['sh_person']=$address['shperson'];
        $data['sh_mobile']=$address['mobile'];
        $data['sh_address']=$address['province'].$address['city'].$address['district'].$address['address'];
        $data['uid']=$uid;
        $data['status']=1;//订单状态1=待支付,2=待接单（商家未接单）,3=待完成/待收货，4=待评价/已收货, 5=已评价 6交易已取消',
        $data['dingdan']=create_order_sn();
        $data['add_time']=$_SERVER['REQUEST_TIME'];

        if($parm['item_id']){//立即购买提交
            $ret=$this->get_item_now($parm);//获取立即购买的商品
        }else{//购物车
            $ret=gwc_item(['uid'=>$uid,'id'=>['in',$cart_ids]]);//获取购物车商品
        }

        !$ret&&$this->ajaxReturn(['status'=>0,'msg'=>'系统繁忙']);
        $data['item_num']=$ret['msg']['count'];//商品件数
        $data['order_amount']=$ret['msg']['money'];//应付总金额
        $data['total_amount']=$ret['msg']['money'];//实际总金额（默认选金元宝支付时的金额）

        $start=M();//开启事务
        $start->startTrans();
        $res_order=$this->_order->add($data);//生成订单
        if(!$res_order){
            $start->rollback();
            $this->ajaxReturn(['status'=>0,'msg'=>'订单提交失败']);
        }
        $list=$ret['list'];//商品信息
        //添加订单明细
        foreach ($list as $kk=>$vv){
            $order_list[]=[
                'oid'=>$res_order,
                'uid'=>$uid,
                'attr_id'=>$vv['attr_id'],
                'item_id'=>$vv['item_id'],
                'title'=>$vv['title'].'-'.$vv['attr_name'],
                'img'=>$vv['img'],
                'num'=>$vv['num'],
                'price'=>$vv['price'],
                'oldprice'=>$vv['oldprice'],
                'acer'=>$vv['acer'],
                'coin'=>$vv['coin'],

            ];
        }
        $res_ol= $this->_order_list->addAll($order_list);//添加订单明细
        if(!$res_ol){
            $start->rollback();
            $this->ajaxReturn(['status'=>0,'msg'=>'系统繁忙']);
        }
        if(!$parm['item_id']){//购物车=>删除已购买的购物车商品
            $res_del=M('gwc')->delete($cart_ids);//删除已购买的购物车商品
            if(!$res_del){
                $start->rollback();
                $this->ajaxReturn(['status'=>0,'msg'=>'系统繁忙']);
            }
        }

//        cookie('cart_ids',null);
        $start->commit();
        $this->ajaxReturn(['status'=>1,'msg'=>'操作成功','url'=>U('order/order_pay',['oid'=>$res_order])]);

    }

    //提交订单=>生成订单
    public function order_submit0(){
        $uid=$this->uid;
        $cart_ids=cookie('cart_ids');
//        $cart_ids=I('cart_ids');

        $is_have=M('gwc')->where(['id'=>['in',$cart_ids]])->select();
        !$is_have&& redirect(U('item/gwc'), 1, '操作失败');

        (!$addr_id=I('addr_id')) &&$this->ajaxReturn(['status'=>0,'msg'=>'请选择收货地址']);
        $data['memos']=I('memos');
        //订单收获地址表
        $address=M('member_address')->find($addr_id);
        $data['sh_person']=$address['shperson'];
        $data['sh_mobile']=$address['mobile'];
        $data['sh_address']=$address['province'].$address['city'].$address['district'].$address['address'];
        $data['uid']=$uid;
        $data['status']=1;//订单状态1=待支付,2=待接单（商家未接单）,3=待完成/待收货，4=待评价/已收货, 5=已评价 6交易已取消',
        $data['dingdan']=create_order_sn();
        $data['add_time']=$_SERVER['REQUEST_TIME'];
        $ret=gwc_item(['uid'=>$uid,'id'=>['in',$cart_ids]]);//获取购物车商品
        !$ret&&$this->ajaxReturn(['status'=>0,'msg'=>'系统繁忙']);
        $data['order_amount']=$ret['msg']['money'];//应付总金额
        $data['total_amount']=$ret['msg']['money'];//实际总金额（默认选金元宝支付时的金额）
        $start=M();
        $start->startTrans();

        $res_order=$this->_order->add($data);//生成订单
        if(!$res_order){
            $start->rollback();
            $this->ajaxReturn(['status'=>0,'msg'=>'订单提交失败']);
        }
        $list=$ret['list'];//购物车的商品信息
        //添加订单明细
        foreach ($list as $kk=>$vv){
            $order_list[]=[
                'oid'=>$res_order,
                'uid'=>$uid,
                'attr_id'=>$vv['attr_id'],
                'item_id'=>$vv['item_id'],
                'title'=>$vv['title'].'-'.$vv['attr_name'],
                'img'=>$vv['img'],
                'num'=>$vv['num'],
                'price'=>$vv['price'],
                'oldprice'=>$vv['oldprice'],
                'acer'=>$vv['acer'],
                'coin'=>$vv['coin'],

            ];
        }
        $res_ol= $this->_order_list->addAll($order_list);//添加订单明细
        //删除已购买的购物车商品
        $res_del=M('gwc')->delete($cart_ids);//删除已购买的购物车商品
        if(!$res_ol||!$res_del){
            $start->rollback();
            $this->ajaxReturn(['status'=>0,'msg'=>'系统繁忙']);
        }
        cookie('cart_ids',null);
        $start->commit();
        $this->ajaxReturn(['status'=>1,'msg'=>'操作成功','url'=>U('order/order_pay',['oid'=>$res_order])]);

    }

    //订单支付
    public function order_pay()
    {
        $uid=$this->uid;
        if(IS_POST){//支付
            $pos=I();
            $oid=$pos['oid'];
            $zftype=$pos['zftype'];//支付方式 1金元宝 2金果 3金元宝+银币
            !in_array($zftype,[1,2,3])&&  $this->ajaxReturn(['status'=>0,'msg'=>'操作失败']);
            $member=$this->_member->find($uid);
            (!$pos['pas'])&& $this->ajaxReturn(['status'=>0,'msg'=>'请输入支付密码']);
            (!$member['paypassword'])&& $this->ajaxReturn(['status'=>0,'msg'=>'您还未设置支付密码']);
           (st_md5($pos['pas']) != $member['paypassword'])&& $this->ajaxReturn(['status'=>0,'msg'=>'支付密码有误']);

            $order=$this->_order->find($oid);

            ($order['status']!=1)&& $this->ajaxReturn(['status'=>-1,'msg'=>'订单已支付']);
            $order_list=$this->_order_list->where(['oid'=>$oid])->select();

            $start=M();
            $start->startTrans();
            //操作商品：库存、销量
            $attr_list=$this->_item_attr->where(['id'=>['in',array_column($order_list,'attr_id')]])->getField('id,attr_value');
            foreach($order_list as $kk=>$vv){
                if($attr_list[$vv['attr_id']]<$vv['num']){//库存验证
                        $start->rollback();
                        $this->ajaxReturn(['status'=>0,'msg'=>$vv['title'].'库存不足']);
                }
                $res_attr=$this->_item_attr->where(['id'=>$vv['attr_id']])->setDec('attr_value',$vv['num']);
                $res_item=$this->_item->where(['id'=>$vv['item_id']])->setInc('sales',$vv['num']);
                if(!$res_attr || !$res_item){
                    $start->rollback();
                    $this->ajaxReturn(['status'=>0,'msg'=>'操作失败']);
                }
            }

            $list=$this->get_order_money($order,$order_list);//各种支付类型需要支付的金额

            $shipping_price=0;//配送费
            $coupon_price_dk=0;//优惠券抵扣

            //金额验证&&操作
           $now=time();
            if($zftype==1){//1金元宝支付
                $money=$list['order_amount']-$coupon_price_dk;
                $send_coin=$money*100;//线上消费送银币
                ($member['gold_acer']< $money)&& $this->ajaxReturn(['status'=>0,'msg'=>'余额不足']);
                $save_order=['status'=>2,'pay_time'=>$now,'zftype'=>$zftype,'total_amount'=>$money];
                $save_member['gold_acer']=['exp','gold_acer -'.$money];
                $recharge[] = account_arr(2, $uid,'-'.$money , '线上消费', $now,$oid,5);
                if($send_coin>0){
                    $save_member['silver_coin']=['exp','silver_coin +'.$send_coin];
                    $recharge[] = account_arr(4, $uid,$send_coin, '线上消费送银币', $now,$oid,5);
                }

            }else if($zftype==2){//2金果000
                $money=$list['fruit'];
                $send_coin=$money*30;//线上消费送银币
                ($member['gold_fruit']<$money)&& $this->ajaxReturn(['status'=>0,'msg'=>'余额不足']);
                $save_order=[
                    'status'=>2, 'pay_time'=>$now,'zftype'=>$zftype,
                    'fruit_price'=>$money,//金果支付
                    'total_amount'=>0,//实际支付金元宝
                ];
                $save_member['gold_fruit']=['exp','gold_fruit -'.$money];
                $recharge[] = account_arr(3, $uid,'-'.$money , '线上消费', $now,$oid,5);
                if($send_coin>0){
                    $save_member['silver_coin']=['exp','silver_coin +'.$send_coin];
                    $recharge[] = account_arr(4, $uid,$send_coin, '线上消费送银币', $now,$oid,5);
                }

            }else{//3金元宝+银币
                $money=$list['order_amount'];
                $money1= $list['acer']-$coupon_price_dk;
                $money2= $list['coin'];

                ($member['gold_acer']< $money1)&& $this->ajaxReturn(['status'=>0,'msg'=>'元宝不足']);
                ($member['silver_coin']< $money2)&& $this->ajaxReturn(['status'=>0,'msg'=>'银币不足']);
                $save_order=[
                    'status'=>2, 'pay_time'=>$now,'zftype'=>$zftype,
                    'coin_price'=>$money2,//银币支付
                    'total_amount'=>$money1,//实际支付金元宝
                    'coin_price_dk'=>$money-$list['acer'],//应付金额-实际支付(不含优惠券)=银币抵扣数
                ];
                $save_member=[
                    'gold_acer'=>['exp','gold_acer -'.$list['acer']],
                    'silver_coin'=>['exp','silver_coin -'.$list['coin']],
                ];
                $recharge[] = account_arr(2, $uid,'-'.$money1 , '线上消费', $now,$oid,5);
                $recharge[] = account_arr(4, $uid,'-'.$money2 , '线上消费', $now,$oid,5);

            }


            //操作订单
            $res_order=$this->_order->where(['id'=>$oid])->save($save_order);


            //操作用户
            $res_member=$this->_member->where(['id'=>$uid])->save($save_member);

            if(!$res_order || !$res_member){
                $start->rollback();
                $this->ajaxReturn(['status'=>0,'msg'=>'操作失败']);
            }
            //消费明细（放最后，避免订单和用户金额操作成功而明细操作失败）
            $res_account=M('account')->addAll($recharge);

            if(!$res_account){
                $start->rollback();
                $this->ajaxReturn(['status'=>0,'msg'=>'操作失败']);
            }

            $start->commit();
            $this->ajaxReturn(['status'=>1,'msg'=>'操作成功','url'=>U("member/mine")]);


        }else{//展示
            $oid=I('oid');
            $this->assign('oid',$oid);
            $order=$this->_order->find($oid);
            ($order['status']!=1)&& redirect(U('index/index'), 1, '订单已支付');
            $order_list=$this->_order_list->where(['oid'=>$oid])->select();
            $list=$this->get_order_money($order,$order_list);
            $this->assign('list',$list);
            $this->display();
        }


    }


    //立即购买获取商品
    function get_item_now($param){

        $item_id=$param['item_id'];
        $attr_id=$param['attr_id'];
        $num=$param['num'];

        $item=M('item')->where(['id'=>$item_id])->getField('id as item_id,img,title');
        $item_attr= M('item_attr')->where(['id'=>$attr_id])->getField('id as attr_id,attr_name,attr_value,price,oldprice,acer,coin');
        $list[0]=array_merge($item[$item_id],$item_attr[$attr_id]);
        $list[0]['num']=$num;
        $money=$num*$item_attr[$attr_id]['price'];
        $msg['money']=$money;
        $msg['count']=1;

        $data['msg']=$msg;
        $data['list']=$list;

        return $data;
    }

    //获得商品订单各币种金额
    private function get_order_money($order,$order_list){
        //币种计算
        $coin=0;
        $acer=0;
        $total_amount=$order['total_amount'];
        $fruit=round($total_amount/C('pin_jg_scj'),2);
        foreach ($order_list as $kk=>$vv){
            $acer +=($vv['acer']*$vv['num']);
            $coin +=($vv['coin']*$vv['num']);
        }

        $list=['coin'=>$coin,
            'acer'=>$acer,//元宝加银币支付
            'fruit'=>$fruit,//金果支付=>金果价格
            'total_amount'=>$total_amount,//元宝支付=>实际支付元宝总价
            'order_amount'=>$order['order_amount']//订单应付元宝总价
        ];
        return $list;
    }


    }