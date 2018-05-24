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
	<!--var SELF = '/index.php?s=/WalletOther/basin_account/status/2.html';-->
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
        <div class="header-title">寄存明细</div>
        <div class="header-left return-prev"><i class="fa fa-angle-left"></i></div>
        <div class="header-right"></div>
    </div>
</div>
<div class="header-space"></div>
<!--header-->

<!--content-->
<div class="content y-bg-white">
    <!--为空时显示的背景图-->
    <div class="y-record-bg y-hide">
        <div class="y-record-bg-content"></div>
        <p class="y-record-bg-text">这里空空如也~</p>
    </div>
    <!--弹出窗-->
    <!--<div class="y-alert-bg y-recordAlert-bg y-hide">-->
        <!--<div class="y-recordAlert y-alert-div">-->
            <!--<div class="y-alert-content y-recordAlert-content">确定提取到我的钱包？</div>-->
            <!--<div class="jus-bet y-alert-Button y-recordAlert-Button y-text-center">-->
                <!--<p class="y-alert-bankButton y-color-999">取消</p>-->
                <!--<p><a href="javascript:;" class="y-color-red y-block">确定</a></p>-->
            <!--</div>-->
        <!--</div>-->
    <!--</div>-->
    <!--弹出窗-->
    <ul>
        <?php if(is_array($jbp)): $i = 0; $__LIST__ = $jbp;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$j): $mod = ($i % 2 );++$i;?><li class="y-bg-white y-declare-li">
                <div class="declare-tier-top jus-bet font-32">
                    <div>本次寄存</div>
                    <div><?php echo ($j['totalprices']); ?></div>
                </div>
                <!--status支出状态 1寄存中2待提取3已提取 -->
                <div class="declare-tier-bottom jus-bet ali-cen font-24">
                    <div class="y-color-999"><?php echo date('Y-m-d H:i:s',$j['add_time']);?></div>
                    <?php switch($j['status']): case "1": ?><div class="y-color-666">剩余<?php echo ($j['other_days']); ?>天</div><?php break;?>
                        <?php case "2": ?><div class="y-bg-red y-record-button" data-id="<?php echo ($j['id']); ?>">提取</div><?php break;?>
                        <?php case "3": ?><div class="y-color-666">已提取</div><?php break;?>
                        <?php default: endswitch;?>
                </div>

            </li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
</div>
<!--content-->
<!--footer-->
<!--footer-->
</body>

	<!-- /主体 -->

	<!-- 底部 -->
			


    <script>
        $('.y-bg-red').click(function(){
            var id=$(this).data('id');
            layer.confirm('确定提取吗？', {btn: ['是的','取消']}, function(){
                $.ajax({type:'post',url:'<?php echo U('WalletOther/basin_out');?>',async:true,
                    data:{id:id},dataType:'json',success:function(d) {
                        if(d.status==1){
                            layer.msg(d.msg,{icon:1,time:1000},function(){
                                window.location.href = d.url;
                            })
                        }else{
                            layer.msg(d.msg,{icon:2,time:1000})
                        }
                    }
                })
            })

        })
    </script>
    <script>
        $(".y-record-button").click(function(){
            $(".y-recordAlert-bg").show();
        })
        $(".y-alert-bankButton").click(function(){
            $(".y-recordAlert-bg").hide();
        })
    </script>

	<!-- /底部 -->
</body>
</html>