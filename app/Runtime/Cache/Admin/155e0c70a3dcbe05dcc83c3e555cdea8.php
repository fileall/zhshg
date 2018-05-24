<?php if (!defined('THINK_PATH')) exit();?><!--编辑银行卡头像-->
<div class="dialog_content">
    <form id="info_form" action="<?php echo u('BankImg/add');?>" method="post" enctype="multipart/form-data">
        <table width="100%" class="table_form">
            <tr>
                <th>银行名:</th>
                <td><input type="text" name="name" id="name" class="input-text" value="<?php echo ($info["name"]); ?>" size="30"></td>
            </tr>

            <tr>
                <th>logo：</th>
                <td>
                    <input type="file" name="img" />
                </td>
            </tr>
        </table>
        <input type="hidden" name="id" value="<?php echo ($info["id"]); ?>" />
    </form>
</div>
<script src="/theme/admin/js/fileuploader.js"></script>
<script>
    $(function(){
        $.formValidator.initConfig({formid:"info_form",autotip:true});
        $("#name").formValidator({onshow:lang.please_input+lang.article_cate_name,onfocus:lang.please_input+lang.article_cate_name}).inputValidator({min:1,onerror:lang.please_input+lang.article_cate_name}).defaultPassed();

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