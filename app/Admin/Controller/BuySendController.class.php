<?php
namespace Admin\Controller;
class BuySendController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('BuySend');
        $this->set_mod('BuySend');

    }

    public function _before_index() {
        //默认排序
        $this->sort = 'money';
        $this->order ='ASC';
    }



}







