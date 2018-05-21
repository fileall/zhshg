<?php

	//给未登录用户一个id
	function get_session_id(){
		 $session_id = cookie('yx_session_id');
	     if(!$session_id){
	   	   $str = time().rand(10000, 99999);
	       $session_id =substr_replace($str, 88, 1, 5);
	       cookie('yx_session_id',$session_id,3600*24*7);
	       return $session_id;
	     }else{
	       return $session_id;
	     }
	}
	//判断是否微信
	function is_weixin(){ 
//	if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ){
//		echo "<script >alert('请点击右上角在浏览器中打开！'); ";
//	}	
//	return true;
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
    }
}

	function is_identity(){
    $user = cookie('user_auth');
    if($user['identity'] == 3){
        return true;
    }else{
        return false;
    }
}

//验证是否为商户
function is_business(){
	 $user = cookie('user_auth');
	 if($user['member_type'] == 1){
		 return 1;
	 }else{
		 return 0;
	 }
}

function attach($attach, $type,$path=false) {
    if (false === strpos($attach, 'http://')) {
        //本地附件
        $img_url = __ROOT__ . '/' . C('pin_attach_path') . $type . '/' . $attach;
        $img_path = realpath('.'.__ROOT__).'/' . C('pin_attach_path') . $type . '/' . $attach;
// return $img_url;
        if($attach){
            return $img_url;
        }else{
            return 'theme/default/wap/images/02.jpg';
//          return $img_url;
        }
        //远程附件
        //todo...
    } else {
        //URL链接
        return $attach;
    }
}

function get_week_cn($w){
    $weekarray = array("日","一","二","三","四","五","六");
    return $weekarray[$w];
}


	/** 
   * 验证码检查 
   */  
	function check_verify($code, $id = ""){  
    $verify = new \Think\Verify();  
    return $verify->check($code, $id);  
} 
	
	/**
 * 邮件发送函数
 */
    function sendMail($to, $title, $content) {
		 vendor('PHPMailer.class#phpmailer');
        $mail = new PHPMailer(); //实例化
        $mail->IsSMTP(); // 启用SMTP
        $mail->Host=C('MAIL_HOST'); //smtp服务器的名称（这里以QQ邮箱为例）
        $mail->SMTPAuth = C('MAIL_SMTPAUTH'); //启用smtp认证
        $mail->Username = C('MAIL_USERNAME'); //你的邮箱名
        $mail->Password = C('MAIL_PASSWORD') ; //邮箱密码
        $mail->From = C('MAIL_FROM'); //发件人地址（也就是你的邮箱地址）
        $mail->FromName = C('MAIL_FROMNAME'); //发件人姓名
        $mail->AddAddress($to,"尊敬的客户");
        $mail->WordWrap = 50; //设置每行字符长度
        $mail->IsHTML(C('MAIL_ISHTML')); // 是否HTML格式邮件
        $mail->CharSet=C('MAIL_CHARSET'); //设置邮件编码
        $mail->Subject =$title; //邮件主题
        $mail->Body = $content; //邮件内容
        $mail->AltBody = "这是一个纯文本的身体在非营利的HTML电子邮件客户端"; //邮件正文不支持HTML的备用显示
        return($mail->Send());
	}
	
	//检测是否手机端
	function check_wap() {
	if (isset($_SERVER['HTTP_VIA']))
		return true;
	if (isset($_SERVER['HTTP_X_NOKIA_CONNECTION_MODE']))
		return true;
	if (isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID']))
		return true;
	if (strpos(strtoupper($_SERVER['HTTP_ACCEPT']), "VND.WAP.WML") > 0) {
		// Check whether the browser/gateway says it accepts WML.
		$br = "WML";
	} else {
		$browser = isset($_SERVER['HTTP_USER_AGENT']) ? trim($_SERVER['HTTP_USER_AGENT']) : '';
		if (empty($browser))
			return true;
		$mobile_os_list = array('Google Wireless Transcoder', 'Windows CE', 'WindowsCE', 'Symbian', 'Android', 'armv6l', 'armv5', 'Mobile', 'CentOS', 'mowser', 'AvantGo', 'Opera Mobi', 'J2ME/MIDP', 'Smartphone', 'Go.Web', 'Palm', 'iPAQ');
		$mobile_token_list = array('Profile/MIDP', 'Configuration/CLDC-', '160×160', '176×220', '240×240', '240×320', '320×240', 'UP.Browser', 'UP.Link', 'SymbianOS', 'PalmOS', 'PocketPC', 'SonyEricsson', 'Nokia', 'BlackBerry', 'Vodafone', 'BenQ', 'Novarra-Vision', 'Iris', 'NetFront', 'HTC_', 'Xda_', 'SAMSUNG-SGH', 'Wapaka', 'DoCoMo', 'iPhone', 'iPod');
		$found_mobile = checkSubstrs($mobile_os_list, $browser) || checkSubstrs($mobile_token_list, $browser);
		if ($found_mobile)
			$br = "WML";
		else
			$br = "WWW";
	}
	if ($br == "WML") {
		return true;
	} else {
		return false;
	}
}
function checkSubstrs($list, $str) {
	$flag = false;
	for ($i = 0; $i < count($list); $i++) {
		if (strpos($str, $list[$i]) > 0) {
			$flag = true;
			break;
		}
	}
	return $flag;
}
?>