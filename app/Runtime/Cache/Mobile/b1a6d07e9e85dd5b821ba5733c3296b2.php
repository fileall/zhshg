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
	<!--var URL = '/index.php?s=/Merchant';-->
	<!--var SELF = '/index.php?s=/Merchant/merchant_list.html';-->
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
<div class="y-ambitus-main">
<!--header-->
<div class="header-wrap">
    <div class="header-inner">
        <div class="header-title">
            <div class="orientation row-box">
                <div class="row-flex">
                    <div class="orientation-icon"><img src="/theme/mobile/images/location1.png"></div>
                </div>
                <div class="orientation-name">解放西路</div>
                <div class="row-flex">
                    <div class="orientation-right"><!--<i class="fa fa-angle-right"></i>--></div>
                </div>
            </div>
        </div>
        <div class="header-left return-prev"><i class="fa fa-angle-left"></i></div>
        <div class="header-right"></div>
    </div>
</div>
<div class="header-space"></div>
<!--header-->

<!--一级分类-->
<div class="filtrate-wrap flex y-bg-white">
    <div class="swiper-container flex1">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <div class="y-filtrate-name active">推荐</div>
            </div>
            <?php if(is_array($cate)): $i = 0; $__LIST__ = $cate;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><div class="swiper-slide">
                    <!--<?php if($i == 1): ?>active<?php endif; ?>-->
                     <div class="y-filtrate-name" data-id="<?php echo ($val['id']); ?>"><?php echo ($val["name"]); ?></div>
                </div><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </div>

    <div class="filtrate-right">
        <a href="javaScript:;"><div class="filtrate-select">筛选</div></a>
        <div class="filtrate-link"></div>
    </div>
</div>
<div class="header-space"></div>
<!--一级分类-->
<!--二级分类-->
<div class="">
    <ul class="more-kind-ul y-more-kind-ul  y-hide" style="display: block;">
    </ul>
    <?php if(is_array($cate)): $i = 0; $__LIST__ = $cate;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><!--<?php if($i == 1): ?>style="display: block;"<?php endif; ?>-->
        <ul class="more-kind-ul y-more-kind-ul  y-hide">
            <?php if(is_array($cate_next)): $i = 0; $__LIST__ = $cate_next;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i; if($val['id'] == $vv['pid']): ?><li class="flex ali-cen " >
                        <li class="more-kind-li two-cate" data-id="<?php echo ($vv['id']); ?>"><?php echo ($vv['name']); ?></li>
                    </li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
        </ul><?php endforeach; endif; else: echo "" ;endif; ?>
</div>
<div class="clear-float"></div>
<!--二级分类-->
<!--content-->
<div class="content">
    <ul class="filtrate-list-ul">
        <!--<li class="filtrate-list-li"><a href="shop_details.html">-->
            <!--<div class="filtrate-box row-box">-->
                <!--<div class="filtrate-list-left">-->
                    <!--<div class="filtrate-list-img vertical-center">-->
                        <!--<div class="vertical-auto"><img src="/theme/mobile//theme/mobile/images/img3.jpg"></div>-->
                    <!--</div>-->
                <!--</div>-->

                <!--<div class="row-flex">-->
                    <!--<div class="store-name">商铺名称商铺名称</div>-->
                    <!--<div class="store-date">工作时间：09:00-18:00</div>-->
                    <!--<div class="store-txt"><span class="zhi">付</span>支持 金元宝，银元宝，金果支付</div>-->
                    <!--<div class="store-txt"><span class="bei">送</span>赠送5倍银币</div>-->
                <!--</div>-->

                <!--<div class="filtrate-list-right">-->
                    <!--0.5km-->
                <!--</div>-->
            <!--</div>-->
        <!--</a></li>-->
    </ul>
</div>
<!--content-->
</div>
<!--筛选页面-->
<div class="y-ambitus-search y-hide">
    <!--header-->
    <div class="header-wrap">
        <div class="header-inner">
            <div class="header-title">筛选</div>
            <div class="header-left y-header-left"><i class="fa fa-angle-left"></i></div>
            <div class="header-right"></div>
        </div>
    </div>
    <div class="header-space"></div>
    <!--header-->

    <!--content-->
    <div class="content">
        <div class="screen-content">
            <div class="serach-box vertical-center y-width94">
                <div class="vertical-auto" style="width: 100%;"><input id="search" type="text" name="title" ></div>
                <div class="serach-tis"><i class="fa fa-search"></i>搜索商品</div>
            </div>

            <!--选择地区-->
            <div class="area-form">
                <div class="area-form-title">选择区域<span id="clear_address">(清空)</span></div>
                <div class="area-form-input vertical-center">
                    <div class="vertical-auto" style="width: 100%;"><input id="area"  name="address" type="text" readonly="readonly" placeholder="请选择区域"></div>
                </div>
            </div>
            <!--选择地区-->

            <!--商品筛选-->
            <div class="area-form">
                <div class="area-form-title">支付类型</div>
                <div class="">
                    <ul class="more-kind-ul y-screen-kind-ul">
                        <li class="more-kind-li zftype active " data-zftype="0">全部</li>
                        <li class="more-kind-li zftype"  data-zftype="1">金元宝支付</li>
                        <li class="more-kind-li zftype" data-zftype="3">金果支付</li>
                        <div class="clear-float"></div>
                    </ul>
                    <!--<ul class="more-kind-ul y-screen-kind-ul">-->
                        <!--<li class="more-kind-li active">全部</li>-->
                        <!--<li class="more-kind-li">金元宝支付</li>-->
                        <!--<li class="more-kind-li">银元宝支付</li>-->
                        <!--<li class="more-kind-li">金果支付</li>-->
                        <!--<div class="clear-float"></div>-->
                    <!--</ul>-->
                </div>

                <div class="submit-button"><a class="sub-pass" href="javaScript:;">确定</a></div>
            </div>
            <!--商品筛选-->
        </div>
    </div>
</div>
<!--筛选页面-->


<!--footer-->
<!--返回顶部-->
<div class="y-ambiuts-footer">
<div class="y-return-top">
    <img src="/theme/mobile/images/y_icon73.png"/>
</div>
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

		



			


    <script type="text/javascript" src="/theme/mobile/js/jquery.cookie.js"></script>
    <script src="http://api.map.baidu.com/api?v=2.0&amp;ak=qbRzMX2V7I3bKRmCfaW9mclbDkaGBLDr"></script>
    <script src="http://api.map.baidu.com/getscript?v=2.0&amp;ak=qbRzMX2V7I3bKRmCfaW9mclbDkaGBLDr" type="text/javascript"></script>
    <link rel="stylesheet" href="/theme/mobile/css/LArea.css">
    <script src="/theme/mobile/js/LArea.js"></script>
    <script src="/theme/mobile/js/LAreaData1.js"></script>
    <script src="/theme/mobile/js/LAreaData2.js"></script>
    <script>



        $(function(){
            //筛选页面
            $(".filtrate-select").click(function(){
                $(".y-ambitus-search").show();
                $(".y-ambitus-main").hide();
                $(".y-ambiuts-footer").hide();
            })
            $(".y-header-left").click(function(){
                $(".y-ambitus-search").hide();
                $(".y-ambitus-main").show();
                $(".y-ambiuts-footer").show();
            })

            //滑动选项卡
            var swiper = new Swiper('.swiper-container',
                {
                    slidesPerView:4,
                });
            $('.y-filtrate-name').click(function(){
                var index=$('.y-filtrate-name').index(this)
                $('.y-filtrate-name').removeClass('active');
                $(this).addClass('active');
                $('.more-kind-ul').eq(index).show().siblings().hide();
            })
            //返回顶部
            var returnTop=false;
            $(".y-return-top").click(function() {
                if (!returnTop) {
                    $("html,body").animate({scrollTop:0}, 500);
                    returnTop=true;
                    var returnTime=setTimeout(function(){
                        returnTop=false;
                    },2000);
                }
            });

            //选择地区
            var area1 = new LArea();
            area1.init({
                'trigger': '#area',
                'valueTo': '#value1',
                'keys': {
                    id: 'id',
                    name: 'name'
                },
                'type': 1,
                'data': LAreaData
            });

            $('#clear_address').click(function(){
                $('input[name="address"]').val('');

            })

            //经纬度
            getAddr();

            //初始化 获取列表
            test(0,0,'','');
            //分类 获取列表
            $('.y-filtrate-name').click(function(){
                $('.more-kind-li').removeClass('active');
                var xx=$(this).data('id');
                var cate_id=(xx==undefined || !xx)?0:xx;
                test(0,cate_id,'','');
            });//一级分类
            $('.two-cate').click(function(){
                var xx=$(this).data('id');
                var cate_id=(xx==undefined || !xx)?0:xx;
                test(0,cate_id,'','');

            })//二级级分类


            //筛选 获取列表
            $('.sub-pass').click(function(){
                $(".y-ambitus-search").hide();
                $(".y-ambitus-main").show();
                $(".y-ambiuts-footer").show();

                var aa=$('.y-filtrate-name.active').data('id');
                var bb=$('.two-cate.active').data('id');
                aa=(aa==undefined || !aa)?0:aa;
                bb=(bb==undefined || !bb)?0:bb;

                var cate_id=(bb)?bb:aa;
                var zftype=$('.more-kind-li.zftype.active').data('zftype');
                var title=$('input[name="title"]').val();
                var address=$('input[name="address"]').val();

                test(zftype,cate_id,title,address);

            })
        })

        //数据
        function test(zftype,cate_id,title,address){
            $('.filtrate-list-ul').html('');
            layui.use('flow', function(){
                var flow = layui.flow;
                flow.load({elem: '.filtrate-list-ul',done: function(page, next){ //到达临界点（默认滚动触发），触发下一页
                        $.get('<?php echo U("merchant_list_ajax");?>'
                            ,{page:page,zftype:zftype,cate_id:cate_id,title:title,address:address}
                            , function(res){

                            next(res[1], page < res[0]);
                        }, 'json');
                    }
                });
            });
        }

        //百度地图获取坐标
        function getAddr() {
            var geolocation = new BMap.Geolocation();
            geolocation.getCurrentPosition(function (r) {
                if (this.getStatus() == BMAP_STATUS_SUCCESS) {
                    // alert(r.point.lng + "," + r.point.lat);
                    $.cookie('lng_lat',r.point.lng + "," + r.point.lat);
                   showPosition(r);
                }
            });
        }

        //百度地图WebAPI 坐标转地址
        function showPosition(r) {
            var url = 'http://api.map.baidu.com/geocoder/v2/?ak=qbRzMX2V7I3bKRmCfaW9mclbDkaGBLDr&callback=?&location=' + r.point.lat + ',' + r.point.lng + '&output=json&pois=1';
            $.getJSON(url, function (res) {
                // alert(res.result.formatted_address);
                $('.orientation-name').html(res.result.addressComponent.district+res.result.addressComponent.street);
            });
        }


        // var store_id = getCookie('store_id');
        // var data_log = "";
        // //var address_info = getCookie('store_info');
        // // if(store_id){
        // //            store_info(address_info,store_id);
        // //        }else{
        // //页面初始加载时选择最近门店
        // var geolocation = new qq.maps.Geolocation("RN5BZ-JAZ2U-FKBVV-4ZPMC-QWM5V-CNBPZ", "myapp");
        // //document.getElementById("pos-area").style.height = (document.body.clientHeight - 110) + 'px';
        // var info =new Object();
        // var positionNum = 0;
        // var options = {timeout: 10000};
        // geolocation.getLocation(showPosition, showErr, options);
        //
        // function showPosition(position) {
        //     data_log = position;
        //     store_info(position,store_id);
        // };
        // // alert(info.lat)
        // function showErr() {
        //     document.getElementById("demo").innerHTML += "序号：" + positionNum;
        // };
        //
        //
        // //选择门店及门店信息
        // function store_info(data,store_id){
        //     $.get("<?php echo U('Index/ambitus_head');?>",{info:data,store_id:store_id},function(msg){
        //         $(".head_content").html(msg);
        //     })
        // }
        // //重新定位
        // function repeat_location(){
        //     clearCookie('store_id');
        //     window.location.reload();
        // }
</script>

	<!-- /底部 -->
</body>
</html>