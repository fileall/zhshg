<?php
namespace Admin\Controller;
class SearchKeywordsController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('SearchKeywords');
        $this->set_mod('SearchKeywords');
    }

    public function _before_index(){
        //默认排序
        $this->sort = 'ordid';
        $this->order = 'ASC';
    }

}