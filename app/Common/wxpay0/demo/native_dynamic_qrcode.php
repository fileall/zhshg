<?php
/**
 * Native（原生）支付-模式二-demo
 * ====================================================
 * 商户生成订单，先调用统一支付接口获取到code_url，
 * 此URL直接生成二维码，用户扫码后调起支付。
 * 
*/
	include_once("../WxPayPubHelper/WxPayPubHelper.php");
	require_once("config.php");
	
	//使用统一支付接口
	$unifiedOrder = new UnifiedOrder_pub();
	
	

	$out_trade_no =$_POST['str'];

	$strsql="update jrkj_order set zftype=2 where dingdan='".$out_trade_no."'";	
	$ss=mysql_query($strsql);
	//sssssssssssssssssssssss
	
	$sql = "SELECT realprices,totalprices,dingdan FROM jrkj_order where dingdan= '".$out_trade_no."' ";	
	//$sql = "SELECT realprices,totalprices,dingdan FROM jrkj_order where 1=1 order by id desc limit 1";
	//echo $sql."<br>";
	$rs = mysql_query($sql);
	if($total = mysql_fetch_array($rs)){
		//print_r($total);
		$total_fee = $total['realprices']*100;
		$out_trade_no = $total['dingdan'];
	}

	//echo $total_fee."<br>";
	$WIDsubject='云农飞菜';
	
	
	//设置统一支付接口参数
	//设置必填参数
	//appid已填,商户无需重复填写
	//mch_id已填,商户无需重复填写
	//noncestr已填,商户无需重复填写
	//spbill_create_ip已填,商户无需重复填写
	//sign已填,商户无需重复填写
	$unifiedOrder->setParameter("body","$WIDsubject");//商品描述
	//自定义订单号，此处仅作举例
	//$timeStamp = time();
	//$out_trade_no = WxPayConf_pub::APPID."$timeStamp";
	$unifiedOrder->setParameter("out_trade_no","$out_trade_no");//商户订单号 
	$unifiedOrder->setParameter("total_fee","$total_fee");//总金额
	$unifiedOrder->setParameter("notify_url",WxPayConf_pub::NOTIFY_URL);//通知地址 
	$unifiedOrder->setParameter("trade_type","NATIVE");//交易类型
	//非必填参数，商户可根据实际情况选填
	//$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号  
	//$unifiedOrder->setParameter("device_info","XXXX");//设备号 
	//$unifiedOrder->setParameter("attach","XXXX");//附加数据 
	//$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
	//$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间 
	//$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记 
	//$unifiedOrder->setParameter("openid","XXXX");//用户标识
	//$unifiedOrder->setParameter("product_id","XXXX");//商品ID
	
	//获取统一支付接口结果
	$unifiedOrderResult = $unifiedOrder->getResult();
	//var_dump($unifiedOrderResult["code_url"]);
	//商户根据实际情况设置相应的处理流程
	if ($unifiedOrderResult["return_code"] == "FAIL") 
	{
		//商户自行增加处理流程
		echo "通信出错：".$unifiedOrderResult['return_msg']."<br>";
	}
	elseif($unifiedOrderResult["result_code"] == "FAIL")
	{
		//商户自行增加处理流程
		echo "错误代码：".$unifiedOrderResult['err_code']."<br>";
		echo "错误代码描述：".$unifiedOrderResult['err_code_des']."<br>";
	}
	elseif($unifiedOrderResult["code_url"] != NULL)
	{
		//从统一支付接口获取到code_url
		$code_url = $unifiedOrderResult["code_url"];
		
		//商户自行增加处理流程
		//......
	}

?>


<!DOCTYPE HTML>
<html>
<head>
	<meta charset="UTF-8">
	<title>微信安全支付</title>
</head>
<body style="font-family: '微软雅黑';">
	
<div style="border: 1px solid #ddd; height: 480px; width: 1024px; margin: 0 auto; padding: 20px; position: relative;">
	<div style="border-bottom: 1px dashed #ddd; line-height: 40px;">订单编号：<?php echo $out_trade_no; ?></div>
	<div style="border-bottom: 1px dashed #ddd; line-height: 40px; text-align: right; font-size: 16px; color: #333;">金额：<span style="color:red; font-size: 20px;"><?php echo $total_fee/100; ?>元</span></div>
	<div style="color: red; line-height: 40px;">温馨提示：您已经成功下单，请尽快完成支付</div>
	<div style=" background: #eee; line-height: 40px; text-indent: 24px;">付款方式：</div>
	
	<div id="qrcode" style="margin-left: 100px; margin-top: 50px;">
	</div>
	<p style="margin-left: 108px; margin-top: 0; margin-bottom: 0; line-height: 30px;">请使用微信扫描</p>
    <p style="margin-left: 102px;margin-top: 0;margin-bottom: 0;  line-height: 30px;">二维码以完成支付</p>
	<div style="position: absolute; left:310px; bottom: 20px;">
		<img src="/theme/default//images/iphone.png" width="230" height="290" />
	</div>
    <div align="left">
		<a href="http://ynfc.0791jr.com/index.php?m=Home&c=member&a=myOrder">返回会员中心</a>
	</div>
	<!--<div align="center">
		<p>订单号：<?php echo $out_trade_no; ?></p>
	</div>
	<div align="center">
		<form  action="./order_query.php" method="post">
			<input name="out_trade_no" type='hidden' value="<?php echo $out_trade_no; ?>">
		    <button type="submit" >查询订单状态</button>
		</form>
	</div>
	<br>
	<div align="center">
		<form  action="./refund.php" method="post">
			<input name="out_trade_no" type='hidden' value="<?php echo $out_trade_no; ?>">
			<input name="refund_fee" type='hidden' value="1">
		    <button type="submit" >申请退款</button>
		</form>
	</div>
	<br>
	<div align="center">
		<a href="../index.php">返回首页</a>
	</div>-->
</div>
</body>
<script src="./qrcode.js"></script>
<script>
if(<?php echo $unifiedOrderResult["code_url"] != NULL; ?>){
	var url = "<?php echo $code_url;?>";
	//参数1表示图像大小，取值范围1-10；参数2表示质量，取值范围'L','M','Q','H'
	var qr = qrcode(10, 'M');
	qr.addData(url);
	qr.make();
	var wording=document.createElement('p');
	wording.innerHTML = "";
	var code=document.createElement('DIV');
	code.innerHTML = qr.createImgTag();
	var element=document.getElementById("qrcode");
	element.appendChild(wording);
	element.appendChild(code);
}
</script>
</html>