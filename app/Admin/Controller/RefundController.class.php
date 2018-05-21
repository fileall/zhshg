<?php
namespace Admin\Controller;
use Think\Page;
class RefundController extends AdminCoreController {
	public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Refund');
        $this->set_mod('Refund');
    }

    public function index(){
        $map = $this->_search();
        $pagesize = C('pagesize');

        if (I("sort",'', 'trim')) {
            $sort = I("sort",'', 'trim');
        } else if (!empty($sort_by)) {
            $sort = $sort_by;
        } else if ($this->sort) {
            $sort = $this->sort;
        } else {
            $sort = 'id';
        }
        if (I("order",'', 'trim')) {
            $order = I("order",'', 'trim');
        } else if (!empty($order_by)) {
            $order = $order_by;
        } else if ($this->order) {
            $order = $this->order;
        } else {
            $order = 'DESC';
        }
  
//      $select = M()->table('__REFUND__ refund')
//          ->field('refund.id')
//          ->join('__ORDER__ st_order ON st_order.id = refund.order_id','left')
//          ->join('__MEMBER__ member ON st_order.member_id = member.id','left');

        $count = M('refund')->where($map)->count();
        $pager = new Page($count, $pagesize);
//
//      $select = M()->table('__REFUND__ refund')
//          ->field('refund.*,member.account,member.truename,st_order.amount,merchant.title,st_order.order_sn,st_order.pay_id')
//          ->join('__ORDER__ st_order ON st_order.id = refund.order_id','left')
//          ->join('__MEMBER__ member ON st_order.member_id = member.id','left')
//          ->join('__MERCHANT__ merchant ON st_order.merchant_id = merchant.id','left');

        $list = M('refund')->where($map)->limit($pager->firstRow.','.$pager->listRows)->order($sort . ' ' . $order)->select();
        foreach ($list as $k => $v) {
        	$list[$k]['truename']=M('member')->where(array('id'=>$v['member_id']))->getField("truename");
        	$list[$k]['pay_id']=M('order')->where(array('id'=>$v['order_id']))->getField("pay_id");
        	$list[$k]['order_sn']=M('order')->where(array('id'=>$v['order_id']))->getField("order_sn");
        }
    
        $page = $pager->show();

        $this->assign('pay',C('pay'));
        $this->assign('refund_status',C('refund_status'));
        $this->assign("page", $page);
        $this->assign('list',$list);

        $this->display();
    }

    protected function _search() {
        $map = array();
        
        ($time_start = I('time_start','', 'trim')) && $map['create_time'][] = array('egt', strtotime($time_start));
        ($time_end = I('time_end','', 'trim')) && $map['create_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));
        $account = I('account');
		if($account){
			$where['truename']=array('like',"%$account%");
			$member=M('member')->where($where)->select();
			foreach ($member as $k => $v) {
				 $member_id[$k]=$v['id'];
			}
			$map['member_id'] = array('in',$member_id);
		}
		 

        $status = I('status','', 'trim');
        if($status !== ''){
            $map['status'] = $status;
        }

        $this->assign('search', array(
            'time_start' => $time_start,
            'time_end' => $time_end,
            'status'  => $status,
            'account' => $account,
        ));
	
        return $map;
    }


    public function detail(){
        $id = I('id');
        if(IS_POST){
            //判断状态，只可操作未审核状态
            $map = array('order_id' => $id);
                $data = array(
                    'status' => I('status'),
                    'remark' => I('remark'),
                );

//              if($data['amount'] > $refund['amount']){
//                  IS_AJAX && $this->ajax_return(0, L('operation_failure').'退款金额不能大于订单金额');
//                  $this->error(L('operation_failure'));
//              }

                $this->_mod->startTrans();
				$order=M('order')->where(array('id'=>$id))->find();
				$order_list=M('order_list')->where(array('oid'=>$id))->select();
				foreach($order_list as $k=>$v){
					$ju=$v['item_name'].";".$ju;
				}
				
				$member=M('member')->where(array('id'=>$order['member_id']))->find();
                $op = $this->_mod->where(array('order_id' => $id))->setField($data);
                if($data['status'] ==1){
                    $order_op = M('Order')->where(array('id' => $id))->setField('status',5);
                }else if($data['status'] ==2){
                    $order_op = M('Order')->where(array('id' => $id))->setField('status',6);
                }else if($data['status'] ==3){
                    $order_op = M('Order')->where(array('id' => $id))->setField('status',7);
//					if($order_op){
//						$order=M('order')->where(array('id'=>$id))->find();
//						M('member')->where(array('id'=>$order['member_id']))->setInc("prices",$order['realprices']);
//					}
				}
//              if($order_status == 12){ //微信支付待完善
//                  $sn_op = M('SnCode')->where(array('order_id' => $refund['order_id']))->delete();
//              }else{
//                  $sn_op = true;
//              }
                

                $time=date('Y-m-d H:i:s',$order['add_time']);
                if($op &&$order_op ){
                	if($data['status']==2){
                		$a=A('WeiXin')->connect($member['wx_openid'],'您好,您申请的退款审核失败',$order['dingdan'],$ju,$time,$order['id'],'退款回复'.$data['remark']);
                	}else if($data['status']==3){
                		$a=A('WeiXin')->connect($member['wx_openid'],'您好,您申请的退款审核成功',$order['dingdan'],$ju,$time,$order['id'],'退款回复'.$data['remark']);
                	}
					
                    $this->_mod->commit();                  
					IS_AJAX && $this->ajax_return(1, L('operation_success'), '', 'edit');
                    $this->success(L('operation_success'));
                }else{
                    $this->_mod->rollback();
                    IS_AJAX && $this->ajax_return(0, L('operation_failure'));
                    $this->error(L('operation_failure'));
                }
//          }else{
//              IS_AJAX && $this->ajax_return(0, L('operation_failure'));
//              $this->error(L('operation_failure'));
//          }

        }else{

            $info=M('refund')->where(array('order_id'=>$id))->find();
            $order=M('order')->where(array('id'=>$info['order_id']))->find();
        	$info['realname']=M('member')->where(array('id'=>$info['member_id']))->getField("realname");
        	$info['zftype']=M('order')->where(array('id'=>$info['order_id']))->getField("zftype");
        	$info['dingdan']=M('order')->where(array('id'=>$info['order_id']))->getField("dingdan");

            $this->assign('order', $order);
            $this->assign('info', $info);
			$this->assign('id', $id);
            $this->assign('zftype',C('zftype'));
            $this->assign('refund_status',C('refund_status'));
            $this->assign('refund_reason',C('refund_reason'));

            if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1,'',$response,'add');
            } else {
                $this->display();
            }
        }
    }

    //通过
    public function approve(){
        $id = I('id',0,'intval');
        empty($id) && $this->error('参数丢失');
        $count = $this->_mod->where(array('id' => $id))->count();
        ($count < 1) && $this->error('不存在');
        $withdraw_rate = C('withdraw_rate');
        $apply = $this->_mod->where(array('id' => $id))->find();
        if(IS_POST){
            if($apply['status'] == 1 || $apply['status'] == 2){
                $this->ajax_return(0,'已经审核的申请不可二次操作');
            }
            $status = I('status',0,'intval');
            $remark = I('remark','','trim');
            if($status == 2){
                $apply_op = $this->_mod->where(array('id' => $id))->setField(array('status' => $status,'remark' => $remark));
                if($apply_op){
                    $this->ajax_return(1, L('operation_success'), '', 'add');
                }else{
                    IS_AJAX && $this->ajax_return(0,$this->_mod->getError());
                    $this->error($this->_mod->getError());
                }
            }elseif($status == 1){
                $this->_mod->startTrans();
                ($apply['status']) && $this->error('这个提现申请已处理');
                $apply_op = $this->_mod->where(array('id' => $id))->setField(array('status' => $status,'remark' => $remark));
                $type = $apply['type'];

                if($apply['platform'] == 'wealth'){
                    $AccoungLog = D('AccountLog');
                    $profit_total = $AccoungLog->where(array('member_id' => $apply['member_id']))->sum('profit');
                    ($apply['amount'] > $profit_total) && $this->error('取现金额大于可取金额');
                    $apply['amount'] = abs($apply['amount']);
                    $member_op = D('Member')->member_profit($apply['member_id'],$apply['amount'] * $withdraw_rate[2] / 100,'balance',$change_desc='循环消费',$change_type=17,$apply['id']);
                }else{
                    $member_op = true;
                }

                $mod_op = D('Member')->member_profit($apply['member_id'],-$apply['amount'],'profit',$change_desc='提现',$change_type=16,$apply['id']);

                if($apply_op && $member_op && $mod_op){
                    $this->_mod->commit();
                    $this->ajax_return(1, L('operation_success'), '', 'add');
                }else{
                    $this->_mod->rollback();
                    IS_AJAX && $this->ajax_return(0,$this->_mod->getError());
                    $this->error($this->_mod->getError());
                }
            }
        }else{
            $info = $this->_mod->relation(true)->find($id);

            $type_balance = M('AccountLog')->where(array('member_id' => $info['member_id']))->sum('profit');

            if($info){
                $this->assign('withdraw_status',C('withdraw_status'));
                $this->assign('type_balance',$type_balance);
                $this->assign('info',$info);
                if (IS_AJAX) {
                    $response = $this->fetch();
                    $this->ajax_return(1,'',$response,'add');
                } else {
                    $this->display();
                }
            }
        }
    }

    //撤消
    public function reject(){
        $id = I('id',0,'intval');
        empty($id) && $this->error('参数丢失');
        $count = $this->_mod->where(array('id' => $id))->count();
        ($count < 1) && $this->error('不存在');
        if(IS_POST){
            $this->_mod->startTrans();
            $apply = $this->_mod->where(array('id' => $id))->find();
            $apply_op = $this->_mod->where(array('id' => $id))->setField(array('status' => 0));
            $is_op = I('is_op',0,'intval');
            if($is_op){
                $member_deposit = M('AccountLog')->where(array('member_id' => $apply['apply_member_id']))->sum('deposit');
                if($member_deposit < $apply['amount']){
                    $deposit_op = false;
                }else{
                    $MemberTree = D('MemberTree');
                    $Member = D('Member');
                    $deposit_op = $Member->member_profit($apply['apply_member_id'],-abs($apply['amount']),'deposit',$change_desc='后台撤消会员申请验证',$change_type=15);
                    D('AccountLog')->deposit($apply['apply_member_id'],-abs($apply['amount']),$deposit_op);
                }
            }else{
                $deposit_op = true;
            }
            if($apply_op && $deposit_op){
                $this->_mod->commit();
                $this->ajax_return(1, L('operation_success'), '', 'add');
            }else{
                $this->_mod->rollback();
                IS_AJAX && $this->ajax_return(0,'操作失败',$this->_mod->getError());
                $this->error($this->_mod->getError());
            }

        }else{
            $info = $this->_mod->find($id);
            if($info){
                $this->assign('info',$info);
                if (IS_AJAX) {
                    $response = $this->fetch();
                    $this->ajax_return(1,'',$response,'add');
                } else {
                    $this->display();
                }
            }

        }
    }
   
}