<?php
namespace Admin\Controller;
class BadwordController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->set_mod('Badword');
    }

    protected function _search() {
        $map = array();
        ($word_type = I('word_type','', 'trim')) && $map['word_type'] = array('eq', $word_type);
        ($keyword = I('keyword','', 'trim')) && $map['badword'] = array('like', '%'.$keyword.'%');
        $this->assign('search', array(
            'keyword' => $keyword,
            'word_type' => $word_type,
        ));
        return $map;
    }

    public function ajax_check_name() {
        $name = I('badword','', 'trim');
        $id = I('id',0, 'intval');
        if (D('Badword')->name_exists($name, $id)) {
            $this->ajax_return(0, L('该敏感词已存在'));
        } else {
            $this->ajax_return(1);
        }
    }
   
}