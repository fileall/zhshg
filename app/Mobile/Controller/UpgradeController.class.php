<?php
namespace Mobile\Controller;

/**升级
 * Class UpgradeController
 * @package Mobile\Controller
 */
class UpgradeController extends HomeController
{
    public function _initialize()
    {
        parent::_initialize();
        $this->uid = is_login();
        if (!$this->uid) {
            $this->redirect('Login/enter');
        }
        $this->_member = D('Member');
        $this->_account = D('Account');
        $this->_grade_rule=D('GradeRule');


        $member = $this->_member->find($this->uid);
        $this->assign('member', $member);


    }


    //充值会员(升级)
    public function upgrade(){
        $member=$this->get('member');

        $gr = $this->_grade_rule->getField('id,name,upgrade_price,upgrade_condition
            ,upgrade_one_price,seller_none_silve,tj_acer_pay_silver,tj_acer_silver');
        $member['vips_name']=$gr[ $member['vips']]['name'];
        $vips_next=$gr[($member['vips']+1)];

        $this->assign('member', $member);
        $this->assign('vips_next', $vips_next);
        $this->display();

    }

    //充值会员》生成订单=>跳转支付界面
    public function make_order_upgrade(){
        $pos = I('post.');
        $uid=$this->uid;
        $now= time();
        $after_vip=$pos['after_vip'];
        $gr_modle=$this->_grade_rule;
        $upgrade_recharge=M('order_recharge');
        //等级规则表
        $gr_rule = $gr_modle->getField('id,name,upgrade_price,upgrade_condition
            ,upgrade_one_price,seller_none_silve,tj_acer_pay_silver,tj_acer_silver');
        $totalprices=$gr_rule[$after_vip]['upgrade_price'];//升级所需金额
        $member=$this->get('member');
        $member_next=$this->_member->where(['relation_id'=>$uid,'vips'=>['egt',2]])->count();//下线掌柜人数
        //升级验证(只能升下一级、升级有下线人数限制)
        ($member['vips']>=$after_vip)&&$this->ajaxReturn(['status'=>-1, 'msg'=>'您已是'.$gr_rule[$after_vip]['name'],'url'=>U('upgrade/upgrade')]);
        ($member_next < $gr_rule[$after_vip]['upgrade_condition'])&&$this->ajaxReturn(['status'=>0,'msg'=>'您推荐的掌柜人数不足']);

        //不允许3s内重复生成类似订单
        $map=['totalprices'=>$totalprices,'status'=>1,'uid'=>$uid,'after_vip'=>$after_vip];
        $map['add_time']=['gt',$now-3];
        $over_order=$upgrade_recharge->where($map)->count();
        $over_order&&$this->ajaxReturn(['status'=>0,'dingdan'=>0,'msg'=>'请勿重复提交']);

        $data['dingdan']=create_order_sn();//生成订单号
        $data['uid'] = $uid;
        $data['totalprices'] = $totalprices;
        $data['zftype']=0;//'支付方式  0.未选择 1=微信，2=支付宝',
        $data['status']=1;//支付状态 1待支付 2支付成功 3支付失败',
        $data['add_time']=$now;
        $data['old_vip']=$member['vips'];
        $data['after_vip']=$after_vip;


        $yd=$upgrade_recharge->add($data);//生成临时订单
        if($yd)
            $this->ajaxReturn(['status'=>1,'msg'=>'订单提交成功','url'=>U('upgrade/upgrade_pay',['dingdan'=>$data['dingdan'],'money'=>$totalprices,'oid'=>$yd])]);
        else
            $this->ajaxReturn(['status'=>0,'msg'=>'订单提交失败']);

    }

    //充值元宝》支付界面
    public function upgrade_pay()
    {
        $appid=C('WX_CONFIG.appid');
        $appsecret=C('WX_CONFIG.appsecret');

        $wx = new \Mobile\Org\WeiXinAbout();
        $openid = $wx->get_appid($appid, $appsecret);
        session('openid', $openid);

        $this->assign('dingdan',I('dingdan'));
        $this->assign('money',I('money'));
        $this->assign('oid',I('oid'));
        $this->display();

    }

    //充值会员》余额购买
    public function upgrade_ye(){
        $uid=$this->uid;
        $pos=I('post.');
        $now= time();
        $oid=$pos['oid'];

        $order = M('order_recharge');//升级记录表
        $gr_modle=$this->_grade_rule;
        $member_model=$this->_member;
        $mem=$this->get('member');

        (!$pos['pas'])&& $this->ajaxReturn(['status'=>0,'msg'=>'请输入支付密码']);
        (!$mem['paypassword'])&& $this->ajaxReturn(['status'=>0,'msg'=>'您还未设置支付密码']);
        (st_md5($pos['pas']) != $mem['paypassword'])&& $this->ajaxReturn(['status'=>0,'msg'=>'支付密码有误']);

        $info = $order->find($oid);
        ($mem['prices']< $info['totalprices'])&& $this->ajaxReturn(['status'=>0,'msg'=>'余额不足']);

        $old_vip=$info['old_vip'];
        $after_vip=$info['after_vip'];
        $start = M();
        $start->startTrans();
        //更改订单状态
        $res_order = $order->where(array('id' => $oid))
            ->save(array('status' => 2, 'pay_time' => $now, 'zftype' => 3));

        if (!$res_order) {
            $start->rollback();
            $this->ajaxReturn(['status'=>0,'msg'=>'系统繁忙']);
        }

        //等级规则表
        $gr_rule = $gr_modle->getField('id,name,upgrade_price,upgrade_condition
            ,upgrade_one_price,seller_none_silve,tj_acer_pay_silver,tj_acer_silver,upgrade_silver,upgrade_fruit');

        //币种变动&&本人明细
        $totalprices=$info['totalprices'];
        $save['prices'] = $mem['prices'] -$totalprices;//减余额


        $recharge[] = account_arr(1, $uid, '-'.$totalprices, '升级会员:'.$gr_rule[$after_vip]['name'], $now);
        $upgrade_fruit=$gr_rule[$after_vip]['upgrade_fruit'];
        $upgrade_silver=$gr_rule[$after_vip]['upgrade_silver'];

        if($upgrade_fruit>0){//加金果
            $recharge[] = account_arr(3, $uid, $upgrade_fruit, '升级会员奖励金果', $now);
            $save['gold_fruit'] = $mem['gold_fruit'] + $upgrade_fruit;
        }

        if($upgrade_silver>0){//加银币
            $recharge[] = account_arr(4, $uid, $upgrade_silver, '升级会员奖励银币', $now);
            $save['silver_coin'] = $mem['silver_coin'] +$upgrade_silver;
        }
        $save['vips']=$info['after_vip'];//级别

        //修改本人
        $res_member = $member_model->where(array('id' => $uid))->save($save);

        if (!$res_member) {
            $start->rollback();
            $this->ajaxReturn(['status'=>0,'msg'=>'系统繁忙']);
        }


        //推荐人得奖励(加工资)
        if ($mem['relation_id']) {
            $relation_member = $member_model->where(array('id' => $mem['relation_id']))->field('id,vips')->find();
            $relation_price=$gr_rule[$relation_member['vips']]['upgrade_one_price'];
            #余额明细
            if ($relation_price > 0) {
                $res_relation = $member_model->where(array('id' => $mem['relation_id']))->setInc('prices', $relation_price);
                if (!$res_relation) {
                    $start->rollback();
                    $this->ajaxReturn(['status'=>0,'msg'=>'系统繁忙']);
                }
                $recharge[] = account_arr(1, $mem['relation_id'], $relation_price, '下线成为掌柜', $now);
            }
        }

        //各区代得银币&&明细
        if($after_vip==2){//第一次成为掌柜才有
            $province_id=$mem['province_id'];
            $city_id=$mem['city_id'];
            $district_id=$mem['district_id'];
            $where['is_qd']=1;//vips_qd区代等级 1区2市3省
            $where['_string']='(vips_qd =3 and province_id ='.$province_id.')'
                .'or ( vips_qd =2 and  city_id='. $city_id.')'
                .'or ( vips_qd =1 and district_id='.$district_id.')';
            $qds=$member_model->where($where)->select();//区代列表
            $qd_rule=M('qd_rule')->getField('id,name,reward_silver_multiple,upgrade_recharge');//区代等级规则
            if (!empty($qds)) {
                foreach($qds as $kk=>$vv){
                    $qd_price=$qd_rule[$vv['vips_qd']]['upgrade_recharge'];//区代所得银币
                    if($qd_price>0){
                        $recharge[]=account_arr(1,$vv['id'],$qd_price,'区域内会员成为掌柜',$now);//明细
                        $res_qd=$member_model->where(['id'=>$vv['id']])->setInc('prices',$qd_price);//改会员表
                        if (!$res_qd) {
                            $start->rollback();
                            $this->ajaxReturn(['status'=>0,'msg'=>'系统繁忙']);
                        }
                    }

                }
            }
        }


        //添加所有明细
        $res_account = $this->_account->addAll($recharge);

        if (!$res_account) {
            $start->rollback();
            $this->ajaxReturn(['status'=>0,'msg'=>'系统繁忙']);
        }
        $start->commit();
        $this->ajaxReturn(['status'=>1,'msg'=>'充值成功','url'=>U("wallet/wallet")]);

    }


    //充值元宝》第三方支付
    public function upgrade_api_pay()
    {
        $pos = I('post.');
        $price=M('order_recharge')->where(['dingdan'=>$pos['dingdan']])->getField('totalprices');
        //第三方记录表
//        $add['dingdan']=create_order_sn();//生成订单号
//        $add['uid'] = $this->uid;
//        $add['oid'] = $this->uid;
//        $add['totalprices'] = $price;
//        $add['type'] = 1;//1为支付 2为充值',
//        $add['zftype']=1;//'支付方式  0.未选择 1=微信，2=支付宝',
//        $add['status']=1;//支付状态 1待支付 2支付成功 3支付失败',
//        $add['add_time']=time();
//        $res_order=M('order_recharge')->add($add);
//        (!$res_order)&&$this->ajaxReturn(['status'=>0,'msg'=>'系统繁忙']);

        $item_type = 2; //1充值元宝 2会员升级
        $attach = json_encode(array('item_type' => $item_type));

        $data['attach'] = $attach;
        $data['body'] = '会员升级';
        $data['number'] = $pos['dingdan'];
        $data['price'] =  $price* 100;//单位分
//        $data['price'] = 0.01 * 100;//单位分
        $data['openid'] = session('openid');
        $config = A('Api/Wxpay')->orderParameter($data);//回调里：价钱，该订单状态，改支付方式

        if ($config['err_code'] != 0) {
            echo json_encode(array('err_code' => 1, 'err_msg' => $config['err_msg']));
        } else {
            echo json_encode(array('err_code' => 0, 'err_msg' => $config));
        }
        exit;

    }


}?>