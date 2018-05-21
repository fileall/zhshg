<?php

namespace Mobile\Controller;

class MerchantController extends HomeController {

    public function _initialize() {

        parent::_initialize();

        $member = D('member')->find(is_login());

        $this->assign('member', $member);

        if(!is_login()){

			$this->redirect('Login/enter');

		}

//		$uid = is_login();
//		if(!($uid == 325 ||$uid == 532 || $uid == 1|| $uid == 306)){
//			echo "<script>alert('店铺系统升级中!');location.href='".U('index/index')."';</script>";
//		}

    }
    
	
    //添加商铺

    public function add_shop(){ 

		//商铺类型//支付类型//让利倍数?

		$cate = D('MemberCate')->where('status =1')->order('ordid asc,id desc')->select();

		$this->assign('cate',$cate);

		//获取微信分享必要参数

        $jssdk = new \Mobile\Org\Jssdk(C("WX_CONFIG.appid"), C("WX_CONFIG.appsecret"));

        $js = $jssdk->GetSignPackage();

		$this->assign('js',$js);

	 	$this->display();

    }
    
    
    
    
    
	//test
    public function test(){
//	$tel_arr=array('00000000001','00000000002','00000000003','00000000004','00000000005','00000000006','00000000007',
//	'00000000008','00000000009','00000000010','00000000011','00000000012','00000000013','00000000014','00000000015');
//	foreach($tel_arr as $k){
//		$data['ewm'] =  $this->set_qrcode("http://".$_SERVER['HTTP_HOST'].U('member/check_pay',array('tel'=>$k)));
//		$shop=D('Merchant')->where(array('tel'=>$k))->save($data);
//		var_dump($shop);
//	}
//die;
//	$aa= D('Member')->where(array('id'=>35))->find();
//	$num=34400000-3440000;
//	$is_ok=D('Member')->where(array('id'=>35))->setInc('silver_coin',$num);
//	$liu=all_ls(35,$num,6,2,'银币到账');
//		var_dump($is_ok);
//		var_dump($liu);
die;    	
    	
    	
    	
//  	$bb = date('Y-m-d H:i:s',1508651998);
//  	$where['gold_fruit'] = array('lt',0);
//  	$t = D('Member')->where($where)->select();
//		$row = D('Member')->where(array('mobile'=>array('in',array('18779983135','18870032008','15070055378','15979165059','1307797261'))))
//  	->field('id,mobile')->select();

		#默认推荐人是商家$tj_type 0默认无人推荐1用户2商家
		//添加$tj_type字段
		$tj_type=0;
		$tuijian=18146708510;
		$have_c=D('Merchant')->where(array('tel'=>$tuijian))->find();//先查商家
		($have_c)&&$tj_type=2;
		if(!$have_c){
			$have_c=D('Member')->where(array('mobile'=>$tuijian))->find();
        	(!$have_c&&$tuijian) && exit(json_encode(array('status'=>0,'msg'=>'请填写正确的推荐人或保持推荐人一栏为空')));
			($have_c)&&$tj_type=1;
		}
		//被推荐人是用户*
		#注册送银币
       	if($tj_type==1){
       		$tj_silver = D('GradeRule')->find($have_['vips']);//等级规则
       		D('Member')->where(array('id'=>$have_c['id']))->setInc('silver_coin',$tj_silver['tj_silver']);//推荐人得银币
       		all_ls($have_c['id'],$tj_silver['tj_silver'],6,2,'下线注册送银币');//人银币流水
       	}
       	if($tj_type==2){
       		$tj_silver = D('GradeRule')->find(3);//等级规则
       		D('Merchant')->where(array('id'=>$have_c['id']))->setInc('silver_coin',$reg_rule['tj_silver']);//推荐shop得银币
       		all_ls_shop($have_c['id'],$tj_silver['tj_silver'],2,2,'下线注册送银币');//shop银币流水
       	}
       	#购买元宝上线赠送银币
		if ($tuijian&&$tj_type==1) {
			
		}
		if ($tuijian&&$tj_type==2) {
			//按vip3到shop账户
		}
		#升级送余额
		if ($tuijian&&$tj_type==1) {
			
		}
		if ($tuijian&&$tj_type==2) {
			//按vip3到shop账户
		}
		#推荐的用户去消费=>二次银币奖励？
		
		
		//被推荐人是shop*
		#申请商铺送
		if ($tuijian&&$tj_type==1) {
			//推荐者1000银币
		}
		if ($tuijian&&$tj_type==2) {
			//推荐者1000银币
		}
		#商家营业返上线银币
		if ($tuijian&&$tj_type==1) {
			//消费金额*让利%*对应等级倍数
		}
		if ($tuijian&&$tj_type==2) {
			//消费金额*让利%*对应等级倍数
		}
		
		
	 	$this->display();

    }


	public function uploadImage()

	{ 

		$media_id = I('media_id', '', 'trim');
		$nums = I('nums', '', 'trim');
		

		

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
		$dir='';
		if($nums==1){
				$dir = "data/attachment/sh_img/".$imgname;
		}else{
				$dir = "data/attachment/merchant/".$imgname;
		}


		$res2 = file_put_contents($dir, $res1);

		

//		dump($res2);die;



		if($res2){

//			$url = $dir.$media_id; 

			$this->ajaxReturn(array('sta'=>1,'msg'=>'上传成功','name'=>$imgname));exit;



		}else{

			$this->ajaxReturn(array('sta'=>-1,'msg'=>'上传失败'));exit;

//			echo json_encode(array('sta'=>-1,'msg'=>'上传失败'));



		}

	}

    

    //店铺申请

    public function sh_shop(){

	    	$pos = I('post.');

	    	$am=D('Merchant');

	    	$uid=is_login();

	    	$member = D('Member')->find($uid);

	    	$mer = D('Merchant')->where(array('tel'=>$pos['tel'],'status'=>array('in',array(0,1,2))))->find();

	    	($mer)&& exit(json_encode(array('status'=>0,'msg'=>'该号码已申请过店铺,请勿重复申请')));//一个商家一个固定电话

	    	

	    	$mer_have = D('Merchant')->where(array('uid'=>$uid,'status'=>array('in',array(0,1,2))))->find();

	    	($mer_have)&& exit(json_encode(array('status'=>0,'msg'=>'您已申请过店铺,请勿重复申请')));//一个用户一个商家

//	    	$have_c = D('Member')->where(array('mobile'=>$pos['tuijian']))->find();//推荐人手机查推荐人
//	    	($have_c&&($have_c['id'] == $uid))&& exit(json_encode(array('status'=>0,'msg'=>'请填写正确的推荐人或保持推荐人一栏为空')));
//	    	(!$have_c&&$pos['tuijian']) && exit(json_encode(array('status'=>0,'msg'=>'请填写正确的推荐人或保持推荐人一栏为空')));

	    	#默认推荐人是商家$tj_type 0默认无人推荐1用户2商家
			//添加$tj_type字段
//			$tj_type=0;
//			$tuijian=$pos['tuijian'];
//			$have_c=D('Merchant')->where(array('tel'=>$tuijian))->find();//先查商家
//			($have_c)&&$tj_type=2;
//			if(!$have_c){
//				$have_c=D('Member')->where(array('mobile'=>$tuijian))->find();
//				($have_c)&&($have_c['id']==$uid)&& exit(json_encode(array('status'=>0,'msg'=>'推荐人不能是自己')));
//	        	(!$have_c&&$tuijian) && exit(json_encode(array('status'=>0,'msg'=>'请填写正确的推荐人或保持推荐人一栏为空')));
//				($have_c)&&$tj_type=1;a
//			}

			$data['tj_type']=1;//上线身份
			$data['title']=$pos['title'];
//			$data['tuijian']=$have_c['id'];//推荐人存用户表id
			$data['tel']=$pos['tel'];
			$data['cate_id']=$pos['cate_id'];
			$data['shop_hours']=$pos['shop_hours1']."-".$pos['shop_hours2'];
			$data['desc']=$pos['desc'];
			$data['long_lat']=$pos['long_lat'];
			$data['address']=$pos['address'];
			$data['info']=$pos['info'];
	        $data['uid']=$uid;
	        $data['add_time']=time();
	        $data['yy_img']=$pos['img_2'][0];
//	        $data['imgs']=implode(',',$pos['img_1']);;
	        
	      	$data['zftype']=implode(',',$pos['zftype']);
			$data['status']=0;//商家申请状态  0为未审核 1为驳回 2为通过	        

	        //推荐二维码(用户表电话直接去推荐、不作二维码)
//	     	$data['ewm_tj'] =  $this->set_qrcode("http://".$_SERVER['HTTP_HOST'].U('Merchant/add_shop',array('tel'=>$pos['tel'])));
			//收款二维码(商户表电话)
   			$data['ewm'] =  $this->set_qrcode("http://".$_SERVER['HTTP_HOST'].U('member/check_pay',array('tel'=>$pos['tel'])));
			$row = $am->add($data);

			if($row){
				$imgs=$pos['img_1'];
				$sh_img['withdraw_id']=$row;
				$sh_img['member_id']=$uid;
				$sh_img['add_time']=time(); 
				foreach($imgs as $k){
					$sh_img['img']=$k;
					D('ShImg')->add($sh_img);
				}
			}

			//后台通过后送10000银币

	        exit(json_encode(array('status'=>1,'msg'=>'已成功提交后台')));

				

   	

    }

    

   

  

  

  

  

  

}