<?php
namespace Admin\Controller;
class FlinkController extends AdminCoreController {
    public function _initialize()
    {
        parent::_initialize();
        $this->_mod = D('Flink');
        $this->set_mod('Flink');
    }

    protected function _search() {
        $map = array();
        ($cate_id = I('cate_id','', 'trim')) && $map['cate_id'] = array('eq', $cate_id);
        ($keyword = I('keyword','', 'trim')) && $map['name'] = array('like', '%'.$keyword.'%');
        $this->assign('search', array(
            'keyword' => $keyword,
            'cate_id' => $cate_id,
        ));
        return $map;
    }

    public function _before_index() {
        $this->list_relation = true;
        $this->_before_add();

        $this->assign('img_dir',$this->_get_imgdir());

        //默认排序
        $this->sort = 'ordid,id';
        $this->order = 'DESC';
    }

    public function _before_add() {
        $cate_list = D('FlinkCate')->where(array('status'=>1))->select();
        $this->assign('cate_list',$cate_list);
    }

    public function _before_edit()
    {
        $this->_before_add();
        $this->assign('img_dir',$this->_get_imgdir());
    }

    public function ajax_upload_img() {
        //上传图片
        if (!empty($_FILES['img']['name'])) {
            $result = $this->_upload($_FILES['img'], 'flink');
            if ($result['error']) {
                $this->ajax_return(0, $result['info']);
            } else {
                $data['img'] = $result['info'][0]['savename'];
                $this->ajax_return(1, L('operation_success'), $data['img']);
            }
        } else {
            $this->ajax_return(0, L('illegal_parameters'));
        }
    }

    public function ajax_check_name()
    {
        $name = I('name','', 'trim');
        $id = I('id',0, 'intval');
        if ($this->_mod->name_exists($name, $id)) {
            $this->ajax_return(0, '链接名称已经存在');
        } else {
            $this->ajax_return();
        }
    }

    /**
     * 友情链接图片上传目录
     *
     * @staticvar null $dir
     * @return string
     */
    protected function _get_imgdir() {
        static $dir = null;
        if ($dir === null) {
            $dir = './data/attachment/flink/';
        }
        return $dir;
    }
}