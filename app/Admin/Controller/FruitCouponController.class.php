<?php

namespace Admin\Controller;
/**
 * 会员提现
 * Class WithdrawMemberController
 * @package Admin\Controller
 */
class FruitCouponController extends AdminCoreController
{
    public function _initialize()
    {
        parent::_initialize();
        $this->_mod = D('FruitCoupon');
        $this->set_mod('FruitCoupon');

    }

    //搜索
    public function _search()
    {
        $map = array();
        ($time_start = I('time_start', '', 'trim')) && $map['add_time'][] = array('egt', strtotime($time_start));
        ($time_end = I('time_end', '', 'trim')) && $map['add_time'][] = array('elt', strtotime($time_end) + (24 * 60 * 60 - 1));
//        $query = M('Member')->field('id')->where(['mobile'=>['like',"%$keywords%"]])->select(false);
        ($keywords = I('keywords', '', 'trim')) && $map['_string'] = "uid in (select id from jrkj_member where nickname like '%" . $keywords . "%')" ;
        $search = array(
            'time_start' => $time_start,
            'time_end' => $time_end,
            'keywords' => $keywords,
        );

        $this->assign('search', $search);
        return $map;
    }

    public function _before_index()
    {
        $sort = 'uid';
        $this->assign('sort', $sort);

    }

    public function _after_list($list)
    {
        if (empty($list)) {
            return $list;
        }
        foreach ($list as $k => $v) {
            $listIds[] = $v['uid'];
        }
        $listMobile = M('member')->where(['id' => ['in', $listIds]])->field('id,nickname')->select();
        $listMobile2 = [];
        foreach ($listMobile as $a) {
            $listMobile2[$a['id']] = $a;
        }
        foreach ($list as $key => $value) {
            $list[$key]['nickname'] = $listMobile2[$value['uid']]['nickname'];
        }
        return $list;
    }




}