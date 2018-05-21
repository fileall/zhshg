<?php if (!defined('THINK_PATH')) exit(); if($list == null): ?><div class="null-wrap vertical-center">
        <div class="vertical-auto">
            <div class="null-page">
                <div class="null-page-icon"><img src="/theme/mobile/images/icon60.png"></div>
                <div class="null-page-txt font-28">您暂时还没有订单哦~</div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <!--空购物车-->
    <div class="y-width100">         
        <!--商品订单-->
        <div class="goods-tier-box y-goods-tier-box y-block">
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$night): $mod = ($i % 2 );++$i;?><div class="y-mar-top20 y-bg-white">
                    <div class="goods-state-col">
                        <div class="goods-state-numbers">订单编号：<?php echo ($night["dingdan"]); ?></div>
                        <div class="goods-state-name y-color-red">
                            <?php switch($night["status"]): case "1": ?>待付款<?php break;?>
                                <?php case "2": ?>待发货<?php break;?>
                                <?php case "3": ?>待收货<?php break;?>
                                <?php case "4": ?>待评价<?php break;?>
                                <?php case "5": ?>已评价<?php break;?>
                                <?php case "6": ?>交易已取消<?php break;?>
                                <?php default: ?>默认情况<?php endswitch;?>
                        </div>
                        <div class="clear-float"></div>
                    </div>

                    <?php if(is_array($night['goods'])): $i = 0; $__LIST__ = $night['goods'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$good): $mod = ($i % 2 );++$i;?><a href="<?php echo U('Orderlist/y_orderDetails',array('order_id'=>$night['id'],'zftype'=>$night['type']));?>">
                            <div class="row-box goods-tier-inner y-border-none">
                                <div class="goods-img-box">
                                    <div class="level-img vertical-center">
                                        <div class="vertical-auto"><img src="<?php echo attach($good['img'],item);?>"></div>
                                    </div>
                                </div>
                                <div class="flex y-order-text">
                                    <div class="flavour-title ellipsis2"><?php echo ($good["title"]); ?></div>
                                    <div class="jus-bet ali-cen">
                                        <?php if($night['type'] == 1): ?><div class="y-color-red font-28">&yen;<?php echo ($good["price"]); ?></div>
                                            <?php else: ?>
                                            <div class="y-color-red font-28">金果<?php echo ($good["price"]); ?></div>
                                            <!--<div class="y-color-red font-28">金果<?php echo ($good["sli_price"]); ?></div>--><?php endif; ?>
                                        <div class="y-color-999">x<?php echo ($good["num"]); ?></div>
                                    </div>
                                </div>
                            </div>
                        </a><?php endforeach; endif; else: echo "" ;endif; ?>
                    <!--<?php if($night['zftype'] == 3): ?>-->
                        <!--<div class="statistics">-->
                            <!--<div class="y-width94 y-text-right">银币抵扣 合计：-->
                                <!--<span class="font-28 y-color-red">-->
                                    <!--¥<?php echo ($night['coin_price_dk']); ?>-->
                                <!--</span></div>-->
                        <!--</div>-->
                    <!--<?php endif; ?>-->
                    <div class="statistics">
                        <div class="y-width94 y-text-right">共<?php echo ($night["total_num"]); ?>件商品 合计：<span class="font-28 y-color-red">
                                 <?php if($night['type'] == 1): ?>¥<?php echo ($night['total_amount']); ?>
                                     <?php else: ?>
                                     金果<?php echo ($night['fruit_price']); endif; ?>
                            </span></div>
                    </div>

                    <div class="goods-state-link-box">
                        <?php if($night['status'] == 1): if($night['type'] == 1): ?><a href="<?php echo U('order/order_pay',array('oid'=>$night['id'],'zftype'=>$night['type']));?>" class="goods-state-link one" >立即付款</a>
                                <!--<a class="goods-state-link one" class="de" value="<?php echo ($night['id']); ?>" onclick="delete1()">取消订单</a>-->
                                <?php elseif($night['type'] == 2): ?>
                          <a href="<?php echo U('Goldfruitshop/y_pay',array('oid'=>$night['id'],'zftype'=>$night['type']));?>" class="goods-state-link one" >立即付款</a><?php endif; ?>
                            <a class="goods-state-link one" id="de" value="<?php echo ($night['id']); ?>" onclick="delete1()">取消订单</a><?php endif; ?>
                        <?php if($night['status'] == 3): ?><a class="goods-state-link one" id="shou" value="<?php echo ($night['id']); ?>" onclick="shouhuo()">确认收货</a><?php endif; ?>
                        <?php if($night['status'] == 4): ?><a href="<?php echo U('Orderlist/y_orderComment',array('oid'=>$night['id']));?>" class="goods-state-link one" >去评价</a><?php endif; ?>
                        <div class="clear-float"></div>
                    </div>
                </div><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        <!--<div class="goods-tier-box y-goods-tier-box">1</div>-->
        <!--<div class="goods-tier-box y-goods-tier-box">2</div>-->
        <!--<div class="goods-tier-box y-goods-tier-box">3</div>-->
        <!--<div class="goods-tier-box y-goods-tier-box">4</div>-->
        <!--商品订单-->
    </div><?php endif; ?>