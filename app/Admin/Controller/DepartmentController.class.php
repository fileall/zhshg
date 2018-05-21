<?php
namespace Admin\Controller;
class DepartmentController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Department');
        $this->set_mod('Department');
    }
    

}