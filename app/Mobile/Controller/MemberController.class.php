<?php

namespace Mobile\Controller;

use Admin\Org\Image;

class MemberController extends HomeController {
    public function _initialize() {
        parent::_initialize();
        $this->uid=is_login();
        if(!$this->uid){
            $this->redirect('Login/enter');
        }

        $this->_member=D('Member');
        $this->_account=D('Account');

        $member =  $this->_member->find($this->uid);
//        if(!$member){
//            $this->redirect('Login/enter');
//        }
        $this->assign('member', $member);

    }

    //个人中心
    public function mine(){

        $uid=$this->uid;
        $mem = $this->_member->find($uid);
        if($mem['is_qd']==1){//如果是区代需要回到区代页面
            $this->redirect('agent/agent');
        }
        $gr = D('GradeRule')->getField('id,name');
        $mem['vips']=$gr[ $mem['vips']];

        #今天财富值统计
        $time_end=time();//当前时间
        $time_start= strtotime(date('Y-m-d',$time_end));//今天0点时间戳s
        $time_rule['add_time'] =  array('between',array($time_start,$time_end));
        $time_rule['uid'] = $uid;
        $time_rule['totalprices'] = ['gt',0];
        $time_rule['type'] = 4;//1为支付 2为充值',
        $selive_coin_day = $this->_account->where($time_rule)->getField(' sum(totalprices) ');
        $selive_coin_day=intval($selive_coin_day);
        //订单统计数量
        $count1 = M('order')->where(array('status'=>1,'uid'=>$uid))->count();
        $count2 = M('order')->where(array('status'=>2,'uid'=>$uid))->count();
        $count3 = M('order')->where(array('status'=>3,'uid'=>$uid))->count();
        $count4 = M('order')->where(array('status'=>4,'uid'=>$uid))->count();
        $array['count1'] = $count1;
        $array['count2'] = $count2;
        $array['count3'] = $count3;
        $array['count4'] = $count4;
        $this->assign($array);
        $this->assign('mem',$mem);
        $this->assign('selive_coin_day',$selive_coin_day);
        $this->display();

    }

    //个人中心 我是商家
    public function my_merchant(){

        $mem = D('Member')->find(is_login());
        $merchant=D('Merchant')->where(array('uid'=>$mem['id']))->find();

        $map = array();
        $map['member_id']=$merchant['id'];
        $map['item_type']=(I('type'))?I('type'):1;
        ($time_start=I('time_start'))&&$map['add_time'][]=array('egt',strtotime($time_start));
        ($time_end=I('time_end'))&&$map['add_time'][]=array('elt',strtotime($time_end)+24*60*60);

        //item_type 1金元宝 2银元宝 3金果 4余额/收益 5金币 6银币（1商家收益 2商家银币）
        $ye=D('ShopRecharge')->where($map)->order('add_time desc,id desc')->select();
//		$ye=D('ShopRecharge')->where(array('member_id'=>$merchant['id'],'item_type'=>$money_type))->order('add_time desc,id desc')->select();
        $this->assign('ye',$ye);


        $no_sj=D('Merchant')->where(array('uid'=>$mem['id'],'status'=>2))->find();
        (!$no_sj)&&$this->assign('no_sj',1);
        ($merchant)&&$this->assign('merchant',$merchant);
        $this->assign('type',$map['item_type']);
        $this->display();
    }

    //时间
    public function my_merchant_searchdate(){
        $this->assign('type',I('type'));
        $this->display();
    }

    //个人中心 我是服务中心

    public function my_service(){
        $mem = D('Member')->find(is_login());
        $gr = D('GradeRule')->select();
        foreach($gr as $k){
            ($mem['vips'] == $k['id'])&&$mem['vips']=$k['name'];
        }

        $this->assign('mem',$mem);
        $this->display();

    }



    //个人中心  设置

    public function myset(){

        $this->display();

    }
    public function about(){
        $type = I('type','','trim');//传参 1 为用户协议 2为关于我们


        $this->assign('type',$type);
        $this->display();
    }

    //意见反馈
    public  function opinion(){
        if(IS_POST){
            $member_id = $this->uid;
            $content = I('content','','trim');
            $User = M('opinion');
            $opinion = array(
                'content'  =>$content,
                'add_time' =>time(),
                'user_id'  =>$member_id ,
            );
            $User ->add($opinion);
            $this->ajaxReturn(array('err_code'=>1));
        }else{
            $this->display();
        }

 }

    //设置个人信息

    public function set_person(){

        if(IS_POST){
            $pos = I();
            $member = $this->get('member');

            //头像
            if($pos['is_avatar']==1){
                $avatar = D('member')->where(array('id'=>$member['id']))->setField('avatar',$pos['avatar']);
                $re=array('msg'=>$avatar?'头像修改成功':'图片上传失败,请重试','status'=>$avatar?1:0);
            }

            //昵称
            if($pos['is_nickname']==1){
                $nickname = D('member')->where(array('id'=>$member['id']))->setField('nickname',$pos['nickname']);
                $re=array(
                    'msg'=>$nickname?'昵称修改成功':'新的昵称不能和旧昵称相同',
                    'status'=>$nickname?1:0,'url'=>$nickname?U('member/set_person'):'');
            }

            //性别
            if($pos['is_sex']==1){
                $sex = D('member')->where(array('id'=>$member['id']))->setField('sex',$pos['sex']);
                $re=array(
                    'msg'=>$sex?'性别修改成功':'新的信息不能和旧信息相同',
                    'status'=>$sex?1:0,'url'=>$sex?U('member/set_person'):'');
            }

            //地区
            if($pos['is_address']==1){
                $address = D('member')->where(array('id'=>$member['id']))->setField('address',$pos['address']);
                $re=array('msg'=>$address?'地区修改成功':'新的信息不能和旧信息相同','status'=>$address?1:0,'url'=>$address?U('member/set_person'):'');
            }

            $this->ajaxReturn($re);
        }else{
            $is_page = I('is_page');//页面分发
            $page='';
            switch($is_page){
                case 1:$page='name';break;
                case 2:$page='gender';break;
                case 3:$page='area';break;
                default:
                    $jssdk = new \Mobile\Org\Jssdk(C("WX_CONFIG.appid"), C("WX_CONFIG.appsecret"));
                    $js = $jssdk->GetSignPackage();
                    $this->assign('js',$js);
            }
            $info=$this->get('member');
            //省市区
            $place_ids=[$info['province_id'],$info['city_id'],$info['district_id']];
            $place=M('place')->where(['id'=>['in',$place_ids]])->getField('id,name');
            $info['province_id']&&$region=$place[$info['province_id']].',';
            $info['city_id']&&$region.=$place[$info['city_id']].',';
            $info['district_id']&&$region.=$place[$info['district_id']];
            $info['region']=$region?$region:'';

            $this->assign('member',$info);
            $this->display($page);

        }


    }



    //设置》实名认证

    public function set_attestation(){
        $uid=$this->uid;
        $member= $this->_member->find($uid);
        if(IS_POST){
            $pos = I('post.');
            $member['type']==2&& $this->ajaxReturn(['status'=>0,'msg'=>'您已在实名审核中']);
            $member['type']==3&& $this->ajaxReturn(['status'=>0,'msg'=>'您已是实名会员']);

            $data['id'] = $uid;
            $data['type'] = 2;//'1普通会员，2实名认证中,3已实名认证，4实名认证失败',
            $data['realname'] = $pos['realname'];
            $data['id_nums'] = $pos['id_nums'];
            $place_str=explode(',',$pos['address']);

            $data['province_id'] = get_place_id($place_str[0]);
            $data['city_id'] = get_place_id($place_str[1]);
            $data['district_id'] = get_place_id($place_str[2]);
            $data['rz_time'] = time();

            $res=$this->_member->save($data);
            !$res&& $this->ajaxReturn(['status'=>0,'msg'=>'提交失败,请重试']);

            $member_idcard=[[
                'uid'=>$uid,
                'type'=>1,
                'img'=>$pos['img_one'],
            ],[
                'uid'=>$uid,
                'type'=>2,
                'img'=>$pos['img_two'],
            ]];
            $res_img = M('member_idcard')->addAll($member_idcard);
            if($res_img){
                $this->ajaxReturn(['status'=>1,'msg'=>'提交成功']);
            }else{
                $this->ajaxReturn(['status'=>0,'msg'=>'提交失败,请重试']);
            }

        } else {
            //获取微信必要参数
//            $jssdk = new \Mobile\Org\Jssdk(C("WX_CONFIG.appid"), C("WX_CONFIG.appsecret"));
//            $js = $jssdk->GetSignPackage();
//            $this->assign('js',$js);
            //修改信息
            if($member['type']==4){
                $idcard=M('member_idcard')->where(['uid'=>$uid])->getField('type,img');
                $this->assign('idcard',$idcard);
            }
            $this->assign('member',$member);

            $this->assign('is_w',I('get.is_w'));
            $this->display();

        }

    }

    //异步上传身份证
    public function ajax_idcard(){
        $dir='id_card/';
        if (!empty($_FILES)){
            !empty($_FILES['imgs1']['name'])&& $type=1;
            !empty($_FILES['imgs2']['name'])&& $type=2;

            $result = $this->_upload($_FILES['imgs'.$type],$dir);
            if ($result['error']) {
                $this->ajax_return(0, $result['info']);
            } else {
                $savename = $result['info'][0]['savename'];
                $this->ajaxReturn(['status'=>1,'msg'=>$savename,'type'=>$type]);
            }
        } else {
            $this->ajaxReturn(['status'=>0,'msg'=>'照片上传失败']);
        }
    }



    //设置》账户安全
    public function set_safety(){
        $this->display();
    }

    //设置》修改登录密码
    public function set_pw_enter(){
        if(IS_POST){
            $param=I();
            $uid=$this->uid;
            $oldpw = st_md5($param['oldpw']);
            $password = st_md5($param['password']);
            $oldpws = M('Member')->where(array('id'=>is_login()))->getField('password');//实际原密码
            ($oldpw != $oldpws)&& $this->ajaxReturn(['msg'=>'原密码输入有误!','status'=>0]);
//            ($password = $oldpws)&& $this->ajaxReturn(['msg'=>'新密码输入不应与原密码相同!','status'=>0]);

            $db = M('Member')->where(array('id'=>$uid))->setField('password',$password);
             if(false!==$db){
                 cookie('user_auth',null);
                 session('user_auth',null);
                 $return=[ 'status'=>1,'msg'=>'操作成功！','url'=>U('Login/enter')];
             }else{
                 $return=[ 'status'=>0,'msg'=>'操作失败！'];
             }
            $this->ajaxReturn($return);
        }else{
            $this->display();

        }

    }

    //设置》支付密码  修改密码&&忘记密码
    public function set_pw_pay(){
        $uid=$this->uid;
        $data=I();
        if(IS_POST){
            $member=$this->get('member');
            ($data['mobile'] != $member['mobile'])&&exit(json_encode(array('status'=>0,'msg'=>'请输入您本人的手机号码！')));
            $code = cookie('reg_data');//短信验证
			($data['m_code']!=$code['code'])&&$return=array('msg'=>'手机验证码错误！','status'=>0);

            $db = $this->_member->where(array('id'=>$uid))->setField('paypassword',st_md5(I('paypassword')));
            //来源1安全设置2金果回购3扫码支付4转好友5手动支付6提现7元宝购买8聚宝盆寄存9订单支付10区代金果兑换
            $set_pw_pay=$data['set_pw_pay'];
            switch($set_pw_pay){
                case 1:$url=U('member/mine');break;
                case 2:$url=U('wallet/fruit_recycle');break;
                case 3:$url=U('wallet/scan_pay',['merchant_id'=>$data['param']]);break;
                case 4:$url=U('wallet/transfer_to_friend',['transfer_type'=>$data['param']]);break;
                case 5:$url=U('wallet/pay_to_merchant',['transfer_type'=>$data['param']]);break;
                case 6:$url=U('wallet/balance_extract',['page_type'=>$data['param']]);break;
                case 7:
                    $param=json_decode(htmlspecialchars_decode($data['param']),true);//防止反斜杠被转义
                    $url=U('wallet/acer_pay',['oid'=>$param['oid'],'dingdan'=>$param['dingdan'],'money'=>$param['money']]);
                    break;
                case 8:$url=U('WalletOther/basin_in');break;
                case 9:$url=U('Order/order_pay',['oid'=>$data['param']]);break;
                case 10:$url=U('agent/agent_fruit_exchange');break;
                case 11:$url=U('Goldfruitshop/y_pay',['oid'=>$data['param']]);break;
                case 12:$url=U('WalletOther/silver_store');break;
                default:$url=U('member/mine');
            }
            (false===$db)&& $return=[ 'status'=>0,'msg'=>'操作失败！'];
            cookie('reg_data',null);
            $return=[ 'status'=>1,'msg'=>'操作成功！','url'=>$url];
            $this->ajaxReturn($return);
        }else{
//            $this->assign('is_pay',I('get.is_pay'));//支付时没有密码，过来设置
//            $this->assign('is_w',I('get.is_w'));//
            $this->assign('set_pw_pay',$data['set_pw_pay']);
            $this->assign('param',$data['param']);//如果带参数

            $this->display();
        }

    }

    //个人中心  我要推荐
    public function popularize_link(){
        $uid=$this->uid;
        $member_modle=$this->_member;
        $member = $member_modle->where(array('id'=>$uid))->find();
//		$tjr_url = "http://".$_SERVER['HTTP_HOST'].U('login/register',array('ewid'=>$ewid));
        $ewid=$member['mobile'];
        $ewm=$member['ewm'];
        $name=$member['nickname'];

        //获取微信分享必要参数
        $jssdk = new \Mobile\Org\WeiXinAbout(C("WX_CONFIG.appid"),C("WX_CONFIG.appsecret"));
        $js = $jssdk->GetSignPackage();

        $this->assign('uid',$uid);
        $this->assign('js',$js);
        $this->assign('name',$name);
        $this->assign('ewid',$ewid);
        $this->assign('ewm',$ewm);
        $this->display();

    }

    //个人中心 我已推荐

    public function popularize(){
        $uid = is_login();
        $next_mem = D('Member')->where(array('relation_id'=>$uid))->order('id desc')->select();//下线
//		$next_mem = D('Merchant')->where(array('tuijian'=>$uid))->order('id desc')->select();//我推荐的商家

        $this->assign('next_mem',$next_mem);
        $this->display();

    }





    //个人中心  平台介绍

    public function my_set2(){

        $this->display();



    }

    //**********************我的钱包 开始***********************************
    //个人中心  我的钱包
    public function wallet(){
        $mem = D('Member')->find(is_login());
        $this->assign('mem',$mem);
        $this->display();
    }

    //我的钱包》我的余额
    public function w_purse(){
        $this->assign('type',1);
        $this->display();
    }

    //我的钱包》余额提现

    public function w_extract(){

        if(IS_POST){//提现
            $pos = I('post.');
            $uid=$this->uid;
            $now=time();
            $withdraw_mod=M('withdraw_member');
            $member = D('Member')->find($uid);
            ($member['paypassword'] != st_md5($pos['pw']))&& $this->ajaxReturn(['status'=>0,'msg'=>'支付密码有误或未设置!']);
            //余额是否足够
            $service_charge=C('pin_tx_sxf');
            $dec_money=$pos['prices']+$service_charge;
            ($member['prices'] < $dec_money)&& $this->ajaxReturn(['status'=>0,'msg'=>'您的余额不足!']);

            //单次提现上限
            $meibi = C('pin_tx_db_je')*10000;
            ($pos['prices'] > $meibi)&& $this->ajaxReturn(['status'=>0,'msg'=>'抱歉,单笔最高提现金额为:'.$meibi.'!']);

    		//验证提现时间是否在3天内
//            $tx_day=C('pin_tx_zq');
//            if($tx_day>0){
//                $last_day = $withdraw_mod->where(['uid'=>$uid])->field('max(add_time)')->find();//上一单时间
//                $last_day&&($now-24*3600*$tx_day < $last_day['max(add_time)'])&& $this->ajaxReturn(['status'=>0,'msg'=>'提现周期为每三天一次!']);
//            }

            //验证每日提现金额上限
            $time_start= strtotime(date('Y-m-d', $now));//今天0点时间戳s
            $time_end= $time_start+24*3600;
            $time_rule['add_time'] =  array('between',array($time_start,$time_end));
            $time_rule['uid'] = $uid;
            $time_rule['status']=array('in',array(1,3));

            $history_money = $withdraw_mod->where($time_rule)->getField('sum(totalprices) as all_money');
            $prices_today = $pos['prices'] + $history_money;
            $sx = C('pin_tx_mr_je')*10000;
            ($prices_today >$sx)&& $this->ajaxReturn(['status'=>0,'msg'=>'抱歉,每日最高提现金额为:'.$sx.'!']);


            $card = M('member_bankcard')->where(['id'=>$pos['card_id']])->field('id',true)->find();//银行卡id
            $data=$withdraw_mod->create($card);
            $data['uid']=$uid;
            $data['totalprices']=$pos['prices'];//用户提现金额
            $data['service_charge']=$service_charge;
            $data['status']=1;//状态 1未审核 2通过 3驳回'
            $data['add_time']=$now;

            $start=M();
            $start->startTrans();
            $yd=$withdraw_mod->add($data);	//提交审核

            $yd&&$res_member=$this->_member->where(array('id'=>$uid))->setDec('prices',$dec_money);//扣用户余额

            //余额收支情况 $type币种1工资2金元宝3金果4银币
            $data_account=account_arr(1,$uid,'-'.$dec_money,'工资提现',$now);
            $res_member&&$account_arr=M('account')->add($data_account);
            if(!$yd || !$res_member || !$account_arr){
                $start->rollback();
                $this->ajaxReturn(['status'=>0,'msg'=>'提交申请失败!']);
            }

            $start->commit();
            $this->ajaxReturn(['status'=>1,'msg'=>'提交申请成功!']);

        }else{
            $card = D('MemberBankcard')->where(array('member_id'=>is_login(),'status'=>1))->order('add_time desc,id desc')->select();
            foreach($card as $k=>$v){
                $card[$k]['nums'] = substr($v['nums'],-4);
            }

            $this->assign('card',$card);
            $this->display();
        }


    }


    //流加载 币种流水
    public function ajax_bz_liu(){
        $member_id=is_login();
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

        $pages =   ceil($count/ 4); //上取整    总条数/每页条数
        $list_aj[0] = $pages;//总页数
        //每页数据
        $list_ajs=$account->where($map)->order('id desc')->limit(($page-1) * 4 , 4)->select();

        $this->assign('a',$list_ajs);
        $a=$this->fetch('w_bz_liu');

        $list_aj[1] = $a;//数据

        echo  json_encode($list_aj);
    }
    //我的钱包  我的金元宝(内容在ajax_bz_liu)
    public function w_ingotA(){

        $this->assign('type',2);

        $this->display();

    }

    //我的钱包  我的银元宝0
    public function w_ingotB(){
        $this->display();
    }

    //***********************我的钱包 结束************************************

    //3种支付
    public function w_shaped_pay(){

        $tel = I('get.tel');
        $this->assign('tel',$tel);//扫码得到

        $wx_pay_config=C('wx_pay_config');
        $appid =$wx_pay_config['appid'] ;
        $appsecret = $wx_pay_config['appsecret'];

        $wx = new \Mobile\Org\WeiXinAbout();
//        if (!session('pt_openid') || !session('pt_token')) {
            $token = $wx->getToken($appid, $appsecret);
            $openid = $wx->get_appid($appid, $appsecret);
            session('pt_openid', $openid);
            session('pt_token', $token);
//        }
        //获取微信分享必要参数、扫码
        $jssdk = new \Mobile\Org\Jssdk(C("WX_CONFIG.appid"),C("WX_CONFIG.appsecret"));
        $js = $jssdk->GetSignPackage();
        $this->assign('js',$js);

        $this->display();
    }
    //扫码支付、3个币种支付:选择支付类型&&处理订单和流水
    public function check_pay(){
        if(IS_POST){
            $pos = I('post.');
            $uid=$this->uid;
            $member=D('Member')->find($uid);
            $shop = D('Merchant')->where(array('tel'=>$pos['tel'],'status'=>2))->find();
            $dingdan=M('order_line')->where(array('dingdan'=>$pos['dingdan']))->find();
            $now=time();
            $memos='';
            //》》
            if($pos['zftype']==1){
                $memos='金元宝支付';$acer='gold_acer';$memos2='金元宝收款';
                $shop_bz=1;
                $account_bz=2;
            }
            if($pos['zftype']==2){
                $memos='银元宝支付';$acer='silver_acer';$memos2='银元宝收款';
                $shop_bz=2;
            }
            if($pos['zftype']==3){
                $memos='金果支付';$acer='gold_fruit';$memos2='金果收款';
                $shop_bz=3;
                $account_bz=3;
            }

            //余额判断、防止出现负数
            if($member[$acer] < $pos['prices']){
                $re =array('status'=>0,'msg'=>'余额不足');
                $this->ajaxReturn($re);exit;
            }

            #消费者消费币种减
            $member_jyb = D('Member')->where(array('id'=>$member['id']))->setDec($acer,$pos['prices']);
            $mr[] = account_arr($account_bz, $member['id'],'-'.$pos['prices'],$memos,$now);

            #商家收益加
            $shop_jyb =	D('Merchant')->where(array('tel'=>$pos['tel']))->setInc($acer,$pos['prices']);
            $mr_shop[] = account_shop_arr($account_bz, $shop['id'],$pos['prices'],$memos2,$now);

            #返商家主人银币(有让利前提,查让利规则表)
            $rl_nums=D('RangliRule')->where(array('rangli'=>$shop['rangli']))->getField('rl_nums');
            $rangli_bufen=$pos['prices']*$shop['rangli']/100;//消费金额*让利%=让利部分
            $rangli_yb = $rangli_bufen*$rl_nums;//消费金额*让利%*平台返银币倍数
            if($rangli_yb !=0){
                $shop_yinbi =	$this->_member->where(array('id'=>$shop['uid']))->setInc('silver_coin',$rangli_yb);
                //商家主人得平台返银流水
                $mr[] = account_arr(4, $shop['uid'],$rangli_yb,'店铺盈利奖励银币',$now);
            }
            #商家营业返上线银币
            if($rangli_yb !=0&&$shop['tuijian']){
                $tuijian=$this->_member->find($shop['tuijian']);
                $seller_none_silve=D('GradeRule')->where(array('id'=>$tuijian['vips']))->getField('seller_none_silve');
//				$get_silver_coin=$pos['prices']*$shop['rangli']*$seller_none_silve/100;//金额*让利(先转100分比)*vips的倍数
                $get_silver_coin=$rangli_yb*$seller_none_silve;//商家所得银币*返上线的倍数
                if($get_silver_coin !=0){
                    $is_get_coin = D('Member')->where(array('id'=>$shop['tuijian']))->setInc('silver_coin',$get_silver_coin);//推荐人得银币
                    $mr[] = account_arr(4, $shop['tuijian'],$get_silver_coin,'下线商家盈利奖励',$now);
                }
            }

            #消费者返银币(除金果消费)
            $buy_get=0;
            if($pos['zftype'] !=3){

                if($shop['set_coin'] != 0){
                    $buy_get = $shop['set_coin']*$pos['prices'];//消费金额*返银倍数
                    #消费者得商家返银币(除金果消费)
                    $this->_member->where(array('id'=>$member['id']))->setInc('silver_coin',$buy_get);//消费者得银币
                    $mr[] = account_arr(4, $member['id'],$buy_get,'消费商家送银币',$now);

                    #消费者上线返银币(除金果消费)
                    if($member['relation_id']){
                        $tj=$this->_member->find($member['relation_id']);//上线
                        $tj_acer_pay_silver=D('GradeRule')->where(array('id'=>$tj['vips']))->getField('tj_acer_pay_silver');
                        $buy_get_up=$buy_get*$tj_acer_pay_silver;
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
                D('MemberRecharge')->where(array('dingdan'=>$pos['dingdan']))->save(['status'=>2]);
                //添加流水记录
                M('account')->addAll($mr);
                M('account_shop')->addAll($mr_shop);

                //推送消息
                $openid = session('pt_openid');
                $token = session('pt_token');
//				$openid = "oy5y10b_hFP1Ofg3Mvh1q_hxZ9Ao";
                $data = array(
                    'first'=>array('value'=>urlencode("恭喜您,消费成功"),'color'=>"#000000"),
                    'keyword1'=>array('value'=>urlencode($dingdan['totalprices'].'元'),'color'=>"#999999"),//消费金额
                    'keyword2'=>array('value'=>urlencode($buy_get.'个银币'),'color'=>'#999999'),//所的奖励
                    'keyword3'=>array('value'=>urlencode($memos),'color'=>'#999999'),//结账类型
                    'keyword4'=>array('value'=>urlencode(date('Y-m-d h:i:s',time())),'color'=>'#999999'),//消费时间
                    'keyword5'=>array('value'=>urlencode($shop['title']),'color'=>'#999999'),//消费地点
                    'remark'=>array('value'=>urlencode('欢迎再来!'),'color'=>'#000000'),
                );
                $wx = new \Mobile\Org\WeiXinAbout();
                $res=$wx->doSend($openid, 'EdGGaGp4FLwSyAiWo_nNRmPvIW5EZURzY-rFkoNbMCg', '', $token, $data);
//                if($res['errcode']==41001){
//                    $wx_pay_config=C('wx_pay_config');
//                    $appid =$wx_pay_config['appid'] ;
//                    $appsecret = $wx_pay_config['appsecret'];
//                    $token = $wx->getToken($appid, $appsecret);
//                    $openid = $wx->get_appid($appid, $appsecret);
//                    $res=$wx->doSend($openid, 'EdGGaGp4FLwSyAiWo_nNRmPvIW5EZURzY-rFkoNbMCg', '', $token, $data);
//                }
                $re =array(
                    'status'=>1,
                    'msg'=>'操作成功',
                );
                $this->ajaxReturn($re);

                exit;
            }else{
                $re =array(
                    'status'=>0,
                    'msg'=>'操作失败,请重试',
                );
                $this->ajaxReturn($re);exit;
            }

        }else{
            $tel=I('tel');//扫码得到
            $shop=D('Merchant')->where(array('tel'=>$tel))->find();
            $this->assign('tel',$tel);
            $this->assign('shop',$shop);


            $wx_pay_config=C('wx_pay_config');
            $appid =$wx_pay_config['appid'] ;
            $appsecret = $wx_pay_config['appsecret'];
            $wx = new \Mobile\Org\WeiXinAbout($appid,$appsecret);
            if (!session('pt_openid') || !session('pt_token')) {
                $token = $wx->getToken($appid, $appsecret);
                $openid = $wx->get_appid($appid, $appsecret);
                session('pt_openid', $openid);
                session('pt_token', $token);
            }
            $this->display();
        }

    }



    //支付成功
    public function pay_succeed(){
        $data=I('get.');
        $recharge=D('MemberRecharge')->find($data['liu_id']);
        $shop=D('Merchant')->where(array('tel'=>$data['tel']))->find();

        $this->assign('shop',$shop);
        $this->assign('recharge',$recharge);

        $this->display();
    }


    //我的钱包 金元宝转余额

    public function w_transform(){

        if(IS_POST){

            $member=D('member')->find(is_login());

            $pos = I('post.');

            if($member['gold_acer'] < $pos['prices']){

                $re =array('status'=>0,'msg'=>'余额不足');

                $this->ajaxReturn($re);exit;

            }



            $data['dingdan']=make_order_sn('order');//生成订单号

            $data['member_id'] = $member['id'];

            $data['skperson']='';

            $data['totalprices'] = $pos['prices'];

            $data['zftype'] = 4;//'支付方式  0.未选择 1=微信，2=支付宝',

            $data['memos']='金元宝转余额';

            $data['type'] =1; //支出状态 1=出 ，  2=入

            $data['item_type'] = 4;//1金元宝 2银元宝 3金果 4余额 5会员卡（15作为充值订单表时）

            $data['status'] = 2;// 1未付款 2已付款

            $data['add_time']=time();

            $yd=M('MemberRecharge')->data($data)->add();//生成临时订单



            //金元宝减。金果加0.2、余额加0.7

            $jyb2ye['gold_acer']=$member['gold_acer'] - $pos['prices'];//金元宝减

            $jyb2ye['gold_fruit']=$member['gold_fruit']+$pos['prices']*0.2;//金果加

            $jyb2ye['prices']=$member['prices']+$pos['prices']*0.7;//余额加

            $zhuan = M('Member')->where(array('id'=>$member['id']))->save($jyb2ye);

            //流水//人*//数量*//谁的流水//支出状态 //*备注

            if($yd&&$zhuan){

                all_ls($member['id'],$pos['prices'],1,1,'金元宝转余额',$yd);

                all_ls($member['id'],$pos['prices']*0.2,3,2,'金元宝转余额',$yd);

                all_ls($member['id'],$pos['prices']*0.7,4,2,'金元宝转余额',$yd);

            }



            $re =array(

                'status'=>$yd&&$zhuan?1:0,

                'msg'=>$yd&&$zhuan?'操作成功':'操作失败,请重试',

            );

            $this->ajaxReturn($re);exit;

        }else{

            $this->display();

        }





    }







    //金币

    public function w_ingotJB(){

//	 	$ye = D('MemberRecharge')->where(array('member_id'=>is_login(),'item_type'=>5,'dingdan'=>0))->order('add_time desc')->select();
//
//	 	$this->assign('ye',$ye);

        $this->display();

    }

    //银币

    public function w_ingotYB(){
        $this->assign('type',4);

        $this->display();

    }

    //金果

    public function w_jinguo(){
        $this->assign('type',3);

        $this->display();

    }


    //我的钱包》金果回购
    public function recycle(){
        if(IS_POST){
            $pos = I('post.');
            $uid=$this->uid;
            $now= time();
            $account=$this->_account;
            $member = $this->_member->find($uid);
            $nums=$pos['nums'];
            //不允许3s内重复操作
            $map=['totalprices'=>$nums,'uid'=>$uid,'type'=>3];
            $map['add_time']=['gt',$now-3];
            $over_order=$account->where($map)->count();
            $over_order&&$this->ajaxReturn(['status'=>0,'msg'=>'请勿重复提交']);

            //金果验证
            $nums<0 &&$this->ajaxReturn(['status'=>0,'msg'=>'请输入金果数']);
            $member['gold_fruit']<$nums &&$this->ajaxReturn(['status'=>0,'msg'=>'余额不足']);

            $start = M();
            $start->startTrans();
            //加余额减金果
            $prices=($nums * C('pin_jg_scj'));
            $jj['gold_fruit'] =['exp',' gold_fruit -'.$nums];//减金果
            $jj['prices'] =['exp',' prices +'.$prices];//加余额
            $res=$this->_member->where(array('id'=>$uid))->save($jj);
            if(!$res){
                $start->rollback();
                $this->ajaxReturn(['status'=>0,'msg'=>'金果回购失败,请重试']);
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

    //我的钱包》金果交易
    public function w_golden_transfer(){
        if(IS_POST){
            $pos = I('post.');
            $uid=$this->uid;
            $now= time();
            $account=$this->_account;
            $nums=$pos['nums'];
            $dec_nums=$nums*(C('pin_jg_sxf')+1);

            $member = $this->_member->find($uid);//己方
            $info = $this->_member->where(array('mobile'=>$pos['mobile']))->find();//对方
            //不允许3s内重复操作
            $map=['totalprices'=>$nums,'uid'=>$uid,'type'=>3,'add_time'=>['gt',$now-3]];
            $over_order=$account->where($map)->count();
            $over_order&&$this->ajaxReturn(['status'=>0,'msg'=>'请勿重复提交']);

            !$info && exit(json_encode(array('status'=>0,'msg'=>'对方手机号不存在')));
            !$info['status'] && exit(json_encode(array('status'=>0,'msg'=>'对方账号已被冻结')));
            ($member['mobile'] == $pos['mobile']) && exit(json_encode(array('status'=>0,'msg'=>'请确定对方手机号码是否正确')));
            $nums<0 && exit(json_encode(array('status'=>0,'msg'=>'请输入金果数')));
            ($member['gold_fruit'] <$dec_nums) && exit(json_encode(array('status'=>0,'msg'=>'您的余额不足')));

            $start = M();
            $start->startTrans();
            //己方减金果
            $res_self=$this->_member->where(array('id'=>$uid))->setDec('gold_fruit',$dec_nums);
            $res_other=$this->_member->where(array('id'=>$info['id']))->setInc('gold_fruit',$nums);
            if(!$res_self|| !$res_other){
                $start->rollback();
                $this->ajaxReturn(['status'=>0,'msg'=>'操作失败!']);
            }
            //己方流水、对方明细
            $recharge[] = account_arr(3, $uid,'-'.$dec_nums, '金果互转', $now,0,3,$pos['mobile']);//减金果
            $recharge[] = account_arr(3, $info['id'],$nums, '金果互转', $now);//加金果

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



    //我的钱包》金果交易明细

    public function w_particulars(){
        if(IS_POST){
            $uid=$this->uid;
            $account=$this->_account;
            $map = array('uid'=>$uid,'type'=>3,'account_type'=>3);

            $page = (int)I('page');//当前页数
            $count=$account->where($map)->order('id desc')->count();//总条数
            $pages =   ceil($count/ 4); //上取整   总页数= 总条数/每页条数

            $data=$account->where($map)->limit(($page-1)*4, 4)->order('id desc')->select();
            foreach($data as $kk=>$vv){
                $data[$kk]['add_time']=date('Y-m-d',$vv['add_time']);
            }
            exit(json_encode(['data'=>$data,'pages'=>$pages]));
        }else{
            $this->display();
        }

    }


    //微信生成订单=>充值元宝
    public function recharge_make2(){

        $pos = I('post.');
        $uid=$this->uid;
        $now= time();
        $zftype=$pos['zftype'];
        $cz_money=$pos['price'];
        $order_recharge=M('order_recharge');
        //不允许3s内重复生成类似订单
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
            $this->ajaxReturn(['status'=>1,'dingdan'=>$data['dingdan'],'msg'=>'']);
        else
            $this->ajaxReturn(['status'=>0,'dingdan'=>0,'msg'=>'订单生成失败']);

    }



    //余额购买金元宝

    public function sealing_ye(){
        $uid=$this->uid;
        $pos=I('post.');
        $now= time();
        $zftype=$pos['zftype'];//'支付类型0.未选择 1=微信，2=支付宝 3余额
        $cz_money=$pos['prices'];
        $order = M('order_recharge');//第三方支付记录表
        $member_model=$this->_member;
        $mem = $member_model->find($uid);
        (!$pos['pas'])&& $this->ajaxReturn(['status'=>0,'msg'=>'请输入支付密码']);
        (!$mem['paypassword'])&& $this->ajaxReturn(['status'=>0,'msg'=>'您还未设置支付密码']);
        (st_md5($pos['pas']) != $mem['paypassword'])&& $this->ajaxReturn(['status'=>0,'msg'=>'支付密码有误']);
        ($mem['prices']< $pos['prices'])&& $this->ajaxReturn(['status'=>0,'msg'=>'余额不足']);

        //不允许3s内重复生成类似订单
        $map=['totalprices'=>$cz_money,'status'=>1,'uid'=>$uid,'type'=>2];
        $map['add_time']=['gt',$now-3];
        $over_order=$order->where($map)->count();
        $over_order&&$this->ajaxReturn(['status'=>0,'dingdan'=>0,'msg'=>'请勿重复提交']);

        //生成充值订单

        $data['dingdan']=create_order_sn();//生成订单号
        $data['uid'] = $uid;
        $data['totalprices'] = $cz_money;
        $data['type'] = 2;//1为支付 2为充值',
        $data['zftype']=$zftype;//'支付方式  0.未选择 1=微信，2=支付宝',
        $data['status']=1;//支付状态 1待支付 2支付成功 3支付失败',
        $data['add_time']=$now;
        $yd=$order->add($data);//生成临时订单
        !$yd&&$this->ajaxReturn(['status'=>0,'dingdan'=>0,'msg'=>'订单生成失败']);

        $dingdan =  $data['dingdan'];//订单号'
        $info = $order->where(array('dingdan'=>$dingdan))->find();

        $start = M();
        $start->startTrans();
            //更改订单状态
            $res_order = $order->where(array('id' => $info['id']))->save(array('status' => 2, 'pay_time' => $now, 'zftype' => 1));

             if (!$res_order) {
                $start->rollback();
                $this->ajaxReturn(['status'=>0,'msg'=>'系统繁忙']);
            }

             $jyb = $info['totalprices'];
            $save['prices'] = $mem['prices'] - $jyb;//减余额
            $save['gold_acer'] = $mem['gold_acer'] + $jyb;//加元宝
             $yb = $jyb * 0;
//            $save['silver_coin'] = $mem['silver_coin'] + $yb;//送银币=>查银币赠送规则表
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

            //区代得银币&&明细
//                    if ($district_id = $member['district_id']) {
//                        $member_qd = $member_model->where(['id' => $district_id])->find();
//                        #区代明细
//                        ($member_qd)&&$recharge[]=account_arr(4,$member['relation_id'],$silver_coin_num,'下线充值元宝',$now);
//                        if (!$res_qd){
//                            $sql=$start->_sql();
//                            throw new  Exception($sql);
//                        }
//                    }

            //添加所有明细
            $res_account = $this->_account->addAll($recharge);

              if (!$res_account) {
                $start->rollback();
                $this->ajaxReturn(['status'=>0,'msg'=>'系统繁忙']);
            }
            $start->commit();
            $this->ajaxReturn(['status'=>1,'msg'=>'充值成功','url'=>U("member/wallet")]);

    }

    //购买金元宝  调微信接口

    public function sealing_pay(){
        $uid=$this->uid;

        if (IS_POST) {
            $pos = I('post.');

            $price = $pos['price'];
            $item_type = $pos['item_type']; //1充值元宝2充值会员
            $attach = json_encode(array('item_type'=>$item_type));

            $data['attach'] = $attach;
            $data['body'] = '元宝充值';
            $data['number'] =$pos['dingdan'];

//            $data['price'] =  $price *100;//单位分
            $data['price'] =  0.01 *100;//单位分


            $data['openid'] = session('openid');

            $config = A('Api/Wxpay')->orderParameter($data);

            if ($config['err_code'] != 0) {

                echo json_encode(array('err_code'=>1, 'err_msg'=>$config['err_msg']));

            } else {

                echo json_encode(array('err_code'=>0, 'err_msg'=>$config));

            }

            exit;

        } else {

//            $appid=C('WX_CONFIG.appid');
//            $appsecret=C('WX_CONFIG.appsecret');
//
//            $wx = new \Mobile\Org\WeiXinAbout();
//            $openid = $wx->get_appid($appid, $appsecret);
//            session('openid', $openid);


            $member = D('Member')->where(array('id'=>$uid))->field('id,vips')->find();

//            $rule = D('GradeRule')->where(array('id'=>$member['vips']))->field('reward_silver_multiple,yybzh_bl')->find();
//
//            $num = 100;
//
//            $selive = $num * $rule['reward_silver_multiple'];
//
//            $this->assign('selive',$selive);
//
//            $selive_yb = $num * $rule['yybzh_bl'];
//
//            $this->assign('selive_yb',$selive_yb);
//
//            $goal = $num - $selive_yb;
//
//            $this->assign('goal',$goal);
            $this->display();

        }


    }

    //个人中心  我的钱包》银行卡

    public function w_bank(){

        $b_card = D('MemberBankcard')->where(array('member_id'=>is_login(),'status'=>1))->order('add_time desc,id desc')->limit(2)->select();

        foreach($b_card as $k=>$v){

            $b_card[$k]['nums'] = substr($v['nums'], 0, 4) . '********' . substr($v['nums'], -4);

        }

        $this->assign('b_card',$b_card);
        $count = count($b_card);
        $this->assign("count",$count);
        $this->display();

    }

    //我的钱包》银行卡》添加银行卡

    public function add_bank(){
        $param = I();
        if(IS_POST){
            $uid=$this->uid;
            $data=D('MemberBankcard')->create($param);
            $address=explode(',',$param['address']);
            $data['province']=$address[0];
            $data['city']=$address[1];
            $data['district']=$address[2];

            $data['member_id'] = $uid;
            $data['add_time'] = time();
            $add_card = D('MemberBankcard')->add($data);
            if($add_card){
                $add_bank= $param['add_bank'];//1银行卡管理 2提现
                $url=($add_bank==1)?U('member/w_bank'):U('wallet/balance_extract',['page_type'=>$param['param']]);
                $re=['status'=>1,'msg'=>'添加成功!', 'url'=> $url];
            }else{
                $re=['status'=>0,'msg'=>'添加失败!', 'url'=>'','is_tx'=>1];
            }
            $this->ajaxReturn($re);
        }else{
//        $is_tx = I('is_tx');
//        $this->assign('is_tx',$is_tx);//000
            $this->assign('add_bank',$param['add_bank']);//页面来源
            $this->assign('param',$param['param']);//附带参数
            $this->display();

        }

    }

    //我的钱包》银行卡》解绑银行卡

    public function del_bank(){

        if(IS_POST){

            $data['id'] = I('post.id');

            $card = D('MemberBankcard')->where($data)->save(array('status'=>0));

            $re=array(

                'status'=>$card?1:0,

                'msg'=>$card?'解绑成功!':'解绑失败!',

//                'url'=>$card? U('member/w_bank'):''

            );

            $this->ajaxReturn($re);
        }else{
            $this->display();
        }


    }


    //我的钱包》聚宝盆

    public function w_basin(){



        $member = D('Member')->find(is_login());

        $all_acer_jc=$member['gold_acer_jc'] + $member['silver_acer_jc'];

        empty($all_acer_jc)&&$all_acer_jc=0;

        $sy = C('pin_jbp_bs')*100;



        $this->assign('sy',$sy);

        $this->assign('all_acer_jc',$all_acer_jc);

        $this->display();



    }



    //我的钱包》聚宝盆提取

    public function w_village_number(){

        if(IS_POST){//提取
            $pos = I('post.');
            $uid=is_login();
            $member=D('Member')->find($uid);
            $jbp = D('MemberJbp')->find($pos['id']);//找到要提取的订单
            ($jbp['zftype']!=1)&& exit($this->ajaxReturn(array('status'=>0,'msg'=>'操作异常!')));
            if($jbp['totalprices'] > $member['gold_acer_jc']){
                $re=array('status'=>0,'msg'=>'操作异常,您的聚宝盆金元宝数量不足!');
                $this->ajaxReturn($re);
            }
            $start=M();
            $start->startTrans();
            $tq = D('MemberJbp')->where(array('id'=>$pos['id']))->save(['status'=>3]);//改订单状态
            if(!$tq) {
                $start->rollback();
                $this->ajaxReturn(['status' => 0, 'msg' => '操作失败!']);
            }

            $acer=array();
            //用户表加元宝减寄存的元宝
            $acer['gold_acer']=['exp',' gold_acer +'.$jbp['totalprices']];
            $acer['gold_acer_jc']=['exp',' gold_acer_jc -'.$jbp['totalprices']];
            $change_money=D('Member')->where(array('id'=>$member['id']))->save($acer);
            if(!$change_money){
                $start->rollback();
                $this->ajaxReturn(['status' => 0, 'msg' => '操作失败!']);
            }
            //元宝流水单
            $data['oid']=0;
            $data['uid'] = $uid;
            $data['type'] = 2;
            $data['totalprices'] = $jbp['totalprices'];
            $data['change_desc']='聚宝盆提取金元宝';
            $data['add_time']=time();

            $acc_res=M('Account')->add($data);//生成金元宝流水单
            if(!$acc_res) {
                $start->rollback();
                $this->ajaxReturn(['status' => 0, 'msg' => '操作失败!']);
            }

            $start->commit();
            $this->ajaxReturn(['status' => 1, 'msg' => '操作成功!']);

        }else{//展示

            $data['member_id']=is_login();

            $data['status']=array('in',array(1,2));

            //判断聚宝盆可提现状态

            $jbp = D('MemberJbp')->where($data)->order('add_time desc')->select();

            $today = strtotime(date('Y-m-d', time())) ;//今天0点

            $tian = C('pin_jbp_zq');
            foreach($jbp as $k=>$v){

                $start = strtotime(date('Y-m-d', $v['add_time']));//开始时间

                $aaa = ($start+$tian*3600*24-$today)/(24*3600);
                $jbp[$k]['other_days']=$aaa;
                //可提取立即修改状态
                if($aaa == 0 || $aaa < 0){
                    $res=  D('MemberJbp')->where(array('id'=>$v['id']))->save(array('status'=>2));
                    $jbp[$k]['status']=2;
                }

            }
            $this->assign('jbp',$jbp);

            $this->display();

        }





    }





    //我的钱包》聚宝盆:寄存金元宝、银元宝

    public function w_village(){

        $uid = $this->uid;

        if(IS_POST){

            $member=$this->_member->find($uid);
            $pos = I('post.');
            $nums_jyb=$pos['nums_jyb'];
            ($nums_jyb<0||$nums_jyb==0) && $this->ajaxReturn(['status' => 0, 'msg' => '操作失败!']);
            $now=time();

            //生成聚宝盆记录
            if($nums_jyb > $member['gold_acer']){
                $this->ajaxReturn(['status' => 0, 'msg' => '操作失败!您的金元宝余额不足']);
            }

            $jyb['member_id'] =$uid;
            $jyb['totalprices'] = $nums_jyb;
            $jyb['memos']='聚宝盆存金元宝';
            $jyb['status'] = 1;//1寄存中/2未提取/3已提取
            $jyb['add_time']=$now;
            $res_jyb=M('MemberJbp')->add($jyb);
            if(!$res_jyb){
                $this->ajaxReturn(['status' => 0, 'msg' => '操作失败!']);
            }
            //金额变化
            $a=0;
            $acer=array();
            $acer['gold_acer_jc']=$member['gold_acer_jc']+$pos['nums_jyb'];//聚宝盆加元宝
            $acer['gold_acer']=$member['gold_acer']-$pos['nums_jyb'];//减元宝
            $a=$pos['nums_jyb']*C('pin_jbp_bs');//送银币
            $acer['silver_coin'] = $member['silver_coin'] + $a;

            $mem =$this->_member->where(array('id'=>$uid))->save($acer);//银币
            //元宝流水&&银币流水
            $data[]=[
                'oid'           =>0,
                'uid'           =>$uid,
                'type'          =>2,//币种1工资2金元宝3金果4银币
                'totalprices'   =>'-'.$pos['nums_jyb'],
                'change_desc'   =>'聚宝盆存金元宝',
                'add_time'      =>$now
            ];
            if($a > 0){
                $data[]=[
                    'oid'           =>0,
                    'uid'           =>$uid,
                    'type'          =>4,
                    'totalprices'   =>$a,
                    'change_desc'   =>'聚宝盆',
                    'add_time'      =>$now
                ];
            }

            $acc_res=$this->_account->addAll($data);//生成金元宝流水单

            $re=array(
                'status'=>$acc_res?1:0,
                'msg'=>$acc_res?'操作成功!':'操作失败!',
                'url'=>$acc_res?U('member/w_basin'):'',
            );

            $this->ajaxReturn($re);exit;

        }

        $this->display();

    }

    //我的钱包  收支明细(余额)
    public function w_record(){

        $ye = D('MemberRecharge')->where(array('member_id'=>is_login(),'item_type'=>4))->order('add_time desc')->select();

        $this->assign('ye',$ye);
        $this->display();
    }


    //我的钱包》线下转账
    public function w_transfer(){

        if(IS_POST){
            $pos = I('post.');
            $uid=$this->uid;
            $now=time();

            //不允许3s内重复生成类似订单
            $map=['totalprices'=>$pos['prices'],'status'=>1,'uid'=>$uid,'img'=>$pos['img_one']];
            $map['add_time']=['gt',$now-3];
            $over_order=M('apply_line')->where($map)->count();
            $over_order&&$this->ajaxReturn(['status'=>0,'msg'=>'请勿重复提交申请','withdraw_id'=>0]);

            $card = D('BankCard')->find($pos['card_id']);//平台银行卡id

            $data['order_no']=create_order_sn();//生成订单号
            $data['uid']=$uid;
            $data['account_name']=$pos['account_name'];//申请人开户名
            $data['branch_nums']=$card['nums'];
            $data['branch_name']=$card['name'];//收款银行名
            $data['totalprices']=$pos['prices'];//金额
            $data['memos']='线下充值';//备注
            $data['bankcard_id']=$pos['card_id'];//平台银行的id
            $data['status']=1;//状态 1未审核 1通过 2驳回
            $data['img']=$pos['img_one'];//照片
            $data['add_time']=$now;

            $yd=M('apply_line')->add($data);

            if($yd){
                $this->ajaxReturn(['status'=>1,'msg'=>'订单生成成功','withdraw_id'=>$yd]);
            }else{
                $this->ajaxReturn(['status'=>0,'msg'=>'订单生成失败','withdraw_id'=>0]);

            }
        }else{
            $pt_card = D('BankCard')->select();//平台银行卡
            $this->assign('pt_card',$pt_card);

            //获取微信分享必要参数
            $jssdk = new \Mobile\Org\Jssdk(C("WX_CONFIG.appid"), C("WX_CONFIG.appsecret"));
            $js = $jssdk->GetSignPackage();
            $this->assign('js',$js);

            $this->display();
        }


    }


    //线下转账申请图片
    public function upload_apply_line_img()
    {
        $data = I();
//        file_put_contents('wx_file.txt', var_export($data,true));

        $folder='sh_img/';//审核图片存放的文件夹

        $res= wx_upload_img($data,$folder);

        if($res){
            $this->ajaxReturn(array('status'=>1,'msg'=>'上传成功','name'=>$res));
        }else{
            $this->ajaxReturn(array('status'=>-1,'msg'=>'上传失败'));
        }

    }

    //我的钱包》线下转账记录
    public function w_transfer_record(){

        $zz_list = D('Withdraw')->where(array('type'=>2,'member_id'=>is_login()))->order('create_time desc')->select();

        //转账凭证
        foreach($zz_list as $k=>$v){

            $zz_list[$k]['img_code']= D('ShImg')->where(array('withdraw_id'=>$v['id']))->getField('img');

        }
        $this->assign('zz_list',$zz_list);

        $this->display();

    }

    //同城配送
    public function city_service(){
        $this->display();
    }





    //个人中心
    public function mine_old(){

        $uid=$this->uid;
        $mem = $this->_member->find($uid);
        $gr = D('GradeRule')->getField('id,name');
        $mem['vips']=$gr[ $mem['vips']];

        #今天财富值统计
        $time_end=time();//当前时间
        $time_start= strtotime(date('Y-m-d',$time_end));//今天0点时间戳s
        $time_rule['add_time'] =  array('between',array($time_start,$time_end));
        $time_rule['uid'] = $uid;
        $time_rule['totalprices'] = ['gt',0];
        $time_rule['type'] = 4;//1为支付 2为充值',
        $selive_coin_day = $this->_account->where($time_rule)->getField(' sum(totalprices) ');
        $selive_coin_day=intval($selive_coin_day);

        $this->assign('mem',$mem);
        $this->assign('selive_coin_day',$selive_coin_day);
        $this->display();

    }

    //图片上传*******************************************************************
    public function zed(){
        if (!empty($_FILES['file']['name'])) {

            $result = $this->_upload($_FILES['file'], 'useravatar',array('width'=>C('pin_article_cate_img.width'),'height'=>C('pin_article_cate_img.height')));

            if ($result['error']) {

                $this->ajaxReturn(0);

            } else {

                $result['info'][0]['savename'] = str_replace('.','_thumb.',$result['info'][0]['savename']);

                $data['img'] = $result['info'][0]['savename'];

                //$db = D('Member')->where(array('id'=>is_login()))->setField('avatar',$data['img']);

                $this->ajaxReturn($data);

            }

        } else {

            $this->ajaxReturn(0);

        }

    }

    //微信多图上传
    public function uploadImage()
    {
        $data = I();
        $folder='avatar/';
        $res= wx_upload_img($data,$folder);

        if($res){
            $this->ajaxReturn(array('status'=>1,'msg'=>'上传成功','name'=>$res));
        }else{
            $this->ajaxReturn(array('status'=>-1,'msg'=>'上传失败'));
        }

    }

    //微信多图上传>未封装
//    public function uploadImage0()
//    {
//        $media_id = I('media_id', '', 'trim');
//        $appid = C("WX_CONFIG.appid");
//        $appsecret = C("WX_CONFIG.appsecret");
//        if (!S('wx_access_token')) {
//            $res = json_decode(file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$appsecret}"), true);
//            $access_token = $res['access_token'];
//
//            if ($access_token) {
//                S('wx_access_token',$access_token,7000);
//            }
//        } else {
//            $access_token = S('wx_access_token');
//        }
//
//        $uri = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$media_id";
//        $res1 = file_get_contents($uri);
//
//        $imgname = $media_id.".jpg";
//        $dir = "data/attachment/useravatar/".$imgname;
//        $res2 = file_put_contents($dir, $res1);
//        if($res2){
//            $this->ajaxReturn(array('sta'=>1,'msg'=>'上传成功','name'=>$imgname));exit;
//        }else{
//            $this->ajaxReturn(array('sta'=>-1,'msg'=>'上传失败'));exit;
//        }
//    }


}