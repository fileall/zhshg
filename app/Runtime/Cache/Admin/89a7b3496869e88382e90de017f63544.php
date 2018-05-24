<?php if (!defined('THINK_PATH')) exit();?><!--<!doctype html>-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<link href="/theme/admin/css/style.css" rel="stylesheet"/>

<title><?php echo L('website_manage');?></title>

	<script>

	var URL = '/jradmin.php/grade_rule';

	var SELF = '/jradmin.php?m=admin&c=grade_rule&a=edit&id=1&menuid=453';

	var ROOT_PATH = '';

	var APP	 =	 '/jradmin.php';

	//语言项目

	var lang = new Object();

    <?php $_result=json_decode(L('js_lang_st'),true);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>lang.<?php echo ($key); ?> = "<?php echo ($val); ?>";<?php endforeach; endif; else: echo "" ;endif; ?>

	</script>

<script>
$(function() {
	var elm = $('.shortbar');
	var startPos = $(elm).offset().top;
	$.event.add(window, "scroll", function() {
		var p = $(window).scrollTop();
		if (p > startPos) {
			elm.addClass('sortbar-fixed');
		} else {
		    elm.removeClass('sortbar-fixed');

		}
	});
});
</script>
<style>
	.sortbar-fixed {
    margin: 0 auto;
    width: 100%;
    position: fixed!important;
    _position: absolute!important;
    z-index: 20000;
    top: 0;
    left: 0px;
    right: 0px;
</style> 
</head>



<body>
<!--<?php var_dump($big_menu); ?>-->
<div id="J_ajax_loading" class="ajax_loading"><?php echo L('ajax_loading');?></div>

<?php if(($sub_menu != '') OR ($big_menu != '')): ?><div class="subnav">

    <div class="content_menu ib_a">

    	<?php if(!empty($big_menu)): ?><a class="add fb J_showdialog" href="javascript:void(0);" data-uri="<?php echo ($big_menu["iframe"]); ?>" data-title="<?php echo ($big_menu["title"]); ?>" data-id="<?php echo ($big_menu["id"]); ?>" data-width="<?php echo ($big_menu["width"]); ?>" data-height="<?php echo ($big_menu["height"]); ?>"><?php echo ($big_menu["title"]); ?></a>　<?php endif; ?>

        <?php if(!empty($sub_menu)): if(is_array($sub_menu)): $key = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($key % 2 );++$key; if($key != 1): ?><span>|</span><?php endif; ?>

            <?php if(empty($val["dialog"])): ?><a href="<?php echo U($val['controller_name'].'/'.$val['action_name'],array('menuid'=>$menuid)); echo ($val["data"]); ?>" class="add <?php echo ($val["class"]); ?>"><?php echo L($val['name']);?></a>

            <?php else: ?>

                <?php
 $size = explode('|',$val['dialog']); ?>

                <a class="add fb J_showdialog" href="javascript:void(0);" data-uri="<?php echo U($val['controller_name'].'/'.$val['action_name'],array('menuid'=>$menuid)); echo ($val["data"]); ?>" data-title="<?php echo ($val["name"]); ?>" data-id="<?php echo ($val["action_name"]); ?>" data-width="<?php echo ($size[0]); ?>" data-height="<?php echo ($size[1]); ?>"><em><?php echo ($val["name"]); ?></em></a>　<?php endif; endforeach; endif; else: echo "" ;endif; endif; ?>

    </div>

</div><?php endif; ?>
<!--添加文章-->
<form id="info_form" action="<?php echo U('GradeRule/edit');?>" method="post" enctype="multipart/form-data">
	<div class="pad_lr_10">
		<div class="col_tab">
			<ul class="J_tabs tab_but cu_li">
				<li class="current">基本信息</li>
			</ul>

			<div class="J_panes" >
				<div class="content_list pad_10">
					<table   width="100%" cellpadding="2" cellspacing="1" class="table_form" >
					<!--<table width="100%" cellspacing="0" class="table_form">-->

						<tr >
							<th>级别名称 :</th>
							<td>
								<input type="text" name="name" id="J_name" class="input-text" value="<?php echo ($info['name']); ?>" size="30">
							</td>
						</tr>
						<!--<tr>-->
							<!--<th>注册送银币 :</th>-->
							<!--<td>-->
								<!--<input type="text" name="reg_silver" class="input-text" value="<?php echo ($info['reg_silver']); ?>" size="30">个-->
							<!--</td>-->
						<!--</tr>-->
						<!--<tr>-->
							<!--<th>推荐送银币 :</th>-->
							<!--<td>-->
								<!--<input type="text" name="tj_silver" class="input-text" value="<?php echo ($info['tj_silver']); ?>" size="30">个-->
							<!--</td>-->
						<!--</tr>-->
						<!--<tr>-->
							<!--<th>购买金元宝奖励银币 :</th>-->
							<!--<td>-->
								<!--<input type="text" name="reward_silver_multiple" class="input-text" value="<?php echo ($info['reward_silver_multiple']); ?>" size="30">倍-->
							<!--</td>-->
						<!--</tr>-->
						<!--<tr>-->
							<!--<th>最低购金买元宝数量 :</th>-->
							<!--<td>-->
								<!--<input type="text" name="min_acer_price" class="input-text" value="<?php echo ($info['min_acer_price']); ?>" size="30">个-->
							<!--</td>-->
						<!--</tr>-->

						<tr>
							<th>升级费用 :</th>
							<td>
								<input type="text" name="upgrade_price" class="input-text" value="<?php echo ($info['upgrade_price']); ?>" size="30">元
							</td>
						</tr>
						<tr>
							<th>升级条件 :</th>
							<td>
								<input type="text" name="upgrade_condition" class="input-text" value="<?php echo ($info['upgrade_condition']); ?>" size="30">(推荐的会员人数)
							</td>
						</tr>
						<tr>
							<th>升级奖励银币:</th>
							<td>
								<input type="text" name="upgrade_silver" class="input-text" value="<?php echo ($info['upgrade_silver']); ?>" size="30">个
							</td>
						</tr>
						<tr>
							<th>升级奖励金果:</th>
							<td>
								<input type="text" name="upgrade_fruit" class="input-text" value="<?php echo ($info['upgrade_fruit']); ?>" size="30">个
							</td>
						</tr>
						<tr>
							<th>银楼置换可用元宝上限:</th>
							<td>
								<input type="text" name="max_acer_nums" class="input-text" value="<?php echo ($info['max_acer_nums']); ?>" size="30">个
							</td>
						</tr>
						<tr>
							<th>下线成掌柜奖励余额 :</th>
							<td>
								<input type="text" name="upgrade_one_price" class="input-text" value="<?php echo ($info['upgrade_one_price']); ?>" size="30">元
							</td>
						</tr>
						<tr>
							<th>下线购买元宝奖励银币:</th>
							<td>
								<input type="text" name="tj_acer_silver" class="input-text" value="<?php echo ($info['tj_acer_silver']); ?>" size="30">倍
							</td>
						</tr>
						<tr>
							<th>下线消费奖励银币:</th>
							<td>
								<input type="text" name="tj_acer_pay_silver" class="input-text" value="<?php echo ($info['tj_acer_pay_silver']); ?>" size="30">倍
							</td>
						</tr>
						<tr>
							<th>被推荐商家盈利奖励银币:</th>
							<td>
								<input type="text" name="seller_none_silve" class="input-text" value="<?php echo ($info['seller_none_silve']); ?>" size="30">倍
							</td>
						</tr>

						<!--<tr>-->
							<!--<th>是否有二次消费奖励特权 :</th>-->
							<!--<td>-->
								<!--<label><input type="radio" name="acer_consume_silver" class="radio_style" value="1" <?php if($info["acer_consume_silver"] == '1'): ?>checked="checked"<?php endif; ?>> <?php echo L('yes');?> </label>&nbsp;&nbsp;-->
								<!--<label><input type="radio" name="acer_consume_silver" class="radio_style" value="0" <?php if($info["acer_consume_silver"] == '0'): ?>checked="checked"<?php endif; ?>> <?php echo L('no');?></label>-->
							<!--</td>-->
						<!--</tr>-->
					</table>
				</div>
			</div>
			<div class="mt10" ><input type="submit"  value="<?php echo L('submit');?>" id="dosubmit" name="dosubmit" class="btn btn_submit" style="margin:0 0 10px 100px;"><br /><br /><br /></div>
		</div>
	</div>
	<input type="hidden" name="id" value="<?php echo ($info['id']); ?>">
</form>
<script src="/theme/admin/js/jquery/jquery.js"></script>
<script src="/theme/admin/js/jquery/plugins/jquery.tools.min.js"></script>
<script src="/theme/admin/js/jquery/plugins/formvalidator.js"></script>
<script src="/theme/admin/js/pinphp.js"></script>
<script src="/theme/admin/js/admin.js"></script>
<!--预览图片插件-->
<script type="text/javascript" src="/theme/admin/js/example/js/jquery.mousewheel-3.0.2.pack.js"></script>
<script type="text/javascript" src="/theme/admin/js/example/js/jquery.fancybox-1.3.1.js"></script>
<script type="text/javascript" src="/theme/admin/js/example/js/pngobject.js"></script>
<link rel="stylesheet" href="/theme/admin/js/example/style/jquery.fancybox-1.3.1.css" type="text/css" />
<!--时间-->
<script src="/theme/admin/js/laydate/laydate.js"></script>
<script>
//初始化弹窗
(function (d) {
    d['okValue'] = lang.dialog_ok;
    d['cancelValue'] = lang.dialog_cancel;
    d['title'] = lang.dialog_title;
})($.dialog.defaults);
</script>

<?php if(isset($list_table)): ?><script src="/theme/admin/js/jquery/plugins/listTable.js"></script>
<script>
$(function(){
	 
	$('.J_tablelist').listTable();
});
</script><?php endif; ?>
<script type="text/javascript">
    $(function() {
        // $('ul.J_tabs').tabs('div.J_panes > div');

        $.formValidator.initConfig({formid:"info_form",autotip:true});
        $("#J_name").formValidator({onshow:lang.please_input+'等级名称',onfocus:lang.please_input+'等级名称'}).inputValidator({min:1,onerror:lang.please_input+'等级名称'}).defaultPassed();

        // $('#info_form').ajaxForm({success:complate,dataType:'json'});
        // function complate(result){
        //     if(result.status == 1){
        //         $.dialog.get(result.dialog).close();
        //         $.pinphp.tip({content:result.msg});
        //         window.location.reload();
        //     } else {
        //         $.pinphp.tip({content:result.msg, icon:'alert'});
        //     }
        // }

    });
</script>
</body>
</html>