<?php if (!defined('THINK_PATH')) exit();?><!--<!doctype html>-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<link href="/theme/admin/css/style.css" rel="stylesheet"/>

<title><?php echo L('website_manage');?></title>

	<script>

	var URL = '/jradmin.php/order_recharge';

	var SELF = '/jradmin.php?m=admin&c=order_recharge&a=index&m=admin&menuid=517&p=1';

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

</div><?php endif; ?><!--会员充值列表--><div class="pad_10" >    <form name="searchform" method="get"  >        <table width="100%" cellspacing="0" class="search_form">            <tbody>            <tr>                <td>                    <div class="explain_col">                        <input type="hidden" name="m" value="admin" />                        <input type="hidden" name="c" value="OrderRecharge" />                        <input type="hidden" name="a" value="index" />                        <input type="hidden" name="menuid" value="<?php echo ($menuid); ?>" />                        &nbsp;&nbsp;     充值时间：<input type="text" name="time_start" id="J_time_start" class="date" size="12" value="<?php echo ($search["time_start"]); ?>">                        -                        <input type="text" name="time_end" id="J_time_end" class="date" size="12" value="<?php echo ($search["time_end"]); ?>">                        &nbsp;&nbsp;支付状态:                        <select name="status">                            <option value="" <?php if($search["status"] === ''): ?>selected<?php endif; ?>  >-<?php echo L('all');?>-</option>                            <option value="1" <?php if($search["status"] === 0): ?>selected<?php endif; ?>>未支付</option>                            <option value="2" <?php if($search["status"] === 1): ?>selected<?php endif; ?>>已支付</option>                        </select>                        &nbsp;&nbsp;支付类型:                        <select name="zftype">                            <option value="" <?php if($search["zftype"] === ''): ?>selected<?php endif; ?>  >-<?php echo L('all');?>-</option>                            <option value="1" <?php if($search["zftype"] === 1): ?>selected<?php endif; ?>>微信</option>                            <option value="3" <?php if($search["zftype"] === 3): ?>selected<?php endif; ?>>余额</option>                        </select>                        &nbsp;&nbsp;关键字 :                        <input name="keyword" type="text" class="input-text" size="25" value="<?php echo ($search["keyword"]); ?>" placeholder="姓名/电话"/>                        <input type="submit"  class="btn" value="搜索" />                    </div>                </td>            </tr>            </tbody>        </table>    </form>    <div class="J_tablelist table_list" data-acturi="<?php echo U('OrderRecharge/ajax_edit');?>">        <table width="100%" class="tc" cellspacing="0">            <thead>            <tr>                <!--<th width=20><input type="checkbox" id="checkall_t" class="J_checkall"></th>-->                <th>ID</th>                <th>用户名称</th>                <th>手机号</th>                <th>充值时间</th>                <th>充值金额</th>                <th>支付方式</th>                <th>状态</th>            </tr>            </thead>            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>                    <!--<td><input type="checkbox" class="J_checkitem" value="<?php echo ($val["id"]); ?>"></td>-->                    <td><?php echo ($val['id']); ?></td>                    <td><?php echo ($member[$val['uid']]['nickname']); ?></td>                    <td><?php echo ($member[$val['uid']]['mobile']); ?></td>                    <td><?php echo (date("Y-m-d H:i:s",$val['add_time'])); ?></td>                    <td><?php echo ($val["totalprices"]); ?></td>                    <td>                        <?php if($val['zftype'] == 0): ?>未选择                            <?php elseif($val['zftype'] == 1 ): ?>微信                            <?php elseif($val['zftype'] == 3): ?>余额<?php endif; ?>                    </td>                    <td>                        <?php if($val['status'] == '1' ): ?>未支付                            <?php elseif($val['status'] == 2): ?>已支付<?php endif; ?>                    </td>                    <!--<td align="center"><img data-tdtype="toggle" data-id="<?php echo ($val["id"]); ?>" data-field="status" data-value="<?php echo ($val["status"]); ?>" src="/theme/admin/images/toggle_<?php if($val["status"] == 0): ?>disabled<?php else: ?>enabled<?php endif; ?>.gif" /></td>-->                </tr><?php endforeach; endif; else: echo "" ;endif; ?>        </table>    </div>    <div class="songkebor">        <!--<label class="select_all"><input type="checkbox" name="checkall" class="J_checkall"><?php echo L('select_all');?>/<?php echo L('cancel');?></label>-->        <label align="left">总条数:<?php echo ($count); ?>条</label>        <div id="pages"><?php echo ($page); ?></div>    </div>    <br/>    <br/></div><script src="/theme/admin/js/jquery/jquery.js"></script>
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
</script><?php endif; ?><script>    laydate.render({        elem: '#J_time_start'    });    laydate.render({        elem: '#J_time_end'    });</script></body></html>