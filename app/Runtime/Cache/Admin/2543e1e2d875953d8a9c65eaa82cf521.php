<?php if (!defined('THINK_PATH')) exit();?><!--<!doctype html>-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<link href="/theme/admin/css/style.css" rel="stylesheet"/>

<title><?php echo L('website_manage');?></title>

	<script>

	var URL = '/jradmin.php/item';

	var SELF = '/jradmin.php?m=admin&c=item&a=index&menuid=527&is_fruit=1';

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
<!--商品列表-->
<div class="pad_lr_10" >
    <form name="searchform" method="get" >
    <table width="100%" cellspacing="0" class="search_form">
            <tr>
                <td>
                <div class="explain_col">
                    <input type="hidden" name="m" value="admin" />
                    <input type="hidden" name="c" value="item" />
                    <input type="hidden" name="a" value="index" />
                    <input type="hidden" name="menuid" value="<?php echo ($menuid); ?>" />
					<?php if($sm != ''): ?><input type="hidden" name="sm" value="<?php echo ($sm); ?>" /><?php endif; ?>
                    </select>
                    &nbsp;&nbsp;发布时间：<input type="text" name="time_start" id="J_time_start" class="date"  value="<?php echo ($search["time_start"]); ?>" placeholder="时间开始">
                    -
                    <input type="text" name="time_end" id="J_time_end" class="date" value="<?php echo ($search["time_end"]); ?>" placeholder="时间结束">
                    <!--价格区间：-->
                    <!--<input type="text" name="price_min" class="input-text" size="5" value="<?php echo ($search["price_min"]); ?>" />-->
                    <!-- - -->
                    <!--<input type="text" name="price_max" class="input-text" size="5" value="<?php echo ($search["price_max"]); ?>" />-->

                    分类：<select class="J_cate_select mr10" data-pid="0" data-uri="<?php echo U('item_cate/ajax_getchilds', array('type'=>0));?>" data-selected="<?php echo ($search["selected_ids"]); ?>"></select>
                    <input type="hidden" name="cate_id" id="J_cate_id" value="<?php echo ($search["cate_id"]); ?>" />

                    &nbsp;&nbsp;状态：
                    <select name="status">
                        <option value="">-<?php echo L('all');?>-</option>
                        <option value="1" <?php if($search["status"] == '1'): ?>selected="selected"<?php endif; ?>>正常</option>
                        <option value="0" <?php if($search["status"] == '0'): ?>selected="selected"<?php endif; ?>>已下架</option>
                    </select>
                        &nbsp;&nbsp;关键字：
                    <input name="keyword" type="text" class="input-text" size="25" value="<?php echo ($search["keyword"]); ?>" placeholder="名称"/>
					<input type="submit" name="search" class="btn" value="搜索" />
                    <input type="hidden" name="is_fruit"  value="<?php echo ($search["is_fruit"]); ?>" />

                </div>
                </td>
            </tr>
    </table>
    </form>
    
<!--     <div class="explain_col">  
     <form id="addform" action="<?php echo U('Item/upload');?>" method="post" enctype="multipart/form-data">
        <input style=" width:65px;" type="file" name="excelData" value=""  datatype="*4-50" />
       <input type="submit" class="btn btn-primary Sub" value="导入" />
        
    <a href="<?php echo U('Item/goods_export');?>" class="btn btn-primary Sub" >导出</a>
     </form>
    </div>-->
   
    
    <?php if($sm == 'image'): ?><div class="J_tablelist item_imglist clearfix">
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><div class="item fl">
            <label>
            <input type="checkbox" class="J_checkitem check" value="<?php echo ($val["id"]); ?>" />
            <div class="img clearfix J_preview"><img src="<?php echo attach(get_thumb($val['img'], '_m'), 'item');?>"></div>
            </label>
            <span class="line_x"><?php echo ($val["title"]); ?></span>
            <ul>
                <li><a class="J_tooltip btn_blue" title="<?php echo ($cate_list[$val['cate_id']]); ?>"><?php echo L('cate');?></a></li>
                <li><a class="J_tooltip btn_blue" title="<?php echo ((isset($val["uname"]) && ($val["uname"] !== ""))?($val["uname"]):L('item_no_author')); ?>"><?php echo L('author');?></a></li>
            </ul>
        </div><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
    <?php else: ?>
    <div class="J_tablelist table_list" data-acturi="<?php echo U('item/ajax_edit');?>">
    <table width="100%" cellspacing="0">
            <tr>
                <th width="20"><input type="checkbox"  name="checkall" class="J_checkall"></th>
                <th><span data-tdtype="order_by" data-field="id">ID</span></th>
                <th>图片</th>
                <th width="300"><span data-tdtype="order_by" data-field="title">商品名称</span></th>
                <th width="200"><span data-tdtype="order_by" data-field="cate_id">分类</span></th>
                <th width="100"><span data-tdtype="order_by" data-field="price">商品单价</span></th>
                <th>收银类型</th>
                <!--<th><span data-tdtype="order_by" data-field="bq">商品标签</span></th>-->
                <th><span data-tdtype="order_by" data-field="inventory">库存</span></th>
                <th><span data-tdtype="order_by" data-field="ordid"><?php echo L('sort_order');?></span></th>
                <th><span data-tdtype="order_by" data-field="status"><?php echo L('status');?></span></th>
                <th><span data-tdtype="order_by" data-field="status">上架时间</span></th>
                <th><?php echo L('operations_manage');?></th>
            </tr>

            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
                <td align="center"><input type="checkbox" class="J_checkitem" value="<?php echo ($val["id"]); ?>"></td>
                <td align="center"><?php echo ($val["id"]); ?></td>
                <td style="text-align:center; padding:5px;">
                    <?php if(empty($val['img'])): ?>未设置<?php else: ?>
                        <img src="<?php echo attach($val['img'], 'item');?>" width="32" class="J_preview" data-bimg="<?php echo attach($val['img'], 'item');?>"><?php endif; ?>
                </td>
                <td align="center">
                    <span data-tdtype="edit" data-field="title" data-id="<?php echo ($val["id"]); ?>" class="tdedit" style="color:<?php echo ($val["colors"]); ?>;"><?php echo ($val["title"]); ?></span>
                    <!--<?php if(!empty($val['img'])): ?><span class="attachment_icon J_attachment_icon" file-type="image" file-rel="<?php echo attach($val['img'],'item');?>">-->
                    <!--<img src="/theme/admin/images/filetype/image_s.gif" /></span><?php endif; ?></td>-->
                <td align="center">
                         <!--<?php echo ($val['cate_name']); ?>-->
                   <b><?php if(!empty($big_cate3[$val['cate_id']])): echo ($big_cate3[$val['cate_id']]); ?>|<?php endif; ?>
                        <?php if(!empty($big_cate2[$val['cate_id']])): echo ($big_cate2[$val['cate_id']]); ?>|<?php endif; ?>
                            <?php if(!empty($cate_list[$val['cate_id']])): echo ($cate_list[$val['cate_id']]); endif; ?>
                    </b>
                </td>
                <td align="center"><?php echo ($val["price"]); ?></td>
                <td align="center">线上</td>
                <td align="center"><span data-tdtype="edit" data-field="inventory" data-id="<?php echo ($val["id"]); ?>" class="tdedit"><?php echo ($val["inventory"]); ?></span></td>
                <td align="center"><span data-tdtype="edit" data-field="ordid" data-id="<?php echo ($val["id"]); ?>" class="tdedit"><?php echo ($val["ordid"]); ?></span></td>
                <td align="center"><img data-tdtype="toggle" data-id="<?php echo ($val["id"]); ?>" data-field="status" data-value="<?php echo ($val["status"]); ?>" src="/theme/admin//images/toggle_<?php if($val["status"] == 0): ?>disabled<?php else: ?>enabled<?php endif; ?>.gif" /></td>
                <td align="center"><?php echo date('Y-m-d',$val['add_time']);?></td>
                
                <td align="center">
                	<a href="<?php echo u('item/edit', array('id'=>$val['id'], 'menuid'=>$menuid));?>"><?php echo L('edit');?></a> | 
                	<a href="javascript:void(0);" class="J_confirmurl" data-uri="<?php echo u('item/delete', array('id'=>$val['id']));?>" data-acttype="ajax" data-msg="<?php echo sprintf(L('confirm_delete_one'),$val['title']);?>"><?php echo L('delete');?></a></td>

            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    	</tbody>
    </table>
    </div><?php endif; ?>
	
    <div class="songkebor">
        <label class="select_all mr10"><input type="checkbox" name="checkall" class="J_checkall"><?php echo L('select_all');?>/<?php echo L('cancel');?></label>
        <input type="button" class="btn" data-tdtype="batch_action" data-acttype="ajax" data-uri="<?php echo U('item/delete');?>" data-name="id" data-msg="<?php echo L('confirm_delete');?>" value="批量删除" />
        <div id="pages"><?php echo ($page); ?></div> 
        <div style="clear: both;"></div>
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
<!--<link rel="stylesheet" href="/theme/admin/js/calendar/calendar-blue.css"/>
<script src="/theme/admin/js/calendar/calendar.js"></script>-->
<script>
    $('.J_preview').preview(); //查看大图


    laydate.render({
        elem: '#J_time_start'
    });

    laydate.render({
        elem: '#J_time_end'
    });


$('.J_cate_select').cate_select({top_option:lang.all}); //分类联动
$('.J_tooltip[title]').tooltip({offset:[10, 2], effect:'slide'}).dynamic({bottom:{direction:'down', bounce:true}});
</script>

</body>

</html>