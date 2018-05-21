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
	<!--var URL = '/index.php?s=/Merchant';-->
	<!--var SELF = '/index.php?s=/Merchant/merchant_details/id/6.html';-->
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
	

    <!--header-->
    <div class="header-wrap">
        <div class="header-inner">
            <div class="header-title">商铺详情</div>
            <div class="header-left return-prev"><i class="fa fa-angle-left"></i></div>
            <div class="header-right"></div>
        </div>
    </div>
    <div class="header-space"></div>
    <!--header-->
    <!--content-->
    <div class="content">
        <!--banner-->
        <?php if(!empty($info["sh_img"])): ?><div class="swiper-container">
                <div class="swiper-wrapper">

                    <!--<?php $_result=explode(',',$info['imgs']);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$img): $mod = ($i % 2 );++$i;?><div class="swiper-slide">
                            <div class="banner-image vertical-center">
                                <div class="all-auto"><img src="<?php echo attach($img, 'merchant');?>"></div>
                            </div>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>-->

                    <?php if(is_array($info['sh_img'])): $i = 0; $__LIST__ = $info['sh_img'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$img): $mod = ($i % 2 );++$i;?><div class="swiper-slide">
                            <div class="banner-image vertical-center">
                                <div class="all-auto"><img src="<?php echo attach($img['img'], 'merchant_img', 1);?>"></div>
                            </div>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div><?php endif; ?>
        <!--banner-->



        <div class="shop-message">
            <ul class="shop-message-ul">
                <li class="shop-message-li">
                    <div class="shop-message-box row-box">
                        <div class="shop-message-name">商铺名称</div>

                        <div class="row-flex">
                            <div class="shop-message-txt"><?php echo ($info["title"]); ?></div>
                            <a class="shop-message-link" href="<?php echo U('merchant_details?map=1&id='.$info['id']);?>">商铺地图</a>
                            <div class="clear-float"></div>
                        </div>
                    </div>
                </li>

                <li class="shop-message-li">
                    <div class="shop-message-box row-box">
                        <div class="shop-message-name">营业时间</div>

                        <div class="row-flex">
                            <div class="shop-message-txt"><?php echo ($info["start"]); ?>-<?php echo ($info["end"]); ?></div>
                            <a class="shop-message-link" href="tel:<?php echo ($info["tel"]); ?>"><span><img src="/theme/mobile/images/icon49.png"></span></a>
                            <div class="clear-float"></div>
                        </div>
                    </div>
                </li>

                <li class="shop-message-li">
                    <div class="shop-message-box row-box">
                        <div class="shop-message-name">商铺类型</div>

                        <div class="row-flex">
                            <div class="shop-message-txt"><?php echo ($info["cate"]["name"]); ?></div>
                            <div class="clear-float"></div>
                        </div>
                    </div>
                </li>

                <li class="shop-message-li">
                    <div class="shop-message-box row-box">
                        <div class="shop-message-name">所在位置</div>

                        <div class="row-flex">
                            <div class="shop-message-txt"><?php echo ($info["address"]); ?></div>
                            <div class="clear-float"></div>
                        </div>
                    </div>
                </li>

                <li class="shop-message-li">
                    <div class="shop-message-box row-box">
                        <div class="row-flex">
                            <div class="store-txt"><span class="zhi">支</span>支持 <?php echo ($info["zftype"]); ?></div>
                            <div class="store-txt"><span class="bei">倍</span>赠送<?php echo ($info["set_coin"]); ?>倍银币</div>
                        </div>
                    </div>
                </li>

                <li class="shop-message-li">
                    <div class="shop-message-box row-box">
                        <div class="shop-message-name">商铺简介</div>

                        <div class="row-flex">
                            <div class="shop-message-txt">
                                <?php echo ($info["intro"]); ?>
                            </div>
                            <div class="clear-float"></div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

        <div class="" style="width: 100%;margin-top: 0.2rem;">
            <?php echo html_entity_decode($info['info'], ENT_QUOTES, 'UTF-8');?>
        </div>
    </div>
    <!--content-->
    <link rel="stylesheet" href="/theme/mobile/css/swiper.css" />
    <script src="/theme/mobile/js/swiper.min.js"></script>
    <script>
        //banner
        var swiper = new Swiper('.swiper-container',
            {
                pagination: '.swiper-pagination',
                paginationClickable: true,
                autoplay:3000,
                autoplayDisableOnInteraction:false,
                loop:true
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