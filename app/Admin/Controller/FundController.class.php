<?php
namespace Admin\Controller;
class FundController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
    }

    public function index(){
        $this->display();
    }
    //资金流水
    public function finance(){
        $this->list_relation = true;
        $map = $this->_search_finance();
        $mod = D('Finance');
        !empty($mod) && $this->_list($mod, $map);
        $this->assign('finance_type',C('finance_type'));
        $this->display();
    }
    //充值记录
    public function recharge(){
        $map = $this->_search_recharge();
        $mod = D('Recharge');
        !empty($mod) && $this->_list($mod, $map);
        $this->display();
    }
    //提现记录
    public function withdrawal(){
        $this->list_relation = true;
        $map = $this->_search_recharge();
        $mod = D('Draw');
        !empty($mod) && $this->_list($mod, $map);
        $this->assign('withdrawal_status',C('withdrawal_status'));
        $this->display();
    }

    //驳回提现申请 ajax
    public function withdrawal_reject(){
        $id = I('id',0,'intval');
        $mod = D('Draw');
        $draw = $mod->field('status')->find($id);
        ($draw['status'] != 2) && $this->ajax_return(0, '只能操作审核中的申请');
        $mod->where(array('id'=>$id))->setField(array('status'=>1));
        $this->ajax_return(1, L('operation_success'));
    }
    //审核提现申请 ajax
    public function withdrawal_verify(){
        $id = I('id',0,'intval');
        $mod = D('Draw');
        $draw = $mod->field('status')->find($id);
        ($draw['status'] != 2) && $this->ajax_return(0, '只能操作审核中的申请');
        $mod->where(array('id'=>$id))->setField(array('status'=>3));
        $this->ajax_return(1, L('operation_success'));
    }

    //发放提现 ajax
    public function withdrawal_pay(){
        $id = I('id',0,'intval');
        $mod = D('Draw');
        $draw = $mod->field('status,member_id,fee')->find($id);
        ($draw['status'] != 3) && $this->ajax_return(0, '只能操作已审核的申请');
        //扣款
        $member = D('Member')->where(array('member_id'=>$draw['member_id']))->find();
        D('Member')->where(array('member_id'=>$draw['member_id']))->setDec('balance',$draw['fee']);
        //更新提款记录为已支付
        $mod->where(array('id'=>$id))->setField(array('status'=>4));
        //写入资金明细
        $data = array(
            'change' => $draw['fee'],
            'ex_total' => $member['balance'],
            'total' => $member['balance'] - $draw['fee'],
            'type' => '3',
            'member_id' => is_login(),
            'remark' => $member['nickname']."提现".$draw['fee']."元"
        );

        $this->ajax_return(1, L('operation_success'));
    }

    protected function _search_recharge(){
        $map = array();
        ($time_start = I('time_start','', 'trim')) && $map['create_time'][] = array('egt', strtotime($time_start));
        ($time_end = I('time_end','', 'trim')) && $map['create_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));
        ($update_time_start = I('update_time_start','', 'trim')) && $map['update_time'][] = array('egt', strtotime($time_start));
        ($update_time_end = I('update_time_end','', 'trim')) && $map['update_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));
        ($status = I('status','', 'trim')) && $map['status'] = $status;
        ($keyword = I('keyword','', 'trim')) && $map['order_id'] = array('like', '%'.$keyword.'%');

        $this->assign('search', array(
            'time_start' => $time_start,
            'time_end' => $time_end,
            'update_time_start' => $update_time_start,
            'update_time_end' => $update_time_end,
            'status'  => $status,
            'keyword' => $keyword,
        ));
        return $map;
    }

    protected function _search_withdrawal(){
        $map = array();
        ($time_start = I('time_start','', 'trim')) && $map['create_time'][] = array('egt', strtotime($time_start));
        ($time_end = I('time_end','', 'trim')) && $map['create_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));
        ($update_time_start = I('update_time_start','', 'trim')) && $map['update_time'][] = array('egt', strtotime($time_start));
        ($update_time_end = I('update_time_end','', 'trim')) && $map['update_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));
        ($status = I('status','', 'trim')) && $map['status'] = $status;
        ($keyword = I('keyword','', 'trim')) && $map['order_id'] = array('like', '%'.$keyword.'%');

        $this->assign('search', array(
            'time_start' => $time_start,
            'time_end' => $time_end,
            'update_time_start' => $update_time_start,
            'update_time_end' => $update_time_end,
            'status'  => $status,
            'keyword' => $keyword,
        ));
        return $map;
    }

    protected function _search_finance(){
        $map = array();
        ($time_start = I('time_start','', 'trim')) && $map['create_time'][] = array('egt', strtotime($time_start));
        ($time_end = I('time_end','', 'trim')) && $map['create_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));
        ($type = I('type','', 'trim')) && $map['type'] = $type;
        ($keyword = I('keyword','', 'trim')) && $map['remark'] = array('like', '%'.$keyword.'%');

        $this->assign('search', array(
            'time_start' => $time_start,
            'time_end' => $time_end,
            'type'  => $type,
            'keyword' => $keyword,
        ));
        return $map;
    }
}