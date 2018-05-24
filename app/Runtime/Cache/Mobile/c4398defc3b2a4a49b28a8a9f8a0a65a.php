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
	<!--var SELF = '/index.php?s=/wallet/balance_extract/page_type/1.html';-->
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
        <div class="header-title">提现</div>
        <div class="header-left return-prev"><i class="fa fa-angle-left"></i></div>
        <div class="header-right"></div>
    </div>
</div>
<div class="header-space"></div>
<!--header-->

<!--没有银行卡的时候的弹出框，通过在添加类名y-block和y-hied控制显隐藏-->
<!--<div class="y-alert-bg y-addCard-alert-bg">-->
    <!--<div class="y-addCard-alert y-alert-div">-->
        <!--<p class="y-alert-content">你还没有可用于提现的银行卡，请先添加一张储蓄卡。</p>-->
        <!--<div class="jus-end y-alert-Button">-->
            <!--<p class="y-alert-bankButton y-color-999">取消</p>-->
            <!--<a href="y_bankAdd.html" class="y-color-red">添加银行卡</a>-->
        <!--</div>-->
    <!--</div>-->
<!--</div>-->
<!--没有银行卡的时候的弹出框-->

<!--content，有银行卡的时候进行显示，通过在添加类名y-block和y-hied控制显隐藏-->
<div class="content">

    <!--选择银行卡-->
    <?php if(empty($card[0])): ?><div class="jus-cen">
            <a href="<?php echo U('member/add_bank',['add_bank'=>2,'param'=>$page_type]);?>" class="y-selectCard-add btn-add-carts">
                <div class="left"><i class="fa fa-plus"></i>添加银行卡</div>
            </a>
        </div>
    <?php else: ?>
        <div id="y-selectCard" class="ali-cen y-selectCard y-width94 y-mar-top20 y-bg-white" data-card_id="<?php echo ($card[0]['id']); ?>">
            <div class="left y-selectCard-img">
                <img src="/data/attachment/bank_img/5afcf3faeae7c.png"/>
            </div>
            <div class="y-selectCard-text flex flex1">
                <p><?php echo ($card[0]['name']); ?></p>
                <p class="y-selectCard-text-msg">尾号<?php echo ($card[0]['nums']); ?>储蓄卡</p>
            </div>
            <div class="function-list-arrows"><i class="fa fa-angle-right"></i></div>
        </div><?php endif; ?>
    <!--选择银行卡-->

    <!--提现金额-->
    <div class="y-selectMoney y-width94 y-bg-white y-mar-top20">
        <p class="font-28 y-color-999">提现金额</p>
        <div class="y-mar-top20">
            <span class="y-color-red y-selectMoney-span">&yen;</span>
            <input class="y-selectMoney-input flex1" type="number" value="100.00" id="nums"/>
        </div>
    </div>
    <div class="y-selectMoney-msg y-color-999 y-bg-white y-width94 font-28">可用金额：<span class="y-color-red"><?php echo ($prices); ?></span>(最小提现金额<span class="y-color-red">100</span>元)</div>
    <!--提现金额-->
    <div class="submit-button"><a id="pay-button" class="extract-btn" href="javascript:;">确认提现</a></div>
</div>
<!--content-->

<!--余额支付密码弹窗-->
<div class="payment-window">
    <div class="payment-inner-win">
        <div class="payment-form">
            <div class="y-color-999">
                <div class="payment-left pay-close">x</div>
                <div class="payment-title">输入支付密码</div>
                <div class="payment-title y-border-top">提现金额0元</div>
            </div>
            <!--密码区-->
            <div class="pay-input-group row-box">
                <div class="row-flex pass-input-wrap">
                    <div class="pass-input-dot"><i class="fa fa-circle"></i></div>
                    <input type="password" readonly="readonly" value="">
                </div>
                <div class="row-flex pass-input-wrap">
                    <div class="pass-input-dot"><i class="fa fa-circle"></i></div>
                    <input type="password" readonly="readonly" value="">
                </div>
                <div class="row-flex pass-input-wrap">
                    <div class="pass-input-dot"><i class="fa fa-circle"></i></div>
                    <input type="password" readonly="readonly" value="">
                </div>
                <div class="row-flex pass-input-wrap">
                    <div class="pass-input-dot"><i class="fa fa-circle"></i></div>
                    <input type="password" readonly="readonly" value="">
                </div>
                <div class="row-flex pass-input-wrap">
                    <div class="pass-input-dot"><i class="fa fa-circle"></i></div>
                    <input type="password" readonly="readonly" value="">
                </div>
                <div class="row-flex pass-input-wrap">
                    <div class="pass-input-dot"><i class="fa fa-circle"></i></div>
                    <input type="password" readonly="readonly" value="">
                </div>
            </div>
            <!--密码区-->
            <div class="y-text-right y-width94 y-mar-top20">
                <a class="y-color-red" href="<?php echo U('member/set_pw_pay',['set_pw_pay'=>6,'param'=>$page_type]);?>">忘记密码？</a>
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
<input type="hidden" value="<?php echo ($page_type); ?>" id="page_type" /><!--提现页面-->
<input type="hidden" value="6" id="origin_type" /><!--调取密码框页面-->
<input type="hidden" value="<?php echo U('wallet/balance_extract');?>" id="sealing_ye" />

<!--选择银行卡弹出框-->
<div class="y-alert-bg y-selectCard-alert-bg y-hide">
    <div class="y-selectCard-alert y-bg-white">
        <!--选择银行卡标题-->
        <div>
            <div class="header-inner">
                <div class="header-title">提现</div>
                <div class="header-left y-alert-bankButton"><i class="fa fa-angle-left"></i></div>
                <div class="header-right y-header-right font-28 y-alert-submitbutton">确定</div>
            </div>
        </div>
        <!--银行卡列表-->
        <div>
            <?php if(is_array($card)): $i = 0; $__LIST__ = $card;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><div class="ali-cen y-selectCard2 <?php if($i == 1): ?>y-selectCard2-sed<?php endif; ?>"
                     data-id="<?php echo ($val['id']); ?>" data-name="<?php echo ($val['name']); ?>" data-img="data/attachment/bank_img/5afcf3faeae7c.png" data-nums="<?php echo ($val['nums']); ?>">
                    <div class="left y-selectCard-img2">
                        <img src="data/attachment/bank_img/5afcf3faeae7c.png"/>
                    </div>

                    <div class="y-selectCard-text flex flex1">
                        <p><?php echo ($val['name']); ?><span class="font-28">(<?php echo ($val['nums']); ?>)</span></p>
                        <!--<p class="y-selectCard-text-msg font-24">可转至该卡<span class="y-color-red">0.00</span>元</p>-->
                    </div>
                </div><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        <!--添加银行卡-->
        <div class="jus-cen">
            <a href="<?php echo U('member/add_bank',['add_bank'=>2,'param'=>$page_type]);?>" class="y-selectCard-add btn-add-carts">
                <div class="left"><i class="fa fa-plus"></i>添加银行卡</div>
            </a>
        </div>
    </div>
</div>
<!--选择银行卡弹出框-->
<!--footer-->
<!--footer-->
</body>

	<!-- /主体 -->

	<!-- 底部 -->
			


<script>
    //选择银行卡弹出框
    $("#y-selectCard").click(function(){
        $(".y-selectCard-alert-bg").show();
    })
    $(".y-alert-bankButton").click(function(){
        $(".y-alert-bg").hide();
    })

    //选择银行卡
    $(".y-selectCard2").click(function(){
        $(this).addClass("y-selectCard2-sed").siblings().removeClass("y-selectCard2-sed")
    })

    $(".y-alert-submitbutton").click(function(){
        var chack_card=$(".y-selectCard2.y-selectCard2-sed");
        var nums=chack_card.data('nums');
        var name=chack_card.data('name');
        var img=chack_card.data('img');
        var id=chack_card.data('id');

        var selectCard=$('#y-selectCard');
        selectCard.attr('data-card_id',id);
        selectCard.find('img').attr('src',img);
        selectCard.find('p').eq(0).html(name);
        selectCard.find('p').eq(1).html('尾号'+nums+'储蓄卡');//替换银行卡

        $(".y-selectCard-alert-bg").hide();//确定=>隐藏该弹窗
    })


    //支付密码弹出框
    $("#pay-button").click(function(){
        var nums = $("#nums").val();
        var card_id=$('#y-selectCard').attr('data-card_id');

        nums= Math.round(nums*100)/100;
        $("#nums").val(nums);//保留两位小数
        if(!nums){
            layer.msg('请填写金额！',{icon:0,time:1000})
            return false;
        }
        if(nums<100){
            layer.msg('最小提现金额100元！',{icon:0,time:1000})
            return false;
        }
        if(!card_id){
            layer.msg('请选择银行卡！',{icon:0,time:1000})
            return false;
        }

        $('.payment-title.y-border-top').html('提现金额'+nums+'元');

        $(".payment-window").addClass("active");
        $(".payment-form").addClass("active");
    })
    $(".pay-close").click(function(){
        $(".payment-window").removeClass("active");
        $(".payment-form").removeClass("active");
    })
</script>

	<!-- /底部 -->
</body>
</html>