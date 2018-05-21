<?php
namespace Admin\Controller;
class TagController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->set_mod('Tag');
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
        if (D('tag')->name_exists($name, $id)) {
            $this->ajax_return(0, L('标签已存在'));
        } else {
            $this->ajax_return(1);
        }
    }
}