<?php if (!defined('THINK_PATH')) exit(); if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$info): $mod = ($i % 2 );++$i;?><li class="filtrate-list-li"><a href="<?php echo U('merchant_details',['id'=>$info['id']]);?>">
    <div class="filtrate-box row-box">
        <div class="filtrate-list-left">
            <div class="filtrate-list-img vertical-center">
                <div class="vertical-auto"><img src="<?php echo attach($info['img'],'avatar');?>"></div>
            </div>
        </div>

        <div class="row-flex">
            <div class="store-name"><?php echo ($info["title"]); ?></div>
            <div class="store-date">工作时间：<?php echo ($info["start"]); ?>-<?php echo ($info["end"]); ?></div>
            <?php if($info['zftype']): ?><div class="store-txt"><span class="zhi">付</span>支持
                        <?php $_result=explode(',',$info['zftype']);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$zftype): $mod = ($i % 2 );++$i; if($zftype == 1): ?>金元宝<?php endif; ?>
                            <?php if($zftype == 3): ?>金果<?php endif; endforeach; endif; else: echo "" ;endif; ?>
                        支付
                </div><?php endif; ?>
            <?php if($info['set_coin'] > 0): ?><div class="store-txt"><span class="bei">送</span>赠送<?php echo ($info["set_coin"]); ?>倍银币</div><?php endif; ?>
        </div>

        <div class="filtrate-list-right">
            <?php echo round($info['juli']/1000,2)?>km
        </div>
    </div>
</a></li><?php endforeach; endif; else: echo "" ;endif; ?>