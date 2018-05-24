<?php if (!defined('THINK_PATH')) exit();?><!--<!doctype html>-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<link href="/theme/admin/css/style.css" rel="stylesheet"/>

<title><?php echo L('website_manage');?></title>

	<script>

	var URL = '/jradmin.php/withdraw_qd_fruit';

	var SELF = '/jradmin.php?m=admin&c=withdraw_qd_fruit&a=index&menuid=524';

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
<style>
    .btn_wrap_fixed{ margin:10px auto; padding:10px 8px;line-height: 28px;border-radius: 6px;-webkit-border-radius: 6px;-moz-border-radius: 6px;-ms-border-radius: 6px;}
</style>
<!--区代金果-->
<div class="pad_lr_10">
	 <form name="searchform" method="get"  >
    <table width="100%" cellspacing="0" class="search_form">
        <tbody>
            <tr>
                <td>
                <div class="explain_col"> 
                    <input type="hidden" name="m" value="admin" />
                    <input type="hidden" name="c" value="WithdrawQdFruit" />
                    <input type="hidden" name="a" value="index" /> 
                    &nbsp;&nbsp;申请日期：<input type="text" name="time_start" id="J_time_start" class="date" value="<?php echo ($search["time_start"]); ?>" placeholder="开始时间">
                    - 
                    <input type="text" name="time_end" id="J_time_end" class="date"  value="<?php echo ($search["time_end"]); ?>" placeholder="截止时间"> 
                    &nbsp;&nbsp;关键字 :
                    <input name="keywords" type="text" class="input-text"  value="<?php echo ($search["keywords"]); ?>" placeholder="会员名" />
                    <input type="submit"  class="btn" value="搜索" />
                    <input type="hidden" name="p" value="<?php echo ($p); ?>">
                </div>
                </td>
            </tr>
        </tbody>
    </table>
    </form>
    <div class="J_tablelist table_list" data-acturi="<?php echo U('withdraw/ajax_edit');?>">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
                <!--<th width="40"><input type="checkbox" name="checkall" class="J_checkall"></th>-->
                <th>序号</th>
                <th>会员名</th>
                <th>兑换面额</th>
                <th>张数</th>
                <th>备注</th>
                <th align="left">申请时间</th>
                <th><?php echo L('status');?></th>
                <th width="60"><?php echo L('operations_manage');?></th>
            </tr>
        </thead>
    	<tbody>
        	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
                <!--<td align="center"><input type="checkbox" class="J_checkitem" value="<?php echo ($val["id"]); ?>"></td>-->
                <td align="center"><?php echo ($val["id"]); ?></td>
                <td align="center"><?php echo ($val["nickname"]); ?></td>
                <td align="center"><?php echo ($val["face_value"]); ?></td>
                <td align="center"><?php echo ($val["nums"]); ?></td>
                <td align="center"><?php echo ($val["memos"]); ?></td>
                <td align="center"><?php echo date('Y-m-d H:i' ,$val['add_time']);?></td>
                <td align="center">
                    <?php switch($val["status"]): case "1": ?>未审核<?php break;?>
                        <?php case "2": ?>已通过<?php break;?>
                        <?php case "3": ?>已驳回<?php break; endswitch;?>
                </td>
                <td align="center">
                    <a class="blue" href="<?php echo U('WithdrawQdFruit/check',array('oid'=>$val['id'],'uid'=>$val['uid']));?>">详情</a>
                    <?php if($val['status'] == 1): ?><a href="javascript:;" class="J_confirmurl" data-acttype="ajax" data-uri="<?php echo U('WithdrawQdFruit/sh', array('id'=>$val['id'],'status'=>1));?>" data-msg="确认通过吗?" style="color:#F76741;">通过</a>
                        |<a href="javascript:;" class="J_confirmurl" data-acttype="ajax" data-uri="<?php echo U('WithdrawQdFruit/sh', array('id'=>$val['id'],'status'=>2));?>" data-msg="确认驳回吗?" style="color:#F76741;">驳回</a>
                        <?php elseif($val['status'] == 2): ?>
                        已通过
                        <?php elseif($val['status'] == 3): ?>
                        已驳回<?php endif; ?>
                </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    	</tbody>
    </table>
    </div>
    <div class="btn_wrap_fixed">
        <!--<label class="select_all mr10"><input type="checkbox" name="checkall" class="J_checkall"><?php echo L('select_all');?>/<?php echo L('cancel');?></label>
        <input type="button" class="btn" data-tdtype="batch_action" data-acttype="ajax" data-uri="<?php echo U('WithdrawQd/pass');?>" data-name="id" data-msg="<?php echo L('confirm_delete');?>" value="通过" />
        <input type="button" class="btn" data-tdtype="batch_action" data-acttype="ajax" data-uri="<?php echo U('WithdrawQd/bh');?>" data-name="id" data-msg="确认驳回?" value="驳回" />-->
        <div id="pages"><?php echo ($page); ?></div>
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
<script>
    laydate.render({
        elem: '#J_time_start'
    });

    laydate.render({
        elem: '#J_time_end'
    });
 
    //新增申请
     $(".add").click(function(){
    	$("input[name='a']").val("add");
    	$("form").submit()
    })
//   //查询
//   $(".look_index").click(function(){
//  	$("input[name='a']").val("index");
//  	$("form").submit()
//  })
////下载报表
//  $(".export").click(function(){
//  	$("input[name='a']").val("export");
//  	$("form").submit()
//  })
    
</script>


</html>