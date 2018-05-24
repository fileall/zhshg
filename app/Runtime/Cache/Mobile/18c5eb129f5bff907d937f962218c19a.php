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
	<!--var SELF = '/index.php?s=/wallet/scan_pay/merchant_id/3.html&code=0211m3U50semYI1JFnX50AfcU501m3UT&state=STATE';-->
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
	


<body>
<!--header-->
<div class="header-wrap">
    <div class="header-inner">
        <div class="header-title">支付</div>
        <div class="header-left return-prev"><i class="fa fa-angle-left"></i></div>
    </div>
</div>
<div class="header-space"></div>
<!--header-->

<!--content-->
<div class="content y-contentPay2-content">

    <div class="y-bg-white y-mar-top40">
        <div class="y-codePay2-header">
            <div class="y-codePay2-img">
                <img class="w100" src="<?php echo attach($shop['img'],'avatar');?>"/>
            </div>
            <p class="y-text-center font-30 y-mar-top20"><?php echo ($shop["title"]); ?></p>
        </div>
        <div class="y-width94">
            <p class="font-28">付款金额：</p>
            <div class="y-codePay2-money ali-cen y-mar-top20 ">
                <p>&yen;</p>
                <input class="flex1 y-mar-left20 y-codePay2-input" type="number" id="nums"/>
            </div>
            <!--<input class="y-mar-top20 font-30 y-width100 y-border-none" type="text" maxlength="20" placeholder="添加备注（20字以内）" id="memos"/>-->
        </div>

    </div>
    <div class="submit-button y-mar-top40">
        <a class="extract-btn pay-button" href="javascript:;">确认支付</a>
    </div>
    <div class="y-codePay2-msg y-text-center y-width100 y-color-ccc">钱实时到对方账户，无法退款</div>
</div>
<!--content-->
<!--余额支付密码弹窗-->
<div class="payment-window">
    <div class="payment-inner-win">
        <!--支付方式-->
        <div class="y-codepay-alert y-color-999 y-bg-white">
            <div class="payment-left pay-close">x</div>
            <div class="payment-title">确认付款</div>
            <div class="payment-num y-border-top y-color-333">&yen;0.00</div>
            <div class="y-width94 y-alertpay-text">
                <!--<div class="jus-bet font-30">-->
                    <!--<p>订单信息</p>-->
                    <!--<p>收款</p>-->
                <!--</div>-->
                <div class="y-border-top">选择付款方式</div>
                <div class="y-border-top">
                    <ul class="y-codepay-ul">
                        <li class="pay-li">
                            <label form="yuanbao">
                                <div class="row-box">
                                    <div class="pay-icon">
                                        <div class="pay-icon-img"><img src="/theme/mobile/images/icon28.png"></div>
                                    </div>

                                    <div class="row-flex">
                                        <div class="pay-name">元宝</div>
                                        <!--<div class="pay-name-t">元宝支付满100减10</div>-->
                                        <div class="clear-float"></div>
                                    </div>

                                    <div class="pay-input-box">
                                        <div class="pay-input-inner active" data-zftype="1"><input name="pay-name" type="radio" checked="checked" id="yuanbao"></div>
                                    </div>
                                </div>
                            </label>
                        </li>

                        <li class="pay-li">
                            <label form="yyb">
                                <div class="row-box">
                                    <div class="pay-icon">
                                        <div class="pay-icon-img"><img src="/theme/mobile/images/icon29.png"></div>
                                    </div>

                                    <div class="row-flex">
                                        <div class="pay-name">金果</div>
                                        <!--<div class="pay-name-t">银宝支付满100减10</div>-->
                                        <div class="clear-float"></div>
                                    </div>

                                    <div class="pay-input-box">
                                        <div class="pay-input-inner" data-zftype="2"><input name="pay-name" type="radio" id="yyb"></div>
                                    </div>
                                </div>
                            </label>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="submit-button y-mar-top40">
                <a class="extract-btn pay-button2" href="javascript:;">确认支付</a>
            </div>
        </div>
        <!--支付方式-->
        <!--支付密码-->
        <div class="payment-form">
            <div class="y-color-999">
                <div class="payment-left pay-close">x</div>
                <div class="payment-title">输入支付密码</div>
                <div class="payment-title y-border-top">支付金额0元</div>
            </div>
            <!--密码区-->
            <div class="pay-input-group row-box">
                <div class="row-flex pass-input-wrap">
                    <div class="pass-input-dot"><i class="fa fa-circle"></i></div>
                    <input type="password" readonly="readonly" value="">
                </div>
                <div class="row-flex pass-input-wrap">
                    <div class="pass-input-dot"><i class="fa fa-circle"></i></div>
                    <input type="password" readonly="readonly" value="">
                </div>
                <div class="row-flex pass-input-wrap">
                    <div class="pass-input-dot"><i class="fa fa-circle"></i></div>
                    <input type="password" readonly="readonly" value="">
                </div>
                <div class="row-flex pass-input-wrap">
                    <div class="pass-input-dot"><i class="fa fa-circle"></i></div>
                    <input type="password" readonly="readonly" value="">
                </div>
                <div class="row-flex pass-input-wrap">
                    <div class="pass-input-dot"><i class="fa fa-circle"></i></div>
                    <input type="password" readonly="readonly" value="">
                </div>
                <div class="row-flex pass-input-wrap">
                    <div class="pass-input-dot"><i class="fa fa-circle"></i></div>
                    <input type="password" readonly="readonly" value="">
                </div>
            </div>
            <!--密码区-->
            <div class="y-text-right y-width94 y-mar-top20">
                <!--<a href="y_codePaySuccess.html">点这跳转支付成功</a>-->
                <a class="y-color-red" href="<?php echo U('member/set_pw_pay',['set_pw_pay'=>3,'param'=>$shop['id']]);?>">忘记密码？</a>
            </div>
            <!--按钮去-->
            <div class="pay-btn-group">
                <button class="pay-button figure" value="1" type="button">1</button>
                <button class="pay-button figure" value="2" type="button">2</button>
                <button class="pay-button figure" value="3" type="button">3</button>
                <button class="pay-button figure" value="4" type="button">4</button>
                <button class="pay-button figure" value="5" type="button">5</button>
                <button class="pay-button figure" value="6" type="button">6</button>
                <button class="pay-button figure" value="7" type="button">7</button>
                <button class="pay-button figure" value="8" type="button">8</button>
                <button class="pay-button figure" value="9" type="button">9</button>
                <button class="pay-button pay-button2" type="button"></button>
                <button class="pay-button figure" value="0" type="button">0</button>
                <button class="pay-button empty pay-button2" type="button"><i class="fa fa-times"></i></button>
                <div class="clear-float"></div>
            </div>
            <!--按钮去-->
        </div>
        <!--支付密码-->
    </div>

</div>
<!--余额支付密码弹窗-->
<input type="hidden" value="<?php echo ($shop['tel']); ?>" id="mobile" />
<input type="hidden" value="4" id="origin_type" />
<input type="hidden" value="<?php echo U('wallet/check_pay');?>" id="sealing_ye" />

</body>

	<!-- /主体 -->

	<!-- 底部 -->
			


<script type="text/javascript">


    //显示选择支付方式
    $(".pay-button").click(function(){

        var nums =$('#nums').val();
        if(!nums){
            layer.msg('请填写金额！',{icon:0,time:1000})
            return false;
        }
        if(parseInt(nums) < 0){
            layer.msg('请合理填写元金额！',{icon:0,time:1000})
            return false;
        }
        $('.payment-num.y-border-top').html('&yen;'+nums);
        $('.payment-title.y-border-top').html('支付金额'+nums+'元');


        //判断是否已经显示弹出窗，防止事件冒泡
        if (!$(".payment-window").is(".active")) {
            $(".payment-window").addClass("active");
            $(".y-codepay-alert").addClass("active");
        }else{
            return;
        }
    })
    //显示支付密码框
    $(".pay-button2").click(function(){
        $(".y-codepay-alert").removeClass("active");
        $(".payment-form").addClass("active");
    })
    //支付弹窗全部隐藏
    $(".pay-close").click(function(){
        $(".payment-window").removeClass("active");
        $(".y-codepay-alert").removeClass("active");
        $(".payment-form").removeClass("active");
    })
</script>

	<!-- /底部 -->
</body>
</html>