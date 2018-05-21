<?php
namespace Admin\Controller;
class ZhuantiController extends AdminCoreController {
	public function index(){
		$template=M("template");
		$rest=$template->where("status=1")->select();
		$this->assign("rest",$rest);
		$this->display();
	}
	
	public function updatezt(){
		$changezt=I("get.changezt");
		$template=M("template");
		$rest=$template->where()->save();
	}

}
?>