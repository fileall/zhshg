<?php if (!defined('THINK_PATH')) exit();?><!--<!doctype html>-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<link href="/theme/admin/css/style.css" rel="stylesheet"/>

<title><?php echo L('website_manage');?></title>

	<script>

	var URL = '/jradmin.php/service';

	var SELF = '/jradmin.php?m=admin&c=service&a=add&menuid=460';

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
<!--添加区代-->
<form id="info_form" action="<?php echo u('Service/add');?>" method="post" enctype="multipart/form-data">
	<input type="hidden" name="is_qd" value="1" />
	<div class="pad_lr_10">
		<div class="col_tab">
			<ul class="J_tabs tab_but cu_li">
				<li class="current">基本信息</li>
				<!--<li>展示图片</li>-->
				<!-- <li>SEO设置</li> -->
				<!--<li>商品规格</li>-->
			</ul>
			<div class="J_panes">
				<div class="content_list pad_10">
					<table width="100%" cellpadding="2" cellspacing="1" class="table_form">
						<tr>
							<th>昵称 :</th>
							<td><input type="text" name="nickname" id="nickname" class="input-text" size="30" value="<?php echo ($info["nickname"]); ?>"></td>
						</tr>

						<tr>
							<th>真实姓名 :</th>
							<td>
								<input type="text" name="realname" id="realname" class="input-text" size="20" maxlength="15" >

							</td>
						</tr>

						<tr>
							<th>身份证号 :</th>
							<td>
                                <input type="text" name="is_nums" id="is_nums" class="input-text" size="20" maxlength="15" >
							</td>
						</tr>

						<tr>
							<th>手机号 :</th>
							<td>
								<input type="text" name="mobile" id="mobile" class="input-text" size="20" maxlength="15" >
							</td>
						</tr>
						<!--<tr>
							<th>等级 :</th>
							<td>
								<select name="vips_qd">
									<?php $_result=vips_qd();if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($i); ?>" <?php if($info['vips_qd'] == $i): ?>selected<?php endif; ?>><?php echo ($val); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
								</select>
							</td>
						</tr>-->
						<tr>
							<th>地区 :</th>
							<td>
								<select class="J_cate_select mr10" data-pid="0" data-uri="<?php echo U('place/ajax_getchilds', array('type'=>0));?>" data-selected="<?php echo ($place_selected_ids); ?>"></select>
								<input type="hidden" name="province_id" id="aaaa" value="0" >
								<input type="hidden" name="city_id" id="bbbb" value="0" >
								<input type="hidden" name="district_id" id="cccc" value="0" >
								<input type="hidden" name="cate_id" id="place_id" value="0" />
							</td>
						</tr>
						<tr>
							<th>头像 :</th>
							<td>
								<?php if(!empty($info['avatar'])): ?><a class="fancybox" rel="group" href="<?php echo attach($info['avatar'],'avatar');?>">
										<img src="<?php echo attach($info['avatar'], 'avatar');?>" width="100" height="100"/></a>
									<br /><?php endif; ?>
								<!--<?php if(!empty($info['img'])): ?><img src="<?php echo attach(get_thumb($info['img'], '_m'), 'item');?>" width="100" height="100"/><br /><?php endif; ?>-->
								<input type="file" name="avatar" />
							</td>
						</tr>

						<tr>
							<th>身份证正面 :</th>
							<td>
								<?php if(!empty($idcard_imgs[1])): ?><a class="fancybox" rel="group" href="<?php echo attach($idcard_imgs[1],'id_card');?>">
										<img src="<?php echo attach($idcard_imgs[1],'id_card');?>" width="100" height="100"/></a>
									<br /><?php endif; ?>
								<input type="file" name="id_card1" />
							</td>
						</tr>

						<tr>
							<th>身份证反面 :</th>
							<td>
								<?php if(!empty($idcard_imgs[2])): ?><a class="fancybox" rel="group" href="<?php echo attach($idcard_imgs[2],'id_card');?>">
										<img src="<?php echo attach($idcard_imgs[2],'id_card');?>" width="100" height="100"/></a>
									<br /><?php endif; ?>
								<input type="file" name="id_card2" />
							</td>
						</tr>

						<tr>
							<th>性别 :</th>
							<td>
								<label><input name="sex" type="radio" value="1" checked="checked"> 男</label>
								<label><input name="sex" type="radio" value="2" checked="checked"> 女</label>&nbsp;&nbsp;
								<label><input name="sex" type="radio" value="0" checked="checked" > 未设置</label>
							</td>
						</tr>
						<tr>
							<th>新密码 :</th>
							<td><input type="password" name="password" id="J_password" class="input-text" size="30">
								<!--<label class="gray">&nbsp;&nbsp;不修改则留空</label>-->
							</td>
						</tr>
						<tr>
							<th>支付密码 :</th>
							<td><input type="password" name="paypassword" id="J_pay_pwd" class="input-text" size="30">
								<!--<label class="gray">&nbsp;&nbsp;不修改则留空</label>-->
							</td>
						</tr>

					</table>
				</div>

			</div>
			<div class="mt10"><input type="submit" value="<?php echo L('submit');?>" id="dosubmit" name="dosubmit" class="btn btn_submit" style="margin:0 0 10px 100px;"></div>
			<br/>
			<br/>

		</div>
	</div>
	<input type="hidden" name="menuid"  value="<?php echo ($menuid); ?>"/>
</form>

<script src="/theme/admin/js/kindeditor/kindeditor-min.js"></script>
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
    $(document).ready(function () {
        $( ".fancybox").fancybox();
    });

    //二维码
    $('#ewm').click(function(){
        if(confirm("是否确定更换二维码?"))
        {
            var id=$(this).data('id');
            $.get("<?php echo U('member/ewm');?>",{id:id},function(res){
                if(res.status==1){
                    $('input[name="ewm"]').val(res.url);
                    $('#aa').attr('href','/data/attachment/ewm/'+res.url);
                    $('#bb').attr('src','/data/attachment/ewm/'+res.url);
                }else{
                    $.pinphp.tip({content:'系统繁忙', icon:'alert'});
                }
            })
        }

    })
</script>
<script type="text/javascript">
    //区域分类联动
    $('.J_cate_select').cate_select({
        top_option: lang.all,
        field: 'place_id',
        target_class: 'J_cate_select'
    });

    $('body').live('change','.J_cate_select.mr10',function(){
        if($('#place_id').val()!=0){
            var aa=$('.J_cate_select:eq(0)').val();
            var bb=$('.J_cate_select:eq(1)').val();
            var cc=$('.J_cate_select:eq(2)').val();
            $('#aaaa').val(aa);
            $('#bbbb').val(bb);
            $('#cccc').val(cc);
        }
    })

    //表单
    $(function() {
        $('ul.J_tabs').tabs('div.J_panes > div');

        $.formValidator.initConfig({formid:"info_form",autotip:true});
        // $("#J_password").formValidator({empty:true,onshow:lang.not_edit_password, onfocus:lang.password+lang.between_6_to_20}).inputValidator({ min:6, max:20, onerror:lang.password+lang.between_6_to_20});
    });

    $(function(){
        $.formValidator.initConfig({formid:"info_form",autotip:true});
        $("#nickname").formValidator({onshow:"请填写名称",onfocus:"请填写名称"}).inputValidator({min:1,onerror:"请填写名称"});
        $("#realname").formValidator({onshow:"请填写名称",onfocus:"请填写名称"}).inputValidator({min:1,onerror:"请填写名称"});
        $("#is_nums").formValidator({ onshow: "请输入18位的身份证", onfocus: "输入18位的身份证", oncorrect: "输入正确" }).regexValidator({ regexp: "idcard", datatype: "enum", onerror: "你输入的身份证格式不正确" }); ;
        $("#mobile").formValidator({ onshow: "请输入数字", oncorrect: "谢谢你的合作，你的数字正确" }).regexValidator({ regexp: "num", datatype: "enum", onerror: "数字格式不正确" });
        /*$("#id_card1").formValidator({ onshow: "请输入图片名", oncorrect: "谢谢你的合作，你的图片名正确" }).regexValidator({ regexp: "picture", datatype: "enum", onerror: "图片名格式不正确" });
        $("#id_card2").formValidator({ onshow: "请输入", oncorrect: "谢谢你的合作，正确" }).regexValidator({ regexp: "notempty", datatype: "enum", onerror: "身份证不能为空" });*/
        $('#info_form').ajaxForm({success:complate,dataType:'json'});
    })
</script>
</body>
</html>