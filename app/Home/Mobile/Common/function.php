<?php

function attach($attach, $type,$path=false) {

    if (false === strpos($attach, 'http://')) {
        //本地附件

        $img_url = __ROOT__ . 'data/attachment/' . $type . '/' . $attach;

        $img_path = realpath(__ROOT__).'/data/attachment/' . $type . '/' . $attach;

	//return $img_url; 

        if(is_file($img_path) || $path){

            return $img_url;

        }else{

            return __ROOT__ . '/data/nopicture.jpg';

        }

        //远程附件

        //todo...

    } else {

        //URL链接

        return $attach;

    }

}


/**
 * 检测用户是否登录
 * @return integer 0-未登录，大于0-当前登录用户ID
 */
function is_login(){
    $user = cookie('user_auth');
    if (empty($user)) {
        return 0;
    } else {
		return $user['id'];
        //return session('user_auth')==data_auth_sign($user) ? $user['id'] : 0;    
    }
}


function make_order_sn($mode = 'Order', $field = 'order_sn') {
    $Ord = M($mode);
    $ordcode = date('ymd') . substr(md5(rand(100000,999999)),rand(0,20),6).substr(time(), -4) . substr(microtime(), 2, 5);
    $oldcode = $Ord -> where(array($field => $ordcode)) -> getField($field);
    if ($oldcode) {
        make_order_sn();
    } else {
        return $ordcode;
    }
}

//付款人数
function payer($item_id){
	$res =M('order_list')->query('select distinct uid from jrkj_order_list where item_id='.$item_id);
	return count($res);
}

//银币流水
function yb_ls($member_id,$totalprices,$type,$memos){
	$recharge['dingdan']= 0;
    $recharge['member_id'] = $member_id; //人*
    $recharge['skperson']='';
    $recharge['totalprices'] =$totalprices;//数量*
	$recharge['zftype'] = 1;//'支付方式  0.未选择 1=微信，2=支付宝',
	$recharge['type'] = $type; //支出状态 1=出 ，  2=入 *
    $recharge['item_type'] = 6;//item_type 1金元宝 2银元宝 3金果 4余额 （5金币 6银币）*
	$recharge['memos'] = $memos;//*
	$recharge['status'] = 2;// 1未付款 2已付款
	$recharge['add_time'] = time(); 
	D('MemberRecharge')->add($recharge);
	//人*//数量*//支出状态 //*备注
}

//流水单
function all_ls($member_id,$totalprices,$item_type,$type,$memos){
	$recharge['dingdan']= 0;
    $recharge['member_id'] = $member_id; //人*
    $recharge['skperson']='';
    $recharge['totalprices'] =$totalprices;//数量*
	$recharge['zftype'] = 0;//'支付方式  0.未选择 1=微信，2=支付宝',
	$recharge['type'] = $type; //支出状态 1=出 ，  2=入 *
    $recharge['item_type'] = $item_type;//item_type 1金元宝 2银元宝 3金果 4余额 （5金币 6银币）*
	$recharge['memos'] = $memos;//*
	$recharge['status'] = 2;// 1未付款 2已付款
	$recharge['add_time'] = time(); 
	$is_ls = D('MemberRecharge')->add($recharge);
	return $is_ls;
	//人*//数量*//币种的流水//支出状态 //*备注
}

//商户流水单
function all_ls_shop($member_id,$totalprices,$item_type,$type,$memos){
	$recharge['dingdan']= 0;
    $recharge['member_id'] = $member_id; //商户*
    $recharge['skperson']='';
    $recharge['totalprices'] =$totalprices;//数量*
	$recharge['zftype'] = 0;//'支付方式  0.未选择 1=微信，2=支付宝',
	$recharge['type'] = $type; //支出状态 1=出 ，  2=入 *
    $recharge['item_type'] = $item_type;//item_type 1商家收益 2商家银币',
	$recharge['memos'] = $memos;//*
	$recharge['status'] = 2;// 1未付款 2已付款
	$recharge['add_time'] = time(); 
	$is_ls=D('ShopRecharge')->add($recharge);
	return $is_ls;
	//商户*//数量*//币种的流水//支出状态 //*备注
}


//	//添加用户
//	function add_member($nickname,$mobile){
//		$data['vips'] = 1;
//		$data['sex'] = 0;//默认0未选择
//		$data['password'] = 'c6f4b02c1aa65a08af09cb8423c53c07';
//		$data['paypassword'] = 'c6f4b02c1aa65a08af09cb8423c53c07';
//		$data['nickname'] = $nickname;
//		$data['realname'] = $nickname;
//		$data['mobile'] = $mobile;//默认0未选择
//		$data['reg_time'] = time();//默认0未选择
//		$data['last_login_time'] = $data['reg_time'];//默认0未选择
//		$data['ewm'] =  $this->set_qrcode("http://".$_SERVER['HTTP_HOST'].U('login/register',array('ewid'=>$data['mobile'])));
//		$uid = D('Member')->add($data);
//		return $uid;
//	
//	}
//成为商家
//	function be_shop($title,$mobile,$uid,$rangli,$set_coin){
//		
//		$datas['title']=$title;
//		$datas['tel']=$mobile;
//		$datas['cate_id']=13;
//		$datas['shop_hours']='9:00-22:00';
//		$datas['desc']='南昌';
//	    $datas['uid']=$uid;
//	    $datas['add_time']=time();
//	  	$datas['zftype']='1,2,3';
//	  	$datas['rangli']=$rangli;
//	  	$datas['set_coin']=$set_coin;
//		$datas['status']=2;//商家申请状态  0为未审核 1为驳回 2为通过	        
//	    //推荐二维
////		$datas['ewm_tj'] =  $this->set_qrcode("http://".$_SERVER['HTTP_HOST'].U('member/add_shop',array('tel'=>$mobile)));
//		//收款二维码(商户表电话、返银币倍数)
//	   	$datas['ewm'] =  $this->set_qrcode("http://".$_SERVER['HTTP_HOST'].U('member/w_shaped_pay',array('tel'=>$mobile)));
//	
//		D('Member')->where(array('id'=>$uid))->save(array('type'=>2));//商家
//		$row = D('Merchant')->add($datas);
//		return $row;
//	}
	

