<?php
namespace Mobile\Controller;
class LoginController extends HomeController {
    public function _initialize() {
        parent::_initialize();
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
    //注册***********************
     public function register(){
     	$tjr_id = I('ewid','','trim');//获得推荐码(上级推荐人的加密id)//改=上级的mobile

     	($tjr_id)&& $this->assign('tjr_id',$tjr_id);
        if(IS_POST){
            $user = D('Member');
		    $obj=new \Think\Verify();	
//        	if($obj->check(I('post.yzm_code','','trim'))){
	            if (false === $data = $user->create()){
					$this->ajaxReturn(array('status'=>0,'msg'=>$user->getError()));
	            }else{

	            	$have_c = D('Member')->where(array('mobile'=>$data['relation_id']))->find();
	            	(!$have_c&&$data['relation_id']) && exit(json_encode(array('status'=>0,'msg'=>'请填写正确的推荐人或保持推荐人一栏为空')));
		           
		            #默认推荐人是商家$tj_type 0默认无人推荐1用户2商家
					//添加$tj_type字段
//					$tj_type=0;
//					$tuijian=$data['relation_id'];
//					$have_c=D('Merchant')->where(array('tel'=>$tuijian))->find();//先查商家
//					($have_c)&&$tj_type=2;
//					if(!$have_c){
//						$have_c=D('Member')->where(array('mobile'=>$tuijian))->find();
//			        	(!$have_c&&$tuijian) && exit(json_encode(array('status'=>0,'msg'=>'请填写正确的推荐人或保持推荐人一栏为空')));
//						($have_c)&&$tj_type=1;
//					}
	            	$data['vips'] = 1;
	            	$data['sex'] = 0;//默认0未选择
	            	$data['tj_type'] = 1;//上线身份 0无1会员2商户
	            	
	            	$str=I('mobile','','trim');
//			    	$str = substr($str, 0, strlen($str) - 4); 
			    	
	                $user->startTrans();
					$uid = $user->add($data);
					
	                if($uid){
	                    $user->commit();
	                    //生成二维码  (加密id)
//		                $ewid = $this->encode($uid);
						//我的二维码=mobile
		               	$datas['ewm'] =  $this->set_qrcode("http://".$_SERVER['HTTP_HOST'].U('login/register',array('ewid'=>$data['mobile'])));
		                !empty($have_c)&&	$datas['relation_id'] = $have_c['id'];//推荐人
		               	$datas['nickname'] = $str;//默认用户昵称
		               	//注册送银币
		               	$reg_rule = D('GradeRule')->find(1);//等级规则
		               	$datas['silver_coin'] = $reg_rule['reg_silver'];//注册人得银币
		               	$user->where(array('id'=>$uid))->save($datas);
		               	yb_ls($uid,$reg_rule['reg_silver'],2,'注册送银币');//注册人银币流水
		               	//被推荐人是用户*
						#注册送上线银币
//				       	if($tj_type==1){
//				       		$tj_silver = D('GradeRule')->find($have_['vips']);//等级规则
//				       		D('Member')->where(array('id'=>$have_c['id']))->setInc('silver_coin',$tj_silver['tj_silver']);//推荐人得银币
//     						all_ls($have_c['id'],$tj_silver['tj_silver'],6,2,'下线注册送银币');//人银币流水
//				       	}
//				       	if($tj_type==2){
//				       		$tj_silver = D('GradeRule')->find(3);//等级规则
//				       		D('Merchant')->where(array('id'=>$have_c['id']))->setInc('silver_coin',$reg_rule['tj_silver']);//推荐shop得银币
//     						all_ls_shop($have_c['id'],$tj_silver['tj_silver'],2,2,'下线注册送银币');//shop银币流水
//				       	}
		               	
		               	if($have_c){
		               		$user->where(array('id'=>$have_c['id']))->setInc('silver_coin',$reg_rule['tj_silver']);//推荐人得银币
		               		yb_ls($have_c['id'],$reg_rule['tj_silver'],2,'下线注册送银币');//推荐人银币流水
		               	}
		               	
	                    cookie('reg_data',null);
						$info = M('Member')->where(array('mobile'=>$data['mobile']))->find();
						session('user_auth',array(
			                'id' => $info['id'],
			                'mobile' => $info['mobile']
			            ));
			            cookie('user_auth',array(
			                'id' => $info['id'],
			                'mobile' => $info['mobile']
			            ));
	                    exit(json_encode(array('status'=>1,'msg'=>'注册成功')));
	                }else{
	                    $user->rollback();
	                    exit(json_encode(array('status'=>0,'msg'=>'操作失败，请重试')));
	                }
	            }
//	        }else{
//	        	$this->ajaxReturn(array('status'=>0,'msg'=>'图形验证码输入有误'));
//	        }   
        }else{
//      	if (!$code = I('get.code')) {
//      		$redirect_uri = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
//      		$appid = 'wxad4fc1ee40754ebf';
//      		$get_code_uri = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_base &state=STATE#wechat_redirect";
//      	} else {
//      		$get_access_token_uri = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=APPID&secret=SECRET&code=CODE&grant_type=authorization_code'; 
//      	}
//      	
            $this->display('enroll');
        }
    }
  //叮咚云验证码
	function send_yzm($ch,$data){
	    curl_setopt ($ch, CURLOPT_URL, 'https://api.dingdongcloud.com/v1/sms/sendyzm');
	    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
	    return curl_exec($ch);
	}
    //叮咚云*******************
    public function bb(){
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
		$yzmcontent="【臻怡家生活馆】您的验证码是：".$code."。请在10分钟内输入，请勿告诉其他人。";  
		$data=array('content'=>urlencode($yzmcontent),'apikey'=>$apikey,'mobile'=>$mobile);
		
		curl_setopt ($ch, CURLOPT_URL, 'https://api.dingdongcloud.com/v1/sms/sendyzm');
	    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		$array = json_decode(curl_exec($ch),true);
//		var_dump($array['code']);die;
		if($array['code']==1){
			$datas=array('mobile'=>$mobile,'code'=>$code,'add_time'=>time());
			$db = D('MobileCode')->add($datas);//发送成功保存到数据库
			$db && cookie('reg_data',array('mobile'=>$mobile,'code'=>$code),600);
			$return =array(
				'status' => $db?1:0,
				'msg'=> $db ?'发送成功':'发送失败'
			);
			ob_clean();
			$this->ajaxReturn($return);
		}else{
			$return =array(
				'status' => -1,
				'msg'=> '发送失败',
			);
			ob_clean();
			$this->ajaxReturn($return);
		}
    }
	    
    
    //云片手机验证短信*****************************
	public function aa(){
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
			$data=array(
				'mobile'=>$mobile,
				'code'=>$code,
				'add_time'=>time()
			);
			$db = D('MobileCode')->add($data);//发送成功保存到数据库
			$db && cookie('reg_data',array('mobile'=>$mobile,'code'=>$code),600);
			
			$return =array(
				'status' => $db?1:0,
				'msg'=> $db ?'发送成功':'保存失败'
			);
			ob_clean();
			$this->ajaxReturn($return);
		}else{
			$return =array(
				'status' => -1,
				'msg'=> '发送失败',
			);
			ob_clean();
			$this->ajaxReturn($return);
		}
	}
    //登录***********************
    public function enter(){
    	if(IS_POST){
            $data = I('post.');
            $info = M('Member')->where(array('mobile'=>$data['mobile']))->find();
            empty($info) && exit(json_encode(array('status'=>0,'msg'=>'手机号不存在')));
            empty($info['status']) && exit(json_encode(array('status'=>0,'msg'=>'手机号被禁止登录')));
            (st_md5($data['password']) != $info['password']) && exit(json_encode(array('status'=>0,'msg'=>'密码错误')));
            session('user_auth',array(
                'id' => $info['id'],
                'mobile' => $info['mobile'] 
            ));
            cookie('user_auth',array(
                'id' => $info['id'],
                'mobile' => $info['mobile']
            ));
            

            //判断今天是否登录过//银币转换金币
//         	 $time1= strtotime('2017-10-14 23:00:00');//时间戳s
//			 $a = date('Y-m-d H:i:s', $time1);//上

           	 $a = date('Y-m-d H:i:s', $info['last_login_time']);//上
			 $b = date('Y-m-d', time());//今天0点 
			 
//         	 if ($a < $b ) {   
//         	 	$yb = $info['silver_coin'];
//	            $coin['gold_coin'] = $info['gold_coin'] + $yb * C('pin_silver_coin');//加金币后
//	            $coin['silver_coin'] = $yb - $yb * C('pin_silver_coin');//减银币后  
//         	 }

            $coin['last_login_time'] = time();
            $coin['last_login_ip'] = get_client_ip();      
			  	 
			D('Member')->where(array('id'=>$info['id']))->save($coin);
           
            exit(json_encode(array('status'=>1,'msg'=>'登录成功','url'=>U('member/mine'))));
        }else{
            $this->display();
        }
    } 
   
     //退出登录***********************
     public function login_out(){	
     	
     	if(IS_AJAX){
 			session('user_auth',null);
	        cookie('user_auth',null);
	        $re = array(
	        	'status' => 1,
	        	'msg' =>'已退出登录!',
	        	'url' => U('login/enter')
	        );
	        $this->ajaxReturn($re);
     	}
	}
    //忘记密码
    public function forgot_pwd(){
    	 if(IS_POST){
            $data = I('post.');
            $pwd_data = cookie('reg_data');
            ($pwd_data['mobile'] != $data['mobile']) && exit(json_encode(array('stuatus'=>0,'msg'=>'手机号更改或验证码已过期')));
            ($pwd_data['code'] != $data['code']) && exit(json_encode(array('stuatus'=>0,'msg'=>'验证码错误或已过期')));

            $info = M('Member')->where(array('mobile'=>$data['mobile'],'status'=>1))->find();
            empty($info) && exit(json_encode(array('stuatus'=>0,'msg'=>'该手机号被禁止登录')));

            $pwd = st_md5($data['password']);
            if($pwd == $info['password']) {
                cookie('reg_data',null);
                exit(json_encode(array('stuatus'=>1,'msg'=>'修改成功')));
            }elseif(M('Member')->where(array('mobile'=>$data['mobile']))->setfield('password',$pwd)){
                cookie('reg_data',null);
                exit(json_encode(array('stuatus'=>1,'msg'=>'修改成功')));
            }else{
                exit(json_encode(array('stuatus'=>0,'msg'=>'操作失败，请重试')));
            }
        }else{
            //动态关闭表单令牌
//          C('TOKEN_ON',false);
            $this->display('password');
        }
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
    
    
    //注册
    public function register0(){
        if(IS_POST){
            $user = D('Member');
            if (false === $data = $user->create()){
				$this->ajaxReturn(array('status'=>0,'info'=>$user->getError()));
            }else{
                //注册送积分规则
                $time = time();
                $info = M('IntegralRule')->find(1);
                $user->startTrans();
                if($info['status'] == 1 && $time >= $info['start_time'] && $time <= $info['end_time']){
                    $user->integral = $info['integral'];
                    $uid = $user->add();
                    $integral_log = M('Integral')->add(array(
                        'uid' => $uid,
                        'integral' => $info['integral'],
                        'describe' => '注册送积分',
                        'add_time' => $time,
                        'type' => 1
                    ));
                }else{
                    $uid = $user->add();
                    $integral_log = true;
                }

                //验证操作
                if($uid && $integral_log){
                    $user->commit();
                    cookie('reg_data',null);
                    exit(json_encode(array(1,'注册成功')));
                }else{
                    $user->rollback();
                    exit(json_encode(array(0,'操作失败，请重试')));
                }
            }
        }else{
          //  $cate = M('CurriculumCate')->where(array('pid'=>0,'status'=>1))
           //     ->field('id,name')
           //     ->order('ordid,id desc')
           //     ->select();

          //  $this->assign('cate',$cate);
            $this->display('enroll');
        }
    }

    //登录
    public function enter0(){
        if(IS_POST){
            $data = I('post.');
            empty($data['mobile']) && exit(json_encode(array(0,'请输入手机号')));
            (check_mobile($data['mobile']) === false) && exit(json_encode(array(0,'手机号格式错误')));
            empty($data['password']) && exit(json_encode(array(0,'请输入密码')));
            (check_pwd($data['password']) === false) && exit(json_encode(array(0,'密码格式错误')));
            $info = M('Member')->where(array('mobile'=>$data['mobile']))->find();
            empty($info) && exit(json_encode(array(0,'手机号不存在')));
            empty($info['status']) && exit(json_encode(array(0,'手机号被禁止登录')));
            (st_md5($data['password']) != $info['password']) && exit(json_encode(array(0,'密码错误')));
            session('user_auth',array(
                'id' => $info['id'],
                'mobile' => $info['mobile']
            ));
            cookie('user_auth',array(
                'id' => $info['id'],
                'mobile' => $info['mobile']
            ));
            exit(json_encode(array(1,'登录成功')));
        }else{
            //动态关闭表单令牌
            C('TOKEN_ON',false);
            $this->display();
        }
    }

    //忘记密码
    public function forgot_pwd0(){
        if(IS_POST){
            $data = I('post.');
            empty($data['mobile']) && exit(json_encode(array(0,'请输入手机号')));
            (check_mobile($data['mobile']) === false) && exit(json_encode(array(0,'手机号格式错误')));
            empty($data['code']) && exit(json_encode(array(0,'请输入验证码')));
            empty($data['password']) && exit(json_encode(array(0,'请输入密码')));
            (check_pwd($data['password']) === false) && exit(json_encode(array(0,'密码格式错误')));
            ($data['password'] != $data['confirm_password']) && exit(json_encode(array(0,'两次密码输入不一致')));
            $pwd_data = cookie('pwd_data');
            ($pwd_data['mobile'] != $data['mobile']) && exit(json_encode(array(0,'手机号更改或验证码已过期')));
            ($pwd_data['code'] != $data['code']) && exit(json_encode(array(0,'验证码错误或已过期')));

            $info = M('Member')->where(array('mobile'=>$data['mobile'],'status'=>1))->find();
            empty($info) && exit(json_encode(array(0,'该手机号被禁止登录')));

            $pwd = st_md5($data['password']);
            if($pwd == $info['password']) {
                cookie('pwd_data',null);
                exit(json_encode(array(1,'修改成功')));
            }elseif(M('Member')->where(array('mobile'=>$data['mobile']))->setfield('password',$pwd)){
                cookie('pwd_data',null);
                exit(json_encode(array(1,'修改成功')));
            }else{
                exit(json_encode(array(0,'操作失败，请重试')));
            }
        }else{
            //动态关闭表单令牌
            C('TOKEN_ON',false);
            $this->display('password');
        }
    }



    //注册获取验证码 wj
    public function pwd_send_sms(){
        $mobile = I('mobile','','trim');
        empty($mobile) && exit(json_encode(array(0,'请输入手机号')));
        (check_mobile($mobile) === false) && exit(json_encode(array(0,'手机号格式错误')));
        $only = M('Member')->where(array('mobile'=>$mobile,'status'=>1))->count();
        empty($only) && exit(json_encode(array(0,'该手机号不存在或被禁止登录')));
        $code = rand(100000,999999);
        $result = $this->sendSms($mobile,'1904612',('#code#').'='.urlencode((string)$code));
        if($result['code'] === 0){
            cookie('pwd_data',array('mobile'=>$mobile,'code'=>$code),600);
            exit(json_encode(array(1,'短信已发送')));
        }else{
            exit(json_encode(array(0,'发送失败，请重试')));
        }
    }

    //验证是否登录
    public function pc_is_login(){
        $user_auth = session('user_auth');
        if($user_auth){
            echo 1;
        }else{
            echo 0;
        }
    }

    //退出登录
    public function sign_out(){
        session('user_auth',null);
        cookie('user_auth',null);
        $this->redirect('Index/index');
    }
	
	    /**
     * 获取紧接着的下一级分类ID
     */
    public function ajax_getPlace() {
        $id = I('id',0, 'intval');
        $type = I('type', 0, 'intval');
        $map = array('pid'=>$id);
        if (!empty($type)) {
            $map['type'] = $type;
        }
		
        $return = M('Place')->field('id,name')->where($map)->select();
        if ($return) {
            $this->ajax_return(1, '', $return);
        } else {
            $this->ajax_return(0, '');
        }
    }
	
	    //获取课程分类
    public function get_curriculum(){
        $id = I('id','','intval');
        empty($id) && exit(json_encode(array(0,'')));
        $list = M('CurriculumCate')->where(array('pid'=>$id,'status'=>1))->field('id,name')->order('ordid,id desc')->select();
        if($list){
            exit(json_encode(array(1,$list)));
        }else{
            exit(json_encode(array(0,'')));
        }
    }
    //订单分类联动
    public function ajax_getchilds() {
        $id = I('id','', 'intval');
        $return = D('CurriculumCate')->field('id,name')->where(array('pid'=>$id))->select();
        if ($return) {
            $this->ajax_return(1, L('operation_success'), $return);
        } else {
            $this->ajax_return(0, L('operation_failure'));
        }
    }

    public function teacher(){
        $this->display();
    }

}