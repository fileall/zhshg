<?php if (!defined('THINK_PATH')) exit();?>
    <?php if(is_array($a)): $i = 0; $__LIST__ = $a;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$y): $mod = ($i % 2 );++$i;?><div class="y-tab-title y-couponRecord-item">
            <div class="y-tab-title-text">
                <div class="y-vip-icon">
                    <img src="<?php echo attach($y['avatar'],'avatar');?>"/>
                </div>
            </div>
            <div class="y-tab-title-text y-width75"><?php echo ($y['mobile']); ?></div>
            <div class="y-tab-title-text font-24"><?php echo date('Y-m-d' ,$y['reg_time']);?></div>
        </div><?php endforeach; endif; else: echo "" ;endif; ?>