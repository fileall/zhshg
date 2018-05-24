<?php if (!defined('THINK_PATH')) exit();?><!--订单详情-->
<div class="pad_lr_10">
    <div class="J_tablelist table_list">
        <table width="95%" cellspacing="0" align="center">
            <span style="position: relative;left:10px;">基本信息</span><br/>
            <tr>
                <td align="right" width="60">订单ID：</td>
                <td align="left"><?php echo ($info['id']); ?></td>
                <td align="right">订单号：</td>
                <td align="left"><?php echo ($info['dingdan']); ?></td>
                <td align="right">用户：</td>
                <td align="left"><?php echo ($user['nickname']); ?></td>
                <td align="right">联系电话：</td>
                <td align="left"><?php echo ($user['mobile']); ?></td>
            </tr>
            <!--<tr>-->
                <!--<td align="right">商家：</td>-->
                <!--<td align="left"><?php echo ($merchant['name']); ?></td>-->
                <!--<td align="right">商家电话：</td>-->
                <!--<td align="left"><?php echo ($merchant['tel']); ?></td>-->
                <!--<td align="right">骑手：</td>-->
                <!--<td align="left"><?php echo ($worker['real_name']); ?></td>-->
                <!--<td align="right">骑手电话：</td>-->
                <!--<td align="left"><?php echo ($worker['tel']); ?></td>-->
            <!--</tr>-->

            <tr>
                <td align="right">应付金额：</td>
                <td align="left"><?php echo ($info['order_amount']); ?></td>
                <td align="right">实际金额：</td>
                <td align="left"><?php echo ($info['total_amount']); ?></td>
                <td align="right">支付时间：</td>
                <td align="left"><?php if(!empty($info['pay_time'])): echo (date('Y-m-d H:i:s',$info['pay_time'])); endif; ?></td>
                <td align="right">下单时间：</td>
                <td align="left"><?php if(!empty($info['add_time'])): echo (date('Y-m-d H:i:s',$info['add_time'])); endif; ?></td>
            </tr>
            <tr>
                <td align="right">支付类型：</td>
                <td align="left"><?php echo order_zftype()[$info['zftype']];?></td>
                <td align="right">订单状态：</td>
                <td align="left"><?php echo order_status()[$info['status']];?></td>
                <td align="right">退款状态：</td>
                <td align="left"><?php echo tk_status()[$info['tk_status']];?></td>
            </tr>
        </table>

        <br/><br/>
        <table width="95%" cellspacing="0" align="center">
            <span style="position: relative;left:10px;">地址</span><br/>
            <tr>
                <td align="right" width="60">收货人：</td>
                <td align="left"><?php echo ($info['sh_person']); ?></td>
                <td align="right">联系方式：</td>
                <td align="left"><?php echo ($info['sh_mobile']); ?></td>
                <td align="right">收货地址：</td>
                <td align="left"><?php echo ($info['sh_address']); ?></td>
            </tr>
        </table>
        <br/><br/>
        <table width="95%" cellspacing="0" align="center">
            <span style="position: relative;left:10px;">发货</span><br/>
            <tr>
                <td align="right" width="70">快递公司：</td>
                <td align="left"><?php echo ($info['express_company']); ?></td>
                <td align="right"  width="70">快递单号：</td>
                <td align="left"><?php echo ($info['express_num']); ?></td>
            </tr>
        </table>

        <br/><br/>
        <table width="95%" cellspacing="0" align="center">
            <span style="position: relative;left:10px;">商品信息</span><br/>
            <thead>
            <tr>
                <th>商品</th>
                <th>数量</th>
                <th>单品价格</th>
                <th>单品小计</th>
            </tr>
            </thead>
            <tbody>
            <?php if(is_array($info['order_list'])): $i = 0; $__LIST__ = $info['order_list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
                    <td align="center"><?php echo ($val['title']); ?></td>
                    <td align="center"><?php echo ($val['num']); ?></td>
                    <td align="center"><?php echo ($val['price']); ?></td>
                    <td align="center"><?php echo sprintf("%.2f",$val['price']*$val['num']);?></td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            <tr>
                <td align="center"></td>
                <td align="center"></td>
                <td align="center"></td>
                <td align="center">商品总额：
                    <?php if($info['type'] == 2): echo sprintf("%.2f",$info['fruit_price']);?>金果
                    <?php else: ?>
                        <?php echo sprintf("%.2f",$info['total_amount']);?>元宝<?php endif; ?>

                </td>
            </tr>


            </tbody>
        </table>
        <br/>
        <!--<span style="position: relative;left: 90%;">订单总额：<?php echo sprintf("%.2f",$info['total_amount']);?></span>-->

        <br/><br/>
        <table width="95%" cellspacing="0" align="center">
            <span style="position: relative;left:10px;">费用信息</span><br/>
            <tr>
                <td align="right" >应付元宝：</td>
                <td align="left"><?php echo sprintf("%.2f",$info['order_amount']);?></td>
                <td align="right">运费：</td>
                <td align="left"><?php if(empty($info['shipping_price'])): ?>0<?php else: echo ($info['shipping_price']); endif; ?></td>
                <td align="right">优惠券抵扣：</td>
                <td align="left">-<?php echo sprintf("%.2f",$info['coupon_price_dk']);?></td>
                <td align="right">金果支付：</td>
                <td align="left">-<?php echo sprintf("%.2f",$info['fruit_price']);?></td>
            </tr>
            <tr>
                <td align="right">银币支付：</td>
                <td align="left">-<?php echo sprintf("%.2f",$info['coin_price']);?></td>
                <td align="right">实付元宝：</td>
                <td align="left"><?php echo ($info['total_amount']); ?></td>
            </tr>
        </table>

        <!--<table width="95%" cellspacing="0" align="center">-->
            <!--<span style="position: relative;left:10px;">操作记录</span><br/>-->
            <!--<thead>-->
            <!--<tr>-->
                <!--<th>订单状态</th>-->
                <!--<th>操作时间</th>-->
            <!--</tr>-->
            <!--</thead>-->
            <!--<tbody>-->
            <!--<?php if(is_array($info['time'])): $i = 0; $__LIST__ = $info['time'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$time): $mod = ($i % 2 );++$i;?>-->
                <!--<tr>-->
                    <!--<td align="center"><?php echo (date('Y-m-d H:i:s',$time['create_time'])); ?></td>-->
                    <!--<td align="center"><?php echo time_type()[$time['status']];?></td>-->
                <!--</tr>-->
            <!--<?php endforeach; endif; else: echo "" ;endif; ?>-->
            <!--</tbody>-->
        <!--</table>-->
    </div>
</div>
</body>
</html>