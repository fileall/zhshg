<?php
namespace Admin\Controller;
class FlinkCateController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->set_mod('FlinkCate');
    }

    public function _before_index() {
        $this->sort = 'ordid';
        $this->order = 'ASC';
    }

    public function _before_delete() {
        $ids = trim(I('id'), ',');
        $ids_arr = explode(',', $ids);
        foreach ($ids_arr as $val) {
            if (M('flink')->where(array('cate_id'=>$val))->count()) {
                IS_AJAX && $this->ajax_return(0, '分类下面存在数据，不能删除！');
                $this->error('分类下面存在数据，不能删除！');
            }
        }
    }

    public function ajax_check_name() {
        $name = I('name','', 'trim');
        $id = I('id','', 'intval');
        if (D('FlinkCate')->name_exists($name, $id)) {
            $this->ajax_return(0, L('flink_cate_already_exists'));
        } else {
            $this->ajax_return(1);
        }
    }
}