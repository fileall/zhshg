<?php
namespace Admin\Controller;
use Admin\Org\Image;
use Admin\Org\Tree;
class RangliRuleController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('RangliRule');
        $this->set_mod('RangliRule');
    }

    public function _before_index() {
        //默认排序
        $this->sort = 'id';
        $this->order = 'ASC';
    }
    
}