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
	<!--var SELF = '/index.php?s=/login/register/ewid/9.html';-->
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
				<div class="header-title">注册</div>
				<div class="header-left return-prev"><i class="fa fa-angle-left"></i></div>
				<div class="header-right"></div>
			</div>	
		</div>
		<div class="header-space"></div>
		<!--header-->


		<!--content-->
		<div class="content">
			<div class="enter-form">
			  <form action="" method="post" class="login-form">
				<ul class="enter-form-ul">
					<li class="enter-form-li row-box">
						<div class="enter-form-left">
							<div class="enter-form-icon"><i class="fa fa-user-o"></i></div>
						</div>
						
						<div class="enter-form-right row-flex">
							<div class="enter-form-input vertical-center">
								<div class="vertical-auto" style="width: 100%;">
									<input id="relation_id" name="relation_mobile" placeholder="请输入推荐人手机" value="<?php echo ($relation_mobile); ?>">
								</div>
							</div>
						</div>
					</li>
					
					<!--地址-->
					<li class="enter-form-li row-box">
						<div class="enter-form-left">
							<div class="enter-form-icon"><i class="fa fa-map-marker"></i></div>
						</div>
						<div class="enter-form-right row-flex">
							<div class="enter-form-input">
								<div class="vertical-auto" style="width: 100%;">
									<input  name="address"  class="form-input-celect" id="demo1" type="text" readonly="" placeholder="请选择所在地区" value="">
		            				<input id="value1" type="hidden" >
								</div>
							</div>
						</div>
					
					</li>
					<!--<li class="enter-form-li row-box">
						<div class="enter-form-left">
							<div class="enter-form-icon"><i class="fa fa-map-marker"></i></div>
						</div>
						
						<div class="enter-form-right row-flex">
							
							<div class="area-box">
							<select class="J_cate_place_select mr10" data-pid="0" data-uri="<?php echo U('Login/ajax_getPlace');?>" data-selected="<?php echo ($spid); ?>"></select>
			          	<input type="hidden" name="address" id="J_pid" />

							</div>
						</div>
					</li>-->
					<!--地址-->
					
					<li class="enter-form-li row-box">
						<div class="enter-form-left">
							<div class="enter-form-icon"><i class="fa fa-tablet"></i></div>
						</div>
						
						<div class="enter-form-right row-flex">
							<div class="enter-form-input vertical-center">
								<div class="vertical-auto" style="width: 100%;"><input type="number"  id="mobile" name="mobile" placeholder="请输入手机号"></div>
							</div>
						</div>
					</li>
					
					
					<!--<li class="enter-form-li row-box">
						<div class="enter-form-left">
							<div class="enter-form-icon"><i class="fa fa-hdd-o"></i></div>
						</div>-->
						
						<!--<div class="enter-form-right row-flex">
							<div class="enter-form-input vertical-center">
								<div class="vertical-auto" style="width: 100%;"><input name="yzm_code" id="yzm_code" type="" placeholder="请输入图形验证码"></div>
							</div>
						</div>-->
						
						<!--<div class="enter-form-verify">
							<div class="verification-input" style="background-color: #fff;">
								<img id="click_yzm" src="verifyImg"  onclick="this.src='/index.php?s=/Login/verifyImg/'+Math.random()" alt="" />
								<!--<img src="/theme/mobile/images/0.gif">-->
							<!--</div>
						</div>
					</li>-->
					
					<li class="enter-form-li row-box">
						<div class="enter-form-left">
							<div class="enter-form-icon"><i class="fa fa-hdd-o"></i></div>
						</div>
						
						<div class="enter-form-right row-flex">
							<div class="enter-form-input vertical-center">
								<div class="vertical-auto" style="width: 100%;"><input id="m_code" type="number" name="m_code" placeholder="请输入验证码"></div>
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
								<div class="vertical-auto" style="width: 100%;"><input id="password" type="password" name="password" placeholder="请输入密码"></div>
							</div>
						</div>
					</li>
					
					<li class="enter-form-li row-box">
						<div class="enter-form-left">
							<div class="enter-form-icon"><i class="fa fa-lock"></i></div>
						</div>
						
						<div class="enter-form-right row-flex">
							<div class="enter-form-input vertical-center">
								<div class="vertical-auto" style="width: 100%;"><input id="pass" type="password" name="confirm_password" placeholder="请再次输入密码"></div>
							</div>
						</div>
					</li>
				</ul>
				
				<div class="enter-btn">
				<!--	<button class="open-succeed-win" type="button">注册</button>-->
					 <button class="login-btn" type="submit">
注册
                            </button>
                            <div class="check-tips"></div>
				</div>
				
				<div class="enter-bottom-link">
					<div class="enroll-deal-switch">
						<label for="deal">
							<div class="enroll-deal-input active">
								<input type="checkbox" checked="checked" id="deal">
								<i class="fa fa-check-circle"></i>
							</div>
						</label>
						<div class="enroll-deal-ok active">同意</div>
					</div>
					<a class="enter-bottom-pass active" href="<?php echo U('Index/about',array('type'=>1));?>">《用户协议》</a>
					<a class="enter-bottom-enroll" href="<?php echo U('Login/enter');?>">已有账号<span>请登录</span></a>
					<div class="clear-float"></div>
				</div>
				</form>
			</div>
		</div>
		<!--content-->
		
		<!--注册成功弹窗-->
		<div class="succeed-win">
			<div class="succeed-inner-box vertical-center">
				<div class="vertical-auto" style="width: 100%;">
					<div class="succeed-content">
						<div class="succeed-win-title">注册成功！</div>
						<div class="succeed-win-text">
							恭喜你注册成功，您可以立即登录或完善资料成为商家
						</div>
						<div class="succeed-win-a">
							<a class="succeed-win-link" href="<?php echo U('Login/enter');?>">立即登录</a>
							<a class="succeed-win-link two" href="personage.html">完善资料</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--注册成功弹窗-->
		
		
	<!-- /主体 -->

	<!-- 底部 -->
			


	<link rel="stylesheet" href="/theme/mobile/css/LArea.css">
	<script src="/theme/mobile/js/LArea.js"></script>
	<script src="/theme/mobile/js/LAreaData1.js"></script>
	<script src="/theme/mobile/js/LAreaData2.js"></script>
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
</script>
 <script>	
		/* 表单获取焦点变色 */
    	$(".login-form").on("focus", "input", function(){
            $(this).closest('.item').addClass('focus');
        }).on("blur","input",function(){
            $(this).closest('.item').removeClass('focus');
        });
        
//注册  表单提交验证
$('form').submit(function(){

	if(!$('#mobile').val()){
		layer.msg('请填写手机号码！',{icon:0,time:1000})
		return false;
	}
    if(!$('input[name="address"]').val()){
        layer.msg('请选择地址！',{icon:0,time:1000})
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
	//密码验证
    var password=$('#password').val();
	if(!password){
		layer.msg('请填写密码！',{icon:0,time:1000})
		return false;
	}
    if(password.length<6||password.length>16){
        layer.msg('密码长度在6~16',{icon:0,time:1000})
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
	
	//点击注册  开始后台验证
	$.post('<?php echo U("login/register");?>',data,function(d){
		if(d.status==1){
			 layer.msg(d.msg, {icon:1,time: 2000, btn: ['好的!']},function(){
			  	window.location.href=d.url;
			  });
		}else{
			layer.msg(d.msg,{icon:0,time:2000})
		}
	},"JSON")
	return false;
})    

//注册   点击获取手机验证码  前的验证
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
		$('.verification').val(''+t+'秒').attr('disabled',true);
		setTimeout('de_time()',1000);  

	} else {
		t=60;
//		$('.verification').css('background-color','#D91023');
//		$('.verification').css('border',' none');
		$('.verification').val('获取验证码');
		$('.verification').attr('disabled',false);
	}
}	    

//获取验证码倒计时
//var COUNTDOWN = 60;//计时
//function settime(obj){
//  if (COUNTDOWN == 0) {    
//      obj.value="获取验证码";
//      COUNTDOWN = 60; 
//      return;
//  }else{ 
//      obj.value = COUNTDOWN + "秒"; 
//      COUNTDOWN--;
//  }
//	setTimeout(function(){settime(obj)},1000); 
//}

//$(".verification").click(function(){
//	if($(this).val() != "获取验证码"){
//		return false;
//	}else{
//		settime(this);
//	}
//});

</script>

	<!-- /底部 -->
</body>
</html>