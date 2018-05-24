<?php if (!defined('THINK_PATH')) exit();?><!--添加管理员-->
<div class="dialog_content">
	<form id="info_form" name="info_form" action="<?php echo u('ItemBrand/add');?>" method="post">
	<table width="100%" class="table_form">
    	<tr>
							<th width="120">分类 :</th>
							<td><select class="J_cate_select mr10" data-pid="0" data-uri="<?php echo U('Item_cate/ajax_getchilds');?>" data-selected=""></select><input type="hidden" name="cate_id" id="J_cate_id" value="" /></td>
						</tr>
		<tr> 
	      <th width="80">名称 :</th>
	      <td><input type="text" name="name" id="J_name" size="30" class="input-text"></td>
	    </tr>
		<tr> 
	      <th>排序 :</th>
	      <td><input type="text" name="ordid" id="J_ordid" size="30" class="input-text" value="100"></td>
	    </tr>
	</table>
	</form>
</div>
<script src="/theme/admin//js/fileuploader.js"></script>
<script>
$(function(){
	
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
    
    $('.J_cate_select').cate_select('请选择');
     var uploader = new qq.FileUploaderBasic({
    	allowedExtensions: ['jpg','gif','jpeg','png','bmp','pdg'],
        button: document.getElementById('J_upload_img'),
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
    
});
</script>