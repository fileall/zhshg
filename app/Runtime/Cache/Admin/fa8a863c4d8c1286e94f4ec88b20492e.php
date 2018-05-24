<?php if (!defined('THINK_PATH')) exit();?><!--编辑栏目-->
<div class="dialog_content">
	<form id="info_form" action="<?php echo u('item_cate/edit');?>" method="post">
	<table width="100%" class="table_form">
		<tr>
			<th width="100"><?php echo L('item_cate_parent');?> :</th>
			<td>
				<select class="J_cate_select mr10" data-pid="0" data-uri="<?php echo U('item_cate/ajax_getchilds');?>" data-selected="<?php echo ($info["spid"]); ?>"></select>
				<input type="hidden" name="pid" id="J_cate_id" />
			</td>
		</tr>
		<tr>
			<th><?php echo L('item_cate_name');?> :</th>
			<td>
				<input type="text" name="name" id="J_name" class="input-text" value="<?php echo ($info["name"]); ?>" style="color:<?php echo ($info["fcolor"]); ?>" size="30">
		        <input type="hidden" value="" name="fcolor" id="J_color">
		        <a href="javascript:;" class="color_picker_btn"><img class="J_color_picker" data-it="J_name" data-ic="J_color" src="/theme/admin//images/color.png"></a>
			</td>
		</tr>
		 <!--<tr>
            <th>图片链接 :</th>
            <td><input type="text" name="lian" id="lian" class="input-text" size="30" value="<?php echo ($info["lian"]); ?>"></td>
        </tr>-->
	    <tr>
			<th>分类图片 :</th>
			<td>
			    <input type="text" name="img" id="J_img" class="input-text fl mr10" size="30" value="<?php echo ($info["img"]); ?>">
            	<div id="J_upload_img" class="upload_btn"><span><?php echo L('upload');?></span></div>
			    <?php if(!empty($info['img'])): ?><span class="attachment_icon J_attachment_icon" file-type="image" file-rel="<?php echo attach($info['img'], 'item_cate');?>"><img src="/theme/admin//images/filetype/image_s.gif" /></span><?php endif; ?></td>
		</tr>
		<!--<tr>
			<th>Wap首页小图标 :</th>
			<td>
			    <input type="text" name="home_img" id="J_img1" class="input-text fl mr10" size="30" value="<?php echo ($info["home_img"]); ?>">
            	<div id="J_upload_img1" class="upload_btn"><span><?php echo L('upload');?></span></div>
			    <?php if(!empty($info['home_img'])): ?><span class="attachment_icon J_attachment_icon" file-type="image" file-rel="<?php echo attach($info['home_img'], 'item_cate');?>"><img src="/theme/admin//images/filetype/image_s.gif" /></span><?php endif; ?></td>
		</tr>
		 <tr>
			<th>Pc分类图片 :</th>
			<td>
			    <input type="text" name="pc_img" id="J_img2" class="input-text fl mr10" size="30" value="<?php echo ($info["pc_img"]); ?>">
            	<div id="J_upload_img2" class="upload_btn"><span><?php echo L('upload');?></span></div>
			    <?php if(!empty($info['pc_img'])): ?><span class="attachment_icon J_attachment_icon" file-type="image" file-rel="<?php echo attach($info['pc_img'], 'item_cate');?>"><img src="/theme/admin//images/filetype/image_s.gif" /></span><?php endif; ?>
			</td>
		</tr>
		 <tr>
			<th>Pc首页小图标 :</th>
			<td>
			    <input type="text" name="pc_home_img" id="J_img3" class="input-text fl mr10" size="30" value="<?php echo ($info["pc_home_img"]); ?>">
            	<div id="J_upload_img3" class="upload_btn"><span><?php echo L('upload');?></span></div>
			    <?php if(!empty($info['pc_home_img'])): ?><span class="attachment_icon J_attachment_icon" file-type="image" file-rel="<?php echo attach($info['pc_home_img'], 'item_cate');?>"><img src="/theme/admin//images/filetype/image_s.gif" /></span><?php endif; ?>
			</td>
		</tr>-->
		
	    <tr>
			<th>审核状态 :</th>
			<td>
				<label><input type="radio" name="status" value="0" <?php if($info["status"] == 0): ?>checked<?php endif; ?>> 未审核</label>&nbsp;&nbsp;
				<label><input type="radio" name="status" value="1" <?php if($info["status"] == 1): ?>checked<?php endif; ?>> 已审核</label>
			</td>
		</tr>
		
		<tr>
			<th>首页显示 :</th>
			<td>
				<label><input type="radio" name="is_home" value="0" <?php if($info["is_home"] == 0): ?>checked<?php endif; ?>> 不显示</label>&nbsp;&nbsp;
				<label><input type="radio" name="is_home" value="1" <?php if($info["is_home"] == 1): ?>checked<?php endif; ?>> 显示</label>
			</td>
		</tr>
		<tr>
			<th><?php echo L('seo_title');?> :</th>
			<td><input type="text" name="seo_title" class="input-text" value="<?php echo ($info["seo_title"]); ?>" style="width:300px;"></td>
		</tr>
		<tr>
			<th><?php echo L('seo_keys');?> :</th>
			<td><input type="text" name="seo_keys" class="input-text" value="<?php echo ($info["seo_keys"]); ?>" style="width:300px;"></td>
		</tr>
		<tr>
			<th><?php echo L('seo_desc');?> :</th>
			<td><textarea name="seo_desc" style="width:295px; height:50px;"><?php echo ($info["seo_desc"]); ?></textarea></td>
		</tr>
	</table>
	<input type="hidden" name="id" value="<?php echo ($info["id"]); ?>" />
</form>
</div>
<script src="/theme/admin//js/jquery/plugins/colorpicker.js"></script>
<script src="/theme/admin//js/fileuploader.js"></script>
<script>
var check_name_url = "<?php echo U('item_cate/ajax_check_name', array('id'=>$info['id']));?>";
$(function(){
	$.formValidator.initConfig({formid:"info_form",autotip:true});
	$("#J_name").formValidator({onshow:lang.please_input+lang.item_cate_name,onfocus:lang.please_input+lang.item_cate_name}).inputValidator({min:1,onerror:lang.please_input+lang.item_cate_name}).defaultPassed();
	
	$('#info_form').ajaxForm({success:complate,dataType:'json'});
	function complate(result){
		if(result.status == 1){
			$.dialog.get(result.dialog).close();
            $.pinphp.tip({content:result.msg});
            window.location.reload();
		} else {
			$.pinphp.tip({content:result.msg, icon:'alert'});
		}
	}
	$('.J_cate_select').cate_select({top_option:'选择分类'});
	
	//颜色选择器
	$('.J_color_picker').colorpicker();
	
	//上传图片
    var uploader = new qq.FileUploaderBasic({
    	allowedExtensions: ['jpg','gif','jpeg','png','bmp','pdg'],
        button: document.getElementById('J_upload_img'),
        multiple: false,
        action: "<?php echo U('item_cate/ajax_upload_img');?>",
        inputName: 'img',
        forceMultipart: true,
        messages: {
        	typeError: lang.upload_type_error,
        	sizeError: lang.upload_size_error,
        	minSizeError: lang.upload_minsize_error,
        	emptyError: lang.upload_empty_error,
        	noFilesError: lang.upload_nofile_error,
        	onLeave: lang.upload_onLeave
        },
        showMessage: function(message){
        	$.pinphp.tip({content:message, icon:'error'});
        },
        onSubmit: function(id, fileName){
        	$('#J_upload_img').addClass('btn_disabled').find('span').text(lang.uploading);
        },
        onComplete: function(id, fileName, result){
        	$('#J_upload_img').removeClass('btn_disabled').find('span').text(lang.upload);
            if(result.status == '1'){
        		$('#J_img').val(result.data);
        	} else {
        		$.pinphp.tip({content:result.msg, icon:'error'});
        	}
        }
    });
	
	/*//上传图片2
    var uploader = new qq.FileUploaderBasic({
    	allowedExtensions: ['jpg','gif','jpeg','png','bmp','pdg'],
        button: document.getElementById('J_upload_img1'),
        multiple: false,
        action: "<?php echo U('item_cate/ajax_upload_img');?>",
        inputName: 'img',
        forceMultipart: true,
        messages: {
        	typeError: lang.upload_type_error,
        	sizeError: lang.upload_size_error,
        	minSizeError: lang.upload_minsize_error,
        	emptyError: lang.upload_empty_error,
        	noFilesError: lang.upload_nofile_error,
        	onLeave: lang.upload_onLeave
        },
        showMessage: function(message){
        	$.pinphp.tip({content:message, icon:'error'});
        },
        onSubmit: function(id, fileName){
        	$('#J_upload_img1').addClass('btn_disabled').find('span').text(lang.uploading);
        },
        onComplete: function(id, fileName, result){
        	$('#J_upload_img1').removeClass('btn_disabled').find('span').text(lang.upload);
            if(result.status == '1'){
        		$('#J_img1').val(result.data);
        	} else {
        		$.pinphp.tip({content:result.msg, icon:'error'});
        	}
        }
    });
    
      //上传图片3
    var uploader = new qq.FileUploaderBasic({
    	allowedExtensions: ['jpg','gif','jpeg','png','bmp','pdg'],
        button: document.getElementById('J_upload_img2'),
        multiple: false,
        action: "<?php echo U('item_cate/ajax_upload_img');?>",
        inputName: 'img',
        forceMultipart: true, //用$_FILES
        messages: {
        	typeError: lang.upload_type_error,
        	sizeError: lang.upload_size_error,
        	minSizeError: lang.upload_minsize_error,
        	emptyError: lang.upload_empty_error,
        	noFilesError: lang.upload_nofile_error,
        	onLeave: lang.upload_onLeave
        },
        showMessage: function(message){
        	$.pinphp.tip({content:message, icon:'error'});
        },
        onSubmit: function(id, fileName){
        	$('#J_upload_img2').addClass('btn_disabled').find('span').text(lang.uploading);
        },
        onComplete: function(id, fileName, result){
        	$('#J_upload_img2').removeClass('btn_disabled').find('span').text(lang.upload);
            if(result.status == '1'){
        		$('#J_img2').val(result.data);
        	} else {
        		$.pinphp.tip({content:result.msg, icon:'error'});
        	}
        }
    });
    
     //上传图片4
    var uploader = new qq.FileUploaderBasic({
    	allowedExtensions: ['jpg','gif','jpeg','png','bmp','pdg'],
        button: document.getElementById('J_upload_img3'),
        multiple: false,
        action: "<?php echo U('item_cate/ajax_upload_img');?>",
        inputName: 'img',
        forceMultipart: true, //用$_FILES
        messages: {
        	typeError: lang.upload_type_error,
        	sizeError: lang.upload_size_error,
        	minSizeError: lang.upload_minsize_error,
        	emptyError: lang.upload_empty_error,
        	noFilesError: lang.upload_nofile_error,
        	onLeave: lang.upload_onLeave
        },
        showMessage: function(message){
        	$.pinphp.tip({content:message, icon:'error'});
        },
        onSubmit: function(id, fileName){
        	$('#J_upload_img3').addClass('btn_disabled').find('span').text(lang.uploading);
        },
        onComplete: function(id, fileName, result){
        	$('#J_upload_img3').removeClass('btn_disabled').find('span').text(lang.upload);
            if(result.status == '1'){
        		$('#J_img3').val(result.data);
        	} else {
        		$.pinphp.tip({content:result.msg, icon:'error'});
        	}
        }
    });*/
});
</script>