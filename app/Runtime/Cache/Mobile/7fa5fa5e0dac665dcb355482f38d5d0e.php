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
	<!--var SELF = '/index.php?s=/member/popularize_link/what_ewm/1.html';-->
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
				<div class="header-title">我的专属二维码</div>
				<div class="header-left return-prev"><i class="fa fa-angle-left"></i></div>
				<div class="header-right"></div>
			</div>
		</div>
		<div class="header-space"></div>
		<!--header-->
		<!--content-->
		<div class="erweima-wrap">
			<div class="erweima-inner">
				<div class="erweima-content">
					<div class="erweima-img vertical-center">
						<div class="vertical-auto"><img src="<?php echo attach($ewm,'ewm');?>"></div>
					</div>
					<!--<div class="erweima-name">臻惠生活</div>-->
				</div>
				<div class="erweima-copy btn"  id="urls" data-clipboard-text="<?php echo ($ewid); ?>">复制分享</div>
			</div>
		</div>
		<!--content-->


		<!--footer-->
		<div class="y-alert-bg y-text-center y-hide">
			<div class="y-bg-white y-erweima-alert">
				<p class="font-30 y-mar-top20">分享到</p>
				<ul class="y-erweima-ul jus-bet y-border-top y-mar-top20">
					<li class="flex1" id="onMenuShareAppMessage">
						<div class="y-erweima-icon">
							<img src="/theme/mobile/images/y_icon80.png"/>
						</div>
						<p class="y-mar-top10">微信</p>
					</li>
					<li class="flex1" id="onMenuShareQQ">
						<div class="y-erweima-icon">
							<img src="/theme/mobile/images/y_icon81.png"/>
						</div>
						<p class="y-mar-top10">QQ</p>
					</li>
					<li class="flex1"  id="onMenuShareTimeline">
						<div class="y-erweima-icon">
							<img src="/theme/mobile/images/y_icon82.png"/>
						</div>
						<p class="y-mar-top10">微信朋友圈</p>
					</li>
					<li class="flex1" id="qqZone">
						<div class="y-erweima-icon">
							<img src="/theme/mobile/images/y_icon83.png"/>
						</div>
						<p class="y-mar-top10">QQ空间</p>
					</li>
				</ul>
				<p class="y-erweima-button font-30 y-bg-f5">取消</p>
			</div>
		</div>
		<!--footer-->
	</body>


	<!-- /主体 -->

	<!-- 底部 -->
			


<script type="text/javascript" src="/theme/mobile/js/clipboard.min.js"></script>
 <script  type="text/javascript">
     $(".erweima-copy").click(function(){
         $(".y-alert-bg").fadeIn(300);
         $(".y-erweima-alert").addClass("active");
     })
     $(".y-erweima-button").click(function(){
         $(".y-alert-bg").fadeOut(300);
         $(".y-erweima-alert").removeClass("active");
     })

// $(document).ready(function(){
//    var clipboard = new Clipboard('#urls');
//    clipboard.on('success', function(e) {
// //     console.log(e);
// 		layer.msg("复制成功!您的推荐码是"+'<?php echo ($ewid); ?>',{icon:1,time:2000})
//    });
//    clipboard.on('error', function(e) {
// 		layer.msg("复制失败！请手动复制",{icon:0,time:1000})
//     });
// })

</script>
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script>

		wx.config({
            debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
            appId: "<?php echo ($js['appId']); ?>", // 必填，公众号的唯一标识
            timestamp: "<?php echo ($js['timestamp']); ?>", // 必填，生成签名的时间戳
            nonceStr: "<?php echo ($js['nonceStr']); ?>", // 必填，生成签名的随机串
            signature: "<?php echo ($js['signature']); ?>",// 必填，签名，见附录1
            jsApiList: [
                'checkJsApi',
				'onMenuShareTimeline',
				'onMenuShareAppMessage',
				'onMenuShareQQ',
			] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
        });
        var share_data = {
            title: '悦买宝', // 分享标题
            desc: '悦买宝!', // 分享描述
            link: "http://zhshg.0791jr.com/index.php?m=mobile&c=login&a=register&ewid=<?php echo ($uid); ?>", // 分享链接
            imgUrl: "http://zhshg.0791jr.com/theme/mobile/images/ymb-logo.png", // 分享图标
            type: 'link',
            trigger: function (res) {
                // 不要尝试在trigger中使用ajax异步请求修改本次分享的内容，因为客户端分享操作是一个同步操作，这时候使用ajax的回包会还没有返回
                layer.msg('复制链接成功!');
            },
            success: function (res) {
                layer.msg('已分享!');
            },
            cancel: function (res) {
                layer.msg('分享已取消!');
            },
//           fail: function (res) {
//               alert(JSON.stringify(res));
//           }
        };


        // 2.1 监听“分享给朋友”，按钮点击、自定义分享内容及分享结果接口
        document.querySelector('#onMenuShareAppMessage').onclick = function () {
            wx.onMenuShareAppMessage(share_data);
            layer.msg('点击右上角开始推荐分享吧!');
        };

        // 2.2 监听“分享到朋友圈”按钮点击、自定义分享内容及分享结果接口
        document.querySelector('#onMenuShareTimeline').onclick = function () {
            wx.onMenuShareTimeline(share_data);
            layer.msg('点击右上角开始推荐分享吧!');
        };

        // 2.3 监听“分享到QQ”按钮点击、自定义分享内容及分享结果接口
        document.querySelector('#onMenuShareQQ').onclick = function () {
            wx.onMenuShareQQ(share_data);
            layer.msg('点击右上角开始推荐分享吧!');
        };


		//分享给qq空间
        document.querySelector('#qqZone').onclick = function () {
            qqZone();
        };

        function qqZone(){
            var _url = "http://zhshg.0791jr.com/index.php?m=mobile&c=login&a=register&ewid=<?php echo ($uid); ?>";// 分享链接
            var _showcount = 0;
            var _desc = "悦买宝，越买越赚钱";
            var _summary = "";
            var _title = "悦买宝";
            var _site = "";
            var _width = "600px";
            var _height = "800px";
            var _summary = "";
            var _shareUrl = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?';
            var _pic= "http://zhshg.0791jr.com/theme/mobile/images/ymb-logo.png"; // 分享图标
            _shareUrl += 'url=' + encodeURIComponent(_url||document.location);   //参数url设置分享的内容链接|默认当前页location
            _shareUrl += '&showcount=' + _showcount||0;      //参数showcount是否显示分享总数,显示：'1'，不显示：'0'，默认不显示
            _shareUrl += '&desc=' + encodeURIComponent(_desc||'分享的描述');    //参数desc设置分享的描述，可选参数
            //_shareUrl += '&summary=' + encodeURIComponent(_summary||'分享摘要');    //参数summary设置分享摘要，可选参数
            _shareUrl += '&title=' + encodeURIComponent(_title||document.title);    //参数title设置分享标题，可选参数
            //_shareUrl += '&site=' + encodeURIComponent(_site||'');   //参数site设置分享来源，可选参数
            _shareUrl += '&pics=' + encodeURIComponent(_pic||'');   //参数pics设置分享图片的路径，多张图片以＂|＂隔开，可选参数
            window.open(_shareUrl,'width='+_width+',height='+_height+',top='+(screen.height-_height)/2+',left='+(screen.width-_width)/2+',toolbar=no,menubar=no,scrollbars=no,resizable=1,location=no,status=0');
        }

	</script>


	<!-- /底部 -->
</body>
</html>