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
	<!--var SELF = '/index.php?s=/wallet/wallet.html';-->
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
        <div class="header-title">我的钱包</div>
        <a href="<?php echo U('Member/mine');?>">
            <div class="header-left return-prev"><i class="fa fa-angle-left"></i></div>
        </a>
        <div class="header-right"></div>
    </div>
</div>
<div class="header-space"></div>
<!--header-->
<!--content-->
<div class="content">
    <!--我的钱包-->
    <div class="burse-wrap row-box">
        <div class="row-flex">
            <a class="burse-link one" href="<?php echo U('member/set_pw_pay');?>">支付密码</a>
        </div>
        <div class="row-flex">
            <a class="burse-link two" href="<?php echo U('wallet/w_ingotA');?>">元宝</br><?php echo ($member['gold_acer']); ?></a>
        </div>
        <div class="row-flex">
            <a class="burse-link three" href="<?php echo U('member/w_bank');?>">银行卡</a>
        </div>
    </div>
    <!--我的钱包-->


    <!---币种-->
    <div class="y-moneyType function-list">
        <ul class="jus-bet flex-warp">
            <li class="function-list-li y-function-list-li"><a href="<?php echo U('wallet/coin');?>">
                <div class="function-list-icon y-function-list-icon"><img src="/theme/mobile/images/y_icon42.png"></div>
                <div class="function-list-name">银币</div>
            </a></li>

            <li class="function-list-li y-function-list-li"><a href="<?php echo U('wallet/balance');?>">
                <div class="function-list-icon y-function-list-icon"><img src="/theme/mobile/images/y_icon13.png"></div>
                <div class="function-list-name">悦买工资</div>
            </a></li>
            <li class="function-list-li y-function-list-li"><a href="<?php echo U('wallet/fruit');?>">
                <div class="function-list-icon y-function-list-icon"><img src="/theme/mobile/images/y_icon14.png"></div>
                <div class="function-list-name">金果</div>
            </a></li>
            <li class="function-list-li y-function-list-li"><a href="<?php echo U('WalletOther/basin');?>">
                <div class="function-list-icon y-function-list-icon"><img src="/theme/mobile/images/y_icon15.png"></div>
                <div class="function-list-name">聚宝盆</div>
            </a></li>
            <li class="function-list-li y-function-list-li"><a href="<?php echo U('WalletOther/silver_store');?>">
                <div class="function-list-icon y-function-list-icon"><img src="/theme/mobile/images/y_icon16.png"></div>
                <div class="function-list-name">银楼</div>
            </a></li>
            <li class="function-list-li y-function-list-li"><a href="<?php echo U('index/exploit');?>">
                <div class="function-list-icon y-function-list-icon"><img src="/theme/mobile/images/y_icon17.png"></div>
                <div class="function-list-name">实体众筹</div>
            </a></li>
            <li class="function-list-li y-function-list-li"><a href="<?php echo U('upgrade/upgrade');?>">
                <div class="function-list-icon y-function-list-icon"><img src="/theme/mobile/images/y_icon18.png"></div>
                <div class="function-list-name">我要升级</div>
            </a></li>
            <li class="function-list-li y-function-list-li"><a href="<?php echo U('Wallet/vip_list',array('type'=>0));?>">
                <div class="function-list-icon y-function-list-icon"><img src="/theme/mobile/images/y_icon19.png"></div>
                <div class="function-list-name">我的会员</div>
            </a></li>
            <li class="function-list-li y-function-list-li"><a href="<?php echo U('index/exploit');?>">
                <div class="function-list-icon y-function-list-icon"><img src="/theme/mobile/images/y_icon20.png"></div>
                <div class="function-list-name">优惠券</div>
            </a></li>
            <li class="function-list-li y-function-list-li"><a href="<?php echo U('index/exploit');?>">
                <div class="function-list-icon y-function-list-icon"><img src="/theme/mobile/images/y_icon21.png"></div>
                <div class="function-list-name">悦买信用</div>
            </a></li>
            <li class="function-list-li y-function-list-li"><a href="<?php echo U('index/exploit');?>">
                <div class="function-list-icon y-function-list-icon"><img src="/theme/mobile/images/y_icon22.png"></div>
                <div class="function-list-name">我要赊账</div>
            </a></li>
            <li class="function-list-li y-function-list-li"><a href="<?php echo U('index/exploit');?>">
                <div class="function-list-icon y-function-list-icon"><img src="/theme/mobile/images/y_icon23.png"></div>
                <div class="function-list-name">悦买借支</div>
            </a></li>
        </ul>
    </div>
    <!---币种-->
    <!--服务-->
    <div class="y-bg-white">
        <div class="y-service">
            <p class="y-service-title font-28">钱包服务</p>
            <ul class="jus-bet y-border-top">
                <li class="function-list-li y-function-list-li y-function-list-li-first"><a href="<?php echo U('index/exploit');?>">
                    <div class="function-list-icon y-function-list-icon"><img src="/theme/mobile/images/y_icon24.png"></div>
                    <div class="function-list-name">悦买账单</div>
                </a></li>

                <li class="function-list-li y-function-list-li"><a href="<?php echo U('index/exploit');?>">
                    <div class="function-list-icon y-function-list-icon"><img src="/theme/mobile/images/y_icon25.png"></div>
                    <div class="function-list-name">悦买分</div>
                </a></li>
                <li class="function-list-li y-function-list-li"><a href="<?php echo U('index/exploit');?>">
                    <div class="function-list-icon y-function-list-icon"><img src="/theme/mobile/images/y_icon26.png"></div>
                    <div class="function-list-name">悦买保险</div>
                </a></li>
            </ul>
        </div>
    </div>
    <!--服务-->
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

		



			


    <script>
        $("#y-close").click(function(){
            $(".y-proclaim").hide();
        });
    </script>

	<!-- /底部 -->
</body>
</html>