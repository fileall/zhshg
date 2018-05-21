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
	<!--var SELF = '/index.php?s=/wallet/coin.html';-->
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
        <div class="header-title">银币</div>
        <div class="header-left return-prev"><i class="fa fa-angle-left"></i></div>
        <div class="header-right"></div>
    </div>
</div>
<div class="header-space"></div>
<!--header-->

<!--content-->
<div class="content">
    <div class="outstanding-box y-goldIngot-bg vertical-center">
        <div class="vertical-auto" style="width: 100%;">
            <div class="outstanding-title font-28">当前银币（个）</div>
            <div class="outstanding-money"><?php echo ($member["silver_coin"]); ?></div>
        </div>
    </div>

    <ul class="y-mar-top20 y-bg-white">
        <li class="function-list-li y-shaped-item">
            <a href="<?php echo U('wallet/account',['type'=>4]);?>">
                <div class="ali-cen">
                    <div class="function-list-left">
                        <div class="function-list-icon"><img src="/theme/mobile/images/y_icon37.png"></div>
                    </div>
                    <div class="jus-bet flex1 y-shaped-item-text">
                        <div class="row-flex flex1">
                            <div class="function-list-name">银币明细</div>
                        </div>

                        <div class="function-list-right">
                            <div class="function-list-arrows"><i class="fa fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            </a>
        </li>
    </ul>
    <div class="employ-explain y-shaped-msg">
        <div class="employ-explain-title">活动规则：</div>
        <ul>
            <li class="employ-explain-txt">一、银币来源：</li>
            <li class="employ-explain-txt">1、每日购买元宝券赠送银币</li>
            <!--<li class="employ-explain-txt">2、线上使用元宝券购物赠送银币</li>-->
            <li class="employ-explain-txt">2、线下使用元宝券消费赠送银币</li>
            <!--<li class="employ-explain-txt">4、线下使用金果券消费赠送银币</li>-->
            <li class="employ-explain-txt">3、金果券线上兑换商品赠送银币</li>
            <li class="employ-explain-txt">4、银楼可使用元宝置换银币</li>
            <li class="employ-explain-txt">5、聚宝盆寄存元宝赠送银币</li>
            <li class="employ-explain-txt">6、推荐好友注册购物获银币奖励</li>
            <li class="employ-explain-txt">7、推荐商家赚银币</li>
            <li class="employ-explain-txt">二、银币用途：</li>
            <li class="employ-explain-txt">1、可充当现金券在商城进行购物</li>
            <li class="employ-explain-txt">2、可作成长为工资和金果</li>

        </ul>
    </div>
</div>
<!--content-->
</body>
    </body>

	<!-- /主体 -->

	<!-- 底部 -->
			


	<!-- /底部 -->
</body>
</html>