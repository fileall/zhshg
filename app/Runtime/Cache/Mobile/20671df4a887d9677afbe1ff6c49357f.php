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
	<!--var URL = '/mobile.php?s=/Order';-->
	<!--var SELF = '/mobile.php?s=/order/order_pay/oid/52.html';-->
	<!--var ROOT_PATH = '';-->
	<!--var APP	 =	 '/mobile.php?s=';-->
	<!--//语言项目-->
	<!--var lang = new Object();-->
    <!--lang.connecting_please_wait = "请稍后...";lang.confirm_title = "提示消息";lang.move = "移动";lang.dialog_title = "消息";lang.dialog_ok = "确定";lang.dialog_cancel = "取消";lang.please_input = "请输入";lang.please_select = "请选择";lang.not_select = "不选择";lang.all = "所有";lang.input_right = "输入正确";lang.plsease_select_rows = "请选择要操作的项目！";lang.upload = "上传";lang.uploading = "上传中";lang.upload_type_error = "不允许上传的文件类型！";lang.upload_size_error = "文件大小不能超过{sizeLimit}！";lang.upload_minsize_error = "文件大小不能小于{minSizeLimit}！";lang.upload_empty_error = "文件为空，请重新选择！";lang.upload_nofile_error = "没有选择要上传的文件！";lang.upload_onLeave = "正在上传文件，离开此页将取消上传！";-->
	<!--</script>-->

</head>
<body class="body-bgColor">
	<!-- 头部 -->
	
	<!-- /头部 -->
	
	<!-- 主体 -->
	

	
	<body class="body-bgColor">
		<!--header-->
		<div class="header-wrap">
			<div class="header-inner">
				<div class="header-title">支付订单</div>
				<div class="header-left return-prev"><i class="fa fa-angle-left"></i></div>
				<div class="header-right"></div>
			</div>	
		</div>
		<div class="header-space"></div>
		<!--header-->
		
		<!--content-->
		<div class="content">
			<div class="payment">
				<div class="pay-message y-pay-message">
					<div class="y-width94 jus-bet font-30">
						<p>订单金额</p>
                        <p class="y-color-red">&yen;<?php echo ($list['order_amount']); ?></p>
					</div>
				</div>
				<div class="pay-message y-pay-message">
					<div class="y-width94 jus-bet font-30">
						<p>实际支付</p>
						<p class="y-color-red" ><span id="pay_real">&yen;<?php echo ($list['order_amount']); ?></span></p>
					</div>
				</div>
				
				<div class="pay-way">
					<div class="inner-area">选择支付方式</div>
				</div>

				<!--支付选项-->
				<div class="y-width94 y-bg-white pay-wrap">
					<ul>
						<li class="pay-li active" data-html="&yen;<?php echo ($list['order_amount']); ?>">
							<label form="yuanbao">
								<div class="row-box">
									<div class="pay-icon">
										<div class="pay-icon-img"><img src="/theme/mobile/images/icon28.png"></div>
									</div>
									
									<div class="row-flex">
										<div class="pay-name">元宝支付</div>
										<!--<div class="pay-name-t">元宝支付满100减10</div>-->
										<div class="clear-float"></div>
									</div>
									
									<div class="pay-input-box">
										<div class="pay-input-inner active" data-pay_type="1"><input name="pay-name" type="radio" checked="checked" id="yuanbao"></div>
									</div>
								</div>
							</label>
						</li>

						<li class="pay-li active" data-html="<?php echo ($list['acer']); ?>元宝+<?php echo ($list['coin']); ?>银币">
							<label form="yyb">
								<div class="row-box">
									<div class="pay-icon">
										<div class="pay-icon-img"><img src="/theme/mobile/images/y_icon42.png"></div>
									</div>
									
									<div class="row-flex">
										<div class="pay-name">元宝+银币支付</div>
										<!--<div class="pay-name-t">银宝支付满100减10</div>-->
										<div class="clear-float"></div>
									</div>
									
									<div class="pay-input-box">
										<div class="pay-input-inner" data-pay_type="3"><input name="pay-name" type="radio" id="yyb"></div>
									</div>
								</div>
							</label>
						</li>
						
						<!--<li class="pay-li active">-->
							<!--<label form="jinguo">-->
								<!--<div class="row-box">-->
									<!--<div class="pay-icon">-->
										<!--<div class="pay-icon-img"><img src="/theme/mobile/images/icon29.png"></div>-->
									<!--</div>-->
									<!---->
									<!--<div class="row-flex">-->
										<!--<div class="pay-name">金果支付</div>-->
										<!--&lt;!&ndash;<div class="pay-name-t">金果支付满100减10</div>&ndash;&gt;-->
										<!--<div class="clear-float"></div>-->
									<!--</div>-->
									<!---->
									<!--<div class="pay-input-box">-->
										<!--<div class="pay-input-inner" data-pay_type="2"><input name="pay-name" type="radio" id="jinguo"></div>-->
									<!--</div>-->
								<!--</div>-->
							<!--</label>-->
						<!--</li>-->
						<!--微信支付-->
						<!--<li class="pay-li active">-->
							<!--<label form="weixing">-->
								<!--<div class="row-box">-->
									<!--<div class="pay-icon">-->
										<!--<div class="pay-icon-img"><img src="images/icon17.png"></div>-->
									<!--</div>-->
									<!--<div class="row-flex">-->
										<!--<div class="pay-name">微信支付</div>-->
										<!--<div class="pay-name-t"></div>-->
										<!--<div class="clear-float"></div>-->
									<!--</div>-->
									<!--<div class="pay-input-box">-->
										<!--<div class="pay-input-inner"><input name="pay-name" type="radio" id="weixing"></div>-->
									<!--</div>-->
								<!--</div>-->
							<!--</label>-->
						<!--</li>-->
					</ul>
				</div>
				<!--支付选项-->

				<div class="submit-button"><button type="button" id="now_pay">确定支付</button></div>
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
                    <div class="payment-title y-border-top">支付金额0元</div>
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
					<a class="y-color-red" href="<?php echo U('member/set_pw_pay',['set_pw_pay'=>9,'param'=>$oid]);?>">忘记密码？</a>
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
        </div>
    </div>
    <!--余额支付密码弹窗-->
		<input type="hidden" value="<?php echo U('order/order_pay');?>" id="sealing_ye" />
		<input type="hidden" value="3" id="origin_type" />
		<input type="hidden" value="<?php echo ($oid); ?>" id="oid" />


		<!--footer-->
		<!--footer-->
	</body>

	<!-- /主体 -->

	<!-- 底部 -->
			


	<script>
		$('.pay-li').click(function(){
			var html=$(this).data('html');
            $('#pay_real').html(html);
		})

	    $(".submit-button").click(function(){
	        if($("#weixing").prop("checked") == true){
	            //选择微信支付
	        }else{
	            $('.payment-title.y-border-top').html('支付'+$('#pay_real').html());
	            $(".payment-window").addClass("active");
                $(".payment-form").addClass("active");
	        }
        })
        $(".pay-close").click(function(){
            $(".payment-window").removeClass("active");
            $(".payment-form").removeClass("active");
        })

        //点击购买(微信)
        // $('#now_pay').click(function(){
        //     //金额
        //     var prices = 0;
        //     //订单类型1元宝充值2微信充值
        //     var item_type = 0;
			// //商品订单支付方式1金元宝2金果3金元宝+银币
        //     var zf_type = $(".y-bg-white > ul > li").find(".pay-input-inner.active").data("pay_type");
        //     //生成订单 zf_type 1微信支付 2支付宝 3(元宝&&金果&&元宝+银币)支付在custom.js 4
        // })
	</script>

	<!-- /底部 -->
</body>
</html>