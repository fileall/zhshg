<?php if (!defined('THINK_PATH')) exit();?><!--<!doctype html>-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<link href="/theme/admin/css/style.css" rel="stylesheet"/>

<title><?php echo L('website_manage');?></title>

	<script>

	var URL = '/jradmin.php/merchant';

	var SELF = '/jradmin.php?m=admin&c=merchant&a=sh_list&menuid=476';

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
<!--商家列表-->
<div class="pad_10" >

    <form name="searchform" method="get"  >
        <table width="100%" cellspacing="0" class="search_form">
            <tbody>
            <tr>
                <td>
                    <div class="explain_col">
                        <input type="hidden" name="m" value="admin" />
                        <input type="hidden" name="c" value="Merchant" />
                        <input type="hidden" name="a" value="sh_list" />
                        <input type="hidden" name="menuid" value="<?php echo ($menuid); ?>" />
                        &nbsp;&nbsp;添加日期：<input type="text" name="time_start" id="J_time_start" class="date" value="<?php echo ($search["time_start"]); ?>" placeholder="时间开始">
                        -
                        <input type="text" name="time_end" id="J_time_end" class="date"  value="<?php echo ($search["time_end"]); ?>" placeholder="时间截止">
                        <!--地区：<select class="J_cate_select mr10" data-pid="0" data-uri="<?php echo U('place/ajax_getchilds');?>" data-selected="<?php echo ($search["selected_ids"]); ?>"></select>-->
                        <!--<input type="hidden" name="cate_id" id="J_cate_id" value="<?php echo ($search["cate_id"]); ?>" />-->

                        &nbsp;&nbsp;分类：<select class="J_cate_select mr10" data-pid="0" data-uri="<?php echo U('MerchantCate/ajax_getchilds');?>" data-selected="<?php echo ($search["selected_ids"]); ?>"></select>
                        <input type="hidden" name="cate_id" id="J_cate_id" value="<?php echo ($search["cate_id"]); ?>" />

                        &nbsp;&nbsp;关键字 :
                        <input name="keywords" type="text" class="input-text"  value="<?php echo ($search["keywords"]); ?>" placeholder="店名/电话/会员名" size="10"/>
                        <input type="submit"  class="btn look_index" value="搜索" />
                        <input type="button"  class="btn export" value="下载报表" />
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </form>

    <!--<div class="explain_col">
        <form id="addform" action="<?php echo U('Member/member_upload');?>" method="post" enctype="multipart/form-data">
            <input type="file" style=" width:65px;" name="excelData" value="" datatype="*4-50" nullmsg="" />
            <input type="submit" class="btn btn-primary Sub" value="导入" />
            <a href="/download/member_import.xls" class="btn btn-primary Sub" >下载导入模板</a>
            <input type="button" class="btn" data-tdtype="batch_action" data-uri="<?php echo U('Member/export');?>" data-name="id" data-msg="确定导出已经选中的学生吗？" value="导出" />
        </form>
    </div>-->

    <div class="J_tablelist table_list" data-acturi="<?php echo U('Merchant/ajax_status_edit');?>">
        <table width="100%" cellspacing="0">
            <thead>
            <tr>
                <th width="20"><input type="checkbox" id="checkall_t" class="J_checkall"></th>
                <th width="70"  align="left"><span data-tdtype="order_by" data-field="id">id</span></th>
                <th width="84" align="left"><span data-tdtype="order_by" data-field="cate_id">分类</span></th>
                <th width="70"  align="left"><span data-tdtype="order_by" data-field="title">店名</span></th>
                <th width="70"  align="left">会员名</th>
                <th width="70"  align="left">会员电话</th>
                <th width="84" align="left">店铺电话</th>
                <th width="125" align="left">地址</th>
                <th width="90" align="left">图片审核</th>
                <th width="90" align="left"><span data-tdtype="order_by" data-field="tuijian">推荐人</span></th>
                <th width="100"><span data-tdtype="order_by" data-field="add_time">添加日期</span></th>
                <th width="50"><?php echo L('operations_manage');?></th>
            </tr>
            </thead>

            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
                    <td align="center"><input type="checkbox" class="J_checkitem" value="<?php echo ($val["id"]); ?>"></td>
                    <td align="center"><?php echo ($val['id']); ?></td>
                    <td align="center">
                        <b><?php if(!empty($big_cate3[$val['cate_id']])): echo ($big_cate3[$val['cate_id']]); ?>|<?php endif; ?>
                            <?php if(!empty($big_cate2[$val['cate_id']])): echo ($big_cate2[$val['cate_id']]); ?>|<?php endif; ?>
                            <?php if(!empty($cate_list[$val['cate_id']])): echo ($cate_list[$val['cate_id']]); endif; ?>
                        </b>
                    </td>
                    <td align="center"><?php echo ($val['title']); ?></td>
                    <td align="center"><?php echo ($member[$val['uid']]['realname']); ?></td>
                    <td align="center"><?php echo ($member[$val['uid']]['mobile']); ?></td>
                    <td align="center"><?php echo ($val["tel"]); ?></td>
                    <td align="center"><?php echo ($val["address"]); ?></td>
                    <td align="center">
                        <a href="javascript:;" class="J_showdialog"
                           data-uri="<?php echo U('sh_details', array('id'=>$val['id']));?>" data-title="详情" data-id="edit" data-width="500">详情</a>
                    </td>
                    <td align="center">
                        <?php echo ($member_tj[$val[tuijian]]['mobile']); ?>
                    </td>
                    <td align="center"><?php echo (date("Y-m-d",$val['add_time'])); ?></td>
                    <td align="center">
                        <?php switch($val['status']): case "0": ?><a href="javascript:;" class="J_showdialog" data-uri="<?php echo u('merchant/pass', array('id'=>$val['id'], 'menuid'=>$menuid));?>" data-title="确定通过吗" data-id="pass" data-width="500" data-height="20">通过</a>|
                                <a href="javascript:;" class="J_confirmurl" data-acttype="ajax" data-uri="<?php echo U('merchant/refuse', array('id'=>$val['id'],'menuid'=>$menuid));?>" data-msg="确定驳回吗">驳回</a><?php break;?>
                            <?php case "1": ?>已驳回<?php break; endswitch;?>
                    </td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>

        </table>
    </div>
    <div class="songkebor">

        <label class="select_all"><input type="checkbox" name="checkall" class="J_checkall"><?php echo L('select_all');?>/<?php echo L('cancel');?></label>
        <!--<input type="button" class="btn" data-tdtype="batch_action" data-acttype="ajax" data-uri="<?php echo U('Merchant/delete');?>" data-name="id" data-msg="<?php echo L('confirm_delete');?>" value="批量删除" />-->
        <div id="pages"><?php echo ($page); ?></div>

    </div>
</div>

<!--查看大图-->
<div class="popup-win">
    <div class="popup-inner">
        <div class="vertical-auto popup-image"><img src=""></div>
        <div class="close-popup">×</div>
    </div>
</div>
<!--查看大图-->
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

<!--<link rel="stylesheet" type="text/css" href="/theme/admin//js/calendar/calendar-blue.css"/>

<script type="text/javascript" src="/theme/admin//js/calendar/calendar.js"></script>-->
<script>

    laydate.render({
        elem: '#J_time_start'
    });

    laydate.render({
        elem: '#J_time_end'
    });
    //查看大图
    // $(".check-image").click(function(){
    //     $(".popup-win").show();
    //     var img_src = $(this).find("img").attr("src");
    //     $(".vertical-auto img").attr("src",img_src);
    // });
    $('.J_preview').preview(); //查看大图

    $('.J_cate_select').cate_select({top_option:lang.all}); //分类联动



    $(".close-popup").click(function(){
        $(".popup-win").hide();
    });
    $(".look_index").click(function(){
        $("input[name='a']").val("sh_list");
        $("form").submit();
    })
    //下载表报
    $(".export").click(function(){
        $("input[name='a']").val("export_sh");
        $("form").submit();
    })

</script>