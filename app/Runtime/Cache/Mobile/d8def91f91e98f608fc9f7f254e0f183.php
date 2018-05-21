<?php if (!defined('THINK_PATH')) exit(); if(is_array($item)): $i = 0; $__LIST__ = $item;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div class="y-mar-top20 y-goldShop-item"><a href="<?php echo U('Goldfruitshop/gold_shop_detail',array('item_id'=>$v['item_id'],'id'=>$v['id']));?>">
        <p class="font-26 ellipsis1"><?php echo ($v["title"]); ?></p>
        <p class="font-20 y-color-999">悦买宝新用户专享</p>
        <p class="font-20 y-color-999"><span class="font-26 y-color-red"><?php echo ($v["gold_fruit"]); ?>个</span>&nbsp;金果</p>
        <div class="y-goldShop-item-img y-over-hide">
            <img src="<?php echo attach($v['img'],item);?>"/>
        </div>
        <!--<div class="y-goldShop-item-button y-bg-blue">立即兑换</div>-->
    </a>
    </div><?php endforeach; endif; else: echo "" ;endif; ?>