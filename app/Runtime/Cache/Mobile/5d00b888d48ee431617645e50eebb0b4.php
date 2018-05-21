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
	<!--var SELF = '/index.php?s=/Member/add_bank/add_bank/1.html';-->
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
            <div class="header-title">添加银行卡</div>
            <div class="header-left return-prev"><i class="fa fa-angle-left"></i></div>
        </div>
    </div>
    <div class="header-space"></div>
    <!--header-->

    <!--content-->
    <div class="content">
        <form action="" method="post">
            <ul class="add-bank-ul">
                <li class="add-bank-li">
                    <div class="merchant-form row-box" style="margin-top: 0;">
                        <div class="merchant-currency">开户银行：</div>
                        <div class="row-flex">
                            <div class="merchant-form-input vertical-center">
                                <div class="vertical-auto" style="width: 100%;"><input name="name" type="text" placeholder="请输入开户银行"></div>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="add-bank-li row-box">
                    <div class="merchant-form row-box" style="margin-top: 0;">
                        <div class="merchant-currency">开户地区：</div>
                        <div class="row-flex">
                            <div class="enter-form-input">
                                <div class="vertical-auto" style="width: 100%;">
                                    <input  name="address"  class="form-input-celect" id="demo1" type="text" readonly="" placeholder="请选择所在地区" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <!--地址-->
                <!--<li class="add-bank-li">-->
                <!--<div class="merchant-form row-box" style="margin-top: 0;">-->
                <!--<div class="merchant-currency">开户省份：</div>-->
                <!--<div class="row-flex">-->
                <!--<div class="merchant-form-input vertical-center">-->
                <!--<div class="vertical-auto" style="width: 100%;"><input name="province" type="text" placeholder="请输入开户省份"></div>-->
                <!--</div>-->
                <!--</div>-->
                <!--</div>-->
                <!--</li>-->
                <!--<li class="add-bank-li">-->
                <!--<div class="merchant-form row-box" style="margin-top: 0;">-->
                <!--<div class="merchant-currency">开户市区：</div>-->
                <!--<div class="row-flex">-->
                <!--<div class="merchant-form-input vertical-center">-->
                <!--<div class="vertical-auto" style="width: 100%;"><input name="city" type="text" placeholder="请输入开户市区"></div>-->
                <!--</div>-->
                <!--</div>-->
                <!--</div>-->
                <!--</li>-->

                <li class="add-bank-li">
                    <div class="merchant-form row-box" style="margin-top: 0;">
                        <div class="merchant-currency">开户分行：</div>
                        <div class="row-flex">
                            <div class="merchant-form-input vertical-center">
                                <div class="vertical-auto" style="width: 100%;"><input name="title" type="text" placeholder="请输入开户分行"></div>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="add-bank-li">
                    <div class="merchant-form row-box" style="margin-top: 0;">
                        <div class="merchant-currency">开户姓名：</div>
                        <div class="row-flex">
                            <div class="merchant-form-input vertical-center">
                                <div class="vertical-auto" style="width: 100%;"><input name="member_name" type="text" placeholder="请输入开户姓名"></div>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="add-bank-li">
                    <div class="merchant-form row-box" style="margin-top: 0;">
                        <div class="merchant-currency">开户卡号：</div>
                        <div class="row-flex">
                            <div class="merchant-form-input vertical-center">
                                <div class="vertical-auto" style="width: 100%;"><input name="nums" type="number" placeholder="请输入开户卡号"></div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>

            <div class="submit-button"><button  type="submit" id="xx">确认添加</button></div>
        </form>
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

        $('#xx').click(function(){
            if(!$('input[name="name"]').val()){
                layer.msg('请输入开户银行！',{icon:0,time:1000})
                return false;
            }
            if(!$('input[name="title"]').val()){
                layer.msg('请输入开户分行！',{icon:0,time:1000})
                return false;
            }
            if(!$('input[name="member_name"]').val()){
                layer.msg('请输入开户姓名！',{icon:0,time:1000})
                return false;
            }
            if(!$('input[name="nums"]').val()){
                layer.msg('请填写银行卡号码！',{icon:0,time:1000})
                return false;
            }

            var data = $('form').serialize();
            data +='&add_bank=<?php echo ($add_bank); ?>';//页面来源
            data +='&param=<?php echo ($param); ?>';//附带参数
            //开始后台验证
            $.post('<?php echo U("member/add_bank");?>'
                ,data
                ,function(d){
                    if(d.status==1){
                        layer.msg(''+d.msg+'',{icon:1,time:1000},function(){
                            window.location.href=d.url;//正常返回

                            // if(d.is_tx==1){
                            // 	window.location.href='/index.php?s=/wallet/balance_extract.html';//回到提现界面
                            // }else{
                            // 	window.location.href=d.url;//正常返回
                            // }

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