<?php
/*
* Copyright 2014 Baidu, Inc.
*
* Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with
* the License. You may obtain a copy of the License at
*
* Http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on
* an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the
* specific language governing permissions and limitations under the License.
*/

// 报告所有 PHP 错误
error_reporting(-1);

define('__VOD_CLIENT_ROOT', dirname(__DIR__));

// 设置VodClient, BosClient的Access Key ID、Secret Access Key和ENDPOINT
// 你仅需修改其中的ak、sk，其他部分请勿修改。
$my_credentials = array(
    'ak' => '177c75dc89a64e22b0786ee2cb3f5e04',
    'sk' => '456c5552473640168b51c3f424ce6872',
);
$g_vod_configs = array(
    'credentials' => $my_credentials,
    'endpoint' => 'http://vod.bj.baidubce.com',
);

$g_bos_configs = array(
    'credentials' => $my_credentials,
    'endpoint' => 'http://bj.bcebos.com',
);

// 设置log的格式和级别
$__handler = new \Monolog\Handler\StreamHandler(STDERR, \Monolog\Logger::DEBUG);
$__handler->setFormatter(
    new \Monolog\Formatter\LineFormatter(null, null, false, true)
);
\BaiduBce\Log\LogFactory::setInstance(
    new \BaiduBce\Log\MonoLogFactory(array($__handler))
);
\BaiduBce\Log\LogFactory::setLogLevel(\Psr\Log\LogLevel::DEBUG);
