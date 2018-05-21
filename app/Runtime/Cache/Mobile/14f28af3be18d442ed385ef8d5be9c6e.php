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
	<!--var SELF = '/index.php?s=/Login/enter.html';-->
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
			<div class="header-title">登录</div>
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
								<div class="vertical-auto" style="width: 100%;">
									<!--<?php if(session('user_auth')): ?>-->
									<!--<input id="mobile" name="mobile" type="number" placeholder="请输入手机号" value="<?php echo session('user_auth')['mobile'];?>"></div>-->
									<!--<?php else: ?>-->
									<!--<input id="mobile" name="mobile" type="number" placeholder="请输入手机号" value="<?php echo cookie('user_auth')['mobile'];?>"></div>-->
									<!--<?php endif; ?>-->
									<input id="mobile" name="mobile" type="number" placeholder="请输入手机号" value="<?php echo cookie('user_auth')['mobile'];?>"></div>
							</div>
						</div>
					</li>

					<li class="enter-form-li row-box">
						<div class="enter-form-left">
							<div class="enter-form-icon"><i class="fa fa-lock"></i></div>
						</div>

						<div class="enter-form-right row-flex">
							<div class="enter-form-input vertical-center">
								<div class="vertical-auto" style="width: 100%;">
									<?php if(session('user_auth')): ?><input value="<?php echo session('user_auth')['password'];?>" id="password" name="password" type="password" placeholder="请输入密码">
										<?php else: ?>
										<input value="<?php echo cookie('user_auth')['password'];?>" id="password" name="password" type="password" placeholder="请输入密码"><?php endif; ?>
								</div>
							</div>
						</div>
					</li>
				</ul>

				<div class="enter-btn">
					<button type="submit">登录</button>
				</div>

				<div class="enter-bottom-link">
					<a class="enter-bottom-pass" href="<?php echo U('Login/forgot_pwd');?>">忘记密码？</a>
					<a class="enter-bottom-enroll" href="<?php echo U('Login/register');?>">立即注册</a>
					<div class="clear-float"></div>
				</div>
			</div>
		</form>

		<div class="thirdparty">
			<div class="row-box">
				<div class="row-flex">
					<div class="thirdparty-line"></div>
				</div>

				<div class="thirdparty-txt">第三方账户登录</div>

				<div class="row-flex">
					<div class="thirdparty-line"></div>
				</div>
			</div>

			<div class="row-box thirdparty-icon">
				<!--<div class="row-flex">
                    <div class="thirdparty-link one"><a href=""></i></a></div>
                </div>-->

				<div class="row-flex">
					<div class="thirdparty-link two"><a href="javascript:;" id="wx_enter"><i class="fa fa-weixin"></i></a></div>
				</div>

				<!--<div class="row-flex">
                    <div class="thirdparty-link three"><a href=""><i class="fa fa-weibo"></i></a></div>
                </div>-->
			</div>
		</div>
	</div>
	<!--content-->

	<!-- /主体 -->

	<!-- 底部 -->
			


	<script src="http://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js"></script>
	<script>
        //微信
        $("#wx_enter").click(function(){
            return false;
            // var appid='<?php echo C("wx_pay_config")["appid"];?>';
            // var wx_url= 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='+appid+'&redirect_uri='
            // +'http://zhshg.0791jr.com'+'<?php echo U("login/wx_login");?>'
            // +'&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect';
            // window.location.href=wx_url;
            layer.confirm('是否微信授权登录？', { btn: ['是的','取消']}, function(){
                //开始后台验证
                $.get('<?php echo U("login/wx_login");?>','',function(d){
                    if(d.status==1){
                        layer.msg(d.msg,{icon:1,time:1000},function(){
                            window.location.href=d.url;
                        })
                    }else{
                        layer.msg(d.msg,{icon:0,time:1000},function(){
                            window.location.href=d.url;
                        })
                    }
                },"JSON")
            })
        })

	</script>
	<script>
        //是否已登录
        var is_login = '<?php echo is_login();?>';
        if(is_login != 0){
            var conf = confirm('检测到您已登录,是否登录？');
            if(conf){
                window.location.href='<?php echo U("member/mine");?>';
            }
        }

        $('form').submit(function(){
            var reg = /1[3|4|5|7|8][0-9]{9}/;//手机格式
//		var eg = /^(?![0-9]*$)[a-zA-Z0-9]{6,20}$/;
            if(!$('#mobile').val()){
                layer.msg('请填写手机号码！',{icon:0,time:2000})
                return false;
            }
//		if(!reg.test($('#mobile').val())){
//			layer.msg('手机号码格式不正确！',{icon:0,time:2000})
//			return false;
//		}

            if(!$('#password').val()){
                layer.msg('请填写密码！',{icon:0,time:2000})
                return false;
            }
//		if(!eg.test($('#password').val())){
//			layer.msg('密码格式有误！',{icon:0,time:2000})
//			return false;
//		}

            var data = $(this).serialize();
            //开始后台验证
            $.post('<?php echo U("login/enter");?>',data,function(d){
                if(d.status==1){
                    layer.msg(''+d.msg+'',{icon:1,time:2000},function(){
                        window.location.href=d.url;
                    })
                }else{
                    layer.msg(''+d.msg+'',{icon:0,time:2000})
                }
            },"JSON")
            return false;
        })

	</script>

	<!-- /底部 -->
</body>
</html>