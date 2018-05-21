<?php
namespace Admin\Controller;
class TraderController extends AdminCoreController {
	public function _initialize()
    {
        parent::_initialize();
        $this->_mod = D('Trader');
        $this->set_mod('Trader');
    }

}