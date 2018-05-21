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

	if($notify->checkSign() == TRUE){
		if ($notify->data["return_code"] == "FAIL") {
			//此处应该更新一下订单状态，商户自行增删操作
			$log_->log_result($log_name,"【通信出错】:\n".$xml."\n");
		}elseif($notify->data["result_code"] == "FAIL"){
			//此处应该更新一下订单状态，商户自行增删操作
			$log_->log_result($log_name,"【业务出错】:\n".$xml."\n");
		}else{
			
			
			
			
			//此处应该更新一下订单状态，商户自行增删操作
			//商户订单号
			$out_trade_no = $notify->data["out_trade_no"];
			 /*  $total_fee = $notify->data["total_fee"];*/
			
			//$log_->log_result($log_name,"【支付订单号】:\n".$out_trade_no."\n");
			//	改变订单支付状态
			/*$res=strstr($out_trade_no ,  'Z' );*/
			
		/*	if(!$res){*/
			
			$strsql="update jrkj_order set zftype=3,status=2,pay_time= unix_timestamp(now()),update_time= unix_timestamp(now()) where dingdan='".$out_trade_no."'";			
			// 执行sql查询
			$result=mysql_query($strsql, $conn);
			$sql = "select member_id,prices,nums,item_id from jrkj_order_list where oid in(select id from jrkj_order where dingdan = ".$out_trade_no.")";
			$rs = mysql_query($sql);
			while($res= mysql_fetch_array($rs)){
			
				//	减去库存,增加销量
				$sql5 = "UPDATE jrkj_item set inventory =inventory- ".$res['nums'].",sales=sales+".$res['nums']."  where id =".$res['item_id']."";
				mysql_query($sql5);		
					
				$sql6="select activity_status,activity_num from jrkj_item  where id =".$res['item_id']."";
				$rss = mysql_query($sql6);
			    $item= mysql_fetch_array($rss);
				
				if($item['activity_status']==1){
					$sql7 = "UPDATE jrkj_item set activity_num =activity_num-".$res['nums']." where id =".$res['item_id']."";
				    mysql_query($sql7);
					if($item['activity_num']<=$res['nums']){
						$sql7 = "UPDATE jrkj_item set activty_status =0 where id =".$res['item_id']."";
				        mysql_query($sql7);
					}
				}
				
			}
			
			$sql8 = "select member_id,dingdan,totalprices,realprices from jrkj_order where  dingdan = ".$out_trade_no."";
			$rsc = mysql_query($sql8);
			$order= mysql_fetch_array($rsc);
			
			$sql9 = "insert into jrkj_member_recharge(member_id,dingdan,totalprices,realprices,zftype,status,add_time) values(".$order['member_id'].",".$order['dingdan'].",".$order['totalprices'].",".$order['realprices'].",3,1,".time().")";
			mysql_query($sql9);
			//
		/*}else{
				//改变充值订单状态
				
			$strsql="update jrkj_member_recharge set zftype=2,status=1 where dingdan='".$out_trade_no."'";			
			// 执行sql查询
			$result=mysql_db_query($mysql_database, $strsql, $conn);
			
			
			$sql = "select * from jrkj_member_recharge  where dingdan = ".$out_trade_no;
			$rs = mysql_query($sql);
			while($res= mysql_fetch_array($rs)){	
			
				$sql1 = "UPDATE jrkj_member set prices= prices+'".$total_fee."' where id= '".$res['uid']."'";
	           mysql_query($sql1,$conn);
			}
				
				}*/
			cookie('yx_order','');
		    cookie('yx_card','');	
			cookie('dingdan','');	
			$log_->log_result($log_name,"【支付成功】:\n".$xml."\n");
		}
		
		//商户自行增加处理流程,
		//例如：更新订单状态
		//例如：数据库操作
		//例如：推送支付完成信息
	}
?>