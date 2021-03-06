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
	<!--var URL = '/index.php?s=/Goldfruitshop';-->
	<!--var SELF = '/index.php?s=/Goldfruitshop/gold_shop.html';-->
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
        <div class="header-title">金果商城</div>
        <div class="header-left return-prev"><i class="fa fa-angle-left"></i></div>
    </div>
</div>
<div class="header-space"></div>
<!--header-->

<!--content-->
<div class="content">
    <!--banner-->
    <div class="y-goldShop-banner y-over-hide">
        <img src="/theme/mobile/images/y_img2.png"/>
    </div>
    <!--banner-->
    <!--兑换记录-->
    <?php if($uid != 0): ?><div class="y-goldShop-record jus-ali y-bg-white y-width94">
        <div class="flex1">
            <p>我的金果总额&nbsp;<span class="y-color-red font-28"><?php echo ($gold['gold_fruit']); ?>个</span></p>
            <p class="font-22 y-mar-top10"><a class="y-color-999" href="<?php echo U('wallet/account',['type'=>3]);?>">查看金果明细&nbsp;&gt;</a></p>
        </div>
        <a href="<?php echo U('y_goldShopRecord');?>"><div class="y-goldShop-record-right y-border y-color-666">兑换记录</div></a>
    </div><?php endif; ?>
    <!--商品列表-->
    <div id="LAY_demo1" class="y-goldShop-list jus-bet flex-warp">
    </div>
</div>
<!--content-->

<!--footer-->
<!--footer-->
</body>
<script>
    $(".y-goldShop-item:even").addClass("y-border-right")
    layui.use('flow', function(){
        var flow = layui.flow;
        flow.load({
            elem: '#LAY_demo1' //流加载容器
            ,done: function(page, next){ //执行下一页的回调
              $.get("/index.php?s=/Goldfruitshop/gold_shop.html",{p:page},function (res) {
                  next(res.list, page < res.length); //假设总页数为 10
              });
            }
        });
    });

</script>


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

		



			


	<!-- /底部 -->
</body>
</html>