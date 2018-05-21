<?php
namespace Admin\Controller;
use Admin\Org\Tree;
class MemberCardController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('MemberCard');
		$this->set_mod('MemberCard');
    }
	
	public function _before_index(){
		
		$a=I('salesman');
//		dump($a);exit;
		check_dz() && $map['store'] = $_SESSION['admin']['id'];
		$map['status']=0;
		//统计数量 
		$nums[0] = $this->_mod->where($map)->count();
		$map['status']=1;
		$nums[1]= $this->_mod->where($map)->count();

		
		//获取商品一级分类
		$cate = M('ItemCate')->where(array('pid'=>0))->getfield('id,name');
		
		//查询会员卡类别
		$card_cate = M('MemberCardCate')->field('id,title,face_value')
										->where(array('status'=>1))
										->select();
		
		//所有门店
		$store = M('Admin')->where(array('role_id'=>3,'status'=>1))
    					   ->field('id,username')
    					   ->select();
		//重组门店数据				   
		foreach($store as $v){
			$store_name[$v['id']] = $v['username'];	
		}
		
		//所有业务员
		$salesman = M('Salesman')->getfield('id,name');
						   
		$this->assign('salesman',$salesman);
		$this->assign('store_name',$store_name);
		$this->assign('store',$store);
		$this->assign('nums',$nums);
		$this->assign('cate_list',$cate);
		$this->assign('card_cate',$card_cate);
	}
	
	
	
    protected function _search() {
        $map = array();
		($member_id=I('member_id'))&&$map['member_id']=$member_id;
		($time_start = I('time_start','', 'trim')) && $map['add_time'][] = array('egt', strtotime($time_start));
        ($time_end = I('time_end','', 'trim')) && $map['add_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));
		($use_time_start = I('use_time_start','', 'trim')) && $map['use_time'][] = array('egt', strtotime($use_time_start));
        ($use_time_end = I('use_time_end','', 'trim')) && $map['use_time'][] = array('elt', strtotime($use_time_end)+(24*60*60-1));
        $status = I('status','','trim');
		if(!$status){
			$map['status']=0;
		}
        $map['status'] = $status;
        //($type = I('type','','trim')) ? $map['type'] = $type : $map['type'] = 0;
        ($card = I('card','', 'trim')) && $map['card'] = array('like', '%'.$card.'%');
        ($store_id = I('store_id','', 'trim')) && $map['store'] = $store_id;
        ($salesman = I('salesman','', 'trim')) && $map['salesman'] = $salesman;
		check_dz() && $map['store'] = $_SESSION['admin']['id'];
	    
        $this->assign('search', array(
            //'type'  => $type,
            'status'  => $status,
            'card'  => $card,
            'time_end'  => $time_end,
            'time_start'  => $time_start,
            'use_time_start'  => $use_time_start,
            'use_time_end'  => $use_time_end,
            'store_id'  => $store_id,
        ));

        return $map;
    }
	
	public function add(){
        if (IS_POST) {
        	$data = I('post.');
			empty($data['card']) && $this->error("卡号不能为空");
			$member_card=M('member_card')->where(array('card'=>$data['card']))->find();
			if($member_card){
				$this->ajax_return(0,"卡号已存在！");
			}
			
			$data['add_time']=time();
			if($data['price']<=0){
				$this->ajax_return(0,"面值必须大于0！");
			}
			
			$data['face_value']=$data['price'];
			if($data['member_id']){
				$data['status']=1;
				$data['bang_time']=time();
			}else{
				$data['status']=0;
			}
			
			//把分类ID组成字符串
			//$data['cate'] ? $cate = rtrim(implode(",",$data['cate']),',') : $cate = ""; 
			
			//类别详情
//			$info = M('MemberCardCate')->find($data['cate']);
//			
//			//生成卡号
//			$card = array();
//			for($i=0;$i<$data['nums'];$i++){
//				$card[] = rand(1000,9999).substr(time(),3,7).rand(10,99); 
//			}
//			//去掉重复的卡号  
//			$card = array_unique($card);  
//			
//			$card_data = array();
//			foreach($card as $v){
//				//验证卡号唯一
//				$test['card'] = $v;
//				if (false === $this->_mod->create($test)) {
//	                //$this->ajax_return(0,$mod->getError());
//	                $this->error($this->_mod->getError());   
//					break;  
//	            }
//				
//				//组合要添加的数据
//				$card_data[] = array(    
//					'card' => $test['card'],
//					'face_value' => $info['face_value'],    
//					'price' => $info['face_value'],    
//					'cate' => $info['cate'],
//				);  
//			} 
			
			//dump($card_data);exit;
            
            if($this->_mod->add($data)){
            	$this->ajax_return(1, L('operation_success'), '', 'add');
            }else{
            	$this->ajax_return(0, L('operation_failure'));
            }
            
            /*if($this->_mod->addAll($card_data)){
                $this->ajax_return(1, L('operation_success'),'','add');
            } else {
                $this->ajax_return(0, L('operation_failure'));  
            }*/
        } else {
        	//所有门店
			$store = M('Admin')->where(array('role_id'=>3,'status'=>1))
	    					   ->field('id,username')
	    					   ->select();
			//所有业务员
			$salesman = M('Salesman')->where(array('store_id'=>$info['store']))->field('id,name')->select();
        	$list = M('ItemCate')->field('id,name')->where(array('pid'=>0))->select();
        	$this->assign('list',$list);  
			$this->assign('salesman',$salesman); 
			$this->assign('store',$store); 
            $response = $this->fetch();
            $this->ajax_return(1,'',$response,'add');
        }
	}



    public function edit()
    {
        $mod = D('MemberCard');
        $pk = $mod->getPk();
        if (IS_POST) {
            $data = I('post.');

			if(($data['salesman']&&!$data['status'])){
				$this->ajax_return(0,"您的操作有逻辑错误！");
			}
            $member_card=M('member_card')->where(array('id'=>$data['id']))->find();
		    if($data['price']>$member_card['face_value']){
		    	$this->ajax_return(0,"余额不能大于面值！");
		    }
			
			if($data['price']<$member_card['price']){
		    	$this->ajax_return(0,"不能减少余额！");
		    }
			
			//把分类ID组成字符串
			if($data['cate']) $data['cate'] = rtrim(implode(",",$data['cate']),','); 
			if($data['use_time']) $data['use_time'] = strtotime($data['use_time']); 
			
            if (false === $data = $mod->create($data)) {
                $this->ajax_return(0, $mod->getError());
            }
			
            if (false !== $mod->save($data)) {  
                $this->ajax_return(1, L('operation_success'), '', 'edit');
            } else {  
                $this->ajax_return(0, L('operation_failure'));
            }  
        } else {
            $id = I($pk, 'intval');
			$this->_relation && $mod->relation(true);
            $info = $mod->find($id);
			//所有门店
			$store = M('Admin')->where(array('role_id'=>3,'status'=>1))
	    					   ->field('id,username')
	    					   ->select();
			//所有业务员
			$salesman = M('Salesman')->where(array('store_id'=>$info['store']))->field('id,name')->select();
			//获取商品一级分类
			$list = M('ItemCate')->where(array('pid'=>0))->field('id,name')->select();
			//dump($info['store']);exit;
			$this->assign('list', $list);	
			$this->assign('salesman', $salesman);	
            $this->assign('store', $store);
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


	
	//	余额详情
	public function balance(){
		  $uid = I('id','intval');
		  $balance = M('Member_recharge')-> where(array('uid'=>$uid)) -> order('id desc')  -> select();	
		  $this->assign('balance', $balance);
		  if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1, '', $response);
            } 
            else {
                $this->display();
            }
    }
	
	
    //保存导入数据
//  public function save_import($data){
//  	$data_list = array();
//      foreach ($data as $k=>$v){
//      	if (false === $this->_mod->create(array('card'=>$v['A'],'price'=>$v['B']))) {
//              $this->error($this->_mod->getError());     
//				break;  
//          }
//			
//      	$data_list[] = array(
//      		'card' => $v['A'],
//      		'store' => $v['D'],
//      		'face_value' => $v['B'],
//      		'price' => $v['B'],
//      		'cate' => $v['C'],
//      		'add_time' => time(),
//			);  
//      }
//		
//      if($this->_mod->addAll($data_list)){
//          $this->success('导入成功');
//      }else{
//          $this->error('导入失败');  
//      }
//  }
	
	
	
	
	
	

}