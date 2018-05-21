<?php
namespace Mobile\Controller;
class AjaxController extends HomeController {
    public function _initialize() {
        parent::_initialize();
    }
    
    
   //流加载的方法  银币收支
    public function ajax_liu_yb(){
    	$page = I('page');
		//总页数
		$count=D('MemberRecharge')->where(array('dingdan'=>0,'member_id'=>is_login(),'item_type'=>6))
			->order('add_time desc')->count();
		
	    $pages =   ceil($count/ 6); //上取整    总条数/每页条数
	    $list_aj[0] = $pages;//总页数
	    //每页数据
		$list_ajs=D('MemberRecharge')->where(array('dingdan'=>0,'member_id'=>is_login(),'item_type'=>6))
			->order('add_time desc')->limit(($page -1) * 6 , 6)->select();	
				
        $this->assign('a',$list_ajs);
        $a=$this->fetch('w_ingotYB_liu');
        
        $list_aj[1] = $a;//数据
        
		echo  json_encode($list_aj);
	    	
		
	}
  
}