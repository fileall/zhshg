<?php
namespace Admin\Controller;
use Think\Page;
class ItemBrandController extends AdminCoreController {
	public function _initialize() {
        parent::_initialize();
        $this->_mod = D('ItemBrand');
        $this->set_mod('ItemBrand');
    }

    protected function _search() {
        $map = array();
        ($name = I('name','', 'trim')) && $map['name'] = array('like', '%'.$name.'%');
        $this->assign('search', array(
            'name' => $name,
        ));
        return $map;
    }
	public function _before_index(){
		$this->list_relation=true;
	}
}