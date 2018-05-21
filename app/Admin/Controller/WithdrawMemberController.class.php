<?php
namespace Admin\Controller;
/**
 * 会员提现
 * Class WithdrawMemberController
 * @package Admin\Controller
 */
class WithdrawMemberController extends AdminCoreController {
    public function _initialize()
    {
        parent::_initialize();
        $this->_mod = D('WithdrawMember');
        $this->set_mod('WithdrawMember');

    }
    //搜索
    public function _search()
    {
        $map=array();

        ($time_start = I('time_start', '', 'trim')) && $map['add_time'][] = array('egt', strtotime($time_start));
        ($time_end = I('time_end', '', 'trim')) && $map['add_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));
        ($keywords = I('keywords', '', 'trim')) && $map['_string'] = "uid in (select id from jrkj_member where realname like '%".$keywords."%' or mobile like '%".$keywords."%')";

        $search = array(
            'time_start' => $time_start,
            'time_end'   => $time_end,
            'keywords'   =>$keywords,
        );

        $this->assign('search', $search);
        return $map;
    }

    public function index()
    {
        $map=$this->_search();
        $mod=D('WithdrawMember');
        !empty($mod) && $this->_list($mod, $map,'add_time','desc');


        $list=$this->get('list');
        if($list){
            //会员
            $uids=array_unique(array_column($list,'uid'));
            !empty($mod)&&$member=M('member')->where(['id'=>['in',$uids]])->getField('id,realname,mobile');
            $this->assign('member',$member);
        }
        $this->display();
    }

    //审核
    public function sh(){
        $mod = $this->_mod;
        $id = I('id', 'intval');
        $status = I('status',0,'intval');//1通过2驳回
        (!$id || !in_array($status,[1,2]))&&  $this->ajax_return(0, L('operation_failure'));
        $info = $mod->find($id);
        (1 != $info['status'])&&$this->ajax_return(0, L('operation_failure'));

        $member_model = M('member');
        $start=M();
        $start->startTrans();
        if($status==2){//驳回=》加回工资
            $res_money=$member_model->where(['id'=>$info['uid']])->setInc('prices',$info['totalprices']);
            if(!$res_money){
                $start->rollback();
                $this->ajax_return(0, L('operation_failure'));
            }
            //会员明细
            $arr= [
                'type' 			=> 1,//币种1工资2金元宝3金果4银币
                'uid'			=> $info['uid'],
                'totalprices'	=> $info['totalprices']+$info['service_charge'],
                'change_desc'	=> '工资提现驳回',
                'add_time'		=> $_SERVER['REQUEST_TIME']
            ];
            $res_account=M('account')->add($arr);
            if(!$res_account){
                $start->rollback();
                $this->ajax_return(0, L('operation_failure'));
            }
            $after_status=3;
        }else{//通过=》线下转账
            $after_status=2;
        }

        //改变申请状态
        $res_status =$mod->where(array('id'=>$id))->setField('status',$after_status);

        if($res_status){
            $mod->commit();
            $this->ajax_return(1, L('operation_success'), '', 'apply');
        }else{
            $mod->rollback();
            $this->ajax_return(0, L('operation_failure'));
        }

    }


    public function show(){
        $id = I('id',0, 'intval');
        $this->_mod->relation(true);
        $info = $this->_mod->find($id);
        if(IS_POST){
            //启动事务
            $this->_mod->startTrans();
            //资金流水记录
            $finance_data = array(
                'order_id' => make_order_id('Finance'),
                'total' => $info['fee'],
                'log_type' => 1,
                'member_id' => $info['member_id'],
                'status' => 1,
                'remark' => '充值订单：'.$info['order_id'].'的充值金额',
                'item_id' => $info['id'],
            );
            $Finance = D('Finance');
            if($finance_data = $Finance->create($finance_data)){
                $finance_op = $Finance->add($finance_data);
            }else{
                $this->error($Finance->getError());
            }
            //修改充值订单状态
            $recharge_op = $this->_mod->where(array('id'=>$id))->setField(array('status'=>1));
            //增加帐户余额
            $member_op = M('Member')->where(array('member_id'=>$info['member_id']))->setInc('balance',$info['fee']);
            if($finance_op && $recharge_op && $member_op){
                $this->_mod->commit();//成功则提交
                IS_AJAX && $this->ajax_return(1, L('operation_success'), '', 'show');
                $this->success(L('operation_success'));
            }else{
                $this->_mod->rollback();//不成功，则回滚
                IS_AJAX && $this->ajax_return(0, L('operation_failure'));
                $this->error(L('operation_failure'));
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

    //下载报表余额提现
    public function export()
    {
        ob_end_clean();

        $map = $this->_search();

        $list = $this->_mod->where($map)->select();
        if($list){
            //会员
            $memberIds = array_unique(array_column($list,'uid'));
            !empty($memberIds) && $member = M('member')->where(['id'=>['in',$memberIds]])->getField('id,realname,mobile');
        }
        $data = array();
        foreach ($list as $k => $v) {
            $data[$k]['realname']= $member[$v['uid']]['realname']; //会员名
            $data[$k]['mobile'] = $member[$v['uid']]['mobile']; //手机号
            $data[$k]['member_name'] = $v['member_name']; //开户名
            $data[$k]['province'] = $v['province'];//开户省
            $data[$k]['city'] = $v['province'];//开户市
            $data[$k]['district'] = $v['district'];//开户区
            $data[$k]['khh'] = $v['name'].$v['title']; //开户行
            $data[$k]['nums'] = $v['nums']; //卡号
            $data[$k]['totalprices'] = $v['totalprices']; //提现金额
            $data[$k]['sj'] = $v['totalprices']-C('pin_tx_sxf');//实际到账
            $data[$k]['sx']=C('pin_tx_sxf');//手续费
            $data[$k]['create_time'] = date('Y-m-d H:i' ,$v['add_time']);//申请时间
            ($v['status']==1)&& $data[$k]['status']='未审核';
            ($v['status']==2)&& $data[$k]['status']='已通过';
            ($v['status']==3)&& $data[$k]['status']='已驳回';

        }

        $headArr = array();
        $headArr[] = '会员名';
        $headArr[] = '手机号';
        $headArr[] = '开户名';
        $headArr[] = '开户省';
        $headArr[] = '开户市';
        $headArr[] = '开户区';
        $headArr[] = '开户行';
        $headArr[] = '卡号';
        $headArr[] = '提现金额';
        $headArr[] = '实际到账';
        $headArr[] = '手续费';
        $headArr[] = '申请时间';
        $headArr[] = '状态';
        $filename="余额提现".date("Y-m-d");
        $this->getExceltjab($filename, $headArr, $data);

    }








}