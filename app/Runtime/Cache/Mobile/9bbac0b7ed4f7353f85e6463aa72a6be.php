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
	<!--var SELF = '/index.php?s=/WalletOther/basin.html';-->
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
        <div class="header-title">聚宝盆</div>
        <div class="header-left return-prev"><i class="fa fa-angle-left"></i></div>
        <div class="header-right y-header-right">
            <a href="<?php echo U('WalletOther/basin_account');?>">寄存明细</a>
        </div>
    </div>
</div>
<div class="header-space"></div>
<!--header-->

<!--content-->
<div class="content">
    <!--公告栏-->
    <!--<div class="jus-bet y-proclaim y-basin-proclaim">
        <div class="flex1 y-over-hide y-bg-img y-basin-proclaim-div">
            <div>
                <p class="y-proclaim-text">理财新选择！</p>
            </div>
        </div>
        <span class="y-proclaim-close" id="y-close">X</span>
    </div>-->
    <!--公告栏-->
    <div class="basin-top-col y-basin-top-col">
        <a class="y-banner" href="<?php echo U('WalletOther/basin_account');?>">
            <div class="y-banner-title y-bg-img">元宝寄存总数(个)</div>
            <div class="y-banner-num"><?php echo ($member['gold_acer_jc']); ?></div>
        </a>
    </div>
    <div class="y-income y-width94 y-bg-white">
        <p class="y-color-999 font-28">寄存7天/100元宝可收益银币(个)</p>
        <p class="y-income-num"><?php echo 100*C('pin_jbp_bs')?></p>
    </div>
    <div class="employ-explain y-shaped-msg">
        <div class="employ-explain-title y-mar-top20">活动规则：</div>
        <ul class="font-28">
            <li class="employ-explain-txt">1、每次寄存必须为100的倍数；</li>
            <li class="employ-explain-txt">2、寄存后即可获得相应倍数的银币；</li>
            <li class="employ-explain-txt">3、寄存满7天后即提取自由使用</li>
            <li class="employ-explain-txt">3、可重复或者每天分次进行寄存，不限寄存次数</li>
        </ul>
    </div>
</div>
<!--content-->

    <!--footer-->
    <div class="footer-space"></div><!--间距-->
    <div class="footer-wrap jus-bet">
        <div class="footer-inner y-footer-inner inner-area w100">
            <a href="<?php echo U('WalletOther/basin_account',['status'=>2]);?>" class="check-link y-check-link y-bg-white y-color-red ">元宝提取</a>
        </div>
        <div class="footer-inner y-footer-inner inner-area w100">
            <a href="<?php echo U('WalletOther/basin_in');?>" class="check-link y-check-link">元宝寄存</a>
        </div>
    </div>
    <!--footer-->
</body>


	<!-- /主体 -->

	<!-- 底部 -->
			


    <script>
        $("#y-close").click(function(){
            $(".y-proclaim").hide();
        })
    </script>

	<!-- /底部 -->
</body>
</html>