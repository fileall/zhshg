<?php
     
  
/* * 
 * 功能：支付宝页面跳转同步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 *************************页面功能说明*************************
 * 该页面可在本机电脑测试
 * 可放入HTML等美化页面的代码、商户业务逻辑程序代码
 * 该页面可以使用PHP开发工具调试，也可以使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyReturn
 */
require_once("alipay.config.php");
require_once("config.php");
require_once("lib/alipay_notify.class.php");
?>
<!DOCTYPE HTML>
<html>
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyReturn();
//if(!$verify_result) {//验证成功
if($verify_result) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代码
	
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
    //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
	//商户订单号
	$out_trade_no = $_GET['out_trade_no'];
	//支付宝交易号
	$trade_no = $_GET['trade_no'];
	//交易状态
	$trade_status = $_GET['trade_status'];
    if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
    }
    else {
      echo "trade_status=".$_GET['trade_status'];
    }
//		echo  $out_trade_no.'<br/>'; echo $trade_no .'<br/>' ; echo $trade_status; exit;
	session_start();
	$userid=$_SESSION['userid'];
	if(!$userid){
	$userid = $_COOKIE['userid'];	
	}
	//	改变订单支付状态
	$strsql="update jrkj_order set status=1,pay_time= unix_timestamp(now()) where dingdan='".$out_trade_no."'";
	// 执行sql查询
	$result=mysql_db_query($mysql_database, $strsql, $conn);
	$sql = "select jid,integral,nums,did from jrkj_order_list where oid in
(select id from jrkj_order where dingdan = ".$out_trade_no." and status=1)";
	$rs = mysql_query($sql);
	while($res= mysql_fetch_array($rs)){
	if($res['did']){   //是否多规格-是
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
		//改变会员消费记录
	$sql8 = "select integral from jrkj_member where id = ".$userid."";
	$resa= mysql_fetch_array(mysql_query($sql8)) ;
	$sql7 = "INSERT INTO `jrkj_integral` (`uid`,`sid`,`nums`,`title`,`totalintegral`,`changeintegral`,`add_time`,`types`) VALUES ('".$userid."','0','1','购物(订单号:".$out_trade_no.")','".$resa['integral']."','changeintegral'+".$res['integral']*$res['nums'].",unix_timestamp(now()),'0')";
	mysql_query($sql7,$conn);
		}
		
	$uid=$_SESSION['userid'];
	if(!$uid){
		echo "<script>window.location='http://ynfc.0791jr.com/index.php?m=Home&c=memberWap&a=user';</script>";
	}else{
		echo "<script>window.location='http://ynfc.0791jr.com/index.php?m=Home&c=member&a=user';</script>";
	}
	
	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}else {
    //验证失败
    //如要调试，请看alipay_notify.php页面的verifyReturn函数
    echo "验证失败";
	}
?>
        <title>支付宝即时到账交易接口</title>
	</head>
    <body>
    </body>
</html>