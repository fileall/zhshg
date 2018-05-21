<?php
namespace Admin\Controller;
class LoanController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Loan');
		$this->set_mod('Loan');
    }

    public function _before_index() {

        //状态信息
        $this->assign('loan_status',C('loan_status'));

        //默认排序
        $this->sort = 'ordid ASC,';
        $this->order ='create_time DESC';
        $this->list_relation = true;

        $this->assign('loan_config', C('loan_config'));
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
        $this->assign('loan_config',C('loan_config'));

        //调操盘手信息
        $trader = M('Trader')->field('id,username')->select();
        //查找保证金记录
        $finance = M('Finance')->where(array('item_id'=>$id))->find();
        $this->assign('finance',$finance);
        $this->assign('trader',$trader);
    }

    public function _before_update($data) {
        $data['start_time'] = strtotime(I('start_time','','trim'));
        $data['end_time'] = strtotime(I('end_time','','trim'));
        return $data;
    }
    //结算
    public function verify(){
        $id = I('id',0,'intval');
        $info = $this->_mod->where(array('id'=>$id))->relation('member')->find();
        ($info['status']!= 6) && $this->error('只有配资成功的项目才能结算');
        if(IS_POST){
            //实例化模型
            $Invest = D('Invest');
            $Member = D('Member');
            $Finance = D('Finance');

            //启动事务，防止断点掉数据
            $Invest->startTrans();
            //获取盈亏
            $earning = I('earning',0,'intval');
            $bond = $info['total'] * 10000/ $info['provide_rate']; //风险金
            $finance_data = array();
            if($earning < 0){
                //如果亏损
                //保证金里扣除亏损
                $bond = $bond - abs($earning);
                $member_earning_op = true;
            }else{
                //如果盈利
                //调用投资人信息
                $invest = $Invest->field('id,order_id,member_id,invest_amount')->where(array('loan_id'=>$id,'status'=>3))->select();
                //如果有分红 将分红分配到投资人 记录资金流水
                if(!empty($info['share_rate'])){
                    $info['share_rate'] = abs($info['share_rate']);
                    $share_total = ($earning * $info['share_rate'])/100;
                    foreach($invest as $val){
                        //回分红到余额
                        $member_earning = ($earning * $val['invest_amount'] * $info['share_rate'])/($info['total'] * 10000 *100);//每个投资人的分红
                        $member_earning_op = $Member->where(array('id'=>$val['member_id']))->setInc('balance',$member_earning);
                        //生成资金流水记录 回分红
                        $finance_data[] = array(
                            'order_id' => make_order_id('Finance'),
                            'total' => $member_earning,
                            'log_type' => 11,
                            'member_id' => $val['member_id'],
                            'status' => 1,
                            'remark' => '投资订单号：'.$val['order_id'].'的股票分红',
                            'item_id' => $val['id'],
                        );
                    }
                    //$member_earning_finance_op = $Finance->addAll($member_earning_finance_data);
                    //总收益里扣除分红
                    $earning = $earning - $share_total;
                }else{
                    $member_earning_op = true;
                }
            }
            //投资本金回退到投资人的余额
            $Home = A('Home/Home');
            $invest_back_op = $Home->invest_back($invest,4);
            /*foreach($invest as $val){
                //回本金到帐户余额
                $member_invest_amount_op = $Member->where(array('id'=>$val['member_id']))->setInc('balance',$val['invest_amount']);
                if(!$member_invest_amount_op){
                    break;
                }
                //修改投资状态为已结算
                $invest_status_op = $Invest->where(array('id'=>$val['id']))->setField(array('status'=>4));
                if(!$invest_status_op){
                    break;
                }
                //生成资金流水记录 回本金
                $finance_data[] = array(
                    'order_id' => make_order_id('Finance'),
                    'total' => $val['invest_amount'],
                    'log_type' => 12,
                    'member_id' => $val['member_id'],
                    'status' => 1,
                    'remark' => '订单号：'.$val['order_id'].'的投资本金回款',
                    'item_id' => $val['id'],
                );
            }*/

            //保证金回退配资人余额 记录资金流水
            if(!$val['is_envelope']){
                $member_loan_bond_op = $Member->where(array('id'=>$info['member_id']))->setInc('balance',$bond);
                $finance_data[] = array(
                    'order_id' => make_order_id('Finance'),
                    'total' => $bond,
                    'log_type' => 7,
                    'member_id' => $info['member_id'],
                    'status' => 1,
                    'remark' => '订单号：'.$info['order_id'].'的保证金回款',
                    'item_id' => $info['id'],
                );
            }
            //盈收到配资人余额 记录资金流水
            $member_loan_earning_op = $Member->where(array('id'=>$info['member_id']))->setInc('balance',$earning);
            $finance_data[] = array(
                'order_id' => make_order_id('Finance'),
                'total' => $earning,
                'log_type' => 8,
                'member_id' => $info['member_id'],
                'status' => 1,
                'remark' => '订单号：'.$info['order_id'].'的配资收益',
                'item_id' => $info['id'],
            );
            $finance_op = $Finance->addAll($finance_data);
            //修改配资为已结算
            $loan_op = $this->_mod->where(array('id'=>$id))->setField(array('status'=>9));
            if($member_earning_op && $invest_back_op  && $member_loan_bond_op && $member_loan_earning_op && $finance_op && $loan_op){
                $Invest->commit();
                IS_AJAX && $this->ajax_return(1, L('operation_success'), '', 'edit');
                $this->success('结算成功！');
            }else{
                $Invest->rollback();//不成功，则回滚
                IS_AJAX && $this->ajax_return(0, L('operation_failure'));
                $this->error('数据保存不成功，请稍后再试');
            }
        }else{
            $this->assign('info',$info);
            if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1, '', $response);
            } else {
                $this->display();
            }
        }
    }

    public function verify_bak(){
        $id = I('id',0,'intval');
        $info = $this->_mod->where(array('id'=>$id))->relation('member')->find();
        ($info['status']!= 6) && $this->error('只有配资成功的项目才能结算');
        if(IS_POST){
            $id = I('id',0,'intval');
            $data['start_time'] = strtotime(I('start_time','','trim'));
            $data['end_time'] = strtotime(I('end_time','','trim'));
            $data['status'] = 5;
            $data['minimum'] = abs(I('minimum',0,'intval'));
            $this->_mod->where(array('id'=>$id))->save($data);
            $this->success('该活动已审核通过',U('loan/index'));
        }else{
            //调操盘手信息
            $trader = M('Trader')->field('id,username')->select();
            //查找保证金记录
            $finance = M('Finance')->where(array('item_id'=>$id))->find();
            $this->assign('finance',$finance);
            $this->assign('info',$info);
            $this->assign('loan_config', C('loan_config'));
            $this->assign('trader',$trader);
            $this->display();
        }
    }

    public function verify_back(){
        if(IS_POST){
            //($info['status'] > 2) && $this->error('该活动已经审核通过，无须再次审核！');
            $id = I('id',0,'intval');
            $data['start_time'] = strtotime(I('start_time','','trim'));
            $data['end_time'] = strtotime(I('end_time','','trim'));
            $data['status'] = 1;
            $this->_mod->where(array('id'=>$id))->save($data);
            $this->success('操作成功',U('loan/index'));
        }
    }

    
}