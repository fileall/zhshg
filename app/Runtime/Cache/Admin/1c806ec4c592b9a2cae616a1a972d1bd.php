<?php if (!defined('THINK_PATH')) exit();?><!--<!doctype html>-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<link href="/theme/admin/css/style.css" rel="stylesheet"/>

<title><?php echo L('website_manage');?></title>

	<script>

	var URL = '/jradmin.php/ad';

	var SELF = '/jradmin.php?m=admin&c=ad&a=index&menuid=12';

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
<!--广告列表-->
<div class="pad_lr_10">
    <form name="searchform" method="get" >
    <table width="100%" cellspacing="0" class="search_form">
        <tbody>
            <tr>
            <td>
            <div class="explain_col">
            	<input type="hidden" name="m" value="admin" />
                <input type="hidden" name="c" value="ad" />
                <input type="hidden" name="a" value="index" />
                <input type="hidden" name="menuid" value="<?php echo ($menuid); ?>" />
            	<?php echo L('ad_start_time');?>：
            	<input type="text" name="start_time_min" id="start_time_min" class="date" size="12" value="<?php echo ($search["start_time_min"]); ?>">
                -
                <input type="text" name="start_time_max" id="start_time_max" class="date mr10" size="12" value="<?php echo ($search["start_time_max"]); ?>">
                <?php echo L('ad_end_time');?>：
                <input type="text" name="end_time_min" id="end_time_min" class="date" size="12" value="<?php echo ($search["end_time_min"]); ?>">
                -
                <input type="text" name="end_time_max" id="end_time_max" class="date" size="12" value="<?php echo ($search["end_time_max"]); ?>">
            	<div class="bk3"></div>
                <?php echo L('adboard');?>：
                <select name="board_id" class="mr10">
                    <option value="">--<?php echo L('all');?>--</option>
                    <?php if(is_array($board_list)): $i = 0; $__LIST__ = $board_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if($search["board_id"] == $key): ?>selected="selected"<?php endif; ?>><?php echo ($val); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
                <!--<?php echo L('ad_type');?>：
                <select name="style" class="mr10">
                    <option value="">&#45;&#45;不限&#45;&#45;</option>
                    <?php if(is_array($ad_type_arr)): $i = 0; $__LIST__ = $ad_type_arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if($search["style"] == $key): ?>selected="selected"<?php endif; ?>><?php echo ($val); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>-->
                <?php echo L('keyword');?>：
                <input name="keyword" type="text" class="input-text mr10" size="25" value="<?php echo ($search["keyword"]); ?>" />
                <input type="submit" name="search" class="btn" value="搜索" />
        	</div>
            </td>
            </tr>
        </tbody>
    </table>
    </form>
    
    <div class="J_tablelist table_list" data-acturi="<?php echo U('ad/ajax_edit');?>">
		<table width="100%" cellspacing="0">
        <thead>
          <tr>
            <th width="25"><input type="checkbox" name="checkall" class="J_checkall"></th>
            <th>ID</th>
            <th align="left"><span data-tdtype="order_by" data-field="name"><?php echo L('ad_name');?></span></th>
           <th align="left"><span data-tdtype="order_by" data-field="url"><?php echo L('ad_url');?></span></th>
            <th><span data-tdtype="order_by" data-field="type"><?php echo L('ad_type');?></span></th>
            <th><span data-tdtype="order_by" data-field="board_id"><?php echo L('adboard');?></span></th>
            <th width="150">有效时间</th>
            <th width="60"><span data-tdtype="order_by" data-field="ordid"><?php echo L('sort_order');?></span></th>
            <th width="60"><span data-tdtype="order_by" data-field="status"><?php echo L('status');?></span></th>
            <th width="80"><?php echo L('operations_manage');?></th>
          </tr>
        </thead>
        <tbody>
          <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
            <td align="center"><input type="checkbox" class="J_checkitem" value="<?php echo ($val["id"]); ?>"></td>
            <td align="center"><?php echo ($val["id"]); ?></td>
            <td><span data-tdtype="edit" data-field="name" data-id="<?php echo ($val["id"]); ?>" class="tdedit"><?php echo ($val["name"]); ?></span></td>
            <td><span data-tdtype="edit" data-field="url" data-id="<?php echo ($val["id"]); ?>" class="tdedit"><?php echo ($val["url"]); ?></span></td>
            <td align="center"><?php echo ($ad_type_arr[$val['type']]); if($val["type"] == 'image'): if(!empty($val['content'])): ?><span class="attachment_icon J_attachment_icon" file-type="image" file-rel="<?php echo attach($val["content"],'advert');?>"><img src="/theme/admin/images/filetype/image_s.gif" /></span><?php endif; endif; ?></td>
            <td align="center"><em class="red">(id:<?php echo ($val["board_id"]); ?>)</em><?php echo ($val["adbord"]["name"]); ?></td>
            <td align="center"><?php echo (date('Y-m-d',$val["start_time"])); ?> / <?php echo (date('Y-m-d',$val["end_time"])); ?></td>
            <td align="center"><span data-tdtype="edit" data-field="ordid" data-id="<?php echo ($val["id"]); ?>" class="tdedit"><?php echo ($val["ordid"]); ?></span></td>
            <td align="center"><img data-tdtype="toggle" data-id="<?php echo ($val["id"]); ?>" data-field="status" data-value="<?php echo ($val["status"]); ?>" src="/theme/admin/images/toggle_<?php if($val["status"] == 0): ?>disabled<?php else: ?>enabled<?php endif; ?>.gif" /></td>
            <td align="center">
            	<a href="javascript:void(0);" class="J_showdialog" data-uri="<?php echo U('ad/edit', array('id'=>$val['id']));?>" data-title="<?php echo L('edit');?> - <?php echo ($val["name"]); ?>" data-id="edit" data-width="520" data-height="410"><?php echo L('edit');?></a> | 
                <a href="javascript:void(0);" class="J_confirmurl" data-acttype="ajax" data-uri="<?php echo u('ad/delete', array('id'=>$val['id']));?>" data-msg="<?php echo sprintf(L('confirm_delete_one'),$val['name']);?>"><?php echo L('delete');?></a></td>
          </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
      	</table>
		<div class="btn_wrap_fixed">
    		<label class="select_all"><input type="checkbox" name="checkall" class="J_checkall"><?php echo L('select_all');?>/<?php echo L('cancel');?></label>
            <input type="button" class="btn" data-tdtype="batch_action" data-acttype="ajax" data-uri="<?php echo U('ad/delete');?>" data-name="id" data-msg="<?php echo L('confirm_delete');?>" value="<?php echo L('delete');?>" />
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
<script type="text/javascript">
/*Calendar.setup({
	inputField : "start_time_min",
	ifFormat   : "%Y-%m-%d",
	showsTime  : false,
	timeFormat : "24"
});
Calendar.setup({
	inputField : "start_time_max",
	ifFormat   : "%Y-%m-%d",
	showsTime  : false,
	timeFormat : "24"
});
Calendar.setup({
	inputField : "end_time_min",
	ifFormat   : "%Y-%m-%d",
	showsTime  : false,
	timeFormat : "24"
});
Calendar.setup({
	inputField : "end_time_max",
	ifFormat   : "%Y-%m-%d",
	showsTime  : false,
	timeFormat : "24"
});*/
laydate.render({
    elem: '#start_time_min'
});

laydate.render({
    elem: '#start_time_max'
});
laydate.render({
    elem: '#end_time_min'
});

laydate.render({
    elem: '#end_time_max'
});
</script>
</body>
</html>