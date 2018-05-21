<?php
//多文件上传
function _uploads($dir='',$thumb = array()){
    $upload = new UploadFile();
    if (!empty($thumb)) {
        $upload->thumb = true;
        $upload->thumbMaxWidth = $thumb['width'];
        $upload->thumbMaxHeight = $thumb['height'];
        $upload->thumbPrefix = '';
        $upload->thumbSuffix = isset($thumb['suffix']) ? $thumb['suffix'] : '_thumb';
        $upload->thumbExt = isset($thumb['ext']) ? $thumb['ext'] : '';
        $upload->thumbRemoveOrigin = isset($thumb['remove_origin']) ? true : false;
    }

    if ($dir) {
        $base_path = C('pin_attach_path');
        //如果从数据库读取失败，则从配置文件中加载
        if('' == $base_path) $base_path = C('yb_attach_path');
        $upload_path = $base_path . $dir . '/';
        $upload->savePath = $upload_path;
    }
    //自定义上传规则
    $upload = $this->_upload_init($upload);
    $result = $upload->upload();
    $maps = array('result' => $result,
        'uploadinfos' => $upload->uploadsFileInfo);
    //dump($upload->uploadsFileInfo); exit;
    return $maps;
}



/**
 * 上传文件默认规则定义
 */
function _upload_init($upload) {
    $allow_max = C('pin_attr_allow_size'); //读取配置
    $allow_exts = explode(',', C('pin_attr_allow_exts')); //读取配置

    //如果从数据库读取失败，则从配置文件中加载
    if('' == $allow_max) $allow_max = C('yb_attr_allow_size');
    if(NULL == $allow_exts || 0 >= count($allow_exts) || NULL == $allow_exts[0]) $allow_exts =explode(',', C('yb_attr_allow_exts')); //读取配置

    $allow_max && $upload->maxSize = $allow_max * 1024;   //文件大小限制
    $allow_exts && $upload->allowExts = $allow_exts;  //文件类型限制
    $upload->saveRule = 'uniqid';
    return $upload;
}

//将秒数转换成时分秒
function changeTimeType($seconds){
    if ($seconds > 3600){
        $hours = intval($seconds/3600);
        $minutes = $seconds % 3600;
        $time = $hours.":".gmstrftime('%M:%S', $minutes);
    }else{
        $time = gmstrftime('%H:%M:%S', $seconds);
    }
    return $time;
}

//文件大小单位转换GB MB KB
function formatBytes($size) {
    $units = array(' B', ' KB', ' MB', ' GB', ' TB');
    for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
    return round($size, 2).$units[$i];
}

/**
 * 对象 转 数组
 *
 * @param object $obj 对象
 * @return array
 */
function object_to_array($obj) {
    $obj = (array)$obj;
    foreach ($obj as $k => $v) {
        if (gettype($v) == 'resource') {
            return;
        }
        if (gettype($v) == 'object' || gettype($v) == 'array') {
            $obj[$k] = (array)object_to_array($v);
        }
    }

    return $obj;
}

 /**
     * 获取分类下面的所有子分类的ID集合
     *
     * @param int $id
     * @param bool $with_self
     * @return array $array
     */
     function get_child_ids($id, $with_self=false) {
        $spid = D('item_cate')->where(array('id'=>$id))->getField('spid');
        $spid = $spid ? $spid .= $id .'|' : $id .'|';
        $id_arr = D('item_cate')->field('id')->where(array('spid'=>array('like', $spid.'%')))->select();
        $array = array();
        foreach ($id_arr as $val) {
            $array[] = $val['id'];
        }
        $with_self && $array[] = $id;
        return $array;
    }


/**
 * 数据签名认证
 * @param  array  $data 被认证的数据
 * @return string       签名
 */


function data_auth_sign($data) {
    //数据类型检测
    if(!is_array($data)){
        $data = (array)$data;
    }
    ksort($data); //排序
    $code = http_build_query($data); //url编码并生成query字符串
    $sign = sha1($code); //生成签名
    return $sign;
}

//含密钥md5加密
function st_md5($str = ''){
	return md5(C('st_encryption_key').$str);
	//return md5($str);
}
//邀请码
function id2invite($id=0){
    $id = empty($id)?0:intval($id);
    return $id + 12101;
}

function invite2id($code){
    if(empty($code)){
        return 0;
    }else{
        return abs($code - 12101);
    }
}
/*
 * 获取缩略图
 */

function get_thumb($img, $suffix = '_thumb') {
    if (false === strpos($img, 'http://')) {
        $ext = array_pop(explode('.', $img));
        $thumb = str_replace('.' . $ext, $suffix . '.' . $ext, $img);
    } else {
        if (false !== strpos($img, 'taobaocdn.com') || false !== strpos($img, 'taobao.com')) {
            switch ($suffix) {
                case '_s':
                    $thumb = $img . '_100x100.jpg';
                    break;
                case '_m':
                    $thumb = $img . '_210x1000.jpg';
                    break;
                case '_b':
                    $thumb = $img . '_480x480.jpg';
                    break;
            }
        }
    }
    return $thumb;
}
/*二维数组按指定的键值排序*/
function array_sort($array,$keys,$type='asc'){
	 if(!isset($array) || !is_array($array) || empty($array)){
		 return '';
	 }
	 if(!isset($keys) || trim($keys)==''){
		 return '';
	 }
	 if(!isset($type) || $type=='' || !in_array(strtolower($type),array('asc','desc'))){
		 return '';
	 }
	 $keysvalue=array();
	 foreach($array as $key=>$val){
		  $val[$keys] = str_replace('-','',$val[$keys]);
		  $val[$keys] = str_replace(' ','',$val[$keys]);
		  $val[$keys] = str_replace(':','',$val[$keys]);
		  $keysvalue[] =$val[$keys];
	 }
	 asort($keysvalue); //key值排序
	 reset($keysvalue); //指针重新指向数组第一个
	 foreach($keysvalue as $key=>$vals) {
		  $keysort[] = $key;
	 }
	 $keysvalue = array();
	 $count=count($keysort);
	 if(strtolower($type) != 'asc'){
		  for($i=$count-1; $i>=0; $i--) {
			 $keysvalue[] = $array[$keysort[$i]];
		  }
	 }else{
		  for($i=0; $i<$count; $i++){
			 $keysvalue[] = $array[$keysort[$i]];
		  }
	 }
	 return $keysvalue;
}
/* 求两个已知经纬度之间的距离,单位为米
 *
 * @param lng1 $ ,lng2 经度
 * @param lat1 $ ,lat2 纬度
 * @return float 距离，单位米
 * @author www.Alixixi.com
 */
function getdistance($lng1, $lat1, $lng2, $lat2) {
    // 将角度转为狐度
    $radLat1 = deg2rad($lat1); //deg2rad()函数将角度转换为弧度
    $radLat2 = deg2rad($lat2);
    $radLng1 = deg2rad($lng1);
    $radLng2 = deg2rad($lng2);
    $a = $radLat1 - $radLat2;
    $b = $radLng1 - $radLng2;
    $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137 * 1000;
    return $s;
}
/**
 * 获取用户头像
 */
function avatar($uid, $size) {
    if($size){
        $avatar_size = explode(',', C('pin_avatar_size'));
        $size = in_array($size, $avatar_size) ? $size : '100';
        $avatar_dir = avatar_dir($uid);
        $avatar_file = $avatar_dir . md5($uid) . "_{$size}.jpg";
    }else{
        $avatar_file = $uid . "_thumb.jpg";
    }

    if (!is_file(C('pin_attach_path') . 'avatar/' . $avatar_file)) {
        $avatar_file = "default.jpg";
    }
    return __ROOT__ . '/' . C('pin_attach_path') . 'avatar/' . $avatar_file;
}

function avatar_dir($uid) {
    $uid = abs(intval($uid));
    $suid = sprintf("%09d", $uid);
    $dir1 = substr($suid, 0, 3);
    $dir2 = substr($suid, 3, 2);
    $dir3 = substr($suid, 5, 2);
    return $dir1 . '/' . $dir2 . '/' . $dir3 . '/';
}

//获取一个随机且唯一的订单号；
function make_order_id($mode = 'Recharge'){
    $Ord=M($mode);
    $ordcode = date('ymd').substr(time(),-5).substr(microtime(),2,5);
    $oldcode=$Ord->where(array('order_id'=>$ordcode))->getField('order_id');
    if($oldcode){
        make_order_id($mode);
    }else{
        return $ordcode;
    }
}


function check_pwd($password){
    $RegExp='/^[a-zA-Z0-9_]{6,16}$/'; //由大小写字母跟数字组成并且长度在6-16字符之间
    return preg_match($RegExp,$password)?$password:false;
}

function check_nickname($Argv){
    $RegExp='/^[a-zA-Z0-9_]{4,16}$/'; //由大小写字母跟数字组成并且长度在3-16字符之间
//  $RegExp='^([\u4e00-\u9fa5]{3,15}|[0-9a-zA-Z_]{6,30})$'; //由大小写字母跟数字组成并且长度在4-10字符之间
    return preg_match($RegExp,$Argv)?$Argv:false;
}

function check_mobile($Argv){
    $RegExp='/^(?:13|15|17|18)[0-9]{9}$/';
    return preg_match($RegExp,$Argv)?$Argv:false;
}

function check_email($Argv){
    $RegExp='/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/';
    return preg_match($RegExp,$Argv)?$Argv:false;
}

function code2html($code){
    return htmlspecialchars_decode($code);
}

//手机号中间四位用*代替
function replace_mobile($phone){
    return  preg_replace('/(1[3578]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$phone);
}

/*
* 关键词中的空格替换为','
*/
function empty_replace($str) {
    $str = preg_replace("/<sty(.*)\\/style>|<scr(.*)\\/script>|<!--(.*)-->/isU","",$str);
    $alltext = "";
    $start = 1;
    for($i=0;$i<strlen($str);$i++)
    {
        if($start==0 && $str[$i]==">")
        {
            $start = 1;
        }
        else if($start==1)
        {
            if($str[$i]=="<")
            {
                $start = 0;
                $alltext .= " ";
            }
            else if(ord($str[$i])>31)
            {
                $alltext .= $str[$i];
            }
        }
    }
    $alltext = str_replace("　","",$alltext);
    $alltext = preg_replace("/&([^;&]*)(;|&)/","",$alltext);
    $alltext = preg_replace("/[ ]+/s"," ",$alltext);
    return $alltext;
}
/**
 * google api 二维码生成【QRcode可以存储最多4296个字母数字类型的任意文本，具体可以查看二维码数据格式】
 * @param string $chl 二维码包含的信息，可以是数字、字符、二进制信息、汉字。
 不能混合数据类型，数据必须经过UTF-8 URL-encoded
 * @param int $widhtHeight 生成二维码的尺寸设置
 * @param string $EC_level 可选纠错级别，QR码支持四个等级纠错，用来恢复丢失的、读错的、模糊的、数据。
 *       L-默认：可以识别已损失的7%的数据
 *       M-可以识别已损失15%的数据
 *       Q-可以识别已损失25%的数据
 *       H-可以识别已损失30%的数据
 * @param int $margin 生成的二维码离图片边框的距离
 */
function qr_code($chl,$widhtHeight ='170',$EC_level='L',$margin='0'){
    $chl = urlencode($chl);
    echo '<img src="http://chart.apis.google.com/chart?chs='.$widhtHeight.'x'.$widhtHeight.'&cht=qr&chld='.$EC_level.'|'.$margin.'&chl='.$chl.'" alt="QR code" widhtHeight="'.$widhtHeight.'" widhtHeight="'.$widhtHeight.'"/>';
}

function get_w_o_line($apply_money,$provide_rate=1){

    $provide_fee = $apply_money;
    if($provide_fee <= 50){
        $warnning_line = $provide_fee * 1.1;
        $open_line = $provide_fee * 1.06;
    }elseif($provide_fee > 50 && $provide_fee < 200){
        $warnning_line = $provide_fee * 1.1;
        $open_line = $provide_fee * 1.06;
    }elseif($provide_fee > 200 && $provide_fee <= 2000){
        $warnning_line = $provide_fee * 1.12;
        $open_line = $provide_fee * 1.08;
    }elseif($provide_fee > 2000 && $provide_fee <= 5000){
        $warnning_line = $provide_fee * 1.14;
        $open_line = $provide_fee * 1.1;
    }
    $data = array(
        'warnning_line' => $warnning_line*10000,
        'open_line' => $open_line*10000
    );
    return $data;
}

/*计算生成利息 默认回月息*/
function interest_member($invest_amount = 0,$rate=0,$month = 1){
    $amount = ($rate * $month * $invest_amount)/12;
    return sprintf("%.2f", $amount);
}

/*
 * 计算时间差
 * $part 还回时间类型
 * */
function st_date_diff($part, $begin, $end)
{
    $diff = strtotime($end) - strtotime($begin);
    switch($part)
    {
        case "y": $retval = bcdiv($diff, (60 * 60 * 24 * 365)); break;
        case "m": $retval = bcdiv($diff, (60 * 60 * 24 * 30)); break;
        case "w": $retval = bcdiv($diff, (60 * 60 * 24 * 7)); break;
        case "d": $retval = bcdiv($diff, (60 * 60 * 24)); break;
        case "h": $retval = bcdiv($diff, (60 * 60)); break;
        case "n": $retval = bcdiv($diff, 60); break;
        case "s": $retval = $diff; break;
    }
    return $retval;
}

function get_url() {
    $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
    $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
    $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
    $relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);
    return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
}



function encrypt($data, $key='stone') {
    $prep_code = serialize($data);
    $block = mcrypt_get_block_size('des', 'ecb');
    if (($pad = $block - (strlen($prep_code) % $block)) < $block) {
        $prep_code .= str_repeat(chr($pad), $pad);
    }
    $encrypt = mcrypt_encrypt(MCRYPT_DES, $key, $prep_code, MCRYPT_MODE_ECB);
    return base64_encode($encrypt);
}

function decrypt($str, $key='stone') {
    $str = base64_decode($str);
    $str = mcrypt_decrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);
    $block = mcrypt_get_block_size('des', 'ecb');
    $pad = ord($str[($len = strlen($str)) - 1]);
    if ($pad && $pad < $block && preg_match('/' . chr($pad) . '{' . $pad . '}$/', $str)) {
        $str = substr($str, 0, strlen($str) - $pad);
    }
    return unserialize($str);
}

if(!function_exists('array_column')){
    function array_column($input, $columnKey, $indexKey=null){
        $columnKeyIsNumber      = (is_numeric($columnKey)) ? true : false;
        $indexKeyIsNull         = (is_null($indexKey)) ? true : false;
        $indexKeyIsNumber       = (is_numeric($indexKey)) ? true : false;
        $result                 = array();
        foreach((array)$input as $key=>$row){
            if($columnKeyIsNumber){
                $tmp            = array_slice($row, $columnKey, 1);
                $tmp            = (is_array($tmp) && !empty($tmp)) ? current($tmp) : null;
            }else{
                $tmp            = isset($row[$columnKey]) ? $row[$columnKey] : null;
            }
            if(!$indexKeyIsNull){
                if($indexKeyIsNumber){
                    $key        = array_slice($row, $indexKey, 1);
                    $key        = (is_array($key) && !empty($key)) ? current($key) : null;
                    $key        = is_null($key) ? 0 : $key;
                }else{
                    $key        = isset($row[$indexKey]) ? $row[$indexKey] : 0;
                }
            }
            $result[$key]       = $tmp;
        }
        return $result;
    }
}


function get_merchant_bond($member_id = 0){
    $member_id = empty($member_id)?is_login():$member_id;
    $task_ids = M('Task')->where(array('member_id'=>$member_id))->getField('id',true);
    if($task_ids){
        //
        $map['task_id'] = array('IN',$task_ids);
        $map['status'] = array('IN',array(3,4,5,6));
        $task_apply = D('TaskApply')->field('id')->where($map)->relation('apply_info')->select();

        $amount = array_column($task_apply,'amount');
        $commission_amount = array_column($task_apply,'commission_amount');

        return array_sum($amount)+array_sum($commission_amount);
    }else{
        return 0;
    }
}

function is_vip(){
    $vip = M('MemberVip')->where(array('member_id'=>is_login()))->find();
    if(!empty($vip)){
        $time_end = date("Y-m-d H:i:s", strtotime("+".$vip['month']." months", strtotime($vip['time_start'])));
        if($time_end < date('Y-m-d H:i:s')){
            return false;
        }
        return $vip;
    }else{
        return false;
    }
}

function export_csv($data=array(),$filename='')
{
    $filename = empty($filename)? date('YmdHis') . ".csv":$filename;

    header("Content-Type: application/vnd.ms-excel; charset=GB2312");
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");
    header("Content-Disposition: attachment;filename=$filename ");
    header("Content-Transfer-Encoding: binary ");

    $csvContent = array_to_str($data);

    echo $csvContent;
}

function array_to_str($data){
    $str = '';
    foreach($data as $val){
        $linestr = implode(',',$val);
        //$str .= iconv("utf-8","gb2312",$linestr)." \n";
        $str .= mb_convert_encoding($linestr,"gbk","utf-8")." \n";
    }
    return $str;
}

function stripslashes_deep($value) {
	if ( is_array($value) ) {
		$value = array_map('stripslashes_deep', $value);
	} elseif ( is_object($value) ) {
		$vars = get_object_vars( $value );
		foreach ($vars as $key=>$data) {
			$value->{$key} = stripslashes_deep( $data );
		}
	} elseif ( is_string( $value ) ) {
		$value = stripslashes($value);
	}
	return $value;
}

	//商品栏目id中文替换数字
	function cate_to($val){
	 	$val1 =  explode('|' , $val);
		$val2 = end($val1);
		$item_cate = D('ItemCate') -> field('name,id') -> where(array('status'=>1)) -> select();
		foreach($item_cate as $i => $val){
			if(trim($val2) == trim($val['name'])){
				$val3 = $val['id'];
			}
		}
		return $val3;
	 }


//截取字符长度
function subtext($text, $length){
    if(mb_strlen($text, 'utf8') > $length)
        return mb_substr($text, 0, $length, 'utf8');
    return $text;
}



/*
    * 中国正常GCJ02坐标---->百度地图BD09坐标
    * 腾讯地图用的也是GCJ02坐标
    * @param double $lat 纬度
    * @param double $lng 经度
    */

    function Convert_GCJ02_To_BD09($lat,$lng){
            $x_pi = 3.14159265358979324 * 3000.0 / 180.0;
            $x = $lng;
            $y = $lat;
            $z =sqrt($x * $x + $y * $y) + 0.00002 * sin($y * $x_pi);
            $theta = atan2($y, $x) + 0.000003 * cos($x * $x_pi);
            $lng = $z * cos($theta) + 0.0065;
            $lat = $z * sin($theta) + 0.006;
            return array('lng'=>$lng,'lat'=>$lat);
    }


    /*
    * 百度地图BD09坐标---->中国正常GCJ02坐标
    * 腾讯地图用的也是GCJ02坐标
    * @param double $lat 纬度
    * @param double $lng 经度
    * @return array();
    */

    function Convert_BD09_To_GCJ02($lat,$lng){
            $x_pi = 3.14159265358979324 * 3000.0 / 180.0;
            $x = $lng - 0.0065;
            $y = $lat - 0.006;
            $z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * $x_pi);
            $theta = atan2($y, $x) - 0.000003 * cos($x * $x_pi);
            $lng = $z * cos($theta);
            $lat = $z * sin($theta);
            return array('lng'=>$lng,'lat'=>$lat);
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
function all_ls($member_id,$totalprices,$item_type,$type,$memos,$order_id=0){
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
    $recharge['order_id'] = $order_id;

	$is_ls = D('MemberRecharge')->add($recharge);
	return $is_ls;
	//人*//数量*//币种的流水//支出状态 //*备注//订单id
}

//无线散下流水单
function all_ls_super($member_id,$totalprices,$item_type,$type,$memos,$order_id=0){
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
	$recharge['order_id']= $order_id;

	$is_ls = D('MemberZyRecharge')->add($recharge);
	return $is_ls;
	//人*//数量*//币种的流水//支出状态 //*备注
}

///商户流水单
function all_ls_shop($member_id,$totalprices,$item_type,$type,$memos,$order_id=0){
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
    $recharge['order_id'] = $order_id;

	$is_ls=D('ShopRecharge')->add($recharge);
	return $is_ls;
	//商户*//数量*//币种的流水//支出状态 //*备注//订单id
}


function getExceltjab($fileName, $headArr, $data){
    //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
    import("Org.Util.PHPExcel");
    import("Org.Util.PHPExcel.Writer.Excel5");
    import("Org.Util.PHPExcel.IOFactory.php");
    $date = date("Y_m_d",time());
    $fileName .= "_{$date}.xls";

    //创建PHPExcel对象，注意，不能少了\

    $objPHPExcel = new \PHPExcel();

    $objProps = $objPHPExcel->getProperties();

    //设置表头
    $key = ord("A");
    //print_r($headArr);exit;
    foreach($headArr as $v){
        $colum = chr($key);
        $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
        $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
        $key += 1;
    }

    $column = 2;
    $objActSheet = $objPHPExcel->getActiveSheet();

    //print_r($data);exit;
    foreach($data as $key => $rows){ //行写入
        $span = ord("A");
        foreach($rows as $keyName=>$value){// 列写入
            $j = chr($span);
            $objActSheet->setCellValue($j.$column, $value.' ');
            $span++;
        }
        $column++;
    }

    $fileName = iconv("utf-8", "gb2312", $fileName);
    //重命名表
    //$objPHPExcel->getActiveSheet()->setTitle('test');
    //设置活动单指数到第一个表,所以Excel打开这是第一个表
    $objPHPExcel->setActiveSheetIndex(0);
    header('Content-Type: application/vnd.ms 

-excel');
    header("Content-Disposition: attachment;filename=\"$fileName\"");
    header('Cache-Control: max-age=0');

    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output'); //文件通过浏览器下载
    exit;
}



    //获取qq头像：   $url获取路径、$dir保存路径
    function get_tx_avatar($url,$dir){
        //方法一：//推荐用该方法
        $header = array(
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:45.0) Gecko/20100101 Firefox/45.0',
            'Accept-Language: zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
            'Accept-Encoding: gzip, deflate',);
        //	 $url='http://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTKkGpNuUhaBniatRsiaG7ksqmhUWzkk40kTRS6icQS7kJcsfxcibQo7vDFcKibr7NHb9YIXiaXsEtLcdL6A/0';
    //  	 $url='http://q1.qlogo.cn/g?b=qq&nk='.qq号码.'&s=100';

        $curl = curl_init();curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_ENCODING, 'gzip');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        $data = curl_exec($curl);$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);curl_close($curl);
        if ($code == 200) {//把URL格式的图片转成base64_encode格式的！
            $imgBase64Code = "data:image/jpeg;base64," . base64_encode($data);
        }
        $img_content=$imgBase64Code;//图片内容
        //echo $img_content;exit;
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $img_content, $result))
        {

            $type = $result[2];//得到图片类型png?jpg?gif?
            $filename = date("YmdHis").rand(1,9).".{$type}";//文件名

            $new_file =  'data/attachment/'.$dir."/".$filename;

            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $img_content)))){
                return $filename;
            }else{
                return 0;
            }
        }
    }