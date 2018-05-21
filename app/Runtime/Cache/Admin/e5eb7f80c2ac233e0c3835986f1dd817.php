<?php if (!defined('THINK_PATH')) exit();?><!--<!doctype html>-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<link href="/theme/admin/css/style.css" rel="stylesheet"/>

<title><?php echo L('website_manage');?></title>

	<script>

	var URL = '/jradmin.php/member';

	var SELF = '/jradmin.php?m=admin&c=member&a=index&menuid=149';

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
<!--会员列表-->
<div class="pad_10" >

  <form name="searchform" method="get"  >
    <table width="100%" cellspacing="0" class="search_form">
        <tbody>
            <tr>
                <td>
                <div class="explain_col">
                    <input type="hidden" name="m" value="admin" />
                    <input type="hidden" name="c" value="Member" />
                    <input type="hidden" name="a" value="index" />
                    <input type="hidden" name="menuid" value="<?php echo ($menuid); ?>" />

                    <input type="hidden" name="cate_id" id="J_cate_id" value="<?php echo ($search["cate_id"]); ?>" />
                    &nbsp;&nbsp;注册日期：<input type="text" name="time_start" id="J_time_start" class="date" value="<?php echo ($search["time_start"]); ?>" placeholder="时间开始">
                    -
                    <input type="text" name="time_end" id="J_time_end" class="date"  value="<?php echo ($search["time_end"]); ?>" placeholder="时间截止">
                    &nbsp;&nbsp;会员等级：
                    <select name="vips">
                        <option value="">全部</option>
                        <?php if(is_array($grade)): $i = 0; $__LIST__ = $grade;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if($search['vips'] == $key): ?>selected<?php endif; ?> ><?php echo ($val); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    &nbsp;&nbsp;关键字 :
                    <input name="keyword" type="text" class="input-text"  value="<?php echo ($search["keywords"]); ?>" placeholder="会员名/手机" />
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

  <div class="J_tablelist table_list" data-acturi="<?php echo U('member/ajax_edit');?>">
        <table width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="20"><input type="checkbox" id="checkall_t" class="J_checkall"></th>
                <th>id</th>
                <th>头像</th>
                <th>昵称</th>
                <!--<th>姓名</th>-->
                <th>手机</th>
                <th>级别</th>
                <th>推荐<br/>会员</th>
                <th>推荐<br/>商家</th>
                <th>聚宝盆</th>
                <th><span data-tdtype="order_by" data-field="prices">工资</span></th>
                <th><span data-tdtype="order_by" data-field="gold_acer">元宝</span></th>
                <th><span data-tdtype="order_by" data-field="gold_fruit">金果</span></th>
                <th><span data-tdtype="order_by" data-field="silver_coin">银币</span></th>
                <th>推荐人</th>
                <th><span data-tdtype="order_by" data-field="reg_time">注册日期</span></th>
                <!--<th width="100">升级日期</th>-->
                <th><span data-tdtype="order_by" data-field="status"><?php echo L('status');?></span></th>
                <th><?php echo L('operations_manage');?></th>
          </tr>
        </thead>

            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
                <td align="center"><input type="checkbox" class="J_checkitem" value="<?php echo ($val["id"]); ?>"></td>
                <!--<td align="center"><?php echo ($p*10-10+$i); ?></td>-->
                <td align="left"><?php echo ($val["id"]); ?></td>
                <td style="text-align:center; padding:5px;">
                    <?php if(empty($val['avatar'])): ?>未设置<?php else: ?>
                        <img src="<?php echo attach($val['avatar'], 'avatar');?>" width="32" class="J_preview" data-bimg="<?php echo attach($val['avatar'], 'avatar');?>"><?php endif; ?>
                </td>
                <td align="center"><?php echo ($val['nickname']); ?></td>
                <!--<td align="center"><?php echo ($val['realname']); ?></td>-->
                <td align="center"><?php echo ($val["mobile"]); ?></td>
                <td align="center"><?php echo ($grade[$val['vips']]); ?></td>
                <td>
                    <?php if(recommend_nums($val['id'])): ?><a class="red" href="<?php echo U('member/next_list',array('id'=>$val['id'],'menuid'=>$menuid));?>"><?php echo recommend_nums($val['id']);?></a><?php endif; ?>
                </td>
                <!--<td align="center"><?php echo ($val["relation_mobile"]); ?></td>-->
                <td>
                    <?php if(recommend_merchant($val['id'])): ?><a class="red" href="<?php echo U('member/merchant_list',array('id'=>$val['id'],'menuid'=>$menuid));?>"><?php echo recommend_merchant($val['id']);?></a><?php endif; ?>
                </td>
                <td align="center"><?php echo ($val['gold_acer_jc']); ?></td>

                <td align="center">
                    <a class="blue" href="<?php echo U('account/index',array('uid'=>$val['id'],'type'=>1));?>"><?php echo ($val["prices"]); ?></a>
                </td>
                <td align="center">
                    <a class="blue" href="<?php echo U('account/index',array('uid'=>$val['id'],'type'=>2));?>"><?php echo ($val["gold_acer"]); ?></a>
                </td>
                <td align="center">
                    <a class="blue" href="<?php echo U('account/index',array('uid'=>$val['id'],'type'=>3));?>"><?php echo ($val["gold_fruit"]); ?></a>
                </td>
                <td align="center">
                    <a class="blue" href="<?php echo U('account/index',array('uid'=>$val['id'],'type'=>4));?>"><?php echo ($val["silver_coin"]); ?></a>
                </td>
                <td align="center"><?php echo ($member[$val['relation_id']]); ?></td>

                <td align="center"><?php echo (date("Y-m-d",$val['reg_time'])); ?></td>
                <td align="center"><img data-tdtype="toggle" data-id="<?php echo ($val["id"]); ?>" data-field="status" data-value="<?php echo ($val["status"]); ?>" src="/theme/admin//images/toggle_<?php if($val["status"] == 0): ?>disabled<?php else: ?>enabled<?php endif; ?>.gif" /></td>
                 <td align="center">
                     <a href="<?php echo U('member/edit',array('id'=>$val['id']));?>">编辑</a>
                     <!--<a href="javascript:;" class="J_showdialog" data-uri="<?php echo u('member/edit', array('id'=>$val['id'], 'menuid'=>$menuid,'type'=>1));?>" data-title="升级-<?php echo ($val["nickname"]); ?>" data-id="edit" data-width="500" data-height="20">升级</a> |-->
                     <!--<a href="javascript:;" class="J_showdialog" data-uri="<?php echo u('member/edit', array('id'=>$val['id'], 'menuid'=>$menuid,'type'=>2));?>" data-title="修改-<?php echo ($val["nickname"]); ?>" data-id="edit" data-width="500" data-height="20">修改</a>-->
                </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </table>
  </div>
 
   <div class="songkebor">

        <label class="select_all"><input  style="display: none;" type="checkbox" name="checkall" class="J_checkall">分页&nbsp;&nbsp;&nbsp;总条数:<?php echo ($count); ?>条</label>
        <!--<?php echo L('select_all');?>/<?php echo L('cancel');?>-->

        <!--<input type="button" class="btn" data-tdtype="batch_action" data-acttype="ajax" data-uri="<?php echo U('member/delete');?>" data-name="id" data-msg="<?php echo L('confirm_delete');?>" value="批量删除" />-->
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

</html>

<script>
    laydate.render({
        elem: '#J_time_start'
    });

    laydate.render({
        elem: '#J_time_end'
    });


    $('.J_preview').preview(); //查看大图


    $(".export").click(function(){
    	$("input[name='a']").val("export");
    	$("form").submit();
     })
    
     $(".look_index").click(function(){
    	$("input[name='a']").val("index");
    	$("form").submit()
    })
</script>