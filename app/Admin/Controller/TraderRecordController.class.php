<?php
namespace Admin\Controller;
class TraderRecordController extends AdminCoreController {
	public function _initialize()
    {
        parent::_initialize();
        $this->_mod = D('TraderRecord');
        $this->set_mod('TraderRecord');
    }
    public function _before_index(){
        $this->list_relation = true;
    }

    public function _before_add(){
        $loan = D('Loan')->field('id,title')->where(array('status'=>5))->select();
        $this->assign('loan',$loan);
    }

    public function _before_edit(){
        $loan = D('Loan')->field('id,title')->where(array('status'=>5))->select();
        $this->assign('loan',$loan);
    }
}