<?php if (!defined('THINK_PATH')) exit();?><!--<!doctype html>-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<link href="/theme/admin/css/style.css" rel="stylesheet"/>

<title><?php echo L('website_manage');?></title>

	<script>

	var URL = '/jradmin.php/merchant';

	var SELF = '/jradmin.php?m=admin&c=merchant&a=add&menuid=418';

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
<link rel="stylesheet" href="/theme/admin/getpoint/common.css">
<link rel="stylesheet" href="/theme/admin/getpoint/index.css">
<script src="/theme/admin/getpoint/jquery-1.9.1.min.js"></script>
<link rel="stylesheet" href="/theme/admin/getpoint/jquery-ui.min.css">
<script src="/theme/admin/getpoint/jquery-ui-1.10.4.min.js"></script>
<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>

<!--添加文章-->
<form id="info_form" action="<?php echo U('Merchant/add');?>" method="post" enctype="multipart/form-data">
	<div class="pad_lr_10">
		<div class="col_tab">
			<ul class="J_tabs tab_but cu_li">
				<li class="current"><?php echo L('article_basic');?></li>
                <li>店铺选点</li>
				<li>海报</li>
			</ul>
			<div class="J_panes">
				<div class="content_list pad_10">
					<table width="100%" cellspacing="0" class="table_form">
						<tr>
							<th width="120">查询会员 :</th>
							<td><input type="text" id="search_member" class="input-text" placeholder="姓名/手机" size="30"></td>
						</tr>
							<th width="120">选择会员 :</th>
							<td>
								<select name="uid" id="user_list">
									<option value="">--请先查询会员再选择--</option>
								</select>
							</td>
						</tr>
						<tr>
							<th width="120">选择分类 :</th>
							<td><select class="J_cate_select mr10" data-pid="0" data-uri="<?php echo U('merchant_cate/ajax_getchilds');?>" data-selected="<?php echo ($cate_selected_ids); ?>"></select>
								<input type="hidden" name="cate_id" id="J_cate_id" value="<?php echo ($info["cate_id"]); ?>" /></td>
						</tr>
						<tr>
							<th>店铺名称 :</th>
							<td><input type="text" name="title" id="title" class="input-text" value="<?php echo ($info["title"]); ?>" size="30"></td>
						</tr>
						<tr>
							<th>店铺头像 :</th>
							<td><input type="file" name="img" id="img" class="input-text"  style="width:200px;" /></td>
						</tr>
						
					    <th>营业执照 :</th>
							<td>
								<input type="file"  name="yy_img" size="30" />
							</td>
						</tr>
						
						<tr>
							<th>支付类型 :</th>
							<td>
                           	    <input name="zftype[]" type="checkbox" value="1" />金元宝
								<!--<input name="zftype[]" type="checkbox" value="2" />银元宝-->
								<input name="zftype[]" type="checkbox" value="3" />金果
                            </td>
						</tr>
						<tr>
							<th width="120">让利 :</th>
							<td>
								<select name="rangli">
									<?php $__FOR_START_1656359012__=0;$__FOR_END_1656359012__=55;for($i=$__FOR_START_1656359012__;$i < $__FOR_END_1656359012__;$i+=5){ ?><option value="<?php echo ($i); ?>" <?php if($i == $info['rangli']): ?>selected="selected"<?php endif; ?>><?php echo ($i); ?>%</option><?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<th>消费返银 :</th>
							<td>
                           	    <input type="number"  min="0.0" step="0.1" name="set_coin" />倍
                           	    <!--<input type="number" min="0" name="set_coin" value="" />倍-->
							 </td>
						</tr>
						<tr>
							<th>服务电话 :</th>
							<td><input type="number" name="tel" class="input-text"  size="30"></td>
						</tr>
						<tr>
							<th width="120">营业时间 :</th>
							<td>
								<input type="text" id="J_time_start" class="input-text" size="12" name="start" value="<?php echo ($info["start"]); ?>"> -
								<input type="text" id="J_time_end" class="input-text" size="13" name="end" value="<?php echo ($info["end"]); ?>" >
							</td>
						</tr>
						<!--<tr>-->
							<!--<th>简介 :</th>-->
							<!--<td><textarea name="intro" class="intro" style="width:68%;height:100px;"><?php echo ($info["intro"]); ?></textarea></td>-->
						<!--</tr>-->
						<tr>
							<th>详情 :</th>
							<td><textarea name="info" class="info" style="width:98%;height:520px;visibility:hidden;resize:none;"><?php echo ($info["info"]); ?></textarea></td>
						</tr>
					</table>
				</div>
				 <!--店铺选点-->
				<div class="content_list pad_10 ">
					<div>
						区域 :
						<select class="J_place_select mr10" data-pid="0" data-uri="<?php echo U('place/ajax_getchilds', array('type'=>0));?>" data-selected="<?php echo ($place_selected_ids); ?>"></select>
						<input type="hidden" name="province_id" id="aaaa" value="0" >
						<input type="hidden" name="city_id" id="bbbb" value="0" >
						<input type="hidden" name="district_id" id="cccc" value="0" >
						<input type="hidden" name="place_id" id="place_id" value="0" >
					</div>
					<br/>
					<div style="width:912px;position:relative;">
						<div style="height:53px;">
							<div class="search">
								<div class="search_c"><input type="text" class="search_t" onKeyPress="if(event.keyCode==13) {btnSearch.click();return false;}"/></div>
								<div id="btn_search" class="btn_get">搜索</div>
							</div>
							<div class="poi">
								<div class="poi_note">坐标：</div>
								<input type="text" id="poi_cur" name="long_lat" value="<?php echo ($info["long_lat"]); ?>"/>
								<div class="poi_note">地址：</div>
								<input type="text" id="addr_cur" name="address"/>
							</div>
						</div>
						<div id="main">
							<div id="tooles">
								<div id="cur_city">
									<strong>北京市</strong><span class="change_city">[<span style="text-decoration:underline;">更换城市</span>]<span id="level">当前缩放等级：10</span></span>
									<div id="city" class="hide">
    <h3 class="city_class">热门城市<span class="close">X</span></h3>
    <div class="city_container">
        <span class="city_name">北京</span>
        <span class="city_name">深圳</span>
        <span class="city_name">上海</span>
        <span class="city_name">香港</span>
        <span class="city_name">澳门</span>
        <span class="city_name">广州</span>
        <span class="city_name">天津</span>
        <span class="city_name">重庆</span>
        <span class="city_name">杭州</span>
        <span class="city_name">成都</span>
        <span class="city_name">武汉</span>
        <span class="city_name">青岛</span>
    </div>
    <h3 class="city_class">全国城市</h3>
    <div class="city_container">
        <div class="city_container_left">直辖市</div>
        <div class="city_container_right">
            <span class="city_name">北京</span>
            <span class="city_name">上海</span>
            <span class="city_name">天津</span>
            <span class="city_name">重庆</span>
        </div>
    </div>
    <div style="clear:both"></div>
    <div class="city_container">
        <div class="city_container_left"><span class="style_color">内蒙古</span></div>
        <div class="city_container_right">
            <span class="city_name">呼和浩特</span>
            <span class="city_name">包头</span>
            <span class="city_name">乌海</span>
            <span class="city_name">赤峰</span>
            <span class="city_name">通辽</span>
            <span class="city_name">鄂尔多斯</span>
            <span class="city_name">呼伦贝尔</span>
            <span class="city_name">巴彦淖尔</span>
            <span class="city_name">乌兰察布</span>
            <span class="city_name">兴安盟</span>
            <span class="city_name">锡林郭勒盟</span>
            <span class="city_name">阿拉善盟</span>
        </div>
    </div>
    <div style="clear:both"></div>
    <div class="city_container">
        <div class="city_container_left"><span class="style_color">山西</span></div>
        <div class="city_container_right">
            <span class="city_name">太原</span>
            <span class="city_name">大同</span>
            <span class="city_name">阳泉</span>
            <span class="city_name">长治</span>
            <span class="city_name">晋城</span>
            <span class="city_name">朔州</span>
            <span class="city_name">晋中</span>
            <span class="city_name">运城</span>
            <span class="city_name">忻州</span>
            <span class="city_name">临汾</span>
            <span class="city_name">吕梁</span>

        </div>
    </div>
    <div style="clear:both"></div>
    <div class="city_container">
        <div class="city_container_left"><span class="style_color">陕西</span></div>
        <div class="city_container_right">
            <span class="city_name">西安</span>
            <span class="city_name">铜川</span>
            <span class="city_name">宝鸡</span>
            <span class="city_name">咸阳</span>
            <span class="city_name">渭南</span>
            <span class="city_name">延安</span>
            <span class="city_name">汉中</span>
            <span class="city_name">榆林</span>
            <span class="city_name">安康</span>
            <span class="city_name">商洛</span>
        </div>
    </div>
    <div style="clear:both"></div>
    <div class="city_container">
        <div class="city_container_left"><span class="style_color">河北</span></div>
        <div class="city_container_right">
            <span class="city_name">石家庄</span>
            <span class="city_name">唐山</span>
            <span class="city_name">秦皇岛</span>
            <span class="city_name">邯郸</span>
            <span class="city_name">邢台</span>
            <span class="city_name">保定</span>
            <span class="city_name">张家口</span>
            <span class="city_name">承德</span>
            <span class="city_name">沧州</span>
            <span class="city_name">廊坊</span>
            <span class="city_name">衡水</span>
        </div>
    </div>
    <div style="clear:both"></div>
    <div class="city_container">
        <div class="city_container_left"><span class="style_color">辽宁</span></div>
        <div class="city_container_right">
            <span class="city_name">沈阳</span>
            <span class="city_name">大连</span>
            <span class="city_name">鞍山</span>
            <span class="city_name">抚顺</span>
            <span class="city_name">本溪</span>
            <span class="city_name">丹东</span>
            <span class="city_name">锦州</span>
            <span class="city_name">营口</span>
            <span class="city_name">阜新</span>
            <span class="city_name">辽阳</span>
            <span class="city_name">盘锦</span>
            <span class="city_name">铁岭</span>
            <span class="city_name">朝阳</span>
            <span class="city_name">葫芦岛</span>
        </div>
    </div>
    <div style="clear:both"></div>
    <div class="city_container">
        <div class="city_container_left"><span class="style_color">吉林</span></div>
        <div class="city_container_right">
            <span class="city_name">长春</span>
            <span class="city_name">吉林</span>
            <span class="city_name">四平</span>
            <span class="city_name">辽源</span>
            <span class="city_name">通化</span>
            <span class="city_name">白山</span>
            <span class="city_name">松原</span>
            <span class="city_name">白城</span>
            <span class="city_name">延边</span>
        </div>
    </div>
    <div style="clear:both"></div>
    <div class="city_container">
        <div class="city_container_left"><span class="style_color">黑龙江</span></div>
        <div class="city_container_right">
            <span class="city_name">哈尔滨</span>
            <span class="city_name">齐齐哈尔</span>
            <span class="city_name">鸡西</span>
            <span class="city_name">鹤岗</span>
            <span class="city_name">双鸭山</span>
            <span class="city_name">大庆</span>
            <span class="city_name">伊春</span>
            <span class="city_name">牡丹江</span>
            <span class="city_name">佳木斯</span>
            <span class="city_name">七台河</span>
            <span class="city_name">黑河</span>
            <span class="city_name">绥化</span>
            <span class="city_name">大兴安岭</span>
        </div>
    </div>
    <div style="clear:both"></div>
    <div class="city_container">
        <div class="city_container_left"><span class="style_color">江苏</span></div>
        <div class="city_container_right">
            <span class="city_name">南京</span>
            <span class="city_name">无锡</span>
            <span class="city_name">徐州</span>
            <span class="city_name">常州</span>
            <span class="city_name">苏州</span>
            <span class="city_name">南通</span>
            <span class="city_name">连云港</span>
            <span class="city_name">淮安</span>
            <span class="city_name">盐城</span>
            <span class="city_name">扬州</span>
            <span class="city_name">镇江</span>
            <span class="city_name">泰州</span>
            <span class="city_name">宿迁</span>
        </div>
    </div>
    <div style="clear:both"></div>
    <div class="city_container">
        <div class="city_container_left"><span class="style_color">安徽</span></div>
        <div class="city_container_right">
            <span class="city_name">合肥</span>
            <span class="city_name">蚌埠</span>
            <span class="city_name">芜湖</span>
            <span class="city_name">淮南</span>
            <span class="city_name">马鞍山</span>
            <span class="city_name">淮北</span>
            <span class="city_name">铜陵</span>
            <span class="city_name">安庆</span>
            <span class="city_name">黄山</span>
            <span class="city_name">阜阳</span>
            <span class="city_name">宿州</span>
            <span class="city_name">滁州</span>
            <span class="city_name">六安</span>
            <span class="city_name">宣城</span>
            <span class="city_name">池州</span>
            <span class="city_name">亳州</span>
        </div>
    </div>
    <div style="clear:both"></div>
    <div class="city_container">
        <div class="city_container_left"><span class="style_color">山东</span></div>
        <div class="city_container_right">
            <span class="city_name">济南</span>
            <span class="city_name">青岛</span>
            <span class="city_name">淄博</span>
            <span class="city_name">枣庄</span>
            <span class="city_name">东营</span>
            <span class="city_name">潍坊</span>
            <span class="city_name">烟台</span>
            <span class="city_name">威海</span>
            <span class="city_name">济宁</span>
            <span class="city_name">泰安</span>
            <span class="city_name">日照</span>
            <span class="city_name">莱芜</span>
            <span class="city_name">临沂</span>
            <span class="city_name">德州</span>
            <span class="city_name">聊城</span>
            <span class="city_name">滨州</span>
            <span class="city_name">菏泽</span>
        </div>
    </div>
    <div style="clear:both"></div>
    <div class="city_container">
        <div class="city_container_left"><span class="style_color">浙江</span></div>
        <div class="city_container_right">
            <span class="city_name">杭州</span>
            <span class="city_name">宁波</span>
            <span class="city_name">温州</span>
            <span class="city_name">嘉兴</span>
            <span class="city_name">绍兴</span>
            <span class="city_name">金华</span>
            <span class="city_name">衢州</span>
            <span class="city_name">舟山</span>
            <span class="city_name">台州</span>
            <span class="city_name">丽水</span>
            <span class="city_name">湖州</span>
        </div>
    </div>
    <div style="clear:both"></div>
    <div class="city_container">
        <div class="city_container_left"><span class="style_color">江西</span></div>
        <div class="city_container_right">
            <span class="city_name">南昌</span>
            <span class="city_name">景德镇</span>
            <span class="city_name">萍乡</span>
            <span class="city_name">九江</span>
            <span class="city_name">新余</span>
            <span class="city_name">鹰潭</span>
            <span class="city_name">赣州</span>
            <span class="city_name">吉安</span>
            <span class="city_name">宜春</span>
            <span class="city_name">抚州</span>
            <span class="city_name">上饶</span>
        </div>
    </div>
    <div style="clear:both"></div>
    <div class="city_container">
        <div class="city_container_left"><span class="style_color">福建</span></div>
        <div class="city_container_right">
            <span class="city_name">福州</span>
            <span class="city_name">厦门</span>
            <span class="city_name">莆田</span>
            <span class="city_name">三明</span>
            <span class="city_name">泉州</span>
            <span class="city_name">漳州</span>
            <span class="city_name">南平</span>
            <span class="city_name">龙岩</span>
            <span class="city_name">宁德</span>
        </div>
    </div>
    <div style="clear:both"></div>
    <div class="city_container">
        <div class="city_container_left"><span class="style_color">湖南</span></div>
        <div class="city_container_right">
            <span class="city_name">长沙</span>
            <span class="city_name">株洲</span>
            <span class="city_name">湘潭</span>
            <span class="city_name">衡阳</span>
            <span class="city_name">邵阳</span>
            <span class="city_name">岳阳</span>
            <span class="city_name">常德</span>
            <span class="city_name">张家界</span>
            <span class="city_name">益阳</span>
            <span class="city_name">郴州</span>
            <span class="city_name">永州</span>
            <span class="city_name">怀化</span>
            <span class="city_name">娄底</span>
            <span class="city_name">湘西</span>
        </div>
    </div>
    <div style="clear:both"></div>
    <div class="city_container">
        <div class="city_container_left"><span class="style_color">湖北</span></div>
        <div class="city_container_right">
            <span class="city_name">武汉</span>
            <span class="city_name">黄石</span>
            <span class="city_name">襄樊</span>
            <span class="city_name">十堰</span>
            <span class="city_name">宜昌</span>
            <span class="city_name">荆门</span>
            <span class="city_name">鄂州</span>
            <span class="city_name">孝感</span>
            <span class="city_name">荆州</span>
            <span class="city_name">黄冈</span>
            <span class="city_name">咸宁</span>
            <span class="city_name">随州</span>
            <span class="city_name">恩施</span>
        </div>
    </div>
    <div style="clear:both"></div>
    <div class="city_container">
        <div class="city_container_left"><span class="style_color">河南</span></div>
        <div class="city_container_right">
            <span class="city_name">郑州</span>
            <span class="city_name">开封</span>
            <span class="city_name">洛阳</span>
            <span class="city_name">平顶山</span>
            <span class="city_name">焦作</span>
            <span class="city_name">鹤壁</span>
            <span class="city_name">新乡</span>
            <span class="city_name">安阳</span>
            <span class="city_name">濮阳</span>
            <span class="city_name">许昌</span>
            <span class="city_name">漯河</span>
            <span class="city_name">三门峡</span>
            <span class="city_name">南阳</span>
            <span class="city_name">商丘</span>
            <span class="city_name">信阳</span>
            <span class="city_name">周口</span>
            <span class="city_name">驻马店</span>
        </div>
    </div>
    <div style="clear:both"></div>
    <div class="city_container">
        <div class="city_container_left"><span class="style_color">海南</span></div>
        <div class="city_container_right">
            <span class="city_name">海口</span>
            <span class="city_name">三亚</span>
            <span class="city_name">三沙</span>
        </div>
    </div>
    <div style="clear:both"></div>
    <div class="city_container">
        <div class="city_container_left"><span class="style_color">广东</span></div>
        <div class="city_container_right">
            <span class="city_name">广州</span>
            <span class="city_name">深圳</span>
            <span class="city_name">珠海</span>
            <span class="city_name">汕头</span>
            <span class="city_name">韶关</span>
            <span class="city_name">佛山</span>
            <span class="city_name">江门</span>
            <span class="city_name">湛江</span>
            <span class="city_name">茂名</span>
            <span class="city_name">东沙群岛</span>
            <span class="city_name">肇庆</span>
            <span class="city_name">惠州</span>
            <span class="city_name">梅州</span>
            <span class="city_name">汕尾</span>
            <span class="city_name">河源</span>
            <span class="city_name">阳江</span>
            <span class="city_name">清远</span>
            <span class="city_name">东莞</span>
            <span class="city_name">中山</span>
            <span class="city_name">潮州</span>
            <span class="city_name">揭阳</span>
            <span class="city_name">云浮</span>
        </div>
    </div>
    <div style="clear:both"></div>
    <div class="city_container">
        <div class="city_container_left"><span class="style_color">广西</span></div>
        <div class="city_container_right">
            <span class="city_name">南宁</span>
            <span class="city_name">柳州</span>
            <span class="city_name">桂林</span>
            <span class="city_name">梧州</span>
            <span class="city_name">北海</span>
            <span class="city_name">防城港</span>
            <span class="city_name">钦州</span>
            <span class="city_name">贵港</span>
            <span class="city_name">玉林</span>
            <span class="city_name">百色</span>
            <span class="city_name">贺州</span>
            <span class="city_name">河池</span>
            <span class="city_name">来宾</span>
            <span class="city_name">崇左</span>
        </div>
    </div>
    <div style="clear:both"></div>
    <div class="city_container">
        <div class="city_container_left"><span class="style_color">贵州</span></div>
        <div class="city_container_right">
            <span class="city_name">贵阳</span>
            <span class="city_name">遵义</span>
            <span class="city_name">安顺</span>
            <span class="city_name">铜仁</span>
            <span class="city_name">毕节</span>
            <span class="city_name">六盘水</span>
            <span class="city_name">黔西南</span>
            <span class="city_name">黔东南</span>
            <span class="city_name">黔南</span>
        </div>
    </div>
    <div style="clear:both"></div>
    <div class="city_container">
        <div class="city_container_left"><span class="style_color">四川</span></div>
        <div class="city_container_right">
            <span class="city_name">成都</span>
            <span class="city_name">自贡</span>
            <span class="city_name">攀枝花</span>
            <span class="city_name">泸州</span>
            <span class="city_name">德阳</span>
            <span class="city_name">绵阳</span>
            <span class="city_name">广元</span>
            <span class="city_name">遂宁</span>
            <span class="city_name">内江</span>
            <span class="city_name">乐山</span>
            <span class="city_name">南充</span>
            <span class="city_name">宜宾</span>
            <span class="city_name">广安</span>
            <span class="city_name">达州</span>
            <span class="city_name">眉山</span>
            <span class="city_name">雅安</span>
            <span class="city_name">巴中</span>
            <span class="city_name">资阳</span>
            <span class="city_name">阿坝</span>
            <span class="city_name">甘孜</span>
            <span class="city_name">凉山</span>
        </div>
    </div>
    <div style="clear:both"></div>
    <div class="city_container">
        <div class="city_container_left"><span class="style_color">云南</span></div>
        <div class="city_container_right">
            <span class="city_name">昆明</span>
            <span class="city_name">保山</span>
            <span class="city_name">昭通</span>
            <span class="city_name">丽江</span>
            <span class="city_name">普洱</span>
            <span class="city_name">临沧</span>
            <span class="city_name">曲靖</span>
            <span class="city_name">玉溪</span>
            <span class="city_name">文山</span>
            <span class="city_name">西双版纳</span>
            <span class="city_name">楚雄</span>
            <span class="city_name">红河</span>
            <span class="city_name">德宏</span>
            <span class="city_name">大理</span>
            <span class="city_name">怒江</span>
            <span class="city_name">迪庆</span>
        </div>
    </div>
    <div style="clear:both"></div>
    <div class="city_container">
        <div class="city_container_left"><span class="style_color">甘肃</span></div>
        <div class="city_container_right">
            <span class="city_name">兰州</span>
            <span class="city_name">嘉峪关</span>
            <span class="city_name">金昌</span>
            <span class="city_name">白银</span>
            <span class="city_name">天水</span>
            <span class="city_name">酒泉</span>
            <span class="city_name">张掖</span>
            <span class="city_name">武威</span>
            <span class="city_name">定西</span>
            <span class="city_name">陇南</span>
            <span class="city_name">平凉</span>
            <span class="city_name">庆阳</span>
            <span class="city_name">临夏</span>
            <span class="city_name">甘南</span>
        </div>
    </div>
    <div style="clear:both"></div>
    <div class="city_container">
        <div class="city_container_left"><span class="style_color">宁夏</span></div>
        <div class="city_container_right">
            <span class="city_name">银川</span>
            <span class="city_name">石嘴山</span>
            <span class="city_name">吴忠</span>
            <span class="city_name">固原</span>
            <span class="city_name">中卫</span>
        </div>
    </div>
    <div style="clear:both"></div>
    <div class="city_container">
        <div class="city_container_left"><span class="style_color">青海</span></div>
        <div class="city_container_right">
            <span class="city_name">西宁</span>
            <span class="city_name">玉树</span>
            <span class="city_name">果洛</span>
            <span class="city_name">海东</span>
            <span class="city_name">海西</span>
            <span class="city_name">黄南</span>
            <span class="city_name">海北</span>
            <span class="city_name">海南</span>
        </div>
    </div>
    <div style="clear:both"></div>
    <div class="city_container">
        <div class="city_container_left"><span class="style_color">西藏</span></div>
        <div class="city_container_right">
            <span class="city_name">拉萨</span>
            <span class="city_name">那曲</span>
            <span class="city_name">昌都</span>
            <span class="city_name">山南</span>
            <span class="city_name">日喀则</span>
            <span class="city_name">阿里</span>
            <span class="city_name">林芝</span>
        </div>
    </div>
    <div style="clear:both"></div>
    <div class="city_container">
        <div class="city_container_left"><span class="style_color">新疆</span></div>
        <div class="city_container_right">
            <span class="city_name">乌鲁木齐</span>
            <span class="city_name">克拉玛依</span>
            <span class="city_name">吐鲁番</span>
            <span class="city_name">哈密</span>
            <span class="city_name">博尔塔拉</span>
            <span class="city_name">巴音郭楞</span>
            <span class="city_name">克孜勒苏</span>
            <span class="city_name">和田</span>
            <span class="city_name">阿克苏</span>
            <span class="city_name">喀什</span>
            <span class="city_name">塔城</span>
            <span class="city_name">伊犁</span>
            <span class="city_name">昌吉</span>
            <span class="city_name">阿勒泰</span>
        </div>
    </div>
    <div style="clear:both"></div>
</div>
								</div>
							</div>
							<div id="bside_left">
								<div id="txt_pannel">
									<h3>功能简介：</h3>
									<p>1、支持地址 精确/模糊 查询；</p>
									<p>2、支持POI点坐标显示；</p>
									<p>3、坐标鼠标跟随显示；</p>
									<h3>使用说明：</h3>
									<p>在搜索框搜索关键词后，地图上会显示相应poi点，同时左侧显示对应该点的信息，点击某点或某信息，右上角会显示相应该点的坐标和地址。</p>
								</div>
							</div>
							<div id="bside_rgiht">
								<div id="container"></div>
							</div>
						</div>
					</div>
                </div>
				<!--店铺图片-->
				<div class="content_list pad_10 ">
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

									</div>
								</div>
							</td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="mt10"><input type="submit" value="<?php echo L('submit');?>" id="dosubmit" name="dosubmit" class="btn btn_submit" style="margin:0 0 10px 100px;"><br /><br /><br /></div>
		</div>
	</div>
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
<script src="/theme/admin/js/kindeditor/kindeditor.js"></script>
<script src="/theme/admin/js/plupload/plupload.full.min.js"></script><!--上传图片插件-->

<script>
    $(function(){
        $('ul.J_tabs').tabs('div.J_panes > div');
		//表单验证
		$('#info_form').submit(function(){
            if(!$('#title').val()){
                $.pinphp.tip({content:'请填写店铺名字', icon:'alert'});
                return false;
            }
            if(!$('#user_list').val()){
                $.pinphp.tip({content:'请选择用户', icon:'alert'});
                return false;
            }
            if(!$('input[name="tel"]').val()){
                $.pinphp.tip({content:'请填写店铺电话', icon:'alert'});
                return false;
            }
            var tel=$('input[name="cate_id"]').val();
            if(tel==0||!tel){
                $.pinphp.tip({content:'请选择分类', icon:'alert'});
                return false;
            }
            var province_id=$('input[name="province_id"]').val();
			if(province_id==0||!province_id){
                    $.pinphp.tip({content:'请选择地区', icon:'alert'});
                return false;
            }

		})
//        $.formValidator.initConfig({formid:"info_form",autotip:true});
//        $('#title').formValidator({onshow:"请填写店铺名字",onfocus:"请填写名称"}).inputValidator({min:1,onerror:"请填写名称"});
        //自定义时间格式
        laydate.render({
            elem: '#J_time_start'
            ,type: 'time'
            ,format: 'HH:mm'

        });

        laydate.render({
            elem: '#J_time_end'
            ,type: 'time'
            ,format: 'HH:mm'
        });

        $('.J_cate_select').cate_select({top_option:lang.all}); //店铺分类联动
        $('.J_place_select').cate_select({top_option:lang.all,field:'place_id',target_class:'J_place_select'}); //地区分类联动

		//省市区
        $('body').live('change','.J_place_select.mr10',function(){
            if($('#place_id').val()!=0){
                var aa=$('.J_place_select:eq(0)').val();
                var bb=$('.J_place_select:eq(1)').val();
                var cc=$('.J_place_select:eq(2)').val();
                $('#aaaa').val(aa);
                $('#bbbb').val(bb);
                $('#cccc').val(cc);
                $('#place_id').val(cc);
            }
        })

        //查询会员
        $('#search_member').keyup(function () {
            var parm = $(this).val(),
                ht = "";
            $.get("<?php echo U('Merchant/get_user_list');?>",{parm:parm},function (result) {
                if(result.length > 0){
                    $.each(result,function (k,v) {
                        ht += '<option value="'+v.id+'">'+v.realname+'-'+v.mobile+'</option>';
                    })
                }else{
                    ht += '<option value="">--没有相关会员，请重新查询--</option>';
                }
                $('#user_list').html(ht);
            },'json')
        })


    })
</script>

<!--上传图片-->
<script>
    //上传图片
    var uploader = new plupload.Uploader({
        runtimes: 'gears,html5,html4,silverlight,flash',
        browse_button: 'logo_upload_btn',
        url: "<?php echo U('Merchant/ajax_upload_img');?>",
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
                data.src='data/attachment/merchant_img/'+datasrc;

                $("#" + file.id).html("<input type=hidden name='imgs[]' value='"+datasrc+"'><img src='"+data.src+"' alt='"+data.name+"' width='100px' height='100px'>\n\
	<div class='operate'><i class='toleft'>左移</i><i class='toright'>右移</i><i class='del'>删除</i></div>")

                $("#btn_submit").removeAttr("disabled").removeClass("disabled").val("提 交");
                if (data.error != 0) {
                    alert(data.error);
                }
            },
            Error: function(up, err) {
                if (err.code == -601) {
                    alert("请上传jpg,png,gif,jpeg,zip或rar！");
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
            $.get("<?php echo U('Merchant/del_imgs');?>",{id:id},function (result) {
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

</script>

<script type="text/javascript">
	//地图
    var container = document.getElementById("container");
    var map = new qq.maps.Map(container, {
            zoom: 10
        }), 
        
        label = new qq.maps.Label({
            map: map,
            offset: new qq.maps.Size(15,-12),
            draggable: false,
            clickable: false
        }),
        
        markerArray = [],
        curCity = document.getElementById("cur_city"),
        btnSearch = document.getElementById("btn_search"),
        bside = document.getElementById("bside_left"),
        url, query_city,
        cityservice = new qq.maps.CityService({
            complete: function (result) {
                curCity.children[0].innerHTML = result.detail.name;
                map.setCenter(result.detail.latLng);
            }
        });
        
	    cityservice.searchLocalCity();
	    map.setOptions({
	        draggableCursor: "crosshair"
	    });

	    $(container).mouseenter(function () {
	        label.setMap(map);
	    });
	    $(container).mouseleave(function () {
	        label.setMap(null);
	    });

	    qq.maps.event.addListener(map, "mousemove", function (e) {
	        var latlng = e.latLng;
	        label.setPosition(latlng);
	        label.setContent(latlng.getLat().toFixed(6) + "," + latlng.getLng().toFixed(6));
	    });

	    var url3;
	    qq.maps.event.addListener(map, "click", function (e) {
	        document.getElementById("poi_cur").value = e.latLng.getLat().toFixed(6) + "," + e.latLng.getLng().toFixed(6);
	        url3 = encodeURI("http://apis.map.qq.com/ws/geocoder/v1/?location=" + e.latLng.getLat() + "," + e.latLng.getLng() + "&key=RN5BZ-JAZ2U-FKBVV-4ZPMC-QWM5V-CNBPZ&output=jsonp&&callback=?");
	        $.getJSON(url3, function (result) {
	            if(result.result!=undefined){
	                document.getElementById("addr_cur").value = result.result.address;
	            }else{
	                document.getElementById("addr_cur").value = "";
	            }
	
	        })
	    });

    qq.maps.event.addListener(map, "zoom_changed", function () {
        document.getElementById("level").innerHTML = "当前缩放等级：" + map.getZoom();
    });
    var listener_arr = [];
    var isNoValue = false;
    qq.maps.event.addDomListener(btnSearch, 'click', function () {
        var value = this.parentNode.getElementsByTagName("input")[0].value;
        var latlngBounds = new qq.maps.LatLngBounds();
        for(var i= 0,l=listener_arr.length;i<l;i++){
            qq.maps.event.removeListener(listener_arr[i]);
        }
        listener_arr.length = 0;
        query_city = curCity.children[0].innerHTML;
        url = encodeURI("http://apis.map.qq.com/ws/place/v1/search?keyword=" + value + "&boundary=region(" + query_city + ",0)&page_size=9&page_index=1&key=RN5BZ-JAZ2U-FKBVV-4ZPMC-QWM5V-CNBPZ&output=jsonp&&callback=?");
        $.getJSON(url, function (result) {

            if (result.count) {
                isNoValue = false;
                bside.innerHTML = "";
                each(markerArray, function (n, ele) {
                    ele.setMap(null);
                });
                markerArray.length = 0;
                each(result.data, function (n, ele) {
                    var latlng = new qq.maps.LatLng(ele.location.lat, ele.location.lng);
                    latlngBounds.extend(latlng);
                    var left = n * 27;
                    var marker = new qq.maps.Marker({
                        map: map,
                        position: latlng,
                        zIndex: 10
                    });
                    marker.index = n;
                    marker.isClicked = false;
                    setAnchor(marker, true);
                    markerArray.push(marker);
                    var listener1 = qq.maps.event.addDomListener(marker, "mouseover", function () {
                        var n = this.index;
                        setCurrent(markerArray, n, false);
                        setCurrent(markerArray, n, true);
                        label.setContent(this.position.getLat().toFixed(6) + "," + this.position.getLng().toFixed(6));
                        label.setPosition(this.position);
                        label.setOptions({
                            offset: new qq.maps.Size(15, -20)
                        })

                    });
                    listener_arr.push(listener1);
                    var listener2 = qq.maps.event.addDomListener(marker, "mouseout", function () {
                        var n = this.index;
                        setCurrent(markerArray, n, false);
                        setCurrent(markerArray, n, true);
                        label.setOptions({
                            offset: new qq.maps.Size(15, -12)
                        })
                    });
                    listener_arr.push(listener2);
                    var listener3 = qq.maps.event.addDomListener(marker, "click", function () {
                        var n = this.index;
                        setFlagClicked(markerArray, n);
                        setCurrent(markerArray, n, false);
                        setCurrent(markerArray, n, true);
                        document.getElementById("addr_cur").value = bside.childNodes[n].childNodes[1].childNodes[1].innerHTML.substring(3);
                    });
                    listener_arr.push(listener3);
                    map.fitBounds(latlngBounds);
                    var div = document.createElement("div");
                    div.className = "info_list";
                    var order = document.createElement("div");
                    var leftn = -54 - 17 * n;
                    order.style.cssText = "width:17px;height:17px;margin:3px 3px 0px 0px;float:left;background:url(/theme/admin/getpoint/img/marker_n.png) " + leftn + "px 0px";
                    div.appendChild(order);
                    var pannel = document.createElement("div");
                    pannel.style.cssText = "width:200px;float:left;";
                    div.appendChild(pannel);
                    var name = document.createElement("p");
                    name.style.cssText = "margin:0px;color:#0000CC";
                    name.innerHTML = ele.title;
                    pannel.appendChild(name);
                    var address = document.createElement("p");
                    address.style.cssText = "margin:0px;";
                    address.innerHTML = "地址：" + ele.address;
                    pannel.appendChild(address);
                    if (ele.tel != undefined) {
                        var phone = document.createElement("p");
                        phone.style.cssText = "margin:0px;";
                        phone.innerHTML = "电话：" + ele.tel;
                        pannel.appendChild(phone);
                    }
                    var position = document.createElement("p");
                    position.style.cssText = "margin:0px;";
                    position.innerHTML = "坐标：" + ele.location.lat.toFixed(6) + "，" + ele.location.lng.toFixed(6);
                    pannel.appendChild(position);
                    bside.appendChild(div);
                    div.style.height = pannel.offsetHeight + "px";
                    div.isClicked = false;
                    div.index = n;
                    marker.div = div;
                    div.marker = marker;
                });
                $("#bside_left").delegate(".info_list", "mouseover", function (e) {

                    var n = this.index;

                    setCurrent(markerArray, n, false);
                    setCurrent(markerArray, n, true);
                }); 
                $("#bside_left").delegate(".info_list", "mouseout", function () {
                    each(markerArray, function (n, ele) {
                        if (!ele.isClicked) {
                            setAnchor(ele, true);
                            ele.div.style.background = "#fff";
                        }
                    })
                });
                $("#bside_left").delegate(".info_list", "click", function (e) {
                    var n = this.index;
                    setFlagClicked(markerArray, n);
                    setCurrent(markerArray, n, false);
                    setCurrent(markerArray, n, true);
                    map.setCenter(markerArray[n].position);
                    document.getElementById("addr_cur").value = this.childNodes[1].childNodes[1].innerHTML.substring(3);
                });
            } else {

                bside.innerHTML = "";
                each(markerArray, function (n, ele) {
                    ele.setMap(null);
                });
                markerArray.length = 0;
                var novalue = document.createElement('div');
                novalue.id = "no_value";
                novalue.innerHTML = "对不起，没有搜索到您要找的结果!";
                bside.appendChild(novalue);
                isNoValue = true;
            }
        });
    });

    btnSearch.onmousedown = function () {
        this.className = "btn_active";
    };
    btnSearch.onmouseup = function () {
        this.className = "btn_get";
    };
    function setAnchor(marker, flag) {
        var left = marker.index * 27;
        if (flag == true) {
            var anchor = new qq.maps.Point(10, 30),
                origin = new qq.maps.Point(left, 0),
                size = new qq.maps.Size(27, 33),
                icon = new qq.maps.MarkerImage("/theme/admin/getpoint/img/marker10.png", size, origin, anchor);
            marker.setIcon(icon);
        } else {
            var anchor = new qq.maps.Point(10, 30),
                origin = new qq.maps.Point(left, 35),
                size = new qq.maps.Size(27, 33),
                icon = new qq.maps.MarkerImage("/theme/admin/getpoint/img/marker10.png", size, origin, anchor);
            marker.setIcon(icon);
        }
    }
    function setCurrent(arr, index, isMarker) {
        if (isMarker) {
            each(markerArray, function (n, ele) {
                if (n == index) {
                    setAnchor(ele, false);
                    ele.setZIndex(10);
                } else {
                    if (!ele.isClicked) {
                        setAnchor(ele, true);
                        ele.setZIndex(9);
                    }
                }
            });
        } else {
            each(markerArray, function (n, ele) {
                if (n == index) {
                    ele.div.style.background = "#DBE4F2";
                } else {
                    if (!ele.div.isClicked) {
                        ele.div.style.background = "#fff";
                    }
                }
            });
        }
    }
    function setFlagClicked(arr, index) {
        each(markerArray, function (n, ele) {
            if (n == index) {
                ele.isClicked = true;
                ele.div.isClicked = true;
                var str = '<div style="width:250px;">' + ele.div.children[1].innerHTML.toString() + '</div>';
                var latLng = ele.getPosition();
                document.getElementById("poi_cur").value = latLng.getLat().toFixed(6) + "," + latLng.getLng().toFixed(6);
            } else {
                ele.isClicked = false;
                ele.div.isClicked = false;
            }
        });
    }
    var city = document.getElementById("city");

    curCity.onclick = function (e) {
        var e = e || window.event,
            target = e.target || e.srcElement;
        if (target.innerHTML == "更换城市") {
            city.style.display = "block";
            if(isNoValue){
                bside.innerHTML = "";
                each(markerArray, function (n, ele) {
                    ele.setMap(null);
                });
                markerArray.length = 0;
            }

        }
    };

    var url2;
    city.onclick = function (e) {
        var e = e || window.event,
            target = e.target || e.srcElement;
        if (target.className == "close") {
            city.style.display = "none";
        }
        if (target.className == "city_name") {

            curCity.children[0].innerHTML = target.innerHTML;

            url2 = encodeURI("http://apis.map.qq.com/ws/geocoder/v1/?region=" + curCity.children[0].innerHTML + "&address=" + curCity.children[0].innerHTML + "&key=RN5BZ-JAZ2U-FKBVV-4ZPMC-QWM5V-CNBPZ&output=jsonp&&callback=?");
            $.getJSON(url2, function (result) {
                map.setCenter(new qq.maps.LatLng(result.result.location.lat, result.result.location.lng));
                map.setZoom(10);
            });
            city.style.display = "none";
        }
    };

    var url4;
    $(".search_t").autocomplete({
        source:function(request,response){
            url4 = encodeURI("http://apis.map.qq.com/ws/place/v1/suggestion/?keyword=" + request.term + "&region=" + curCity.children[0].innerHTML + "&key=RN5BZ-JAZ2U-FKBVV-4ZPMC-QWM5V-CNBPZ&output=jsonp&&callback=?");
            $.getJSON(url4,function(result){

                response($.map(result.data,function(item){
                    return({
                        label:item.title

                    })
                }));
            });
        }
    });

    function each(obj, fn) {
        for (var n = 0, l = obj.length; n < l; n++) {
            fn.call(obj[n], n, obj[n]);
        } 
    }
</script>


</body>
</html>