<?php
namespace Admin\Controller;
class SchoolController extends AdminCoreController {
	public function _initialize() {
        parent::_initialize();
        $this->_mod = D('School');
        $this->set_mod('School');
    }

   
}