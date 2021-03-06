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
	<!--var SELF = '/index.php?s=/wallet/fruit.html';-->
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

            <div class="header-title">我的金果</div>
            <a href="<?php echo U('wallet/wallet');?>">
                <div class="header-left"><i class="fa fa-angle-left"></i></div>
            </a>
            <div class="header-right"></div>

        </div>

    </div>

    <div class="header-space"></div>

    <!--header-->



    <!--content-->

    <div class="content">

        <div class="outstanding-box vertical-center">

            <div class="vertical-auto" style="width: 100%;">

                <div class="outstanding-title">我的金果</div>

                <div class="outstanding-money">￥<?php echo ($member["gold_fruit"]); ?></div>
            </div>

        </div>

        <div class="outstanding-wrap">

            <a class="outstanding-link active" href="<?php echo U('wallet/pay_to_merchant',array('transfer_type'=>2));?>" id="zf">金果支付</a>

            <a class="outstanding-link" href="<?php echo U('wallet/fruit_recycle');?>">金果回购</a>

            <a class="outstanding-link" href="<?php echo U('wallet/transfer_to_friend',['transfer_type'=>2]);?>">金果转好友</a>
            
            <a class="outstanding-link" href="<?php echo U('fruit_cdkey');?>">金果券</a>

        </div>



        <div class="notecase-tis">金果明细</div>

        <ul class="notecase-ul" id="ul">
        </ul>
        <!--<div class="more-text">查看更多></div>-->

    </div>

    </body>


	<!-- /主体 -->

	<!-- 底部 -->
			



    <script type="text/javascript" src="/theme/mobile/js/jquery.cookie.js"></script>

    <script>

//        $("#zf").click(function(){
//
//            $.cookie('zftype',3);
//
//            var zftype = $.cookie('zftype');
//
//            location.href='<?php echo U("wallet/w_shaped_pay",array("zftype"=>'+zftype+'));?>';
//
//        })

    </script>
    <script>

        //流加载
        layui.use('flow', function(){
            var flow = layui.flow;
            var type = '<?php echo ($type); ?>';//币种1工资2金元宝3金果',
            flow.load({
                elem: '#ul' //指定列表容器
//		         ,scrollElem: '#'//滚动所在元素
                ,done: function(page, next){ //到达临界点（默认滚动触发），触发下一页
//		             var lis = [];
                    $.get('<?php echo U("wallet/ajax_bz_liu");?>',{page:page,type:type}, function(res){
                        next(res[1], page < res[0]);
                    }, 'json');
                }

            });

        });

    </script>


	<!-- /底部 -->
</body>
</html>