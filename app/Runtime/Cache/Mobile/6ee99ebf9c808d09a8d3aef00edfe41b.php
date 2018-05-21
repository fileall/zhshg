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
	<!--var SELF = '/index.php?s=/wallet/acer_buy.html';-->
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
	

<!--元宝充值选择金额界面-->
<body>
<!--header-->
<div class="header-wrap">
    <div class="header-inner">
        <div class="header-title">元宝购买</div>
        <div class="header-left return-prev"><i class="fa fa-angle-left"></i></div>
        <div class="header-right"></div>
    </div>
</div>
<div class="header-space"></div>
<!--header-->

<!--content-->
<div class="content">
    <div>
        <div class="merchant-form y-merchant-form row-box y-bg-white" style="margin-top: 0;">
            <div class="merchant-currency font-32">充值金额：</div>
            <div class="row-flex">
                <div class="merchant-form-input vertical-center">
                    <div class="vertical-auto" style="width: 100%;">
                        <input class="font-32" type="number" placeholder="请输入充值金额" id="price">
                    </div>
                </div>
            </div>
        </div>

        <div class="y-bg-white y-goldBuy y-width94">
            <p class="y-color-999 font-32">选择充值金额</p>
            <ul class="buy-ul y-mar-top20 jus-bet flex-warp">
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><li class="y-buy-li">
                        <p class="font-32" data-value="<?php echo ($val["money"]); ?>"><?php echo ($val["money"]); ?>元</p>
                        <p class="font-28">送<?php echo ($val["send_coin"]); ?>银币</p>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
            <div class="submit-button y-goldBuy-button"><a>立即购买</a></div>
        </div>
    </div>

</div>
<!--content-->

</body>

	<!-- /主体 -->

	<!-- 底部 -->
			


    <script>

        $('.y-buy-li').click(function(){
            var price = $(this).find('p[class="font-32"]').data('value');
            $('#price').val(price);

        });



        //点击购买
        $('.submit-button.y-goldBuy-button').click(function(){
            var price = $('#price').val();

            if(!price){
                layer.msg('请填写金额！',{icon:0,time:1000})
                return false;
            }

            if(price < 0){
                layer.msg('金额不能为负数！',{icon:0,time:1000})
                return false;
            }
            var zs= /^[1-9]*[1-9][0-9]*$/;
            if(!zs.test(price)){
                layer.msg('请填写整数！',{icon:0,time:1000})
                return false;
            }

            $.post('<?php echo U("wallet/make_order_recharge");?>',{price:price},function(d){
                if(d.status ==1){
                      window.location.href=d.url;
                }else{
                    layer.msg(d.msg);
                }

            })
        })

        $(".y-buy-li").click(function(){
            $(this).addClass("y-buy-li-select").siblings().removeClass("y-buy-li-select")
        })

        //聚焦时候 去掉按钮样式
        $('#price').focus(function(){
            $('.y-buy-li').removeClass("y-buy-li-select")
        })
    </script>

	<!-- /底部 -->
</body>
</html>