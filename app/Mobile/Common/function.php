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
//    $user = session('user_auth');
    if (empty($user)) {
        return 0;
    } else {
		return $user['id'];
        //return session('user_auth')==data_auth_sign($user) ? $user['id'] : 0;    
    }
}




//付款人数
function payer($item_id){
	$res =M('order_list')->query('select distinct uid from jrkj_order_list where item_id='.$item_id);
	return count($res);
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


    //地区名获取id
     function get_place_id($str){
        if(!$str) return false;
         $place_id= M('place')->where(['name'=>$str])->getField('id');
        return $place_id;
     }

     /*会员币种明细
      * type币种1工资2金元宝3金果4银币
      * account_type流水类型0无特殊定义1下线会员收益2下线商家收益3金果转好友4元宝转好友5线上消费6银楼置换
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


    //生成订单号$mode模型 $field订单号字段
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

    //生成订单号
    function create_order_sn(){
        $order_sn=substr_replace($_SERVER['REQUEST_TIME'].rand(10000,99999), 88,1,5);
        return $order_sn;
    }

    //购物车获取商品 $map=['uid'=>$uid,'id'=>['in',$cart_ids]
    function gwc_item($map)
    {
        if(!is_array($map)) return false;
        $gwc_mod=M('gwc');
        $item_mod=M('item');
        $item_attr_mod=M('item_attr');

        $list=$gwc_mod->where($map)->select();
        if(!$list) return false;

        $item_ids=array_column($list,'item_id');
        $attr_ids=array_column($list,'attr_id');

        $item=$item_mod->where(['id'=>['in',$item_ids]])->getField('id,img,title');
        $item_attr=$item_attr_mod->where(['id'=>['in',$attr_ids]])->getField('id,attr_name,attr_value,price,oldprice,acer,coin');

        $money=0;//统计订单总金额
        $count = 0;//统计订单商品总数量
        foreach($list as $kk=>$vv){
            unset($item[$vv['item_id']]['id']);
            unset($item_attr[$vv['attr_id']]['id']);
            $list[$kk]=array_merge($list[$kk],$item[$vv['item_id']],$item_attr[$vv['attr_id']]);
            $money +=($list[$kk]['price']*$vv['num']);
            $count +=1;
        }

        $msg['money'] = $money;
        $msg['count'] = $count;

        $data['msg']=$msg;
        $data['list']=$list;//购物车选中的商品列表

        return $data;

    }

//    //立即购买获取商品
//    function get_item_now($param){
//        $item_id=$param['item_id'];
//        $attr_id=$param['attr_id'];
//        $num=$param['num'];
//
//        $item=M('item')->where(['id'=>$item_id])->getField('id as item_id,img,title');
//        $item_attr= M('item_attr')->where(['id'=>$attr_id])->getField('id as attr_id,attr_name,attr_value,price,oldprice,acer,coin');
//        $list[0]=array_merge($item[$item_id],$item_attr[$attr_id]);
//        $list[0]['num']=$num;
//        $money=$num*$item_attr[$attr_id]['price'];
//        $msg['money']=$money;
//        $msg['count']=1;
//
//        $data['msg']=$msg;
//        $data['list']=$list;
//
//        return $data;
//    }

    /*微信多图上传
     *
     * $data参数数组
     * $folder图片所在文件夹
     * return图片路径
     */
    function wx_upload_img($data,$folder)

    {

        $media_id = $data['media_id'];

        $appid = C("WX_CONFIG.appid");
        $appsecret = C("WX_CONFIG.appsecret");

        if (!S('wx_access_token')) {
            $res = json_decode(file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$appsecret}"), true);
            $access_token = $res['access_token'];
            if ($access_token) {
                S('wx_access_token',$access_token,7000);//设置缓存
            }
        } else {
            $access_token = S('wx_access_token');
        }

        $uri = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$media_id";
        $res1 = file_get_contents($uri);

        $imgname = date('YmdHis').rand(1000,9999).".jpg";
//        $imgname = $media_id.".jpg";

        $dir = "data/attachment/".$folder.$imgname;

        $res2 = file_put_contents($dir, $res1);//生成文件
        if($res2){
            return $imgname;
        }else{
            return false;
        }

    }

	

