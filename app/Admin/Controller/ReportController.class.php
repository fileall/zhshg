<?php
namespace Admin\Controller;
use Admin\Org\Image;
use Admin\Org\Tree;
class ReportController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Report');
        $this->_cate_mod = D('TrialCate');
        $this->set_mod('Report');
    }

    public function _before_index() {
        $res = $this->_cate_mod->field('id,name')->select();
        $cate_list = array();
        foreach ($res as $val) {
            $cate_list[$val['id']] = $val['name'];
        }
        $this->assign('cate_list', $cate_list);
        $this->assign('report_status',C('report_status'));
        $p = I('p',1,'intval');
        $this->assign('p',$p);

        //默认排序
        //$this->sort = 'ordid';
        //$this->order = 'ASC';
    }

    protected function _search() {
        $map = array();
        ($time_start = I('time_start','', 'trim')) && $map['add_time'][] = array('egt', strtotime($time_start));
        ($time_end = I('time_end','', 'trim')) && $map['add_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));
        ($status = I('status','', 'trim')) && $map['status'] = $status;
        ($keyword = I('keyword','', 'trim')) && $map['title'] = array('like', '%'.$keyword.'%');
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
        ));
        return $map;
    }

    public function _before_add()
    {
        $author = $_SESSION['pp_admin']['username'];
        $this->assign('author',$author);

        $site_name = D('setting')->where(array('name'=>'site_name'))->getField('data');
        $this->assign('site_name',$site_name);

        $first_cate = $this->_cate_mod->field('id,name')->where(array('pid'=>0))->order('ordid DESC')->select();
        $this->assign('first_cate',$first_cate);

        //取属性列表
        $attrs = D('Attr')->select();
        $this->assign('attrs',$attrs);
    }

    protected function _before_insert($data) {

        //上传图片
        if (!empty($_FILES['img']['name'])) {
            $art_add_time = date('ym/d/');
            $result = $this->_upload($_FILES['img'], 'report/' . $art_add_time, array('width'=>C('pin_article_img.width'), 'height'=>C('pin_article_img.height'), 'remove_origin'=>true));
            if ($result['error']) {
                $this->error($result['info']);
            } else {
                $ext = array_pop(explode('.', $result['info'][0]['savename']));
                $data['img'] = $art_add_time .'/'. str_replace('.' . $ext, '_thumb.' . $ext, $result['info'][0]['savename']);
            }
        }
        return $data;
    }

    public function _after_insert($id){
        //获取属性
        $attrs = I('attr');
        //添加
        $report_attr = array();
        foreach($attrs as $val){
            $report_attr[] = array(
                'report_id' => $id,
                'attr_id' => $val,
            );
        }
        M('Report_attr')->addAll($report_attr);
    }

    public function _before_edit(){
        $id = I('id','','intval');
        $article = $this->_mod->field('id,cate_id')->where(array('id'=>$id))->relation('attr')->find();
        $attr_list = array();
        $attr_list = array_map('array_shift', $article['attr']);
        $spid = $this->_cate_mod->where(array('id'=>$article['cate_id']))->getField('spid');
        if( $spid==0 ){
            $spid = $article['cate_id'];
        }else{
            $spid .= $article['cate_id'];
        }
        $this->assign('selected_ids',$spid);
        //取属性列表
        $attrs = D('Attr')->select();
        $this->assign('attrs',$attrs);
        $this->assign('attr_list',$attr_list);
        $this->assign('report_status',C('report_status'));
    }

    protected function _before_update($data) {
        if (!empty($_FILES['img']['name'])) {
            $art_add_time = date('ym/d/');
            //删除原图
            $old_img = $this->_mod->where(array('id'=>$data['id']))->getField('img');
            $old_img = $this->_get_imgdir($old_img,'report');
            is_file($old_img) && @unlink($old_img);
            //上传新图
            $result = $this->_upload($_FILES['img'], 'report/' . $art_add_time, array('width'=>C('pin_article_img.width'), 'height'=>C('pin_article_img.height'), 'remove_origin'=>true));
            if ($result['error']) {
                $this->error($result['info']);
            } else {
                $ext = array_pop(explode('.', $result['info'][0]['savename']));
                $data['img'] = $art_add_time .'/'. str_replace('.' . $ext, '_thumb.' . $ext, $result['info'][0]['savename']);
            }
        } else {
            unset($data['img']);
        }
        //获取属性
        $attrs = I('attr');
        //删除
        M('Report_attr')->where(array('report_id'=>$data['id']))->delete();
        //添加
        $report_attr = array();
        foreach($attrs as $val){
            $report_attr[] = array(
                'report_id' => $data['id'],
                'attr_id' => $val,
            );
        }
        M('Report_attr')->addAll($report_attr);
        return $data;
    }


    /**
     * ajax获取标签
     */
    public function ajax_gettags() {
        $title = I('title','', 'trim');
        if ($title) {
            $tags = D('Tag')->get_tags_by_title($title);
            $tags = implode(' ', $tags);
            $this->ajax_return(1, L('operation_success'), $tags);
        } else {
            $this->ajax_return(0, L('operation_failure'));
        }
    }

    public function get_attach_data($data){
        if (!empty($_FILES['img']['name'])) {
            $art_add_time = date('ym/d/');
            //删除原图
            $old_img = $this->_mod->where(array('id'=>$data['id']))->getField('img');
            $old_img = attach('$old_img','artcile');
            is_file($old_img) && @unlink($old_img);
            //上传新图
            $result = $this->_upload($_FILES['img'], 'article/' . $art_add_time, array('width'=>'130', 'height'=>'100', 'remove_origin'=>true));
            if ($result['error']) {
                $this->error($result['info']);
            } else {
                $ext = array_pop(explode('.', $result['info'][0]['savename']));
                $data['img'] = $art_add_time .'/'. str_replace('.' . $ext, '_thumb.' . $ext, $result['info'][0]['savename']);
            }
        } else {
            unset($data['img']);
        }

        return $data;
    }

}