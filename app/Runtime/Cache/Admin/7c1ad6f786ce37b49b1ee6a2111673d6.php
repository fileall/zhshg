<?php if (!defined('THINK_PATH')) exit();?><!--编辑管理员-->
<div class="dialog_content">
	<form id="info_form" action="<?php echo U('admin/edit');?>" method="post">
	<table width="100%" class="table_form">
		<tr>
      		<th width="90"><?php echo L('admin_username');?> :</th>
      		<td><input type="text" name="username" id="J_username" class="input-text" value="<?php echo ($info["username"]); ?>"></td>
    	</tr>
    	<tr> 
      		<th><?php echo L('password');?> :</th>
      		<td><input type="password" name="password" id="J_password" class="input-text"></td>
    	</tr>
    	<tr>
      		<th><?php echo L('cofirmpwd');?> :</th>
      		<td><input type="password" name="repassword" id="J_repassword" class="input-text"></td>
    	</tr>
      <tr>
        <th><?php echo L('admin_email');?> :</th>
        <td><input type="text" name="email" class="input-text" value="<?php echo ($info["email"]); ?>" size="30"></td>
      </tr>
		<?php if($info["id"] != 1): ?><tr>
      		<th><?php echo L('admininrole');?> :</th>
      		<td>
      			<select name="role_id">
					<option value="0">请选择</option>
        			<?php if(is_array($role_list)): $i = 0; $__LIST__ = $role_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>" <?php if($val['id'] == $info['role_id']): ?>selected="selected"<?php endif; ?>><?php echo ($val["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
        		</select>
      		</td>
    	</tr><?php endif; ?>

		<!--<tr>
			<th>区域名称 :</th>
			<td>
				<input type="text" name="area_name" id="area_name" class="input-text" value="<?php echo ($info["area_name"]); ?>">
			</td>
		</tr>
    	<?php if($info["id"] != 1): ?><tr>
		      <th>所属区域 :</th>
		      <td>
		      	<select name="city_id">
					<option value="0">请选择</option>
		        	<?php if(is_array($area_list)): $i = 0; $__LIST__ = $area_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option <?php if($val['id'] == $info['ppid'] or $val['id'] == $info['pid']): ?>selected="selected"<?php endif; ?> value="<?php echo ($val["id"]); ?>"><?php echo ($val["area_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
		        </select>
		      </td>
		    </tr>
		<?php else: ?>
		    <input type="hidden" name="city_id" class="input-text" value="<?php echo ($city_id); ?>"><?php endif; ?>-->
	</table>
	<input type="hidden" name="id" value="<?php echo ($info["id"]); ?>" />
	</form>
</div>

<script>
var check_name_url = "<?php echo U('admin/ajax_check_name', array('id'=>$info['id']));?>";
$(function(){
	$.formValidator.initConfig({formid:"info_form",autotip:true});

	$("#J_username").formValidator({ onshow:lang.please_input+lang.admin_username, onfocus:lang.please_input+lang.admin_username}).inputValidator({ min:1, onerror:lang.please_input+lang.admin_username}).ajaxValidator({type:"get", url:check_name_url, datatype:"html", async:'false', success:function(data){	if( data == "1" ){return true;}else{return false;}}, onerror:lang.admin_name_exists, onwait:lang.connecting_please_wait}).defaultPassed();
	$("#J_password").formValidator({ empty:true, onshow:lang.not_edit_password, onfocus:lang.password+lang.between_6_to_20}).inputValidator({ min:6, max:20, onerror:lang.password+lang.between_6_to_20});
	$("#J_repassword").formValidator({ empty:true, onshow:lang.not_edit_password, onfocus:lang.cofirmpwd}).compareValidator({desid:"J_password",operateor:"=",onerror:lang.passwords_not_match});

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
});
</script>