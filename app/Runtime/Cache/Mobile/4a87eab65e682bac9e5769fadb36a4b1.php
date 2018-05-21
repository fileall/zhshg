<?php if (!defined('THINK_PATH')) exit(); if(is_array($a)): $i = 0; $__LIST__ = $a;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$y): $mod = ($i % 2 );++$i;?><li class="notecase-li">

        <div class="notecase-left">

            <div class="notecase-type"><?php echo ($y["change_desc"]); ?></div>

            <div class="notecase-date"><?php echo date('Y-m-d',$y['add_time']);?></div>

        </div>

        <div class="notecase-right <?php if($y["totalprices"] < 0 ): ?>active<?php endif; ?>">
        <?php echo ($y["totalprices"]); ?>
        </div>
        <div class="clear-float"></div>

    </li><?php endforeach; endif; else: echo "" ;endif; ?>