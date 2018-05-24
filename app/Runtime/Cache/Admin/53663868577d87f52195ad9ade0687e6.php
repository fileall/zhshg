<?php if (!defined('THINK_PATH')) exit();?><!--<!doctype html>-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<link href="/theme/admin/css/style.css" rel="stylesheet"/>

<title><?php echo L('website_manage');?></title>

	<script>

	var URL = '/jradmin.php/order';

	var SELF = '/jradmin.php?m=admin&c=order&a=index&menuid=518&is_tk=2';

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
<!--订单列表-->
<div class="pad_lr_10" >
    <form name="searchform" method="get">
        <table width="100%" cellspacing="0" class="search_form">
            <tbody>
            <tr>
                <td>
                    <div class="explain_col">
                        <input type="hidden" name="m" value="admin" />
                        <input type="hidden" name="c" value="order" />
                        <input type="hidden" name="a" value="index" />
                        <input type="hidden" name="menuid" value="<?php echo ($menuid); ?>" />
                        下单时间 :
                        <input type="text" name="start_addtime" value="<?php echo ($search["start_addtime"]); ?>"  id="J_start_addtime" class="date" size="12">
                        -<input type="text" name="end_addtime" value="<?php echo ($search["end_addtime"]); ?>"  id="J_end_addtime" class="date" size="12">&nbsp;&nbsp;
                        <!-- 配送时间 :
                         <input type="text" name="start_delivery" value="<?php echo ($search["start_delivery"]); ?>"  id="J_start_delivery" class="date" size="12">
                         -<input type="text" name="end_delivery" value="<?php echo ($search["end_delivery"]); ?>"  id="J_end_delivery" class="date" size="12">-->
                        支付类型 :
                        <select name="zftype">
                            <option value="">-<?php echo L('all');?>-</option>
                            <?php $_result=order_zftype();if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($i); ?>" <?php if($search["zftype"] == $i ): ?>selected="selected"<?php endif; ?>><?php echo ($val); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>

                        <!--仅订单列表-->
                        <?php if($search["is_tk"] != '2' && $search["is_tk"] != '1'): ?>&nbsp;&nbsp;订单状态 : <select name="status">
                            <option value="">-<?php echo L('all');?>-</option>
                            <?php $_result=order_status();if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($i); ?>" <?php if($search["status"] == $i ): ?>selected="selected"<?php endif; ?>><?php echo ($val); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select><?php endif; ?>
                        <!--仅仅退款订单-->
                        <?php if($search["is_tk"] == '1'): ?>退款订单:
                            <select name="tk_status">
                                <option value="">-<?php echo L('all');?>-</option>
                                <option value="1" <?php if($search["status"] == '1'): ?>selected="selected"<?php endif; ?>>退款中</option>
                                <option value="2" <?php if($search["status"] == '2'): ?>selected="selected"<?php endif; ?>>已审核</option>
                                <option value="3" <?php if($search["status"] == '3'): ?>selected="selected"<?php endif; ?>>已驳回</option>
                            </select><?php endif; ?>

                        &nbsp;&nbsp;<?php echo L('keyword');?> :<input name="keyword" type="text" class="input-text" size="25" value="<?php echo ($search["keyword"]); ?>" placeholder="订单号/用户手机"/>&nbsp;&nbsp;
                        <input type="hidden" name="is_tk" value="<?php echo ($is_tk); ?>" />

                        <input type="submit" class="btn" value="搜索" />
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
    <div class="pad_lr_10">
        <!--<div class="skbor">

            <label class="select_all">

                <input type="checkbox" name="checkall" class="J_checkall"><?php echo L('select_all');?>/<?php echo L('cancel');?></label>

            <input type="button" class="btn" data-tdtype="batch_action" data-uri="<?php echo U('order_wm/export_order_wms',array('menuid'=>$menuid));?>" data-name="id" value="导出" />

            <?php if($search["keyword"] != '' or $search["start_addtime"] != '' or $search["end_addtime"] != '' or $search["start_delivery"] != '' or $search["end_delivery"] != '' or $search["status"] != '' ): ?>&nbsp;&nbsp;&nbsp;&nbsp;订单总金额：<span style="color: #f42e02"><?php echo ($prices); ?> 元</span><?php endif; ?>

        </div>-->
        <div class="content_list">
            <div class="J_tablelist table_list" data-acturi="<?php echo U('order_wm/ajax_edit');?>">
                <table width="100%" cellspacing="0">
                    <thead>
                    <tr>

                        <!--<th width="40"><input type="checkbox" name="checkall" class="J_checkall"></th>-->

                        <th width="10"><input type="checkbox" id="checkall_t" class="J_checkall"></th>
                        <!--<th width=50><span data-tdtype="order_wm_by" data-field="id">ID</span></th>-->
                        <th align="center" width="30">订单号</th>
                        <th align="center" width="30"><span data-tdtype="order_by" data-field="order_amount">金额(元)</th>
                        <th width="50">支付方式</th>
                        <th width="50">订单状态</th>
                        <!--<th width="50">退款情况</th>-->
                        <th width="70">用户名</th>
                        <th width="70">手机号</th>
                        <th width="70">备注</th>
                        <th width="70">下单时间</th>
                        <th width="50">订单详情</th>

                        <?php if($search["is_tk"] == '1' ): ?><th width="50"><?php echo L('operations_manage');?></th><?php endif; ?>
                        <?php if($search["is_tk"] == '2' ): ?><th width="50">接单操作</th><?php endif; ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
                            <td align="center"><input type="checkbox" class="J_checkitem" value="<?php echo ($val["id"]); ?>"></td>
                            <!--<td align="center"><?php echo ($val["id"]); ?></td>-->
                            <td align="center"><?php echo ($val["dingdan"]); ?></td>
                            <td align="center"><?php echo ($val["order_amount"]); ?></td>
                            <td align="center">
                                <?php if(!order_zftype()[$val['zftype']]){ echo '未选择'; }else{ echo order_zftype()[$val['zftype']]; } ?>
                            </td>
                            <td align="center"><?php echo order_status()[$val['status']];?></td>
                            <!--<td align="center">
                                <?php if(is_array($val["sub"])): $i = 0; $__LIST__ = $val["sub"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; echo ($vo['title']); ?>    &lt;!&ndash;<?php echo ($gwc_title[$vo['jid']]); ?>&ndash;&gt;<?php if($vo['did'] == 0): echo ($vo['nums']); echo ($vo['unit']); ?>&lt;!&ndash;<?php echo ($gwc_unit[$vo['jid']]); ?>&ndash;&gt;<?php else: echo ($vo['nums']); echo ($gwc_value[$vo['did']]); endif; ?><br><?php endforeach; endif; else: echo "" ;endif; ?>
                            </td>-->
                            <td align="center"><?php echo ($member_list[$val['uid']]['nickname']); ?></td>
                            <td align="center"><?php echo ($member_list[$val['uid']]['mobile']); ?></td>
                            <td align="center"><?php echo ($val["memos"]); ?></td>
                            <td align="center"><?php echo (date('Y-m-d',$val["add_time"])); ?></td>
                            <td align="center">
                                <!--<a href="<?php echo u('order_wm/edit',array('id'=>$val['id'],'menuid'=>$menuid));?>">详情</a>-->
                                <a href="javascript:;" class="J_showdialog" data-uri="<?php echo u('order/detail', array('id'=>$val['id']));?>" data-title="查看详情"  data-id="edit" data-width="900" data-height="300" >详情</a>
                            </td>
                            <?php if($search["is_tk"] == '1'): ?><td align="center">
                                    <?php if($val["tk_status"] == '1'): ?><a href="javascript:void(0);" class="J_confirmurl" data-uri="<?php echo u('order/sh', array('id'=>$val['id'],'status'=>1));?>" data-acttype="ajax" data-msg="您确定通过?">通过</a>
                                        |<a href="javascript:void(0);" class="J_confirmurl" data-uri="<?php echo u('order/sh', array('id'=>$val['id'],'status'=>2));?>" data-acttype="ajax" data-msg="您确定驳回?">驳回</a>
                                        <?php elseif($val["tk_status"] == '2'): ?>
                                        已通过<?php else: ?>已驳回<?php endif; ?>
                                </td>
                            <?php elseif($search["is_tk"] == '2'): ?>
                                <td align="center">
                                    <?php if($val["status"] == '2'): ?><a href="javascript:;" class="J_showdialog" data-uri="<?php echo u('order/send', array('id'=>$val['id']));?>" data-title="发货"  data-id="edit" data-width="500" data-height=100 >发货</a>
                                        <!--|<a href="javascript:void(0);" class="J_confirmurl" data-uri="<?php echo u('order/no_send', array('id'=>$val['id'],'status'=>2));?>" data-acttype="ajax" data-msg="您确定拒绝接单?">拒绝</a>-->
                                        <?php elseif($val["status"] == '3'): ?>已发货
                                        <?php elseif($val["status"] == '6'): ?>已拒绝

                                        <?php else: endif; ?>
                                </td>
                            <?php else: endif; ?>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="songkebor">

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
<script>
    laydate.render({
        elem: '#J_start_addtime'
    });

    laydate.render({
        elem: '#J_end_addtime'
    });
</script>

</body>

</html>