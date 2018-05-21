<?php
namespace Admin\Controller;
class OutlineDealController extends AdminCoreController {
	public function _initialize()
    {
        parent::_initialize();
        $this->_mod = D('ApplyLine');
        $this->set_mod('ApplyLine');
    }
	 protected function _search() {
        $map = array();
        ($time_start=I('time_start'))&&$map['add_time'][]=array('egt',strtotime($time_start));
        ($time_end=I('time_end'))&&$map['add_time'][]=array('elt',strtotime($time_end)+24*60*60);
        ($keywords = I('keywords', '', 'trim')) && $map['_string'] = "uid in (select id from jrkj_member where mobile like '%".$keywords."%' or realname like '%".$keywords."%')";

        $this->assign('search', array(
            'keywords' => $keywords,
            'time_start' =>$time_start,
            'time_end'  =>$time_end,
        ));
        return $map;
    }

    public function index() {

		$wh=$this->_search();

		$mod=$this->_mod;
		
        !empty($mod) && $this->_list($mod, $wh,'add_time','desc');

        $list=$this->get('list');

        if($list){
            $member_ids=array_unique(array_column($list,'uid'));
            $member_ids&&$member=M('member')->where(['id'=>['in',$member_ids]])->getField('id,realname,mobile');
        }
        $this->assign('member',$member);
        $this->display();
    }
	 /**
     * 审核
     */
    public function act()
    {
        $mod = $this->_mod;
        $id = I('id', 'intval');
        $status = I('status',0,'intval');//1通过2驳回

        (!$id || !in_array($status,[1,2]))&&  $this->ajax_return(0, L('operation_failure'));


        $info = $mod->find($id);
        (1 != $info['status'])&&$this->ajax_return(0, L('operation_failure'));

		$member_model = D('Member');
        $start=M();
        $start->startTrans();
        if($status==1){//通过=》加元宝
            $res_money=$member_model->where(['id'=>$info['uid']])->setInc('gold_acer',$info['totalprices']);
           if(!$res_money){
               $start->rollback();
               $this->ajax_return(0, L('operation_failure'));
           }
            //会员明细
            $arr= [
                'type' 			=> 2,//币种1工资2金元宝3金果4银币
                'uid'			=> $info['uid'],
                'oid'           => 0,
                'totalprices'	=> $info['totalprices'],
                'change_desc'	=> '线下充值',
                'add_time'		=> $_SERVER['REQUEST_TIME']
            ];
            $res_account=M('account')->add($arr);
            if(!$res_account){
                $start->rollback();
                $this->ajax_return(0, L('operation_failure'));
            }
            $after_status=2;
        }else{//驳回
            $after_status=3;
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


    //***************
	
    public function show() {
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

        } else {
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

	public function certificate()
	{
		$id = I('id', '', 'intval');
		$list = D('ShImg')->where(array('withdraw_id'=>$id))->select(); 
//		dump($list); 
		$this->assign('list', $list);
		 if (IS_AJAX) {
            $response = $this->fetch();
            $this->ajax_return(1, '', $response);
        } else {
            $this->display();
        }
	}

	public function recharge() 
	{
		
		if (IS_POST) {  
			$post = I('post.');
			
			if (!$post['mobile'] || !$post['prices']) {
				 $this->ajax_return(0, '请完整输入提现信息');
			}
			
			//
			if (!preg_match('/\d{1,10}(\.\d{1,2})?$/', $post['prices'])) {
				$this->ajax_return(0, '请填写正确金额');
			}
				
			 $admin = M('Admin')->where(array('id'=>session('admin.id'), 'status'=>1))->find();
            
            if ($admin['password'] != md5($post['password'])) {
                $this->ajax_return(0, '登录密码错误');
            }
			
    		$member = D('Member')->where(array('mobile'=>$post['mobile']))->find();  
    		if (!$member) {
    			$this->ajax_return(0, '用户不存在');
    		}
			
			//银行卡
    		$card = D('BankCard')->find($post['card_id']);//平台银行卡id

			$data = array(

				'member_id'=> $member['id'],

				'totalprices'=> $post['prices'],

				'item_type' => 4,

				'zftype' => 6,

				'status' => 2,

				'withdraw_id' => 0,

				'add_time'=>time(), 

				'memos'=>'后台充值', 

			);

    		$yd = M('member_recharge')->add($data);   
			
			$data['order_no'] = time();//生成订单号 

			$data['member_id'] = $member['id'];

////		$data['branch'] = $card['nums']; 

			$data['amount'] = $post['prices'];//金额

			$data['create_time']=time();

////		$data['account_name'] = $post['account_name'];

			$data['status'] = 2; //0表示未审核 1为驳回 2为通过  

			$data['type'] = 2;

			$data['remark'] = '后台充值';//备注   

			//$data['bankcard_id']=$pos['card_id'];//平台银行的id  

//			$data['account_name']=$pos['account_name'];//申请人开户名

			$yd = M('Withdraw')->data($data)->add();	//生成临时订单 	 
			
			
			if ($yd) {  
				//充值成功后更新用户余额
				$result =M('member')->where(array('id'=>$member['id']))->setInc('prices', $post['prices']);

				$this->ajax_return(1, '充值成功','','add'); 
			} else {
				$this->ajax_return(0, '申请失败');
			}
        } else {
    		#转账银行卡
			$pt_card = D('BankCard')->where(array('status'=>1))->select();//平台银行卡
    		$this->assign('pt_card', $pt_card); 
			
           $response = $this->fetch();
           $this->ajax_return(1, '', $response,'add');
        }
	}


    //线下充值待审核记录
    public function export_sh()
    {
        ob_end_clean();

        $map = $this->_search();
        $list = $this->_mod->where($map)->relation(true)->select();
        if($list){
            $member_ids=array_unique(array_column($list,'member_id'));
            $member_ids&&$member=M('member')->where(['id'=>['in',$member_ids]])->getField('id,realname,mobile');
        }
        $data = array();
        foreach ($list as $k => $v) {
            $data[$k]['realname']= $member[$v['member_id']]['realname']; //会员名
            $data[$k]['mobile'] =  $member[$v['member_id']]['mobile'];
            $data[$k]['account_name']= $v['account_name']; //开户名
            $data[$k]['amount'] = $v['amount']; //充值金额
            $data[$k]['khh'] = $v['branch_name'];//转账银行卡
            $data[$k]['branch'] = $v['branch']; //转账银行卡
            $data[$k]['create_time'] = date('Y-m-d',$v['create_time']); //时间

            ($v['status']==0)&& $data[$k]['status']='未审核';//状态
            ($v['status']==1)&& $data[$k]['status']='已驳回';
            ($v['status']==2)&& $data[$k]['status']='已充值';

        }

        $headArr = array();
        $headArr[] = '会员名';
        $headArr[] = '会员电话';
        $headArr[] = '开户名';
        $headArr[] = '转账金额';
        $headArr[] = '转账银行';
        $headArr[] = '卡号';
        $headArr[] = '时间';
        $headArr[] = '状态';
        $filename="线下充值待审核记录";
        $this->getExceltjab($filename, $headArr, $data);

    }



    //线下已充值记录
     public function export()
	{
		ob_end_clean();
		
		$map = $this->_search();
    	$list = $this->_mod->where($map)->select();
        if($list){
            $member_ids=array_unique(array_column($list,'member_id'));
            $member_ids&&$member=M('member')->where(['id'=>['in',$member_ids]])->getField('id,realname,mobile');
        }
        $data = array();
        foreach ($list as $k => $v) {
            $data[$k]['realname']= $member[$v['member_id']]['realname']; //会员名
            $data[$k]['mobile'] =  $member[$v['member_id']]['mobile'];
            $data[$k]['amount'] = $v['amount']; //充值金额
            $data[$k]['sh'] = '管理员'; //审核人
            $data[$k]['create_time'] = date('Y-m-d',$v['create_time']); //时间
            ($v['status']==0)&& $data[$k]['status']='未审核';//状态
            ($v['status']==1)&& $data[$k]['status']='已驳回';
            ($v['status']==2)&& $data[$k]['status']='已充值';
        	
        }
		
        $headArr = array();
		$headArr[] = '会员名';
		$headArr[] = '会员电话';
		$headArr[] = '充值金额';
		$headArr[] = '审核人';
        $headArr[] = '时间';
		$headArr[] = '状态';
        $filename="线下充值记录";
        $this->getExceltjab($filename, $headArr, $data); 
	
	}
	
	
	

}