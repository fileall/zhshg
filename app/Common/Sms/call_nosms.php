<?php
require_once('lib/nusoap-for-php5.3.php');

function SendMsg($mobile,$content){
	$send_result=0;

	$client = new nusoap_client('http://www.jianzhou.sh.cn/JianzhouSMSWSServer/services/BusinessService?wsdl', true);
	$client->soap_defencoding = 'utf-8';
	$client->decode_utf8      = false;
	$client->xml_encoding     = 'utf-8';
	$err = $client->getError();
	if ($err) {
		return $send_result;
	}
	$content = iconv("GB2312","UTF-8",$content); //将gb2312转为utf-8再发
	$params = array(
		'account' => 'sdk_ynfc',
		'password' => 'jianzhou',
		'destmobile' =>  $mobile,
		'msgText' => $content."【云农飞菜】",
	);
	
	$result = $client->call('sendBatchMessage', $params, 'http://www.jianzhou.sh.cn/JianzhouSMSWSServer/services/BusinessService');
	if ($client->fault) {
		return $send_result;
	} else {
		$err = $client->getError();
		if ($err) {
			return $send_result;
		} else {
			$send_result=$result['sendBatchMessageReturn'];			
		}
	}
	return $send_result;
}
?>
