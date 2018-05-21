<?php
namespace Admin\Controller;
class ActivitySetController extends AdminCoreController {
    public function _initialize() {
    	
        parent::_initialize();
		
        $this->_mod = D('ActivitySet');
        $this->_cate_mod = D('ActivitySet');
		$this->set_mod('ActivitySet');
		//品牌
		
		$brand = D('itemBrand')->where(array('status'=>1))->order('ordid desc,id') ->select();
		$this->assign('brand',$brand);
		
    }
	
//	public function index(){
//		
//		$list=M('activity_set')->select();
//		
//		$this->assign("list",$list);
//		$this->display();
//	}
	
	public function add(){
		if(IS_POST){
			$data['status']=I('status');
			$data['starttime']=I('starttime');
			$data['endtime']=I('endtime');
			$a=M('activity_set')->add($data);
			if($a){
				 IS_AJAX && $this->ajax_return(1, L('operation_success'), '', 'add');
                $this->success(L('operation_success'));
			}else{
				IS_AJAX && $this->ajax_return(0, L('operation_failure'));
                $this->error(L('operation_failure'));
			}
		}else{
			$response = $this->fetch();
            $this->ajax_return(1,'',$response,'add');
		}
		
	}
	
	public function edit(){
		$id=I('id');
		 
		if(IS_POST){
			$data['status']=I('status');
			$data['starttime']=I('starttime');
			$data['endtime']=I('endtime');
			$a=M('activity_set')->where(array('id'=>$id))->save($data);
			if($a){
				 IS_AJAX && $this->ajax_return(1, L('operation_success'), '', 'edit');
                $this->success(L('operation_success'));
			}else{
				IS_AJAX && $this->ajax_return(0, L('operation_failure'));
                $this->error(L('operation_failure'));
			}
		}else{
			$this->assign('id',$id);
			$info=M('activity_set')->where(array('id'=>$id))->find();
			
			$this->assign('info',$info);
			$response = $this->fetch();
            $this->ajax_return(1,'',$response,'add');
		}
		
	}
}

?>
	