<?php

if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

define('APP_STATUS', 'config');
define('APP_DEBUG', false);
define('BIND_MODULE','Admin');
define('APP_PATH','./app/');

// 引入ThinkPHP入口文件
require './_core/ThinkPHP.php';