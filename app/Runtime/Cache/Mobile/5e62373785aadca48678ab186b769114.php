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
	<!--var SELF = '/index.php?s=/Merchant/merchant_add.html';-->
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
            <div class="header-title">申请成为商家</div>
            <div class="header-left return-prev"><i class="fa fa-angle-left"></i></div>
            <div class="header-right"></div>
        </div>
    </div>
    <div class="header-space"></div>
    <!--header-->

    <!--content-->
    <form>
    <div class="content">
        <div class="tobeshop-title">基本资料</div>
        <ul class="add-shop-ul y-width94 y-addshop-ul">
            <li class="add-shop-li row-box">
                <div class="add-shop-name"><span class="y-addshop-msg y-color-red">*</span>商铺名称</div>
                <div class="row-flex">
                    <div class="enter-form-input vertical-center">
                        <div class="vertical-auto w100" ><input name="title" value="<?php echo ($info["title"]); ?>" type="text" placeholder="请输入商铺名称" class="tr" maxlength="10"></div>
                    </div>
                </div>
            </li>
            <li class="add-shop-li row-box flex y-addshop-class">
                <div class="add-shop-name"><span class="y-addshop-msg y-color-red">*</span>行业分类</div>
                <div class="row-flex ali-cen jus-end">
                    <span><?php echo ($info["cate_name"]); ?></span><input type="hidden" name="cate_id" value="<?php echo ($info["cate_id"]); ?>"/>
                    <div class="function-list-arrows"><i class="fa fa-angle-right"></i></div>
                </div>
            </li>
                <!--<li class="add-shop-li row-box flex y-addshop-class">-->
                    <!--<div class="add-shop-name"><span class="y-addshop-msg y-color-red">*</span>行业分类</div>-->
                    <!--<div class="row-flex ali-cen jus-end">-->
                        <!--<div class="function-list-arrows">-->
                            <!--<select class="J_cate_select mr10" data-pid="0" data-uri="<?php echo U('ajax_getchilds');?>" data-selected="<?php echo ($spid); ?>"></select>-->
                            <!--<input type="hidden" name="pid" id="J_cate_id" value="0" />-->
                        <!--</div>-->
                    <!--</div>-->
                <!--</li>-->

            <li class="add-shop-li row-box">
                <div class="add-shop-name"><span class="y-addshop-msg y-color-red">*</span>推荐人号码</div>
                <div class="row-flex">
                    <div class="enter-form-input vertical-center">
                        <div class="vertical-auto w100" ><input type="number" name="tuijian" value="<?php echo ($info["tuijian"]); ?>" placeholder="请输入推荐人号码" class="tr tel" maxlength="11"></div>
                    </div>
                </div>
            </li>
            <li class="add-shop-li row-box">
                <div class="add-shop-name"><span class="y-addshop-msg y-color-red">*</span>店铺电话</div>
                <div class="row-flex">
                    <div class="enter-form-input vertical-center">
                        <div class="vertical-auto w100" ><input type="number" name="tel" value="<?php echo ($info["tel"]); ?>" placeholder="请输入店铺电话" class="tr tel" maxlength="11"></div>
                    </div>
                </div>
            </li>
        </ul>
        <div class="tobeshop-title">资质信息</div>
        <div class="add-shop-message y-add-shop-message">
            <div class="message-title y-color-333"><span class="y-addshop-msg y-color-red">*</span>请上传营业执照
                <span class="y-color-red font-20">(为保证顺利开通店铺，请上传清晰营业执照)</span></div>
            <div class="discuss-wrap">
                <div class="evaluate-wrap-box" id="img_box2">
                    <div class="evaluate-img-tier">
                        <div class="add-img-icon y-add-img-icon"   id="add_img2"><i class="fa fa-camera"></i></div><!--上传图片按钮-->
                    </div>
                    <!--<div class="flex y-addshop-addimg flex-warp">-->
                        <!--<div class="message-btn uploadimg">-->
                            <!--<img src="" alt="" />-->
                            <!--<input type="file" class="file" accept="image/*" multiple="multiple"/>--><!--上传图片按钮-->
                        <!--</div>-->
                    <!--</div>-->

                    <div class="evaluate-img-tier close-event vertical-center">
                        <div class="vertical-auto"><img src="<?php echo attach($info['yy_img'],'merchant_yyimg');?>" id="img_2"><input name="img_2[]" value="<?php echo ($info['yy_img']); ?>"></div>
                        <div class="close-evaluate"><i class="fa fa-close"></i></div>
                    </div>
                    <!--<div class="clear-float"></div>-->
                </div>
            </div>

            <div class="message-title y-border-top y-mar-top20 y-color-333"><span class="y-addshop-msg y-color-red">*</span>请上传店铺图片
                <span class="y-color-red font-20">(最多可设置8张附图)</span></div>
            <div class="discuss-wrap">
                <div class="evaluate-wrap-box" id="img_box1">
                        <div class="evaluate-img-tier">
                            <div class="add-img-icon y-add-img-icon"   id="add_img1"><i class="fa fa-camera"></i></div>
                        </div>
                        <?php if(is_array($info['imgs'])): $i = 0; $__LIST__ = $info['imgs'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><div class="evaluate-img-tier close-event vertical-center append-img2">
                                <div class="vertical-auto"><img  src="<?php echo attach($val['img'],'merchant_img');?>" >
                                    <input type="hidden" name="img_1[]"  value="<?php echo ($val['img']); ?>"></div>
                                <div class="close-evaluate"><i class="fa fa-close"></i></div>
                            </div><?php endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                    <!--<div class="clear-float"></div>-->
                </div>
            </div>

            <!--<div class="message-title y-color-333 font-26"><span class="y-addshop-msg y-color-red">*</span>请上传营业执照<span class="y-color-red font-20">(为保证顺利开通店铺，请上传清晰营业执照)</span>-->
            <!--</div>-->
            <!--<div class="flex y-addshop-addimg flex-warp">-->
                <!--<div class="message-btn uploadimg">-->
                    <!--<img src="" alt="" />-->
                    <!--<input type="file" class="file onefile" accept="image/*"/>-->
                <!--</div>-->
            <!--</div>-->
            <!--<div class="message-title y-border-top y-mar-top20 y-color-333 font-26"><span class="y-addshop-msg y-color-red">*</span>请上传店铺图片<span class="y-color-red font-20">(最多可设置8张附图)</span>-->
            <!--</div>-->
            <!--<div class="flex y-addshop-addimg flex-warp">-->
                <!--<div class="message-btn uploadimg">-->
                    <!--<img src="" alt="" />-->
                    <!--<input type="file" class="file" accept="image/*" multiple="multiple"/>-->
                <!--</div>-->
            <!--</div>-->
        </div>

        <div class="tobeshop-title">地址信息</div>
        <ul class="add-shop-ul">
            <li class="add-shop-li row-box">
                <div class="add-shop-name"><span class="y-addshop-msg y-color-red">*</span>省市区</div>
                <div class="row-flex">
                    <div class="enter-form-input">
                        <div class="vertical-auto" style="width: 100%;">
                            <input class="form-input-celect" name="region" value="<?php echo ($info['region']); ?>" id="demo1" type="text" readonly="" placeholder="请选择所在地区！" value="" readonly="readonly">
                            <input id="value1" type="hidden" value="20,234,504">
                        </div>
                    </div>
                </div>
            </li>

            <li class="add-shop-li row-box">
                <div class="add-shop-name"><span class="y-addshop-msg y-color-red">*</span>地图定位</div>
                <div class="row-flex">
                    <div class="enter-form-input">
                        <div class="vertical-auto" style="width: 100%;">
                            <input class="form-input-celect"  id="address_info" type="text" readonly placeholder="请点击获取地址！" value="<?php echo ($info['address']); ?>">
                            <input name="longitude" type="hidden" value="<?php echo ($info['longitude']); ?>" />
                            <input name="latitude" type="hidden" value="<?php echo ($info['latitude']); ?>" />
                            <input name="address" type="hidden" value="<?php echo ($info['address']); ?>" />
                        </div>
                    </div>
                </div>
            </li>

            <!--<li class="add-shop-li row-box">-->
                <!--<div class="add-shop-name"><span class="y-addshop-msg y-color-red">*</span>当前坐标</div>-->
                <!--<div class="row-flex">-->
                    <!--<div class="enter-form-input vertical-center">-->
                        <!--<div class="vertical-auto" style="width: 100%;"><input id="desc"  type="text" readonly></div>-->
                    <!--</div>-->
                <!--</div>-->
            <!--</li>-->

            <!--<li class="add-shop-li row-box flex"><a href="map_position.html" class="row-box flex1">-->
                <!--<div class="add-shop-name"><span class="y-addshop-msg y-color-red">*</span>地图定位</div>-->
                <!--<div class="row-flex ali-cen jus-end">-->
                    <!--<div class="function-list-arrows"><i class="fa fa-angle-right"></i></div>-->
                <!--</div>-->
            <!--</a></li>-->

        </ul>
        <input type="hidden" name="merchant_id" value="<?php echo ($merchant["id"]); ?>">
        <div class="submit-button"><a href="javascript:;" id="sub">确定加入</a></div>
    </div>
    </form>


    <!--content-->
    <!--选择分类页面-->
        <div class="y-select-classify y-bg-white y-hide">
        <div class="header-wrap">
            <div class="header-inner">
                <div class="header-title">行业分类</div>
                <div class="header-left y-left-icon"><i class="fa fa-angle-left"></i></div>
            </div>
        </div>
        <div class="header-space"></div>

        <div class="industry-con flex">
            <ul class="industry-left">
                <?php if(is_array($cate)): $i = 0; $__LIST__ = $cate;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><li class="flex ali-cen y-bg-white <?php if($i == 1): ?>y-color-red<?php endif; ?>">
                        <div class="flex1"><span><?php echo ($val["name"]); ?></span></div>
                        <div class="function-list-arrows"><i class="fa fa-angle-right"></i></div>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
            <div class="flex1">
                <?php if(is_array($cate)): $i = 0; $__LIST__ = $cate;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><ul class="industry-right <?php if($i == 1): ?>y-block<?php endif; ?>">
                        <?php if(is_array($cate_next)): $i = 0; $__LIST__ = $cate_next;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i; if($val['id'] == $vv['pid']): ?><li class="flex ali-cen" data-id="<?php echo ($vv['id']); ?>">
                                    <div class="flex1"><span><?php echo ($vv["name"]); ?></span></div>
                                    <div class="function-list-arrows"><i class="fa fa-angle-right"></i></div>
                                </li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                    </ul><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
        </div>
        <!--选择分类-->
    <!--地图-->
    <div id="html_address" style="display: none;">
        <div style="width: 100%;height: 100%;position: fixed;top: 0;left: 0;z-indx5">
            <iframe id="mapPage" width="100%" height="100%" frameborder=0
                    src="http://apis.map.qq.com/tools/locpicker?search=1&type=1&key=RN5BZ-JAZ2U-FKBVV-4ZPMC-QWM5V-CNBPZ&referer=myapp">
            </iframe>
        </div>
    </div>
    <!--地图-->
</body>

	<!-- /主体 -->

	<!-- 底部 -->
			


<link rel="stylesheet" href="/theme/mobile/css/LArea.css">
<script src="/theme/mobile/js/LArea.js"></script>
<script src="/theme/mobile/js/LAreaData1.js"></script>
<script src="/theme/mobile/js/LAreaData2.js"></script>
<!--微信多图上传-->
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<!--分类联动-->
<!--<script src="/theme/admin/js/jquery/jquery.js"></script>-->
<!--<script src="/theme/admin/js/admin.js"></script>-->

<script>

        //分类联动
        // $('.J_cate_select').cate_select({top_option:lang.all});

        //地图选点
        $('#address_info').click(function(){
            $('#html_address').show();
            layer.open({
                type: 1
                ,content: $('#html_address').html()
                ,anim: 'up'
                ,style: 'position:fixed; left:0; top:0; width:100%; height:100%; border: none; -webkit-animation-duration: .5s; animation-duration: .5s;'
            });

        })
        window.addEventListener('message', function(event) {
            // 接收位置信息，用户选择确认位置点后选点组件会触发该事件，回传用户的位置信息
            var loc = event.data;
            //防止其他应用也会向该页面post信息，需判断module是否为'locationPicker'
            if (loc && loc.module == 'locationPicker') {
                var lat = loc.latlng.lat,
                    city=loc.cityname,
                    lng = loc.latlng.lng;
                $('input[name=address]').val(loc.poiaddress);
                $('input[name=longitude]').val(lng);
                $('input[name=latitude]').val(lat);

                $('#address_info').val(loc.poiaddress);
                $('#desc').val(lat+','+lng);
                layer.closeAll();
                $('#html_address').hide();
            }
        }, false);

    </script>

<script>
    //选择地区
    var area1 = new LArea();
    area1.init({
        'trigger': '#demo1',
        'valueTo': '#value1',
        'keys': {
            id: 'id',
            name: 'name'
        },
        'type': 1,
        'data': LAreaData
    });
    //显隐藏选择分类页面
    $(".y-left-icon").click(function(){
        $(".y-select-classify").hide();
        $(".content").show();
    })
    $(".industry-right li").click(function(){
        $(".y-select-classify").hide();
        //行业分类
        $('.row-flex.ali-cen.jus-end').find('span').html($(this).find('span').html());
        $('input[name="cate_id"]').val($(this).data('id'));
        $(".content").show();
    })
    $(".y-addshop-class").click(function(){
        $(".y-select-classify").show();
        $(".content").hide();
    })
    //分类选择联动
    $(".industry-left li").click(function(){
            $(this).addClass("y-bg-white y-color-red").siblings().removeClass("y-bg-white y-color-red")
            $(".industry-right").eq($(this).index()).show().siblings().hide();
    })

</script>

<script>
    // if($('#img_2').find('input').val()==''){
    //     $('#img_2').parent().parent().hide();
    // }
    //表单提交
    $('#sub').click(function(){
        if(!$('input[name="title"]').val()){
            layer.msg($('input[name="title"]').attr('placeholder'),{icon:0,time:1000})
            return false;
        }
        if(!$('input[name="region"]').val()){
            layer.msg($('input[name="region"]').attr('placeholder'),{icon:0,time:1000})
            return false;
        }

        //店铺多图数量验证
        if($('.append-img2').length > 8){
            layer.msg('最多可设置8张图',{icon:0,time:1000})
            return false;
        }

        var data = $('form').serialize();
        //开始后台验证
        $.post('<?php echo U("merchant_update");?>',data,function(d){
            if(d.status==1){
                layer.msg(d.msg, {icon:1,time: 1000},function(){
                    window.location.href=d.url;
                });
            }else{
                layer.msg(d.msg,{icon:0,time:1000})
            }
        },"JSON")
        return false;
    })
</script>

<script>
    //微信上传图片
    wx.config({
        debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: "<?php echo ($js['appId']); ?>", // 必填，公众号的唯一标识
        timestamp: "<?php echo ($js['timestamp']); ?>", // 必填，生成签名的时间戳
        nonceStr: "<?php echo ($js['nonceStr']); ?>", // 必填，生成签名的随机串
        signature: "<?php echo ($js['signature']); ?>",// 必填，签名，见附录1
        jsApiList: [ 'chooseImage','previewImage','uploadImage','downloadImage','getLocalImgData'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    });
    //店铺多图(做个删除按钮)
    $("#add_img1").click(function(){
        if($('.append-img2').length > 7){
            layer.msg('最多可设置8张图',{icon:0,time:1000});//上传前数量验证
            return false;
        }

        wx.chooseImage({
            count: 8, // 默认9
            sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
            sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
            success: function (res) {
                var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
                uploadImage(localIds,1);
            }
        });
    })
    //营业执照
    $("#add_img2").click(function(){
        wx.chooseImage({
            count: 1, // 默认9
            sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
            sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
            success: function (res) {
                var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
                uploadImage(localIds,2);
            }
        });
    })

    //上传图片
    function uploadImage(localIds,nums)
    {
        var localId = localIds.pop()
        wx.uploadImage({
            localId: localId, // 需要上传的图片的本地ID，由chooseImage接口获得
            isShowProgressTips: 1, // 默认为1，显示进度提示
            success: function (res) {
                var serverId = res.serverId; // 返回图片的服务器端ID
//			    	location.href = "/index.php?m=Mobile&c=Merchant&a=uploadImage&media_id="+serverId;
                download(serverId,nums);//nums2营业执照1店铺多图

//
                if (localIds.length > 0) {
                    uploadImage(localIds,nums)
                }
            }
        });
    }

    //临时文件预览
    function download(serverId,nums) {
        $.post("/index.php?m=Mobile&c=Merchant&a=uploadImage", {'media_id': serverId,'nums':nums}, function (data) {
            var type=(nums==1)?'merchant_img/':'merchant_yyimg/';//nums1店铺多图2营业执照
            if (data.status == 1&&nums==1) {
                $("#img_box"+nums).append(''
                    +'<div class="evaluate-img-tier close-event vertical-center append-img2">'
                    +'<div class="vertical-auto"><img  src="'+'data/attachment/'+type+data.name+'" ><input type="hidden" name="img_'+nums+'[]"  value="'+data.name+'"></div>'
                    +'<div class="close-evaluate"><i class="fa fa-close"></i></div></div>'
                );

            }else if(data.status == 1&&nums==2){
                $('.evaluate-img-tier.close-event.vertical-center').show();
                $("#img_2").attr('src','data/attachment/'+type+data.name);
                $("#img_2").siblings("input").val(data.name);

            }else{
                layer.msg(data.msg);
            }
        }, 'json');
    }

</script>

	<!-- /底部 -->
</body>
</html>