<?php
namespace Admin\Controller;
class MerchantBindController extends AdminCoreController {
	public function _initialize()
    {
        parent::_initialize();
        $this->_mod = D('MerchantBind');
        $this->set_mod('MerchantBind');
    }

    public function _before_index(){
        $this->list_relation = true;
    }

    public function _before_add(){
        $member_list = M('Member')->where(array('member_type'=>1))->limit(100)->select();
        $this->assign('member_list',$member_list);
        $this->assign('bind_status',C('bind_status'));

    }

}