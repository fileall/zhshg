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
	<!--var SELF = '/index.php?s=/Index/index.html';-->
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
            <div class="home-header padd row-box">
                <div class="home-left" id="scanQRCode">
                    <a href="javascript:;" >
                        <div class="logo-img y-indexCode-img"><img src="/theme/mobile/images/icon01.png"></div>
                        <div class="y-text-center font-22">扫一扫</div>

                    </a>
                </div>
                <div class="flex1">
                    <div class="y-over-hide y-logo-img">
                        <img src="/theme/mobile/images/y_logo.png"/>
                    </div>
                </div>
                <div class="home-right">
                    <a href="<?php echo U('index/new_list');?>">
                        <div class="message-img y-message-img"><img src="/theme/mobile/images/y_icon60.png"></div>
                        <p class="y-text-center">消息</p>
                    </a>
                    <!--<div class="message-tis y-message-tis y-bg-red">99</div>-->
                </div>
            </div>
        </div>
    </div>
    <div class="header-space"></div>
    <!--header-->
    <!--content-->
    <div class="content">

        <!--轮播banner-->
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php if(is_array($lux)): $i = 0; $__LIST__ = $lux;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$lu): $mod = ($i % 2 );++$i;?><div class="swiper-slide"><a href="javascript:;">
                        <div class="banner-image vertical-center">
                            <!--<div class="all-auto"><img src="/theme/mobile/images//banner1.jpg"></div>-->
                            <div class="all-auto"><img src="<?php echo attach($lu['content'],'advert');?>"></div>
                        </div>
                    </a></div><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
        <!--轮播banner-->

        <!--服务导航-->
        <div class="carbolon active">
            <ul class="carbolon-ul">
                <li class="carbolon-li active"><a href="javascript:;">
                    <div class="carbolon-icon"><img src="/theme/mobile/images/y_icon61.png"></div>
                    <div class="carbolon-name">国内特产</div>
                </a></li>

                <li class="carbolon-li active"><a href="javascript:;">
                    <div class="carbolon-icon"><img src="/theme/mobile/images/y_icon62.png"></div>
                    <div class="carbolon-name">全球特产</div>
                </a></li>

                <li class="carbolon-li active"><a href="javascript:;">
                    <div class="carbolon-icon"><img src="/theme/mobile/images/y_icon63.png"></div>
                    <div class="carbolon-name">悦买商城</div>
                </a></li>

                <li class="carbolon-li active"><a href="javascript:;">
                    <div class="carbolon-icon"><img src="/theme/mobile/images/y_icon64.png"></div>
                    <div class="carbolon-name">悦买团购</div>
                </a></li>

                <li class="carbolon-li active"><a href="javascript:;">
                    <div class="carbolon-icon"><img src="/theme/mobile/images/y_icon65.png"></div>
                    <div class="carbolon-name">生活服务</div>
                </a></li>

                <li class="carbolon-li active"><a href="javascript:;">
                    <div class="carbolon-icon"><img src="/theme/mobile/images/y_icon66.png"></div>
                    <div class="carbolon-name">悦买出行</div>
                </a></li>

                <li class="carbolon-li active"><a href="javascript:;">
                    <div class="carbolon-icon"><img src="/theme/mobile/images/y_icon67.png"></div>
                    <div class="carbolon-name">同城配送</div>
                </a></li>

                <li class="carbolon-li active"><a href="javascript:;">
                    <div class="carbolon-icon"><img src="/theme/mobile/images/y_icon68.png"></div>
                    <div class="carbolon-name">悦买生鲜</div>
                </a></li>

                <div class="clear-float"></div>
            </ul>
        </div>
        <!--服务导航-->

        <!--滚动信息-->
        <div class="scroll-message">
            <div class="row-box inner-auto">
                <div class="scroll-message-left">
                    <div class="scroll-message-title"><img src="/theme/mobile/images/y_icon69.png"></div>
                </div>
                <div class="row-flex">
                    <div class="scroll-message-data">
                        <ul class="scroll-message-ul">
                            <?php if(is_array($active_go)): $i = 0; $__LIST__ = $active_go;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$act): $mod = ($i % 2 );++$i;?><li class="scroll-message-li">
                                    <a href="<?php echo U('index/new_details',array('id'=>$act['id']));?>"><?php echo ($act["intro"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                            <!--<li class="scroll-message-li"><a href="javascript:;">用户99444****112 刚刚购买元宝成功刚刚购买元宝成功</a></li>-->
                        </ul>
                    </div>
                </div>

                <div class="scroll-message-more"><a href="<?php echo U('index/new_list');?>">更多</a></div>
                <!--new_list.html-->
            </div>
        </div>
        <!--滚动信息-->

        <!--金果商城入口-->
        <div class="w100 y-over-hide"><a href="<?php echo U('Goldfruitshop/gold_shop');?>"><img src="<?php echo attach($new_user['content'],'advert');?>"></a></div>
        <!--金果商城入口-->

        <!--秒杀-->
        <div class="activity-wrap"><a href="javascript:;">
            <div class="inner-auto row-box">
                <div class="activity-left">
                    <div class="activity-name">悦买宝秒杀<span>20点场</span></div>
                </div>
                <div class="row-flex">
                    <div class="activity-time vertical-center"><div class="vertical-auto">00</div></div>
                    <div class="activity-txt">:</div>
                    <div class="activity-time vertical-center"><div class="vertical-auto">00</div></div>
                    <div class="activity-txt">:</div>
                    <div class="activity-time vertical-center"><div class="vertical-auto">00</div></div>
                    <div class="clear-float"></div>
                </div>
                <div class="activity-right">悦选尖货秒</div>
            </div>
        </a></div>

        <div class="activity-content">
            <ul class="activity-ul">
                <?php if(is_array($favour)): $i = 0; $__LIST__ = $favour;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$xx): $mod = ($i % 2 );++$i;?><li class="activity-li"><a href="javascript:;">
                        <div class="activity-img vertical-center">
                            <div class="vertical-auto"><img src="<?php echo attach($xx['img'],'item');?>"></div>
                        </div>
                        <div class="activity-money">￥<?php echo ($xx['price']); ?></div>
                        <!--<div class="activity-money active">￥140.00</div>-->
                    </a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                <div class="clear-float"></div>
            </ul>
        </div>
        <!--秒杀-->

        <!--分类-->
        <div class="classification">
            <div class="classification-tier">
                <a href="<?php echo U('Item/lable',array('fx'=>1));?>">
                <div class="classification-text-img"><img src="/theme/mobile/images/text1.png"></div>
                <div class="classification-title">发现品质生活</div>
                <div class="classification-img-box">
                    <?php if(is_array($fl[0])): $i = 0; $__LIST__ = $fl[0];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$xx): $mod = ($i % 2 );++$i;?><!--<a href="<?php echo U('index/detail',array('id'=>$xx['id']));?>">-->

                            <div class="classification-img-tier vertical-center">
                                <div class="vertical-auto"><img src="<?php echo attach($xx['img'],'item');?>"></div>
                            </div>
                        <!--</a>--><?php endforeach; endif; else: echo "" ;endif; ?>
                    <div class="clear-float"></div>
                </div>
                </a>

            </div>
            <div class="classification-tier">
                <a href="<?php echo U('Item/lable',array('zhm'=>1));?>">
                <div class="classification-text-img"><img src="/theme/mobile/images/text2.png"></div>
                <div class="classification-title">教你买买买</div>
                <div class="classification-img-box">
                    <?php if(is_array($fl[1])): $i = 0; $__LIST__ = $fl[1];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$xx): $mod = ($i % 2 );++$i;?><!--<a href="<?php echo U('index/detail',array('id'=>$xx['id']));?>">-->
                            <div class="classification-img-tier vertical-center">
                                <div class="vertical-auto"><img src="<?php echo attach($xx['img'],'item');?>"></div>
                            </div>
                        <!--</a>--><?php endforeach; endif; else: echo "" ;endif; ?>
                    <div class="clear-float"></div>
                </div>
                </a>

            </div>
            <div class="classification-tier">
                <a href="<?php echo U('Item/lable');?>">
                <div class="classification-text-img"><img src="/theme/mobile/images/text5.png"></div>
                <div class="classification-title">新品首发,总有你要的</div>
                <div class="classification-img-box">
                    <?php if(is_array($fl[4])): $i = 0; $__LIST__ = $fl[4];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$xx): $mod = ($i % 2 );++$i;?><!--<a href="<?php echo U('index/detail',array('id'=>$xx['id']));?>">-->
                            <div class="classification-img-tier vertical-center">
                                <div class="vertical-auto"><img src="<?php echo attach($xx['img'],'item');?>"></div>
                            </div>
                        <!--</a>--><?php endforeach; endif; else: echo "" ;endif; ?>
                    <div class="clear-float"></div>
                </div>
                </a>

            </div>
            <div class="classification-tier">
                <a href="<?php echo U('Item/lable',array('jx'=>1));?>">
                <div class="classification-text-img"><img src="/theme/mobile/images//text4.png"></div>
                <div class="classification-title">精选品质生活</div>
                <div class="classification-img-box">
                    <?php if(is_array($fl[3])): $i = 0; $__LIST__ = $fl[3];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$xx): $mod = ($i % 2 );++$i;?><!--<a href="<?php echo U('index/detail',array('id'=>$xx['id']));?>">-->
                            <div class="classification-img-tier vertical-center">
                                <div class="vertical-auto"><img src="<?php echo attach($xx['img'],'item');?>"></div>
                            </div>
                        <!--</a>--><?php endforeach; endif; else: echo "" ;endif; ?>
                    <div class="clear-float"></div>
                </div>
                </a>

            </div>

            <div class="clear-float"></div>
        </div>
        <!--分类-->
        <!--猜你喜欢-->
        <div class="suspect-wrap">
            <div class="suspect-title"><span>—</span>猜你喜欢<span>—</span></div>
            <ul class="suspect-ul">
                <!--<?php if(is_array($favour)): $i = 0; $__LIST__ = $favour;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>-->
                <!--<li class="suspect-li">-->
                <!--<a href="<?php echo U('index/detail',array('id'=>$val['id']));?>">-->
                <!--<div class="suspect-img vertical-center">-->
                <!--<div class="vertical-auto"><img src="<?php echo get_thumb(attach($val['img'],'item'),'_b');?>" /></div>-->
                <!--</div>-->
                <!--<div class="suspect-img-name">-->
                <!--<?php echo mb_substr($val['title'],0,15,"UTF-8");?>-->
                <!--<?php if(strlen($val['title']) > 15) echo '..';?>-->
                <!--<div class="suspect-money">-->
                <!--<span>￥<?php echo ($val["price"]); ?></span><?php echo ($val["sales"]); ?>人已付款-->
                <!--</div>-->
                <!--</div>-->
                <!--</a>-->
                <!--</li>-->
                <!--<?php endforeach; endif; else: echo "" ;endif; ?>-->
                <div class="clear-float"></div>
            </ul>
        </div>
        <!--猜你喜欢-->

        <!--content-->

        <!--footer-->
        <block name="footer">
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

		



			


    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script>
        //猜你喜欢
        layui.use('flow', function(){
            var flow = layui.flow;
            flow.load({
                elem: '.suspect-ul' //指定列表容器
//		    ,scrollElem: '#'//滚动所在元素
                ,done: function(page, next){ //到达临界点（默认滚动触发），触发下一页
                    var lis = [];
                    $.get('<?php echo U("guesslike_list");?>',{page:page}, function(res){
                        layui.each(res[1], function(index, item){
                            lis.push(' <li class="suspect-li">' +
                                '<a href="'+item.href+'">' +
                                ' <div class="suspect-img vertical-center">' +
                                '<div class="vertical-auto"><img src="'+item.img+'" /></div></div>' +
                                ' <div class="suspect-img-name">'+item.title+'<div class="suspect-money">'+
                                ' <span>￥'+item.price+'</span>'+item.sales+'人已付款</div></div></a></li>');
                        });
                        next(lis.join(''), page < res[0]);
                    }, 'json');
                }

            });

        });
        //轮播图
        var swiper = new Swiper('.swiper-container',
            {
                pagination: '.swiper-pagination',
                paginationClickable: true,
                autoplay:3000,
                autoplayDisableOnInteraction:false,
                loop:true
            });

        //微信
        wx.config({
            debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。

            appId: "<?php echo ($js['appId']); ?>", // 必填，公众号的唯一标识

            timestamp: "<?php echo ($js['timestamp']); ?>", // 必填，生成签名的时间戳

            nonceStr: "<?php echo ($js['nonceStr']); ?>", // 必填，生成签名的随机串

            signature: "<?php echo ($js['signature']); ?>",// 必填，签名，见附录1

            jsApiList: [ 'scanQRCode'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
        });

        wx.ready(function(){
            $("#scanQRCode").on("click",function(){
                wx.scanQRCode({
                    needResult: 0, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
                    scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
                    success: function (res) {
                        var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
                    }
                });

            })
        });
    </script>

	<!-- /底部 -->
</body>
</html>