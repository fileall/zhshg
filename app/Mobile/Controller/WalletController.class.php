<?php

namespace Mobile\Controller;
use Think\Page;
class WalletController extends HomeController
{
    public function _initialize()
    {
        parent::_initialize();
        $this->uid = is_login();
        if (!$this->uid) {
            $this->redirect('Login/enter');
        }
        $this->_member = D('Member');
        $this->_merchant= D('Merchant');
        $this->_account = D('Account');
        $this->_account_shop = D('AccountShop');


        $member = $this->_member->find($this->uid);
        $this->assign('member', $member);
    }


    //**********************我的钱包 开始***********************************
    //个人中心  我的钱包
    public function wallet(){
        $member=$this->get('member');
        if($member['is_qd']==1){//如果是区代需要回到区代页面
            $this->redirect('agent/agent');
        }
        $this->display();
    }

    //我的钱包》金元宝(内容在ajax_bz_liu)
    public function w_ingotA(){
        $this->assign('type',2);
        $this->display();
    }

    //我的钱包》金果(内容在ajax_bz_liu)
    public function fruit(){
        $this->assign('type',3);
        $this->display();

    }

    //我的钱包》余额
    public function balance(){
        //是否具有提现资格1是掌柜 或 推荐满5人
        $member=$this->get('member');
        $next_count=$this->_member->where(['relation_id'=>$member['id']])->count();
//        $is_tx=($member['vips']> 1||$next_count > 4)?1:0;

        $is_tx=0;
        (($member['id'] ==1) || $member['id']==306)&&$is_tx=1;
        $is_tx=1;
        $this->assign('is_tx',$is_tx);
        $this->display();
    }

    //我的钱包》银币
    public function coin(){
        $this->assign('type',4);
        $this->display();
    }

    //我的钱包》币种流水(去掉：ajax_bz_liu?)
    public function account(){
        $type=I('type');//币种1工资2金元宝3金果4银币
        $member_id=$this->uid;
        switch($type){
            case 1:$item_type=1;$field='工资';break;
            case 2:$item_type=2;$field='元宝';break;
            case 3:$item_type=3;$field='金果';break;
            case 4:$item_type=4;$field='银币';break;
            default: echo "No find";
        }
        $account=$this->_account;
        $page = I('page');
        $map=array('uid'=>$member_id,'type'=>$item_type);
        $count=$account->where($map)->order('id desc')->count();//总条数

        if(IS_AJAX){
            $pages =   ceil($count/ 8); //上取整    总条数/每页条数
            $list_aj[0] = $pages;//总页数
            //每页数据
            $list_ajs=$account->where($map)->order('id desc')->limit(($page-1) * 8 , 8)->select();
            $this->assign('a',$list_ajs);
            $a=$this->fetch('w_bz_liu');

            $list_aj[1] = $a;//数据
            echo  json_encode($list_aj);
        }else{

            $this->assign('is_kk',$count?0:1);//数据为空 1是
            $this->assign('field',$field);
            $this->assign('type',$type);
            $this->display();
        }
    }

    //流加载 币种流水
    public function ajax_bz_liu(){
        $member_id=$this->uid;
        $type=I('type');//币种1工资2金元宝3金果4银币
        switch($type){
            case 1:$item_type=1;break;
            case 2:$item_type=2;break;
            case 3:$item_type=3;break;
            case 4:$item_type=4;break;
            default: echo "No find";
        }
        $account=D('Account');
        $page = I('page');
        $map=array('uid'=>$member_id,'type'=>$item_type);
        //总页数
        $count=$account->where($map)->order('id desc')->count();

        $pages =   ceil($count/ 8); //上取整    总条数/每页条数
        $list_aj[0] = $pages;//总页数
        //每页数据
        $list_ajs=$account->where($map)->order('id desc')->limit(($page-1) * 8 , 8)->select();

        $this->assign('a',$list_ajs);
        $a=$this->fetch('w_bz_liu');

        $list_aj[1] = $a;//数据

        echo  json_encode($list_aj);
    }


    //我的钱包》余额提现0
    public function balance_extract0(){
        $uid=$this->uid;

        if(IS_POST){//提现
            $pos = I('post.');
            $prices=$pos['nums'];
            $now=time();
            $withdraw_mod=M('withdraw_member');
            $member = $this->get('member');

            !$pos['card_id']&&$this->ajaxReturn(['status'=>0,'msg'=>'请选择银行卡!']);
            (!$pos['pas'])&& $this->ajaxReturn(['status'=>0,'msg'=>'请输入支付密码']);
            (!$member['paypassword'])&& $this->ajaxReturn(['status'=>0,'msg'=>'您还未设置支付密码']);
            (st_md5($pos['pas']) != $member['paypassword'])&& $this->ajaxReturn(['status'=>0,'msg'=>'支付密码有误']);

            //余额是否足够
            $service_charge=C('pin_tx_sxf');
            $dec_money=$prices+$service_charge;
            ($member['prices'] < $dec_money)&& $this->ajaxReturn(['status'=>0,'msg'=>'您的余额不足!']);

            //单次提现上限(单位万)
            $meibi = C('pin_tx_db_je')*10000;
            ($prices > $meibi)&& $this->ajaxReturn(['status'=>0,'msg'=>'抱歉,单笔最高提现金额为:'.$meibi.'!']);

            //验证提现时间是否在周期内
//            $tx_day=C('pin_tx_zq');
//            if($tx_day>0){
//                $last_day = $withdraw_mod->where(['uid'=>$uid])->field('max(add_time)')->find();//上一单时间
//                $last_day&&($now-24*3600*$tx_day < $last_day['max(add_time)'])&& $this->ajaxReturn(['status'=>0,'msg'=>'提现周期为每三天一次!']);
//            }

            //不允许3s内重复操作
            $map=['totalprices'=>$prices,'uid'=>$uid,'status'=>1];
            $map['add_time']=['gt',$now-3];
            $over_order=$withdraw_mod->where($map)->count();
            $over_order&&$this->ajaxReturn(['status'=>0,'msg'=>'请勿重复提交']);

            //验证每日提现金额上限
            $time_start= strtotime(date('Y-m-d', $now));//今天0点时间戳s
            $time_end= $time_start+24*3600;
            $time_rule['add_time'] =  array('between',array($time_start,$time_end));
            $time_rule['uid'] = $uid;
            $time_rule['status']=array('in',array(1,3));

            $history_money = $withdraw_mod->where($time_rule)->getField('sum(totalprices) as all_money');
            $prices_today = $prices+ $history_money;
            $sx = C('pin_tx_mr_je')*10000;
            ($prices_today >$sx)&& $this->ajaxReturn(['status'=>0,'msg'=>'抱歉,每日最高提现金额为:'.$sx.'!']);


            $card = M('member_bankcard')->where(['id'=>$pos['card_id']])->field('id',true)->find();//银行卡id
            $data=$withdraw_mod->create($card);
            $data['uid']=$uid;
            $data['totalprices']=$prices;//用户提现金额
            $data['service_charge']=$service_charge;
            $data['status']=1;//状态 1未审核 2通过 3驳回'
            $data['add_time']=$now;

            $start=M();
            $start->startTrans();
            $yd=$withdraw_mod->add($data);	//提交审核

            $yd&&$res_member=$this->_member->where(array('id'=>$uid))->setDec('prices',$dec_money);//扣用户余额
            $data_account=account_arr(1,$uid,'-'.$dec_money,'工资提现',$now);//明细
            $res_member&&$account_arr=M('account')->add($data_account);

            if(!$yd || !$res_member || !$account_arr){
                $start->rollback();
                $this->ajaxReturn(['status'=>0,'msg'=>'提交申请失败!']);
            }

            $start->commit();
            $this->ajaxReturn(['status'=>1,'msg'=>'提交申请成功!','url'=>U('wallet/wallet')]);

        }else{
            $bank_card_modle=D('MemberBankcard');
            $card = $bank_card_modle->where(array('member_id'=>$uid,'status'=>1))->order('is_default desc,id desc')->select();
            foreach($card as $k=>$v){
                $card[$k]['nums'] = substr($v['nums'],-4);
            }

            $this->assign('card',$card);
            $this->display();
        }


    }
    //提现：1会员工资2商家元宝3商家金果4区代工资
    public function balance_extract(){
        $uid=$this->uid;
        if(IS_POST){
            $pos = I('post.');
            $prices=$pos['nums'];
            $page_type=$pos['page_type'];//请求的页面：1会员工资2商家元宝3商家金果4区代工资

            $now=time();
            $withdraw_mod=M('withdraw_member');//申请表
            $account_model=M('account');//明细表
            $wallet_model=M('member');//钱包
            $wallet = $this->_member->find($uid);//提现对象数据
            $paypassword=$wallet['paypassword'];
            $service_charge=0;
            switch($page_type){
                case 1:
                    $account_model=M('account');//明细表
                    $service_charge=C('pin_tx_sxf');
                    $memos="工资提现";
                    $field='prices';
                    break;
                case 2:
                    $withdraw_mod=M('withdraw_merchant');//申请表
                    $account_model=M('account_shop');//明细表
                    $wallet_model=M('merchant');//钱包
                    $wallet=$this->_merchant->where(['uid'=>$uid])->find();
                    $memos="商家元宝提现";
                    $field='gold_acer';
                    break;
                case 3:
                    $withdraw_mod=M('withdraw_merchant');//申请表
                    $account_model=M('account_shop');//明细表
                    $wallet_model=M('merchant');//钱包
                    $wallet=$this->_merchant->where(['uid'=>$uid])->find();
                    $memos="商家金果提现";
                    $field='gold_fruit';

                    break;
                case 4:
                    $withdraw_mod=M('withdraw_qd');//申请表
                    $memos="区代工资提现";
                    $field='prices';
                    break;
                default:
                    $this->ajaxReturn(['status'=>0,'msg'=>'系统繁忙!']);
            }



            !$pos['card_id']&&$this->ajaxReturn(['status'=>0,'msg'=>'请选择银行卡!']);
            (!$pos['pas'])&& $this->ajaxReturn(['status'=>0,'msg'=>'请输入支付密码']);
            (!$paypassword)&& $this->ajaxReturn(['status'=>0,'msg'=>'您还未设置支付密码']);
            (st_md5($pos['pas']) != $paypassword)&& $this->ajaxReturn(['status'=>0,'msg'=>'支付密码有误']);

            //验证每笔提现金额上限
            $tx_db_je=C('pin_tx_db_je')*10000;
            ($prices >$tx_db_je)&& $this->ajaxReturn(['status'=>0,'msg'=>'每笔提现金额上限:'.$tx_db_je.'!']);

            //余额是否足够
            $dec_money=$prices+$service_charge;
            ($wallet[$field] < $dec_money)&& $this->ajaxReturn(['status'=>0,'msg'=>'您的余额不足!']);

            //验证提现时间是否在周期内
//            $tx_day=C('pin_tx_zq');
//            if($tx_day>0){
//                $last_day = $withdraw_mod->where(['uid'=>$uid])->field('max(add_time)')->find();//上一单时间
//                $last_day&&($now-24*3600*$tx_day < $last_day['max(add_time)'])&& $this->ajaxReturn(['status'=>0,'msg'=>'提现周期为每三天一次!']);
//            }

            //不允许3s内重复操作
            $map=['totalprices'=>$prices,'uid'=>$uid,'status'=>1];
            $map['add_time']=['gt',$now-3];
            $over_order=$withdraw_mod->where($map)->count();
            $over_order&&$this->ajaxReturn(['status'=>0,'msg'=>'请勿重复提交']);


            //验证每日提现金额上限
//            $time_start= strtotime(date('Y-m-d', $now));//今天0点时间戳s
//            $time_end= $time_start+24*3600;
//            $time_rule['add_time'] =  array('between',array($time_start,$time_end));
//            $time_rule['uid'] = $uid;
//            $time_rule['status']=array('in',array(1,3));
//            $history_money = $withdraw_mod->where($time_rule)->getField('sum(totalprices) as all_money');
//            $prices_today = $prices+ $history_money;
//            $sx = C('pin_tx_mr_je')*10000;
//            ($prices_today >$sx)&& $this->ajaxReturn(['status'=>0,'msg'=>'抱歉,每日最高提现金额为:'.$sx.'!']);


            $card = M('member_bankcard')->where(['id'=>$pos['card_id']])->field('id',true)->find();//银行卡id
//            dump($card);die;
            $data=$withdraw_mod->create($card);
            $data['uid']=$uid;
            $data['shop_id']=$wallet['id'];
            $data['totalprices']=$prices;//用户提现金额
            $page_type==1&&$data['service_charge']=$service_charge;//手续费
            $page_type==2&&$data['type']=1;
            if($page_type==3){
                $data['type']=2;$data['fruit_price']=C('pin_jg_scj');//当时金果价
            }
            $data['status']=1;//状态 1未审核 2通过 3驳回'
            $data['add_time']=$now;

            $start=M();
            $start->startTrans();
            $yd=$withdraw_mod->add($data);	//提交审核订单

            $yd&&$res_member=$wallet_model->where(array('id'=>$wallet['id']))->setDec($field,$dec_money);//扣余额
            if($page_type==2||$page_type==3)
                $data_account=account_shop_arr($page_type,$wallet['id'],'-'.$dec_money,$memos,$now);//明细
            else
                $data_account=account_arr(1,$uid,'-'.$dec_money,$memos,$now);//明细

            $res_member&&$account_arr=$account_model->add($data_account);

            if(!$yd || !$res_member || !$account_arr){
                $start->rollback();
                $this->ajaxReturn(['status'=>0,'msg'=>'提交申请失败!']);
            }

            $start->commit();
            $this->ajaxReturn(['status'=>1,'msg'=>'提交申请成功!','url'=>U('member/mine')]);



        }else{
            //请求的页面
            $page_type=I('page_type');//请求的页面：1会员工资2商家元宝3商家金果4区代工资
            $this->assign('page_type',$page_type);

            //银行卡
            $bank_card_modle=D('MemberBankcard');
            $card = $bank_card_modle->where(array('member_id'=>$uid,'status'=>1))->order('is_default desc,id desc')->select();
            foreach($card as $k=>$v){
                $card[$k]['nums'] = substr($v['nums'],-4);
            }
            $this->assign('card',$card);

            //可用金额
            if($page_type==2){
                $modle=M('merchant');$field="gold_acer";$map['uid']=$uid;
            }else if($page_type==3){
                $modle=M('merchant');$field="gold_fruit";$map['uid']=$uid;
            }else{
                $modle=M('member');$field="prices";$map['id']=$uid;
            }
            $prices=$modle->where($map)->getField($field);
            $this->assign('prices',$prices);


            $this->display();
        }


    }


    //我的钱包》1金果转好友 && 2元宝转好友
    public function transfer_to_friend(){
        $transfer_type=I('transfer_type');//1元宝2金果
        if(!in_array($transfer_type,[1,2])){
            IS_AJAX&& exit(json_encode(array('status'=>0,'msg'=>'系统繁忙')));
            $this->redirect('wallet/wallet');
        }
        $field=($transfer_type==1)?'元宝':'金果';
        $this->assign('field',$field);

        if(IS_POST){
            $pos = I('post.');

            $uid=$this->uid;
            $now= time();
            $nums=$pos['nums'];
            if($transfer_type == 1){
                $pin_yb_sxf = C('pin_yb_sxf')/100;//元宝转好友手续费%
                $dec_nums = $nums*($pin_yb_sxf+1);
            }else{
                $pin_jg_sxf = C('pin_jg_sxf')/100;//金果转好友手续费%
                $dec_nums = $nums*($pin_jg_sxf+1);
            }
            $account=$this->_account;//用户币种明细表

            $member = $this->_member->find($uid);//己方
            $info = $this->_member->where(array('mobile'=>$pos['mobile']))->find();//对方
            //不允许3s内重复操作
            $map=['totalprices'=>$nums,'uid'=>$uid,'type'=>3,'add_time'=>['gt',$now-3]];
            $over_order=$account->where($map)->count();
            $over_order&&$this->ajaxReturn(['status'=>0,'msg'=>'请勿重复提交']);

            !$info && exit(json_encode(array('status'=>0,'msg'=>'对方手机号不存在')));
            !$info['status'] && exit(json_encode(array('status'=>0,'msg'=>'对方账号已被冻结')));
            ($member['mobile'] == $pos['mobile']) && exit(json_encode(array('status'=>0,'msg'=>'请确定对方手机号码是否正确')));
            (!$pos['pas'])&& $this->ajaxReturn(['status'=>0,'msg'=>'请输入支付密码']);
            (!$member['paypassword'])&& $this->ajaxReturn(['status'=>0,'msg'=>'您还未设置支付密码']);
            (st_md5($pos['pas']) != $member['paypassword'])&& $this->ajaxReturn(['status'=>0,'msg'=>'支付密码有误']);
            $nums<0 && exit(json_encode(array('status'=>0,'msg'=>'请输入金额')));
            ($member['gold_fruit'] <$dec_nums) && exit(json_encode(array('status'=>0,'msg'=>'您的余额不足')));

            if($transfer_type==1){
                $field='gold_acer';$memos='元宝互转';$type=2;//币种1工资2金元宝3金果4银币'
            }else{
                $field='gold_fruit';$memos='金果互转';$type=3;
            }
            $start = M();
            $start->startTrans();

            $res_self=$this->_member->where(array('id'=>$uid))->setDec($field,$dec_nums);//己方减
            $res_other=$this->_member->where(array('id'=>$info['id']))->setInc($field,$nums);//对方加
            if(!$res_self|| !$res_other){
                $start->rollback();
                $this->ajaxReturn(['status'=>0,'msg'=>'操作失败!']);
            }
            //己方流水、对方明细
            $recharge[] = account_arr($type, $uid,'-'.$dec_nums, $memos, $now,0,3,$pos['mobile']);//减金果
            $recharge[] = account_arr($type, $info['id'],$nums, $memos, $now);//加金果

            $res_account=$account->addAll($recharge);
            if($res_account){
                $start->commit();
                $this->ajaxReturn(['status'=>1,'msg'=>'操作成功','url'=> U('member/mine')]);
            }else{
                $start->rollback();
                $this->ajaxReturn(['status'=>0,'msg'=>'操作失败',]);
            }

        }else{

            $this->assign('transfer_type',$transfer_type);
            $this->display();
        }

    }

    //我的钱包》金果回购
    public function fruit_recycle(){
        if(IS_POST){
            $pos = I('post.');
            $uid=$this->uid;
            $now= time();
            $account=$this->_account;
            $member = $this->_member->find($uid);
            $nums=$pos['nums'];//金额
            //不允许3s内重复操作
            $map=['totalprices'=>$nums,'uid'=>$uid,'type'=>3];
            $map['add_time']=['gt',$now-3];
            $over_order=$account->where($map)->count();
            $over_order&&$this->ajaxReturn(['status'=>0,'msg'=>'请勿重复提交']);

            //验证
            (!$pos['pas'])&& $this->ajaxReturn(['status'=>0,'msg'=>'请输入支付密码']);
            (!$member['paypassword'])&& $this->ajaxReturn(['status'=>0,'msg'=>'您还未设置支付密码']);
            (st_md5($pos['pas']) != $member['paypassword'])&& $this->ajaxReturn(['status'=>0,'msg'=>'支付密码有误']);
            $nums<0 && exit(json_encode(array('status'=>0,'msg'=>'请输入金额')));
            ($member['gold_fruit'] <$nums) && exit(json_encode(array('status'=>0,'msg'=>'您的余额不足')));

            $start = M();
            $start->startTrans();
            //加余额减金果
            $prices=$nums * C('pin_jg_scj');
            $jj['gold_fruit'] =['exp',' gold_fruit -'.$nums];//减金果
            $jj['prices'] =['exp',' prices +'.$prices];//加余额
            $res=$this->_member->where(array('id'=>$uid))->save($jj);
            if(!$res){
                $start->rollback();
                $this->ajaxReturn(['status'=>0,'msg'=>'操作失败']);
            }
            //加余额减金果:明细
            $recharge[] = account_arr(3, $uid,'-'.$nums, '金果回购', $now);//减金果
            $recharge[] = account_arr(1, $uid,$prices, '金果回购', $now);//加余额

            $res_account=$account->addAll($recharge);
            if($res_account){
                $start->commit();
                $this->ajaxReturn(['status'=>1,'msg'=>'操作成功','url'=> U('member/mine')]);
            }else{
                $start->rollback();
                $this->ajaxReturn(['status'=>0,'msg'=>'操作失败',]);
            }

        }else{
            $this->display();
        }

    }



    //我的钱包》金果券
    public function fruit_cdkey(){
        if(IS_POST) {
            $uid=$this->uid;
            (!$code=I('code'))&& $this->ajaxReturn(['status'=>0,'msg'=>'请输入兑换码']);
            $fruit_coupon=M('fruit_coupon');
            $totalprices=$fruit_coupon->where(['code'=>$code,'status'=>1])->getField('totalprices');
            (!$totalprices)&& $this->ajaxReturn(['status'=>0,'msg'=>'输入错误,请重试']);

            $start = M();
            $start->startTrans();
            //加金果
            $res=$this->_member->where(['id'=>$uid])->setInc('gold_fruit',$totalprices);
            if(!$res){
                $start->rollback();
                $this->ajaxReturn(['status'=>0,'msg'=>'操作失败']);
            }
            //金果明细
            $recharge =account_arr(3, $uid,$totalprices, '金果券兑换', time());//加金果
            $res_account=$this->_account->add($recharge);
            //金果券失效 status 状态 1未使用 2已使用 -1禁用
            $res_coupon=$fruit_coupon->where(['code'=>$code])->setField('status',2);
            if(!$res_account||!$res_coupon){
                $start->rollback();
                $this->ajaxReturn(['status'=>0,'msg'=>'操作失败']);
            }

            $start->commit();
            $this->ajaxReturn(['status'=>1,'msg'=>'操作成功','url'=> U('wallet/fruit')]);
        }else{
            $this->display();
        }
    }

    //***********************我的钱包 结束************************************

    //**********************元宝充值  开始***************************************

    //充值元宝》选择金额界面
    public function acer_buy(){
        $list=M('buy_send')->select();

        $this->assign('list',$list);
        $this->display();
    }

    //充值元宝》支付界面
    public function acer_pay(){
        $appid=C('WX_CONFIG.appid');
        $appsecret=C('WX_CONFIG.appsecret');

        $wx = new \Mobile\Org\WeiXinAbout();
        $openid = $wx->get_appid($appid, $appsecret);
        session('openid', $openid);

        $param=I();
        unset($param['code'],$param['state']);
        $param_json=json_encode($param);
        $this->assign('param_json',$param_json);//用于忘记密码返回
        $this->assign('dingdan',$param['dingdan']);
        $this->assign('money',$param['money']);
        $this->assign('oid',$param['oid']);
        $this->display();

    }

    //充值元宝》生成订单=>跳转支付界面
    public function make_order_recharge(){
//            $this->ajaxReturn(['status'=>1,'msg'=>'订单生成功','url'=>U('wallet/acer_pay',['dingdan'=>111,'money'=>222])]);

        $pos = I('post.');
        $uid=$this->uid;
        $now= time();
        $zftype=0;
//        $zftype=$pos['zftype'];
        $cz_money=$pos['price'];

        $order_recharge=M('order_recharge');
        //不允许3s内重复操作
        $map=['totalprices'=>$cz_money,'status'=>1,'uid'=>$uid,'type'=>2];
        $map['add_time']=['gt',$now-3];
        $over_order=$order_recharge->where($map)->count();
        $over_order&&$this->ajaxReturn(['status'=>0,'dingdan'=>0,'msg'=>'请勿重复提交']);

        //微信充值上限
        if($zftype==1){
            $time_str = date('Y-m-d', $now);//今天0点
            $time_start= strtotime($time_str);//时间戳s
            $time_end= $time_start+24*3600;
            $time_rule['add_time'] =  array('between',array($time_start,$time_end));
            $time_rule['uid'] = $uid;
            $time_rule['type'] = 2;//1为支付 2为充值',
            $time_rule['status']=2;//支付状态 1待支付 2支付成功 3支付失败',
            $time_rule['zftype']=$zftype;//支付类型0.未选择 1=微信，2=支付宝 3余额',
            $prices_today =$order_recharge->where($time_rule)->getField(' sum(totalprices) ');
            $cz = 3000;//每日充值元宝总个数限制
            (($prices_today+$cz_money) >$cz)&& $this->ajaxReturn(['status'=>0,'msg'=>'抱歉,每日微信充值上限为:'.$cz.'!']);
        }

        $data['dingdan']=create_order_sn();//生成订单号
        $data['uid'] = $uid;
        $data['totalprices'] = $cz_money;
        $data['type'] = 2;//1为支付 2为充值',
        $data['zftype']=$zftype;//'支付方式  0.未选择 1=微信，2=支付宝',
        $data['status']=1;//支付状态 1待支付 2支付成功 3支付失败',
        $data['add_time']=$now;

        $yd=$order_recharge->add($data);//生成临时订单
        if($yd)
            $this->ajaxReturn(['status'=>1,'msg'=>'订单生成功','url'=>U('wallet/acer_pay',['dingdan'=>$data['dingdan'],'money'=>$cz_money,'oid'=>$yd])]);
        else
            $this->ajaxReturn(['status'=>0,'msg'=>'订单生成失败']);

    }


    //充值元宝》余额购买
    public function sealing_ye(){
        $uid=$this->uid;
        $pos=I('post.');
        $now= time();
        $oid=$pos['oid'];
        $order = M('order_recharge');//第三方支付记录表(此处记录充值会员)
        $member_model=$this->_member;
        $mem = $member_model->find($uid);
        (!$pos['pas'])&& $this->ajaxReturn(['status'=>0,'msg'=>'请输入支付密码']);
        (!$mem['paypassword'])&& $this->ajaxReturn(['status'=>0,'msg'=>'您还未设置支付密码']);
        (st_md5($pos['pas']) != $mem['paypassword'])&& $this->ajaxReturn(['status'=>0,'msg'=>'支付密码有误']);

        $info = $order->find($oid);
        $jyb = $info['totalprices'];
        ($mem['prices']< $jyb)&& $this->ajaxReturn(['status'=>0,'msg'=>'余额不足']);

        //不允许3s内重复操作
        $map=['totalprices'=>'-'.$jyb,'uid'=>$uid,'type'=>1];
        $map['add_time']=['gt',$now-3];
        $over_order=M('account')->where($map)->count();
        $over_order&&$this->ajaxReturn(['status'=>0,'dingdan'=>0,'msg'=>'请勿重复提交']);

        $start = M();
        $start->startTrans();
        //更改订单状态
        $res_order = $order->where(array('id' => $info['id']))
            ->save(array('status' => 2, 'pay_time' => $now, 'zftype' => 3));
        if (!$res_order) {
            $start->rollback();
            $this->ajaxReturn(['status'=>0,'msg'=>'系统繁忙']);
        }

        $yb=0;
       //充值送银币规则 #一个按钮一天只能送银币一次
//        $buy_send_rule=M('buy_send')->select();//充值送银币规则
//        foreach($buy_send_rule as $kk=>$vv){
//              $flag=$kk+1<count($buy_send_rule)?($jyb <$buy_send_rule[$kk+1]['money']):true;
//            if($jyb>=$vv['money'] &&$flag)){
//                $yb=$vv['send_coin'];
//                $index=$vv['id'];//当前规则的id
//            }
//        }
//        $save['gold_acer'] = $member['gold_acer'] + $jyb;//加元宝
//        $yb>0&&$save['silver_coin'] = $member['silver_coin'] + $yb;//加银币
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
                $save['silver_coin'] = $mem['silver_coin'] + $yb;
                //获得银币奖励记录
                $res_buy_send=M('buy_send_account')->add(['uid'=>$uid,'bs_id'=>$buy_send_id,'add_time'=>$now]);

                if (!$res_buy_send) {
                    $start->rollback();
                    $this->ajaxReturn(['status'=>0,'msg'=>'系统繁忙']);
                }
            }
        }//加银币
        $save['prices'] = $mem['prices'] - $jyb;//减余额
        $save['gold_acer'] = $mem['gold_acer'] + $jyb;//加元宝

        //本人加金元宝&&银币
        $res_member = $member_model->where(array('id' => $uid))->save($save);

        if (!$res_member) {
            $start->rollback();
            $this->ajaxReturn(['status'=>0,'msg'=>'系统繁忙']);
        }
        //本人金元宝&&银币明细
        $recharge[] = account_arr(1, $uid, '-'.$jyb, '充值元宝', $now);
        $recharge[] = account_arr(2, $uid, $jyb, '充值元宝', $now);
        $yb&&$recharge[] = account_arr(4, $uid, $yb, '充值元宝赠送', $now);

        //推荐人得银币&&明细
        if ($mem['relation_id']) {
            #推荐人赠送银币(规则表)
            $relation_member = $member_model->where(array('id' => $mem['relation_id']))->field('id,vips')->find();
            $relation_bl = D('GradeRule')->where(array('id' => $relation_member['vips']))->field('id,upgrade_one_price,tj_acer_silver')->find();
            $silver_coin_num = $jyb * $relation_bl['tj_acer_silver'];
            #银币明细
            if ($silver_coin_num > 0) {
                $res_relation = $member_model->where(array('id' => $mem['relation_id']))->setInc('silver_coin', $silver_coin_num);
                if (!$res_relation) {
                    $start->rollback();
                    $this->ajaxReturn(['status'=>0,'msg'=>'系统繁忙']);
                }
                $recharge[] = account_arr(4, $mem['relation_id'], $silver_coin_num, '下线充值元宝', $now);
            }
        }

        //各区代得银币&&明细
        $province_id=$mem['province_id'];
        $city_id=$mem['city_id'];
        $district_id=$mem['district_id'];
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
                        $start->rollback();
                        $this->ajaxReturn(['status'=>0,'msg'=>'系统繁忙']);
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

    //充值元宝》微信购买
    public function sealing_pay()
    {
        $pos = I('post.');
        $price=M('order_recharge')->where(['dingdan'=>$pos['dingdan']])->getField('totalprices');
//        file_put_contents('abc.txt',var_export($pos,true));
        $item_type = 1; //1充值元宝2充值会员
        $attach = json_encode(array('item_type' => $item_type));

        $data['attach'] = $attach;
        $data['body'] = '元宝充值';
        $data['number'] = $pos['dingdan'];
        $data['price']  =  $price*100;//单位分
        if($this->uid==67) $data['price'] =1;

        $data['openid'] = session('openid');
        
        $config = A('Api/Wxpay')->orderParameter($data);//回调里：价钱，该订单状态，改支付方式

        if ($config['err_code'] != 0) {
            echo json_encode(array('err_code' => 1, 'err_msg' => $config['err_msg']));
        } else {
            echo json_encode(array('err_code' => 0, 'err_msg' => $config));
        }
        exit;
    }


    //**********************元宝充值 结束***************************************


    //***********************支付  开始************************************
    //扫码后支付页面
    public function scan_pay(){
        $id=I('merchant_id');//扫码得到商家id
        !$id&& $this->redirect('index/index',2,'系统繁忙!');
        $shop=$this->_merchant->where(array('id'=>$id))->find();
        $this->assign('shop',$shop);

        //用作模板推送
        $wx_pay_config=C('wx_pay_config');
        $appid =$wx_pay_config['appid'] ;
        $appsecret = $wx_pay_config['appsecret'];
        $wx = new \Mobile\Org\WeiXinAbout($appid,$appsecret);
//        if (!session('pt_openid') || !session('pt_token')) {
            $token = $wx->getToken($appid, $appsecret);
            $openid = $wx->get_appid($appid, $appsecret);
            session('pt_openid', $openid);
            session('pt_token', $token);
//        }
        $this->display();
    }
    //元宝支付&&金果支付=>商家
    public function pay_to_merchant()
    {
        $param=I();
        $transfer_type=$param['transfer_type'];//1元宝2金果
        $field=($transfer_type==1)?'元宝':'金果';
        $this->assign('transfer_type',$transfer_type);
        $this->assign('field',$field);

        //用作模板推送
        $wx_pay_config=C('wx_pay_config');
        $appid =$wx_pay_config['appid'] ;
        $appsecret = $wx_pay_config['appsecret'];
        $wx = new \Mobile\Org\WeiXinAbout();
        $token = $wx->getToken($appid, $appsecret);
        $openid = $wx->get_appid($appid, $appsecret);
        session('pt_openid', $openid);
        session('pt_token', $token);

        //获取微信分享必要参数、扫码
        $jssdk = new \Mobile\Org\Jssdk(C("WX_CONFIG.appid"),C("WX_CONFIG.appsecret"));
        $js = $jssdk->GetSignPackage();
        $this->assign('js',$js);

        $this->display();

    }

    //元宝支付&&金果支付=>生成订单并处理(扫码支付&&输入电话支付)
    public function check_pay(){
        $pos = I('post.');
//        file_put_contents('check_pay.txt',var_export($pos,true));die;
        $uid=$this->uid;
        $prices=$pos['nums'];
        $tel=$pos['mobile'];
        $transfer_type=$pos['transfer_type'];//1元宝2金果
        $member=$this->_member->find($uid);
        $shop = D('Merchant')->where(array('tel'=>$tel,'status'=>2))->find();

        (!$shop)&& exit(json_encode(array('status'=>0,'msg'=>'对方号码输入有误')));
        (!$pos['pas'])&& $this->ajaxReturn(['status'=>0,'msg'=>'请输入支付密码']);
        (!$member['paypassword'])&& $this->ajaxReturn(['status'=>0,'msg'=>'您还未设置支付密码']);
        (st_md5($pos['pas']) != $member['paypassword'])&& $this->ajaxReturn(['status'=>0,'msg'=>'支付密码有误']);
        $data['memos']=$pos['memos'];//买家留言

        if($transfer_type==1){//元宝
            $str='1';
            $field='gold_acer';
            $data['zftype']=2;
            $memos='金元宝支付';$memos2='金元宝收款';
            $shop_bz=1;$account_bz=2;
        }else{//金果
            $str='3';
            $field='gold_fruit';
            $data['zftype']=3;//币种1工资2金元宝3金果4银币'
            $memos='金果支付';$memos2='金果收款';
            $shop_bz=3;$account_bz=3;
        }

        ($member[$field]< $prices)&& $this->ajaxReturn(['status'=>0,'msg'=>'余额不足']);
        (strpos($shop['zftype'],$str) === false)&& exit(json_encode(array('status'=>0,'msg'=>'该店家不支持此支付方式')));

        $now=time();
        //不允许3s内重复生成类似订单
        $map=['totalprices'=>$prices,'uid'=>$uid,'shop_id'=>$shop['id']];
        $map['add_time']=['gt',$now-3];
        $over_order=M('order_line')->where($map)->count();
        $over_order&& $this->ajaxReturn(['status'=>0,'msg'=>'请勿重复提交订单','dingdan'=>0]);

        $dingdan=create_order_sn();
        $data['dingdan']=$dingdan;
        $data['uid'] = $uid;
        $data['shop_id']=$shop['id'];
        $data['totalprices'] = $prices;
        $data['status'] = 1;// 1未付款 2已付款
        $data['add_time']=$now;
        $yd=M('order_line')->add($data); //生成临时订单
        !$yd &&$this->ajaxReturn(['status'=>0,'msg'=>'操作失败,请重试',]);

        $now=time();
        $order=M('order_line')->find($yd);

        #消费者消费币种减
        $member_jyb = $this->_member->where(array('id'=>$uid))->setDec($field,$prices);
        $mr[] = account_arr($account_bz, $member['id'],'-'.$prices,$memos,$now);

        #商家收益加
        $shop_jyb =	D('Merchant')->where(array('tel'=>$tel))->setInc($field,$prices);
        $mr_shop[] = account_shop_arr($account_bz, $shop['id'],$prices,$memos2,$now);

        #返商家主人银币(有让利前提,查让利规则表)
        $rl_nums=D('RangliRule')->where(array('rangli'=>$shop['rangli']))->getField('rl_nums');
        $rangli_bufen=$prices*$shop['rangli']/100;//消费金额*让利%=让利部分
        $rangli_yb = $rangli_bufen*$rl_nums;//让利部分*平台返银币倍数
//        file_put_contents('a1.txt',var_export([$prices,$rangli_bufen,$rangli_yb,$shop],true));
        if($rangli_yb >0){
            $shop_yinbi =	$this->_member->where(array('id'=>$shop['uid']))->setInc('silver_coin',$rangli_yb);
            //商家主人得平台返银流水
            $mr[] = account_arr(4, $shop['uid'],$rangli_yb,'店铺盈利奖励银币',$now);
        }

        #商家营业返上线银币
        if($shop['relation_id']){
            $tuijian=$this->_member->find($shop['relation_id']);
            $seller_none_silve=D('GradeRule')->where(array('id'=>$tuijian['vips']))->getField('seller_none_silve');
            $get_silver_coin=$rangli_bufen*$seller_none_silve;//商家盈利部分*返上线的倍数
            if($get_silver_coin >0){
                $is_get_coin = $this->_member->where(array('id'=>$shop['relation_id']))->setInc('silver_coin',$get_silver_coin);//推荐人得银币
                $mr[] = account_arr(4, $shop['relation_id'],$get_silver_coin,'下线商家盈利奖励',$now);
            }
        }

        #消费者返银币(除金果消费)
        $buy_get=0;
        if($transfer_type !=2){
            if($shop['set_coin'] != 0){
                $buy_get = $shop['set_coin']*$prices;//消费金额*返银倍数
                #消费者得商家返银币(除金果消费)
                $this->_member->where(array('id'=>$uid))->setInc('silver_coin',$buy_get);//消费者得银币
                $mr[] = account_arr(4, $member['id'],$buy_get,'消费商家送银币',$now);

                #消费者上线返银币(除金果消费)
                if($member['relation_id']){
                    $tj=$this->_member->find($member['relation_id']);//上线
                    $tj_acer_pay_silver=D('GradeRule')->where(array('id'=>$tj['vips']))->getField('tj_acer_pay_silver');
                    $buy_get_up=$prices*$tj_acer_pay_silver;//消费金额*倍数
                    if($buy_get_up !=0){
                        $this->_member->where(array('id'=>$member['relation_id']))->setInc('silver_coin',$buy_get_up);
                        $mr[] = account_arr(4, $member['relation_id'],$buy_get_up,'下线消费送银币',$now);
                    }
                }
            }
        }
        //改变订单状态&&添加流水记录&&推送消息
        if($member_jyb&&$shop_jyb){
            //改变订单状态
            M('order_line')->where(array('id'=>$yd))->save(['status'=>2]);
            //添加流水记录
            M('account')->addAll($mr);
            M('account_shop')->addAll($mr_shop);

            //推送消息
            $openid = session('pt_openid');
            $token = session('pt_token');
//				$openid = "oy5y10b_hFP1Ofg3Mvh1q_hxZ9Ao";
            $data = array(
                'first'=>array('value'=>urlencode("恭喜您,消费成功"),'color'=>"#000000"),
                'keyword1'=>array('value'=>urlencode($prices.'元'),'color'=>"#999999"),//消费金额
                'keyword2'=>array('value'=>urlencode($buy_get.'个银币'),'color'=>'#999999'),//所的奖励
                'keyword3'=>array('value'=>urlencode($memos),'color'=>'#999999'),//结账类型
                'keyword4'=>array('value'=>urlencode(date('Y-m-d h:i:s',time())),'color'=>'#999999'),//消费时间
                'keyword5'=>array('value'=>urlencode($shop['title']),'color'=>'#999999'),//消费地点
                'remark'=>array('value'=>urlencode('欢迎再来!'),'color'=>'#000000'),
            );
            $wx = new \Mobile\Org\WeiXinAbout();
            $res_wx_send=$wx->doSend($openid, 'EdGGaGp4FLwSyAiWo_nNRmPvIW5EZURzY-rFkoNbMCg', '', $token, $data);
//            file_put_contents('wx_send.txt',var_export($res_wx_send,true));
            $re =['status'=>1,'msg'=>'操作成功','url'=>U('wallet/wallet')];
            $this->ajaxReturn($re);
        }else{
            $re =['status'=>0,'msg'=>'操作失败'];
            $this->ajaxReturn($re);exit;
        }


    }


    //***********************支付  结束************************************
    //我的钱包》我的会员
   public function vip_list(){
           $type=I('type');//0商家1会员
           $member_id = $this->uid;
           switch($type){
               case 0:$type = 0;$model = $this->_merchant;break;
               case 1:$type = 1;$model = $this->_member;break;
               default: echo "No find";
           }
           $page = I('page');
           $map = array('relation_id'=>$member_id);
           $count = $model->where($map)->order('id desc')->count();//总条数
           if(IS_AJAX){
               $pages =   ceil($count/ 8); //上取整    总条数/每页条数
               $list_aj[0] = $pages;//总页数
               //每页数据
               $list_ajs = $model->where($map)->order('id desc')->limit(($page-1) * 8 , 11)->select();
              if($type == 0){   //会员表与商家表所查字段不同，以下是为了统一
                   foreach($list_ajs as $k=>$v){
                       $list_ajs[$k]['mobile'] = $v['tel'];
                       $list_ajs[$k]['reg_time'] = $v['add_time'];
                       $list[] = $v['uid'];
                   }
                   if($list_ajs){
                       foreach($list_ajs as $key =>$value){
                           $list_ajs[$key]['avatar'] = $value['img'];
                       }
                   }

               }
               $this->assign('a',$list_ajs);
               $a=$this->fetch('ajax_recommend');
               $list_aj[1] = $a;//数据
               echo  json_encode($list_aj);
           }else{
               $this->assign('is_kk',$count?0:1);//数据为空 1是
               $this->assign('type',$type);
               $this->display();
           }

   }



}?>