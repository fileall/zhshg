<?php
namespace Admin\Controller;
/**商家提现
 * Class SendMoneyController
 * @package Admin\Controller
 */
class SendMoneyController extends AdminCoreController {
	public function _initialize()
    {
        parent::_initialize();
        $this->_mod = D('WithdrawShop');
        $this->set_mod('WithdrawShop');
        $this->assign('withdraw_type',C('withdraw_type'));
        $this->assign('withdrawal_status',C('withdrawal_status'));
        
        $this->merchant=D('Merchant');
        
    }
	/**
     * 获取请求参数生成条件数组
     */
    public function _search()
    {
		 $map=array();
		
	  	($time_start = I('time_start', '', 'trim')) && $map['create_time'][] = array('egt', strtotime($time_start));
		
        ($time_end = I('time_end', '', 'trim')) && $map['create_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));
		
		 ($keywords = I('keywords', '', 'trim')) && $map['_string'] = "member_id in (select id from jrkj_member where realname like '%".$keywords."%' or mobile like '%".$keywords."%')";
   		$map['type']=1;
		$search = array(     
            'time_start' => $time_start, 
            'time_end' => $time_end,  
            'keywords' =>$keywords,
        ); 
		$this->assign('search', $search);
		return $map;   
    }
    
	public function index0()
    {
		$this->list_relation = true;

        $map=$this->_search();

		$mod=D('WithdrawShop');

		!empty($mod) && $this->_list($mod, $map,'create_time','desc');


        $this->display();
    }

    
    public function index()
    {
        $map=$this->_search();
		
		$mod=D('WithdrawShop'); 
		$count=$mod->field('*')->where($map)->count();

        $Page = new \Think\Page($count, 10);
        $Page->setConfig('prev', "上一页");//上一页
        $Page->setConfig('next', '下一页');//下一页
        $Page->setConfig('first', '首页');//第一页
        $Page->setConfig('last', '末页');//最后一页
        $Page->setConfig ( 'theme', '%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%' );
        $show = $Page->show();
        $this->assign('page', $show);
	    $this->assign('count', $count);
		if ($p = I('p','','intval')){
            $Page->firstRow = ($p - 1) * 10;
            $this->assign('p',$p);
       }
//       $list = M()->table('__WITHDRAW_SHOP__ ws')
//          ->join('LEFT JOIN  __MERCHANT__ m on m.id=ws.shop_id')
//  		->field('m.tel,m.title as title,ws.member_name as member_name,ws.amount as amount')
//  		->limit($Page->firstRow.','.$Page->listRows)
//          ->select();

        $list = $mod->field('*')->where($map)->order('id desc')
        	->limit($Page->firstRow.','.$Page->listRows)->select();
		$list_merchant=D('merchant')->getField('id,tel,title');
    	foreach($list as $k=>$v){
    		$list[$k]['tel']=$list_merchant[$v['shop_id']]['tel'];
    		$list[$k]['title']=$list_merchant[$v['shop_id']]['title'];
    	}
        	
        $this->assign('list', $list);
        
        $export = I('export','', 'trim');
        if($export == '下载报表'){
            $this->export($list);
        }

        $this->assign('list',$list);
        $this->display();
    }
    
    

    public function _before_index(){
    	$p = I('p',1,'intval');
        $this->assign('p',$p);
      //弹窗  	
//  	$big_menu = array(
//          'title' => '添加',
//          'iframe' => U('send_money/add'),
//          'id' => 'add',
//          'width' => '520',
//          'height' => '410',
//      );
//      $this->assign('big_menu', $big_menu);
    	
    }
    
 	 //通过电话查对方银行卡
 	 public function cx_tel(){
 	 	$merchant_id=D('Merchant')->where(array('tel'=>I('tel')))->getField('id');
 		$list=M('merchant_bankcard')->where(array('shop_id'=>$merchant_id,'status'=>1))->order('add_time desc')->select();
		echo json_encode($list);
 	 }
    
    
    //驳回
    public function bh(){
    	//状态 0表示未审核 1为驳回 2为通过
    	$id = I('id', '', 'trim');
		$id_arr = explode(',', $id);
		
		
		$member_model = D('Merchant');
		$member_model->startTrans();
		
		foreach ($id_arr as $k => $v) {
			$withdraw = $this->_mod->find($v); 
			
			$recharge[$k]['dingdan'] = 0;
		    $recharge[$k]['order_id'] = $withdraw['id']; 
		    $recharge[$k]['member_id'] = $withdraw['shop_id']; 
		    $recharge[$k]['skperson'] = '';
		    $recharge[$k]['totalprices'] = $withdraw['amount'];//后台扣手续费在实际到账里
			$recharge[$k]['zftype'] = 0;//'支付方式  0.未选择 1=微信，2=支付宝',
			$recharge[$k]['type'] = 2; //支出状态 1=出 ，  2=入 
		    $recharge[$k]['item_type'] = 4;//item_type 1金元宝 2银元宝 3金果 4余额/收益 5金币 6银币
			$recharge[$k]['memos'] = '提现驳回返额';
			$recharge[$k]['status'] = 2;// 1未付款 2已付款
			$recharge[$k]['add_time'] = time();
			#增加余额
			$res  = $member_model->where(array('id'=>$withdraw['shop_id']))->setInc('shouyi', $withdraw['amount']);
			$is_first=$this->_mod->where(array('id'=>array('in', $id_arr)))->getField('is_first',true);
			($is_first[0]==1)&&$this->_mod->where(array('id'=>array('in', $id_arr)))->setField('is_first',0);//1第一次通过0非第一次
			
			if (!$res) {
				$member_model->rollback();
				$this->ajax_return(0, '系统繁忙!');
			}  
		}
		//商家流水
		$member_recharge_model = D('ShopRecharge');
		$recharge_res = $member_recharge_model->addAll($recharge); 
		
		$save_res = $this->_mod->where(array('id'=>array('in', $id_arr)))->setField('status', 1);
		
		if ($recharge_res&&$save_res) { 
			$member_model->commit(); 
			$this->ajax_return(1, '驳回成功!','','edit');
			
		} else {
			$member_model->rollback();
			$this->ajax_return(0, '系统繁忙!');
		}
		
    }
	
	//提现通过
	public function pass()
	{
		$id = I('id', '', 'trim');
		$id_arr = explode(',', $id);
		
		$member_model = D('Merchant');
		M()->startTrans();
		
		$is_first=$this->_mod->where(array('id'=>array('in', $id_arr)))->getField('is_first',true);
		$save_res=0;
		if($is_first[0]==1){
			$save_res = $this->_mod->where(array('id'=>array('in', $id_arr)))->setField('status', 2);
			$this->_mod->where(array('id'=>array('in', $id_arr)))->setField('is_first',0);//1第一次通过0非第一次
		}else{
			$withdraw = $this->_mod->find($id_arr[0]); //订单 
			//二次通过操作、扣余额
			$recharge['dingdan'] = 0;
		    $recharge['order_id'] = $withdraw['id']; 
		    $recharge['member_id'] = $withdraw['shop_id']; 
		    $recharge['skperson'] = '';
	        $recharge['totalprices'] = $withdraw['amount'];//后台扣手续费在实际到账里
			$recharge['zftype'] = 0;//'支付方式  0.未选择 1=微信，2=支付宝',
			$recharge['type'] = 1; //支出状态 1=出 ，  2=入 
		    $recharge['item_type'] = 4;//item_type 1金元宝 2银元宝 3金果 4余额/收益 5金币 6银币
			$recharge['memos'] = '提现通过扣除';
			$recharge['status'] = 2;// 1未付款 2已付款
			$recharge['add_time'] = time();
			#扣余额
			$res  = $member_model->where(array('id'=>$withdraw['shop_id']))->setDec('shouyi', $withdraw['amount']);
			//商家流水
			$member_recharge_model = D('ShopRecharge');
			$recharge_res = $member_recharge_model->add($recharge); //流水
			
			$save_res = $this->_mod->where(array('id'=>array('in', $id_arr)))->setField('status', 2);
		}
		
		if (!$save_res) {
			M()->rollback(); 
			$this->ajax_return(0, '系统繁忙!');
		} else { 
			M()->commit();
			$this->ajax_return(1, '同意申请成功!','','edit');
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
            $Finance = D('Finance');//0
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
    
    //下载报表 商家打款0
     public function export($list)
	{
		ob_end_clean();
		
//		$map = $this->_search();
//  	$list = $this->_mod->where($map)->relation(true)->select();
        $data = array();  
        foreach ($list as $k => $v) { 
            $data[$k]['realname']= $v['realname']; //店铺名称
            $data[$k]['member_name'] = $v['mobile']; //手机号
            $data[$k]['mobile'] = $v['mobile']; //开户名
            $data[$k]['khh'] = $v['branch_name'].$v['branch_title']; //开户行
            $data[$k]['branch'] = $v['branch']; //银行卡号
            $data[$k]['amount'] = $v['amount']; //货款金额
            $data[$k]['sj'] = $v['amount']-C('pin_tx_sxf');//商家扣点
            $data[$k]['sx']=C('pin_tx_sxf');//实际到账
            $data[$k]['create_time'] = date('Y-m-d H:i' ,$v['create_time']);//日期
            ($v['status']==0)&& $data[$k]['status']='未审核';//状态
            ($v['status']==1)&& $data[$k]['status']='已驳回';
            ($v['status']==2)&& $data[$k]['status']='已通过';
        	
        }
		
        $headArr = array();
		$headArr[] = '店铺名称';
		$headArr[] = '手机号';
		$headArr[] = '开户名';
		$headArr[] = '开户行';
		$headArr[] = '银行卡号';
		$headArr[] = '货款金额';
		$headArr[] = '商家扣点';
		$headArr[] = '实际到账';
		$headArr[] = '日期';
		$headArr[] = '状态';
        $filename="商家打款".date("Y-m-d");
        $this->getExceltjab($filename, $headArr, $data); 
	
	}
	

	

    
    
    

}