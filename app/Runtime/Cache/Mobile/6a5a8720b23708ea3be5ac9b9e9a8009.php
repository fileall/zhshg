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
	<!--var SELF = '/index.php?s=/Goldfruitshop/gold_shop_detail/item_id/2908/id/5460.html';-->
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
            <div class="header-title">商品详情</div>
            <div class="header-left return-prev"><i class="fa fa-angle-left"></i></div>
        </div>
    </div>
    <div class="header-space"></div>
    <!--header-->

    <!--content-->
    <div class="content">

        <!--banner-->
        <div class="y-width94">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <?php if(is_array($url)): $i = 0; $__LIST__ = $url;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div class="swiper-slide"><a href="javascreipt:;">
                            <div class="banner-image vertical-center">
                                <div class="all-auto"><img src="<?php echo attach($v['url'],item);?>"></div>
                            </div>
                        </a>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
        <!--banner-->
        <div class="y-width94">
            <div class="font-24 ellipsis2">
                <?php echo ($item_info['title']); ?>
            </div>
            <div class="jus-bet ali-cen y-mar-top20">
                <p class="font-28 y-color-red">所需金果：<span class="font-36"><?php echo ($item_info['gold_fruit']); ?>个</span></p>
                <p class="font-24 y-color-999">剩余库存：<span class="y-color-red"><?php echo ($item_info['attr_value']); ?>件</span></p>
            </div>
            <div class="y-mar-top60 y-color-999">
                <p class="font-28 y-color-333">规则说明：</p>
                <p class="y-mar-top20">1.兑换成功后，不能取消订单；</p>
                <p class="y-mar-top10">2.所有商品正品保障，但不支持退货；</p>
                <p class="y-mar-top10">3.所有商品正品保障，但不支持退货；</p>
            </div>
            <?php if(($uid != 0) AND ($item_info['attr_value'] != 0)): ?><a href="<?php echo U('Goldfruitshop/y_goldShopOrder',array('id'=>$item_info['id'],'item_id'=>$item_id));?>">
                    <?php else: ?>
                    <?php if($item_info['attr_value'] != 0): ?><a href="<?php echo U('Login/enter');?>"><?php else: endif; endif; ?>
            <?php if($item_info['attr_value'] == 0): ?><div class="y-goldShopDetails-button font-30" style="background: grey">立即兑换</div></a>
                <?php else: ?>
                <div class="y-goldShopDetails-button y-bg-red font-30" >立即兑换</div></a><?php endif; ?>

        </div>
    </div>
    <!--content-->

    <!--footer-->
    <!--footer-->
    </body>
    <link rel="stylesheet" href="/theme/mobile/css/swiper.css" />
    <script src="/theme/mobile/js/swiper.min.js"></script>
    <script>
        //banner
        var swiper = new Swiper('.swiper-container',
            {
                pagination: '.swiper-pagination',
                paginationType:'fraction',
                loop:true,
                paginationFractionRender:function(swiper,currentClassName,totalClassName) {
                    return  '<div class="y-text-right">'+
                        '<div class="y-pagination-bg">'+
                        '<span class="' + currentClassName + ' font-32"></span>' +
                        '/' +
                        '<span class="' + totalClassName + '"></span>'+
                        '</div>'+
                        '</div>';
                }
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