<?php

include 'BaiduBce.phar';
require 'SampleConf.php';

use BaiduBce\BceClientConfigOptions;
use BaiduBce\Services\Vod\VodClient;
use BaiduBce\Services\Bos\BosClient;

class VodClientSample
{
    private $client;
    public static $ak = '177c75dc89a64e22b0786ee2cb3f5e04';
    public static $sk = '456c5552473640168b51c3f424ce6872';

    public function __construct()
    {
        // 设置VodClient, BosClient的Access Key ID、Secret Access Key和ENDPOINT
        // 你仅需修改其中的ak、sk，其他部分请勿修改。
        $my_credentials = array(
            'ak' => self::$ak,
            'sk' => self::$sk,
        );
        $g_vod_configs = array(
            'credentials' => $my_credentials,
            'endpoint' => 'http://vod.bj.baidubce.com',
        );
        $g_bos_configs = array(
            'credentials' => $my_credentials,
            'endpoint' => 'http://bj.bcebos.com',
        );
        //新建vodClient和bosClient
        $this->client = new VodClient($g_vod_configs, $g_bos_configs);
        //$bosClient = new BosClient($g_bos_configs);
    }

    public function getVod(){
        return $this->client;
    }

}