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
	<!--var SELF = '/index.php?s=/member/set_pw_pay/set_pw_pay/3/param/3.html';-->
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
				<div class="header-title">设置支付密码</div>
				<div class="header-left return-prev"><i class="fa fa-angle-left"></i></div>
				<div class="header-right"></div>
			</div>	
		</div>
		<div class="header-space"></div>
		<!--header-->
		
		<!--content-->
		<div class="content">
			<form action="" name="" method="post">
				<ul class="">
					<li class="enter-form-li row-box">
						<div class="enter-form-icon"><i class="fa fa-tablet"></i></div>
						<div class="row-flex">
							<div class="enter-form-input">
								<input type="number" id="mobile" name="mobile" placeholder="输入手机号！" >
							</div>
						</div>
					</li>
					<li class="enter-form-li row-box" style="padding-right: 0;">
						<div class="enter-form-icon"><i class="fa fa-pencil-square-o"></i></div>
						<div class="row-flex">
							<div class="enter-form-input">
								<input id="m_code"  type="bunber" name="m_code" placeholder="请输入验证码">
							</div>
						</div>
						<div class="verification-input">
							<input type="button" class="verification"  value="获取验证码">
						</div>
					</li>
					
					<li class="enter-form-li row-box">
						<div class="enter-form-icon"><i class="fa fa-unlock-alt"></i></div>
						<div class="row-flex">
							<div class="enter-form-input">
								<input id="password" type="password" name="paypassword" placeholder="请输入密码">
							</div>
						</div>
					</li>
					
					<li class="enter-form-li row-box">
						<div class="enter-form-icon"><i class="fa fa-unlock-alt"></i></div>
						<div class="row-flex">
							<div class="enter-form-input">
								<input type="password" id="pass" name="qypassword" placeholder="再次输入密码">
							</div>
						</div>
					</li>
				</ul>
				  
				<div class="enter-form-btn">
					<input type="hidden"  name="set_pw_pay" value="<?php echo ($set_pw_pay); ?>" /><!--页面来源-->
					<input type="hidden"  name="param" value="<?php echo ($param); ?>" /><!--附带参数-->
					<button type="submit">保存</button>
				</div>
			</form>
		</div>
		<!--content-->
		
		<!--footer-->
		<!--footer-->
	</body>

	<!-- /主体 -->

	<!-- 底部 -->
			


<script>
	$('form').submit(function(){
	if(!$('#mobile').val()){
		layer.msg('请填写手机号码！',{icon:0,time:1000})
		return false;
	}
	var reg = /1[3|4|5|7|8][0-9]{9}/;//手机格式
	if(!reg.test($('#mobile').val())){
		layer.msg('手机号码格式不正确！',{icon:0,time:1000})
		return false;
	}
	if(!$('#m_code').val()){
		layer.msg('请填写手机验证码！',{icon:0,time:1000})
		return false;
	}
	
	var eg= /^\d{6}$/;//密码格式
	if(!$('#password').val()){
		layer.msg('请填写密码！',{icon:0,time:1000})
		return false;
	}
	if(!eg.test($('#password').val())){
		layer.msg('支付密码格式为6位数！',{icon:0,time:1000})
		return false;
	}
	if($('#password').val()!=$('#pass').val()){
		layer.msg('两次输入密码不一致！',{icon:0,time:1000})
		return false;
	}

	var data = $(this).serialize(); 
	
	//开始后台验证
	$.post('<?php echo U("member/set_pw_pay");?>',data,function(d){
		if(d.status==1){
			layer.msg(d.msg,{icon:1,time:1000},function(){
                location.href=d.url;
			})
		}else{
			layer.msg(d.msg,{icon:0,time:1000})
		}
	},"JSON")
	return false;
})

//点击获取手机验证码
$('.verification').click(function(){	
	var reg = /1[3|4|5|7|8][0-9]{9}/;
	if(!reg.test($('#mobile').val())){
		layer.msg('手机格式不正确！',{icon:0,time:1000})
		return false;
	}
	
	$.post("<?php echo U('login/aa');?>",{mobile:$('#mobile').val()},function(d){
		if(d.status==1){
			layer.msg(''+d.msg+'',{icon:1,time:1000},function(){
				de_time();//倒计时
			})
			
		}else{
			layer.msg(''+d.msg+'',{icon:2,time:1000})
		}
	
	},"JSON")
	
})   
 //倒计时
var t = 60;
function de_time(){
	if(t > 0){
		t--;    
		$('.verification').val(''+t+'秒').attr('disabled',true);
		setTimeout('de_time()',1000);  
	} else {
		t=60;
		$('.verification').val('获取验证码');
		$('.verification').attr('disabled',false);
	}
}	    
</script>	

	<!-- /底部 -->
</body>
</html>