<?php if (!defined('THINK_PATH')) exit();?><!--<!doctype html>-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<link href="/theme/admin/css/style.css" rel="stylesheet"/>

<title><?php echo L('website_manage');?></title>

	<script>

	var URL = '/jradmin.php/adboard';

	var SELF = '/jradmin.php?m=admin&c=adboard&a=index&menuid=13';

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
<!--广告位列表-->
<div class="pad_lr_10">
    <div class="J_tablelist table_list" data-acturi="<?php echo U('adboard/ajax_edit');?>">
      <table width="100%" cellspacing="0">
        <thead>
			<tr>
				<th width="25"><input type="checkbox" class="J_checkall"></th>
				<th><span tdtype="order_by" fieldname="id">ID</span></th>
				<th><span tdtype="order_by" fieldname="name"><?php echo L('adboard_name');?></span></th>
				<th><?php echo L('adboard_size');?></th>
				<th><?php echo L('adboard_desc');?></th>
                <th width="40"><span data-tdtype="order_by" data-field="status"><?php echo L('status');?></span></th>
                <th><?php echo L('operations_manage');?></th>
			</tr>
        </thead>
		<tbody>
			<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
				<td align="center"><input type="checkbox" name="checkall" class="J_checkitem" value="<?php echo ($val["id"]); ?>"></td>
				<td align="center"><?php echo ($val["id"]); ?></td>
				<td><span data-tdtype="edit" data-field="name" data-id="<?php echo ($val["id"]); ?>" class="tdedit"><?php echo ($val["name"]); ?></span></td>
				<td align="center"><span data-tdtype="edit" data-field="width" data-id="<?php echo ($val["id"]); ?>" class="tdedit"><?php echo ($val["width"]); ?></span>*<span data-tdtype="edit" data-field="height" data-id="<?php echo ($val["id"]); ?>" class="tdedit"><?php echo ($val["height"]); ?></span></td>
				<td><span data-tdtype="edit" data-field="description" data-id="<?php echo ($val["id"]); ?>" class="tdedit"><?php echo ($val["description"]); ?></span></td>
                <td align="center"><img data-tdtype="toggle" data-id="<?php echo ($val["id"]); ?>" data-field="status" data-value="<?php echo ($val["status"]); ?>" src="/theme/admin/images/toggle_<?php if($val["status"] == 0): ?>disabled<?php else: ?>enabled<?php endif; ?>.gif" /></td>
                <td align="center">
                	<a href="javascript:void(0);" class="J_showdialog" data-uri="<?php echo U('adboard/edit', array('id'=>$val['id']));?>" dtitle="<?php echo L('edit');?> - <?php echo ($val["name"]); ?>" data-id="edit" data-width="500" data-height="280"><?php echo L('edit');?></a> | 
                    <a href="javascript:void(0);" class="J_confirmurl" data-acttype="ajax" data-uri="<?php echo u('adboard/delete', array('id'=>$val['id']));?>" data-msg="<?php echo sprintf(L('confirm_delete_one'), $val['name']);?>"><?php echo L('delete');?></a></td>
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
		</table>
        <div class="btn_wrap_fixed">
			<label class="select_all"><input type="checkbox" name="checkall" class="J_checkall"><?php echo L('select_all');?>/<?php echo L('cancel');?></label>
            <input type="button" class="btn" data-tdtype="batch_action" data-acttype="ajax" data-uri="<?php echo U('adboard/delete');?>" data-name="id" data-msg="<?php echo L('confirm_delete');?>" value="<?php echo L('delete');?>" />
			<div id="pages"><?php echo ($page); ?></div>
		</div>
	</div>
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