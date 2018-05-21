<?php
namespace Admin\Controller;
class MerchantBindApplyController extends AdminCoreController {
	public function _initialize()
    {
        parent::_initialize();
        $this->_mod = D('MerchantBindApply');
        $this->set_mod('MerchantBindApply');
    }

    public function _before_index(){
        $this->list_relation = true;
    }

}