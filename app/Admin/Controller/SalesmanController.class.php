<?php
namespace Admin\Controller;
use Admin\Org\Tree;
class SalesmanController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Salesman');
		$this->set_mod('Salesman');
    }
	
	public function _before_index(){
		//所有门店
		$store = M('Admin')->where(array('role_id'=>3,'status'=>1))
    					   ->field('id,username')
    					   ->select();
		//重组门店数据				   
		foreach($store as $v){
			$store_name[$v['id']] = $v['username'];	
		}
						   
		$this->assign('store_name',$store_name);
		$this->assign('store',$store);
	}
	
	//返回所属门店业务员
	public function ajax_salesman(){
		if(IS_AJAX){
			$list = $this->_mod->where(array('store_id'=>I('id','','intval')))
			                   ->field('id,name')
							   ->select();
			echo json_encode($list);				   
		}
	}
	
	public function after_index($list,$map){
		($time_start=I('time_start'))&&$where['add_time']=array('egt',strtotime($time_start));
		($time_end=I('time_end'))&&$where['add_time']=array('elt',strtotime($time_end)+24*60*60);	
		if(!$time_start && !$time_end){
			//默认计算当前月的提成
			$where['add_time'][] = array('egt', strtotime(date("Y-m-1")));
		}	
		foreach($list as $k=>$v){
			$where['salesman']=$v['id'];
			$list[$k]['count']=M('MemberCard')->where($where)->count();
		}
	 
		return $list;
	}
	
	
    protected function _search(){
        $map = array();$card = array();
        if($store_id = I('store_id','', 'trim')){
        	$map['store_id'] = $store_id;
        	$card['store'] = $store_id;
		}
			
        if($salesman = I('salesman','', 'trim')){
        	$map['id'] = $salesman;
			$card['salesman'] = $salesman;
		}
			
		//店长进入
		if(check_dz()){
			$map['store_id'] = $_SESSION['admin']['id'];
			$card['store'] = $_SESSION['admin']['id'];
		}	
		
		($time_start = I('time_start','', 'trim')) && $card['use_time'][] = array('egt', strtotime($time_start));
        ($time_end = I('time_end','', 'trim')) && $card['use_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));
		
		if(!$time_start && !$time_end){
			//默认计算当前月的提成
			$card['use_time'][] = array('egt', strtotime(date("Y-m-1")));
			$time_start = date("Y-m-1");	
		}
		
		$card_list = M('MemberCard')->field('store,salesman,SUM(face_value) as value')
		                            ->where($card)
									->where(array('status'=>1))
									->group('salesman')
									->select();
		//计算提成
		$ti_nums = 0;
		$ti_cheng = array();							
		foreach($card_list as $v){
			$ti_cheng[$v['salesman']] = $v['value']*C('pin_y_di');
			$ti_nums = $ti_nums + ($v['value']*C('pin_d_ti')); 	
		}
       
		$this->assign('ti_nums',$ti_nums);							
		$this->assign('ti_cheng',$ti_cheng);							
        $this->assign('search', array(
        	'store_id' =>$store_id,
            'time_end'  => $time_end,
            'time_start'  => $time_start,
            'salesman'  => $salesman,
        ));
	  
        return $map;
    }
	
	
    public function _before_add(){
    	$store = M('Admin')->where(array('role_id'=>3,'status'=>1))
    					   ->field('id,username')
    					   ->select();
						   
		$this->assign('store',$store);						   
    }

    public function _before_edit()
    {
    	$this->_before_add();  
    }

	
	
	
	
	
	

}