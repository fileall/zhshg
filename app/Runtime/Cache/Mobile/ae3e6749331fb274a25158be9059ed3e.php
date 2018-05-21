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
	<!--var URL = '/index.php?s=/Index';-->
	<!--var SELF = '/index.php?s=/Index/search.html';-->
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
				<div class="header-title">搜索</div>
				<div class="header-left return-prev"><i class="fa fa-angle-left"></i></div>
				<div class="header-right"></div>
			</div>	
		</div>
		<div class="header-space"></div>
		<!--header-->
			

		<div class="grabble-wrap">
			<div class="grabble-inner row-box">
				<div class="row-flex">
					<div class="row-flex vertical-center grabble-input">
						<div class="vertical-auto" style="width: 100%;"><input type="text" name="search" placeholder="请输入商品"></div>
					</div>
				</div>
				<div class="grabble-icon">
					<div class="grabble-icon-btn"><a href="javascript:;">
						<button type="button"><i class="fa fa-search"></i></button>
					</a></div>
				</div>
			</div>
		</div>
		<div style="width: 100%;height: 0.8rem;"></div>
		
		<!--content-->
		<div class="content">
			<div class="records-wrap">
				<div class="records-title">热门搜索</div>
				<div class="hot-content">
					<ul class="hot-con-ul">
						<?php if(is_array($searchKeywords)): $i = 0; $__LIST__ = $searchKeywords;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><li class="hot-con-li"><a href="<?php echo U('commodity',array('search'=>$val['keywords']));?>"><?php echo ($val['keywords']); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
						<div class="clear-float"></div>
					</ul>
				</div>
				
				<!--<div class="records-title">历史搜索</div>
				<ul class="records-ul">
					<li class="row-box records-li">
						<div class="row-flex">
							<a href="commodity.html"><div class="records-name">历史搜索标题</div></a>
						</div>
						<div class="records-delete">
							<i class="fa fa-trash-o"></i>
						</div>
					</li>
						
					<li class="row-box records-li">
						<div class="row-flex">
							<a href="commodity.html"><div class="records-name">历史搜索标题</div></a>
						</div>
						<div class="records-delete">
							<i class="fa fa-trash-o"></i>
						</div>
					</li>
						
					<li class="row-box records-li">
						<div class="row-flex">
							<a href="commodity.html"><div class="records-name">历史搜索标题</div></a>
						</div>
						<div class="records-delete">
							<i class="fa fa-trash-o"></i>
						</div>
					</li>
						
					<li class="row-box records-li">
						<div class="row-flex">
							<a href="commodity.html"><div class="records-name">历史搜索标题</div></a>
						</div>
						<div class="records-delete">
							<i class="fa fa-trash-o"></i>
						</div>
					</li>
				</ul>-->
			</div>
			
		</div>
		<!--content-->

	<!-- /主体 -->

	<!-- 底部 -->
			


<script type="text/javascript">
    $(".fa-search").click(function(){
        var search = $('input[name="search"]').val()
        if (search ==""){
            layer.msg('请输入搜索内容')
            return;
        }
        //var urls = '/sh/index.php/Classify/subclassify';
        location.href = "<?php echo U('commodity');?>&search="+search;
    });
</script>

	<!-- /底部 -->
</body>
</html>