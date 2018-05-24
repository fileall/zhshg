<?php if (!defined('THINK_PATH')) exit();?><!--添加商圈-->
<div class="dialog_content">
    <form id="info_form" action="<?php echo U('service/coupon');?>" method="post">
        <input type="hidden" name="uid" value="<?php echo ($uid); ?>"/>
        <table width="100%" cellpadding="2" cellspacing="1" class="table_form">
            <tr>
                <th  width="120">面额：</th>
                <td><input type="text" name="face_value" id="face_value" class="input-text" size="20" value=""></td>
            </tr>

            <tr >
                <th>数量：</th>
                <td >
                    <input type="text" name="nums" id="nums" class="input-text" size="20" value="">
                </td>
            </tr>

            <th>选择数量:</th>
            <td>
                <label><input  class="radioItem" type="radio" name="s" value="10" > 10</label>
                <label><input class="radioItem"type="radio" name="s" value="20" > 20</label>
                <label><input class="radioItem"type="radio" name="s" value="30" > 30</label>
            </td>
          <tr>
              <th>备注</th>
              <td>
                  <input type="text" name="memos" id="memos" maxlength="30" class="input-text"  placeholder="最多30个字" size="30" value=""></td>
              </td>
          </tr>

        </table>
    </form>
</div>
<script>
    $(function(){
        $.formValidator.initConfig({formid:"info_form",autotip:true});
        $("#face_value").formValidator({ onshow: "请输入整数", oncorrect: "正确" }).regexValidator({ regexp: "intege", datatype: "enum", onerror: "格式不正确" });
        $("#nums").formValidator({ onshow: "请输入整数", oncorrect: "正确" }).regexValidator({ regexp: "intege", datatype: "enum", onerror: "格式不正确" });
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
        $(".radioItem").change(function () {
            var _txt = $("input[name='s']:checked").val();//alert(_txt);
            $("#nums").val(_txt);

        });
    })

</script>