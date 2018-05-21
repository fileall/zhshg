<?php
namespace Admin\Controller;
class AdController extends AdminCoreController {
	private $_ad_type = array('image'=>'图片', 'code'=>'代码', 'flash'=>'Flash', 'text'=>'文字');
    public $list_relation = true;
    public function _initialize() {
        parent::_initialize();
        $this->set_mod('Ad');
        $this->_mod = D('Ad');
        $this->_adboard_mod = D('Adboard');
    }

    public function _search() {
        $map = array();
        ($start_time_min = I('start_time_min','', 'trim')) && $map['start_time'][] = array('egt', strtotime($start_time_min));
        ($start_time_max = I('start_time_max','', 'trim')) && $map['start_time'][] = array('elt', strtotime($start_time_max)+(24*60*60-1));
        ($end_time_min = I('end_time_min','', 'trim')) && $map['end_time'][] = array('egt', strtotime($end_time_min));
        ($end_time_max = I('end_time_max','', 'trim')) && $map['end_time'][] = array('elt', strtotime($end_time_max)+(24*60*60-1));
        $board_id = I('board_id','', 'intval');
        $board_id && $map['board_id'] = $board_id;
        $style = I('style','', 'trim');
        $style && $map['type'] = array('eq',$style);
        ($keyword = I('keyword','', 'trim')) && $map['name'] = array('like', '%'.$keyword.'%');
        $this->assign('search', array(
            'start_time_min' => $start_time_min,
            'start_time_max' => $start_time_max,
            'end_time_min' => $end_time_min,
            'end_time_max' => $end_time_max,
            'board_id' => $board_id,
            'style'   => $style,
            'keyword' => $keyword,
        ));
        return $map;
    }

    public function _before_index() {
        $big_menu = array(
            'title' => L('ad_add'),
            'iframe' => U('ad/add'),
            'id' => 'add',
            'width' => '520',
            'height' => '410',
        );
        $this->assign('big_menu', $big_menu);//弹窗

        $res = $this->_adboard_mod->field('id,name')->select();
        $board_list = array();
        foreach ($res as $val) {
            $board_list[$val['id']] = $val['name'];
        }
        $this->assign('board_list', $board_list);
        $this->assign('ad_type_arr', $this->_ad_type);
    }

    public function _before_add() {
        $result = $this->_adboard_mod->where(array('status'=>1))->select();
        $adboard_types = $this->_adboard_mod->get_tpl_list();
        $adboards = array();
        foreach ($result as $val) {
            $val['allow_type'] = implode('|', $adboard_types[$val['tpl']]['allow_type']);
            $adboards[] = $val;
        }
        $this->assign('adboards', $adboards);
        $this->assign('ad_type_arr', $this->_ad_type);
    }

    protected function _before_insert($data) {
        //判断开始时间和结束时间是否合法
        $data['start_time'] = strtotime($data['start_time']);
        $data['end_time'] = strtotime($data['end_time']);
        if ($data['start_time'] >= $data['end_time']) {
            $this->ajax_return(0, L('ad_endtime_less_startime'));
        }

        switch ($data['type']) {
            case 'text':
                $data['content'] = I('text','', 'trim');
                break;
            case 'image':
                $data['content'] = I('img','', 'trim');
                break;
            case 'code':
                $data['content'] = I('code','', 'trim');
                break;
            case 'flash':
                $data['content'] = I('flash','', 'trim');
                break;
            default :
                $this->ajax_return(0, L('ad_type_error'));
                break;
        }
        return $data;
    }

    public function _before_edit() {
        $id = I('id',0, 'intval');
        $board_id = $this->_mod->where(array('id'=>$id))->getField('board_id');
        $board_info = $this->_adboard_mod->field('name,width,height')->where(array('id'=>$board_id))->find();
        $this->assign('board_info', $board_info);
        $this->assign('ad_type_arr', $this->_ad_type);
    }

    protected function _before_update($data) {
        //判断开始时间和结束时间是否合法
        $data['start_time'] = strtotime($data['start_time']);
        $data['end_time'] = strtotime($data['end_time']);
        if ($data['start_time'] >= $data['end_time']) {
            $this->ajax_return(0, L('ad_endtime_less_startime'));
        }
        switch ($data['type']) {
            case 'text':
                $data['content'] = I('text','', 'trim');
                break;
            case 'image':
                $data['content'] = I('img','', 'trim');
                break;
            case 'code':
                $data['content'] = I('code','', 'trim');
                break;
            case 'flash':
                $data['content'] = I('flash','', 'trim');
                break;
            default :
                $this->ajax_return(0, L('ad_type_error'));
                break;
        }
        return $data;
    }

    //上传图片
    public function ajax_upload_img() {
        $type = I('type', 'img', 'trim');
        if (!empty($_FILES[$type]['name'])) {
            $dir = date('ym/d/');
            $result = $this->_upload($_FILES[$type], 'advert/'. $dir );
            if ($result['error']) {
                $this->ajax_return(0, $result['info']);
            } else {
                $savename = $dir . $result['info'][0]['savename'];
                $this->ajax_return(1, L('operation_success'), $savename);
            }
        } else {
            $this->ajax_return(0, L('illegal_parameters'));
        }
    }
}