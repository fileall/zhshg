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
	<!--var URL = '/index.php?s=/Member';-->
	<!--var SELF = '/index.php?s=/Member/mine.html';-->
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
        <div class="header-title">我的</div>
        <a href="<?php echo U('index/index');?>">
            <div class="header-left return-prev"><i class="fa fa-angle-left"></i></div>
        </a>
        <div class="header-right">
            <a class="mine-set-link aaa" href="javascript:;"></a>
        </div>
    </div>
</div>
<div class="header-space"></div>
<!--header-->
<!--弹出窗-->
<div class="y-alert-bg y-recordAlert-bg y-hide">
    <div class="y-minealert-div">
        <div class="y-minealert-content">
            <div class="font-30 y-minealert-content-top">
                <p class="y-minealert-text y-bg-white">联系电话：18279145648</p>
            </div>
            <div class="font-24 y-color-999">需要退换货麻烦线下联系我们</div>
        </div>
        <p class="y-mine-alertButton y-bg-red">我知道了</p>
    </div>
</div>
<!--弹出窗-->

<!--content-->
<div class="content">
    <!--person-->
    <div class="person-wrap y-person-wrap">
        <div class="person-inner row-box">
            <div class="row-flex"><a href="<?php echo U('set_person');?>">
                <div class="person-photo vertical-center y-person-photo">
                    <div class="vertical-auto"><img src="<?php echo attach($member['avatar'],'avatar');?>"></div>
                </div>
                <div class="person-message">
                    <div class="person-name"><?php echo ($member["nickname"]); ?></div>
                    <div class="y-person-grade y-text-center"><?php echo ($mem["vips"]); ?></div>
                </div>
            </a></div>


            <div class="popularize vertical-center">
                <div class="vertical-auto">
                    <a href="<?php echo U('member/popularize_link',array('what_ewm'=>1));?>">
                        <div class="popularize-img"><img src="<?php echo attach($member['ewm'],'ewm');?>"/></div>
                        <div class="popularize-name">推广码</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!--person-->



    <!--order-->
    <div class="order-wrap">
        <div class="order-all"><a href="<?php echo U('Orderlist/y_order',array('status'=>1));?>">
            <div class="order-all-title y-order-all-title">我的订单</div>
            <div class="order-all-icon y-order-all-icon y-bg-img">全部订单</div>
            <div class="clear-float"></div>
        </a></div>
        <ul class="order-ul">
            <li class="order-li">
                <a href="<?php echo U('Orderlist/y_order',array('status'=>2));?>">
                    <?php if(empty($count1)): else: ?>
                        <span class="y-order-msg"><?php echo ($count1); ?></span><?php endif; ?>
                    <div class="order-icon"><img src="/theme/mobile/images/icon32.png"><div class="order-link-numbers"></div></div>
                    <div class="order-name">待付款</div>
                </a>
            </li>

            <li class="order-li">
                <a href="<?php echo U('Orderlist/y_order',array('status'=>3));?>">
                    <?php if(empty($count2)): else: ?>
                        <span class="y-order-msg"><?php echo ($count2); ?></span><?php endif; ?>
                    <div class="order-icon"><img src="/theme/mobile/images/icon33.png"><div class="order-link-numbers"></div></div>
                    <div class="order-name">待发货</div>
                </a>
            </li>

            <li class="order-li">
                <a href="<?php echo U('Orderlist/y_order',array('status'=>4));?>">
                    <?php if(empty($count3)): else: ?>
                        <span class="y-order-msg"><?php echo ($count3); ?></span><?php endif; ?>
                    <div class="order-icon"><img src="/theme/mobile/images/icon34.png"><div class="order-link-numbers"></div></div>
                    <div class="order-name">待收货</div>
                </a>
            </li>

            <li class="order-li">
                <a href="<?php echo U('Orderlist/y_order',array('status'=>5));?>">
                    <?php if(empty($count4)): else: ?>
                        <span class="y-order-msg"><?php echo ($count4); ?></span><?php endif; ?>
                    <div class="order-icon"><img src="/theme/mobile/images/icon35.png"><div class="order-link-numbers"></div></div>
                    <div class="order-name">待评价</div>
                </a>
            </li>

            <li id="y-refund" class="order-li">
                <a href="javascript:;">
                    <div class="order-icon"><img src="/theme/mobile/images/icon36.png"><div class="order-link-numbers"></div></div>
                    <div class="order-name">退款/售后</div>
                </a>
            </li>
            <div class="clear-float"></div>
        </ul>

        <div class="y-exponent-col">
            <div class="exponent-inner y-exponent-inner row-box">
                <div class="row-flex">
                    <div class="exponent-txt y-color-red flex">
                        <p>今日财富指数：<?php echo ($selive_coin_day); ?></p>
                        <div class="y-exponent-txt-icon">
                            <img src="/theme/mobile/images/y_icon51.png"/>
                        </div>
                    </div>
                </div>
                <div class="exponent-right">
                    <div class="exponent-date y-color-red"><?php echo date('Y-m-d');?>
                </div>
            </div>
        </div>
    </div>
    <!--order-->
    <!--<a href="<?php echo U('agent/agent');?>">服务中心</a>-->
    <!--money-->
    <!--money-->

    <!--功能列表-->
    <div class="function-list">
        <ul class="jus-bet flex-warp">

            <li class="function-list-li y-function-list-li"><a href="<?php echo U('Wallet/wallet');?>">
                <div class="function-list-icon y-function-list-icon"><img src="/theme/mobile/images/y_icon1.png"></div>
                <div class="function-list-name">我的钱包</div>
            </a></li>

            <li class="function-list-li y-function-list-li"><a href="<?php echo U('merchant/merchant');?>">
                <div class="function-list-icon y-function-list-icon"><img src="/theme/mobile/images/y_icon2.png"></div>
                <div class="function-list-name">我是商家</div>
            </a></li>
            <li class="function-list-li y-function-list-li"><a href="<?php echo U('index/exploit');?>">
                <div class="function-list-icon y-function-list-icon"><img src="/theme/mobile/images/y_icon3.png"></div>
                <div class="function-list-name">平台介绍</div>
            </a></li>
            <li class="function-list-li y-function-list-li"><a href="<?php echo U('index/exploit');?>">
                <div class="function-list-icon y-function-list-icon"><img src="/theme/mobile/images/y_icon4.png"></div>
                <div class="function-list-name">我的收藏</div>
            </a></li>
            <li class="function-list-li y-function-list-li"><a href="<?php echo U('Item/gwc');?>">
                <div class="function-list-icon y-function-list-icon"><img src="/theme/mobile/images/y_icon5.png"></div>
                <div class="function-list-name">购物车</div>
            </a></li>
            <li class="function-list-li y-function-list-li"><a href="<?php echo U('index/exploit');?>">
                <div class="function-list-icon y-function-list-icon"><img src="/theme/mobile/images/y_icon6.png"></div>
                <div class="function-list-name">同城配送</div>
            </a></li>
            <li class="function-list-li y-function-list-li"><a href="<?php echo U('index/exploit');?>">
                <div class="function-list-icon y-function-list-icon"><img src="/theme/mobile/images/y_icon7.png"></div>
                <div class="function-list-name">我要推荐</div>
            </a></li>
            <li class="function-list-li y-function-list-li"><a href="<?php echo U('myset');?>" class="mine-toset">
                <div class="function-list-icon y-function-list-icon"><img src="/theme/mobile/images/y_icon8.png"></div>
                <div class="function-list-name">设置</div>
            </a></li>
        </ul>
    </div>
    <!--功能列表-->
</div>
<!--content-->
<!--footer-->
<block name="footer">
	<!-- /主体 -->

	<!-- 底部 -->
	

		<!--footer-->

		<div class="footer-space"></div><!--间距-->

		<div class="footer-wrap">

			<div class="footer-inner">

				<div class="footer-nav row-box">

					<a class="row-flex footer-link one <?php if((ACTION_NAME) == "index"): ?>active<?php endif; ?>" href="<?php echo U('Index/index');?>">首页</a>

					<a class="row-flex footer-link two <?php if((ACTION_NAME) == "cate"): ?>active<?php endif; ?>" href="<?php echo U('Index/cate');?>">分类</a>

					<a class="row-flex footer-link three <?php if((ACTION_NAME) == "activity"): ?>active<?php endif; ?>" href="javascript:;">活动</a>

					<a class="row-flex footer-link four  <?php if((ACTION_NAME) == "merchant_list"): ?>active<?php endif; ?>" href="<?php echo U('Merchant/merchant_list');?>">周边</a>

					<a class="row-flex footer-link five <?php if((ACTION_NAME) == "mine"): ?>active<?php endif; ?>" href="<?php echo U('Member/mine');?>">我的</a>

				</div>

			</div>

		</div>

		<!--footer-->

		



			


    <script type="text/javascript" src="/theme/mobile/js/jquery.cookie.js"></script>
    <script>
        $(function(){
            //右上角设置
            $(".aaa").click(function(){
                $.cookie('is_mine',1);
                var is_mine = $.cookie('is_mine');
                location.href='<?php echo U("member/myset",array("is_mine"=>'+is_mine+'));?>';
            })

            $(".mine-toset").click(function(){
                $.cookie('is_mine',2);
                var is_mine = $.cookie('is_mine');
                location.href='<?php echo U("member/myset",array("is_mine"=>'+is_mine+'));?>';
            })

        })
    </script>
    <script>
        $("#y-refund").click(function(){
            $(".y-alert-bg").fadeIn(300);
            $(".y-minealert-div").addClass("active");
        })
        $(".y-mine-alertButton").click(function(){
            $(".y-alert-bg").fadeOut(300);
            $(".y-minealert-div").removeClass("active");
        })
    </script>

	<!-- /底部 -->
</body>
</html>