<?php
/**
 * 通用通知接口demo
 * ====================================================
 * 支付完成后，微信会把相关支付和用户信息发送到商户设定的通知URL，
 * 商户接收回调信息后，根据需要设定相应的处理流程。
 * 
 * 这里举例使用log文件形式记录回调信息。
*/
	include_once("./log_.php");
	include_once("../WxPayPubHelper/WxPayPubHelper.php");
	require_once("config.php");

    //使用通用通知接口
	$notify = new Notify_pub();

	//存储微信的回调
	$xml = $GLOBALS['HTTP_RAW_POST_DATA'];	
	$notify->saveData($xml);
	
	//验证签名，并回应微信。
	//对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
	//微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
	//尽可能提高通知的成功率，但微信不保证通知最终能成功。
	if($notify->checkSign() == FALSE){
		$notify->setReturnParameter("return_code","FAIL");//返回状态码
		$notify->setReturnParameter("return_msg","签名失败");//返回信息
	}else{
		$notify->setReturnParameter("return_code","SUCCESS");//设置返回码
	}
	$returnXml = $notify->returnXml();
	echo $returnXml;
	
	//==商户根据实际情况设置相应的处理流程，此处仅作举例=======
	
	//以log文件形式记录回调信息
	$log_ = new Log_();
	$log_name="./notify_url.log";//log文件路径
	$log_->log_result($log_name,"【接收到的notify通知】:\n".$xml."\n");

	if($notify->checkSign() == TRUE)
	{
		if ($notify->data["return_code"] == "FAIL") {
			//此处应该更新一下订单状态，商户自行增删操作
			$log_->log_result($log_name,"【通信出错】:\n".$xml."\n");
		}
		elseif($notify->data["result_code"] == "FAIL"){
			//此处应该更新一下订单状态，商户自行增删操作
			$log_->log_result($log_name,"【业务出错】:\n".$xml."\n");
		}
		else{
			//此处应该更新一下订单状态，商户自行增删操作
			session_start();
			$userid=$_SESSION['userid'];
			if(!$userid){
			$userid = $_COOKIE['userid'];	
			}
			//	改变订单支付状态
			$strsql="update jrkj_order set status=1,pay_time= unix_timestamp(now()) where dingdan='".$out_trade_no."'";
			// 执行sql查询
			$result=mysql_db_query($mysql_database, $strsql, $conn);
			$sql = "select jid,integral,nums,did from jrkj_order_list where oid in(select id from jrkj_order where dingdan = ".$out_trade_no." and status=1)";
			$rs = mysql_query($sql);
			while($res= mysql_fetch_array($rs)){
				if($res['did']){  
					//	减去库存
					$sql5 = "UPDATE jrkj_item_attr set attr_num =attr_num- ".$res['nums']." where id =".$res['did']."";
					mysql_query($sql5,$conn);	
					//	增加销量
					$sql6 = "UPDATE jrkj_item_attr set attr_sales =attr_sales+ ".$res['nums']." where id =".$res['did']."";
					mysql_query($sql6,$conn);	
				}	
				//	减去库存
				$sql2 = "UPDATE jrkj_item set inventory =inventory- ".$res['nums']." where id =".$res['jid']."";
				mysql_query($sql2,$conn);	
				//	增加销量
				$sql3 = "UPDATE jrkj_item set sales =sales+ ".$res['nums']." where id =".$res['jid']."";
				mysql_query($sql3,$conn);	
				// 增加积分
				$sql4 = "UPDATE jrkj_member set integral =integral+ ".$res['integral']*$res['nums']." where id =".$userid."";
				mysql_query($sql4,$conn);	
			}
			$uid=$_SESSION['userid'];
			if(!$uid){
				//echo "<script>window.location='http://ynfc.0791jr.com/index.php?m=Home&c=memberWap&a=user';";
				echo "<script>alert('支付成功!');</script>";
			}else{
				echo "<script>window.location='http://ynfc.0791jr.com/index.php?m=Home&c=member&a=user';</script>";
			}
			$log_->log_result($log_name,"【支付成功】:\n".$xml."\n");
		}
		
		//商户自行增加处理流程,
		//例如：更新订单状态
		//例如：数据库操作
		//例如：推送支付完成信息
	}
?>