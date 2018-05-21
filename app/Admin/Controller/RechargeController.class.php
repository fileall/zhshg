<?php
namespace Admin\Controller;
class RechargeController extends AdminCoreController {
	public function _initialize()
    {
        parent::_initialize();
        $this->_mod = D('Recharge');
        $this->set_mod('Recharge');
    }

    public function _before_index()
    {
        $this->assign('recharege_type',C('recharege_type'));
        $this->list_relation = true;
    }

    public function _before_add(){
        $member_list = M('Member')->limit(100)->select();
        $this->assign('member_list',$member_list);
        $this->assign('recharege_type',C('recharege_type'));
    }

    public function add() {
        $mod = D($this->_name);
        if (IS_POST) {
            $data = I('post.');
            $recharge_data = array(
                'fee'=> $data['fee'],
                'member_id' => $data['member_id'],
                'order_id' => make_order_id(),
                'type' => $data['type'],
            );
            //启动事务
            $Finance = D('Finance');
            $this->_mod->startTrans();
            if($recharge_data = $mod->create($recharge_data)){
                $recharge_op = $mod->add($recharge_data);
            }

            $recharge_info_data = array(
                'recharge_id' => $recharge_op,
                'due_bank' => $data['due_bank'],
                'pay_bank' => $data['pay_bank'],
                'card_num' => $data['card_num'],
                'card_username' => $data['card_username'],
                'bank_order_id' => $data['bank_order_id'],
                'alipay_account' => $data['alipay_account'],
                'baofoo_pay_id' => $data['baofoo_pay_id'],
            );

            $RechargeInfo = D('RechargeInfo');
            if($recharge_info_data = $RechargeInfo->create($recharge_info_data)){
                $recharge_info_op = $RechargeInfo->add($recharge_info_data);
            }

            //资金流水记录
            $finance_data = array(
                'order_id' => make_order_id('Finance'),
                'total' => $data['fee'],
                'log_type' => 1,
                'member_id' => $data['member_id'],
                'status' => 1,
                'remark' => '充值订单：' . $recharge_data['order_id'] . '的充值金额',
                'item_id' => $recharge_op,
            );

            if ($finance_data = $Finance->create($finance_data)) {
                $finance_op = $Finance->add($finance_data);
            } else {
                $this->error($Finance->getError());
            }
            //修改充值订单状态
            $recharge_op = $this->_mod->where(array('id' => $recharge_op))->setField(array('status' => 1));
            //增加帐户余额
            $member_op = M('Member')->where(array('member_id' => $data['member_id']))->setInc('balance', $data['fee']);
            if ($recharge_op && $recharge_info_op && $finance_op && $recharge_op && $member_op) {
                $this->_mod->commit();//成功则提交
                IS_AJAX && $this->ajax_return(1, L('operation_success'), '', 'add');
                $this->success(L('operation_success'));
            } else {
                $this->_mod->rollback();//不成功，则回滚
                IS_AJAX && $this->ajax_return(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }
        } else {
            $member_list = M('Member')->limit(100)->select();
            $due_bank = C('due_bank');
            $this->assign('due_bank',$due_bank);
            $this->assign('member_list',$member_list);
            $this->assign('recharege_type',C('recharege_type'));
            $this->assign('open_validator', true);
            if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1,'',$response,'add');
            } else {
                $this->display();
            }
        }
    }

    public function edit()
    {
        $mod = D($this->_name);
        $pk = $mod->getPk();
        if (IS_POST) {
            $data = I('post.');
            $recharge_data = array(
                'id' => $data['id'],
                'fee'=> $data['fee'],
                'member_id' => $data['member_id'],
            );
            $recharge_op = $mod->save($recharge_data);
            $recharge_info_data = array(
                'due_bank' => $data['due_bank'],
                'pay_bank' => $data['pay_bank'],
                'card_num' => $data['card_num'],
                'card_username' => $data['card_username'],
                'bank_order_id' => $data['bank_order_id'],
                'alipay_account' => $data['alipay_account'],
                'baofoo_pay_id' => $data['baofoo_pay_id'],
            );
            $RechargeInfo = D('RechargeInfo');
            if($recharge_info_data = $RechargeInfo->create($recharge_info_data)){
                $recharge_info_op = $RechargeInfo->where(array('recharge_id'=>$data['recharge_id']))->save($recharge_info_data);
            }
            IS_AJAX && $this->ajax_return(1, L('operation_success'), '', 'edit');
            $this->success(L('operation_success'));
        } else {
            $id = I('id',0, 'intval');
            $mod->relation(true);

            $info = $this->_mod->find($id);
            $member_list = M('Member')->limit(100)->select();
            $due_bank = C('due_bank');
            $this->_mod->relation(true);
            $this->assign('due_bank',$due_bank);
            $this->assign('member_list',$member_list);
            $this->assign('recharege_type',C('recharege_type'));
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

    public function show($id = 0){
        $id = empty($id)?I('id',0, 'intval'):$id;
        $this->_mod->relation(true);
        $info = $this->_mod->find($id);
        if(IS_POST){
            $Finance = D('Finance');
            //查看是否已充过值
            $finance_record = $Finance->where(array('log_type'=>1,'item_id'=>$id,'status'=>1))->count();
            if(!$finance_record) {
                $finance_record_wait_id = $Finance->where(array('log_type'=>1,'item_id'=>$id,'status'=>0))->getField('id');
                //启动事务
                $this->_mod->startTrans();
                if($finance_record_wait_id){
                    $finance_op = $Finance->where(array('id'=>$finance_record_wait_id))->setField(array('status'=>1));
                }else{
                    //资金流水记录
                    $finance_data = array(
                        'order_id' => make_order_id('Finance'),
                        'total' => $info['fee'],
                        'log_type' => 1,
                        'member_id' => $info['member_id'],
                        'status' => 1,
                        'remark' => '充值订单：' . $info['order_id'] . '的充值金额',
                        'item_id' => $info['id'],
                    );
                    if ($finance_data = $Finance->create($finance_data)) {
                        $finance_op = $Finance->add($finance_data);
                    } else {
                        $this->error($Finance->getError());
                    }
                }

                //修改充值订单状态
                $recharge_op = $this->_mod->where(array('id' => $id))->setField(array('status' => 1));
                //增加帐户余额
                $member_op = M('Member')->where(array('id' => $info['member_id']))->setInc('balance', $info['fee']);
                if ($finance_op && $recharge_op && $member_op) {
                    $this->_mod->commit();//成功则提交
                    IS_AJAX && $this->ajax_return(1, L('operation_success'), '', 'show');
                    $this->success(L('operation_success'));
                } else {
                    $this->_mod->rollback();//不成功，则回滚
                    IS_AJAX && $this->ajax_return(0, L('operation_failure'));
                    $this->error(L('operation_failure'));
                }
            }else{
                IS_AJAX && $this->ajax_return(0,'该订单已充过值');
                $this->error('该订单已充过值');
            }

        }else{
            $this->assign('info', $info);
            $this->assign('open_validator', true);
            $this->assign('due_bank',C('due_bank'));
            if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1, '', $response);
            } else {
                $this->display();
            }
        }

    }

}