<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
	        <meta charset="utf-8">
    	<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />	
		<title><?php echo C('pin_site_title');?></title>
		<script src="/theme/mobile/js/fontSize.js"></script>
		<link rel="stylesheet" href="/theme/mobile/css/index.css" />
		<script src="/theme/mobile/js/jquery-1.11.2.min.js"></script>
		<script src="/theme/mobile/js/custom.js"></script>
		<script type="text/javascript" src="/theme/mobile/js/prefixfree.min.js" ></script>
		<!--店铺设置日期插件-->
		<link rel="stylesheet" href="/theme/mobile/css/lCalendar.css" />
		<script src="/theme/mobile/js/lCalendar.js" type="text/javascript" charset="utf-8"></script>
		<!-- 店铺设置插件   -->
		<link rel="stylesheet" href="/theme/mobile/css/LArea.css">
		<script src="/theme/mobile/js/LArea.js"></script>
		<script src="/theme/mobile/js/LAreaData1.js"></script>
		<script src="/theme/mobile/js/LAreaData2.js"></script>

		<!--layui-->
		<script src="/theme/mobile/js/layer/layer.js"></script>
		<script src="/theme/mobile/js/layui/layui.js"></script>
		<link rel="stylesheet" href="/theme/mobile/js/layui/css/layui.css">
		<!--轮播图-->
		<link rel="stylesheet" href="/theme/mobile/css/swiper.css" />
		<script src="/theme/mobile/js/swiper.min.js"></script>
	<!--<script>-->
	<!--var URL = '/index.php?s=/Wallet';-->
	<!--var SELF = '/index.php?s=/wallet/acer_pay/dingdan/188029664392/money/30/oid/79.html&code=071E0rsR0rzCM72ScesR0GvAsR0E0rsn&state=STATE';-->
	<!--var ROOT_PATH = '';-->
	<!--var APP	 =	 '/index.php?s=';-->
	<!--//语言项目-->
	<!--var lang = new Object();-->
    <!--lang.connecting_please_wait = "请稍后...";lang.confirm_title = "提示消息";lang.move = "移动";lang.dialog_title = "消息";lang.dialog_ok = "确定";lang.dialog_cancel = "取消";lang.please_input = "请输入";lang.please_select = "请选择";lang.not_select = "不选择";lang.all = "所有";lang.input_right = "输入正确";lang.plsease_select_rows = "请选择要操作的项目！";lang.upload = "上传";lang.uploading = "上传中";lang.upload_type_error = "不允许上传的文件类型！";lang.upload_size_error = "文件大小不能超过{sizeLimit}！";lang.upload_minsize_error = "文件大小不能小于{minSizeLimit}！";lang.upload_empty_error = "文件为空，请重新选择！";lang.upload_nofile_error = "没有选择要上传的文件！";lang.upload_onLeave = "正在上传文件，离开此页将取消上传！";-->
	<!--</script>-->

</head>
<body class="body-bgColor">
	<!-- 头部 -->
	
	<!-- /头部 -->
	
	<!-- 主体 -->
	

<!--元宝充值支付页面-->
<body class="body-bgColor">
<!--header-->
<div class="header-wrap">
    <div class="header-inner">
        <div class="header-title">支付方式</div>
        <div class="header-left return-prev"><i class="fa fa-angle-left"></i></div>
        <div class="header-right"></div>
    </div>
</div>
<div class="header-space"></div>
<!--header-->
<!--content-->
<div class="content y-payment-content">
    <div class="y-income y-width94 y-bg-white font-28">
        <p class="y-payment-text">订单编号：<span><?php echo ($dingdan); ?></span></p>
        <p class="y-payment-text">支付金额：&yen;<span><?php echo ($money); ?></span></p>
    </div>
    <!--支付方式-->
    <div>
        <p class="y-payment-msg font-28 y-color-999 y-width94">支付方式</p>
        <div class="y-width94 y-bg-white" id="check_pay">
            <div class="ali-cen y-payment-select" data-zftype="1">
                <div class="left y-selectCard-img2">
                    <img src="/theme/mobile/images/icon17.png" />
                </div>
                <div class="y-selectCard-text flex flex1">
                    <p>微信支付</p>
                    <p class="y-selectCard-text-msg">推荐安装微信5.0及以上版本的使用</p>
                </div>
            </div>
            <!--<div class="ali-cen y-payment-select y-payment-select-sed">
                <div class="left y-selectCard-img2">
                    <img src="/theme/mobile/images/ico-zhifubao.png" />
                </div>
                <div class="y-selectCard-text flex flex1">
                    <p>支付宝支付</p>
                    <p class="y-selectCard-text-msg">推荐有支付宝账号的用户使用</p>
                </div>
            </div>-->
            <div class="ali-cen y-payment-select" data-zftype="3">
                <div class="left y-selectCard-img2">
                    <img src="/theme/mobile/images/y_icon35.png" />
                </div>
                <div class="y-selectCard-text flex flex1">
                    <p>工资支付</p>
                    <p class="y-selectCard-text-msg">推荐工资有余额的情况下使用</p>
                </div>
            </div>
        </div>
    </div>
    <!--支付方式-->
    <div class="submit-button y-payment-button">
        <a id="pay-button" class="extract-btn"><span></span>确认支付</a>
    </div>
</div>
<!--content-->
<!--余额支付密码弹窗-->
<div class="payment-window">
    <div class="payment-inner-win">
        <div class="payment-form">
            <div class="y-color-999">
                <div class="payment-left pay-close">x</div>
                <div class="payment-title">输入支付密码</div>
                <div class="payment-title y-border-top">支付金额<?php echo ($money); ?>元</div>
            </div>
            <!--密码区-->
            <div class="pay-input-group row-box">
                <div class="row-flex pass-input-wrap">
                    <div class="pass-input-dot" ><i class="fa fa-circle"></i></div>
                    <input type="password" readonly="readonly" name="xx" value="">
                </div>
                <div class="row-flex pass-input-wrap">
                    <div class="pass-input-dot" ><i class="fa fa-circle"></i></div>
                    <input type="password" readonly="readonly" name="xx" value="">
                </div>
                <div class="row-flex pass-input-wrap">
                    <div class="pass-input-dot" ><i class="fa fa-circle"></i></div>
                    <input type="password" readonly="readonly" name="xx"value="">
                </div>
                <div class="row-flex pass-input-wrap">
                    <div class="pass-input-dot" ><i class="fa fa-circle"></i></div>
                    <input type="password" readonly="readonly" name="xx" value="">
                </div>
                <div class="row-flex pass-input-wrap">
                    <div class="pass-input-dot"><i class="fa fa-circle"></i></div>
                    <input type="password" readonly="readonly" name="xx" value="">
                </div>
                <div class="row-flex pass-input-wrap">
                    <div class="pass-input-dot"><i class="fa fa-circle"></i></div>
                    <input type="password" readonly="readonly" name="xx" value="">
                </div>
            </div>
            <!--密码区-->
            <div class="y-text-right y-width94 y-mar-top20">
                <a class="y-color-red" href="<?php echo U('member/set_pw_pay',['set_pw_pay'=>7,'param'=>$param_json]);?>">忘记密码？</a>
            </div>
            <!--按钮去-->
            <div class="pay-btn-group">
                <button class="pay-button figure" value="1" type="button" >1</button>
                <button class="pay-button figure" value="2" type="button" >2</button>
                <button class="pay-button figure" value="3" type="button" >3</button>
                <button class="pay-button figure" value="4" type="button" >4</button>
                <button class="pay-button figure" value="5" type="button" >5</button>
                <button class="pay-button figure" value="6" type="button" >6</button>
                <button class="pay-button figure" value="7" type="button" >7</button>
                <button class="pay-button figure" value="8" type="button" >8</button>
                <button class="pay-button figure" value="9" type="button" >9</button>
                <button class="pay-button cancel" type="button"><i class="fa fa-arrow-left"></i></button>
                <button class="pay-button figure" value="0" type="button">0</button>
                <button class="pay-button empty" type="button"><i class="fa fa-times"></i></button>
                <div class="clear-float"></div>
            </div>
            <!--按钮去-->
        </div>
    </div>
</div>
<!--余额支付密码弹窗-->
<input type="hidden" value="<?php echo U('wallet/sealing_ye');?>" id="sealing_ye" />
<input type="hidden" value="<?php echo ($oid); ?>" id="oid" />

</body>

	<!-- /主体 -->

	<!-- 底部 -->
			


    <script>
        //点击购买
        $('#pay-button').click(function(){

            //支付类型0.未选择 1=微信，2=支付宝 3余额',
            var zftype=$('#check_pay').find(".y-payment-select-sed").data("zftype");
            //支付方式zf_type 1微信支付 3余额支付在custom.js
            if(zftype==1){
                $.ajax({
                    type:"post",
                    url:"<?php echo U('wallet/sealing_pay');?>",
                    async:true,
                    data:{dingdan:"<?php echo ($dingdan); ?>",zftype:zftype},
                    dataType:'json',
                    success:function(res) {
                        chooseWXPay(res.err_msg);
                    }
                });
            }else{
                $(".payment-window").addClass("active");
                $(".payment-form").addClass("active");
            }
        })

        function chooseWXPay(sting){
            var config = JSON.parse(sting)
            WeixinJSBridge.invoke(
                'getBrandWCPayRequest', {
                    "appId":config.appId,     //公众号名称，由商户传入
                    "timeStamp":config.timeStamp,         //时间戳，自1970年以来的秒数
                    "nonceStr":config.nonceStr, //随机串
                    "package":config.package,
                    "signType":"MD5",         //微信签名方式：
                    "paySign":config.paySign //微信签名
                },
                function(res){
                    if(res.err_msg == "get_brand_wcpay_request:ok" ) {
                        // 使用以上方式判断前端返回,微信团队郑重提示：res.err_msg将在用户支付成功后返回    ok，但并不保证它绝对可靠。
                        layer.msg('充值成功',{icon:1,time:1000},function(){
                            location.href = "/index.php?s=/wallet/wallet.html";
                        });
                    }
                }
            );
        }

        //选择支付方式
        $(".y-payment-select").click(function(){
            $(this).addClass("y-payment-select-sed").siblings().removeClass("y-payment-select-sed")
        })

        //关闭密码区
        $(".pay-close").click(function(){
            $(".payment-window").removeClass("active");
            $(".payment-form").removeClass("active");
        })
    </script>

	<!-- /底部 -->
</body>
</html>