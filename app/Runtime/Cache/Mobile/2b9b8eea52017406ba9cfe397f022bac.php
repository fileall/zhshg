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
	<!--var URL = '/index.php?s=/Wallet';-->
	<!--var SELF = '/index.php?s=/wallet/pay_to_merchant/transfer_type/1.html&code=061tL4HN09RX242eqGHN0q6YGN0tL4HR&state=STATE';-->
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
        <div class="header-title"><?php echo ($field); ?>支付</div>
        <div class="header-left return-prev"><i class="fa fa-angle-left"></i></div>
        <div class="header-right"></div>
    </div>
</div>
<div class="header-space"></div>
<!--header-->

<!--content-->
<div class="content y-mar-top20">
    <a href="javascript:;" >
        <div class="row-box sweep-wrap" id="scanQRCode" >
            <div class="sweep-left">
                <div class="sweep-icon"><img src="/theme/mobile/images/icon01.png"></div>
            </div>
            <div class="row-flex">
                <div class="sweep-name">扫一扫</div>
            </div>
            <div class="sweep-right">
                <div class="sweep-right-icon"><i class="fa fa-angle-right"></i></div>
            </div>
        </div>
    </a>
    <!--<a href="javascript:;">-->
        <!--<div class="row-box sweep-wrap y-glod-codepay">-->
            <!--<input class="y-gold-codepay-input" type="file" accept="image/*" capture="camera">-->
            <!--<div class="sweep-left">-->
                <!--<div class="sweep-icon"><img src="/theme/mobile/images/icon01.png"></div>-->
            <!--</div>-->
            <!--<div class="row-flex">-->
                <!--<div class="sweep-name">扫一扫</div>-->
            <!--</div>-->
            <!--<div class="sweep-right">-->
                <!--<div class="sweep-right-icon"><i class="fa fa-angle-right"></i></div>-->
            <!--</div>-->

        <!--</div>-->
    <!--</a>-->

    <form action="" name="" method="post">
        <ul class="y-border-top">
            <li class="enter-form-li row-box">
                <div class="enter-form-txt">或输入交易账户</div>
                <div class="row-flex">
                    <div class="enter-form-input">
                        <input type="number" name="oldpassword" placeholder="请输入电话号码" id="mobile">
                    </div>
                </div>
            </li>

            <li class="enter-form-li row-box">
                <div class="enter-form-txt">输入<?php echo ($field); ?>金额</div>
                <div class="row-flex">
                    <div class="enter-form-input">
                        <input type="number" name="newpassword" placeholder="0" id="nums">
                    </div>
                </div>
            </li>

            <!--<li class="enter-form-li row-box">-->
                <!--<div class="enter-form-txt">赠送银币数量</div>-->
                <!--<div class="row-flex">-->
                    <!--<div class="enter-form-input">-->
                        <!--<input type="number" name="newpassword" readonly="readonly" value="0">-->
                    <!--</div>-->
                <!--</div>-->
            <!--</li>-->
        </ul>

        <!--<div class="illustrate">元宝将实时转入到对方账户，无法退回，单次交易扣除15%手续费</div>-->

        <div class="enter-form-btn">
            <a href="javascript:;"><button type="button">立即支付</button></a>
        </div>
    </form>
</div>
<!--content-->
<!--余额支付密码弹窗-->
<div class="payment-window">
    <div class="payment-inner-win">
        <div class="payment-form">
            <div class="y-color-999">
                <div class="payment-left pay-close">x</div>
                <div class="payment-title">输入支付密码</div>
                <div class="payment-title y-border-top">支付金额0元</div>
            </div>
            <!--密码区-->
            <div class="pay-input-group row-box">
                <div class="row-flex pass-input-wrap">
                    <div class="pass-input-dot" ><i class="fa fa-circle"></i></div>
                    <input type="password" readonly="readonly" name="xx" value="">
                </div>
                <div class="row-flex pass-input-wrap">
                    <div class="pass-input-dot" ><i class="fa fa-circle"></i></div>
                    <input type="password" readonly="readonly" name="xx" value="">
                </div>
                <div class="row-flex pass-input-wrap">
                    <div class="pass-input-dot" ><i class="fa fa-circle"></i></div>
                    <input type="password" readonly="readonly" name="xx"value="">
                </div>
                <div class="row-flex pass-input-wrap">
                    <div class="pass-input-dot" ><i class="fa fa-circle"></i></div>
                    <input type="password" readonly="readonly" name="xx" value="">
                </div>
                <div class="row-flex pass-input-wrap">
                    <div class="pass-input-dot"><i class="fa fa-circle"></i></div>
                    <input type="password" readonly="readonly" name="xx" value="">
                </div>
                <div class="row-flex pass-input-wrap">
                    <div class="pass-input-dot"><i class="fa fa-circle"></i></div>
                    <input type="password" readonly="readonly" name="xx" value="">
                </div>
            </div>
            <!--密码区-->
            <div class="y-text-right y-width94 y-mar-top20">
                <a class="y-color-red" href="<?php echo U('member/set_pw_pay',['set_pw_pay'=>5,'param'=>$transfer_type]);?>">忘记密码？</a>
            </div>
            <!--按钮去-->
            <div class="pay-btn-group">
                <button class="pay-button figure" value="1" type="button">1</button>
                <button class="pay-button figure" value="2" type="button">2</button>
                <button class="pay-button figure" value="3" type="button">3</button>
                <button class="pay-button figure" value="4" type="button">4</button>
                <button class="pay-button figure" value="5" type="button">5</button>
                <button class="pay-button figure" value="6" type="button">6</button>
                <button class="pay-button figure" value="7" type="button">7</button>
                <button class="pay-button figure" value="8" type="button">8</button>
                <button class="pay-button figure" value="9" type="button">9</button>
                <button class="pay-button pay-button2" type="button"></button>
                <button class="pay-button figure" value="0" type="button">0</button>
                <button class="pay-button empty pay-button2" type="button"><i class="fa fa-times"></i></button>
                <div class="clear-float"></div>
            </div>
            <!--按钮去-->
        </div>
    </div>
</div>
<!--余额支付密码弹窗-->
<input type="hidden" value="1" id="origin_type" />
<input type="hidden" value="<?php echo ($transfer_type); ?>" id="transfer_type" />
<input type="hidden" value="<?php echo U('wallet/check_pay');?>" id="sealing_ye" />

</body>

	<!-- /主体 -->

	<!-- 底部 -->
			


    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript" src="/theme/mobile/js/jquery.cookie.js"></script>
    <script>
        $('.enter-form-btn').click(function(){
            var nums =Number($('#nums').val());
            if(!$('#mobile').val()){
                layer.msg('请填写对方账户！',{icon:0,time:1000})
                return false;
            }
            if(nums==0){
                layer.msg('请填写金额！',{icon:0,time:1000})
                return false;
            }
            if(parseInt(nums) < 0){
                layer.msg('请合理填写金额！',{icon:0,time:1000})
                return false;
            }

            if('<?php echo ($field); ?>' == '元宝'&&(nums>'<?php echo ($member["gold_acer"]); ?>')){
                layer.confirm('元宝余额不足,去充值?', {
                    btn: ['是的','取消'] //按钮
                }, function(){
                    location.href='<?php echo U("wallet/acer_buy");?>';
                })
                return false;

            }

            $('.payment-title.y-border-top').html('支付金额'+nums+'元');

            $(".payment-window").addClass("active");
            $(".payment-form").addClass("active");
        })

        $(".pay-close").click(function(){
            $(".payment-window").removeClass("active");
            $(".payment-form").removeClass("active");
        })
    </script>

    <script>
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