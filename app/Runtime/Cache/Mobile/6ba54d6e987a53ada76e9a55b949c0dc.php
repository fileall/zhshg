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
	<!--var SELF = '/index.php?s=/member/myset/is_mine/%2Bis_mine%2B.html';-->
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
        <div class="header-title">设置</div><a href="<?php echo U('Member/mine');?>">
        <div class="header-left"><i class="fa fa-angle-left"></i></div>
        <div class="header-right"></div>
    </a></div>
</div>
<div class="header-space"></div>
<!--header-->

<!--content-->
<div class="content">
    <ul class="set-con-ul">
        <li class="set-con-li"><a href="<?php echo U('set_person');?>">
            <div class="set-con-name">个人信息</div>
            <div class="set-con-icon"><i class="fa fa-angle-right"></i></div>
            <div class="clear-float"></div>
        </a></li>
        <li class="set-con-li"><a href="<?php echo U('opinion');?>">
            <div class="set-con-name">意见反馈</div>
            <div class="set-con-icon"><i class="fa fa-angle-right"></i></div>
            <div class="clear-float"></div>
        </a></li>
        <li class="set-con-li"><a href="javascript:;">
            <div class="set-con-name">客服电话</div>
            <div class="set-con-icon y-color-red"><?php echo C('pin_tel');?></div>
            <div class="clear-float"></div>
        </a></li>
        <li class="set-con-li"><a href="<?php echo U('Index/about',array('type'=>2));?>">
            <div class="set-con-name">关于我们</div>
            <div class="set-con-icon"><i class="fa fa-angle-right"></i></div>
            <div class="clear-float"></div>
        </a></li>

        <li class="set-con-li"><a href="javascript:;" id="is_real">
            <div class="set-con-name">实名认证</div>
            <div class="set-con-icon"><i class="fa fa-angle-right"></i></div>
            <div class="clear-float"></div>
        </a></li>

        <li class="set-con-li"><a href="<?php echo U('set_safety');?>">
            <div class="set-con-name">账户安全</div>
            <div class="set-con-icon"><i class="fa fa-angle-right"></i></div>
            <div class="clear-float"></div>
        </a></li>

    </ul>
    <div class="set-con-li y-mar-top20 y-bg-white y-width94"><a href="<?php echo U('address/location');?>">
        <div class="set-con-name">收货地址管理</div>
        <div class="set-con-icon"><i class="fa fa-angle-right"></i></div>
        <div class="clear-float"></div>
    </a></div>

    <div class="set-con-li y-mar-top20 y-bg-white y-width94 y-exitLogin-button"><a href="javascript:;" id="login_out">
        <div class="y-text-center font-30">退出登录</div>
        <div class="set-con-icon"></div>
        <div class="clear-float"></div>
    </a></div>
</div>
<!--content-->
<!--退出登录弹窗-->
<!--<div class="y-alert-bg y-recordAlert-bg y-hide">-->
    <!--<div class="y-recordAlert y-alert-div">-->
        <!--<div class="y-alert-content y-recordAlert-content">确定退出登录吗？</div>-->
        <!--<div class="jus-bet y-alert-Button y-recordAlert-Button y-text-center">-->
            <!--<p class="y-alert-bankButton y-color-999">取消</p>-->
            <!--<p><a href="javascript:;" class="y-color-red y-block">确定</a></p>-->
        <!--</div>-->
    <!--</div>-->
<!--</div>-->
<!--退出登录弹窗-->
<!--footer-->
<!--footer-->
</body>
<script>
    // $(".y-exitLogin-button").click(function(){
    //     $(".y-recordAlert-bg").show();
    // })
    // $(".y-alert-bankButton").click(function(){
    //     $(".y-recordAlert-bg").hide();
    // })

    //退出登录
    $('#login_out').click(function(){
        layer.confirm('是否退出登录?', {
            icon: 3,
            title: '提示',
        },function(){
            $.get('<?php echo U("login/login_out");?>',function(d){
                if(d.status==1){
//					layer.msg(''+d.msg+'');
                    window.location.href=d.url;
                }
            },"JSON")
        })
    })

    //实名认证
    $('#is_real').click(function(){
        var type="<?php echo ($member['type']); ?>";//2实名认证中, 3已实名认证， 4实名认证失败
        if(type==2){
            layer.msg('实名认证中');

        }else if(type==3){
            layer.msg('已实名认证');
        }else{
            location.href="<?php echo U('member/set_attestation');?>";
        }
    })
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