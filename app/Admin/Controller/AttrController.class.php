<?php
namespace Admin\Controller;
class AttrController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->set_mod('Attr');
    }
	protected function _search() {
        $map = array();
        ($keyword = I('keyword','', 'trim')) && $map['name'] = array('like', '%'.$keyword.'%');
        $this->assign('search', array(
            'keyword' => $keyword,
        ));
        return $map;
    }

	public function ajax_check_name() {
        $name = I('name','', 'trim');
        $id = I('id','', 'intval');
        if (D('Attr')->name_exists($name, $id)) {
            echo 0;
        } else {
            echo 1;
        }
    }
}