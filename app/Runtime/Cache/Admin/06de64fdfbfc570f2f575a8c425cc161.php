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

	var SELF = '/jradmin.php?m=admin&c=grade_rule&a=index&menuid=453';

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
<!--文章列表-->
<div class="pad_lr_10" >
    <div class="J_tablelist table_list" data-acturi="<?php echo U('GradeRule/ajax_edit');?>">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
                <!--<th width="20"><input type="checkbox"  name="checkall" class="J_checkall"></th>-->

                <th>等级</th>
                <th>名称</th>
                <!--<th>注册送银币<br/>(个)</th>-->
                <!--<th>推荐送银币<br/>(个)</th>-->
                <!--<th>购元宝返银<br/>(倍)</th>-->
                <!--<th>最低购元宝数<br/>(个)</th>-->
                <th>升级费用<br/>(元)</th>
                <th width="90">升级条件<br/>(下线会员数)</th>
                <th>升级送银币<br/>(个)</th>
                <th>升级送金果<br/>(个)</th>
                <th>银楼置换上限<br/>(元宝)</th>
                <th>下线成掌柜<br/>送余额(元)</th>
                <th>下线购元宝<br/>返银(倍)</th>
                <th>下线消费<br/>返银(倍)</th>
                <th>下线商家盈利<br/>返银(倍)</th>
                <!--<th>元宝二次消费奖励</th>-->
                <th width="80"><?php echo L('operations_manage');?></th>
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
                <!--<td align="center"><input type="checkbox" class="J_checkitem" value="<?php echo ($val["id"]); ?>"></td>-->

                <td align="center"><?php echo ($val['id']); ?></td>
                <td align="center"><?php echo ($val['name']); ?></td>
                <!--<td align="center"><?php echo ($val['tj_silver']); ?></td>-->
                <!--<td align="center"><?php echo ($val['reward_silver_multiple']); ?></td>-->
                <!--<td align="center"><?php echo ($val['min_acer_price']); ?></td>-->
                <td align="center"><?php echo ($val['upgrade_price']); ?></td>
                <td align="center"><?php echo ($val['upgrade_condition']); ?></td>
                <td align="center"><?php echo ($val['upgrade_silver']); ?></td>
                <td align="center"><?php echo ($val['upgrade_fruit']); ?></td>
                <td align="center"><span data-tdtype="edit" data-field="max_acer_nums" data-id="<?php echo ($val["id"]); ?>" class="tdedit"><?php echo ($val['max_acer_nums']); ?></span></td>
                <td align="center"><span data-tdtype="edit" data-field="upgrade_one_price" data-id="<?php echo ($val["id"]); ?>" class="tdedit"><?php echo ($val['upgrade_one_price']); ?></span></td>
                <td align="center"><span data-tdtype="edit" data-field="tj_acer_silver" data-id="<?php echo ($val["id"]); ?>" class="tdedit"><?php echo ($val['tj_acer_silver']); ?></span></td>
                <td align="center"><span data-tdtype="edit" data-field="tj_acer_pay_silver" data-id="<?php echo ($val["id"]); ?>" class="tdedit"><?php echo ($val['tj_acer_pay_silver']); ?></span></td>
                <td align="center"><span data-tdtype="edit" data-field="seller_none_silve" data-id="<?php echo ($val["id"]); ?>" class="tdedit"><?php echo ($val['seller_none_silve']); ?></span></td>

                <!--<td align="center"><img data-tdtype="toggle" data-id="<?php echo ($val["id"]); ?>" data-field="acer_consume_silver" data-value="<?php echo ($val["acer_consume_silver"]); ?>" src="/theme/admin/images/toggle_<?php if($val["acer_consume_silver"] == 0): ?>disabled<?php else: ?>enabled<?php endif; ?>.gif" /></td>-->
                <td align="center"><a href="<?php echo u('GradeRule/edit', array('id'=>$val['id'], 'menuid'=>$menuid));?>"><?php echo L('edit');?></a> <!--| <a href="javascript:void(0);" class="J_confirmurl" data-acttype="ajax" data-uri="<?php echo u('GradeRule/delete', array('id'=>$val['id']));?>" data-msg="<?php echo sprintf(L('confirm_delete_one'),$val['title']);?>"><?php echo L('delete');?></a>--></td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>

    </div>
    <!--<div class="songkebor">-->
        <!--<label class="select_all mr10"><input type="checkbox" name="checkall" class="J_checkall"><?php echo L('select_all');?>/<?php echo L('cancel');?></label>-->
        <!--<input type="button" class="btn" data-tdtype="batch_action" data-acttype="ajax" data-uri="<?php echo U('GradeRule/delete');?>" data-name="id" data-msg="<?php echo L('confirm_delete');?>" value="批量删除" />-->
        <!--<div id="pages"><?php echo ($page); ?></div>-->
        <!--<div style="clear: both;"></div>-->
    <!--</div>-->
</div>
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
</body>
</html>