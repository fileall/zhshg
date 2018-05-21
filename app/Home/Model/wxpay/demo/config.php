<?php
//	$mysql_server_name="localhost"; //数据库服务器名称
//	$mysql_database="ynfc"; // 数据库的名字
//	$mysql_username="root"; // 连接数据库用户名
//	$mysql_password="123"; // 连接数据库密码
	
	
	$mysql_server_name="120.26.197.102"; //数据库服务器名称
	$mysql_database="ynfc.com"; // 数据库的名字
	$mysql_username="ynfc.com"; // 连接数据库用户名
	$mysql_password="ynfc2015"; // 连接数据库密码
	 // 连接到数据库
    $conn=mysql_connect($mysql_server_name, $mysql_username,$mysql_password);
	mysql_select_db($mysql_database, $conn);
?> 