<?php if (!defined('THINK_PATH')) exit();?><!--添加角色-->
<div class="dialog_content">
    <form id="info_form" name="info_form" action="<?php echo u('admin_role/add');?>" method="post">
    <table width="100%" class="table_form">
    <tr>
							<th>所属部门 :</th>
							<td>
								<select name="d_id" style="width: 10rem;">
									<?php if(is_array($d_list)): $i = 0; $__LIST__ = $d_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>"><?php echo ($val["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
								</select>
							</td>
						</tr>
						<!--<tr>
							<th>所属职位 :</th>
							<td>
								<select name="role_id" style="width: 10rem;" >
									<?php if(is_array($role_list)): $i = 0; $__LIST__ = $role_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>"><?php echo ($val["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
								</select>
							</td>
						</tr>-->
						<tr>
							<th>性别 :</th>
							<td>
								<select name="sex" style="width: 10rem;">
									<option value="1">男</option>
									<option value="2">女</option>
								</select>
							</td>
						</tr>
						<tr>
							<th width="80"><span style="color: red;">*</span>账号 :</th>
							<td><input type="text" name="username" class="input-text" size="30"></td>
						</tr>
						<tr>
							<th><span style="color: red;">*</span><?php echo L('password');?> :</th>
							<td><input type="password" name="password" class="input-text" size="30"></td>
						</tr>
						<tr>
							<th><span style="color: red;">*</span><?php echo L('cofirmpwd');?> :</th>
							<td><input type="password" name="repassword" class="input-text" size="30"></td>
						</tr>
						<tr>
							<th>姓名 :</th>
							<td><input type="text" name="name" class="input-text" size="30"></td>
						</tr>
						<tr>
							<th>手机 :</th>
							<td><input type="text" name="mobile" class="input-text" size="30"></td>
						</tr>
						<tr>
							<th><?php echo L('admin_email');?> :</th>
							<td><input type="text" name="email" class="input-text" size="30"></td>
						</tr>
						<!--<tr>
							<th>生日 :</th>
							<td><input type="text" name="birthday" id="birthday" class="date" size="30"></td>
						</tr>-->
       <!-- <tr>
            <th width="80"><?php echo L('role_name');?> :</th>
            <td><input type="text" name="name" id="name" class="input-text"></td>
        </tr>
        <tr>
            <th><?php echo L('role_desc');?> :</th>
            <td><textarea name="remark" id="remark" cols="40" rows="3"></textarea></td>
        </tr>-->
        <tr>
            <th><?php echo L('enabled');?> :</th>
            <td>
                <input type="radio" name="status" class="radio_style" value="1" checked="checked"> &nbsp;<?php echo L('yes');?>&nbsp;&nbsp;&nbsp;
                <input type="radio" name="status" class="radio_style" value="0"> &nbsp;<?php echo L('no');?>
            </td>
        </tr>
    </table>
    </form>
</div>
<script>
$(function(){
    $.formValidator.initConfig({formid:"info_form",autotip:true});
    $("#name").formValidator({ onshow:lang.please_input+lang.role_name, onfocus:lang.please_input+lang.role_name, oncorrect:lang.input_right}).inputValidator({ min:1, onerror:lang.please_input+lang.role_name});

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