<?php
namespace Api\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function _initialize() {
//        parent::_initialize();
        $this->_member=D('Member');
        $this->_account=D('Account');//个人明细
        $this->_order_recharge=D('OrderRecharge');//充值记录&&升级记录
//        $this->_upgrade_recharge=D('UpgradeRecharge');//升级记录0

    }
    //微信回调
    public function index()
    {
        $member_model = $this->_member;
        $account = $this->_account;
        $gr_modle=D('GradeRule');
        $now = time();

        $result = $this->notify();
        $attach_arr = json_decode($result['attach'], true);
//        file_put_contents('wx_api.txt',var_export($attach_arr,true));

        $attach = $attach_arr['item_type']; //附加参数此处
        $sn_no = $result['out_trade_no'];
        $sn = substr($sn_no, 0, strlen($sn_no) - 1);//订单号
        $start = M();
        $start->startTrans();
        try {
            if ($attach == 1) {//充值元宝1**
                $order = $this->_order_recharge;
                $info = $order->where(array('dingdan' => $sn))->find();//订单号
                $uid = $info['uid'];
                if ($info['status'] == 1) {
                    //更改订单状态
                    $res_order = $order->where(array('id' => $info['id']))->save(array('status' => 2, 'pay_time' => $now, 'zftype' => 1));
                    if (!$res_order) {
                        $sql = $start->_sql();
                        throw new  \Exception($sql);
                    }
                    $member = $this->_member->find($uid);
                    $jyb = $info['totalprices'];
                    //充值送银币规则 #一个按钮一天只能送银币一次
//                    $yb=0;
//                    $buy_send_rule=M('buy_send')->select();//充值送银币规则
//                    foreach($buy_send_rule as $kk=>$vv){
//                          $flag=$kk+1<count($buy_send_rule)?($jyb <$buy_send_rule[$kk+1]['money']):true;
//                        if($jyb>=$vv['money'] &&$flag)){
//                            $yb=$vv['send_coin'];
//                            $index=$vv['id'];//当前规则的id
//                        }
//                    }
//                    $save['gold_acer'] = $member['gold_acer'] + $jyb;//加元宝
//                    $yb>0&&$save['silver_coin'] = $member['silver_coin'] + $yb;//加银币
                    //充值送银币规则 #该金额区间一天只能送银币一次
                    $time_end=time();//当前时间
                    $time_start= strtotime(date('Y-m-d',$time_end));//今天0点时间戳s
                    $time_rule['add_time'] =  array('between',array($time_start,$time_end));
                    $time_rule['uid'] = $uid;
                    $buy_send_rule=M('buy_send')->select();
                    foreach($buy_send_rule as $kk=>$vv){
                        $flag=$kk+1<count($buy_send_rule)?($jyb <$buy_send_rule[$kk+1]['money']):true;
                        $jyb>=$vv['money'] &&$flag&&$buy_send_id =$vv['id'];//当前规则的id
                    }

                    if($buy_send_id){
                        $time_rule['bs_id'] =$buy_send_id;//当前规则的id
                        $hava_buy_send=M('buy_send_account')->where($time_rule)->find();
                        (!$hava_buy_send)&&$yb=M('buy_send')->where(['id'=>$buy_send_id])->getField('send_coin');
                        if($yb>0){
                            $save['silver_coin'] = $member['silver_coin'] + $yb;
                            //获得银币奖励记录
                            $res_buy_send=M('buy_send_account')->add(['uid'=>$uid,'bs_id'=>$buy_send_id,'add_time'=>$now]);
                            if (!$res_buy_send) {
                                $sql = $start->_sql();
                                throw new  \Exception($sql);
                            }
                        }
                    }//加银币
                    $save['gold_acer'] = $member['gold_acer'] + $jyb;//加元宝


                    //本人加金元宝&&银币**************
                    $res_member = $member_model->where(array('id' => $uid))->save($save);//银币
                    if (!$res_member) {
                        $sql = $start->_sql();
                        throw new  \Exception($sql);
                    }

                    //本人金元宝&&银币明细**************
                    $recharge[] = account_arr(2, $uid, $jyb, '充值元宝', $now);
                    $yb&&$recharge[] = account_arr(4, $uid, $yb, '充值元宝赠送', $now);



                    //推荐人得银币&&明细**************
                    if ($member['relation_id']) {
                        #推荐人赠送银币(规则表)
                        $relation_member = $member_model->where(array('id' => $member['relation_id']))->field('id,vips')->find();
                        $relation_bl = D('GradeRule')->where(array('id' => $relation_member['vips']))->field('id,upgrade_one_price,tj_acer_silver')->find();
                        $silver_coin_num = $jyb * $relation_bl['tj_acer_silver'];
                        #银币明细
                        if ($silver_coin_num > 0) {
                            $res_relation = $member_model->where(array('id' => $member['relation_id']))->setInc('silver_coin', $silver_coin_num);
                            if (!$res_relation) {
                                $sql = $start->_sql();
                                throw new  \Exception($sql);
                            }
                            $recharge[] = account_arr(4, $member['relation_id'], $silver_coin_num, '下线充值元宝', $now);
                        }
                    }

                    //各区代得银币&&明细**************
                    $province_id=$member['province_id'];
                    $city_id=$member['city_id'];
                    $district_id=$member['district_id'];
                    $where['is_qd']=1;//vips_qd区代等级 1区2市3省
                    $where['_string']='(vips_qd =3 and province_id ='.$province_id.')'
                        .'or ( vips_qd =2 and  city_id='. $city_id.')'
                        .'or ( vips_qd =1 and district_id='.$district_id.')';
                    $qds=$member_model->where($where)->select();//该会员所在地区的各个等级区代
                    $qd_rule=M('qd_rule')->getField('id,name,reward_silver_multiple,upgrade_recharge');//区代等级规则
                    if ($qds) {
                        foreach($qds as $kk=>$vv){
                            $qd_silver_coin_num=$qd_rule[$vv['vips_qd']]['reward_silver_multiple']*$jyb;//区代所得银币
                            if($qd_silver_coin_num>0){
                                $recharge[]=account_arr(4,$vv['id'],$qd_silver_coin_num,'区域内会员充值元宝',$now);//明细
                                $res_qd=$member_model->where(['id'=>$vv['id']])->setInc('silver_coin',$qd_silver_coin_num);//改会员表
                                if (!$res_qd) {
                                    $sql = $start->_sql();
                                    throw new  \Exception($sql);
                                }
                            }

                        }
                    }

                }else{
                    throw new  \Exception('订单状态有误!');
                }
            }else if ($attach == 2) {  //充值会员2**
                $order =  $this->_order_recharge;
                $info = $order->where(array('dingdan' => $sn))->find();
                $uid = $info['uid'];

                if ($info['status'] == 1) {
                    //更改订单状态
                    $res_order = $order->where(array('id' => $info['id']))->save(array('status' => 2, 'pay_time' => $now, 'zftype' => 1));
                    if (!$res_order) {
                        throw new  \Exception('更改订单状态失败');
                    }
                    //会员信息
                    $member = $member_model->find($uid);
                    $old_vip=$info['old_vip'];
                    $after_vip=$info['after_vip'];
                    //等级规则表
                    $gr_rule = $gr_modle->getField('id,name,upgrade_price,upgrade_condition
                     ,upgrade_one_price,seller_none_silve,tj_acer_pay_silver,tj_acer_silver,upgrade_silver,upgrade_fruit');

                    //本人币种变动&&本人明细
                    $upgrade_fruit=$gr_rule[$after_vip]['upgrade_fruit'];
                    $upgrade_silver=$gr_rule[$after_vip]['upgrade_silver'];
                    if($upgrade_fruit>0){//加金果
                        $recharge[] = account_arr(3, $uid, $upgrade_fruit, '升级会员奖励金果', $now);
                        $save['gold_fruit'] = $member['gold_fruit'] + $upgrade_fruit;
                    }
                    if($upgrade_silver>0){//加银币
                        $recharge[] = account_arr(4, $uid, $upgrade_silver, '升级会员奖励银币', $now);
                        $save['silver_coin'] = $member['silver_coin'] +$upgrade_silver;
                    }
                    $save['vips']=$info['after_vip'];//级别
                    $res_member = $member_model->where(array('id' => $uid))->save($save);
                    if (!$res_member) {
                        throw new  \Exception('修改会员信息失败');
                    }
                    //本人币种变动&&本人明细
//                    $bl = D('GradeRule')->where(array('id' => $info['after_vip']))->field('id,upgrade_silver,upgrade_one_price,upgrade_two_price')->find();
//                    $save['vips'] = $info['after_vip'];
//                    $save['silver_coin'] = $member['silver_coin'] + $bl['upgrade_silver'];
//                    $res_member = $member_model->where(array('id' => $uid))->save($save);//银币
//                    if (!$res_member) {
//                        throw new  \Exception('修改会员信息失败');
//                    }
//                    $recharge[] = account_arr(4, $uid, $bl['upgrade_silver'], '升级掌柜赠送', $now);

                    //推荐人得奖励(加工资)
                    if ($member['relation_id']) {
                        $relation_member = $member_model->where(array('id' => $member['relation_id']))->field('id,vips')->find();
                        $relation_price=$gr_rule[$relation_member['vips']]['upgrade_one_price'];
                        #余额明细
                        if ($relation_price > 0) {
                            $res_relation = $member_model->where(array('id' => $member['relation_id']))->setInc('prices', $relation_price);
                            if (!$res_relation) {
                                throw new  \Exception('修改上线会员信息失败');
                            }
                            $recharge[] = account_arr(1, $member['relation_id'], $relation_price, '下线成为掌柜', $now);
                        }
                    }
                    //推荐人得奖励(加工资)
//                    if ($member['relation_id']) {
//                        #查找推荐人
//                        $relation_member = $member_model->where(array('id' => $member['relation_id']))->field('id,vips')->find();
//                        #推荐人赠送金额
//                        $relation_bl = D('GradeRule')->where(array('id' => $relation_member['vips']))->field('id,upgrade_one_price,upgrade_two_price')->find();
//                        if ($attach_arr['vips'] == 2) {
//                            $field = 'upgrade_one_price';
//                        } else {
//                            $field = 'upgrade_two_price';
//                        }
//                        if($relation_bl[$field]>0){
//                            $res_relation=$member_model->where(array('id' => $member['relation_id']))->setInc('prices', $relation_bl[$field]);
//                            if (!$res_relation) {
//                                throw new  \Exception('修改上线会员信息失败');
//                            }
//                            //上线余额明细
//                            $recharge[] = account_arr(1, $member['relation_id'], $relation_bl[$field], '下线升级', $now);
//                        }
//
//
//                    }

                    //各区代得工资&&明细
                    $province_id=$member['province_id'];
                    $city_id=$member['city_id'];
                    $district_id=$member['district_id'];
                    $where['is_qd']=1;//vips_qd区代等级 1区2市3省
                    $where['_string']='(vips_qd =3 and province_id ='.$province_id.')'
                        .'or ( vips_qd =2 and  city_id='. $city_id.')'
                        .'or ( vips_qd =1 and district_id='.$district_id.')';
                    $qds=$member_model->where($where)->select();//区代列表
                    $qd_rule=M('qd_rule')->getField('id,name,reward_silver_multiple,upgrade_recharge');//区代等级规则
                    if (!empty($qds)) {
                        foreach($qds as $kk=>$vv){
                            $qd_price=$qd_rule[$vv['vips_qd']]['upgrade_recharge'];//区代所得工资
                            if($qd_price>0){
                                $recharge[]=account_arr(1,$vv['id'],$qd_price,'区域内会员成为掌柜',$now);//明细
                                $res_qd=$member_model->where(['id'=>$vv['id']])->setInc('prices',$qd_price);//改会员表
                                if (!$res_qd) {
                                    throw new  \Exception('修改区代信息失败');
                                }
                            }

                        }
                    }

                }else{
                    throw new  \Exception('订单状态有误!');
                }
            }else{
                throw new  \Exception('微信附加参数有误!');
            }
            //添加所有明细
            $res_account = $this->_account->addAll($recharge);
            if (!$res_account) {
                throw new  \Exception('添加明细失败');
            }

            $start->commit();
        } catch (\Exception $e) {

            $error = 'Message['.date('Y-m-d H:i:s').']: ' . $e->getMessage().'最后一条sql:'.$start->_sql();
            file_put_contents('Api_Error.txt', $error . PHP_EOL, FILE_APPEND);
            $start->rollback();
        }
    }




    //支付宝课程同步通知
    public function alipay_return(){
        header("Content-Type:text/html;charset=utf-8");
        require_once("_core/Library/Vendor/pay/alipay.config.php");
        import("Vendor.pay.lib.alipay_notify#class",'', '.php');

        //计算得出通知验证结果
        $alipayNotify = new \AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyReturn();

        if($verify_result) {
            //验证成功
            //商户订单号
            $out_trade_no = $_GET['out_trade_no'];
            //支付宝交易号
            $trade_no = $_GET['trade_no'];
            //交易状态
            $trade_status = $_GET['trade_status'];
            if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //如果有做过处理，不执行商户的业务程序

                $order = M('CurriculumOrder');
                //订单详情
                $info = $order->where(array('order_sn'=>$out_trade_no))->find();
                if($info['status'] == 1){
                    //更改订单状态
                    $order->save(array(
                        'id' => $info['id'],
                        'status' => 2,
                        'pay_time' => time(),
                        'pay_type' => 1
                    ));
                    //增加预约人数
                    M('Curriculum')->where(array('id'=>$info['c_id']))->setInc('people');
                    //跳转到成功页面
                    $url = "http://".$_SERVER['SERVER_NAME']."/index.php?m=Home&c=Order&a=payOk&id=".$info['id'];
                    header("Location: $url");
                }
            } else {
                echo "trade_status=".$_GET['trade_status'];
            }

            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
        }else {
            //验证失败
            //如要调试，请看alipay_notify.php页面的verifyReturn函数
            echo "验证失败";
        }

    }

	 /**
     * 验证
	  * 
     * @return array 返回数组格式的notify数据
     */
    public function notify(){
        // 获取xml
        $xml = file_get_contents('php://input', 'r');
		
//		用于查看			
//		import("Vendor.Wxpay.example.log", '', '.php');
//	    if(!file_exists("weixin/")){
//        mkdir("weixin/",0777,true);
//    	}
//
//		$logHandler = new \CLogFileHandler('weixin/'.date('Y-m-d').'.log');
//		$log = \Log::Init($logHandler, 15);
//		\Log::DEBUG("begin notify");
//		\Log::DEBUG($xml);
//		\Log::DEBUG("end notify");  
		


        // 转成php数组
        $data = $this->toArray($xml); 
          if ($result) {
            $str = '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
        } else {
            $str = '<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[签名失败]]></return_msg></xml>';
        }  
        echo $str;
        return $data;
        
		
        // 保存原sign
//      $data_sign = $data['sign']; 
        // sign不参与签名
//      unset($data['sign']);
//      $sign=$this->makeSign($data); 
//      // 判断签名是否正确  判断支付状态
//      if ($sign === $data_sign && $data['return_code'] == 'SUCCESS' && $data['result_code'] == 'SUCCESS') {
//          $result = $data;
//      }else{
//          $result = false;  
//      }
		
        // 返回状态给微信服务器
        if ($result) {
            $str = '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
        } else {
            $str = '<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[签名失败]]></return_msg></xml>';
        }  
        echo $str;
        return $result;   
    }
	
	 /**
     * 将xml转为array
     * @param  string $xml xml字符串
     * @return array  转换得到的数组
     */
    public function toArray($xml){    
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true); 
        $result= json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);         
        return $result;
    } 
	
	  /**
     * 生成签名
     * @return 签名，本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
     */
    public function makeSign($data){  
        // 去空
        $data=array_filter($data);
        //签名步骤一：按字典序排序参数
        ksort($data);
        $string_a=http_build_query($data);
        $string_a=urldecode($string_a); 
        
        //签名步骤二：在string后加入KEY
        $config['KEY'] = 'c9c2a81f6c8869eda1ee8f3c5433ad39';
        $string_sign_temp=$string_a."&key=".$config['KEY'];
        //签名步骤三：MD5加密
        $sign = md5($string_sign_temp);
        // 签名步骤四：所有字符转为大写
        $result=strtoupper($sign); 
        return $result; 
    }
	
	
}