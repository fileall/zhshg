<?php
namespace Admin\Controller;
use Think\Page;
class ItemCommentController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = M('ItemComment');
		$this->set_mod('ItemComment');
    }

    public function index() {   
		($keyword=I('keyword','','trim')) && $where['memos|item_name']=array('like',"%$keyword%");
        $this->assign('keyword', $keyword);
        
		($score=I('score','','trim')) && $where['score']=$score;
        $this->assign('score', $score);
		
		($time_start=I('time_start','','trim')) && $where['add_time'][]=array('egt',strtotime($time_start));
		($time_end=I('time_end','','trim')) && $where['add_time'][]=array('elt',strtotime($time_end));
        $this->assign('time_start', $time_start);
		$this->assign('time_end', $time_end);
		
		$pscore=C('pscore');
        $this->assign('pscore', $pscore);

        $count = $this->_mod->where($where)->count();
        $pager = new Page($count,20);
        $list  = $this->_mod->where($where)->limit($pager->firstRow.','.$pager->listRows)->select();
		foreach($list as $k=>$v){
			$member=M('member')->where(array('id'=>$v['member_id']))->find();
			if($member['nickname']){
				$list[$k]['nickname']=$member['nickname'];
			}else{
				$list[$k]['nickname']=$member['mobile'];
			}
		}
        $this->assign('list',$list);
        $this->assign('page',$pager->show());
  
        $this->assign('list_table', true);

        $this->display();
    }
    
    /**
     * 删除
     */
//  public function delete(){
//      $ids = trim(I('id'), ',');
//      if ($ids) {
//          $item_ids = $this->_mod->where(array('id'=>array('in', $ids)))->getField('item_id', true);
//          if (false !== $this->_mod->delete($ids)) {
//              $item_mod = D('item');
//              foreach ($item_ids as $item_id) {
//                  $item_mod->update_comments($item_id);
//              }
//              IS_AJAX && $this->ajaxReturn(1, L('operation_success'));
//          } else {
//              IS_AJAX && $this->ajaxReturn(0, L('operation_failure'));
//          }
//      } else {
//          IS_AJAX && $this->ajaxReturn(0, L('illegal_parameters'));
//      }
//  }


	
}