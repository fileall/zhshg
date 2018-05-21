<?php
namespace Admin\Controller;
class DaystockController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Daystock');
		$this->set_mod('Daystock');
    }

    public function _before_index() {

        //默认排序
        //$this->order ='create_time DESC';
        $this->list_relation = true;

        $this->assign('loan_config', C('daystock_status'));
    }

    protected function _search() {
        $map = array();
        //'status'=>1
        ($time_start = I('time_start','', 'trim')) && $map['create_time'][] = array('egt', strtotime($time_start));
        ($time_end = I('time_end','', 'trim')) && $map['create_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));
        ($total_min = I('total_min','', 'trim')) && $map['total'][] = array('egt', $total_min);
        ($total_max = I('total_max','', 'trim')) && $map['total'][] = array('elt', $total_max);
        ($rates_min = I('rates_min','', 'trim')) && $map['rates'][] = array('egt', $rates_min);
        ($rates_max = I('rates_max','', 'trim')) && $map['rates'][] = array('elt', $rates_max);

        ($status = I('status','', 'trim')) && $map['status'] = $status;
        ($keyword = I('keyword','', 'trim')) && $map['title'] = array('like', '%'.$keyword.'%');
        $this->assign('search', array(
            'time_start' => $time_start,
            'time_end' => $time_end,
            'status' =>$status,
            'keyword' => $keyword,
        ));
        return $map;
    }

    public function _before_edit() {
        $id = I('id',0,'intval');
        $member_id = $this->_mod->where(array('id'=>$id))->getField('member_id');
        $user = D('Member')->where(array('id'=>$member_id))->find();
        $this->assign('user',$user);
        $this->assign('daystock_status',C('daystock_status'));
    }

    public function verify(){
        $id = I('id',0,'intval');
        $status = I('status',3,'intval');
        $info = $this->_mod->where(array('id'=>$id))->relation('member')->find();
        if(IS_POST){
            ($info['status'] > 2) && $this->error('该申请已经审核通过，无须再次审核！');
            $data['status'] = $status;
            $this->_mod->where(array('id'=>$id))->save($data);
            $this->promote_daystock($id);
            $this->success('该申请已审核通过',U('daystock/index'));
        }else{
            $this->assign('info',$info);
            $this->assign('loan_config', C('loan_config'));
            $this->display();
        }
    }

    //发放推广分成-股票配资
    public function promote_daystock($id=0){
        $Daystock = D('Daystock');
        $Finance = D('Finance');
        $daystock = $Daystock->relation('member')->find($id);

        $map = array(
            'member_id'=>$daystock['invite_id'],
            'log_type' => 15,
            'item_id' => $id,
        );
        $finance_record = $Finance->field('id')->where($map)->find();

        if(!$finance_record){
            //配资分成，从服务费中抽成（在配置中）
            $promote_amount = $daystock['total']/10000 * C('promote_config.day_stock_rate') * C('daystock_range.manage_fee')*$daystock['deadline'];
            $promote_amount = sprintf("%.2f", $promote_amount);
            $Finance->startTrans();
            $member_op = D('Member')->where(array('id'=>$daystock['invite_id']))->setInc('balance',$promote_amount);
            $finance_data = array(
                'order_id' => make_order_id('Finance'),
                'total' => $promote_amount,
                'log_type' => 15,
                'member_id' => $daystock['invite_id'],
                'status' => 1,
                'remark' => $daystock['nickname'].'申请日日盈的提成',
                'item_id' => $daystock['id'],
            );
            $finance_data = $Finance->create($finance_data);
            $finance_op = $Finance->add($finance_data);
            if($member_op && $finance_op){
                $Finance->commit();
            }else{
                $Finance->rollback();
            }
        }
    }

    
}