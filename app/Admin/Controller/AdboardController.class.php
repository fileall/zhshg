<?php
namespace Admin\Controller;
class AdboardController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Adboard');
        $this->set_mod('Adboard');
    }

    protected function _before_insert($data) {
        if ($this->_mod->name_exists($data['name'])) {
            $this->ajax_return(0, L('adboard_already_exists'));
        }
    }

    protected function _before_update($data) {
        if ($this->_mod->name_exists($data['name'], $data['id'])) {
            $this->ajax_return(0, L('adboard_already_exists'));
        }
    }

    public function ajax_check_name() {
        $name = I('name','', 'trim');
        $id = I('id',0, 'intval');
        if ($this->_mod->name_exists($name, $id)) {
            $this->ajax_return(0, L('adboard_already_exists'));
        } else {
            $this->ajax_return();
        }
    }
   
}