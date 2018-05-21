<?php

namespace Admin\Controller;



/**

 * 商家消费详情类

 * Class AccountController

 * @package Admin\Controller

 */

class AccountShopController extends AdminCoreController

{

    public function _initialize()

    {

        parent::_initialize();

        $this->_mod=D('AccountShop');

        $this->set_mod('AccountShop');

        $this->_member=D('Member');

    }



    public function index() {
        $map = $this->_search();

        $mod = $this->_mod;

        !empty($mod) && $this->_list($mod, $map);

        $this->display();

    }



    public function _search()

    {
        $map = [];

        ($uid = I('uid','','intval'))   && $map['shop_id']          = $uid;//商家id

        ($type = I('type','','intval'))   && $map['type']        = $type;

        ($time_start = I('time_start')) && $map['add_time'][]   = ['egt',strtotime($time_start)];

        ($time_end = I('time_end'))     && $map['add_time'][]   = ['elt',strtotime($time_end) + 86399];



        $this->assign('search',[

            'time_start'    => $time_start,

            'time_end'      => $time_end,

        ]);

        !empty($uid) && $this->assign('uid',$uid);

        return $map;

    }





    public function _before_index() {

        $uid=I('uid');//商家id

        $member=M('merchant')->where(['id'=>$uid])->field('title as nickname,tel as mobile')->find();

        $this->assign('member',$member);

    }

}