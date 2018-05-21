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
	<!--var SELF = '/index.php?s=/member/w_bank.html';-->
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
            <div class="header-title">银行卡</div>
            <a href="/index.php?s=/wallet/wallet.html">
                <div class="header-left"><i class="fa fa-angle-left"></i></div>
            </a>
            <div class="header-right">
                <a href="<?php echo U('Member/add_bank',['add_bank'=>1]);?>" class="add-bank"><i class="fa fa-plus"></i>添加</a>
            </div>
        </div>
    </div>
    <div class="header-space"></div>
    <!--header-->

    <!--content-->
    <div class="content">
        <div class="bank-wrap">
            <ul class="bank-wrap-ul">
                <?php if(is_array($b_card)): $i = 0; $__LIST__ = $b_card;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$card): $mod = ($i % 2 );++$i;?><li class="bank-wrap-li">
                        <div class="bank-kind vertical-center">
                            <div class="vertical-auto" style="width: 100%;">
                                <div class="bank-kind-message">
                                    <div class="bank-kind-name"><?php echo ($card["name"]); ?></div>
                                    <div class="bank-kind-name right"><?php echo ($card["title"]); ?></div>
                                    <div class="clear-float"></div>
                                </div>
                                <div class="bank-numbers">
                                    <div class="bank-numbers-sum"><?php echo ($card["nums"]); ?></div>
                                    <div class="bank-numbers-btn" data-id="<?php echo ($card['id']); ?>">解绑</div>
                                    <div class="clear-float"></div>
                                </div>
                            </div>
                        </div>

                        <div class="add-bank-date">
                            添加时间：<?php echo date('Y-m-d H:m',$card['add_time']);?>
                        </div>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>

        <div class="employ-explain">
            <div class="employ-explain-title">提现银行卡使用说明：</div>
            <ul>
                <li class="employ-explain-txt">1、该页面银行卡仅为提现使用。</li>
                <li class="employ-explain-txt">2、用户可以对页面银行进行删除操作。</li>
                <li class="employ-explain-txt">3、如果银行卡信息不全可以补充。</li>
                <li class="employ-explain-txt">4、有提现疑问，请致电：400-1089-805。</li>
            </ul>
        </div>
    </div>
    <!--content-->

    <!--footer-->
    <!--footer-->
    </body>

	<!-- /主体 -->

	<!-- 底部 -->
			


    <script>
        $('.bank-numbers-btn').click(function(){
            var id = $(this).data('id');
            var a =$(this);

            layer.confirm('您确定要解绑银行卡吗？', {
                btn: ['是的','取消'] //按钮
            }, function(){
                //开始后台验证
                $.post('<?php echo U("member/del_bank");?>',{id:id},function(d){
                    if(d.status==1){
                        layer.msg(''+d.msg+'',{icon:1,time:2000},function(){
                            a.parents(".bank-wrap-li").remove();
                        })

                    }else{
                        layer.msg(''+d.msg+'',{icon:0,time:2000})
                    }
                },"JSON")
                return false;
            })
        })
    </script>



	<!-- /底部 -->
</body>
</html>