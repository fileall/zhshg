<?php
return array(
	//'配置项'=>'配置值'
 
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => '127.0.0.1', // 服务器地址127.0.0.1  localhost
	'DB_NAME'   => 'zhshg', // 数据库名
	'DB_USER'   => 'zhshg_user', // 用户名
	'DB_PWD'    => 'qDyQLGXHUnn9jb4', // 密码
	'DB_PORT'   => 3306, // 端口
	'DB_PREFIX' => 'jrkj_', // 数据库表前缀
	'DB_CHARSET'=> 'utf8', // 字符集
	'DB_DEBUG'  =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志 3.2.3新增
    'API_KEY' => "n'NI&u#+lFA0y@;$6Wj=5(~9",//用于验证APP的KEY
	
	
	'TAG_NESTED_LEVEL' =>3,
    'LOAD_EXT_CONFIG' => 'zdq,config_api',
//  'SHOW_PAGE_TRACE'=>1,
//    'LOAD_EXT_FILE'=>'function',//自动加载公共函数

	//模板替换
	'TMPL_PARSE_STRING'  =>array(
		 '__PUBLIC_DEFAULT__' => __ROOT__.'/theme/default/', // 前台的样式文件目录
		 '__PUBLIC_ADMIN__' => __ROOT__.'/theme/admin/', // 后台的样式文件目录
		 //'__JS__'     => '/Public/JS/', // 增加新的JS类库路径替换规则
		 '__UPLOAD__' => __ROOT__.'/data/attachment', // 增加新的上传路径替换规则
        '__MB__' => __ROOT__.'/theme/mobile/', // 临时用前台样式目录
        '__STATIC__' => __ROOT__.'/theme/default/', // 后台的样式文件目录(web端用)

    ),
	
	'URL_CASE_INSENSITIVE' =>true,		//大小写不敏感
	'st_encryption_key' => 'lrioucskteorne',	//加密密钥
	'userid_encryption_key' => 'HDPY_YB@#$!User_ID',//用户ID加密密钥
	
	'LANG_SWITCH_ON' => true,		// 开启语言包功能
	'LANG_AUTO_DETECT' => true, // 自动侦测语言 开启多语言功能后有效
	'LANG_LIST' => 'en-us,zh-cn,zh-tw', //必须写可允许的语言列表
	'VAR_LANGUAGE'     => 'l', // 默认语言切换变量
    'DATA_PATH' => __ROOT__.'./data/',
	'adboard_allow_type' => array(
		'image' => '图片',
		'text' => '文字',
		'code' => '代码',
		'flash' => 'Flash',
	),
	
    'MAIL_ADDRESS'=>'2900220776@qq.com', // 邮箱地址
    'MAIL_SMTP'=>'smtp.qq.com', // 邮箱SMTP服务器
    'MAIL_LOGINNAME'=>'2900220776', // 邮箱登录帐号
    'MAIL_PASSWORD'=>'', // 邮箱密码

    'allow_zftype' => array('','金元宝','银元宝','金果'),
    /*项目配置*/
    'apply_city_list' => array(
        1 => '北京',
        2 => '上海',
        3 => '广州',
    ),
    'apply_age_list' => array(
        1 => '3-6岁',
        2 => '7-9岁',
        3 => '10-12岁',
    ),
    
	
//	'SESSION_OPTIONS'         =>  array(
//        'name'                =>  'ZYJASHG',                    //设置session名
//        'expire'              =>  24*3600*3,                      //SESSION保存15天 24*3600*15
//        'use_trans_sid'       =>  1,                               //跨页传递
//        'use_only_cookies'    =>  0,                               //是否只开启基于cookies的session的会话方式
//    ),

    //微信=>支付调接口时&&微信扫描
    'WX_CONFIG' => array(
        'appid' => 'wx5c1299e0d98a447e',
        'appsecret' => 'a5e57e74278b59658b0efcb0b5776a8d'
    ),


    //微信=>模板消息推送&&第三方登录
    'wx_pay_config' => array(
        'pay_gateway' => 'https://api.mch.weixin.qq.com/pay/unifiedorder',//https:\/\/api.mch.weixin.qq.com\/pay\/unifiedorder
        'appid' => 'wxad4fc1ee40754ebf',//开发者APPID
        'appsecret' => '834d3d5bc0b5d0dfba43f2f01275ff8e',//开发者密钥
        'mch_id' => '1481963862', //0微信分配的商户编号1403842702
        'key' => '981d1ac4124b1aea7222be93825475e5',//0用于生成签名用的商户API KEY 76f75fec654e59fc20a55ba4caebede6
    ),

    //支付宝支付
    'zfb_pay_config' => array(
        'service' => 'mobile.securitypay.pay',//接口名称
        'partner' => '2088421920873471',//合作者身份ID
        'app_id' => '2017061907521372',//APPID 秒装2017020805570963
        'sign_type' => 'RSA', //签名方式
        'seller_id' => 'myg888888@163.com', //卖家支付宝账号
        '_input_charset' => 'utf-8',//参数编码字符集
        'payment_type' => 1,//支付类型 默认为1
    ),

    //会员明细的币种 币种1工资2金元宝3金果4银币
    'bz'=>array(
        '1'=>'工资',
        '2'=>'元宝',
        '3'=>'金果',
        '4'=>'银币',
    ),

 




    

);
