<?php
namespace Admin\Controller;
use Think\Page;
class YaoyueController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->work = D('work');
		$this->set_mod('work');
    }
	
	protected function _before_index() {
		//$map = array();
		//$this->assign('index', array('types' => 2));
        //return $map;
	}
	
	protected function _search() {
        $map = array();
		($types = I('types',0,'intval')) && $map['types'] = $types;
        ($time_start = I('time_start','', 'trim')) && $map['add_time'][] = array('egt', strtotime($time_start));
        ($time_end = I('time_end','', 'trim')) && $map['add_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));
        $status = I('status','', 'trim');
        if($status !== ''){
        	$map['status'] = $status;
		}
        ($keyword = I('keyword','', 'trim')) && $map['wname'] = array('like', '%'.$keyword.'%');
        $cate_id = I('cate_id','', 'intval');
        $selected_ids = '';
        if ($cate_id) {
            $id_arr = $this->_cate_mod->get_child_ids($cate_id, true);
            $map['cate_id'] = array('IN', $id_arr);
            $spid = $this->_cate_mod->where(array('id'=>$cate_id))->getField('spid');
            $selected_ids = $spid ? $spid . $cate_id : $cate_id;
        }
        $this->assign('search', array(
            'time_start' => $time_start,
            'time_end' => $time_end,
            'cate_id' => $cate_id,
            'selected_ids' => $selected_ids,
            'status'  => $status,
            'keyword' => $keyword,
			'types' => $types,
        ));
        return $map;
    }
//  上架
	public function shelves1(){
	 $id = I('id','', 'intval');	
	 $shelves = $this->work ->where(array('id'=>$id))->data(array('status'=>0))->save();
	   $this->ajax_return($shelves);
	}
//	下架
	public function shelves2(){
	 $id = I('id','', 'intval');	
	 $shelves = $this->work ->where(array('id'=>$id))->data(array('status'=>2))->save();
	   $this->ajax_return($shelves);
	}
    /**
     * 列表页面
     */
//  public function index() {
//      $list = $this->work->where(array('status'=>1))->order('hits desc,id')->select();
//	   $this->assign('list',$list);
//      $this->display();
//  }
	
	
	
}