<?php
namespace Admin\Controller;
/*
 * 意见反馈
 */
class OpinionController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Opinion');
        $this->set_mod('Opinion');
    }


    protected function _search() {
        $map = array();
        ($time_start = I('time_start','', 'trim')) && $map['add_time'][] = array('egt', strtotime($time_start));
        ($time_end = I('time_end','', 'trim')) && $map['add_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));
        ($keyword = I('keyword','', 'trim')) && $map['content'] = array('like', '%'.$keyword.'%');

        $this->assign('search', array(
            'time_start' => $time_start,
            'time_end' => $time_end,
            'keyword' => $keyword,
        ));
        return $map;
    }

    public function _after_list($list) {
        if(!empty($list)){
            //主人表会员名
            $member = M('member')->where(['id'=>['in',array_column($list,'user_id')]])->getField('id,nickname');
            $this->assign('member',$member);
        }
        return $list;
    }


    

}