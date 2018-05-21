<?php if (!defined('THINK_PATH')) exit();?><!--<!doctype html>-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<link href="/theme/admin/css/style.css" rel="stylesheet"/>

<title><?php echo L('website_manage');?></title>

	<script>

	var URL = '/jradmin.php/setting';

	var SELF = '/jradmin.php?m=admin&c=setting&a=index&menuid=148';

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
<!--网站设置-->
<div class="pad_lr_10">
	<div class="box-title">
		站点设置
	</div>
	<form id="info_form" action="<?php echo u('setting/edit');?>" method="post">
	<table width="100%" class="table_form">
        <tr>
            <th width="150">版权所有 :</th>
            <td><input type="text" name="setting[site_name]" class="input-text" size="50" value="<?php echo C('pin_site_name');?>"></td>
        </tr>
        <tr>
            <th><?php echo L('site_name');?>|<?php echo L('seo_title');?> :</th>
            <td><input type="text" name="setting[site_title]" class="input-text" size="50" value="<?php echo C('pin_site_title');?>"></td>
        </tr>
        <tr>
            <th><?php echo L('seo_keys');?> :</th>
            <td><input type="text" name="setting[site_keyword]" class="input-text" size="50" value="<?php echo C('pin_site_keyword');?>"></td>
        </tr>
        <tr>
            <th><?php echo L('seo_desc');?> :</th>
            <td><textarea rows="3" cols="50" name="setting[site_description]"><?php echo C('pin_site_description');?></textarea></td>
        </tr>
        <tr>
            <th><?php echo L('site_icp');?> :</th>
            <td><input type="text" name="setting[site_icp]" class="input-text" size="50" value="<?php echo C('pin_site_icp');?>"></td>
        </tr>
        <tr>
            <th><?php echo L('statistics_code');?> :</th>
            <td><textarea rows="3" cols="50" name="setting[statistics_code]"><?php echo C('pin_statistics_code');?></textarea></td>
        </tr>
		<tr>
            <th>网址 :</th>
            <td><input type="text" name="setting[site_url]" class="input-text" size="50" value="<?php echo C('pin_site_url');?>">不带http://</td>
        </tr>
		<tr>
            <th>邮箱 :</th>
            <td><input type="text" name="setting[email]" class="input-text" size="50" value="<?php echo C('pin_email');?>"></td>
        </tr>
		<tr>
            <th>电话 :</th>
            <td><input type="text" name="setting[tel]" class="input-text" size="50" value="<?php echo C('pin_tel');?>"></td>
        </tr>
		<tr>
            <th>手机 :</th>
            <td><input type="text" name="setting[mobile]" class="input-text" size="50" value="<?php echo C('pin_mobile');?>"></td>
        </tr>
		<tr>
            <th>地址 :</th>
            <td><input type="text" name="setting[address]" class="input-text" size="50" value="<?php echo C('pin_address');?>"></td>
        </tr>
        <tr>
            <th>银币日成长比例 :</th>
            <td>
                <input type="text" name="setting[silver_coin]" class="input-text" size="20" value="<?php echo C('pin_silver_coin');?>">/万
            </td>
        </tr>
        
        <tr>
            <th>银币转余额比例 :</th>
            <td>
                <input type="text" name="setting[jb_ye]" class="input-text" size="20" value="<?php echo C('pin_jb_ye');?>">%
            </td>
        </tr>
        
        <tr>
            <th>银币转余额手续费 :</th>
            <td>
                <input type="text" name="setting[jb_ye_sxf]" class="input-text" size="20" value="<?php echo C('pin_jb_ye_sxf');?>">%
            </td>
        </tr>
        <tr>
            <th>银币转余额金果占比 :</th>
            <td>
                <input type="text" name="setting[jb_ye_jg]" class="input-text" size="20" value="<?php echo C('pin_jb_ye_jg');?>">%
            </td>
        </tr>
         <tr>
            <th>金果市场价 :</th>
            <td>
                <input type="text" name="setting[jg_scj]" class="input-text" size="20" value="<?php echo C('pin_jg_scj');?>">元
            </td>
        </tr>
        <tr>
            <th>金果互转手续费 :</th>
            <td>
                <input type="text" name="setting[jg_sxf]" class="input-text" size="20" value="<?php echo C('pin_jg_sxf');?>">%
            </td>
        </tr>
        <tr>
            <th>元宝互转手续费 :</th>
            <td>
                <input type="text" name="setting[yb_sxf]" class="input-text" size="20" value="<?php echo C('pin_yb_sxf');?>">%
            </td>
        </tr>
        <tr>
            <th>余额提现手续费 :</th>
            <td>
                <input type="text" name="setting[tx_sxf]" class="input-text" size="20" value="<?php echo C('pin_tx_sxf');?>">元
            </td>
        </tr>
        <tr>
            <th>单笔最高提现金额 :</th>
            <td>
                <input type="text" name="setting[tx_db_je]" class="input-text" size="20" value="<?php echo C('pin_tx_db_je');?>">万
            </td>
        </tr>
        <tr>
            <th>每日最高提现金额 :</th>
            <td>
                <input type="text" name="setting[tx_mr_je]" class="input-text" size="20" value="<?php echo C('pin_tx_mr_je');?>">万
            </td>
        </tr>
        <tr>
            <th>银楼置换银币:</th>
            <td>
                <input type="text" name="setting[yl_bs]" class="input-text" size="20" value="<?php echo C('pin_yl_bs');?>">倍
            </td>
        </tr>
        <tr>
            <th>聚宝盆银币收益:</th>
            <td>
                <input type="text" name="setting[jbp_bs]" class="input-text" size="20" value="<?php echo C('pin_jbp_bs');?>">倍
            </td>
        </tr>
        <tr>
            <th>聚宝盆周期:</th>
            <td>
                <input type="text" name="setting[jbp_zq]" class="input-text" size="20" value="<?php echo C('pin_jbp_zq');?>">天
            </td>
        </tr>
        <tr>
            <th>提现周期:</th>
            <td>
                <input type="text" name="setting[tx_zq]" class="input-text" size="20" value="<?php echo C('pin_tx_zq');?>">天
            </td>
        </tr>


    	<tr>
        	<th><?php echo L('site_status');?> :</th>
        	<td>
            	<label><input type="radio" class="J_change_status" <?php if(C('pin_site_status') == '1'): ?>checked="checked"<?php endif; ?> value="1" name="setting[site_status]"> <?php echo L('open');?></label> &nbsp;&nbsp;
                <label><input type="radio" class="J_change_status" <?php if(C('pin_site_status') == '0'): ?>checked="checked"<?php endif; ?> value="0" name="setting[site_status]"> <?php echo L('close');?></label>
            </td>
    	</tr>
        <tr id="J_closed_reason" <?php if(C('pin_site_status') == 1): ?>class="hidden"<?php endif; ?>>
        	<th><?php echo L('closed_reason');?> :</th>
        	<td><textarea rows="4" cols="50" name="setting[closed_reason]" id="closed_reason"><?php echo C('pin_closed_reason');?></textarea></td>
    	</tr>
        <tr>
        	<th></th>
        	<td><input type="hidden" name="menuid"  value="<?php echo ($menuid); ?>"/><input type="submit" class="btn btn_submit" value="<?php echo L('submit');?>"/></td>
    	</tr>


    </table>
	</form>
    <br/>
    <br/>
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
$(function(){
    $('.J_change_status').live('click', function(){
        if($(this).val() == '0'){
            $('#J_closed_reason').fadeIn();
        }else{
            $('#J_closed_reason').fadeOut();
        }
    });
});
</script>
</body>
</html>