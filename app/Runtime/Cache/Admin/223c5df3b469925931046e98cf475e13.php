<?php if (!defined('THINK_PATH')) exit();?><!--编辑菜单-->
<div class="dialog_content">
    <form id="info_form" name="info_form" action="<?php echo U('menu/edit');?>" method="post">
    <table width="100%" class="table_form">
        <tr>
            <th width="100"><?php echo L('menu_parentid');?> :</th>
            <td>
            <select name="pid">
                <option value="0"><?php echo L('no_parent_menu');?></option>
                <?php echo ($select_menus); ?>
            </select>
          </td>
        </tr>
        <tr>
        <th><?php echo L('menu_name');?> :</th>
            <td><input type="text" name="name" id="name" class="input-text" value="<?php echo ($info["name"]); ?>"></td>
        </tr>
        <tr>
            <th><?php echo L('module_name');?> :</th>
            <td><input type="text" name="controller_name" id="controller_name" class="input-text" value="<?php echo ($info["controller_name"]); ?>"></td>
        </tr>
        <tr>
            <th><?php echo L('action_name');?> :</th>
            <td><input type="text" name="action_name" id="action_name" class="input-text" value="<?php echo ($info["action_name"]); ?>"></td>
        </tr>
        <tr> 
            <th><?php echo L('att_data');?> :</th>
            <td><input type="text" name="data" id="data" class="input-text" value="<?php echo ($info["data"]); ?>"></td>
        </tr>
        <tr>
            <th><?php echo L('remark');?> :</th>
            <td><textarea name="remark" id="remark" cols="40" rows="3"><?php echo ($info["remark"]); ?></textarea></td>
        </tr>
        <tr>
            <th><?php echo L('sort_order');?> :</th>
            <td><input type="text" name="ordid" id="ordid" class="input-text" value="<?php echo ($info["ordid"]); ?>"></td>
        </tr>
        <tr>
            <th>图标样式 :</th>
            <td><input type="text" name="class" id="class" class="input-text" value="<?php echo ($info["class"]); ?>"></td>
        </tr>
        <tr>
            <th><?php echo L('menu_display');?> :</th>
            <td>
                <label><input type="radio" name="display" class="radio_style" value="1" <?php if($info["display"] == 1): ?>checked="checked"<?php endif; ?>> <?php echo L('yes');?>&nbsp;&nbsp;</label>
                <label><input type="radio" name="display" class="radio_style" value="0" <?php if($info["display"] == 0): ?>checked="checked"<?php endif; ?>> <?php echo L('no');?></label>
            </td>
        </tr>
        <tr>
            <th>常用 :</th>
            <td>
                <label><input type="radio" name="often" class="radio_style" value="1" <?php if($info["often"] == 1): ?>checked="checked"<?php endif; ?>> <?php echo L('yes');?>&nbsp;&nbsp;</label>
                <label><input type="radio" name="often" class="radio_style" value="0" <?php if($info["often"] == 0): ?>checked="checked"<?php endif; ?>> <?php echo L('no');?></label>
            </td>
        </tr>
        <tr>
            <th><?php echo L('menu_dialog');?> :</th>
            <td>
                <input type="text" name="dialog" id="dialog" class="input-text" value="<?php echo ($info["dialog"]); ?>">
                <div class="onFocus">宽|高,设置后页面以弹窗方式打开</div>
            </td>
        </tr>
    </table>
    <input name="id" type="hidden" value="<?php echo ($info["id"]); ?>">
    </form>
</div>

<script>
$(function(){
    $.formValidator.initConfig({formid:"info_form",autotip:true});

    $("#name").formValidator({ onshow:lang.please_input+lang.menu_name, onfocus:lang.please_input+lang.menu_name, oncorrect:lang.input_right}).inputValidator({ min:1, onerror:lang.please_input+lang.menu_name}).defaultPassed();
    $("#module_name").formValidator({ onshow:lang.please_input+lang.module_name, onfocus:lang.please_input+lang.module_name, oncorrect:lang.input_right}).inputValidator({ min:1, onerror:lang.please_input+lang.module_name}).defaultPassed();
    $("#action_name").formValidator({ onshow:lang.please_input+lang.action_name, onfocus:lang.please_input+lang.action_name, oncorrect:lang.input_right}).inputValidator({ min:1, onerror:lang.please_input+lang.action_name}).defaultPassed();

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