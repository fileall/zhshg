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
	<!--var URL = '/index.php?s=/Member';-->
	<!--var SELF = '/index.php?s=/member/set_attestation.html';-->
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
				<div class="header-title">实名认证</div>
				<!--<?php if($is_w == 1): ?>-->
					<!--<a href="/index.php?s=/member/w_purse.html">-->
						<!--<div class="header-left "><i class="fa fa-angle-left"></i></div>-->
					<!--</a>-->
				<!--<?php else: ?>-->
					<!--<a href="/index.php?s=/member/myset.html">-->
						<!--<div class="header-left"><i class="fa fa-angle-left"></i></div>-->
					<!--</a>-->
				<!--<?php endif; ?>-->
				<div class="header-left return-prev"><i class="fa fa-angle-left"></i></div>

				<div class="header-right"></div>
			</div>
		</div>
		<div class="header-space"></div>
		<!--header-->

		<!--content-->
		<div class="content">
			<form action="" name="sm" >
				<ul class="">
					<li class="enter-form-li row-box">
						<div class="enter-form-txt">真实姓名</div>
						<div class="row-flex">
							<div class="enter-form-input">
								<input type="text" name="realname" placeholder="输入真实姓名">
							</div>
						</div>
					</li>

					<li class="enter-form-li row-box">
						<div class="enter-form-txt">身份证号</div>
						<div class="row-flex">
							<div class="enter-form-input">
								<input type="text" name="id_nums" placeholder="输入身份证号">
							</div>
						</div>
					</li>


					<li class="enter-form-li row-box">
						<div class="add-shop-name">所在地区</div>
						<div class="row-flex">
							<div class="enter-form-input">
								<div class="vertical-auto" style="width: 100%;">
									<input  name="address"  class="form-input-celect" id="demo1" type="text" readonly="" placeholder="请选择所在地区" value="">
		            				<input id="value1" type="hidden" >
								</div>
							</div>
						</div>

					</li>

					<li class="enter-form-li row-box">
						<div class="enter-form-txt">证件照片</div>

						<div class="row-flex"></div>
					</li>
				</ul>

					<div class="uploading-wrap">
						<div class="uploading-tier">
							<div class="uploading-img vertical-center">
								<div class="vertical-auto"><img id="zs1" <?php if(!empty($idcard[1])): ?>src="<?php echo attach($idcard[1],'id_card');?>"<?php endif; ?> ></div>
							</div>

							<div class="uploading-up">上传身份照正面<input class="img_one"  type="" data-nums="1"></div>
						</div>

						<div class="uploading-tier">
							<div class="uploading-img vertical-center">
								<div class="vertical-auto"><img id="zs2" <?php if(!empty($idcard[2])): ?>src="<?php echo attach($idcard[2],'id_card');?>"<?php endif; ?>></div>
							</div>
							<div class="uploading-up">上传身份照反面<input class="img_two"  type="" data-nums="2"></div>
						</div>

						<div class="clear-float"></div>
					</div>

				<div class="enter-form-btn">
					<input name="img_one" type="hidden" value="<?php echo ($idcard[1]); ?>">
					<input name="img_two" type="hidden" value="<?php echo ($idcard[2]); ?>">
					<button type="submit" id="sm">立即认证</button>
				</div>
			</form>
			<form action="" name="imgs" method="post" enctype="multipart/form-data" id="imgs">
				<input type="file" name="imgs1" style="display: none" id="imgs1" class="xxx">
				<input type="file" name="imgs2" style="display: none" id="imgs2" class="xxx">
			</form><!--上传文件图片-->
		</div>
		<!--content-->

		<!--footer-->
		<!--footer-->
	</body>


	<!-- /主体 -->

	<!-- 底部 -->
			


	<link rel="stylesheet" href="/theme/mobile/css/LArea.css">
	<script src="/theme/mobile/js/LArea.js"></script>
	<script src="/theme/mobile/js/LAreaData1.js"></script>
	<script src="/theme/mobile/js/LAreaData2.js"></script>
<script>


    $('.img_one').click(function(){
        $('#imgs1').click();
	});
    $('.img_two').click(function(){
        $('#imgs2').click();
    });
	$('.xxx').change(function(){
        var data = new FormData($('#imgs')[0]);  // 要求使用的html对象
        $.ajax({
            type:"post",
            url:'<?php echo U("member/ajax_idcard");?>',
            async:true,
            data:data,
            async: true,// 下面三个参数要指定，如果不指定，会报一个JQuery的错误
            cache: false,
            contentType: false,
            processData: false,
            success:function(data){
                if (data.status==1) {
                    $("#zs"+data.type).attr('src','data/attachment/id_card/'+data.msg);
                    var name= (data.type==1)?"input[name='img_one']":"input[name='img_two']";
                    $(name).val(data.msg);
                }else{
                    layer.msg(data.msg,{icon:0,time:1000})
                }
            }
        });
    });

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



	$(function(){
		$('form[name="sm"]').submit(function(){
            if(!$('input[name="realname"]').val()){
					layer.msg('请输入姓名！',{icon:0,time:1000})
					return false;
				}
            if(!$('input[name="id_nums"]').val()){
				layer.msg('身份证不能为空！',{icon:0,time:1000})
				return false;
            }
            //地区
            if(!$('input[name="address"]').val()){
				layer.msg('请填写所在地！',{icon:0,time:1000})
				return false;
            }
            if(!$('input[name="img_one"]').val()){
				layer.msg('请上传身份证正面照片！',{icon:0,time:1000})
				return false;
            }
            if(!$('input[name="img_two"]').val()){
                layer.msg('请上传身份证反面照片！',{icon:0,time:1000})
                return false;
            }
			// var data = $(this).serialize();
            var data = new FormData($(this)[0]);  // 要求使用的html对象
			$.ajax({
				type:"post",
				url:'<?php echo U("member/set_attestation");?>',
				async:true,
				data:data,
                async: true,// 下面三个参数要指定，如果不指定，会报一个JQuery的错误
                cache: false,
                contentType: false,
                processData: false,
				success:function(d){
					if(d.status==1){
						 layer.msg(d.msg, {icon:1, time: 2000,btn: ['好的!']
						 },function(){
						  	window.location.href='<?php echo U("member/myset");?>';
						  });
					}else{
						layer.msg(d.msg,{icon:2,time:1000})
					}
				}
			});
			return false;

		})

	})
</script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script>



// 		wx.config({
// 	        debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
// 	        appId: "<?php echo ($js['appId']); ?>", // 必填，公众号的唯一标识
// 	        timestamp: "<?php echo ($js['timestamp']); ?>", // 必填，生成签名的时间戳
// 	        nonceStr: "<?php echo ($js['nonceStr']); ?>", // 必填，生成签名的随机串
// 	        signature: "<?php echo ($js['signature']); ?>",// 必填，签名，见附录1
// 	        jsApiList: [ 'chooseImage','previewImage','uploadImage','downloadImage','getLocalImgData'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
// 	    });
//     	//选择图片
// 	    $(".img_one").click(function(){
// 	    	var nums=$(this).data('nums');
// 		    wx.chooseImage({
// 			    count: 1, // 默认9
// 			    sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
// 			    sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
// 			    success: function (res) {
// 			        var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
// 			        uploadImage(localIds,nums);
// 			    }
// 			});
// 		})
//
// 	    //上传图片
// 	    function uploadImage(localIds,nums)
// 	    {
// 	    	var localId = localIds.pop()
// 	    	wx.uploadImage({
// 			    localId: localId, // 需要上传的图片的本地ID，由chooseImage接口获得
// 			    isShowProgressTips: 1, // 默认为1，显示进度提示
// 			    success: function (res) {
// 			    	var serverId = res.serverId; // 返回图片的服务器端ID
// //			    	location.href = "/index.php?m=Mobile&c=Member&a=uploadImage&media_id="+serverId;
// 		    	 	 download(serverId,nums);
// 			    	if (localIds.length > 0) {
// 			    		uploadImage(localIds)
// 			    	}
// 			    }
// 			});
// 	    }
//
// 	     //临时文件预览
//        function download(serverId,nums) {
// //      	location.href = "/index.php?m=Mobile&c=Merchant&a=uploadImage&media_id="+serverId;
//             $.post("/index.php?m=Mobile&c=Member&a=uploadImage", {'media_id': serverId}, function (data) {
//             	if (data.sta == 1) {
//             		//1正面2反面
//             		var img_='';
//             		if(nums==1){img_='img_one';}
//             		if(nums==2){img_='img_two';}
//
// 					$("#zs"+nums).attr('src','data/attachment/useravatar/'+data.name);
// 					$("input[name='"+img_+"']").val(data.name);
//                 }
//             }, 'json');
//         }
</script>

	<!-- /底部 -->
</body>
</html>