<?php
namespace Admin\Controller;
use Admin\Org\Tree;
class MemberCardCateController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('MemberCardCate');
		$this->set_mod('MemberCardCate');
    }
	
	public function _before_index(){
		//获取商品一级分类
		$cate_list = M('ItemCate')->field('id,name')->where(array('pid'=>0))->select();
		foreach($cate_list as $v){
			$cate[$v['id']] = $v['name'];
		}
		//dump($cate);
		
		$this->assign('cate_list',$cate);
	}
	
    public function _before_add(){  
        $list = M('ItemCate')->field('id,name')->where(array('pid'=>0))->select();
    	$this->assign('list',$list); 
    }
	
    public function _before_insert($data){
        //把分类ID组成字符串
		if($data['cate']) $data['cate'] = rtrim(implode(",",$data['cate']),','); 
		
        return $data;
    }

    public function _before_edit(){
    	$this->_before_add();
    }

    public function _before_update($data){  
        return $this->_before_insert($data);  
    }	
	  
	

}