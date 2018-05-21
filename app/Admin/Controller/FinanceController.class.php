<?php
namespace Admin\Controller;
class FinanceController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Finance');
		$this->set_mod('Finance');
    }

    public function _before_index() {

        //状态信息
        $this->assign('loan_status',C('loan_status'));

        //默认排序
        $this->list_relation = true;

        $this->assign('loan_config', C('loan_config'));
        $this->assign('finance_log_type', C('finance_log_type'));
    }

    protected function _search() {
        $map = array();
        //'status'=>1
        ($time_start = I('time_start','', 'trim')) && $map['create_time'][] = array('egt', strtotime($time_start));
        ($time_end = I('time_end','', 'trim')) && $map['create_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));

        ($log_type = I('log_type','', 'trim')) && $map['log_type'] = $log_type;
        ($keyword = I('keyword','', 'trim')) && $map['order_id'] = array('like', '%'.$keyword.'%');
        $this->assign('search', array(
            'time_start' => $time_start,
            'time_end' => $time_end,
            'log_type' =>$log_type,
            'keyword' => $keyword,
        ));
        return $map;
    }
}