<?php
namespace Admin\Controller;
use Think\Controller;
use Think\View;
use Think\Wechat;           
class WeiXinController extends Controller {
	public function _initialize() {
		
		//微信授权
		$this->Wechat = new Wechat();
    }
	
	/*
	 * 进入微信授权获取code
	 */   
    public function wei_login(){
        $uid=is_login();
		
		$member=M('member')->where(array('id'=>$uid))->find();
		if(!$member['wx_openid']){
			$url1 = "http://qmyxsc.com/index.php?m=Home&c=WeiXin&a=tiao";
			$url = $this->Wechat->get_authorize_url($url1,"STATE");
			header("Location: {$url}");
		}else{
			$this->redirect('Member/user_info');   
		}
    	
    }
	
	public function weixin(){
		$url1 = "http://qmyxsc.com/index.php?m=Home&c=WeiXin&a=ti";
		$url = $this->Wechat->get_authorize_url1($url1,"STATE");
		header("Location: {$url}");
	}
	
	public function ti(){
		//code
		$uid=is_login();
		$code = $_GET['code'];
		//获取access_token和openid
		$token_data = $this->Wechat->get_access_token('','',$code);
		$member=M('member')->where(array('wx_openid'=>$token_data['openid']))->find();
		if(!$member){
			$user_info=$this->Wechat->get_user_info($token_data['access_token'],$token_data['openid']);
		
			$weixin = array(//把信息存在cookie
				'nickname'=>$user_info['nickname'],
				'access_token'=>$user_info['openid'],
				'avatar'=>$user_info['headimgurl'],
				'type'  => 'weixin',			
			);
			cookie('yxsq_xinxi',$weixin);
			$this->redirect('Member/bang');
		}else{
			M('Member')->where('id='.$member['id'])->setInc('login');
			//保存cookie和session
			$user_auth = array(
				'id' => $member['id'],
			    'mobile' => substr_replace($member['mobile'],'****', 3,4)   ,
                'last_login_time' => time(),
                'last_login_ip'  =>get_client_ip(),
			);
			//cookie和session
			cookie('user_auth',$user_auth);
			$this->redirect('Member/user_info');
		}
		//插入用户的openid
//		M('member')->where(array('id'=>$uid))->setField('wx_openid',st_md5($token_data['openid']));
//				   
//		$this->redirect('Member/user_info');   
	}
	
	//通过code获取业务员openid
	public function tiao(){
		//code
		$uid=is_login();
		$code = $_GET['code'];
		//获取access_token和openid
		$token_data = $this->Wechat->get_access_token('','',$code);
		
		//插入用户的openid
		M('member')->where(array('id'=>$uid))->setField('wx_openid',$token_data['openid']);
				   
		$this->redirect('Member/user_info');   
	}
	
	//发关模板消息
	public function connect_w($openid,$first,$title,$time,$orderid){
		$template = array(
			'touser' => $openid,
			'template_id' => 'QFX85cBlLqFvHeKHyUvUtwTyRca2ZZ4D1Qyh2Fd-h8g',
			'url' =>"http://qmyxsc.com/index.php?m=Home&c=OrderWap&a=express&id=".$orderid,
			'data' => array(  
				'first' => array(
					'value' => "亲,订单已经发货了",  
					'color' => "#173177",
				),
				'keyword1' => array(
					'value' => $first,
					'color' => "#173177",
				),
				'keyword2' => array(
					'value' => $title,
					'color' => "#173177",
				),
				'keyword3' => array(
					'value' => $time,
					'color' => "#173177",
				),
				'remark' => array(
					'value' => "商品已发货,请您注意查收",  
					'color' => "#173177",
				),
			),
		);  
		   
		$this->Wechat->wx_api();
		return $this->Wechat->send_message(json_encode($template));  
	}


    //发关模板消息
	public function connect($openid,$first,$title,$time,$time1,$orderid,$ttt){
		$template = array(
			'touser' => $openid,
			'template_id' => 'nJrKIIxA-3QmcBfPmrvKJGVJCl0JSy3OktoiqTuGBtA',
			'url' =>"http://qmyxsc.com/index.php?m=Home&c=OrderWap&a=myorder_detail&id=".$orderid,
			'data' => array(  
				'first' => array(
					'value' => $first,  
					'color' => "#173177",
				),
				'keyword1' => array(
					'value' => $title,
					'color' => "#173177",
				),
				'keyword2' => array(
					'value' => $time,
					'color' => "#173177",
				),
				'keyword3' => array(
					'value' => $time1,
					'color' => "#173177",
				),
				'remark' => array(
					'value' => $ttt,  
					'color' => "#173177",
				),
			),
		);  
		   
		$this->Wechat->wx_api();
		return $this->Wechat->send_message(json_encode($template));  
	}
	
	//用来测试发送模板消息
	public function test(){
		$xx = $this->connect_wx('oMbTdwdhyZDQvT6Xj2pCyvksKZT0',1111111111);
		dump($xx);
	}
	    
}