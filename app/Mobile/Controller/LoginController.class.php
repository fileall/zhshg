<?php
namespace Mobile\Controller;
/**登录注册
 * Class LoginController
 * @package Mobile\Controllers
 */
class LoginController extends HomeController {
    public function _initialize() {
        parent::_initialize();
        $this->_member=D('Member');
		
    }

    //注册***********************
     public function register(){
         $user=$this->_member;
         if(IS_POST){
            //$obj=new \Think\Verify();
            //(!$obj->check(I('post.yzm_code','','trim')))&&$this->ajaxReturn(array('status'=>0,'msg'=>'图形验证码输入有误'));
            $param=I();
            $now=time();
            $reg_coin=10000;//注册送银币
            $reg_coin_up=1000;//上线送银币
            $relation_mobile= $param['relation_mobile'];

            $code = cookie('reg_data');//短信验证
			($param['m_code']!=$code['code'])&&exit(json_encode(array('status'=>0,'msg'=>'手机验证码错误')));

            $have_c =$user->where(array('mobile'=>$param['relation_mobile']))->getField('id');
            $relation_mobile&&(!$have_c)&& exit(json_encode(array('status'=>0,'msg'=>'请正确填写推荐人')));

            if (false === $data = $user->create($param))
                 $this->ajaxReturn(array('status'=>0,'msg'=>$user->getError()));
            $arr_addr=explode(',',$data['address']);
            $data['province_id']= get_place_id($arr_addr[0]);
            $data['city_id']=get_place_id($arr_addr[1]);
            $data['district_id']=get_place_id($arr_addr[2]);
            $data['nickname']=$data['mobile'];
            $have_c&&$data['relation_id']=$have_c;//上线
            $data['silver_coin']=$reg_coin;

            $user->startTrans();//开启事务
            $uid = $user->add($data);
            if($uid){
                //我的二维码=mobile
                $ewm=  $this->set_qrcode("http://".$_SERVER['HTTP_HOST'].U('login/register',array('ewid'=>$uid)));
                $res_ewm=$user->where(array('id'=>$uid))->setField('ewm',$ewm);
                if(!$res_ewm){
                    $user->rollback();
                    exit(json_encode(array('status'=>0,'msg'=>'操作失败')));
                }

                //本人银币&&上线银币
                $recharge[] = account_arr(4, $uid,$reg_coin, '注册送银币', $now);//本人银币明细
                if($have_c){
                    $send_coin_up=$user->where(array('id'=>$have_c))->setInc('silver_coin',$reg_coin_up);
                    if(!$send_coin_up){
                        $user->rollback();
                        exit(json_encode(array('status'=>0,'msg'=>'操作失败')));
                    }
                    $recharge[] = account_arr(4, $have_c,$reg_coin_up, '下线注册送银币', $now);//上线银币明细
                }
                $res_account=M('account')->addAll($recharge);
                if(!$res_account){
                    $user->rollback();
                    exit(json_encode(array('status'=>0,'msg'=>'操作失败')));
                }

                $user->commit();
                cookie('reg_data',null);

                exit(json_encode(array('status'=>1,'msg'=>'注册成功','url'=>U('enter'))));
            }else{
                $user->rollback();
                exit(json_encode(array('status'=>0,'msg'=>'操作失败，请重试')));
            }

        }else{
             $tjr_id = I('ewid','','trim');//获得推荐码(上级推荐人的加密id)//改=上级的mobile
             $relation_mobile=$user->where(['id'=>$tjr_id])->getField('mobile');
             $relation_mobile&& $this->assign('relation_mobile',$relation_mobile);
            $this->display();
        }
    }
//    public function about(){
//        $type = I('type','','trim');//传参 1 为用户协议 2为关于我们
//        $User = M('article');
//        if($type == 2){
//            $article = $User->where(array('cate_id'=>3,'id'=>178))->find();
//        }else if($type == 1){
//             $article=  $User->where(array('cate_id'=>24,'id'=>180))->find();
//        }
//        $this->assign('article',$article);
//        $this->assign('type',$type);
//        $this->display();
//    }

    //登录开始************************************************************
    //登录     wx登录http://www.jb51.net/article/94717.htm
    public function enter(){
    	if(IS_POST){
            $data = I('post.');
            $info = M('Member')->where(array('mobile'=>$data['mobile']))->find();
            empty($info) && exit(json_encode(array('status'=>0,'msg'=>'手机号不存在')));
            empty($info['status']) && exit(json_encode(array('status'=>0,'msg'=>'手机号被禁止登录')));
            (st_md5($data['password']) != $info['password']) && exit(json_encode(array('status'=>0,'msg'=>'密码错误')));
            $now=time();
            $user_auth=array(
                'id' => $info['id'],
                'mobile' => $info['mobile'],
                'password'=> $data['password'],
                'session_start_time'=>$now,
            );
            session('user_auth',$user_auth);
            cookie('user_auth',$user_auth,3600*24*7);

            $coin['last_login_time'] = $now;
            $coin['last_login_ip'] = get_client_ip();

            $this->_member->where(array('id'=>$info['id']))->save($coin);
            $url=($info['is_qd']==1)?U('Agent/agent'):U('member/mine');//?区代:普通

            exit(json_encode(array('status'=>1,'msg'=>'登录成功','url'=>$url)));
       }else{
            is_login()&& $this->redirect('Member/mine');

//            $wx_pay_config=C('wx_pay_config');
//        	$appid =$wx_pay_config['appid'] ;
//    		$appsecret = $wx_pay_config['appsecret'];
//    		$wx = new \Mobile\Org\WeiXinAbout($appid,$appsecret);
//			$openid=$wx->get_appid($appid,$appsecret);
//            session('openid_enter',$openid);

            $this->display();
        }
    }

    //微信登录
    public function wx_login(){
        $wx_pay_config=C('wx_pay_config');
        $appid =$wx_pay_config['appid'] ;
        $appsecret = $wx_pay_config['appsecret'];

        $wx = new \Mobile\Org\WeiXinAbout($appid,$appsecret);
        $openid = session('openid_enter');
//        $openid='oy5y10b_hFP1Ofg3Mvh1q_hxZ9Ao';
//        file_put_contents('xx.txt',var_export($wx_info,true));

        $wx_info=$wx->get_user_info($openid);//$openid获取用户信息
        if($wx_info['errcode']){
            exit(json_encode(array('status'=>0,'msg'=>'系统繁忙')));
        }

        $info=M('member')->where(array('wx_openid'=>$openid))->find();
        //找到直接登录、没找到将获取的信息注册账号
        if (!$info) {
            cookie('wx_info',$wx_info);
            exit(json_encode(array('status'=>0,'msg'=>'请先绑定手机','url'=>U('Login/binding'))));
        } else {
            $user_auth=array(
                'id' => $info['id'],
                'mobile' => $info['mobile'],
                'password'=> $info['password']
            );
            session('user_auth',$user_auth);
            cookie('user_auth',$user_auth,3600*24*7);

            exit(json_encode(array('status'=>1,'msg'=>'登录成功','url'=>U('member/mine'))));
        }
    }

    //绑定手机、省市区、登录密码
    public function binding()
    {
        if(IS_POST){
            $param=I();
            $now=time();
            $user=$this->_member;
            $relation_mobile= $param['relation_mobile'];

            $code = cookie('reg_data');//短信验证
			($param['m_code']!=$code['code'])&&exit(json_encode(array('status'=>0,'msg'=>'手机验证码错误')));


            $have_c =$user->where(array('mobile'=>$param['relation_mobile']))->getField('id');
            $relation_mobile&&(!$have_c)&& exit(json_encode(array('status'=>0,'msg'=>'请正确填写推荐人')));

            $arr_addr=explode(',',$param['address']);
            $data['address']= $param['address'];
            $data['province_id']= get_place_id($arr_addr[0]);
            $data['city_id']=get_place_id($arr_addr[1]);
            $data['district_id']=get_place_id($arr_addr[2]);
            $have_c&&$data['relation_id']=$have_c;//上线

            $wx_info=cookie('wx_info');//openid nickname avatar=headimgurl
//            var_dump($wx_info);die;
            (!$mobile=$param['mobile'])&& $this->ajaxReturn(['status'=>0,'msg'=>'请输入手机号码!']);

            $user->startTrans();//开启事务
            $openid_info=$this->_member->where(['mobile'=>$mobile])->field('id,wx_openid,mobile,password')->find();
            if($openid_info){//找到手机号对应账号=》将openid写入
                $data['wx_openid']=$wx_info['openid'];
                $data['nickname']=$wx_info['nickname'];
                $data['avatar']=get_tx_avatar($wx_info['headimgurl'],'avatar');//保存头像
                $data['avatar']=$data['avatar']?$data['avatar']:'';
                $res=$this->_member->where(['id'=>$openid_info['id']])->save($data);
                !$res&& $this->ajaxReturn(['status'=>0,'msg'=>'操作失败!']);
                if(!$res){
                    $user->rollback();
                    $this->ajaxReturn(['status'=>0,'msg'=>'操作失败!']);
                }
            }else{//没找到手机号对应账号=》生成新的账号
                $reg_coin=10000;//注册送银币
                $reg_coin_up=1000;//上线送银币
                $data['silver_coin']=$reg_coin;
                $data['nickname']=$wx_info['nickname'];
                $data['password']=st_md5(123456);//默认密码
                $data['mobile']=$mobile;
                $data['reg_time']=$_SERVER['REQUEST_TIME'];
                $data['wx_openid']=$wx_info['openid'];
                $data['avatar']=get_tx_avatar($wx_info['headimgurl'],'avatar');//保存头像
                $data['avatar']=$data['avatar']?$data['avatar']:'';
                $res=$this->_member->add($data);

                if(!$res){
                    $user->rollback();
                    $this->ajaxReturn(['status'=>0,'msg'=>'操作失败!']);
                }

                //我的二维码=mobile
                $ewm=  $this->set_qrcode("http://".$_SERVER['HTTP_HOST'].U('login/register',array('ewid'=>$res)));
                $res_ewm=$user->where(array('id'=>$res))->setField('ewm',$ewm);
                if(!$res_ewm){
                    $user->rollback();
                    $this->ajaxReturn(['status'=>0,'msg'=>'操作失败!']);
                }

                //本人银币&&上线银币
                $recharge[] = account_arr(4, $res,$reg_coin, '注册送银币', $now);//本人银币明细
                if($have_c){
                    $send_coin_up=$user->where(array('id'=>$have_c))->setInc('silver_coin',$reg_coin_up);
                    if(!$send_coin_up){
                        $user->rollback();
                        $this->ajaxReturn(['status'=>0,'msg'=>'操作失败!']);
                    }
                    $recharge[] = account_arr(4, $have_c,$reg_coin_up, '下线注册送银币', $now);//上线银币明细
                }
                $res_account=M('account')->addAll($recharge);
                if(!$res_account){
                    $user->rollback();
                    $this->ajaxReturn(['status'=>0,'msg'=>'操作失败!']);
                }
            }

            cookie('reg_data',null);//销毁短信验证
            cookie('wx_info',null);//销毁微信记录
            $user->commit();

            $user_auth=array(
                'id' => $openid_info['id']?$openid_info['id']:$res,
                'mobile' =>$mobile,
                'password'=> $openid_info['password']
            );
            session('user_auth',$user_auth);
            cookie('user_auth',$user_auth,3600*24*7);
            $this->ajaxReturn(['status'=>1,'msg'=>'操作成功!','url'=>U('member/mine')]);
        }else{
            $this->display();
        }


    }



    //登录结束************************************************************


    //退出登录
    public function login_out(){
        session('user_auth',null);
        cookie('user_auth',null);
        $re = array('status' => 1,'msg' =>'已退出登录!','url' => U('login/enter'));
        $this->ajaxReturn($re);
    }

    //忘记密码
    public function forgot_pwd(){
    	 if(IS_POST){
            $data = I('post.');
            $pwd_data = cookie('reg_data');//短信验证
            ($pwd_data['mobile'] != $data['mobile']) && exit(json_encode(array('stuatus'=>0,'msg'=>'手机号更改或验证码已过期')));
            ($pwd_data['code'] != $data['m_code']) && exit(json_encode(array('stuatus'=>0,'msg'=>'验证码错误或已过期')));

            $member_modle=$this->_member;
 			$info = $member_modle->where(array('mobile'=>$data['mobile']))->find();
            empty($info) && exit(json_encode(array('status'=>0,'msg'=>'该账号不存在')));
            empty($info['status']) && exit(json_encode(array('status'=>0,'msg'=>'该账号被禁止登录')));

             $pwd = st_md5($data['password']);
             $res=$member_modle->where(array('mobile'=>$data['mobile']))->setField('password',$pwd);

             (false===$res)&& exit(json_encode(array('status'=>0,'msg'=>'修改失败')));

             cookie('reg_data',null);//清除手机验证码
             exit(json_encode(array('status'=>1,'msg'=>'修改成功','url' => U('login/enter'))));
        }else{
            //动态关闭表单令牌
//          C('TOKEN_ON',false);
            $this->display();
        }
    }



    //叮咚云*******************
    public function aa(){
        $code = rand(100000,999999);
        //修改为您的apikey. apikey可在官网（https://www.dingdongcloud.com)登录后获取
        $apikey = "40c8edf07e2110924d6bd8f0fc3e4a5e";
        //修改为您要发送的手机号
        $mobile=I('mobile');
        $ch = curl_init();
        /* 设置验证方式 */
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:text/plain;charset=utf-8', 'Content-Type:application/x-www-form-urlencoded','charset=utf-8'));
        /* 设置返回结果为流 */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        /* 设置超时时间min*/
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        /* 设置通信方式 */
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // 发送验证码短信
        // 修改为您要发送的短信内容,需要对content进行编码
        $yzmcontent="【悦买宝】您的验证码是：".$code."。请在10分钟内输入，请勿告诉其他人。";
        $data=array('content'=>urlencode($yzmcontent),'apikey'=>$apikey,'mobile'=>$mobile);

        curl_setopt ($ch, CURLOPT_URL, 'https://api.dingdongcloud.com/v1/sms/sendyzm');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $array = json_decode(curl_exec($ch),true);//返回的参数
//		var_dump($array['code']);die;
        if($array['code']==1){
            cookie('reg_data',array('mobile'=>$mobile,'code'=>$code),600);//验证码保存600s
            $return =array('status' =>  1,'msg'=> '发送成功',);
        }else{
            $return =array('status' => -1,'msg'=> '发送失败',);
        }
        ob_clean();
        $this->ajaxReturn($return);
    }


    //云片手机验证短信0*****************************
    public function bb(){
        $mobile=I('mobile');
        $code = rand(100000,999999);

        // 必要参数
        $apikey ="76f75fec654e59fc20a55ba4caebede6";//修改为您的apikey(https://www.yunpian.com)登录官网后获取
        $text="您的验证码是".$code."。如非本人操作，请忽略本短信";
        // 发送短信
        $data=array('text'=>$text,'apikey'=>$apikey,'mobile'=>$mobile);
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/single_send.json');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:text/plain;charset=utf-8', 'Content-Type:application/x-www-form-urlencoded','charset=utf-8'));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $json_data = json_decode(curl_exec($ch),true);
        if(curl_exec($ch)){
            cookie('reg_data',array('mobile'=>$mobile,'code'=>$code),600);//验证码保存600s
            $return =array('status' =>  1,'msg'=> '发送成功',);
        }else{
            $return =array('status' => -1,'msg'=> '发送失败',);
        }
        ob_clean();
        $this->ajaxReturn($return);
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    //注册获取验证码  enroll
    public function reg_send_sms(){
         $mobile = I('mobile','','trim');
        empty($mobile) &&  $this->ajaxReturn(array(0,'请输入手机号'));
        (check_mobile($mobile) === false) && $this->ajaxReturn(array(0,'手机号格式错误'));
        $only = M('Member')->where(array('mobile'=>$mobile))->count();
        ($only > 0) && $this->ajaxReturn(array(0,'该手机号已注册'));
        $code = rand(100000,999999);
        $result = $this->sendSms($mobile,'1981560',('#code#').'='.urlencode((string)$code));
        if($result['code'] === 0){
            cookie('reg_data',array('mobile'=>$mobile,'code'=>$code),600);
			$this->ajaxReturn(array(1,'短信已发送'));
        }else{
			$this->ajaxReturn(array(0,'发送失败，请重试'));
        }
    }

    //得到验证码    前台图片调用此方法得到图片地址***********
    public function verifyImg(){
        $config =    array(
            'fontSize'    =>    18,    // 验证码字体大小
            'length'      =>    4,     // 验证码位数
            'imageH'      =>    34, // 关闭验证码杂点
            'useNoise'    =>    false, // 关闭验证码杂点
            'useCurve'    =>    false,
//      'reset' => false // 验证成功后是否重置
        );
        $obj = new \Think\Verify($config);
        $obj->entry();
    }


}