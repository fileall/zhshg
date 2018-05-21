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
	<!--var SELF = '/index.php?s=/merchant/merchant.html';-->
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
        <div class="header-title">我是商家</div><a href="<?php echo U('Member/mine');?>">
        <div class="header-left"><i class="fa fa-angle-left"></i></div></a>
        <div class="header-right"></div>
    </div>
</div>
<div class="header-space"></div>
<!--header-->


<!--content-->
<?php if(!$merchant): ?><div class="not-merchant">
        <img src="/theme/mobile/images/y_bg6.png" alt="" class="not-merchant-bg"/>
        <p class="not-merchant-tip">您还不是商家哦~</p>
        <a href="<?php echo U('merchant_add');?>" class="not-merchant-btn jus-ali">去申请</a>
    </div>
<?php elseif($merchant['status'] == '1'): ?>
    <div class="not-merchant">
        <img src="/theme/mobile/images/y_bg6.png" alt="" class="not-merchant-bg"/>
        <p class="not-merchant-tip">申请失败</p>
        <a href="<?php echo U('merchant_add',['merchant_id'=>$merchant['id']]);?>" class="not-merchant-btn jus-ali">重新申请</a>
    </div>
<?php elseif($merchant['status'] == '0'): ?>
    <div class="not-merchant">
        <img src="/theme/mobile/images/y_bg6.png" alt="" class="not-merchant-bg"/>
        <p class="not-merchant-tip">您的店铺正在审核中~</p>
        <a href="javascript:;" class="not-merchant-btn jus-ali">待审核</a>
    </div>
<?php elseif($merchant['status'] == 2 || $merchant['status'] == 3): ?>
    <div class="content">
        <!--person-->
        <div class="person-wrap y-person-wrap">
            <div class="person-inner row-box">
                <div class="row-flex">
                    <div class="person-photo vertical-center y-person-photo">
                        <div class="vertical-auto"><img src="<?php echo attach($merchant['img'],'avatar');?>"></div>
                    </div>
                    <div class="person-message">
                        <div class="person-name"><?php echo ($merchant['title']); ?></div>
                        <?php if($merchant['status'] == 2): ?><div class="y-merchant-person-grade y-mar-top20 ellipsis1 y-text-center">营业中</div>
                            <?php elseif($merchant['status'] == 3): ?>
                            <div class="y-merchant-person-grade y-mar-top20 ellipsis1 y-text-center">闭店中</div><?php endif; ?>

                    </div>
                </div>


                <div class="popularize y-merchant-popularize vertical-center">
                    <div class="vertical-auto">
                        <a href="<?php echo U('merchant_code');?>">
                            <div class="popularize-img"><img src="<?php echo attach($merchant['ewm'],'ewm');?>"></div>
                            <div class="popularize-name">收款二维码</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!--person-->

        <ul class="flex flex-warp y-border-top y-bg-white y-mar-top20">
            <li class="y-merchant-item y-border-right"><a href="<?php echo U('merchant_ingot',['type'=>2]);?>">
                <p class="font-36"><?php echo ($merchant['gold_acer']); ?></p>
                <p class="font-30">元宝货款</p></a>
            </li>
            <li class="y-merchant-item"><a href="<?php echo U('merchant_fruit',['type'=>3]);?>l">
                <p class="font-36"><?php echo ($merchant['gold_fruit']); ?></p>
                <p class="font-30">金果</p></a>
            </li>
            <li class="y-merchant-item y-border-right"><a href="<?php echo U('merchant_store');?>">
                <div class="y-merchant-item-img">
                    <img src="/theme/mobile/images/y_set.png"/>
                </div>
                <p class="font-30">店铺设置</p></a>
            </li>
            <!--<li class="y-merchant-item y-border-right"><a href="y_storeAddImg.html">-->
                <!--<div class="y-merchant-item-img">-->
                    <!--<img src="/theme/mobile/images/y_add.png"/>-->
                <!--</div>-->
                <!--<p class="font-30">添加店铺图片</p></a>-->
            <!--</li>-->
        </ul>
        <?php if($merchant["status"] == 2): ?><a href="javascript:;" class="peration" data-status="3"><div class="y-merchant-closeShop y-bg-red y-text-center font-28">关闭店铺</div></a>
        <?php else: ?>
            <a href="javascript:;" class="peration" data-status="2"><div class="y-merchant-closeShop y-bg-red y-text-center font-28">开启店铺</div></a><?php endif; ?>
    </div>
    <?php else: endif; ?>
<!--content-->


<!--footer-->
<!--footer-->
</body>

	<!-- /主体 -->

	<!-- 底部 -->
			


<script>

    $('.peration').click(function(){
        var _this=$(this);
        var status=_this.attr('data-status');
        // var status_msg=(status==2)?'关闭店铺':'开启店铺';

        if(status==2){
           var change_html='关闭店铺'; var change_status=3;var change_html2='营业中';
        }else{
           var change_html='开启店铺'; var change_status=2;var change_html2='闭店中';
        }
        $.post("<?php echo U('merchant_operation');?>",{status:status},function(res){
            if(res.status==1){
                layer.msg(res.msg, {icon:1,time: 1000},function(){
//                    window.location.href=window.location.href;
                    _this.attr('data-status',change_status);
                    _this.find('div').html(change_html);
                    $('.y-merchant-person-grade.y-mar-top20').html(change_html2);
                });
            }else{
                layer.msg(res.msg,{icon:0,time:1000})
            }
        },'JSON')
    })
</script>


	<!-- /底部 -->
</body>
</html>