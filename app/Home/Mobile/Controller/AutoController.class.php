<?php
namespace Mobile\Controller;

use Think\Controller;
use Think\View;

class AutoController extends Controller {
	
    protected function _initialize(){
		//初始化网站配置
        $setting = array();
        if(F('setting')){
            $setting = F('setting');
        }else{
            $setting = D('Setting')->setting_cache();
            F('setting',$setting);
        }
        C($setting); 
	} 
	 
	public function test()
	{
		die;
		$member_model = D('Member'); 
		
		$b = date('Y-m-d', time());//今天0点
		
		$list = $member_model->where(array('last_login_time'=>array('egt', $b), 'gold_coin'=>array('egt', '10000')))->field('id, silver_coin, gold_coin')->select();
		
		foreach ($list as $k => $v) {   
			$data = array();
			$coin = $v['silver_coin'] * 0.0005;

			$data['silver_coin'] =   $v['silver_coin'] + $coin; 
			$data['gold_coin'] = $v['gold_coin'] - $coin; 
			
			$res = $member_model->where(array('id'=>$v['id']))->save($data);
			dump($res);
		} 
		die;
		$list = $member_model->where(array('gold_coin'=>array('egt', '10000')))->select();
		dump($list);die;
	}  
	
	//每天定时执行 银币转金币
	public function currency_conversion() 
	{
		$ip = $_SERVER["REMOTE_ADDR"];
		
		if ($ip != '101.200.72.164') {
			$this->redirect('Index/index'); 
			die;
		}  
		
		do{   
			#余额/金币的比例
			$goal_to_price = 100;  
			 
			#银币转金币的比例
			$silver_to_gold_scale = C('pin_silver_coin');    
			
			#金币转换手续费
			$gold_to_change_scale = C('pin_jb_ye_sxf'); 
			
			#金币自动转换最低值 
			$gold_auto_critical = 10000;   
	
			#金币/余额 金果 比例
			$goal_than_price   =  C('pin_jb_ye') / $goal_to_price; 
			
			$goal_than_fruit = C('pin_jb_ye_jg') / $goal_to_price;    
			
			$member_model = D('Member'); 
			$member_recharge_model = D('MemberRecharge');
			
			$member_model->startTrans();     
			
			 
			#用户信息
			$member_list = $member_model->where(array('status'=>1))->field('id,silver_coin,gold_coin,prices,gold_fruit,mobile')->select();

			foreach($member_list as $k => $v) {
				
				$data = array();     
				
				#银币转金币的个数
				$conversions_num = $v['silver_coin'] * $silver_to_gold_scale;   

//				$conversions_price = $v['silver_coin'] * $silver_to_gold_scale;  
				
				#转换后银币,金币的个数 
				$data['silver_coin'] = $v['silver_coin'] - $conversions_num;  
				
				$data['gold_coin'] = $v['gold_coin'] + $conversions_num; 
				  
				$change_price = 0;     
				$change_fruit = 0;
				
				//银币流水
				($conversions_num)&&all_ls($v['id'],$conversions_num,6,1,'自动转换'); 
				//金币流水
				($conversions_num)&&all_ls($v['id'],$conversions_num,5,2,'自动转换');//银币转金币
				
				
				//金币转余额/金果	#如果金币达到自动转换的值   
				if ($data['gold_coin'] >= $gold_auto_critical) { 

					#计算转的金币
					$cur_gold_coin =  floor(($data['gold_coin'] / $gold_auto_critical)) *  $gold_auto_critical; 
					  
					#手续费
					$goal_change_fee = $gold_to_change_scale * $cur_gold_coin;     
					#转换余额
					$change_price = $goal_than_price * $cur_gold_coin;
					#转换金果
					$change_fruit = $goal_than_fruit * $cur_gold_coin; 
					
					#转换后金果数量 余额 
	 				$data['gold_fruit'] =  $v['gold_fruit'] + $change_fruit;  
					$data['prices'] =  $v['prices'] + $change_price;
					
					$data['gold_coin'] =  $data['gold_coin']  - $cur_gold_coin;  


					($cur_gold_coin) && all_ls($v['id'],$cur_gold_coin,5,1,'自动转换');//金币转余额 
					
					#生成余额记录
					if ($change_price) {            
						$recharge['dingdan']='';
			            $recharge['member_id'] = $v['id']; 
			            $recharge['skperson']='臻惠生活馆';
			            $recharge['totalprices'] =$change_price;//后台扣手续费在实际到账里
						$recharge['zftype']= 0;//'支付方式  0.未选择 1=微信，2=支付宝',
						$recharge['type'] = 2; //支出状态 1=出 ，  2=入 
			            $recharge['item_type'] = 4;//1金元宝 2银元宝 3金果 4余额 5会员卡（15作为充值订单表时）
						$recharge['memos']='金币转余额';
						$recharge['status'] = 2;// 1未付款 2已付款
						$recharge['add_time']=time(); 
						
						if (!$member_recharge_model->add($recharge)) { 
							$member_model->rollback();
							$bool = true;
							break;
						}  
					}  
					
					if ($change_fruit) {    
						$recharge['dingdan']=''; 
			            $recharge['member_id'] = $v['id'];  
			            $recharge['skperson']='臻惠生活馆';
			            $recharge['totalprices'] =$change_fruit;//后台扣手续费在实际到账里
						$recharge['zftype']= 0;//'支付方式  0.未选择 1=微信，2=支付宝', 
						$recharge['type'] = 2; //支出状态 1=出 ,  2=入  
			            $recharge['item_type']=  3;//1金元宝 2银元宝 3金果 4余额 5会员卡（15作为充值订单表时）
						$recharge['memos']='金币转余额';
						$recharge['status'] = 2;// 1未付款 2已付款
						$recharge['add_time']=time(); 
						
						if (!$member_recharge_model->add($recharge)) {  
							$member_model->rollback();
							$bool = true;
							break;
						}     	
					}
					
				}

				$res = $member_model->where(array('id'=>$v['id']))->save($data); 
				
				if ($res === false) { 	 
					$member_model->rollback();
					$bool = true;
					break;
				} else { 
					$member_model->commit();
					$bool = false;
				} 
			}
		} while ($bool); 
		echo 1; 
	}
		
	//copy
	public function currency_conversion_copy() 
	{
		
		do{     
			#余额/金币的比例
			$goal_to_price = 100;  
			 
			#银币转金币的比例
			$silver_to_gold_scale = C('pin_silver_coin');    
			
			#金币转换手续费
			$gold_to_change_scale = C('pin_jb_ye_sxf'); 
			
			#金币自动转换最低值 
			$gold_auto_critical = 10000;    
	
			#金币/余额 金果 比例
			$goal_than_price   =  C('pin_jb_ye') / $goal_to_price; 
			
			$goal_than_fruit = C('pin_jb_ye_jg') / $goal_to_price;    
			
			$member_model = D('MemberCopy'); 
			$member_recharge_model = D('MemberRechargeCopy');
			
			$member_model->startTrans();        
			
			 
			#用户信息
			$member_list = $member_model->where(array('status'=>1))->field('id,silver_coin,gold_coin,prices,gold_fruit,mobile,realname')->select();

			
			foreach($member_list as $k => $v) { 
				
				$data = array();     
				
				#银币转金币的个数
				$conversions_num = $v['silver_coin'] * $silver_to_gold_scale;   

//				$conversions_price = $v['silver_coin'] * $silver_to_gold_scale;  
				
				#转换后银币,金币的个数 
				$data['silver_coin'] = $v['silver_coin'] - $conversions_num;  
				
				$data['gold_coin'] = $v['gold_coin'] + $conversions_num; 
				  
				$change_price = 0;     
				$change_fruit = 0;
				
//				//银币流水
//				($conversions_num)&&all_ls($v['id'],$conversions_num,6,1,'自动转换'); 
//				//金币流水
//				($conversions_num)&&all_ls($v['id'],$conversions_num,5,2,'自动转换');//银币转金币
				
				
				//金币转余额/金果	#如果金币达到自动转换的值   
				if ($data['gold_coin'] >= $gold_auto_critical) {

					#计算转的金币
					$cur_gold_coin =  floor(($data['gold_coin'] / $gold_auto_critical)) *  $gold_auto_critical; 
					  
					#手续费
					$goal_change_fee = $gold_to_change_scale * $cur_gold_coin;     
					#转换余额
					$change_price = $goal_than_price * $cur_gold_coin;
					#转换金果
					$change_fruit = $goal_than_fruit * $cur_gold_coin; 
					
					#转换后金果数量 余额 
	 				$data['gold_fruit'] =  $v['gold_fruit'] + $change_fruit;  
					$data['prices'] =  $v['prices'] + $change_price;
					
					$data['gold_coin'] =  $data['gold_coin']  - $cur_gold_coin;  


//					($cur_gold_coin) && all_ls($v['id'],$cur_gold_coin,5,1,'自动转换');//金币转余额 
					
					#生成余额记录
					if ($change_price) {            
						$recharge['dingdan']='';
			            $recharge['member_id'] = $v['id']; 
			            $recharge['skperson']='臻惠生活馆';
			            $recharge['totalprices'] =$change_price;//后台扣手续费在实际到账里
						$recharge['zftype']= 0;//'支付方式  0.未选择 1=微信，2=支付宝',
						$recharge['type'] = 2; //支出状态 1=出 ，  2=入 
			            $recharge['item_type'] = 4;//1金元宝 2银元宝 3金果 4余额 5会员卡（15作为充值订单表时）
						$recharge['memos']='金币转余额';
						$recharge['status'] = 2;// 1未付款 2已付款
						$recharge['add_time']=time(); 
						
						if (!$member_recharge_model->add($recharge)) { 
							$member_model->rollback(); 
							$bool = true;
							break;
						}  
					}  
					
					if ($change_fruit) {    
						$recharge['dingdan']=''; 
			            $recharge['member_id'] = $v['id'];  
			            $recharge['skperson']='臻惠生活馆';
			            $recharge['totalprices'] =$change_fruit;//后台扣手续费在实际到账里
						$recharge['zftype']= 0;//'支付方式  0.未选择 1=微信，2=支付宝', 
						$recharge['type'] = 2; //支出状态 1=出 ,  2=入  
			            $recharge['item_type']=  3;//1金元宝 2银元宝 3金果 4余额 5会员卡（15作为充值订单表时）
						$recharge['memos']='金币转余额';
						$recharge['status'] = 2;// 1未付款 2已付款
						$recharge['add_time']=time(); 
						
						if (!$member_recharge_model->add($recharge)) {  
							$member_model->rollback();
							$bool = true;
							break;
						}     	
					}
					
				}

				$res = $member_model->where(array('id'=>$v['id']))->save($data);  
				
				dump($data['gold_coin']); 
				
				if ($res === false) { 	 
					$member_model->rollback();
					$bool = true;
					break;
				} else { 
					$member_model->commit();
					$bool = false;
				} 
			}
		} while ($bool); 
		
		echo 1; 
	}  
	
	 
	//用户金币转余额 金果 扣除手续费
//	public function gold_coin_conversion($member_id)
//	{
//		$member_model = D('Member'); 
//		
//		$member = $member_model->where(array('id'=>$member_id))->field('id,silver_coin,gold_coin,prices')->find();
//	} 

	
	
}