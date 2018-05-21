<?php
namespace Admin\Controller;
class MemberVipController extends AdminCoreController {
	public function _initialize()
    {
        parent::_initialize();
        $this->_mod = D('MemberVip');
        $this->set_mod('MemberVip');
    }

    public function _before_index()
    {
        $this->list_relation = true;
    }

    public function _before_add(){
        $member_id = I('member_id',0,'intval');
		$name = M('Member') -> where(array('id'=>$member_id,'status'=>1)) -> getField('realname');
		$this -> assign('name',$name); 
        $this->assign('member_id',$member_id);
    }

    public function _before_edit(){
        $member_id = I('member_id',0,'intval');
        $member_vip = $this->_mod->where(array('member_id'=>$member_id))->find();
        $this->assign('member_vip',$member_vip);
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