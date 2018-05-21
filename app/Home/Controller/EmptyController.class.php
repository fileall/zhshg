<?php
namespace Home\Controller;
class EmptyController extends HomeController {
	public function _initialize() {
        parent::_initialize();
        $this->_cate_mod = D('ArticleCate');
        $this->_mod = D('Article');
    }
    public function index(){
        $get_target = strtolower(CONTROLLER_NAME);
        //默认执行新闻栏目
        $ArticleCate = M('ArticleCate');
        $category_id = $ArticleCate->where(array('alias'=>$get_target))->getField('id');
        if($category_id){
            $News_A = A('News');
            $News_A->index($category_id);
        }
    }
}