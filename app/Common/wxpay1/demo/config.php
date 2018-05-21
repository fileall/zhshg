<?php
//	$mysql_server_name="localhost"; //数据库服务器名称
//	$mysql_database="ynfc"; // 数据库的名字
//	$mysql_username="root"; // 连接数据库用户名
//	$mysql_password="123"; // 连接数据库密码
	
	
	$mysql_server_name="101.200.72.164"; //服务器地址
	$mysql_database="zhshg"; // 数据库的名字  
	$mysql_username="zhshg_user"; // 连接数据库用户名
	$mysql_password="qDyQLGXHUnn9jb4"; // 连接数据库密码
	
	 // 连接到数据库
    try {
	    $conn = new PDO("mysql:host=$mysql_server_name;dbname=$mysql_database", $mysql_username, $mysql_password);
	}catch(PDOException $e){
	    echo $e->getMessage();
	}
	
	
//	mysql_select_db($mysql_database, $conn);
?> 