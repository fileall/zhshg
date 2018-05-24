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
	<!--var URL = '/index.php?s=/Item';-->
	<!--var SELF = '/index.php?s=/Item/lable/zhm/1.html';-->
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

            <div class="classify-top row-box">

                <div class="">

                    <div class="classify-ret-icon return-prev"><i class="fa fa-angle-left"></i></div>

                </div>

                <div class="row-flex">

                    <a class="search-link" href="<?php echo U('Index/search');?>"></a>

                </div>

            </div>

        </div>

    </div>

    <div class="header-space"></div>

    <!--header-->



    <!--<div class="filtrate-wrap">-->
        <!--<div class="filtrate-inner">-->
            <!--<ul class="commodity-ul">-->
               <!-- <li data-id="price" data-field="price"  class="commodity-li <?php if($order['order'] == 'price'): ?>active<?php endif; ?>">价格排序<i class="fa fa-angle-down"></i></li>
                <li data-id="sales" data-field="sales" class="commodity-li <?php if($order['order'] == 'sales'): ?>active<?php endif; ?>">销量优先<i class="fa fa-angle-down"></i></li>
                <li data-id="<?php echo ($item_cate); ?>" data-field="id" id="id" class="commodity-li <?php if($order == 'id'): ?>active<?php endif; ?>">新品上架</li>-->




                <!--<div class="clear-float"></div>-->
            <!--</ul>-->
        <!--</div>-->
    <!--</div>-->
    <!--<div class="header-space"></div>-->


    <!--content-->
    <div class="content">
        <div class="suspect-wrap">
            <ul class="suspect-ul" id="lists" style="padding-top: 0.1rem;">
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><li class="suspect-li" data-id="<?php echo ($val['id']); ?>">
                        <a href="index.php?m=Mobile&c=Index&a=detail&id=<?php echo ($val['id']); ?>">
                            <div class="suspect-img vertical-center"><div class="vertical-auto">
                                <img src="<?php echo ($val["img"]); ?>"></div></div><div class="suspect-img-name">
                            <?php echo ($val['title']); ?>:<?php echo ($val['intro']); ?></div><div class="suspect-money">
                            <span>￥<?php echo ($val['price']); ?></span><?php echo ($val['sales']); ?>人已付款</div></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                <div class="clear-float"></div>
            </ul>

        </div>
    </div>
    <!--content-->


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

		



			


    <style>.layui-flow-more {

        margin: 10px 0;

        text-align: center;

        color: #999;

        font-size: 14px;padding:25px

    }</style>
    <script src="/theme/mobile/js/layui/layui.js" charset="utf-8"></script>
    <script>
    </script>

	<!-- /底部 -->
</body>
</html>