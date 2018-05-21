<?php
namespace Admin\Controller;
class BuyerBindController extends AdminCoreController {
	public function _initialize()
    {
        parent::_initialize();
        $this->_mod = D('BuyerBind');
        $this->set_mod('BuyerBind');
    }

    public function _before_index(){
        $this->list_relation = true;
    }

    public function _before_add(){
        $member_list = M('Member')->where(array('member_type'=>2))->limit(100)->select();

        $this->assign('member_list',$member_list);
        $this->assign('bind_status',C('bind_status'));

    }

}