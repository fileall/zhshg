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
	<!--var SELF = '/index.php?m=Mobile&c=Index&a=detail&id=1983';-->
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
				<div class="header-title">详情</div>
				<div class="header-left return-prev"><i class="fa fa-angle-left"></i></div>
				<div class="header-right"></div>
			</div>	
		</div>
		<div class="header-space"></div>
		<!--header-->
		

		<!--content-->
		<div class="content">
			<!--banner-->
			<div class="swiper-container" style="margin-top: 0.2rem;">
				<div class="swiper-wrapper">
					<?php if(is_array($img_list)): $i = 0; $__LIST__ = $img_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><div class="swiper-slide">
							<a href="javascript:;">
							<div class="banner-image vertical-center">
								<div class="all-auto"><img src="<?php echo attach($list['url'],'item');?>"></div>
							</div>
							</a>
						</div><?php endforeach; endif; else: echo "" ;endif; ?>
					
					<!--<div class="swiper-slide"><a href="#">
						<div class="banner-image vertical-center">
							<div class="all-auto"><img src="/theme/mobile/images/banner1.jpg"></div>
						</div>
					</a></div>-->
				</div>
				<div class="swiper-pagination"></div>
			</div>
			<!--banner-->
			
			<div class="details-wrap">
				<div class="details-name">
					
					<?php echo ($info["title"]); ?>
				</div>
				<div class="details-money">￥<?php echo ($info["price"]); ?><span>￥<?php echo ($info["oldprice"]); ?></span></div>
				
				<div class="details-express">
					<div class="details-tier">快递费：包邮</div>
					<div class="details-tier right">已售<?php echo ($info["sales"]); ?>件</div>
					<div class="clear-float"></div>
				</div>
				
				<div class="details-character">
					<div class="details-character-txt">正品保障</div>
					<!--<div class="details-character-txt">七天退货</div>-->
					<div class="clear-float"></div>
				</div>
			</div>
			<!--在售商品数量-->、
			<div class="y-bg-white">
				<div class="y-details-logo y-mar-left20">
					<img src="/theme/mobile/images/y_logo2.png"/>
				</div>
				<div class="y-details-num">
					<p class="font-28">悦买宝</p>
					<p class="y-color-999">在售商品<span class="y-color-red"><?php echo ($info["count"]); ?></span>件</p>
				</div>
				<div class="clear-float"></div>
			</div>

			<!--在售商品数量-->
			
			<!--商品详情/商品评价-->
			<div class="character-nav-box">
				<div class="character-nav-name active">商品介绍</div>
				<!--<div class="character-nav-name">商品评价</div>-->
				<div class="clear-float"></div>
			</div>
			
			
			<div class="">
				
				<div class="character-content-tier active">
					<div class="">
					<?php echo html_entity_decode($info['info'], ENT_QUOTES, 'UTF-8');?>
					</div>
				</div>
				
				<div class="character-content-tier">
					<ul class="criticism-ul">
						<li class="criticism-li">
							<div class="criticism-top">
								<div class="criticism-photo"><img src="/theme/mobile/images/person.jpg"></div>
								<div class="criticism-name">爱吃鱼的猫</div>
								<div class="criticism-star">
									<div class="criticism-date">2017-08-08</div>
									<div class="criticism-right-col">
										<span class="atar-icon active"></span>
										<span class="atar-icon active"></span>
										<span class="atar-icon"></span>
										<span class="atar-icon"></span>
										<span class="atar-icon"></span>
										<div class="clear-float	"></div>
									</div>
								</div>
								<div class="clear-float	"></div>
							</div>
							
							<div class="criticism-txt">
								这款眉笔很好用，多次购买了，下次还会用这个色号这款眉笔很好用，多次购买了，下次还会用这个色号这款眉笔很好用，多次购买了，下次还会用这个色号。
							</div>
							<div class="criticism-img-box">
								<ul class="criticism-img-ul">
									<li class="criticism-img-li vertical-center">
										<div class="vertical-auto"><img src="images/person.jpg"></div>
									</li>
									
									<li class="criticism-img-li vertical-center">
										<div class="vertical-auto"><img src="images/person.jpg"></div>
									</li>
									<div class="clear-float"></div>
								</ul>
							</div>
						</li>
					</ul>
				</div>
			</div>
			<!--商品详情/商品评价-->
		</div>
		<!--content-->
		
		<!--立即购买弹窗-->
		<div class="purchase-popup">
			<div class="purchase-inner">
				<div class="close-purchase"></div>
				<div class="purchase-content">
					<div class="row-box purchase-col">
						<div class="purchase-col-left">
							<div class="purchase-col-img vertical-center">
								<div class="all-auto"><img src="<?php echo attach($info['img'], 'item');?>"></div>
							</div>
						</div>

						<div class="row-flex">
							<div class="purchase-name"><?php echo ($info["title"]); ?></div>
							<div class="purchase-money">￥<?php echo ($info["price"]); ?><span>￥<?php echo ($info["oldprice"]); ?></span></div>
							<div class="purchase-txt">
								<div class="product-left">快递费:包邮</div>
								<div class="product-right">已销售：<?php echo ($info["sales"]); ?>件</div>
								<div class="clear-float"></div>
							</div>
						</div>
					</div>

					<div class="specific-ation">
						<div class="specification-title">选择规格：</div>
						<div class="specification-wrap">
							<?php if(is_array($attr)): $i = 0; $__LIST__ = $attr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><span class="specification-span <?php if($i == 1): ?>active<?php endif; ?>"
								data-attr_value="<?php echo ($val["attr_value"]); ?>" data-price="<?php echo ($val["price"]); ?>" data-oldprice="<?php echo ($val["oldprice"]); ?>" data-id="<?php echo ($val["id"]); ?>">
									<?php echo ($val["attr_name"]); ?>
								</span><?php endforeach; endif; else: echo "" ;endif; ?>
							<div class="clear-float"></div>
						</div>
					</div>

					<div class="row-box wicket-box">
						<div class="wicket-tier-title numbers">数量：</div>
							<div class="row-flex">
								<div class="wicket-amount row-box">
								<div class="wicket-amount-btn minus"><i class="fa fa-minus"></i></div>
								<div class="wicket-amount-input row-flex"><input type="number" readonly="readonly" value="1" id="num"></div>
								<div class="wicket-amount-btn add"><i class="fa fa-plus"></i></div>
							</div>
						</div>
					</div>
				</div>

				<div class="purchase-btn">
					<div class="purchase-btn-add join">
						<button type="button">加入购物车</button>
					</div>

					<div class="purchase-btn-add buy active ">
						<button type="button">立即购买</button>
					</div>
					<div class="clear-float"></div>
				</div>
			</div>
		</div>
		<!--立即购买弹窗-->
		
	<!-- /主体 -->

	<!-- 底部 -->
	
				<div class="footer-space"></div>
				<div class="footer-wrap">
					<div class="footer-inner">
						<div class="footer-nav row-box operation-box">
							<div class="operation-col border">
								<a href="tel:<?php echo C('pin_tel');?>">
									<div class="operation-tier service">客服</div>
								</a>
							</div>

							<div class="operation-col border">
								<a href="<?php echo U('item/gwc');?>">
									<?php if(empty($nums)): ?><div class="operation-tier vehicle">购物车</div>
										<?php else: ?>
										<div class="operation-tier vehicle">购物车<span><?php echo ($nums); ?></span></div><?php endif; ?>

								</a>
							</div>

							<div class="operation-col">
								<div class="operation-tier collect">收藏</div>
							</div>

							<div class="row-flex">
								<div class="operation-add add-indent">加入购物车</div>
							</div>

							<div class="row-flex">
									<div class="operation-add add-indent active">立即购买</div>
							</div>
							<input id="attr_id" type="hidden" value="">
							<input id="attr_value" type="hidden" value="">

						</div>
					</div>
				</div>
				


	<script>
		//立即购买
        $('.purchase-btn-add.buy').click(function() {
            if( $('.specification-span.active').length <1){
                layer.msg('请选择商品规格！',{icon:0,time:1000})
                return false;
            }
            var attr_id=$('#attr_id').val();
            var item_id='<?php echo ($info["id"]); ?>';
            var num=$('#num').val();
            // window.location.href='/mobile.php?s=/item/gwc_settlement/item_id/'+item_id+'/attr_id/'+attr_id+'/num/'+num+'.html';

            $.post('<?php echo U("Item/check_value");?>',{item_id:item_id,attr_id:attr_id,num:num},function(d){
                if(d.status==1){
                    window.location.href='/mobile.php?s=/item/gwc_settlement/item_id/'+item_id+'/attr_id/'+attr_id+'/num/'+num+'.html';
                }else{
                    layer.msg(d.msg,{icon:0,time:1000})
                }
            })

            $(".purchase-popup").removeClass("active");

        })
            //加入购物车操作
		$('.purchase-btn-add.join').click(function(){
            if( $('.specification-span.active').length <1){
                layer.msg('请选择商品规格！',{icon:0,time:1000})
                return false;
            }
            var attr_id=$('#attr_id').val();
            var item_id='<?php echo ($info["id"]); ?>';
            var num=$('#num').val();

            $.post('<?php echo U("Item/add_gwc");?>',{item_id:item_id,attr_id:attr_id,num:num},function(d){
                if(d.status==1){
                    layer.confirm('操作成功,查看购物车?', {
                        btn: ['是的','取消'] //按钮
                    }, function(){
                        window.location.href=d.url;
                    })
                }else{
                    layer.msg(d.msg,{icon:0,time:1000})
                }
            })
			$(".purchase-popup").removeClass("active");
        })

        $(function(){$('.specification-span.active').click();})
        //规格
        $('.specification-span').click(function(){
            var price= $(this).data('price');
            var oldprice= $(this).data('oldprice');
            var attr_value= $(this).data('attr_value');
            var attr_id= $(this).data('id');
            var html1= "<div class=\"details-money\">￥"+price+"<span>"+oldprice+"</span></div>";
            var html2= "<div class=\"purchase-money\">￥"+price+"<span>￥"+oldprice+"</span></div>";

            $('.details-money').html(html1);
            $('.purchase-money').html(html2);

            $('#attr_id').val(attr_id);
            $('#attr_value').val(attr_value);

        })


        //加
        $('.add').click(function(){
            if( $('.specification-span.active').length <1){
                layer.msg('请选择商品规格！',{icon:0,time:1000})
                return false;
            }
            var sib = $(this).siblings(".wicket-amount-input");
            var val = $(sib).find("input").val();
            var _int = parseInt(val) + 1;
            var attr_value=$('#attr_value').val();
            if(attr_value<_int){
                layer.msg('库存不足！',{icon:0,time:1000});
                return false;
			}
            $(sib).find("input").val(_int);

        })

        //减
        $(".wicket-amount-btn.minus").click(function() {
            if( $('.specification-span.active').length <1){
                layer.msg('请选择商品规格！',{icon:0,time:1000})
                return false;
            }
            var sib = $(this).siblings(".wicket-amount-input");
            var val = $(sib).find("input").val();
            var _int = parseInt(val) - 1;
            if(val > 1)
                $(sib).find("input").val(_int);
            else return;
        });


		

	</script>
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



	<!-- /底部 -->
</body>
</html>