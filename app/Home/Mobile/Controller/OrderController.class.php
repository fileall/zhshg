<?php
namespace Mobile\Controller;
class OrderController extends HomeController {
    public function _initialize() {
        parent::_initialize();
        $member = D('member')->find(is_login());
        $this->assign('member', $member);
        if(!is_login()){
			$this->redirect('Login/enter');
		}
    }
   
  
}