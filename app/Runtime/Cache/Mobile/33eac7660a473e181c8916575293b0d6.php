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
	<!--var URL = '/index.php?s=/Address';-->
	<!--var SELF = '/index.php?s=/address/location.html';-->
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
            <div class="header-title">地址管理</div>
            <div class="header-left return-prev"><i class="fa fa-angle-left"></i></div>
            <div class="header-right"></div>
        </div>
    </div>
    <div class="header-space"></div>
    <!--header-->

    <!--content-->
    <div class="content">
        <!--空购物车-->
        <div class="null-wrap vertical-center" style="display: none;">
            <div class="vertical-auto">
                <div class="null-page">
                    <div class="null-page-icon"><img src="/theme/mobile/images/icon59.png"></div>
                    <div class="null-page-txt">您还没有收货地址！</div>
                </div>
            </div>
        </div>
        <!--空购物车-->

        <div>
            <ul class="">
                <?php if(is_array($place)): $i = 0; $__LIST__ = $place;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li class="location-li" data-id="<?php echo ($v["id"]); ?>">
                        <?php if($attr_id != null): ?><a href="<?php echo U('Goldfruitshop/y_goldShopOrder',array('id'=>$attr_id,'item_id'=>$item_id,'addr_id'=>$v['id']));?>"><?php endif; ?>
                        <div class="location-wrap inner-area">
                            <div class="location-message">
                                <div class="location-message-name"><?php echo ($v["shperson"]); ?></div>
                                <div class="location-message-mobile"><?php echo ($v["mobile"]); ?></div>
                                <div class="clear-float"></div>
                            </div>

                            <div class="row-box location-toponymy">
                                <div class="location-toponymy-title">收货地址</div>
                                <div class="row-flex">
                                    <div class="location-toponymy-txt"><?php echo ($v["province"]); echo ($v["city"]); echo ($v["county"]); echo ($v["address"]); ?></div>
                                </div>
                            </div>
                            <?php if($attr_id != null): ?></a><?php endif; ?>

                            <div class="location-fun">
                                <div class="location-default" data-id="<?php echo ($v["id"]); ?>">
                                    <?php if($v['is_default'] == '1' ): ?><label for="location-1">
                                            <div class="y-location-default active">
                                                <div class="location-default-input"><!--fa-circle-thin-->
                                                    <i class="fa fa-check-circle"></i>
                                                    <input type="radio" id="location-1" name="location" checked="checked" data-id="<?php echo ($v["id"]); ?>">
                                                </div>
                                                <div class="location-default-txt">默认地址</div>
                                            </div>
                                        </label>
                                        <?php else: ?>
                                        <label for="location-1">
                                            <div class="location-default-input"><!--fa-circle-thin-->
                                                <i class="fa fa-circle-thin"></i>
                                                <input type="radio" id="1" name="location"  data-id="<?php echo ($v["id"]); ?>"  data-item_id="<?php echo ($item_id); ?>"  data-attr_id="<?php echo ($attr_id); ?>">
                                            </div>
                                            <div class="location-default-txt">设置为默认地址</div>
                                        </label><?php endif; ?>

                                </div>

                                <div class="location-delete" data-id="<?php echo ($v["id"]); ?>">
                                    <div class="location-operation">
                                        <div class="location-operation-name">删除</div>
                                        <!--<div class="location-operation-icon"><i class="fa fa-trash-o"></i></div>-->
                                    </div>
                                </div>

                                <div class="location-compile">
                                    <div class="location-operation"><a href="<?php echo U('add_location',array('id'=>$v['id']));?>">
                                        <div class="location-operation-name">编辑</div>
                                        <!--<div class="location-operation-icon"><i class="fa fa-file-text-o"></i></div>-->
                                    </a></div>
                                </div>
                                <div class="clear-float"></div>
                            </div>
                        </div>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>

    </div>
    <!--content-->

    <!--footer-->
    <div class="footer-space"></div><!--间距-->
    <div class="footer-wrap">
        <div class="footer-inner">
            <a class="add-place" href="<?php echo U('add_location');?>">添加地址</a>
        </div>
    </div>
    <!--footer-->
    </body>

	<!-- /主体 -->

	<!-- 底部 -->
			


    <script type="text/javascript" src="/theme/mobile/js/jquery.cookie.js"></script>

    <script>
        //设置默认地址
        $("input[name = 'location']").click(function() {
            var _this   = $(this);
            if(_this.attr('checked')=='checked'){
                if($.cookie('location')==2){
                    location.href="<?php echo U('item/gwc_settlement');?>";
                }
                return false;
            }
            var status  = 1;
            var id  = $(this).data('id');
            var item_id  = $(this).data('item_id');
            var attr_id  = $(this).data('attr_id');
            $.ajax({type:"post",url:"",data:{'status':status,'id':id,'item_id':item_id,'attr_id':attr_id},success:function(res){
                    if(res.status==1){
                        layer.msg(res.msg,{icon:1,time:1000},function(){
                            location.href=res.uri;
                        })
                    }else {
                        layer.msg(res.msg, {icon:0, time: 1000});
                    }
                }
            })

        });
        //删除地址
        $('.location-delete').click(function(){
            var address_id  = $(this).data('id');
            var _this=$(this);
            layer.confirm('确认删除吗？', { btn: ['是的','取消']}, function(){
                $.post("<?php echo U('delete_address');?>",{'id':address_id},function(res){
                    if(res.status==1){
                        layer.msg(res.msg,{icon:1,time:1000},function(){
                            _this.parents(".location-li").remove();
                            // location.href=location.href;
                        })
                    }else {
                        layer.msg(res.msg, {icon:0, time: 1000});
                    }
                })
            })

        })


        //返回按钮的跳转
        // $('.fa.fa-angle-left').click(function(){
        //     var is_order = $.cookie('is_order');//判断哪里跳转来
        //     var url = (is_order==1)?'<?php echo U("item/gwc_settlement",array("zftype"=>'+zftype+'));?>':'<?php echo U("Member/myset");?>';
        //     location.href=url;
        // })


        //空页面展示
        var i = $('.location-li').length;
        if(i == 0) {
            $('.null-wrap').css('display', '');
        }

    </script>

	<!-- /底部 -->
</body>
</html>