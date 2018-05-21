<?php

namespace Admin\Controller;
/**
 * 金果兑换劵
 * Class WithdrawMemberController
 * @package Admin\Controller
 */
class WithdrawQdFruitController extends AdminCoreController
{
    public function _initialize()
    {
        parent::_initialize();
        $this->_mod = D('withdrawQdFruit');
        $this->set_mod('withdrawQdFruit');

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
        $listNickname = M('member')->where(['id' => ['in', $listIds]])->field('id,nickname')->select();
        $listNickname2 = [];
        foreach ($listNickname as $a) {
            $listNickname2[$a['id']] = $a;
        }
        foreach ($list as $key => $value) {
            $list[$key]['nickname'] = $listNickname2[$value['uid']]['nickname'];
        }
        return $list;
    }
    //审核
    public function sh()
    {
        $mod = $this->_mod;
        $id = I('id', 'intval');
        $status = I('status', 0, 'intval');//1通过2驳回
        (!$id || !in_array($status, [1, 2])) && $this->ajax_return(0, L('operation_failure'));
        $info = $mod->find($id);
        $uid= $info['uid'];
        $oid= $info['id'];
        $memos= $info['memos'];
        $face_value= $info['face_value'];

        (1 != $info['status']) && $this->ajax_return(0, L('operation_failure'));
        $member_model = M('member');
        $start = M();
        $start->startTrans();
        if ($status == 2) {//驳回=》加回金果
            $res_money = $member_model->where(['id' => $info['uid']])->setInc('gold_fruit', $info['totalprices']);
            if (!$res_money) {
                $start->rollback();
                $this->ajax_return(0, L('operation_failure'));
            }
            //会员明细
            $arr = [
                'type' => 3,//币种1工资2金元宝3金果4银币
                'uid' => $info['uid'],
                'totalprices' => $info['totalprices'],
                'change_desc' => '金果兑换驳回',
                'add_time' => $_SERVER['REQUEST_TIME']
            ];
            $res_account = M('account')->add($arr);
            if (!$res_account) {
                $start->rollback();
                $this->ajax_return(0, L('operation_failure'));
            }
            $after_status = 3;
        } else {//通过
            $arr = [
                'type' => 3,//币种1工资2金元宝3金果4银币
                'uid' => $info['uid'],
                'totalprices' => -$info['totalprices'],
                'change_desc' => '金果兑换通过',
                'add_time' => $_SERVER['REQUEST_TIME']
            ];
            $res_account = M('account')->add($arr);
            if (!$res_account) {
                $start->rollback();
                $this->ajax_return(0, L('operation_failure'));
            }
            $valuesData = array();
            for ($i = 0; $i < $info['nums']; $i++) {
                $valuesData[] = array(
                    'uid'         => $uid,
                    'oid'         => $oid,
                    'code'        => rand(10000000,99999999),
                    'totalprices' => $face_value,
                    'memos'       => $memos,
                    'status'      => 1,
                    'add_time'    => $_SERVER['REQUEST_TIME']
                );
            }
            $db2 = M('fruit_coupon')->addAll($valuesData); //dump($a);die;
            if (!$db2) {
                $start->rollback();
                $this->ajax_return(0, L('operation_failure'));
            }
            $after_status = 2;

        }
        //改变申请状态
        $res_status = $mod->where(array('id' => $id))->setField('status', $after_status);

        if ($res_status) {
            $mod->commit();
            $this->ajax_return(1, L('operation_success'), '', 'apply');
        } else {
            $mod->rollback();
            $this->ajax_return(0, L('operation_failure'));
        }

    }
    //金果兑换详情
     public function check(){
        $oid = I('oid');
        $uid = I('uid');
        $nickname = M('member')->where(['id'=>$uid])->getField('nickname');
        $list = M('fruit_coupon')->where(['oid'=>$oid])->select();
        foreach($list as $key=>$value){
            $list[$key]['nickname'] = $nickname;
        }
        $this->assign('list',$list);
        $this->display();
     }



}