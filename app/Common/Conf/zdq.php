<?php
return array(
    'buyer_bind_type' => array(
        1 => '淘宝',
    ),

    'bind_status' => array(
        1 => '启用',
        2 => '未启用',
        3 => '禁用'
    ),

    'task_type' => array(
        1 => '淘宝远程单',
        2 => '淘宝垫付单',
        4 => 'app注册单',
        5 => '微信关注单',
        6 => '网站投票单',
        7 => '网站注册单',
        8 => '京东单',
    ),
    'task_status' => array(
        0 => '下架',
        1 => '上架',
        2 => '编辑',
        3 => '待审核',
        4 => '上架',
        5 => '刷单',
        6 => '商家确认',
        7 => '刷客确认',
        8 => '已完成'
    ),

    'apply_status' => array(
        1 => '退回',
        2 => '审核中',
        3 => '审核通过',
        4 => '订单待确认',
        5 => '订单确认',
        6 => '回款待确认',
        7 => '回款已确认',
    ),
    
    'shtype' => array(
        1 => '快递',
        2 => '自提',
    ), 
	 
    'apply_order_status' => array(
        1 => '退回',
        2 => '等待审核',
        3 => '审核通过',
    ),
    
	'zftype' =>array(
	    0 => '未选择',
	    1 => '<span style="color:green;">余额支付</span>',
	    2 => '<span style="color:red;">会员卡支付</span>',
	    3 => '<span style="color:orange;">微信支付</span>',
	    4 => '<span style="color:purple;">支付宝支付</span>',
	),
	
    'order_status' =>array(
        1 => '待付款',
        2 => '待发货',
        3 => '待收货',
        4 => '待评价',
        5 => '退款中',
        6 => '退款失败',
        7 => '退款成功',
        8 => '交易成功',
        9 => '交易取消',
	),
    
	 'activity_status' =>array(
	    0 => '已结束',
        1 => '正在活动',
        2 => '未开始',      
	),
	

    'buyer_bind_rank' => array(
        1 => '一心',
        2 => '二心',
        3 => '三心',
        4 => '四心',
        5 => '五心',
        6 => '钻号',
    ),

    'task_terminal' => array(
        1 => '电脑单',
        2 => '手机单',
    ),
    
	'pscore' => array(
        1 => '差评',
        2 => '中评',
        3 => '好评',
    ),
    
	'member_recharge_status' =>array(
	    1 => '消费',
	    2 => '充值',
	    3 => '提现',
	),
	
	'integral_type' =>array(
	    1 => '兑换奖品',
	    2 => '兑换优惠券',
	    3 => '注册送积分',
	),
    
    
    'account_safe' =>array(
	    'mobile' => 20,
	    'email' => 10,
	    'paypassword' => 30,
	    'password'=>10,
	    'realname'=>10,
	    'avatar'=>10,
	    'nickname'=>5,
	    'address' =>15,
	),
	
	'wuliu' =>array(
	    '国通快递' => 'GTO',
	    '汇丰物流' => 'HFWL',
	    '顺丰快递' => 'SF',
	    '速通物流' => 'ST',
	    '申通快递' => 'STO',
	    '圆通速递' => 'YTO',
	    '邮政平邮/小包' => 'YZPY',
	    '中通速递' =>  'ZTO',
	    '瑞丰速递' => 'RFEX',
	    '百世快运' => 'BTWL',
	    '快捷速递' => 'FAST',
	    '好来运快递' => 'HYLSD',
	    '龙邦快递' =>'LB',
	),
	
	//微博配置
	'weibo'=>array(
		"WB_AKEY"=>'555787787' , 
		"WB_SKEY"=>'a53360a1b8fa18c827d36482a818df76',
		"WB_CALLBACK_URL"=>'http://qmyxsc.com/index.php?m=Home&c=Member&a=test',	
	),
);