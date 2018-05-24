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

	var SELF = '/jradmin.php?m=admin&c=item&a=edit&id=3078&menuid=52';

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
<!--添加文章-->
<form id="info_form" action="<?php echo U('Item/edit');?>" method="post" enctype="multipart/form-data">
	<div class="pad_lr_10">
		<div class="col_tab">
			<ul class="J_tabs tab_but cu_li">
				<li class="current"><?php echo L('article_basic');?></li>
				<li>商品轮播图</li>
				<li>附加属性</li>
			</ul>
			<div class="J_panes">
				<div class="content_list pad_10">
					<table width="100%" cellspacing="0" class="table_form">


						<tr>
							<th width="120"><?php echo L('article_cateid');?> :</th>
							<td><select class="J_cate_select mr10" data-pid="0" data-uri="<?php echo U('Item_cate/ajax_getchilds');?>" data-selected="<?php echo ($selected_ids); ?>"></select><input type="hidden" name="cate_id" id="J_cate_id" value="<?php echo ($info["cate_id"]); ?>" /></td>
						</tr>
						<tr>
							<th width="120">选择品牌 :</th>
							<td>
								<select name="brand_id" >
									<option value="0">--选择品牌--</option>
									<?php if(is_array($brand_list)): $i = 0; $__LIST__ = $brand_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if($info['brand_id'] == $key): ?>selected<?php endif; ?> ><?php echo ($val); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
								</select>
							</td>
						</tr>

						<tr>
							<th>商品名称 :</th>
							<td>
								<input type="text" name="title" id="J_title" class="input-text" size="60" value="<?php echo ($info['title']); ?>" >
							</td>
						</tr>
						<tr>
							<th>商品主图 :</th>
							<td>
								<?php if(!empty($info['img'])): ?><span class="attachment_icon J_attachment_icon" file-type="image" file-rel="<?php echo attach($info["img"],'item');?>"><img src="<?php echo attach($info["img"],'item');?>" width="100" height="100" /></span><br /><?php endif; ?>
								<input type="file" name="img" id="img" class="input-text"  style="width:200px;" />
							</td>
						</tr>
						<tr>
							<th>商城价格 :</th>
							<td><input type="text" id="J_price" name="price" size="20" class="input-text" value="<?php echo ($info['price']); ?>"></td>
						</tr>
						<tr>
							<th>原价 :</th>
							<td><input type="text" name="oldprice" size="20" class="input-text" value="<?php echo ($info['oldprice']); ?>"></td>
						</tr>
						<tr>
							<th>销量 :</th>
							<td><input type="text" name="sales" size="20" class="input-text" value="<?php echo ($info['sales']); ?>"></td>
						</tr>
						<tr>
							<th>商品库存:</th>
							<td><input type="text" name="inventory" size="20" class="input-text" value="<?php echo ($info['inventory']); ?>"></td>
						</tr>
						<tr>
							<th>金果商城 :</th>
							<td>
								<input type="radio" name="is_fruit" value="1" <?php if($info['is_fruit'] == '1'): ?>checked<?php endif; ?> />加入
								<input type="radio" name="is_fruit" value="0" <?php if($info['is_fruit'] == '0'): ?>checked<?php endif; ?> />不加入
							</td>
						</tr>
						<tr>
							<th>商品标签 :</th>
							<td>
								<input name="fx" type="checkbox" value="1" <?php if($info['fx'] ==1): ?>checked<?php endif; ?>/>发现好货
								<input name="zhm" type="checkbox" value="1" <?php if($info['zhm'] ==1): ?>checked<?php endif; ?>/>臻会买专辑
								<input name="jx" type="checkbox" value="1" <?php if($info['jx'] ==1): ?>checked<?php endif; ?>/>臻怡家精选
							</td>
						</tr>
						 <!--<tr>-->
                             <!--<th>收银类型 :</th>-->
							 <!--<td>-->
								 <!--<input name="zftype[]" type="checkbox" value="1" <?php if($info['fx'] ==1): ?>checked<?php endif; ?>/>元宝-->
								 <!--<input name="zftype[]" type="checkbox" value="1" <?php if($info['zhm'] ==1): ?>checked<?php endif; ?>/>金果-->
								 <!--<input name="zftype[]" type="checkbox" value="1" <?php if($info['jx'] ==1): ?>checked<?php endif; ?>/>云宝+金币-->
							 <!--</td>-->
                         <!--</tr>-->
						<!--<tr>-->
							<!--<th>放入金果商城:</th>-->
							<!--<td>-->
							<!--<label><input type="radio" name="tj" class="radio_style" value="1" <?php if($info['tj'] == '1'): ?>checked="checked"<?php endif; ?>> <?php echo L('yes');?> </label>&nbsp;&nbsp;-->
							<!--<label><input type="radio" name="tj" class="radio_style" value="0" <?php if($info['tj'] == '0'): ?>checked="checked"<?php endif; ?>> <?php echo L('no');?></label>-->
							<!--</td>-->
						<!--</tr>-->

						<tr>
							<th>详细内容 :</th>
							<td><textarea name="info" class="info" style="width:68%;height:400px;visibility:hidden;resize:none;"><?php echo ($info['info']); ?></textarea></td>
						</tr>
					</table>
				</div>


				<!--商品图片-->
				<div class="content_list pad_10 "><!--hidden-->
					<table width="100%" cellpadding="2" cellspacing="1" class="table_form" id="first_upload_file">
						<tbody class="uplode_file">
						<tr>
							<th class="td_left" style="text-align: left;">
								<a class="btn" id="logo_upload_btn" href="javascript:;" style=" width: 60px; margin:10px 0;">上传图片</a>
							</th>
						</tr>
						<tr>
							<td>
								<div id="logo_upload_area" style='width:80%'>
									<div id='photos_area' class="photos_area clearfix">
										 <?php if(is_array($img_list)): $i = 0; $__LIST__ = $img_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><div class='item'>
												<input type='hidden' name='imgs[]' value='<?php echo ($val['url']); ?>'/>
												<img src="<?php echo attach($val['url'], 'item');?>"  width='100px' height='100px'/>
												<div class='operate'><i class='toleft'>左移</i><i class='toright'>右移</i><i class='del' data-id="<?php echo ($val['id']); ?>">删除</i></div>
											</div><?php endforeach; endif; else: echo "" ;endif; ?>
									</div>
								</div>
							</td>
						</tr>
						</tbody>
					</table>
				</div>

				<!--附加属性:多属性设置-->
				<div class="content_list pad_10 hidden">
					<table width="100%" cellpadding="2" cellspacing="1" class="table_form" id="item_attr">
						<tbody class="add_item_attr">

						<?php if(is_array($attr_list)): $i = 0; $__LIST__ = $attr_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr class="shuxing">
								<td align="left" >
									<a href="javascript:void(0);" class="blue" onclick="del_attr(<?php echo ($val["id"]); ?>,this);"><img src="/theme/admin//css/bgimg/tv-collapsable.gif" /></a>
									商品规格 :<span data-tdtype="edit" data-field="attr_name" data-id="<?php echo ($val["id"]); ?>" class="tdedit"><?php echo ($val["attr_name"]); ?></span>
									库存 :<span data-tdtype="edit" data-field="attr_value" data-id="<?php echo ($val["id"]); ?>" class="tdedit"><?php echo ($val["attr_value"]); ?></span>
									价格 :<span data-tdtype="edit" data-field="price" data-id="<?php echo ($val["id"]); ?>" class="tdedit"><?php echo ($val["price"]); ?></span>
									原价 :<span data-tdtype="edit" data-field="oldprice" data-id="<?php echo ($val["id"]); ?>" class="tdedit"><?php echo ($val["oldprice"]); ?></span>
									排序 :<span data-tdtype="edit" data-field="ordid" data-id="<?php echo ($val["id"]); ?>" class="tdedit"><?php echo ($val["ordid"]); ?></span>
									参与金果商城 :<?php if($val["is_fruit"] == 1): ?><input type="button" class="is_fruit" data-id="<?php echo ($val["id"]); ?>" data-val="<?php echo ($val["is_fruit"]); ?>" value="是"/>
									<?php else: ?><input type="button" class="is_fruit" data-id="<?php echo ($val["id"]); ?>" data-val="<?php echo ($val["is_fruit"]); ?>" value="否"/><?php endif; ?>

									★支付时  :
									<span data-tdtype="edit" data-field="gold_fruit" data-id="<?php echo ($val["id"]); ?>" class="tdedit"><?php echo ($val["gold_fruit"]); ?></span>金果|
									<span data-tdtype="edit" data-field="acer" data-id="<?php echo ($val["id"]); ?>" class="tdedit"><?php echo ($val["acer"]); ?></span>元宝加
									<span data-tdtype="edit" data-field="coin" data-id="<?php echo ($val["id"]); ?>" class="tdedit"><?php echo ($val["coin"]); ?></span>银币|
								</td>
							</tr><?php endforeach; endif; else: echo "" ;endif; ?>

						 <tr>
							<td align="left">
								<a href="javascript:void(0);" class="blue" onclick="add_attr_add();"><img src="/theme/admin//css/bgimg/tv-expandable.gif" /></a>
								商品规格: <input type="text" name="attr[attr_name][]" placeholder="属性名" class="input-text" size="10">
								库存 : <input type="text" name="attr[attr_value][]" class="input-text xx" size="8" value="0">&nbsp;&nbsp;&nbsp;
								价格 : <input type="text" class="J_attr_price" name="attr[price][]" class="input-text xx" size="8">
								原价	: <input type="text" name="attr[oldprice][]" class="input-text xx" size="8" value="1"> &nbsp;&nbsp;&nbsp;
								排序 : <input type="text" name="attr[ordid][]" class="input-text" size="8" value="255">
								★支付时 :
								<input type="text" name="attr[acer][]" class="input-text" size="4" value="0">元宝加
								<input type="text" name="attr[coin][]" class="input-text" size="4" value="0">银币

								&nbsp;&nbsp;<span style="color:red">价格必填</span>

							</td>
						</tr>
						</tbody>
					</table>
				</div>
				<!--附加属性:多属性设置-->
			</div>
			<div class="mt10"><input type="submit" value="<?php echo L('submit');?>" id="dosubmit" name="dosubmit" class="btn btn_submit" style="margin:0 0 10px 100px;"><br /><br /><br /></div>
		</div>
	</div>
	<input type="hidden" name="id" value="<?php echo ($info['id']); ?>">
</form>
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
<!--上传图片css-->
<style type="text/css">
	.progress{position:relative;padding: 1px; border-radius:3px; margin:30px 0 0 0;}
	.bar{background-color: green; display:block; width:0%; height:20px; border-radius:3px;}
	.percent{position:absolute; height:20px; display:inline-block;top:3px; left:2%; color:#fff}
	.progress{
		height: 100px;
		padding: 30px 0 0;
		width:100px;
		border-radius: 0;
	}

	.photos_area .item {
		float: left;
		margin: 0 10px 10px 0;
		position: relative;
	}
	.photos_area .item{position: relative;float:left;margin:0 10px 10px 0;}
	.photos_area .item img{border: 1px solid #cdcdcd;}
	.photos_area .operate{background: rgba(33, 33, 33, 0.7) none repeat scroll 0 0; bottom: 0; padding:5px 0; left: 0; position: absolute; width: 102px; z-index: 5; line-height: 21px; text-align: center;}
	.photos_area .operate i{cursor: pointer; display: inline-block; font-size: 0; height: 12px; line-height: 0; margin: 0 5px; overflow: hidden; width: 12px; background: url("Plugins/plupload/icon_sucaihuo.png") no-repeat scroll 0 0;}
	.photos_area .operate .toright{background-position: -13px -13px; position: relative;top:1px;}
	.photos_area .operate .toleft{background-position: 0 -13px; position: relative;top:1px;}
	.photos_area .operate .del{background-position: -13px 0; position: relative;top:0px;}
	.photos_area .preview{background-color: #fff; font-family: arial; line-height: 90px; text-align: center; z-index: 4; left: 0; position: absolute; top: 0; height: 90px; overflow: hidden; width: 90px;}

	.vad { margin: 120px 0 5px; font-family: Consolas,arial,宋体,sans-serif; text-align:center;}
	.vad a { display: inline-block; height: 36px; line-height: 36px; margin: 0 5px; padding: 0 50px; font-size: 14px; text-align:center; color:#eee; text-decoration: none; background-color: #222;}
	.vad a:hover { color: #fff; background-color: #000;}
	.thead { width: 728px; height: 90px; margin: 0 auto; border-bottom: 40px solid #fff;}
</style>
<script src="/theme/admin/js/jquery/plugins/colorpicker.js"></script>
<script src="/theme/admin/js/kindeditor/kindeditor.js"></script>
<script  src="/theme/admin/js/plupload/plupload.full.min.js"></script>

<!--<script src="/Plugins/plupload/plupload.full.min.js"></script>&lt;!&ndash;上传图片插件&ndash;&gt;-->
<script>
    $('.is_fruit').live('click', function() {
		var aaa=$(this);
        var data={id:aaa.data('id'),val:aaa.attr('data-val')};
		$.get('<?php echo U('item/ajax_is_fruit');?>',data,function(res){
			if(res.status==1){
				aaa.attr('data-val',res.val);
                aaa.val(res.html);
			}else{
				$.pinphp.tip({content:'系统繁忙', icon:'alert'});
			}
		},'JSON')
        return false;
    })
</script>
<script type="text/javascript">
    //切换div
    $(function(){
        $('ul.J_tabs').tabs('div.J_panes > div');
    })
	//分类联动
    $('.J_cate_select').cate_select('请选择');

    //表单提交
    // $(function(){
    //     //异步提交表单
    //     $("#info_form").on("click",function(){
    //         console.log($(this));
    //    if(!$('#J_price').val()){
    // 	   $.pinphp.tip({content:'价格不能为空', icon:'alert'});
    // 	   return false;
    //   }
    //
    //         $("#formToUpdate").ajaxSubmit({
    //             type:'post',
    //             url:'',
    //             success:function(data){
    //                 console.log(data);
    //             },
    //             error:function(XmlHttpRequest,textStatus,errorThrown){
    //                 console.log(XmlHttpRequest);
    //                 console.log(textStatus);
    //                 console.log(errorThrown);
    //             }
    //         });
    //     });
    // });


    //商品属性
    function add_attr_add()
    {
        var html='<tr class="zzz"><td align="left"><a href="javascript:void(0);" class="blue" onclick="del_attrs_add(this);"><img src="theme/admin/css/bgimg/tv-collapsable.gif" /></a>'
            +'商品属性: <input type="text" name="attr[attr_name][]" placeholder="属性名" class="input-text" size="10">'
            +'库存 : <input type="text" name="attr[attr_value][]" class="input-text xx" size="8" value="0">&nbsp;&nbsp;&nbsp;'
            + '价格 : <input type="text" class="J_attr_price" name="attr[price][]" class="input-text xx" size="8">'
            +'原价	: <input type="text" name="attr[oldprice][]" class="input-text xx" size="8" value="1"> &nbsp;&nbsp;&nbsp;'
            +'排序 : <input type="text" name="attr[ordid][]" class="input-text" size="8" value="255">   &nbsp;&nbsp;'
			+'★支付时 : <input type="text" name="attr[acer][]" class="input-text" size="4" value="0">元宝加'
        	+'<input type="text" name="attr[coin][]" class="input-text" size="4" value="0">银币';


        $(".add_item_attr").append(html);
    }

    //删除新增的
    function del_attrs_add(obj){
        $(obj).parent().remove();
    }

    //ajax删除属性
    function del_attr(id,obj)
    {
        if($('.shuxing').length==1){
            $.pinphp.tip({content:'请至少保留一条', icon:'alert'});
            return false;
        }
        var url = "<?php echo U('item/delete_attr');?>";
        var item_id="<?php echo ($info['id']); ?>";
        $.get(url+"&attr_id="+id+"&item_id="+item_id, function(data){
            // alert(data);return false;
            if(data.status==1){
                $(obj).parent().parent().remove();
            }else{
                $.pinphp.tip({content:'删除失败', icon:'error'});
            }
        });

    }
     //修改属性的单个字段
    $('span[data-tdtype="edit"]').live('click', function() {
        var s_val   = $(this).text(),
            s_name  = $(this).attr('data-field'),
            s_id    = $(this).attr('data-id'),
            width   = $(this).width();
        $('<input type="text" class="lt_input_text" value="'+s_val+'" />').width(width).focusout(function(){
            if(s_name=='full'||s_name=='miu'){
                var aa=parseInt($(this).val());
                if(aa<0){
                    $.pinphp.tip({content:'请输入正确的数据', icon:'alert'});
                    return false;
                }
            }

            $(this).prev('span').show().text($(this).val());
            if($(this).val() != s_val) {
                $.getJSON("<?php echo U('item/ajax_edit_attr');?>", {id:s_id, field:s_name, val:$(this).val()}, function(result){
                    if(result.status == 0) {
                        $.pinphp.tip({content:result.msg, icon:'error'});
                        $('span[data-field="'+s_name+'"][data-id="'+s_id+'"]').text(s_val);
                        return;
                    }
                });
            }
            $(this).remove();
        }).insertAfter($(this)).focus().select();
        $(this).hide();
        return false;
    });




    //上传图片
    $(function() {
        var uploader = new plupload.Uploader({
            runtimes: 'gears,html5,html4,silverlight,flash',
            browse_button: 'logo_upload_btn',
            url: "<?php echo U('Item/ajax_upload_img');?>",
            flash_swf_url: 'plupload/Moxie.swf',
            silverlight_xap_url: 'plupload/Moxie.xap',
            filters: {
                max_file_size: '25mb',
                mime_types: [
                    {title: "files", extensions: "jpg,png,gif,jpeg"}
                ]
            },
            multi_selection: true,
            init: {
                FilesAdded: function(up, files) {
                    $("#btn_submit").attr("disabled", "disabled").addClass("disabled").val("正在上传...");
                    var item = '';
                    plupload.each(files, function(file) { //遍历文件
                        item += "<div class='item' id='" + file['id'] + "'><div class='progress'><span class='bar'></span><span class='percent'>0%</span></div></div>";
                    });
                    $("#photos_area").append(item);
                    uploader.start();
                },

                UploadProgress: function(up, file) { //上传中，显示进度条
                    var percent = file.percent;
                    $("#" + file.id).find('.bar').css({"width": percent + "%"});
                    $("#" + file.id).find(".percent").text(percent + "%");
                },

                FileUploaded: function(up, file, info) {
                    var data = eval("(" + info.response + ")");
                    var datasrc=data.src;
                    data.src='data/attachment/item/'+datasrc;

                    $("#" + file.id).html("<input type=hidden name='imgs[]' value='"+datasrc+"'><img src='"+data.src+"' alt='"+data.name+"' width='100px' height='100px'>\n\
	<div class='operate'><i class='toleft'>左移</i><i class='toright'>右移</i><i class='del'>删除</i></div>")

                    $("#btn_submit").removeAttr("disabled").removeClass("disabled").val("提 交");
                    if (data.error != 0) {
                        $.pinphp.tip({content:data.error, icon:'alert'});
                    }
                },
                Error: function(up, err) {
                    if (err.code == -601) {
                        $.pinphp.tip({content:"请上传jpg,png,gif,jpeg,zip或rar！", icon:'alert'});
                        $("#btn_submit").removeAttr("disabled").removeClass("disabled").val("提 交");
                    }
                }
            }
        });
        uploader.init();
        //左右切换和删除图片
        $(document).delegate(".toleft","click", function() {
            var item = $(this).parent().parent(".item");
            var item_left = item.prev(".item");
            if ($("#photos_area").children(".item").length >= 2) {
                if (item_left.length == 0) {
                    item.insertAfter($("#photos_area").children(".item:last"));
                } else {
                    item.insertBefore(item_left);
                }
            }
        })
        $(document).delegate(".toright","click", function() {
            var item = $(this).parent().parent(".item");
            var item_right = item.next(".item");
            if ($("#photos_area").children(".item").length >= 2) {
                if (item_right.length == 0) {
                    item.insertBefore($("#photos_area").children(".item:first"));
                } else {
                    item.insertAfter(item_right);
                }
            }
        })
        // $(document).delegate(".del","click", function() {
        //     $(this).parent().parent(".item").remove();
        // })
        $(document).delegate(".del","click", function() {
            var self = $(this),
                id = self.data('id');
            if(id){
                $.get("<?php echo U('Item/del_imgs');?>",{id:id},function (result) {
                    if(result == 1){
                        self.parent().parent(".item").remove();
                    }else{
                        alert('操作失败，请重试');
                    }
                })
            }else{
                self.parent().parent(".item").remove();
            }
        })

        KindEditor.create('.info', {
            uploadJson : '<?php echo U("attachment/editer_upload");?>',
            fileManagerJson : '<?php echo U("attachment/editer_manager");?>',
            allowFileManager : true
        });

    });
</script>

</body>
</html>