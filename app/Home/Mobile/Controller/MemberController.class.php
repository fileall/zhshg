<?php

namespace Mobile\Controller;

use Admin\Org\Image;

class MemberController extends HomeController {

	public $APPSECRET = 'fd78a530db36628ad8b3e48348d90231';

    public $APPID     = 'wx5bb60c27fa07f4ca';

    public function _initialize() {

        parent::_initialize();

        $member = D('member')->find(is_login());

        $this->assign('member', $member);
		$uid= is_login();
        if(!is_login()){
//			echo "<script>alert('您还未登录,请先登录!');location.href='".U('Login/enter')."';</script>";
			$this->redirect('Login/enter');

		}
//		if(!($uid == 325 ||$uid == 532 || $uid == 1|| $uid == 306)){
//
//			echo "<script>alert('店铺系统升级中!');location.href='".U('index/index')."';</script>";
//
//		}


    }

  

    

    //个人中心

    public function mine(){
    
//     	$data['ewm'] =  $this->set_qrcode("http://".$_SERVER['HTTP_HOST'].U('member/w_shaped_pay',array('tel'=>18070281118)));
//  	var_dump($_SERVER['HTTP_HOST']);die;

    	$mem = D('Member')->find(is_login());

		$mem['gold_acer'] = intval($mem['gold_acer']);

		$mem['silver_acer']=intval($mem['silver_acer']);

		$mem['silver_coin']=intval($mem['silver_coin']);

		$mem['gold_coin']=intval($mem['gold_coin']);

		$mem['gold_fruit']=intval($mem['gold_fruit']);

		$gr = D('GradeRule')->select();

		foreach($gr as $k){

			($mem['vips'] == $k['id'])&&$mem['vips']=$k['name'];

		}

    	$this->assign('mem',$mem);
    	
    	#今天财富值统计
    	$time_str = date('Y-m-d', time());//今天0点

		$time_start= strtotime($time_str);//时间戳s

		$time_end= time();//当前时间

		$time_rule['add_time'] =  array('between',array($time_start,$time_end));
		$time_rule['type']=2;
		$time_rule['item_type']=6;
		$time_rule['member_id']=$mem['id'];
		$selive_coin_list = D('MemberRecharge')->where($time_rule)->select();
		
		$selive_coin_day=0;
		
		foreach($selive_coin_list as $k){

			$selive_coin_day = $selive_coin_day + $k['totalprices'];

		}

		$selive_coin_day=intval($selive_coin_day);
		$this->assign('selive_coin_day',$selive_coin_day);
		
		$this->display();

    }

     //个人中心 我是商家

    public function my_merchant(){

    	$mem = D('Member')->find(is_login());
		$merchant=D('Merchant')->where(array('uid'=>$mem['id']))->find();
		
		$money_type=I('get.type');
		//默认显示收益
		if($money_type==2){
			$ye=D('ShopRecharge')->where(array('member_id'=>$merchant['id'],'item_type'=>2))->order('add_time desc,id desc')->select();
		}else{
			$ye=D('ShopRecharge')->where(array('member_id'=>$merchant['id'],'item_type'=>1))->order('add_time desc,id desc')->select();
		}

    	$this->assign('ye',$ye);

    	//今日收益统计
    	$time_start= date('Y-m-d', time());
		$time_start= strtotime($time_start);
		$time_end= time();//时间戳s
    	$time_rule['add_time'] =  array('between',array($time_start,$time_end));
		$time_rule['item_type']=1;//item_type 1商家收益2银币）
		$time_rule['type']=2;//type支出状态 1=出 ，  2=入 
		$time_rule['member_id']=$merchant['id'];
    	$last = M('ShopRecharge')->where($time_rule)->select();
		$prices_today = 0;
		foreach($last as $k){
			$prices_today = $prices_today + $k['totalprices'];
		}
		
		$no_sj=D('Merchant')->where(array('uid'=>$mem['id'],'status'=>2))->find();
		(!$no_sj)&&$this->assign('no_sj',1);
    	$this->assign('prices_today',$prices_today);
    	($merchant)&&$this->assign('merchant',$merchant);
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

    

    //设置个人信息

    public function set_person(){

    	 if(IS_POST){

    	 	$pos = I('post.');

    	 	$member = D('member')->find(is_login());

    	 	//头像

    	 	if($pos['is_avatar']==1){

    	 		$avatar = D('member')->where(array('id'=>$member['id']))->setField('avatar',$pos['avatar']);

    	 		$re=array('msg'=>$avatar?'头像修改成功':'图片上传失败,请重试','status'=>$avatar?1:0);

    	 	}

    	 	//用户名实名需要认证

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

    	 		$re=array(

    	 		'msg'=>$address?'地区修改成功':'新的信息不能和旧信息相同',

    	 		'status'=>$address?1:0,'url'=>$address?U('member/set_person'):'');

    	 	}

    	 	$this->ajaxReturn($re);exit;
    	 	


	        }

	        

           $is_page = I('get.is_page');

           switch($is_page){

    		case 1:

	    		$this->display('name');

	    		break;

    		case 2:

    			$this->display('gender');

	    		break;

    		case 3:

    			$this->display('area');

	    		break;

    		default:
				//获取微信分享必要参数
		
		        $jssdk = new \Mobile\Org\Jssdk(C("WX_CONFIG.appid"), C("WX_CONFIG.appsecret"));
		
		        $js = $jssdk->GetSignPackage();
		
				$this->assign('js',$js);
    			$this->display();

    			break;

        }

    	

    	

    	

    }

    //图片上传*************************************

	public function zed(){

		 if (!empty($_FILES['file']['name'])) {

            $result = $this->_upload($_FILES['file']

	            , 'useravatar'

	            , array('width'=>C('pin_article_cate_img.width'),'height'=>C('pin_article_cate_img.height')));

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

		$media_id = I('media_id', '', 'trim');

		

		$appid = C("WX_CONFIG.appid");

		$appsecret = C("WX_CONFIG.appsecret");

		

		if (!S('wx_access_token')) {

			$res = json_decode(file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$appsecret}"), true);



			$access_token = $res['access_token'];

			if ($access_token) {

	            S('wx_access_token',$access_token,7000);

	    	}

		} else {

	        $access_token = S('wx_access_token'); 

	    }

		

		$uri = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$media_id";



		$res1 = file_get_contents($uri);

		

		$imgname = $media_id.".jpg";  



//		$date_dir = date('ym/d/');

//		$dir1 = "Tem/".$date_dir.$imgname;

		$dir = "data/attachment/useravatar/".$imgname;

		$res2 = file_put_contents($dir, $res1);
//		dump($res2);die;

		if($res2){
//			$url = $dir.$media_id; 

			$this->ajaxReturn(array('sta'=>1,'msg'=>'上传成功','name'=>$imgname));exit;

		}else{

			$this->ajaxReturn(array('sta'=>-1,'msg'=>'上传失败'));exit;
		}

	}
	
	
	//微信多图上传0
//	public function uploads()
//	{
//		$media_id = I('media_id','','trim');
//		 if (!S('wx_access_token')) {
//
//			$res = json_decode(file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->APPID}&secret={$this->APPSECRET}"),true);	
//
//			$access_token = $res['access_token'];
//
//			if ($access_token) {
//
//              S('wx_access_token',$res['access_token'],7000);
//
//	    	}
//
//		} else {
//
//	        $access_token = S('wx_access_token');
//
//	    }
//		$res1 = file_get_contents("http://file.api.weixin.qq.com/cgi-bin/media/get?access_token={$access_token}&media_id={$media_id}");
//		$imgname = $media_id.".jpg";  
//
//		$date_dir = date('ym/d/');
//
//		$dir = "data/attachment/test/".$date_dir;
//
//		if(!file_exists($dir)){
//
//			 mkdir($dir,0777,true);
//
//		}
//
//		$resource = fopen("Tem/".$imgname ,'w+'); 
//
//		$a = fwrite($resource, $res1);
//
//		fclose($resource);
//
//		if($a){
//
//			$url = $dir.$media_id; 
//
//			echo json_encode(array('sta'=>1,'msg'=>'上传成功','name'=>$url));
//
//		}else{
//
//			echo json_encode(array('sta'=>-1,'msg'=>'上传失败'));
//
//		}
//
//		
//
//	}





	

    //多图片上传******************************************

	public function evaluate_ms(){

        if(IS_POST&&IS_AJAX){

        	$pos=I('post.');

            //接受order_sku_id

			$member=M('member')->where(array('id'=>is_login()))->find();

            $ShImg = D('ShImg');

                

            $ShImg->startTrans();

            $data = array(

                'member_id'=>is_login(),  'add_time'=>$_SERVER['REQUEST_TIME'],

                'status'=>1,'withdraw_id'=>$pos['withdraw_id'],

            );

			

			$shijian=$_SERVER['REQUEST_TIME'];

            //判断是否有文件

            $arr = array();$data_img = array();

            if($_FILES['imgs']['name'][0]){

                //循环取出

                foreach($_FILES['imgs']['tmp_name'] as $k=>$v){

                    if(empty($v)) continue;

                    //有图则上传图片

                    if(is_uploaded_file($v)){

                        $arr[$k]['name']=$_FILES['imgs']['name'][$k];

                        $arr[$k]['type']=$_FILES['imgs']['type'][$k];

                        $arr[$k]['tmp_name']=$v;

                        $arr[$k]['error']=0;

                        $arr[$k]['size']=$_FILES['imgs']['size'][$k];

                    }

                }

				

                //循环上传

                foreach($arr as $i =>$c){

                    $dir = date('ym/d');

                    $res = $this->_upload($c, 'pingzheng/'.$dir,  array(

                        'width'=>C('pin_item_simg.width'),'height'=>C('pin_item_simg.height'),'suffix' => '_s',

                    ));

                    if($res['error']){

                        $ret=array('msg'=>$res['info'],'status'=>-4,);

                    }else{

                        $data_img[$i]['add_time'] = $_SERVER['REQUEST_TIME'];

                        $data_img[$i]['status'] = 1;

                        $data_img[$i]['withdraw_id'] = $pos['withdraw_id'];

                        $data_img[$i]['url'] = $dir.'/'.$res['info'][0]['savename'];

                    }

                }

                $db2 = D('ShImg')->addAll($data_img);//上传所有图

                $db2? $ShImg->commit() : $ShImg->rollback();

            }else{

                $db2 ? $ShImg->commit() : $ShImg->rollback();

            }

     	 	$ret=array(

	     	 	'msg'=>$db2 ? '上传成功':'上传失败,请重试',

	     	 	'status'=>$db2 ? 1:-4,

     	 	);

            $this->ajaxReturn($ret);

        }

    }

    //设置实名认证

    public function set_attestation(){

    	if(IS_POST){

//  		echo 1;die;

    		$pos = I('post.');

    		$member= D('Member')->find(is_login());

    		//member_id realname  id_nums  img_one img_two add_time	status

	        $data['member_id'] = $member['id'];

	        $data['realname'] = $pos['realname'];

	        $data['id_nums'] = $pos['id_nums'];

	        $data['add_time'] = time();

	        $data['status'] = 0;//status 0表示未审核 1为驳回 2为通过

	        $data['img_one'] = $pos['img_one'];

	        $data['img_two'] = $pos['img_two'];

			$sm = D('MemberIdcard')->add($data); 

			$re=array(

	     	 	'msg'=>$sm ? '提交审核成功!':'提交审核失败,请重试',

	     	 	'status'=>$sm ? 1:0,

     	 	);

            $this->ajaxReturn($re);exit;

    	} else {

    		$this->assign('is_w',I('get.is_w'));

    		$this->display();

    	}

    }

    //设置账户安全

    public function set_safety(){

    	

    	$this->display();

    	

    }

    //设置账户安全 修改登录密码

    public function set_pw_enter(){

    	if(IS_POST){

    		$oldpw = st_md5(I('post.oldpw'));

			$password=st_md5(I('post.password'));

			$oldpws = M('Member')->where(array('id'=>is_login()))->getField('password');

			if($oldpw != $oldpws){

				$return=array(

					'msg'=>'原密码输入有误!',

					'status'=>0,

				);

				$this->ajaxReturn($return);

				exit;

			}

			if($password == $oldpws){

				$return=array(

					'msg'=>'新密码输入不应与原密码相同!',

					'status'=>0,

				);

				$this->ajaxReturn($return);

				exit;

			}

			

		

			$db = M('Member')->where(array('id'=>is_login()))->setField('password',$password);

			$return=array(

				'msg'=>$db?'修改成功,请重新登录！':'修改失败！',

				'status'=>$db?1:0,

				'url'=>$db?U('Login/login'):''

			);

			cookie('user_auth',null);

			session('user_auth',null);

			$this->ajaxReturn($return);

			exit;

    	}

    	$this->display();

    	

    }

    //设置账户安全 修改支付密码

    public function set_pw_pay(){

    	if(IS_POST){

    		$member= M('member') -> where(array('id'=>is_login())) -> find();

    		$data['mobile'] = I('mobile');

    		if($data['mobile'] != $member['mobile']){

    			exit(json_encode(array('status'=>0,'msg'=>'请输入您本人的手机号码！')));

    		}

    		

//			$code=M('MobileCode')->where(array('mobile'=>$data['mobile']))->order('add_time desc')->getField('code');

			$code = cookie('reg_data');

			if(I('m_code')!=$code['code']){

				$return=array(

					'msg'=>'手机验证码错误！',

					'status'=>0

				);	

			}else{

				$db = M('Member')->where(array('id'=>is_login()))->setField('paypassword',st_md5(I('paypassword')));

				cookie('reg_data',null);

				$return=array(

					'msg'=>$db?'设置成功！':'设置失败！',

					'status'=>$db?1:0,

					'url'=>U('member/set_safety')

				);

			}	

			$this->ajaxReturn($return);

			exit;

		}

		$this->assign('is_w',I('get.is_w'));

    	$this->display();

    	
	
    }

    //个人中心  我要推荐
    public function popularize_link(){
    	$what_ewm = I('get.what_ewm');//1个人2商家（支付）3平台
    	$member = D('Member')->where(array('id'=>is_login()))->find();
//		$tjr_url = "http://".$_SERVER['HTTP_HOST'].U('login/register',array('ewid'=>$ewid));
    	if($what_ewm ==1){
			$ewid=$member['mobile'];
			$ewm=$member['ewm'];
			$name=$member['nickname'];
    	}
    	if($what_ewm ==2){
    		$merchant = D('Merchant')->where(array('uid'=>$member['id'],'status'=>2))->find();
			$ewid=$merchant['tel'];
			$ewm=$merchant['ewm'];
			$name=$merchant['title'];
    	}
    	
		
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

    //个人中心  我的钱包

    public function wallet(){

//  	var_dump(D('GradeRule')->order('id asc')->getField('yybzh_bl',true));

		$mem = D('Member')->find(is_login());

		$mem['prices'] = intval($mem['prices']);

		$mem['gold_acer'] = intval($mem['gold_acer']);

		$mem['silver_acer']=intval($mem['silver_acer']);

		$mem['silver_coin']=intval($mem['silver_coin']);

		$mem['gold_coin']=intval($mem['gold_coin']);

		$mem['gold_fruit']=intval($mem['gold_fruit']);

		

		$this->assign('mem',$mem);

		$this->display();

    }

    

    //我的钱包》我的余额

    public function w_purse(){

    	//余额收支

    	$ye = D('MemberRecharge')->where(array('dingdan'=>0,'member_id'=>is_login(),'item_type'=>4))->order('add_time desc')->select();

    	$this->assign('ye',$ye);

		$this->display();

    }

    

   //我的钱包》我的余额 提现

    public function w_extract(){

    	//银行卡

    	$card = D('MemberBankcard')->where(array('member_id'=>is_login(),'status'=>1))->order('add_time desc,id desc')->select();
    	foreach($card as $k=>$v){
			$card[$k]['nums'] = substr($v['nums'],-4); 
    	}

		$this->assign('card',$card);	
		$this->display();

    }

   

   //我的钱包  我的金元宝

    public function w_ingotA(){

    	//金元宝收支

    	$ye = D('MemberRecharge')->where(array('member_id'=>is_login(),'item_type'=>1,'dingdan'=>0))->order('add_time desc')->select();

    	$this->assign('ye',$ye);

		$this->display();

    }

    

    //我的钱包  我的银元宝

    public function w_ingotB(){
    	//银元宝收支

    	$ye = D('MemberRecharge')->where(array('member_id'=>is_login(),'item_type'=>2,'dingdan'=>0))->order('add_time desc')->select();

    	$this->assign('ye',$ye);

		$this->display();

    }
	//3种支付
    public function w_shaped_pay(){
    	if(IS_POST){
    		#订单处理、流水处理使用check_pay
    		$pos = I('post.');
    		$member=D('Member')->find(is_login());
    		$shop = D('Merchant')->where(array('tel'=>$pos['tel'],'status'=>2))->find();
    		(!$shop)&& exit(json_encode(array('status'=>0,'msg'=>'对方号码输入有误')));
    		//密码判断
    		($member['paypassword'] != st_md5($pos['pw']))&& exit(json_encode(array('status'=>0,'msg'=>'支付密码有误或未设置')));
    		
    		//1金元宝2银元宝3金果
    		$zftype=$pos['zftype'];
    		$no_jinguo=true;
    		if($zftype==1){
    			$str='1';$acer='gold_acer';$memos='金元宝支付';$memos2='金元宝收款';$msg='金元宝余额不足';
	    		$zftype_dingdan=4;//'支付方式 1微信 2.支付宝 3余额 （4金宝 5元宝 6金果）
	    		$item_type =1;//流水$item_type1金元宝 2银元宝 3金果 4余额 （5金币 6银币 
    		}
    		if($zftype==2){
    			$str='2';$acer='silver_acer';$memos='银元宝支付';$memos2='银元宝收款';$msg='银元宝余额不足';
	    		$zftype_dingdan=5;//订单'支付方式 1微信 2.支付宝 3余额 （4金宝 5元宝 6金果）
	    		$item_type =2;//流水$item_type1金元宝 2银元宝 3金果 4余额 （5金币 6银币 
    		}
    		if($zftype==3){
    			$str='3';$acer='gold_fruit';$memos='金果支付';$memos2='金果收款';$msg='金果余额不足';
	    		$zftype_dingdan=6;//订单'支付方式 1微信 2.支付宝 3余额 （4金宝 5元宝 6金果）
	    		$item_type =3;//流水$item_type1金元宝 2银元宝 3金果 4余额 （5金币 6银币
    			$no_jinguo=false;
    		}
    		
			//支付方式判断
			(strpos($shop['zftype'],$str) === false)&& exit(json_encode(array('status'=>0,'msg'=>'该店家不支持此支付方式')));
			//余额判断
    		if($member[$acer] < $pos['prices']){
    			$re =array('status'=>0,'msg'=>$msg);
				$this->ajaxReturn($re);exit;
    		}

    		$data['dingdan']=make_order_sn('order');//生成订单号
            $data['member_id'] = $member['id'];
            $data['skperson']=$shop['title'];
            $data['totalprices'] = $pos['prices']; 
			$data['zftype'] = $zftype_dingdan;//'支付方式 1微信 2.支付宝 3余额 （4金宝 5元宝 6金果）
            $data['memos']=$memos; 
            $data['type'] =1; //支出状态 1=出 ，  2=入 
            $data['item_type'] = 6;//1金元宝 2银元宝 3金果 4余额 5会员卡（15作为充值订单表时）  6线下
			$data['status'] = 2;// 1未付款 2已付款
			$data['add_time']=time();   
			$yd=M('MemberRecharge')->data($data)->add();//生成临时订单	

			//消费者币种减
			$member_jyb = D('Member')->where(array('id'=>$member['id']))->setDec($acer,$pos['prices']);
			//商家收益加
			$shop_jyb =	D('Merchant')->where(array('tel'=>$pos['tel']))->setInc('shouyi',$pos['prices']);
			//商家银币加(有让利前提,查让利规则表)
			$rl_nums=D('RangliRule')->where(array('rangli'=>$shop['rangli']))->getField('rl_nums');
			$rangli_yb = $pos['prices']*$shop['rangli']*$rl_nums/100;
			if($rangli_yb !=0){
				$shop_yinbi =	D('Merchant')->where(array('tel'=>$pos['tel']))->setInc('silver_coin',$rangli_yb);
				all_ls_shop($shop['id'],$rangli_yb,2,2,'商家让利返银币');//商家得平台返银流水
			}
			

			#商家营业返上线银币
			//上线是会员
			//消费金额*让利%
			if($shop['tuijian']){
				$tuijian=D('Member')->find($shop['tuijian']);
				$seller_none_silve=D('GradeRule')->where(array('id'=>$tuijian['vips']))->getField('seller_none_silve');
				$get_silver_coin=$pos['prices']*$shop['rangli']*$seller_none_silve/100;//金额*让利(先转100分比)*vips的倍数
	//			$get_silver_coin=$pos['prices']*$shop['rangli']/100;//金额*让利(先转100分比)
				if($get_silver_coin !=0){
					$is_get_coin = D('Member')->where(array('id'=>$shop['tuijian']))->setInc('silver_coin',$get_silver_coin);//推荐人得银币
					all_ls($shop['tuijian'],$get_silver_coin,6,2,'推荐商家返利');//商家的推荐人银币流水
				}
			}
			
//			if ($shop['tuijian']&&$shop['tj_type']==1) {
//			}
//			//上线是商家
//			if ($shop['tuijian']&&$shop['tj_type']==2) {
//				//消费金额*让利%
//				$get_silver_coin=$pos['prices']*$shop['rangli']/100;//金额*让利(先转100分比)
//				if($get_silver_coin !=0){
//					$is_get_coin = D('Member')->where(array('id'=>$shop['tuijian']))->setInc('silver_coin',$get_silver_coin);//推荐人得银币
//					all_ls_shop($shop['tuijian'],$get_silver_coin,2,2,'推荐商家返利');//商家推荐人银币流水
//				}
//			}
//			$tuijian = D('Member')->find($shop['tuijian']);//商家的推荐人
//			#商家推荐人返银币
//			if($tuijian){
////				$seller_none_silve=D('GradeRule')->where(array('id'=>$tuijian['vips']))->getField('seller_none_silve');
////				$get_silver_coin=$pos['prices']*$shop['rangli']*$seller_none_silve/100;//金额*让利(先转100分比)*vips的倍数
//				$get_silver_coin=$pos['prices']*$shop['rangli']/100;//金额*让利(先转100分比)
//				if($get_silver_coin !=0){
//					$is_get_coin = D('Member')->where(array('id'=>$shop['tuijian']))->setInc('silver_coin',$get_silver_coin);//推荐人得银币
//					all_ls($shop['tuijian'],$get_silver_coin,6,2,'推荐商家返利');//商家推荐人银币流水
//				}
//			}
			
			
			#消费者返银币
			$buy_get=0;
			if(($shop['set_coin'] != 0)&&($no_jinguo)){
				$buy_get = $shop['set_coin']*$pos['prices'];//消费金额*返银倍数
				D('Member')->where(array('id'=>$member['id']))->setInc('silver_coin',$buy_get);//消费者得银币
				all_ls($member['id'],$buy_get,6,2,'消费商家返利');//消费者银币流水
			}
			//流水//人*//数量*//谁的流水//支出状态 //*备注
			if($yd&&$member_jyb&&$shop_jyb){
				all_ls($member['id'],$pos['prices'],$item_type,1,$memos);//消费者金元宝流水

				all_ls_shop($shop['id'],$pos['prices'],1,2,$memos2);//商家收益流水
			}

			$re =array(

				'status'=>$yd&&$member_jyb&&$shop_jyb?1:0,
				'msg'=>$yd&&$member_jyb&&$shop_jyb?'操作成功':'操作失败,请重试',
				'get_coin'=>$yd&&$member_jyb&&$shop_jyb?$buy_get:0,
				'liu_id'=>$yd&&$member_jyb&&$shop_jyb?$yd:0
			);
			$this->ajaxReturn($re);exit;

    	}else{
    		$tel = I('get.tel');
    		$this->assign('tel',$tel);//扫码得到
//  		$zftype = I('get.zftype');
//  		$this->assign('zftype',$zftype);//点击得到
    		
	        //获取微信分享必要参数
	        $jssdk = new \Mobile\Org\Jssdk(C("WX_CONFIG.appid"),C("WX_CONFIG.appsecret"));
	        $js = $jssdk->GetSignPackage();
			$this->assign('js',$js);

			$this->display();

    	}

   }
   //扫码支付、3个币种支付:选择支付类型/处理订单和流水
   public function check_pay(){

   	if(IS_POST){
    		$pos = I('post.');
    		$member=D('Member')->find(is_login());
    		$shop = D('Merchant')->where(array('tel'=>$pos['tel'],'status'=>2))->find();
			$dingdan=D('MemberRecharge')->where(array('dingdan'=>$pos['dingdan']))->find();	
    		if($pos['zftype']==1){
    			$memos='金元宝支付';$acer='gold_acer';$memos2='金元宝收款';
    		}
			if($pos['zftype']==2){
    			$memos='银元宝支付';$acer='silver_acer';$memos2='银元宝收款';
    		}
			if($pos['zftype']==3){
    			$memos='金果支付';$acer='gold_fruit';$memos2='金果收款';
    		}

			//消费者币种减
			$member_jyb = D('Member')->where(array('id'=>$member['id']))->setDec($acer,$pos['prices']);
			//商家收益加
			$shop_jyb =	D('Merchant')->where(array('tel'=>$pos['tel']))->setInc('shouyi',$pos['prices']);
			#返商家银币(有让利前提,查让利规则表)
			$rl_nums=D('RangliRule')->where(array('rangli'=>$shop['rangli']))->getField('rl_nums');
			$rangli_yb = $pos['prices']*$shop['rangli']*$rl_nums/100;
			if($rangli_yb !=0){
				$shop_yinbi =	D('Merchant')->where(array('tel'=>$pos['tel']))->setInc('silver_coin',$rangli_yb);
				all_ls_shop($shop['id'],$rangli_yb,2,2,'商家让利返银币');//商家得平台返银流水
			}
			#商家营业返上线银币
			if($shop['tuijian']){
				$tuijian=D('Member')->find($shop['tuijian']);
				$seller_none_silve=D('GradeRule')->where(array('id'=>$tuijian['vips']))->getField('seller_none_silve');
				$get_silver_coin=$pos['prices']*$shop['rangli']*$seller_none_silve/100;//金额*让利(先转100分比)*vips的倍数
	//			$get_silver_coin=$pos['prices']*$shop['rangli']/100;//金额*让利(先转100分比)
				if($get_silver_coin !=0){
					$is_get_coin = D('Member')->where(array('id'=>$shop['tuijian']))->setInc('silver_coin',$get_silver_coin);//推荐人得银币
					all_ls($shop['tuijian'],$get_silver_coin,6,2,'推荐商家返利');//商家的推荐人银币流水
				}
			}
			
			#消费者返银币
			$buy_get=0;
			if($shop['set_coin'] != 0){
				$buy_get = $shop['set_coin']*$pos['prices'];//消费金额*返银倍数
				D('Member')->where(array('id'=>$member['id']))->setInc('silver_coin',$buy_get);//消费者得银币
				all_ls($member['id'],$buy_get,6,2,'消费商家返利');//消费者银币流水
			}
			//流水//人*//数量*//谁的流水//支出状态 //*备注
			if($member_jyb&&$shop_jyb){
				$dd['status']=2;
				D('MemberRecharge')->where(array('dingdan'=>$pos['dingdan']))->save($dd);	
				
				all_ls($member['id'],$pos['prices'],$pos['zftype'],1,$memos);//消费者币种流水

				all_ls_shop($shop['id'],$pos['prices'],1,2,$memos2);//商家收益流水
			}

			$re =array(
				'status'=>$member_jyb&&$shop_jyb?1:0,
				'msg'=>$member_jyb&&$shop_jyb?'操作成功':'操作失败,请重试',
			);
			$this->ajaxReturn($re);exit;

    }else{   	
// 	$data['ewm'] =  $this->set_qrcode("http://".$_SERVER['HTTP_HOST'].U('member/check_pay',array('tel'=>130)));
// 	$shop=D('Merchant')->where(array('tel'=>130))->save($data);
    	$tel=I('tel');
//  	$tel=130;
    	$shop=D('Merchant')->where(array('tel'=>$tel))->find();

    	$this->assign('tel',$tel);
    	$this->assign('shop',$shop);
  	    $this->display();
    }
    	
   }
   //扫码支付、3个币种支付:生成订单
   public function check_pay_order(){
   		$pos = I('post.');
    		$member=D('Member')->find(is_login());
    		$shop = D('Merchant')->where(array('tel'=>$pos['tel'],'status'=>2))->find();
    		(!$shop)&& exit(json_encode(array('status'=>0,'msg'=>'对方号码输入有误')));
    		//密码判断
    		($member['paypassword'] != st_md5($pos['pw']))&& exit(json_encode(array('status'=>0,'msg'=>'支付密码有误或未设置')));
    		
    		//1金元宝2银元宝3金果
    		$zftype=$pos['zftype'];
    		if($zftype==1){
    			$str='1';$memos='金元宝支付';$msg='金元宝余额不足';$acer='gold_acer';
	    		$zftype_dingdan=4;//订单'支付方式 1微信 2.支付宝 3余额 （4金宝 5元宝 6金果）
    		} 
    		if($zftype==2){
    			$str='2';$memos='银元宝支付';$msg='银元宝余额不足';$acer='silver_acer';
	    		$zftype_dingdan=5;//'支付方式 1微信 2.支付宝 3余额 （4金宝 5元宝 6金果）
    		}
    		if($zftype==3){
    			$str='3';$memos='金果支付';$msg='金果余额不足';$acer='gold_fruit';
	    		$zftype_dingdan=6;//'支付方式 1微信 2.支付宝 3余额 （4金宝 5元宝 6金果）
    		}
    		
			//支付方式判断
			(strpos($shop['zftype'],$str) === false)&& exit(json_encode(array('status'=>0,'msg'=>'该店家不支持此支付方式')));
			//余额判断
    		if($member[$acer] < $pos['prices']){
    			$re =array('status'=>0,'msg'=>$msg);
				$this->ajaxReturn($re);exit;
    		}

    		$data['dingdan']=make_order_sn('order');//生成订单号
            $data['member_id'] = $member['id'];
            $data['skperson']=$shop['title'];
            $data['totalprices'] = $pos['prices']; 
			$data['zftype'] = $zftype_dingdan;//'支付方式 1微信 2.支付宝 3余额 （4金宝 5元宝 6金果）
            $data['memos']=$memos; 
            $data['type'] =1; //支出状态 1=出 ，  2=入 
            $data['item_type'] = 6;//1金元宝 2银元宝 3金果 4余额 5会员卡 6扫码支付
			$data['status'] = 1;// 1未付款 2已付款
			$data['add_time']=time();   
			$yd=M('MemberRecharge')->data($data)->add(); //生成临时订单	
			$re =array(
				'status'=>$yd?1:0,
				'msg'=>$yd?'操作成功':'操作失败,请重试',
				'dingdan'=>$yd?$data['dingdan']:0
			);
			$this->ajaxReturn($re);exit;

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
   
    public function test0(){
//         	$data['ewm'] =  $this->set_qrcode("http://".$_SERVER['HTTP_HOST'].U('member/w_shaped_pay',array('tel'=>6)));
//			var_dump($data['ewm']);die;
		$time_end= date('Y-m-d', time());//25号0点
		$time_end= strtotime($time_end);
		$time_start=date('Y-m-d', $time_end-35*3600);//23号13点
		$time_start= strtotime($time_start);//时间戳s
    	$time_rule['add_time'] =  array('between',array($time_start,$time_end));
		$time_rule['item_type']=1;//1金元宝 2银元宝 3金果 4余额 5会员卡
		$time_rule['status']=2;//付款状态 1未付款，  2=已付款 （作为充值订单表时）
		$time_rule['zftype']=1;//zftype'支付方式  //1微信 2.支付宝 3余额
		$last = M('MemberRecharge')->where($time_rule)->select();
		var_dump($last);
	}
   
    
     //我的钱包 元宝支付
    public function w_shaped_pay0(){
//     	$data['ewm'] =  $this->set_qrcode("http://".$_SERVER['HTTP_HOST'].U('member/w_shaped_pay',array('tel'=>$pos['tel'])));
    	
    	if(IS_POST){
    		$pos = I('post.');
    		$member=D('Member')->find(is_login());
    		$shop = D('Merchant')->where(array('tel'=>$pos['tel'],'status'=>2))->find();
    	
    	
    		(!$shop)&& exit(json_encode(array('status'=>0,'msg'=>'对方号码输入有误')));
    		
			//支付方式判断
			(strpos($shop['zftype'],'金元宝') === false)&& exit(json_encode(array('status'=>0,'msg'=>'该店家不支持此支付方式')));

    		if($member['gold_acer'] < $pos['prices']){
    			$re =array('status'=>0,'msg'=>'余额不足');
				$this->ajaxReturn($re);exit;
    		}

    		$data['dingdan']=make_order_sn('order');//生成订单号
            $data['member_id'] = $member['id'];
            $data['skperson']=$shop['title'];
            $data['totalprices'] = $pos['prices']; 
			$data['zftype'] = 4;//'支付方式 1微信 2.支付宝 3余额 （4金宝 5元宝 6金果）
            $data['memos']='金元宝支付'; 
            $data['type'] =1; //支出状态 1=出 ，  2=入 
            $data['item_type'] = 6;//1金元宝 2银元宝 3金果 4余额 5会员卡（15作为充值订单表时）  6线下
			$data['status'] = 2;// 1未付款 2已付款
			$data['add_time']=time();   
			$yd=M('MemberRecharge')->data($data)->add();//生成临时订单	

			//己方金元宝减。对方收益加
			$member_jyb = D('Member')->where(array('id'=>$member['id']))->setDec('gold_acer',$pos['prices']);
			$shop_jyb =	D('Merchant')->where(array('tel'=>$pos['tel']))->setInc('shouyi',$pos['prices']);
			$tuijian = D('Member')->find($shop['tuijian']);//商家的推荐人
			if($tuijian){
				$seller_none_silve=D('GradeRule')->where(array('id'=>$tuijian['vips']))->getField('seller_none_silve');
				$get_silver_coin=$pos['prices']*$shop['rangli']*$seller_none_silve;//金额*让利*倍数
				if($get_silver_coin !=0){
					$is_get_coin = D('Member')->where(array('id'=>$shop['tuijian']))->setInc('silver_coin',$pos['prices']);//推荐人得银币
					all_ls($shop['tuijian'],$get_silver_coin,6,2,'推荐商家返利');//商家推荐人银币流水
				}
			}
			//流水//人*//数量*//谁的流水//支出状态 //*备注
			if($yd&&$member_jyb&&$shop_jyb){
				all_ls($member['id'],$pos['prices'],1,1,'金元宝支付');//消费者金元宝流水

				all_ls($shop['uid'],$pos['prices'],7,2,'金元宝支付收款');//商家主人收益流水
				
			}

			$re =array(

				'status'=>$yd&&$member_jyb&&$shop_jyb?1:0,

				'msg'=>$yd&&$member_jyb&&$shop_jyb?'操作成功':'操作失败,请重试',

			);

			$this->ajaxReturn($re);exit;

    	}else{
    		$tel = I('get.tel');
    		$this->assign('tel',$tel);
    		
	        //获取微信分享必要参数
	        $jssdk = new \Mobile\Org\Jssdk(C("WX_CONFIG.appid"),C("WX_CONFIG.appsecret"));
	        $js = $jssdk->GetSignPackage();
			$this->assign('js',$js);

			$this->display();

    	}

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

			$jyb2ye['gold_fruit']=$member['gold_acer']+$pos['prices']*0.2;//金果加

			$jyb2ye['prices']=$member['prices']+$pos['prices']*0.7;//余额加

			$zhuan = M('Member')->where(array('id'=>$member['id']))->save($jyb2ye);

			//流水//人*//数量*//谁的流水//支出状态 //*备注

			if($yd&&$zhuan){

				all_ls($member['id'],$pos['prices'],1,1,'金元宝转余额');

				all_ls($member['id'],$pos['prices']*0.2,3,2,'金元宝转余额');

				all_ls($member['id'],$pos['prices']*0.7,4,2,'金元宝转余额');

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

    

	

	//购买金元宝 余额

	public function sealing_ye(){ 

		$pos=I('post.');

		$mem = D('Member')->find(is_login());

//		dump($pos);die;

		if(!$mem['paypassword']) {

			$re =array(

				'status'=>0,

				'msg'=>'您还未设置支付密码'

			);

			$this->ajaxReturn($re);exit;

		}

		if(st_md5($pos['pas']) != $mem['paypassword']) {

			$re =array(

				'status'=>0,

				'msg'=>'支付密码有误'

			);

			$this->ajaxReturn($re);exit;

		}

		if( $mem['prices'] < $pos['prices']) {

			$re =array(

				'status'=>0,

				'msg'=>'余额不足'

			);

			$this->ajaxReturn($re);exit;

		}

		

			//订单号' 
			$sn_no = $pos['dingdan']; 
			$order = D('MemberRecharge');
			$info = $order->where(array('dingdan'=>$pos['dingdan']))->find();
	        if($info['status'] == 1) {
				//更改订单状态**
	            $order->where(array('id'=>$info['id']))->save(array(
	                'status' => 2,
	                'pay_time' => time(),
	                'zftype' => 3
	            ));

	            $member = D('Member')->find($info['member_id']);

	            //购买金元宝送银币。可调倍数。银币silver_coin
	            $k= D('GradeRule')->order('id asc')->getField('reward_silver_multiple',true);
	//          foreach($reward_silver as $k=>$v){
	//          	($member['vips'] == $k )&&M('Member')->where(array('id'=>$info['uid']))->setInc('silver_coin', 100*$reward_silver[$k]);//银币
	//          }
				($member['vips'] == 1 )&&$yb = $info['totalprices'] * $k[0];

				($member['vips'] == 2 )&&$yb = $info['totalprices'] * $k[1];

				($member['vips'] == 3 )&&$yb = $info['totalprices'] * $k[2];

	            M('Member')->where(array('id'=>$info['member_id']))->setInc('silver_coin', $yb);//银币

	            //银币流水

	            yb_ls($member['id'],$yb,2,'余额充值元宝');//人*//数量*//支出状态 //*备注

	        

	            //vips购买金云宝部分转换银元宝。可调比例。金云包gold_acer银元宝silver_acer

	            $bl= D('GradeRule')->order('id asc')->getField('yybzh_bl',true);

	            $jyb=$info['totalprices'];


				
				
				//上线是会员
				if ($member['relation_id']) {
					$member_model = D('Member');    
					#查找推荐人
					$relation_member = $member_model->where(array('id'=>$member['relation_id']))->field('id,vips')->find();  
					#推荐人赠送
					$relation_bl = D('GradeRule')->where(array('id'=>$relation_member['vips']))->field('id,upgrade_one_price,upgrade_two_price,tj_acer_silver')->find();
					#赠送数量
					$silver_coin_num = $jyb * $relation_bl['tj_acer_silver'];

					$member_model->where(array('id'=>$member['relation_id']))->setInc('silver_coin', $silver_coin_num);
					//银币流水
          		    ($silver_coin_num != 0)&&yb_ls($member['relation_id'],$silver_coin_num,2,'下线充值元宝');//人*//数量*//支出状态 //*备注
				}  
				
				
//				if ($member['relation_id']&&$member['tj_type']==1) {
//				}
//				//上线是商户
//				if ($member['relation_id']&&$member['tj_type']==2) {
//					$member_model = D('Member');    
//				    #查找推荐商户状态
//					$relation_about = D('Merchant')->where(array('id'=>$member['relation_id']))->field('id,status,tel')->find();  ;  
//					//商户不处于通过状态，奖励给会员身份
//					if($relation_about['status']!=2){
//						#推荐人电话重新查找会员表可能的推荐人
//						$relation_member = $member_model->where(array('mobile'=>$relation_about['tel']))->field('id,vips')->find();  
//						if($relation_member){
//							#推荐人赠送
//							$relation_bl = D('GradeRule')->where(array('id'=>$relation_member['vips']))->field('id,upgrade_one_price,upgrade_two_price,tj_acer_silver')->find();
//							#赠送数量
//							$silver_coin_num = $jyb * $relation_bl['tj_acer_silver'];
//							$member_model->where(array('id'=>$relation_member['id']))->setInc('silver_coin', $silver_coin_num);
//							//银币流水
//		          		    ($silver_coin_num != 0)&&yb_ls($relation_member['id'],$silver_coin_num,2,'下线充值元宝');//人*//数量*//支出状态 //*备注
//						}
//						
//					}
//					//商户处于通过状态，奖励给商户
//					else{
//						#推荐人赠送
//						$relation_bl = D('GradeRule')->where(array('id'=>3))->field('id,upgrade_one_price,upgrade_two_price,tj_acer_silver')->find();
//						#赠送数量
//						$silver_coin_num = $jyb * $relation_bl['tj_acer_silver'];
//						D('Merchant')->where(array('id'=>$member['relation_id']))->setInc('silver_coin', $silver_coin_num);
//						//银币流水
//	          		    ($silver_coin_num != 0)&&all_ls_shop($member['relation_id'],$silver_coin_num,2,2,'下线充值元宝');//shop银币流水
//					}
//				}
				
				  
	            if($member['vips'] == 2){
	            	$res = M('Member')->where(array('id'=>$info['member_id']))->setInc('silver_acer', $info['totalprices']*$bl[1]);//银元宝
	      		    $jyb=$info['totalprices'] * (1-$bl[1]);
	            }
	            if($member['vips'] == 3){
	            	$res = M('Member')->where(array('id'=>$info['member_id']))->setInc('silver_acer', $info['totalprices']*$bl[2]);//银元宝
	       			$jyb=$info['totalprices'] * (1-$bl[2]); 
	            }
				

           	    $jyb_ok= M('Member')->where(array('id'=>$info['member_id']))->setInc('gold_acer', $jyb);//金元宝

	            

	            //本人流水表**

	            $data = array();//金宝

	            $datas = array();//银宝

	            //等级1只加金元宝

	            if($member['vips'] == 1){

	            	$data['dingdan']='';

		            $data['member_id'] = $member['id'];

		            $data['skperson']='臻惠生活馆';

		            $data['totalprices'] = $info['totalprices'];

					$data['zftype']=$info['zftype'];//'支付方式  0.未选择 1=微信，2=支付宝',

					$data['memos']='充值金元宝';

					$data['type'] =2; //支出状态 1=出 ，  2=入 

		            $data['item_type']=1;//1金元宝 2银元宝 3金果 4余额 5会员卡（15作为充值订单表时）

					$data['status'] = 2;// 1未付款 2已付款

					$data['add_time']=time(); 

	            } 

	            //等级2、3加金元宝加银元宝

	            if($member['vips'] == 2 || $member['vips'] == 3){

	            	$data['dingdan']='0';$datas['dingdan']='0';

		            $data['member_id'] = $member['id'];$datas['member_id'] = $member['id'];

		            $data['skperson']='臻惠生活馆';$datas['skperson']='臻惠生活馆';

		            $data['totalprices'] = $info['totalprices'] * (1-$bl[1]);

					$data['zftype']=$info['zftype'];$datas['zftype']=$info['zftype'];//'支付方式  0.未选择 1=微信，2=支付宝',

					$data['memos']='充值金元宝';$datas['memos']='充值金元宝';

					$data['type'] =2; $datas['type'] =2;//支出状态 1=出 ，  2=入 

		            $data['item_type']=1;//1金元宝 2银元宝 3金果 4余额 5会员卡（15作为充值订单表时）

					$data['status'] = 2;$datas['status'] = 2;// 1未付款 2已付款

					$data['add_time']=time(); 

					

					$datas['totalprices'] = $info['totalprices']*$bl[1];

					($member['vips'] == 3)&& $datas['totalprices'] = $info['totalprices']*$bl[2];

					$datas['item_type']=2;//1金元宝 2银元宝 3金果 4余额 5会员卡（15作为充值订单表时）

	           		$datas['add_time']=$data['add_time']; 

	            

	            	D('MemberRecharge')->add($datas);//银宝入

	            }

	            $isok = D('MemberRecharge')->add($data);//金宝入

	          

       }

        	if($jyb_ok){
        		$ye_dec_ok = D('Member')->where(array('id'=>$mem['id']))->setDec('prices',$pos['prices']);

        		$member_recharge_model = D('MemberRecharge');

				$recharge['dingdan']='';

	            $recharge['member_id'] = $mem['id']; 

	            $recharge['skperson']='臻惠生活馆';

	            $recharge['totalprices'] =$pos['prices'];//后台扣手续费在实际到账里

				$recharge['zftype'] = 3;//'支付方式  0.未选择 1=微信，2=支付宝',

				$recharge['type'] = 1; //支出状态 1=出 ，  2=入 

	            $recharge['item_type'] = 4;//1金元宝 2银元宝 3金果 4余额 5会员卡（15作为充值订单表时）

				$recharge['memos'] = '金元宝充值';

				$recharge['status'] = 2;// 1未付款 2已付款

				$recharge['add_time'] = time(); 

				($ye_dec_ok)&&$member_recharge_model->add($recharge);

        		

        	}

       		

        

	        $re = array(

				'status'=>$jyb_ok?1:0,

				'msg'=>$jyb_ok?'充值成功':'充值失败',

				'url'=>$jyb_ok?'{:U("member/wallet")}':''

			);

			$this->ajaxReturn($re);exit;

	        

    	}

	//金币

	 public function w_ingotJB(){ 

	 	$ye = D('MemberRecharge')->where(array('member_id'=>is_login(),'item_type'=>5,'dingdan'=>0))->order('add_time desc')->select();

	 	$this->assign('ye',$ye);

	 	$this->display();

	 }  

	 //银币

	 public function w_ingotYB(){ 

	 	$ye = D('MemberRecharge')->where(array('member_id'=>is_login(),'item_type'=>6,'dingdan'=>0))->order('add_time desc')->select();

	 	$this->assign('ye',$ye);

	 	$this->display();

	 } 	

	//金果

	 public function w_jinguo(){

    	$ye = D('MemberRecharge')->where(array('member_id'=>is_login(),'item_type'=>3,'dingdan'=>0))->order('add_time desc')->select();

	 	$this->assign('ye',$ye);

	 	$this->display();

	 }	


	 //金果回购//加余额减金果

    public function recycle(){

    	if(IS_POST){

    		$pos = I('post.');

    		$member = D('Member')->find(is_login());
    		
			if($member['gold_fruit'] < $pos['nums']){
    			$re =array('status'=>0,'msg'=>'余额不足');
				$this->ajaxReturn($re);exit;
    		}


	        $data['dingdan']=make_order_sn('order');//生成订单号

	        $data['member_id'] = $member['id'];

	        $data['skperson']='臻惠生活馆';

	        $data['totalprices'] = $pos['nums'];

			$data['zftype']=0;//'支付方式  0.未选择 1=微信，2=支付宝',

	        $data['memos']='金果回购';

	        $data['type'] =0; //支出状态 1=出 ，  2=入 

	        $data['item_type']=3;//1金元宝 2银元宝 3金果 4余额 5会员卡（15作为充值订单表时）

			$data['status'] = 2;// 1未付款 2已付款

			$data['add_time']=time(); 

			

			$yd=M('MemberRecharge')->data($data)->add();//生成订单	

			//加余额减金果

			$jj['gold_fruit'] = $member['gold_fruit'] -	$pos['nums'];//减金果

			$jj['prices'] = $member['prices'] + ($pos['nums'] * C('pin_jg_scj'));//加余额

			D('Member')->where(array('id'=>$member['id']))->save($jj);

			//生成流水表 加余额减金果

			if($yd){

				$datas['dingdan']=0;//加余额生成流水表

		        $datas['member_id'] = $member['id'];

		        $datas['skperson']='臻惠生活馆';

		        $datas['totalprices'] = $pos['nums'] * C('pin_jg_scj');

				$datas['zftype']=5;//'支付方式  0.未选择 1=微信，2=支付宝 。3金元宝  4银元宝 5金果',

		        $datas['memos']='金果回购';

		        $datas['type'] =2; //支出状态 1=出 ，  2=入 

		        $datas['item_type']=4;//流水单对象类型/订单商品类型 1金元宝 2银元宝 3金果 4余额 5会员卡

				$datas['status'] = 2;// 1未付款 2已付款

				$datas['add_time']=time(); 

				M('MemberRecharge')->add($datas);//加余额流水表

			}

		    $datas['totalprices'] = $pos['nums'];

			$datas['type'] =1; //支出状态 1=出 ，  2=入 

	        $datas['item_type']=3;

        	M('MemberRecharge')->add($datas);//减金果流水表

        	

			$re=array(

				'msg'=>$yd?'操作成功':'操作失败',

				'status'=>$yd?1:0,

				'dingdan'=>$yd?$data['dingdan']:'',//订单号

				'order_id'=>$yd?$yd:'',

				'url'=> U('member/mine')

			);

			$this->ajaxReturn($re);

			exit;

    	}

		

		$this->display();

    }	

	//我的钱包》金果交易//己方减金果  对方加金果

    public function w_golden_transfer(){

//  	var_dump(C('pin_silver_coin'));die;

    	if(IS_POST){

    		$pos = I('post.');

    		$member = D('Member')->find(is_login());

    		$info = M('Member')->where(array('mobile'=>$pos['mobile']))->find();

            empty($info) && exit(json_encode(array('status'=>0,'msg'=>'对方手机号不存在')));

            empty($info['status']) && exit(json_encode(array('status'=>0,'msg'=>'对方手机号被禁止登录')));

    		($member['mobile'] == $pos['mobile']) && exit(json_encode(array('status'=>0,'msg'=>'请确定对方手机号码是否正确')));

	      

	        $data['dingdan']=make_order_sn('order');//生成订单号

	        $data['member_id'] = $member['id'];

	        $data['skperson']=$info['mobile'];

	        $data['totalprices'] = $pos['nums'];

			$data['zftype']=0;//'支付方式  0.未选择 1=微信，2=支付宝',

	        $data['memos']='金果互转';

	        $data['type'] =0; //支出状态 1=出 ，  2=入 

	        $data['item_type']=3;//*1金元宝 2银元宝 3金果 4余额 5会员卡（15作为充值订单表时）

			$data['status'] = 2;// 1未付款 2已付款

			$data['add_time']=time(); 

			

			$yd=M('MemberRecharge')->data($data)->add();//生成订单	

			if($yd){

				$jf['gold_fruit'] = $member['gold_fruit'] -	$pos['nums'] - C('pin_jg_sxf')*$pos['nums'];//己方减金果以及手续费

				$df['gold_fruit'] = $info['gold_fruit'] +	$pos['nums'];//对方加金果

				D('Member')->where(array('id'=>$member['id']))->save($jf);

				D('Member')->where(array('mobile'=>$pos['mobile']))->save($df);

				//己方流水、对方流水

				$data['dingdan']=0;

	            $data['member_id'] = $member['id'];

	            $data['skperson']='臻惠生活馆';

	            $data['totalprices'] =$pos['nums'] + C('pin_jg_sxf')*$pos['nums'];

				$data['type'] =1; //*支出状态 1=出 ，  2=入 

	            $data['item_type']=3;//*1金元宝 2银元宝 3金果 4余额 

				$data['memos']='金果交易';

				$data['add_time']=time(); 

				

				D('MemberRecharge')->add($data);//己方金果出

				$data['member_id'] = $info['id'];

	            $data['totalprices'] =$pos['nums'];

				$data['type'] =2; //*支出状态 1=出 ，  2=入 

				D('MemberRecharge')->add($data);//对方金果入

				

			}

			$re=array(

				'msg'=>$yd?'操作成功':'操作失败',

				'status'=>$yd?1:0,

				'dingdan'=>$yd?$data['dingdan']:'',//订单号

				'order_id'=>$yd?$yd:'',

				'url'=> U('member/wallet')

			);

			$this->ajaxReturn($re);

			exit;

    	}

    	

    	$this->display();

    }

    

    //我的钱包》金果交易明细

    public function w_particulars(){

   

    	$jg_list=M('MemberRecharge')->where(array('member_id'=>is_login(),'item_type'=>3,'status'=>2))->order('add_time desc')->select();

    	$this->assign('jg_list',$jg_list);

		$this->display();

    }

	

	

    //购买金元宝  调微信接口

    public function sealing_pay(){

    	if (IS_POST) { 

    		$price = I('price', 0, 'float');  

    		$item_type = I('post.item_type');//1充值元宝2充值会员

    		$attach = json_encode(array('item_type'=>$item_type));

    		

    		$data['attach'] = $attach;

			$data['body'] = '元宝充值'; 

//	        $data['number'] = time();

	        $data['number'] = I('dingdan');

	        $data['price'] =  $price *100;//单位分
	        if(is_login() ==1){
	       	 $data['price'] =  0.01 *100;//单位分
	        	
	        }


	        $data['openid'] = session('openid'); 

			$config = A('Api/Wxpay')->orderParameter($data);

		 	if ($config['err_code'] != 0) {     

			 	echo json_encode(array('err_code'=>1, 'err_msg'=>$config['err_msg']));

	        } else {

	            echo json_encode(array('err_code'=>0, 'err_msg'=>$config)); 

	        }   

			exit; 

    	} else {

    		$appid = 'wx5c1299e0d98a447e';

    		$appsecret = 'a5e57e74278b59658b0efcb0b5776a8d'; 

        	if (!$code = I('get.code')) {

        		$redirect_uri = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']); 

        		$get_code_uri = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_base&state=STATE&connect_redirect=1#wechat_redirect";

        		header("location: $get_code_uri");  

        	} else {

        		$get_access_token_uri = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";

        		 //初始化curl

				$ch = curl_init();

				//设置超时

				curl_setopt($ch, CURLOPT_TIMEOUT, 20);

				curl_setopt($ch, CURLOPT_URL, $get_access_token_uri);

				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);

				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);

				curl_setopt($ch, CURLOPT_HEADER, FALSE);

				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 

			

				//运行curl，结果以jason形式返回

				$res = curl_exec($ch); 

				curl_close($ch);

				//取出openid

				$data = json_decode($res,true);

				

				$openid = $data['openid'];

				session('openid', $openid);

        	}  

			

			$uid = is_login();

			

			$member = D('Member')->where(array('id'=>$uid))->field('id,vips')->find();

			$rule = D('GradeRule')->where(array('id'=>$member['vips']))->field('reward_silver_multiple,yybzh_bl')->find();

			$num = 100;

			$selive = $num * $rule['reward_silver_multiple'];

			$this->assign('selive',$selive);

			

			$selive_yb = $num * $rule['yybzh_bl'];

			$this->assign('selive_yb',$selive_yb);

			

			$goal = $num - $selive_yb;

			$this->assign('goal',$goal);

			

			

    		$this->display();

    	}

//  	$this->display(); 

    }



    

    //个人中心  我的钱包》银行卡

    public function w_bank(){

    	$b_card = D('MemberBankcard')->where(array('member_id'=>is_login(),'status'=>1))->order('add_time desc,id desc')->select();

    	foreach($b_card as $k=>$v){

    		 $b_card[$k]['nums'] = substr($v['nums'], 0, 4) . '********' . substr($v['nums'], -4); 

    	}

    	$this->assign('b_card',$b_card);

		$this->display();

    }

    //我的钱包》银行卡》添加银行卡

    public function add_bank(){

    	$is_tx = I('is_tx');

    	$this->assign('is_tx',$is_tx);

    	if(IS_POST){

    		$data = D('MemberBankcard')->create();

    		$data['member_id'] = is_login();

    		$data['add_time'] = time();

			$add_card = D('MemberBankcard')->add($data);

			$re=array(

				'status'=>$add_card?1:0,

				'msg'=>$add_card?'添加成功!':'添加失败!',

				'url'=>$add_card? U('member/w_bank'):'',

				'is_tx'=>($is_tx==1)?1:0,

			);

			$this->ajaxReturn($re);

			exit;

    	}

    	

		$this->display();

    }

    //我的钱包》银行卡》解绑银行卡

    public function del_bank(){

    	if(IS_POST){

    		$data['id'] = I('post.id');

			$card = D('MemberBankcard')->where($data)->save(array('status'=>0));

			$re=array(

				'status'=>$card?1:0,

				'msg'=>$card?'解绑成功!':'解绑失败!',

				'url'=>$card? U('member/w_bank'):''

			);

			$this->ajaxReturn($re);

			exit;

    	}

    	

		$this->display();

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

    

    //我的钱包》聚宝盆订单记录//展示和提取

     public function w_village_number(){

		if(IS_POST){
			$pos = I('post.');
			$member=D('Member')->find(is_login());
			$jbp = D('MemberJbp')->find($pos['id']);//找到要提取的订单
			if(($jbp['zftype']==1)&&($jbp['totalprices'] > $member['gold_acer_jc'])){
				$re=array(
					'status'=>0,
					'msg'=>'操作异常,您的聚宝盆金元宝数量不足!',
				);
				$this->ajaxReturn($re);exit; 
			}
			if(($jbp['zftype']==2)&&($jbp['totalprices'] > $member['silver_acer_jc'])){
				$re=array(
					'status'=>0,
					'msg'=>'操作异常,您的聚宝盆银元宝数量不足!',
				);
				$this->ajaxReturn($re);exit; 
			}
			
			$tq = D('MemberJbp')->where(array('id'=>$pos['id']))->save(array('status'=>3));//改订单状态
			if($tq){
				$acer=array();
				if($jbp['zftype']==1){

					$acer['gold_acer']=$member['gold_acer']+$jbp['totalprices'];

					$acer['gold_acer_jc']=$member['gold_acer_jc']-$jbp['totalprices'];
				}
				if($jbp['zftype']==2){
					$acer['silver_acer']=$member['silver_acer']+$jbp['totalprices'];
					$acer['silver_acer_jc']=$member['silver_acer_jc']-$jbp['totalprices'];
				}

				//用户表加元宝减寄存的元宝
			    D('Member')->where(array('id'=>$member['id']))->save($acer);
				//元宝流水单
				$data['dingdan']=0;
		        $data['member_id'] = $member['id'];
		        $data['skperson']='臻惠生活馆';
		        $data['totalprices'] = $jbp['totalprices'];
				$data['zftype']=1;//'支付方式
		        ($jbp['zftype']==1)&&$data['memos']='聚宝盆提取金元宝';
		        ($jbp['zftype']==2)&&$data['memos']='聚宝盆提取银元宝';
		        $data['type'] =2; //*支出状态 1=出 ，  2=入 
		        ($jbp['zftype']==1)&&$data['item_type']=1;//*流水对象1金元宝 2银元宝 3金果 4余额
		        ($jbp['zftype']==2)&&$data['item_type']=2;//*流水对象1金元宝 2银元宝 3金果 4余额
				$data['status'] = 1;
				$data['add_time']=time(); 

				$jyb=M('MemberRecharge')->add($data);//生成金元宝流水单	
			}
			$re=array(
				'status'=>$tq?1:0,
				'msg'=>$tq?'操作成功!':'操作失败!',
			);

			$this->ajaxReturn($re);exit; 

		}
		else{

			//   	 $time1= strtotime('2017-10-19 23:00:00');//时间戳s

			//		 $start = strtotime(date('Y-m-d', $time1));//开始时间

			//		 $end = $start+90*3600*24;//预计结束时间

			//		 $today = strtotime(date('Y-m-d', time())) ;//今天0点

			//		 $aaa = ($end - $today)/(24*3600);

			//		 var_dump($aaa);

			//		 die;

			$data['member_id']=is_login();

	     	$data['status']=array('in',array(1,2));

	     	//判断聚宝盆可提现状态

			$jbp = D('MemberJbp')->where($data)->order('add_time desc')->select();

		 	$today = strtotime(date('Y-m-d', time())) ;//今天0点

			$tian = C('pin_jbp_zq');
			foreach($jbp as $k=>$v){

				$start = strtotime(date('Y-m-d', $v['add_time']));//开始时间

				$jbp[$k]['other_days'] = ($start+$tian*3600*24-$today)/(24*3600);

			}

			//可提取立即修改状态

			foreach($jbp as $k=>$v){

				if($v['other_days'] == 0){

					 D('MemberJbp')->where(array('id'=>$v['id']))->save(array('status'=>2));

				}

			}

	     	$this->assign('jbp',$jbp);

			$this->display();

		}	

     	

     	

     }





 	//我的钱包》聚宝盆:寄存金元宝、银元宝

     public function w_village(){

     	$uid = is_login();

		if(IS_POST){ 

			$member=D('member')->find($uid);

			$pos = I('post.'); 

			$pos['nums_jyb']=abs($pos['nums_jyb']);//绝对值

			

			//寄存元宝加、元宝减//加银币

			$acer=array();

			$a=0;

			if(!empty($pos['nums_jyb'])){

				$acer['gold_acer_jc']=$member['gold_acer_jc']+$pos['nums_jyb'];

				$acer['gold_acer']=$member['gold_acer']-$pos['nums_jyb'];

				

				$a=$pos['nums_jyb']*C('pin_jbp_bs');

			}

			if(!empty($pos['nums_yyb'])){

				$acer['silver_acer_jc']=$member['silver_acer_jc']+$pos['nums_yyb'];

				$acer['silver_acer']=$member['silver_acer']-$pos['nums_yyb'];

				$a = $pos['nums_yyb']*C('pin_jbp_bs') + $a;

			}

			

			$acer['silver_coin'] = $member['silver_coin'] + $a;

			$mem = D('member')->where(array('id'=>$uid))->save($acer);//银币

			//银币流水

			yb_ls($member['id'],$a,2,'聚宝盆');//人*//数量*//支出状态 //*备注

				

			//元宝流水单》金元宝

			if(!empty($pos['nums_jyb'])){ 

				$data['dingdan']=0;

		        $data['member_id'] = $member['id'];

		        $data['skperson']='臻惠生活馆';

		        $data['totalprices'] = $pos['nums_jyb'];

				$data['zftype']=1;//'支付方式

		        $data['memos']='聚宝盆存金元宝';

		        $data['type'] =1; //*支出状态 1=出 ，  2=入 

		        $data['item_type']=1;//*流水对象1金元宝 2银元宝 3金果 4余额

				$data['status'] = 1;

				$data['add_time']=time(); 

					

				$jyb=M('MemberRecharge')->add($data);//生成金元宝流水单	

				

			}

			

			//元宝流水单》银元宝

			if(!empty($pos['nums_yyb'])){

				$datas['dingdan']=0;

		        $datas['member_id'] = $member['id'];

		        $datas['skperson']='臻惠生活馆';

				$datas['totalprices'] = $pos['nums_yyb'];

				$datas['zftype']=1;//'支付方式

		        $datas['memos']='聚宝盆存银元宝';

		        $datas['type'] =1; //*支出状态 1=出 ，  2=入 

		        $datas['item_type']=2;//*流水对象1金元宝 2银元宝 3金果 4余额

				$datas['status'] = 1;

				$datas['add_time']=time();

				

   			    $yyb=M('MemberRecharge')->add($datas);//生成银元宝流水单	

			}

			$re=array(

				'status'=>($jyb | $yyb)?1:0,

				'msg'=>($jyb | $yyb)?'操作成功!':'操作失败!',

				'url'=>($jyb | $yyb)?U('member/w_basin'):'',

			);

			$this->ajaxReturn($re);exit; 

		}

		

    	$this->display();

     }

     

     //我的钱包》聚宝盆:寄存金元宝、银元宝ajax订单

     public function w_village_ajax(){

     	//聚宝盆记录(MemberJbp订单)

     	if(IS_POST){

     		$member=D('member')->find(is_login());

			$pos = I('post.');

			$jbp_bs=C('pin_jbp_bs');

			

//			var_dump(empty($pos['nums_jyb']));die;

			$data=array();$datas=array();

			if(!empty($pos['nums_jyb'])){

				if($pos['nums_jyb'] > $member['gold_acer']){

					$re=array(

					'status'=>0,

					'msg'=>'操作失败!您的金元宝余额不足',

					);

					$this->ajaxReturn($re);exit;

				}

				$data['dingdan']=make_order_sn('order');//生成订单号
		        $data['member_id'] = $member['id'];
		        $data['skperson']='臻惠生活馆';
		        $data['totalprices'] = $pos['nums_jyb'];
				$data['zftype']=1;//'支付方式zftype'1金宝2银宝
		        $data['memos']='聚宝盆存金元宝';
				$data['status'] = 1;//1寄存中/2未提取/3已提取
				$data['add_time']=time(); 
				$jyb=M('MemberJbp')->add($data);//生成金元宝聚宝盆订单	

// 			    ($jyb)&&D('member')->where(array('id'=>$member['id']))->setInc('silver_coin',$jyb['totalprices']*$jbp_bs);//加银币

			}

			if(!empty($pos['nums_yyb'])){

				if($pos['nums_yyb'] > $member['silver_acer']){

					$re=array(

					'status'=>0,

					'msg'=>'操作失败!您的银元宝余额不足',

					);

					$this->ajaxReturn($re);exit;

				}

				

				

				$datas['dingdan']=make_order_sn('order');//生成订单号

		        $datas['member_id'] = $member['id'];

		        $datas['skperson']='臻惠生活馆';

		        $datas['totalprices'] = $pos['nums_yyb'];

				$datas['zftype']=2;//'支付方式zftype'1金宝2银宝

		        $datas['memos']='聚宝盆存银元宝';

				$datas['status'] = 1;//1寄存中/2未提取/3已提取

				$datas['add_time']=time(); 

				

   			    $yyb=M('MemberJbp')->add($datas);//生成银元宝聚宝盆订单	

			}

				

//			if($jyb | $yyb){

//				$pos['nums_jyb']=abs($pos['nums_jyb']);//绝对值

//				//寄存元宝加、元宝减//加银币

//				$acer=array();

//				$a=0;

//				if(!empty($pos['nums_jyb'])){

//					$acer['gold_acer_jc']=$member['gold_acer_jc']+$pos['nums_jyb'];

//					$acer['gold_acer']=$member['gold_acer']-$pos['nums_jyb'];

//					

//					$a=$pos['nums_jyb']*C('pin_jbp_bs');

//				}

//				if(!empty($pos['nums_yyb'])){

//					$acer['silver_acer_jc']=$member['silver_acer_jc']+$pos['nums_yyb'];

//					$acer['silver_acer']=$member['silver_acer']-$pos['nums_yyb'];

//					$a = $pos['nums_yyb']*C('pin_jbp_bs') + $a;

//				}

//				

//				$acer['silver_coin'] = $member['silver_coin'] + $a;

//				$mem = D('member')->where(array('id'=>$uid))->save($acer);//银币

//				

//			}	



			$re=array(

				'status'=>($jyb | $yyb)?1:0,

				'msg'=>($jyb | $yyb)?'操作成功!':'操作失败!',

				'url'=>($jyb | $yyb)?U('member/w_village'):'',

				'nums_jyb'=>$pos['nums_jyb'],

				'nums_yyb'=>$pos['nums_yyb'],

			);

			

			$this->ajaxReturn($re);exit;

    	}

    	$this->display();

    }

     

     

     

    

    //我的钱包  收支明细(余额)

    public function w_record(){

//  	$pay_type['pay_type'] = 5;//商品类型 金果转换

//  	$status['status'] = array('in',array(2,3,4,5));

//  	$order = D('Order')->where(array('member_id'=>is_login(),$status,$pay_type))->Field('pay_time,add_time,totalprices,item_type')->order('id desc')->select();

    	$ye = D('MemberRecharge')->where(array('member_id'=>is_login(),'item_type'=>4))->order('add_time desc')->select();

    	

    	$this->assign('ye',$ye);	

		$this->display();

    }

    //我的钱包》线下转账
    public function test(){
		$pt_card = D('BankCard')->select();//平台银行卡

    	$appid = 'wx5c1299e0d98a447e';

    	

    	$this->assign('pt_card',$pt_card);

    	$this->assign('appid',$appid);

    	$jssdk = new \Mobile\Org\Jssdk(C("WX_CONFIG.appid"),C("WX_CONFIG.appsecret"));

    	

        $js = $jssdk->GetSignPackage();

		$this->assign('js',$js);

    	$this->display();
	}

    //我的钱包》线下转账
    public function w_transfer(){

    	$pt_card = D('BankCard')->select();//平台银行卡

    	$appid = 'wx5c1299e0d98a447e';

    	$this->assign('pt_card',$pt_card);

    	$this->assign('appid',$appid);

    	$jssdk = new \Mobile\Org\Jssdk(C("WX_CONFIG.appid"),C("WX_CONFIG.appsecret"));

    	

        $js = $jssdk->GetSignPackage();

		$this->assign('js',$js);

    	$this->display();

    }

    //我的钱包》线下转账记录

     public function w_transfer_record(){

		$zz_list = D('Withdraw')->where(array('type'=>2,'member_id'=>is_login()))->order('create_time desc')->select();

    	foreach($zz_list as $k=>$v){

    		$zz_list[$k]['img']= D('ShImg')->where(array('withdraw_id'=>$v['id']))->getField('img');

    	}

    	$this->assign('zz_list',$zz_list);

    	$this->display();

    }

    

   

     //余额提现withdraw  type1

     public function withdraw_order_make(){

			$pos = I('post.');

    		$member = D('Member')->find(is_login());

    		

    		if($member['paypassword'] != st_md5($pos['pw'])){

    			$re=array(

					'msg'=>'支付密码有误或未设置!',

					'status'=>0,

				);

				$this->ajaxReturn($re);exit;

    		}

    		if($member['prices'] < $pos['prices']){

    			$re=array(	

					'msg'=>'抱歉,您的余额不足!',

					'status'=>0,

				);

				$this->ajaxReturn($re);exit;

    		}

    		

    		$meibi = C('pin_tx_db_je')*10000;

    		if($pos['prices'] > $meibi){

    			$re=array(	

					'msg'=>'抱歉,单笔最高提现金额为:'.$meibi.'!',

					'status'=>0,

				);

				$this->ajaxReturn($re);exit;

    		}

//			//验证提现时间是否在3天内
			$time_three['member_id'] = is_login();
    		$time_three['type']=1;
			$last_day = M('Withdraw')->where($time_three)->field('max(create_time)')->find();//上一单时间
			if(time()-$last_day['max(create_time)']-24*3600*3 <0){
				$re=array('msg'=>'提现周期为每三天一次!','status'=>0);
				$this->ajaxReturn($re);exit;
			}
    		//验证每日提现金额上限

    		$time_str = date('Y-m-d', time());//今天0点

			$time_start= strtotime($time_str);//时间戳s

			$time_end= $time_start+24*3600;

    		$time_rule['create_time'] =  array('between',array($time_start,$time_end));

    		$time_rule['member_id'] = is_login();

    		$time_rule['type']=1;

    		$time_rule['status']=array('in',array(0,2));

    		$last = M('Withdraw')->where($time_rule)->select();

    		$prices_today = $pos['prices'];

    		foreach($last as $k){

    			$prices_today = $prices_today + $k['amount'];

    		}

    		$sx = C('pin_tx_mr_je')*10000;

    		if( $prices_today >$sx){

    			$re=array(	

					'msg'=>'抱歉,每日最高提现金额为:'.$sx.'!',

					'status'=>0,

				);

				$this->ajaxReturn($re);exit;

    		}

    	

    	

    		$card = D('MemberBankcard')->find($pos['card_id']);//银行卡id

//  		D('MemberBankcard')->where(array('member_id'=>$member['id']))->setField('status',0);

//  		D('MemberBankcard')->where(array('member_id'=>$member['id'],'id'=>$card['id']))->setField('status',1);//默认

    		

			$data['order_no'] = make_order_sn('order');//生成订单号

			$data['member_id']=$member['id'];

			$data['branch']=$card['nums'];

			$data['branch_name']=$card['name'];

			$data['branch_title']=$card['title'];

			$data['amount']=$pos['prices'];//用户提现金额,不含手续费(后台计算手续费)

			$data['create_time']=time();

			$data['status']=0;//0表示未审核 1为驳回 2为通过

			$data['type']=1;// 1余额提现 2线下转账 3金币转余额 4金果转换

			$data['bankcard_id']=$pos['card_id'];//银行卡id



			$yd=M('Withdraw')->data($data)->add();	//生成临时订单	

			($yd)&&D('Member')->where(array('id'=>$member['id']))->setDec('prices',$pos['prices']);//扣用户余额	

			//余额收支情况

			if($yd){

				$datas['dingdan']= 0;

	            $datas['member_id'] = $member['id'];

	            $datas['skperson']='臻惠生活馆';

	            $datas['totalprices'] =$pos['prices'];//后台扣手续费在实际到账里

				$datas['zftype']= 0;//'支付方式  0.未选择 1=微信，2=支付宝',

				$datas['type'] =1; //支出状态 1=出 ，  2=入 

	            $datas['item_type']=4;//1金元宝 2银元宝 3金果 4余额 5会员卡（15作为充值订单表时）

				$datas['memos']='余额提现';

				$datas['status'] = 2;// 1未付款 2已付款

				$datas['add_time']=time(); 



//				$res = D('MemberRecharge')->fetchSql(true)->add($datas);//余额出

				$res = D('MemberRecharge')->add($datas);//余额出

			}

			

			$re=array(

				'msg'=>$yd?'订单生成成功':'订单生成失败',

				'status'=>$yd?1:0,

//				'dingdan'=>$yd?$data['order_sn']:'',//订单号

				'order_id'=>$yd?$yd:'',

			);

			

			$this->ajaxReturn($re);

		

		

    	

    }

    

     //线下转账订单withdraw   type 1余额提现 2线下转账 3金币转余额 4金果转换

     public function withdraw_order_make2(){

			$pos = I('post.');

    		$member = D('Member')->find(is_login());

    		

    		$card = D('BankCard')->find($pos['card_id']);//平台银行卡id

			$data['order_no']=make_order_sn('order');//生成订单号

			$data['member_id']=$member['id'];

			

			$data['branch']=$card['nums'];

			$data['branch_name']=$card['name'];

			$data['amount']=$pos['prices'];//金额

			$data['create_time']=time();

			$data['status']=0;//0表示未审核 1为驳回 2为通过

			$data['type']=2;

			$data['remark']='线下转账';//备注

//			$data['bankcard_id']=$pos['card_id'];//平台银行的id

			$data['account_name']=$pos['account_name'];//申请人开户名

			

			$yd=M('Withdraw')->data($data)->add();	//生成临时订单	

			$datas['withdraw_id']=$yd;

			$datas['img']=$pos['img_one'];

			D('ShImg')->add($datas);	

			

			$re=array(

				'msg'=>$yd?'订单生成成功':'订单生成失败',

				'status'=>$yd?1:0,

//				'dingdan'=>$yd?$data['order_sn']:'',//订单号

				'withdraw_id'=>$yd?$yd:'',

			);

			

			$this->ajaxReturn($re);

		

		

    	

    }

    //生成订单order0

    public function order_make(){

    	if(IS_POST){

    		

    		$member = D('Member')->find(is_login());

    		//非普通用户充值金额限制1000

    		if( ($member['vips'] == 2 || $member['vips'] == 3) && (I('post.price') < 1000)){

    			$re=array(

    				'status'=>0,

    				'msg'=>'请充值1000金元宝!',

    			);

    			$this->ajaxReturn($re);

    			exit;

    		}

            $data['comment']='';//

			$data['mobile']=$member['mobile'];

			$data['pay_type']=I('post.zftype');//'支付方式  0.未选择 1=微信，2=支付宝',

			$data['item_type'] = I('post.item_type');//商品类型 1默认商品 2充值

			$data['shperson']='';

			$data['address']='';

			$data['totalprices']=I('post.price');//总价

		

			$data['order_sn']=make_order_sn('order');//生成订单号

			$data['uid'] = is_login();

			$data['add_time']=time(); 

			

			$yd=M('order')->data($data)->add();	//生成临时订单		

			$re=array(

				'msg'=>$yd?'订单提交成功':'订单提交失败',

				'status'=>$yd?1:0,

				'dingdan'=>$yd?$data['order_sn']:'',//订单号

				'jine'=>$yd?$jine:'',

				'je'=>$yd?$je:'',

				'order_id'=>$yd?$yd:''

			);

		}

		$this->ajaxReturn($re);

    	

    }



	//生成订单Recharge充值元宝item_type1

    public function recharge_make2(){

    	if(IS_POST){

    		$pos = I('post.');

    		$member = D('Member')->find(is_login());

    		//非普通用户充值金额限制1000

//  		if( ($member['vips'] == 2 || $member['vips'] == 3) && (I('post.price') < 1000)){

//  			$re=array(

//  				'status'=>0,

//  				'msg'=>'请充值1000金元宝!',

//  			);

//  			$this->ajaxReturn($re);

//  			exit;

//  		}

			

			//微信充值上限

			if($pos['zftype']==1){

				$time_str = date('Y-m-d', time());//今天0点

				$time_start= strtotime($time_str);//时间戳s

				$time_end= $time_start+24*3600;

	    		$time_rule['add_time'] =  array('between',array($time_start,$time_end));

	    		$time_rule['member_id'] = is_login();

	    		$time_rule['item_type']=1;//1金元宝 2银元宝 3金果 4余额 5会员卡

	    		$time_rule['status']=2;//付款状态 1未付款，  2=已付款 （作为充值订单表时）

	    		$time_rule['zftype']=1;//zftype'支付方式  //1微信 2.支付宝 3余额

	    		$last = M('MemberRecharge')->where($time_rule)->select();

	    		$prices_today = $pos['price'];

	    		foreach($last as $k){

	    			$prices_today = $prices_today + $k['totalprices'];

	    		}

	    		$cz = 1000;//每日充值元宝总个数限制

	    		if( $prices_today >$cz){

	    			$re=array(	

						'msg'=>'抱歉,每日微信充值上限为:'.$cz.'!',

						'status'=>0,

					);

					$this->ajaxReturn($re);exit;

	    		}

	    		

			}



            

            $data['dingdan']=make_order_sn('order');//生成订单号

            $data['member_id'] = $member['id'];

            $data['skperson']='臻惠生活馆';

            $data['totalprices'] = $pos['price'];

			$data['zftype']=$pos['zftype'];//'支付方式  0.未选择 1=微信，2=支付宝',

			$data['memos']='充值金元宝';

			$data['type'] =2; //支出状态 1=出 ，  2=入 

            $data['item_type']=1;//1金元宝 2银元宝 3金果 4余额 5会员卡（15作为充值订单表时）

			$data['status'] = 1;// 1未付款 2已付款

			$data['add_time']=time(); 

			

			$yd=M('MemberRecharge')->data($data)->add();	//生成临时订单


			$re=array(

				'msg'=>$yd?'订单提交成功':'订单提交失败',

				'status'=>$yd?1:0,

				'dingdan'=>$yd?$data['dingdan']:'',//订单号

				'order_id'=>$yd?$yd:''

			);

		}

		$this->ajaxReturn($re);

    	

    }









}