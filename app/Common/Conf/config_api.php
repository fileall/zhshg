<?php
return array(
    //服务器响应状态
    'Status_Code' => array(
        'success' => array(
            'code' => '000',
            'message' => '成功'
        ),
        'failed' => array(
            'code' => '001',
            'message' => '失败'
        ),
        'params_error' => array(
            'code' => '002',
            'message' => '参数错误'
        ),
        'server_error' => array(
            'code' => '003',
            'message' => '服务器异常'
        ),
        'authenticating_api_failed' => array(
            'code' => '004',
            'message' => '验证API请求失败'
        ),
        'api_not_found' => array(
            'code' => '005',
            'message' => '请求的API不存在'
        )
    ),

    'API_LIST' => array(

        /**** 用户API 开始 ****/
        /**** 用户API 开始 ****/
        //个人中心首页
        '9999' => array(
            'id' => '9999',
            'controller' => '\App\Controller\MemberController',
            'action' => 'member_info'
        ),
        //注册
        '10000' => array(
            'id' => '10000',
            'controller' => '\App\Controller\MemberController',
            'action' => 'reg'
        ),
        //登录
        '10001' => array(
            'id' => '10001',
            'controller' => '\App\Controller\MemberController',
            'action' => 'login'
        ),
        //设置个人资料
        '10002' => array(
            'id' => '10002',
            'controller' => '\App\Controller\MemberController',
            'action' => 'set_member'
        ),
        //获取短信验证码
        '10003' => array(
            'id' => '10003',
            'controller' => '\App\Controller\MemberController',
            'action' => 'get_yzm'
        ),
        //修改密码
        '10004' => array(
            'id' => '10004',
            'controller' => '\App\Controller\MemberController',
            'action' => 'updatepwd'
        ),

        //忘记密码
        '10005' => array(
            'id' => '10005',
            'controller' => '\App\Controller\MemberController',
            'action' => 'forgetpwd'
        ),
        //添加收藏
        '10006' => array(
            'id' => '10006',
            'controller' => '\App\Controller\MemberController',
            'action' => 'add_collection'
        ),




        //获取首页信息
        '20000' => array(
            'id' => '20000',
            'controller' => '\App\Controller\IndexController',
            'action' => 'index_info'
        ),
        //我的钱包
        '20001' => array(
            'id' => '20001',
            'controller' => '\App\Controller\WalletController',
            'action' => 'wallet'
        ),
        //银行卡展示
        '20002' => array(
            'id' => '20002',
            'controller' => '\App\Controller\WalletController',
            'action' => 'w_bank'
        ),
        //添加银行卡
        '20003' => array(
            'id' => '20003',
            'controller' => '\App\Controller\WalletController',
            'action' => 'add_bank'
        ),

        //解绑银行卡
        '20004' => array(
            'id' => '20004',
            'controller' => '\App\Controller\WalletController',
            'action' => 'del_bank'
        ),
        //币种展示。item_type1金元宝 2银元宝 3金果 4余额 5金币 6银币
        '20005' => array(
            'id' => '20005',
            'controller' => '\App\Controller\WalletController',
            'action' => 'w_all_bz'
        ),
        //余额提现
        '20006' => array(
            'id' => '20006',
            'controller' => '\App\Controller\WalletController',
            'action' => 'w_extract'
        ),








    ),
);