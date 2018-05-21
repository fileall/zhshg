<?php
include 'BaiduBce.phar';
require 'SampleConf.php';

use BaiduBce\BceClientConfigOptions;
use BaiduBce\Services\Vod\VodClient;
use BaiduBce\Services\Bos\BosClient;

//调用配置文件中的参数
global $g_vod_configs;
global $g_bos_configs;
//新建vodClient和bosClient
$vodClient = new VodClient($g_vod_configs, $g_bos_configs);
$bosClient = new BosClient($g_bos_configs);

print_r($vodClient->getMedia("mda-hh3m6vwpukz2a0um"));