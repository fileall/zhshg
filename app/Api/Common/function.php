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


/*会员币种明细
   * type币种1工资2金元宝3金果4银币
   * account_type流水类型0无特殊定义1下线会员收益2下线商家收益3金果转好友4元宝转好友
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
	

