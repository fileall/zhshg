<?php
namespace Admin\Controller;
class InvestController extends AdminCoreController {
	public function _initialize()
    {
        parent::_initialize();
        $this->_mod = D('Invest');
        $this->set_mod('Invest');
    }

    public function _before_index()
    {
        $this->list_relation = true;
    }

    /**
     * 修改
     */
    public function edit()
    {
        $pk = $this->_mod->getPk();
        $id = I($pk, 'intval');
        $this->_mod->relation(true);
        $info = $this->_mod->find($id);
        $Member = D('Member');
        if (IS_POST) {
            if (false === $data = $this->_mod->create()) {
                IS_AJAX && $this->ajax_return(0, $this->_mod->getError());
                $this->error($this->_mod->getError());
            }
            $data['create_time'] = strtotime($data['create_time']);
            $data['invest_amount'] = abs($data['invest_amount']);

            //启动事务
            $this->_mod->startTrans();
            //更新投资记录
            $invest_op = $this->_mod->save($data);
            //核对余额修改
            $diff = $data['invest_amount'] - $info['invest_amount'];
            if($diff > 0){ //增加投资金额
                $balance = $Member->where(array('id'=>$info['member_id']))->getField('balance');
                ($diff > $balance) && $this->error('该用户的余额仅剩'.$balance.'元，无法完成投资修改！');
                $member_op = $Member->where(array('id'=>$info['member_id']))->setDec('balance',$diff);
                //修改借款的筹款数 增加
                $loan_op = D('Loan')->where(array('id'=>$info['loan_id']))->setInc('invest_amount',$diff);
            }elseif($diff < 0){
                $member_op = $Member->where(array('id'=>$info['member_id']))->setInc('balance',$diff);
                //修改借款的筹款数 减少
                $loan_op = D('Loan')->where(array('id'=>$info['loan_id']))->setDec('invest_amount',$diff);
            }else{
                //配合事务
                $member_op = $Member->where(array('id'=>$info['member_id']))->setInc('balance',$diff);
                //修改借款的筹款数 减少
                $loan_op = D('Loan')->where(array('id'=>$info['loan_id']))->setDec('invest_amount',$diff);
            }
            //资金流水记录修改
            $map = array(
                'order_id' => $info['order_id'],
                'item_id' => $info['id'],
                'log_type' => 4
            );
            $finance_op = D('Finance')->where($map)->setField(array('total'=>$data['invest_amount']));

            if($invest_op && $member_op && $loan_op && $finance_op){
                $this->_mod->commit();//成功则提交
                IS_AJAX && $this->ajax_return(1, L('operation_success'), '', 'edit');
                $this->success('修改成功');
            }else{
                $this->_mod->rollback();//不成功，则回滚
                IS_AJAX && $this->ajax_return(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }
        } else {
            $this->assign('info', $info);
            $this->assign('open_validator', true);
            if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1, '', $response);
            } else {
                $this->display();
            }
        }
    }
}