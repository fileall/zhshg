<?php if (!defined('THINK_PATH')) exit();?><!--添加管理员-->
<div class="dialog_content">
	<form id="info_form" name="info_form" action="<?php echo u('admin/add');?>" method="post">
	<table width="100%" class="table_form">
		<tr> 
	      <th width="90"><?php echo L('admin_username');?> :</th>
	      <td><input type="text" name="username" id="username" class="input-text"></td>
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
	    	<td><input type="text" name="email" class="input-text" size="30"></td>
	    </tr>
	    <tr>
	      <th><?php echo L('admininrole');?> :</th>
	      <td>
	      	<select name="role_id" id="role_id">
				<option value="0">请选择</option>
	        	<?php if(is_array($role_list)): $i = 0; $__LIST__ = $role_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>"><?php echo ($val["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
	        </select>
	      </td>
	    </tr>

	<!--	<tr>
			<th>区域名称 :</th>
			<td>
				<input type="text" name="area_name" id="area_name" class="input-text">
			</td>
		</tr>-->

		<!--<tr>
			<th>所属区域 :</th>
			<td>
				<select name="city_id" id="city_id">
					<option value="0">请选择</option>
				</select>
			</td>
		</tr>-->

	</table>
	</form>
</div>
<script>
    var check_name_url = "<?php echo U('admin/ajax_check_name');?>";
    var check_area_name_url = "<?php echo U('admin/ajax_check_area_name');?>";
    var check_role_url = "<?php echo U('admin/ajax_role');?>";
$(function(){
	$.formValidator.initConfig({formid:"info_form",autotip:true});
	
	$("#username").formValidator({ onshow:lang.please_input.admin_username, onfocus:lang.please_input.admin_username}).inputValidator({ min:1, onerror:lang.please_input+lang.admin_username}).ajaxValidator({
		type:"get",
		url:check_name_url,
        datatype : "json",
        async:'false',
		success:function(data){
		    if("1" == data){
		        return true;
		    }else{
		        return false;
		    }
		}, onerror:lang.admin_name_exists, onwait:lang.connecting_please_wait});
	$("#J_password").formValidator({ onshow:lang.please_input.password, onfocus:lang.password+lang.between_6_to_20}).inputValidator({ min:6, max:20, onerror:lang.password+lang.between_6_to_20});
	$("#J_repassword").formValidator({ onshow:lang.cofirmpwd, onfocus:lang.cofirmpwd}).compareValidator({desid:"J_password",operateor:"=",onerror:lang.passwords_not_match});
    $("#role_id").formValidator({ onshow:lang.please_input.admininrole, onfocus:lang.please_input.admininrole}).inputValidator({ min:1, onerror:lang.please_input+lang.admininrole}).ajaxValidator({
        type:"get",
        url:check_role_url,
        datatype : "json",
        async:'false',
        success:function(result){
            if(result.data){
                $("#city_id option").remove();
                $("#city_id").prepend("<option value='0'>请选择</option>");
                if (result.data != null){
                    for (var i = 0;i < result.data.length;i ++){
                        $("#city_id").append("<option value='"+result.data[i]['id']+"'>"+result.data[i]['area_name']+"</option>")
                    }
				}

            }
            if ("1" != result.status){
                return false;
			}
            return true;
        },onerror:lang.admin_name_exists, onwait:lang.connecting_please_wait});

/*    $("#area_name").formValidator({ onshow:lang.please_input+lang.area_name, onfocus:lang.please_input+lang.area_name}).inputValidator({ min:1, onerror:lang.please_input+lang.area_name}).ajaxValidator({
        type:"get",
        url:check_area_name_url,
        datatype : "json",
        async:'false',
        success:function(data){
            if("1" == data){
                return true;
            }else{
                return false;
            }
        }, onerror:lang.admin_name_exists, onwait:lang.connecting_please_wait});*/
	$('#info_form').ajaxForm({success:complate,dataType:'json'});
    function complate(result){
        if(1 == result.status){
            $.dialog.get(result.dialog).close();  
            $.pinphp.tip({content:result.msg});
            window.location.reload();
        } else {
            $.pinphp.tip({content:result.msg, icon:'alert'});
        }
    }
});
</script>