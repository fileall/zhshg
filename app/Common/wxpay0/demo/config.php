<?php
//	$mysql_server_name="localhost"; //数据库服务器名称
//	$mysql_database="ynfc"; // 数据库的名字
//	$mysql_username="root"; // 连接数据库用户名
//	$mysql_password="123"; // 连接数据库密码
	
	
	$mysql_server_name="localhost"; //服务器地址
	$mysql_database="yx.0791jr.com"; // 数据库的名字
	$mysql_username="yx.0791jr_user"; // 连接数据库用户名
	$mysql_password="fAw4ZUPTmLyqbh3"; // 连接数据库密码
	 // 连接到数据库
    $conn=mysql_connect($mysql_server_name, $mysql_username,$mysql_password);
	mysql_select_db($mysql_database, $conn);
?> 