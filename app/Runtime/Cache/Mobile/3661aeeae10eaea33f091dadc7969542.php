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
	<!--var URL = '/index.php?s=/WalletOther';-->
	<!--var SELF = '/index.php?s=/WalletOther/silver_store.html';-->
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
	

<body class="body-bgColor">
<!--header-->
<div class="header-wrap">
    <div class="header-inner">
        <div class="header-title">银楼</div>
        <div class="header-left return-prev"><i class="fa fa-angle-left"></i></div>
        <div class="header-right"></div>
    </div>
</div>
<div class="header-space"></div>
<!--header-->

<!--content-->
<div class="content y-mar-top20">
    <form action="" name="" method="post">
        <div class="merchant-form row-box y-bg-white">
            <div class="merchant-currency font-28">银币购买：</div>
            <div class="row-flex">
                <div class="merchant-form-input vertical-center">
                    <div class="vertical-auto" style="width: 100%;"><input type="number" placeholder="请输入元宝个数" id="nums"></div>
                </div>
            </div>
        </div>

        <div class="village-txt y-village-txt font-28">注：1元宝=<?php echo C('pin_yl_bs');?>银币</div>
        <div class="enter-form-btn y-enter-form-btn">
            <button type="button">确认</button>
        </div>
    </form>
    <div class="employ-explain y-shaped-msg">
        <div class="employ-explain-title y-mar-top20">活动规则：</div>
        <ul class="font-28">
            <!--1、银楼仅针对掌柜及以上级别会员使用；-->
            <!--2、每周二、周三为银楼休息日，其他时间均开放；??-->
            <!--3、使用元宝在银楼置换银币，根据会员等级每日可置换的元宝数量限额不同；（我要升级）-->
            <li class="employ-explain-txt">1、1元宝=<?php echo C('pin_yl_bs');?>银币</li>
            <li class="employ-explain-txt">2、银楼仅针对掌柜及以上级别会员使用；</li>
            <li class="employ-explain-txt">3、使用元宝在银楼置换银币，根据会员等级每日可置换的元宝数量限额不同</li>
        </ul>
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
                <a class="y-color-red" href="<?php echo U('member/set_pw_pay',['set_pw_pay'=>12]);?>">忘记密码？</a>
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
<input type="hidden" value="2" id="origin_type" />
<input type="hidden" value="<?php echo U('WalletOther/silver_store');?>" id="sealing_ye" />

</body>

	<!-- /主体 -->

	<!-- 底部 -->
			


<script>

    $('.enter-form-btn').click(function(){
        var nums =Number($('#nums').val());
        if(nums==0){
            layer.msg('请填写元宝数量！',{icon:0,time:1000})
            return false;
        }
        if(parseInt(nums) < 0){
            layer.msg('请合理填写元宝数量！',{icon:0,time:1000})
            return false;
        }
        $('.y-border-top').html('支付金额'+nums+'元');

        $(".payment-window").addClass("active");
        $(".payment-form").addClass("active");
    })

//    $(".enter-form-btn").click(function(){
//        $(".payment-window").addClass("active");
//        $(".payment-form").addClass("active");
//    })
    $(".pay-close").click(function(){
        $(".payment-window").removeClass("active");
        $(".payment-form").removeClass("active");
    })
</script>

	<!-- /底部 -->
</body>
</html>