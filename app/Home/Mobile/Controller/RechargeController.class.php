<?php
namespace Mobile\Controller;
class RechargeController extends HomeController {
    public function _initialize() {
        parent::_initialize();
        $member = D('member')->find(is_login());
        $this->assign('member', $member);
        if(!is_login()){
			$this->redirect('Login/enter');
		}
		$uid = is_login();
	
//		if(!($uid == 325 ||$uid == 532 || $uid == 1|| $uid == 306)){
//
//			echo "<script>alert('系统升级中!');location.href='".U('index/index')."';</script>";
//
//		}
    }
  //升级会员
   public function upgrade(){
   	if (IS_POST) { 
    		$price = I('price', 0, 'float'); 
			$vips = I('vips', 0, 'intval'); 
    		
			$data['body'] = '会员充值'; 
//	        $data['number'] = time();
	        $data['number'] = I('dingdan');
	        
	        $item_type = I('post.item_type');//1充值元宝 2充值会员  
	        
	        $attach = json_encode(array('item_type'=>$item_type, 'vips'=>$vips));
	        
			$data['attach'] = $attach;//附加参数  
			if($vips==2){$price=300;}
			if($vips==3){$price=1000;}
			
	        $data['price'] = $price* 100;//值为1就是1分  
	        if(is_login()==1){
	        	$data['price'] = 0.01* 100;//值为1就是1分  
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
        	
        	$grade_rule = D('GradeRule')->order('id asc')->select();
        	$this->assign('grade_rule',$grade_rule);
    		$this->display();
    	}
   }

 	//生成订单Recharge充值会员item_type5
    public function recharge_make(){
    	if(IS_POST){
    		$pos = I('post.');
    		$member = D('Member')->find(is_login());  
//  		($member['vips'] >= $pos['vips']) && exit(json_encode(array('status'=>0,'msg'=>'请确定您的vip等级!')));
            
            $data['dingdan']=make_order_sn('order');//生成订单号
            $data['member_id'] = $member['id'];
            $data['skperson']='臻惠生活馆';
            $data['totalprices'] = 0; 
            ($pos['vips'] == 1) && $data['totalprices'] = 0;//总价
            ($pos['vips'] == 2) && $data['totalprices'] = 300;//总价
            ($pos['vips'] == 3) && $data['totalprices'] = 1000;//总价
			$data['zftype'] = 1;//'支付方式  0.未选择 1=微信，2=支付宝',
            $data['memos']='充值会员'; 
            $data['type'] =2; //支出状态 1=出 ，  2=入 
            $data['item_type'] = 5;//1金元宝 2银元宝 3金果 4余额 5会员卡（15作为充值订单表时）  
			$data['status'] = 1;// 1未付款 2已付款
			$data['add_time']=time();   
			$data['old_vip']=$member['vips'];   
			$data['after_vip']=$pos['vips'];   
			
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
    
//  //生成订单Recharge金果回购（换余额）item_type3
//  public function recharge_make3(){
//  	//订单表3金果    流水表加余额减金果totalprices  zftype item_type type memos
//  	if(IS_POST){
//  		$pos = I('post.');
//  		$member = D('Member')->find(is_login());
//          
//          $data['dingdan']=make_order_sn('order');//生成订单号
//          $data['member_id'] = $member['id'];
//          $data['skperson']='臻惠生活馆';
//          $data['totalprices'] = $pos['nums'];
//			$data['zftype']=0;//'支付方式  0.未选择 1=微信，2=支付宝',
//          $data['memos']='金果回购';
//          $data['type'] =0; //支出状态 1=出 ，  2=入 
//          $data['item_type']=3;//1金元宝 2银元宝 3金果 4余额 5会员卡（15作为充值订单表时）
//			$data['status'] = 1;// 1未付款 2已付款
//			$data['add_time']=time(); 
//			
//			$yd=M('MemberRecharge')->data($data)->add();	//生成临时订单		
//			$re=array(
//				'msg'=>$yd?'订单提交成功':'订单提交失败',
//				'status'=>$yd?1:0,
//				'dingdan'=>$yd?$data['dingdan']:'',//订单号
//				'order_id'=>$yd?$yd:''
//			);
//		}
//		$this->ajaxReturn($re);
//  	
//  }
    
//	//生成订单Recharge充值元宝充值会员item_type1
//  public function recharge_make2(){
//  	if(IS_POST){
//  		$pos = I('post.');
//  		$member = D('Member')->find(is_login());
//  		//非普通用户充值金额限制1000
//  		if( ($member['vips'] == 2 || $member['vips'] == 3) && (I('post.price') < 1000)){
//  			$re=array(
//  				'status'=>0,
//  				'msg'=>'请充值1000金元宝!',
//  			);
//  			$this->ajaxReturn($re);
//  			exit;
//  		}
//          
//          $data['dingdan']=make_order_sn('order');//生成订单号
//          $data['member_id'] = $member['id'];
//          $data['skperson']='臻惠生活馆';
//          $data['totalprices'] = $pos['price'];
//			$data['zftype']=$pos['zftype'];//'支付方式  0.未选择 1=微信，2=支付宝',
//			$data['memos']='充值金元宝';
//			$data['type'] =2; //支出状态 1=出 ，  2=入 
//          $data['item_type']=1;//1金元宝 2银元宝 3金果 4余额 5会员卡（15作为充值订单表时）
//			$data['status'] = 1;// 1未付款 2已付款
//			$data['add_time']=time(); 
//			
//			$yd=M('MemberRecharge')->data($data)->add();	//生成临时订单		
//			$re=array(
//				'msg'=>$yd?'订单提交成功':'订单提交失败',
//				'status'=>$yd?1:0,
//				'dingdan'=>$yd?$data['dingdan']:'',//订单号
//				'order_id'=>$yd?$yd:''
//			);
//		}
//		$this->ajaxReturn($re);
//  	
//  }




}