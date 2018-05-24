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
	<!--var URL = '/index.php?s=/Login';-->
	<!--var SELF = '/index.php?s=/Login/forgot_pwd.html';-->
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
				<div class="header-title">忘记密码</div>
				<div class="header-left return-prev"><i class="fa fa-angle-left"></i></div>
				<div class="header-right"></div>
			</div>	
		</div>
		<div class="header-space"></div>
		<!--header-->
		
		<!--content-->
		<div class="content">
			<form action="" method="post" >
			<div class="enter-form">
				<ul class="enter-form-ul">
					<li class="enter-form-li row-box">
						<div class="enter-form-left">
							<div class="enter-form-icon"><i class="fa fa-tablet"></i></div>
						</div>
						
						<div class="enter-form-right row-flex">
							<div class="enter-form-input vertical-center">
								<div class="vertical-auto" style="width: 100%;"><input id="mobile" name="mobile" type="number" placeholder="请输入手机号"></div>
							</div>
						</div>
					</li>
					
					<li class="enter-form-li row-box">
						<div class="enter-form-left">
							<div class="enter-form-icon"><i class="fa fa-hdd-o"></i></div>
						</div>
						
						<div class="enter-form-right row-flex">
							<div class="enter-form-input vertical-center">
								<div class="vertical-auto" style="width: 100%;"><input id="m_code" name="m_code" type="number" placeholder="请输入验证码"></div>
							</div>
						</div>
						
						<div class="enter-form-verify">
							<div class="verification-input">
								<input type="button" class="verification" value="获取验证码">
							</div>
						</div>
					</li>
					
					<li class="enter-form-li row-box">
						<div class="enter-form-left">
							<div class="enter-form-icon"><i class="fa fa-lock"></i></div>
						</div>
						
						<div class="enter-form-right row-flex">
							<div class="enter-form-input vertical-center">
								<div class="vertical-auto" style="width: 100%;"><input id="password" name="password" type="password" placeholder="请新输入密码"></div>
							</div>
						</div>
					</li>
					
					<li class="enter-form-li row-box">
						<div class="enter-form-left">
							<div class="enter-form-icon"><i class="fa fa-lock"></i></div>
						</div>
						
						<div class="enter-form-right row-flex">
							<div class="enter-form-input vertical-center">
								<div class="vertical-auto" style="width: 100%;"><input id="pass" type="password" placeholder="再次新输入密码"></div>
							</div>
						</div>
					</li>
				</ul>
				
				<div class="enter-btn">
					<button type="submit">确认</button>
				</div>
			</div>
			</form>
		</div>
		<!--content-->
		
	</body>

	<!-- /主体 -->

	<!-- 底部 -->
			


<script>
	$('form').submit(function(){
		var eg = /^(?![0-9]*$)[a-zA-Z0-9]{6,20}$/;
		if(!$('#mobile').val()){
			layer.msg('请填写手机号码！',{icon:0,time:2000})
			return false;
		}
		var reg = /1[3|4|5|7|8][0-9]{9}/;//手机格式
		if(!reg.test($('#mobile').val())){
			layer.msg('手机号码格式不正确！',{icon:0,time:2000})
			return false;
		}
        if(!$('#m_code').val()){
            layer.msg('请填写手机验证码！',{icon:0,time:1000})
            return false;
        }

        //密码验证
        var password=$('#password').val();
        if(!password){
            layer.msg('请填写密码！',{icon:0,time:1000})
            return false;
        }
        if(password.length<6||password.length>16){
            layer.msg('登录密码长度在6到16位',{icon:0,time:1000})
            return false;
        }
        var pwd = /^[a-zA-Z0-9_]{6,16}$/;//密码格式
        if(!pwd.test(password)){
            layer.msg('密码由数字和字母组合',{icon:0,time:1000})
            return false;
        }

        if(password != $('#pass').val()){
            layer.msg('两次输入密码不一致！',{icon:0,time:1000})
            return false;
        }
	
		var data = $(this).serialize(); 
	
		//点击提交  开始后台验证
		$.post('<?php echo U("login/forgot_pwd");?>',data,function(d){
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
	
//点击获取手机验证码  前的验证
$('.verification').click(function(){	
	var reg = /1[3|4|5|7|8][0-9]{9}/;
	if(!reg.test($('#mobile').val())){
		layer.msg('手机格式不正确！',{icon:0,time:1000})
		return false;
	}
	
	$.post("<?php echo U('login/aa');?>",{mobile:$('#mobile').val()},function(d){
		if(d.status==1){
			layer.msg(''+d.msg+'',{icon:1,time:2000},function(){
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
		$('.verification').val(''+t+'秒后重试').attr('disabled',true);
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