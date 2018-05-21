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
	<!--var SELF = '/index.php?s=/Wallet/vip_list/type/0.html';-->
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
        <div class="header-title">我的会员</div><a href="<?php echo U('Wallet/wallet');?>">
        <div class="header-left return-prev"><i class="fa fa-angle-left"></i></div></a>
        <div class="header-right"></div>
    </div>
</div>
<div class="header-space"></div>
<!--header-->

<!--content-->
<div class="content">
    <div class="y-tab-title y-vipList-title">
        <div class="y-tab-title-text flex1 y-tab-title-sed" id="demo1">推荐的商家</div>
        <div class="y-tab-title-text flex1" id="demo2">推荐的会员</div>
    </div>
    <div>


        <div class="y-tab-vipContent">
            <div class="jus-bet y-width94 y-color-999">
                <div class="y-tab-title-text">店铺图标</div>
                <div class="y-tab-title-text y-width75">会员账号</div>
                <div class="y-tab-title-text">注册时间</div>
            </div>
            <div class="y-bg-white y-vip-list y-width94" id="ul0">
                <!--<?php if(is_array($recommend)): $i = 0; $__LIST__ = $recommend;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>-->
                <!--<div class="y-tab-title y-couponRecord-item">-->
                    <!--<div class="y-tab-title-text">-->
                        <!--<div class="y-vip-icon">-->
                            <!--<img src="<?php echo attach($val['avatar'],'avatar');?>"/>-->
                        <!--</div>-->
                    <!--</div>-->
                    <!--<div class="y-tab-title-text y-width75"><?php echo ($val['tel']); ?></div>-->
                    <!--<div class="y-tab-title-text font-24"><?php echo date('Y-m-d' ,$val['add_time']);?></div>-->
                <!--</div>-->

                <!--<?php endforeach; endif; else: echo "" ;endif; ?>-->
            </div>
        </div>
        <div class="y-tab-vipContent" style="display: none;">
            <div class="y-tab-vipContent">
                <div class="jus-bet y-width94 y-color-999">
                    <div class="y-tab-title-text">会员头像</div>
                    <div class="y-tab-title-text y-width75">会员账号</div>
                    <div class="y-tab-title-text">注册时间</div>
                </div>
                <div class="y-bg-white y-vip-list y-width94" id="ul1">
                    <!--<?php if(is_array($recommend1)): $i = 0; $__LIST__ = $recommend1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>-->
                    <!--<div class="y-tab-title y-couponRecord-item">-->
                        <!--<div class="y-tab-title-text">-->
                            <!--<div class="y-vip-icon">-->
                                <!--<img src="<?php echo attach($val['avatar'],'avatar');?>"/>-->
                            <!--</div>-->
                        <!--</div>-->
                        <!--<div class="y-tab-title-text y-width75"><?php echo ($val['mobile']); ?></div>-->
                        <!--<div class="y-tab-title-text font-24"><?php echo date('Y-m-d' ,$val['reg_time']);?></div>-->
                    <!--</div>-->
                    <!--<?php endforeach; endif; else: echo "" ;endif; ?>-->

                </div>
            </div>
        </div>
    </div>
</div>
<!--content-->

<!--footer-->
<!--footer-->
</body>

	<!-- /主体 -->

	<!-- 底部 -->
			


<script>
    var type = '<?php echo ($type); ?>';//0商家1会员',
    $(".y-vipList-title .y-tab-title-text").click(function(){
        $(this).addClass("y-tab-title-sed").siblings().removeClass("y-tab-title-sed");
        $(".y-tab-vipContent").eq($(this).index()).show().siblings().hide();
        type = $(this).index();
        //流加载
        layui.use('flow', function(){
            var flow = layui.flow;
            var str = "";
            if (type ==0) {
                str = '#ul0';
            }else if (type == 1){
                str = '#ul1';
            }
            flow.load({
                elem: str //指定列表容器
                //		         ,scrollElem: '#'//滚动所在元素
                ,done: function(page, next){ //到达临界点（默认滚动触发），触发下一页
                    //		             var lis = [];
                    $.get('<?php echo U("wallet/vip_list");?>',{"page":page,"type":type}, function(res){
                        next(res[1], page < res[0]);
                    }, 'json');
                }
            });
        });
    });
    $(".y-vipList-title .y-tab-title-text").eq(0).trigger('click');




</script>

	<!-- /底部 -->
</body>
</html>