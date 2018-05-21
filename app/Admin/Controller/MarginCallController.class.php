<?php
namespace Admin\Controller;
class MarginCallController extends AdminCoreController {
	public function _initialize()
    {
        parent::_initialize();
        $this->_mod = D('MarginCall');
        $this->set_mod('MarginCall');
    }

    public function _before_index(){
        $this->list_relation = true;
    }

    public function _before_add(){
        $map['status'] = array('IN','6,10');
        $loan = D('Loan')->field('id,title')->where($map)->select();
        $this->assign('loan',$loan);
    }

    public function _after_insert($id){
        //给帐户补充资金
        $margin_call = D('MarginCall')->relation('loan')->find($id);
        $loan_op = M('Loan')->where(array('id'=>$margin_call['loan_id']))->setInc('market_value',$margin_call['amount']);
        //生成资金流水记录
        //资金流水记录
        $finance_data = array(
            'order_id' => make_order_id('Finance'),
            'total' => $margin_call['amount'],
            'log_type' => 13,
            'member_id' => $margin_call['member_id'],
            'status' => 1,
            'remark' => $margin_call['title'].'('.$margin_call['loan_id'].')的保证金补交',
            'item_id' => $margin_call['id'],
        );
        $Finance = D('Finance');
        if($finance_data = $Finance->create($finance_data)){
            $finance_op = $Finance->add($finance_data);
        }else{
            $this->error($Finance->getError());
        }
    }

    public function _before_edit(){
        $map['status'] = array('IN','6,10');
        $loan = D('Loan')->field('id,title')->where($map)->select();
        $this->assign('loan',$loan);
    }

    public function _before_update($data = ''){
        //先减掉保证金额度
        $margin_call = D('MarginCall')->relation('loan')->find($data['id']);
        $loan_op = M('Loan')->where(array('id'=>$margin_call['loan_id']))->setDec('market_value',$margin_call['amount']);
    }



}