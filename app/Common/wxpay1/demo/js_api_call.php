<?php	

/**
 * JS_API支付demo
 * ====================================================
 * 在微信浏览器里面打开H5网页中执行JS调起支付。接口输入输出数据格式为JSON。
 * 成功调起支付需要三个步骤：
 * 步骤1：网页授权获取用户openid
 * 步骤2：使用统一支付接口，获取prepay_id
 * 步骤3：使用jsapi调起支付
*/
	
	include_once("../WxPayPubHelper/WxPayPubHelper.php"); 
	include_once("config.php");    
	
//	$sql = "SELECT realprices,totalprices,dingdan FROM jrkj_order where 1=1 order by id desc limit 1";
//	echo $sql."<br>";
//	$rs = mysql_query($sql);
//	while($total = mysql_fetch_array($rs)){
//		$total_fee = $total['realprices'];
//	}
	
	$total_fee = 200;
	echo $total_fee."<br>";
	$WIDsubject='臻惠生活馆';
	
	
	
	//echo __FILE__ ;
	//使用jsapi接口
	$jsApi = new \JsApi_pub();
	//echo $_GET['code']."<br>";
	//=========步骤1：网页授权获取用户openid============
	//通过code获得openid
	if (!isset($_GET['code'])){
		echo "111111111111111<br>";
		//触发微信返回code码
		$url = $jsApi->createOauthUrlForCode(WxPayConf_pub::JS_API_CALL_URL);
		//echo $url."<br>";
		Header("Location: $url"); 
	}else{
		echo "222222222222222<br>";
		//获取code码，以获取openid
	    $code = $_GET['code'];
	    
		$jsApi->setCode($code);
		var_dump($code);
		$openid = $jsApi->getOpenId();
		var_dump($openid);
	}
	//echo "code==".$code."<br>";

	//echo $_COOKIE['uid']."-------".$_REQUEST['dingdan']."<br>";
	/*$weixin =  file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx551a993002bf18af&secret=8d500c266f15164c2649b6c957c9d8ec&code=".$code."&grant_type=authorization_code");//通过code换取网页授权access_token
	$jsondecode = json_decode($weixin); //对JSON格式的字符串进行编码
	$array = get_object_vars($jsondecode);//转换成数组
	$openid = $array['openid'];//输出openid*/
		
	//echo $openid."<br>";
	//=========步骤2：使用统一支付接口，获取prepay_id============
	//使用统一支付接口

	
	$unifiedOrder = new UnifiedOrder_pub();
	
	
	//session_start();
	
	//$WIDout_trade_no = $_GET['WIDout_trade_no'] ;
	
	//$uid=$_REQUEST['uid'];
	//$out_trade_no =$_REQUEST['dingdan'];
	//$sql = "SELECT realprices,totalprices FROM jrkj_order where uid=".$uid." and dingdan= '".$out_trade_no."' ";
	

	
	//设置统一支付接口参数
	//设置必填参数
	//appid已填,商户无需重复填写
	//mch_id已填,商户无需重复填写
	//noncestr已填,商户无需重复填写
	//spbill_create_ip已填,商户无需重复填写
	//sign已填,商户无需重复填写
	$unifiedOrder->setParameter("openid","$openid");//商品描述
	$unifiedOrder->setParameter("body","$WIDsubject");//商品描述
	//自定义订单号，此处仅作举例
	$timeStamp = time();
	$out_trade_no = WxPayConf_pub::APPID."$timeStamp";	
	
	$unifiedOrder->setParameter("out_trade_no","$out_trade_no");//商户订单号 
	$unifiedOrder->setParameter("total_fee","$total_fee");//总金额
	$unifiedOrder->setParameter("notify_url",WxPayConf_pub::NOTIFY_URL);//通知地址 
	$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
	//非必填参数，商户可根据实际情况选填
	//$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号  
	//$unifiedOrder->setParameter("device_info","XXXX");//设备号 
	//$unifiedOrder->setParameter("attach","XXXX");//附加数据 
	//$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
	//$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间 
	//$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记 
	//$unifiedOrder->setParameter("openid","XXXX");//用户标识
	//$unifiedOrder->setParameter("product_id","XXXX");//商品ID
	
	
	$prepay_id = $unifiedOrder->getPrepayId();
	//$prepay_id = 'wx201510291509395522657a690389285100';
	//var_dump($prepay_id);
	//echo $prepay_id."---prepay_id<br>";
	//=========步骤3：使用jsapi调起支付============
	$jsApi->setPrepayId($prepay_id);

	$jsApiParameters = $jsApi->getParameters();
	//echo $jsApiParameters."<br>";
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <title>微信安全支付</title>

	<script type="text/javascript">

		//调用微信JS api 支付
		function jsApiCall()
		{
			WeixinJSBridge.invoke(
				'getBrandWCPayRequest',
				<?php echo $jsApiParameters; ?>,
				function(res){
					WeixinJSBridge.log(res.err_msg);
					//alert(res.err_code+res.err_desc+res.err_msg);
				}
			);
		}

		function callpay()
		{
			if (typeof WeixinJSBridge == "undefined"){
				//alert('111111');
			    if( document.addEventListener ){
			        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
			    }else if (document.attachEvent){
			        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
			        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
			    }				
			}else{
				//alert('222222');
			    jsApiCall();
			}
		}
	</script>
</head>
<body>
	</br></br></br></br>
	<div align="center">
		<button style="width:210px; height:30px; background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" onClick="callpay()" >去支付</button>
	</div>
</body>
</html>