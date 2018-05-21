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
	<!--var SELF = '/index.php?s=/Member/set_person.html';-->
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
				<div class="header-title">个人信息</div>
					<a href="<?php echo U('member/mine');?>" id="go_where">
						<div class="header-left"><i class="fa fa-angle-left"></i></div>
					</a>
				
				<!--<div class="header-right">
					<div class="conserve-btn">保存</div>
				</div>-->
			</div>	
		</div>
		<div class="header-space"></div>
		<!--header-->
		
		<!--content-->
		<div class="content">
			<ul class="set-con-ul">
				<li class="set-con-li person">
					<!--<form action="<?php echo U('member/avatar');?>" method="post" enctype="multipart/form-data">-->
						<div class="set-con-name">个人信息</div>
						<div class="set-con-icon"><i class="fa fa-angle-right"></i></div>
							<div class="person-imges">
								<img id="avatar" src="<?php echo attach($member['avatar'],'avatar');?>">
							</div>
						<!--<input type="file" class="aa">-->
						<input type="" class="aa">
						<div class="clear-float"></div>
					<!--</form>	-->
				</li>
			
				<li class="set-con-li"><a href="<?php echo U('member/set_person',array('is_page'=>1));?>">
					<div class="set-con-name">昵称</div>
					<div class="set-con-icon"><i class="fa fa-angle-right"></i></div>
					<div class="set-con-phone color"><?php echo ($member['nickname']); ?></div>
					<div class="clear-float"></div>
				</a></li>
				
				<li class="set-con-li" id="realname">
					<div class="set-con-name">真实姓名</div>
					<div class="set-con-id" ><?php if($member['type'] == 3): echo ($member["realname"]); endif; ?></div>
					<div class="clear-float"></div>
				</li>
				
				<li class="set-con-li" id="mobile">
					<div class="set-con-name">手机</div>
					<div class="set-con-id"><?php echo ($member["mobile"]); ?></div>
					<div class="clear-float"></div>
				</li>
				
				<li class="set-con-li"><a href="<?php echo U('member/set_person',array('is_page'=>2));?>">
					<div class="set-con-name">性别</div>
					<div class="set-con-icon"><i class="fa fa-angle-right"></i></div>
					<div class="set-con-phone color">
						<?php if($member['sex'] == 1): ?>男<?php elseif($member['sex'] == 2): ?>女
						<?php else: ?>未选择<?php endif; ?>

					</div>
					<div class="clear-float"></div>
				</a></li>
				
				<li class="set-con-li"><a href="javascript:;">
					<div class="set-con-name">地区</div>
					<div class="set-con-icon"><i class="fa fa-angle-right"></i></div>
					<div class="set-con-phone color"><?php echo ($member['region']); ?></div>
					<div class="clear-float"></div>
				</a></li>
			</ul>
			
			<div class="secede-txt">退出登录</div>
			
		</div>
		<!--content-->
		
		<!--footer--><!--间距-->
	</body>

	<!-- /主体 -->

	<!-- 底部 -->
			


<script type="text/javascript" src="/theme/mobile/js/jquery.cookie.js"></script> 	
<script>
    //用户名字提示实名认证
    // $('#realname').click(function(){
    //     layer.confirm('实名认证,享受更多权力', {icon:3,btn: ['确定','取消']},function(){
    //         window.location.href='<?php echo U("member/set_attestation");?>';
    //     })
    // })
    //实名认证
    $('#realname').click(function(){
        var type="<?php echo ($member['type']); ?>";//2实名认证中, 3已实名认证， 4实名认证失败
        if(type==2){
            layer.msg('实名认证中');

        }else if(type==3){
            layer.msg('已实名认证');
        }else{
            location.href="<?php echo U('member/set_attestation');?>";
        }
    })
	// var is_mine=$.cookie('is_mine');
	// if(typeof(is_mine)=="undefined" || (is_mine==null)){
	// 	is_mine=1;
	// }
	//
	// $(function(){
	// 	if(is_mine==1){
	// 		$('#go_where').attr('href','<?php echo U("member/mine");?>');
	// 	}
	// 	if(is_mine==2){
	// 		$('#go_where').attr('href','<?php echo U("member/myset");?>');
	// 	}
	// })
</script>	
	
	
<script>
	//退出登录	
	$('.secede-txt').click(function(){
	  layer.confirm('是否退出登录?', {icon: 3,title: '提示'},function(){
			$.get('<?php echo U("login/login_out");?>',function(d){
				if(d.status==1){
//					layer.msg(''+d.msg+'');
					window.location.href=d.url;
				}
			},"JSON")
		})
	}) 
	

	//手机已绑定
	$('#mobile').click(function(){
		layer.msg('手机已绑定！',{icon:0,time:2000})
	})
	
	</script>
	<!--微信多图上传-->
	<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script>
		wx.config({  
	        debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
	        appId: "<?php echo ($js['appId']); ?>", // 必填，公众号的唯一标识
	        timestamp: "<?php echo ($js['timestamp']); ?>", // 必填，生成签名的时间戳
	        nonceStr: "<?php echo ($js['nonceStr']); ?>", // 必填，生成签名的随机串
	        signature: "<?php echo ($js['signature']); ?>",// 必填，签名，见附录1
	        jsApiList: [ 'chooseImage','previewImage','uploadImage','downloadImage','getLocalImgData'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
	    }); 
    	//选择图片
	    $(".aa").click(function(){
		    wx.chooseImage({
			    count: 1, // 默认9
			    sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
			    sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
			    success: function (res) {
			        var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
			        uploadImage(localIds);
			    }
			});
		})
	    
	    //上传图片
	    function uploadImage(localIds)
	    {
	    	var localId = localIds.pop()
	    	wx.uploadImage({
			    localId: localId, // 需要上传的图片的本地ID，由chooseImage接口获得
			    isShowProgressTips: 1, // 默认为1，显示进度提示
			    success: function (res) {
			    	var serverId = res.serverId; // 返回图片的服务器端ID
//			    	location.href = "/index.php?m=Mobile&c=Member&a=uploadImage&media_id="+serverId;
		    	 	 download(serverId);
			    	if (localIds.length > 0) {  
			    		uploadImage(localIds)
			    	} 
			    }
			});
	    }
	    
	     //临时文件预览
       function download(serverId) {
//      	location.href = "/index.php?m=Mobile&c=Merchant&a=uploadImage&media_id="+serverId;
            $.post("/index.php?m=Mobile&c=Member&a=uploadImage", {'media_id': serverId}, function (data) {
            	if (data.status == 1) {
					$.post('',{avatar:data.name,is_avatar:1},function(mess){
						if(mess.status==1){
                            layer.msg(mess.msg,{icon:1,time:1000},function(){
                                $(".person-imges").html(''
                                    +'<img  src="data/attachment/avatar/'+data.name+'" />'
                                    +'<input type="hidden" name="avatar"  value="">');
							})
						}else{
                            layer.msg(mess.msg,{icon:0,time:1000})
						}
					});
					
                }else{
                    layer.msg(data.msg,{icon:0,time:1000})
				}
            }, 'json');
        }
	
	
	
	
	
	
	
	//上传头像
//	$('.aa').change(function() {
//		var a = $(this);
//		var fd = new FormData('form[name="avatar"]');//序列化
//		fd.append('file', a[0].files[0]);
//	
//		if(a.val() != '') {
//			$.ajax({
//				url: "<?php echo U('Member/zed');?>",
//				dataType: "JSON",
//				data: fd,
//				type: "POST",
//				contentType: false,
//				processData: false,
//				success: function(d) {
//					if(d != 0) {
//						$.post('',{avatar:d['img'],is_avatar:1},function(mess){
//							if(mess.status==1){
//								$('#avatar').attr('src', '/data/attachment/useravatar/'+d['img']);//展示头像
//								$('.hide').val(d['img']);//上传后返回的照片名字
//							}else{
//								layer.msg(mess.msg,{icon:0,time:2000})
//							}
//							
//						})
//						
//						
//					}
//				}
//			})
//		}
//	})
//})
</script>

	<!-- /底部 -->
</body>
</html>