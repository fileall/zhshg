<?php
namespace Admin\Controller;
class ColumnController extends AdminCoreController {
	private $_ad_type = array('image'=>'图片', 'code'=>'代码', 'flash'=>'Flash', 'text'=>'文字');
    public $list_relation = true;
    public function _initialize() {
        parent::_initialize();
        $this->set_mod('Ad');
        $this->_mod = D('Ad');
        $this->_adboard_mod = D('Adboard');
    }
	
	public function index() {
		$this->display();
	}
	
	public function column_list(){
		$this->display();
	}
}