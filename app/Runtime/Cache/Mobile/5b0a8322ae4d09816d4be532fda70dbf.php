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
	<!--var URL = '/mobile.php?s=/Item';-->
	<!--var SELF = '/mobile.php?s=/item/gwc_settlement/item_id/2548/attr_id/3967/num/1.html';-->
	<!--var ROOT_PATH = '';-->
	<!--var APP	 =	 '/mobile.php?s=';-->
	<!--//语言项目-->
	<!--var lang = new Object();-->
    <!--lang.connecting_please_wait = "请稍后...";lang.confirm_title = "提示消息";lang.move = "移动";lang.dialog_title = "消息";lang.dialog_ok = "确定";lang.dialog_cancel = "取消";lang.please_input = "请输入";lang.please_select = "请选择";lang.not_select = "不选择";lang.all = "所有";lang.input_right = "输入正确";lang.plsease_select_rows = "请选择要操作的项目！";lang.upload = "上传";lang.uploading = "上传中";lang.upload_type_error = "不允许上传的文件类型！";lang.upload_size_error = "文件大小不能超过{sizeLimit}！";lang.upload_minsize_error = "文件大小不能小于{minSizeLimit}！";lang.upload_empty_error = "文件为空，请重新选择！";lang.upload_nofile_error = "没有选择要上传的文件！";lang.upload_onLeave = "正在上传文件，离开此页将取消上传！";-->
	<!--</script>-->

</head>
<body class="body-bgColor">
	<!-- 头部 -->
	
	<!-- /头部 -->
	
	<!-- 主体 -->
	
	<body class="body-bgColor">		<!--购物车提交订单界面header-->		<div class="header-wrap">			<div class="header-inner">				<div class="header-title">商品订单</div>				<!--<a href="<?php echo U('Member/mine');?>">-->					<!--<div class="header-left"><i class="fa fa-angle-left"></i></div>-->				<!--</a>-->				<div class="header-left return-prev"><i class="fa fa-angle-left"></i></div>				<div class="header-right"></div>			</div>			</div>		<div class="header-space"></div>		<!--header-->				<!--content--> 		<div class="content">			<div class="order-message">				<!--地址-->				<a href="<?php echo U('address/location',['location'=>2]);?>"><!--地址列表-->					<div class="row-box">						<div class="order-message-icon vertical-center">							<div class="vertical-auto"><i class="fa fa-map-marker"></i></div>						</div>						<div class="row-flex">							<?php if(!$address): ?><div class="add-location" >									<div class="add-location-icon">+</div>									<div class="add-location-txt">添加地址</div>								</div>							<?php else: ?>								<div class="order-box">									<div class="order-text"><?php echo ($address["shperson"]); ?><span><?php echo ($address["mobile"]); ?></span></div>									<div class="order-locatoin"><?php echo ($address["province"]); echo ($address["city"]); echo ($address["district"]); echo ($address["address"]); ?></div>								</div><?php endif; ?>						</div>						<div class="order-message-right vertical-center">							<div class="vertical-auto"><i class="fa fa-angle-right"></i></div>						</div>					</div>				</a>				<!--地址-->			</div>						<div class="advices-wrap">				<ul class="advices-ul">					<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><li class="advices-li">							<div class="row-box">								<div class="advices-left">									<div class="advices-img vertical-center"><a href="<?php echo U('Index/detail',['id'=>$val['id']]);?>">										<div class="vertical-auto"><img src="<?php echo attach($val['img'],'item');?>"></div>									</a></div>								</div>								<div class="row-flex">									<div class="advices-name"><?php echo ($val['title']); ?>-<?php echo ($val['attr_name']); ?></div>									<div class="advices-money">￥<?php echo ($val['price']); ?><span>x<?php echo ($val['num']); ?></span></div>								</div>							</div>						</li><?php endforeach; endif; else: echo "" ;endif; ?>				</ul>			</div>						<div class="advices-message-box">				<ul class="advices-messag-ul">					<li class="advices-messag-li">						<div class="advices-messag-left">总金额：</div>						<div class="advices-messag-right">￥<?php echo ($msg['money']); ?></div>						<div class="clear-float"></div>					</li>					<li class="advices-messag-li">						<div class="advices-messag-left">商品数量：</div>						<div class="advices-messag-right">x<?php echo ($msg['count']); ?></div>						<div class="clear-float"></div>					</li>					<!--<li class="advices-messag-li">-->						<!--<div class="advices-messag-left">积分折现：</div>-->						<!--<div class="advices-messag-right">-10.00</div>-->						<!--<div class="clear-float"></div>-->					<!--</li>-->					<!--<li class="advices-messag-li">-->						<!--<div class="advices-messag-left">含运费：</div>-->						<!--<div class="advices-messag-right">+0.00</div>-->						<!--<div class="clear-float"></div>-->					<!--</li>-->				</ul>			</div>						<div class="leave-wrap">				<div class="row-box leave-inner">					<div class="leave-name">客户留言：</div>					<div class="row-flex">						<div class="leave-text">							<textarea name="memos" rows="" cols="" placeholder="选填"></textarea>						</div>					</div>				</div>			</div>					</div>		<!--content-->				<!--footer-->		<div class="footer-space"></div><!--间距-->		<div class="footer-wrap">			<div class="footer-inner inner-area">				<div class="row-box">					<div class="row-flex">						<div class="order-money">应付：<span>￥<?php echo ($msg['money']); ?></span></div>					</div>					<div class="order-link">						<a href="javascript:;" id="order_submit">提交订单</a>					</div>				</div>			</div>		</div>		<!--footer-->	</body>
	<!-- /主体 -->

	<!-- 底部 -->
			

	<!--<script type="text/javascript" src="/theme/mobile/js/jquery.cookie.js"></script>-->	<script>		//地址列表		// $('#location_list').click(function(){         //    $.cookie('location',2);         //    location.href="<?php echo U('address/location',['location'=>2]);?>";		// })		//提交订单        $('#order_submit').click(function(){            var addr_id='<?php echo ($address['id']); ?>';            if(!addr_id){                layer.msg('请选择收货地址！',{icon:0,time:1000});				return false;			}			var memos = $('input[name="memos"]').val();			$.post('<?php echo U("order/order_submit");?>',{addr_id:addr_id,memos:memos},function(res){                if(res.status==1){                    layer.msg(res.msg,{icon:1,time:1000},function(){                        window.location.href=res.url;                    });                }else if(res.status==-1){                    layer.msg(res.msg,{icon:0,time:1000},function(){                        window.location.href=res.url;                    });				}else {                    layer.msg(res.msg, {icon: 0, time: 1000})                }			},'JSON')        })	</script>
	<!-- /底部 -->
</body>
</html>