<?php

return array(
	//'配置项'=>'配置值'
	"LOAD_EXT_FILE"=>"templet", 
	//模板替换
	'TMPL_PARSE_STRING'  =>array(
		 '__STATIC__' => __ROOT__.'/theme/default/', // 后台的样式文件目录
		 '__PC__' => __ROOT__.'/pc/', // 临时用前台样式目录
	),
    'TOKEN_ON'      =>    true,
	
	'URL_MODEL' => 0,
    'pagesize' => 8,
	'tech' => '技术支持：<a style=" color:#fff" href="http://www.0791jr.com" target="_blank">嘉瑞科技</a>',
	'ERROR_PAGE' => '/404.html',
	
	// 配置邮件发送服务器
    'MAIL_HOST' =>'smtp.qq.com',//smtp服务器的名称
    'MAIL_SMTPAUTH' =>TRUE, //启用smtp认证
    'MAIL_USERNAME' =>'2900220776@qq.com',//你的邮箱名
    'MAIL_FROM' =>'2900220776@qq.com',//发件人地址
    'MAIL_FROMNAME'=>'全民英雄',//发件人姓名
    'MAIL_PASSWORD' =>'mifengcn12',//邮箱密码
    'MAIL_CHARSET' =>'utf-8',//设置邮件编码
    'MAIL_ISHTML' =>TRUE, // 是否HTML格式邮件
    //教师按教龄区分
    'JIAOLING' => array(
        array('name'=>'教龄','start'=>0,'end'=>100),
        array('name'=>'0-3年','start'=>0,'end'=>3),
        array('name'=>'3-6年','start'=>3,'end'=>6),
        array('name'=>'6-10年','start'=>6,'end'=>10),
        array('name'=>'10-15年','start'=>10,'end'=>15),
        array('name'=>'15-25年','start'=>15,'end'=>25),
        array('name'=>'25-35年','start'=>25,'end'=>35),
        array('name'=>'35-45年','start'=>35,'end'=>45)
    )


);