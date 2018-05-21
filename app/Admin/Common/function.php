<?php

function is_login(){

    //return empty($_SESSION['admin']['id'])?0:$_SESSION['admin']['id'];

	return empty($_SESSION['userid']);

}

//含密钥md5加密
//function st_md5($str = ''){
//    return md5(C('st_encryption_key').$str);
//    //return md5($str);
//}

//蛇形转化为驼峰

function snake_case($str = ''){
	
    return strtolower(preg_replace('/((?<=[a-z])(?=[A-Z]))/', '_', $str));
	
}

function attach($attach, $type,$path=false) {

    if (false === strpos($attach, 'http://')) {

        //本地附件

        $img_url = __ROOT__ . '/' . C('pin_attach_path') . $type . '/' . $attach;

        $img_path = realpath(__ROOT__).'/' . C('pin_attach_path') . $type . '/' . $attach;

	return $img_url;

        if(is_file($img_path) || $path){

            return $img_url;

        }else{

            return __ROOT__ . '/data/image/nopicture.gif';

        }

        //远程附件

        //todo...

    } else {

        //URL链接

        return $attach;

    }

}



////判断登录会员所在城市

//	function member_city(){

//		$admin = session('admin');

//		

//	}


//验证是否店长登录
function check_dz(){
	if($_SESSION['admin']['role_id'] == 3){
		return TRUE;
	}else{
		return FALSE;
	}
}
//推荐人数
function recommend_nums($uid){
	return M('member')->where(array('relation_id'=>$uid))->count();
}
//推荐商家
function recommend_merchant($uid){
    return M('merchant')->where(array('relation_id'=>$uid))->count();
}

////流水单
//function all_ls($member_id,$totalprices,$item_type,$type,$memos,$order_id=0){
//	$recharge['dingdan']= 0;
//  $recharge['member_id'] = $member_id; //人*
//  $recharge['skperson']='';
//  $recharge['totalprices'] =$totalprices;//数量*
//	$recharge['zftype'] = 0;//'支付方式  0.未选择 1=微信，2=支付宝',
//	$recharge['type'] = $type; //支出状态 1=出 ，  2=入 *
//  $recharge['item_type'] = $item_type;//item_type 1金元宝 2银元宝 3金果 4余额 （5金币 6银币）*
//	$recharge['memos'] = $memos;//*
//	$recharge['status'] = 2;// 1未付款 2已付款
//	$recharge['add_time'] = time(); 
//  $recharge['order_id'] = $order_id;
//	
//	$is_ls = D('MemberRecharge')->add($recharge);
//	return $is_ls;
//	//人*//数量*//币种的流水//支出状态 //*备注//订单id
//}
//
////无线散下流水单
//function all_ls_super($member_id,$totalprices,$item_type,$type,$memos,$order_id=0){
//	$recharge['dingdan']= 0;
//  $recharge['member_id'] = $member_id; //人*
//  $recharge['skperson']='';
//  $recharge['totalprices'] =$totalprices;//数量*
//	$recharge['zftype'] = 0;//'支付方式  0.未选择 1=微信，2=支付宝',
//	$recharge['type'] = $type; //支出状态 1=出 ，  2=入 *
//  $recharge['item_type'] = $item_type;//item_type 1金元宝 2银元宝 3金果 4余额 （5金币 6银币）*
//	$recharge['memos'] = $memos;//*
//	$recharge['status'] = 2;// 1未付款 2已付款
//	$recharge['add_time'] = time(); 
//	$recharge['order_id']= $order_id;
//	
//	$is_ls = D('MemberZyRecharge')->add($recharge);
//	return $is_ls;
//	//人*//数量*//币种的流水//支出状态 //*备注
//}
//
/////商户流水单
//function all_ls_shop($member_id,$totalprices,$item_type,$type,$memos,$order_id=0){
//	$recharge['dingdan']= 0;
//  $recharge['member_id'] = $member_id; //商户*
//  $recharge['skperson']='';
//  $recharge['totalprices'] =$totalprices;//数量*
//	$recharge['zftype'] = 0;//'支付方式  0.未选择 1=微信，2=支付宝',
//	$recharge['type'] = $type; //支出状态 1=出 ，  2=入 *
//  $recharge['item_type'] = $item_type;//item_type 1商家收益 2商家银币',
//	$recharge['memos'] = $memos;//*
//	$recharge['status'] = 2;// 1未付款 2已付款
//	$recharge['add_time'] = time(); 
//  $recharge['order_id'] = $order_id;
//
//	$is_ls=D('ShopRecharge')->add($recharge);
//	return $is_ls;
//	//商户*//数量*//币种的流水//支出状态 //*备注//订单id
//}

//表名、字段
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


/**
 *删除多余资源
 * $album_img 文件路径
 * $folder文件所在文件夹  'idle_file/'
 */
function del_file($album_img,$folder){
    if(!$folder) return false;
    $flag=false;
    $ext = array_pop(explode('.', $album_img));//删除并返回数组的最后一个值
    #第一张(如果存在缩略图)
    $album_min_img = C('pin_attach_path') . $folder . str_replace('.' . $ext, '_b.' . $ext, $album_img);
    if(is_file($album_min_img)){
        $flag=true;
        @unlink($album_min_img);
    }

    #第二张
    $album_img = C('pin_attach_path') . $folder . $album_img;
    if(is_file($album_img)){
        $flag=true;
        @unlink($album_img);
    }

    $flag&&file_put_contents('test_del.txt','删除'.$album_img.'['.date('Y-m-d H:i:s').']',FILE_APPEND);

    return $flag;
}


//订单支付方式  1金元宝 2金果 3金元宝+银币',
function order_zftype(){
    $order_zftype = array(
//        '0'=>'未选择',
        '1'=>'元宝',
        '2'=>'金果',
        '3'=>'元宝+银币',
    );
    return $order_zftype;
}

//订单状态
function order_status(){
    $order_status= array(
        '1' => '待支付',
        '2' => '待接单',
        '3' => '待收货',
        '4' => '待评价',
        '5' => '已评价',
        '6' => '已取消',
    );
    return $order_status;
}

//退款状态  1退款中 2商家同意退款 3商家拒绝退款
function tk_status($type){
    $tk_status= array(
        '1' => '退款中',
        '2' => '同意退款',
        '3' => '拒绝退款',
    );
    return $tk_status;
}

//会员级别
function vips(){
    $vips = array(
        '1'=>'普通会员',
        '2'=>'掌柜',
        '3'=>'银掌柜',
        '4'=>'金掌柜',
        '5'=>'超级掌柜',

    );
    return $vips;
}


//区代级别
function vips_qd(){
    $vips = array(
        '1'=>'区代',
        '2'=>'市代',
        '3'=>'省代',
    );
    return $vips;
}

/*会员币种明细
     * type币种1工资2金元宝3金果4银币
     * account_type流水类型0无特殊定义1下线会员收益2下线商家收益3金果转好友4元宝转好友5线上消费
     * attach_field附加参数：对方电话（金果转好友）/下线会员id(推荐新会员)/下线商家id(推荐新商家)
     */
function account_arr($type,$uid,$totalprices,$change_desc,$add_time,$oid=0,$account_type=0,$attach_field=0){
    $arr= [
        'type' 			=> $type,//币种1工资2金元宝3金果4银币
        'uid'			=> $uid,
        'totalprices'	=> $totalprices,
        'change_desc'	=> $change_desc,
        'add_time'		=> $add_time,
        'oid'           => $oid,
        'account_type'  => $account_type,
        'attach_field'  => $attach_field,
    ];
    return $arr;
}


//商家币种明细 //$type币种1工资2金元宝3金果4银币
function account_shop_arr($type,$shop_id,$totalprices,$change_desc,$add_time,$oid=0){
    $arr= [
        'type' 			=> $type,//币种1工资2金元宝3金果4银币
        'shop_id'		=> $shop_id,
        'oid'           => $oid,
        'totalprices'	=> $totalprices,
        'change_desc'	=> $change_desc,
        'add_time'		=> $add_time
    ];
    return $arr;
}



