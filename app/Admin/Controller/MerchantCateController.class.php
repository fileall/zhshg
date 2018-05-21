<?php

namespace Admin\Controller;

use Admin\Org\Tree;

class MerchantCateController extends AdminCoreController {

    public function _initialize() {

        parent::_initialize();

        $this->_mod = D('MerchantCate');

        $this->set_mod('MerchantCate');

    }



    public function index() {

        $sort = I("sort", 'ordid', 'trim');
        $order = I("order", 'ASC', 'trim');
        $tree = new Tree();
			
        $tree->icon = array('│ ','├─ ','└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $result = $this->_mod->order($sort . ' ' . $order)->select();
        $array = array();

        foreach($result as $r) {
            $r['str_img'] = $r['img'] ? '<span class="img_border"><img src="'.attach($r['img'], 'member_cate').'" style="width:26px; height:26px;" class="J_preview" data-bimg="'.attach($r['img'], 'member_cate').'" /></span>' : '';
            $r['str_status'] = '<img data-tdtype="toggle" data-id="'.$r['id'].'" data-field="status" data-value="'.$r['status'].'" src="'.C('TMPL_PARSE_STRING.__STATIC__').'/images/toggle_' . ($r['status'] == 0 ? 'disabled' : 'enabled') . '.gif" />';
            $r['str_manage'] = '<a href="javascript:;" class="J_showdialog" data-uri="'.U('MerchantCate/add',array('pid'=>$r['id'])).'" data-title="'.L('add_item_cate').'" data-id="add" data-width="500" data-height="50">'.L('add_item_subcate').'</a> |
                                <a href="javascript:;" class="J_showdialog" data-uri="'.U('MerchantCate/edit',array('id'=>$r['id'])).'" data-title="'.L('edit').' - '. $r['name'] .'" data-id="edit" data-width="500" data-height="50">'.L('edit').'</a> |
                                <a href="javascript:;" class="J_confirmurl" data-acttype="ajax" data-uri="'.U('MerchantCate/delete',array('id'=>$r['id'])).'" data-msg="'.sprintf(L('confirm_delete_one'),$r['name']).'">'.L('delete').'</a>';
            $r['parentid_node'] = ($r['pid'])? ' class="child-of-node-'.$r['pid'].'"' : '';
            $array[] = $r;

        }

        $str  = "<tr id='node-\$id' \$parentid_node>
                <td align='center'><input type='checkbox' value='\$id' class='J_checkitem'></td>
                <td align='center'>\$id</td>
                <td align='left'>\$spacer<span data-tdtype='edit' data-field='name' data-id='\$id' class='tdedit'  style='color:\$fcolor'>\$name</span></td>
                <td align='center'><span data-tdtype='edit' data-field='ordid' data-id='\$id' class='tdedit'>\$ordid</span></td>
                <td align='center'>\$str_status</td>
                <td align='center'>\$str_manage</td>
                </tr>";

        $tree->init($array);

        $list = $tree->get_tree(0, $str);
        $this->assign('list', $list);
        $this->assign('list_table', true);
        $big_menu = array(
            'title' => '新增',
            'iframe' => U('merchant_cate/add'),
            'id' => 'add',
            'width' => '500 ',
            'height' => '50'
        );
        $this->assign('big_menu', $big_menu);//新增一级分类
        $this->display();	

    }



    /**

     * 添加子菜单上级默认选中本栏目

     */

    public function _before_add() {
        $pid = I('pid', 0, 'intval');
        if ($pid) {
            $spid = $this->_mod->where(array('id'=>$pid))->getField('spid');
            $spid = $spid ? $spid.$pid : $pid;
            $this->assign('spid', $spid);
        }
    }
    public function add() {
        $mod = D($this->_name);
        if (IS_POST) {
            if (false === $data = $mod->create()) {

                IS_AJAX && $this->ajax_return(0, $mod->getError());

                $this->error($mod->getError());

            }

            if (method_exists($this, '_before_insert')) {
                $data = $this->_before_insert($data);
            }

            if( $mod->add($data) ){

                if( method_exists($this, '_after_insert')){

                    $id = $mod->getLastInsID();

                    $this->_after_insert($id,$data);

                }
                IS_AJAX && $this->ajax_return(1, L('operation_success'), '', 'add');
                $this->success(L('operation_success'));
            } else {
                IS_AJAX && $this->ajax_return(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }
        } else {
            $this->assign('open_validator', true);
            if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1,'',$response,'add');
            } else {
                $this->display();
            }
        }
    }

    /**
     * 修改
     */
//    public function edit()
//    {
//
//        $mod = D($this->_name);
//        $pk = $mod->getPk();
//        if (IS_POST) {
//            if (false === $data = $mod->create()) {
//                IS_AJAX && $this->ajax_return(0, $mod->getError());
//                $this->error($mod->getError());
//            }
//
//            if (method_exists($this, '_before_update')) {
//                $data = $this->_before_update($data);
//            }
//
//            if (false !== $mod->save($data)) {
//                if( method_exists($this, '_after_update')){
//                    $id = $data['id'];
//                    $this->_after_update($id,$data);
//                }
//                IS_AJAX && $this->ajax_return(1, L('operation_success'), '', 'edit');
//                $this->success(L('operation_success'));
//            } else {
//                IS_AJAX && $this->ajax_return(0, L('operation_failure'));
//                $this->error(L('operation_failure'));
//            }
//        } else {
//            $id = I($pk, 'intval');
//            $this->_relation && $mod->relation(true);
//            $info = $mod->find($id);
//            $this->assign('info', $info);
//            $this->assign('open_validator', true);
    //分类
//$cate_id = I('cate_id',0,'intval');
//if ($cate_id) {
//
//$id_arr = $this->_mod_cate->get_child_ids($cate_id, true);
//$map['cate_id'] = array('IN', $id_arr);
//$spid = $this->_mod_cate->where(array('id'=>$cate_id))->getField('spid');
//if( $spid==0 ){
//$spid = $cate_id;
//}else{
//    $spid .= $cate_id;
//}
//}

//            if (IS_AJAX) {
//                $response = $this->fetch();
//                $this->ajax_return(1, '', $response);
//            } else {
//                $this->display();
//            }
//        }
//    }


    /**

     * 入库数据整理

     */

    protected function _before_insert($data = '') {

        //检测分类是否存在

        if($this->_mod->name_exists($data['name'], $data['pid'])){

            $this->ajax_return(0, L('item_cate_already_exists'));

        }

        //生成spid

        $data['spid'] = $this->_mod->get_spid($data['pid']);

        if(  substr_count($data['spid'],'|') >=2){
            $this->ajax_return(0, '最多添加2级分类');
        }
        return $data;

    }



    /**
     * 修改提交数据
     */
    protected function _before_update($data = '') {

        if ($this->_mod->name_exists($data['name'], $data['pid'], $data['id'])) {

            $this->ajax_return(0, L('item_cate_already_exists'));

        }

        $item_cate = $this->_mod->field('pid')->where(array('id'=>$data['id']))->find();

        if ($data['pid'] != $item_cate['pid']) {

            //不能把自己放到自己或者自己的子目录们下面

            $wp_spid_arr = $this->_mod->get_child_ids($data['id'], true);

            if (in_array($data['pid'], $wp_spid_arr)) {

                $this->ajax_return(0, L('cannot_move_to_child'));

            }

            //重新生成spid

            $data['spid'] = $this->_mod->get_spid($data['pid']);

        }

        return $data;

    }



    /**

     * 批量移动分类

     */

    public function move() {

        if (IS_POST) {

            $data['pid'] = $this->_post('pid', 'intval');

            $ids = $this->_post('ids');

            //检查移动分类是否合法

            //获取目标分类信息

            $target_spid = $this->_mod->where(array('id'=>$data['pid']))->getField('spid');

            $ids_arr = explode(',', $ids);

            foreach ($ids_arr as $id) {

                if (false !== strpos($target_spid . $data['pid'].'|', $id.'|')) {

                    $this->ajax_return(0, L('cannot_move_to_child'));

                }

            }

            //修改PID和SPID

            $data['spid'] = $this->_mod->get_spid($data['pid']);

            $this->_mod->where(array('id' => array('in', $ids)))->save($data);

            $this->ajax_return(1, L('operation_success'), '', 'move');

        } else {

            $ids = trim(I('id'), ',');

            $this->assign('ids', $ids);

            $resp = $this->fetch();

            $this->ajax_return(1, '', $resp);

        }

    }



    /**

     * 分类标签列表

     */

    public function tag_list() {

        $cate_id = I('cate_id',0, 'intval');

        $keyword = I('keyword','', 'trim');

        $cate_tag_mod = M('item_cate_tag');

        $db_pre = C('DB_PREFIX');

        $table = $db_pre.'item_cate_tag';

        $pagesize = 20;

        $map = array($table.'.cate_id'=>$cate_id);

        $keyword && $map['t.name'] = array('like', '%'.$keyword.'%');

        $join = $db_pre.'tag t ON t.id = '.$table.'.tag_id';

        $count = $cate_tag_mod->where($map)->join($join)->count();

        $pager = new Page($count, $pagesize);

        $list = $cate_tag_mod->field('t.id,t.name,weight')->where($map)->join($join)->limit($pager->firstRow.','.$pager->listRows)->select();

        $cate_name = $this->_mod->get_name($cate_id); //分类名称

        $this->assign('list', $list);

        $this->assign('page', $pager->show());

        $this->assign('cate_id', $cate_id);

        $this->assign('cate_name', $cate_name);

        $this->assign('list_table', true);

        $this->display();

    }



    public function ajax_tag_edit() {

        $tag_id = I('id',0 ,'intval');

        $cate_id = I('cate_id',0 ,'intval');

        if (!$cate_id && !$tag_id) {

            $this->ajax_return(0, L('illegal_parameters'));

        }

        $weight = I('val',0, 'intval');

        M('item_cate_tag')->where(array('cate_id'=>$cate_id, 'tag_id'=>$tag_id))->save(array('weight'=>$weight));

        $this->ajax_return(1);

    }



    /**

     * 标签搜索

     */

    public function tag_search() {

        $tag_mod = D('tag');

        $keywords = I('keywords','', 'trim');

        $cate_id = I('cate_id',0, 'intval');

        $map = array();

        $keywords && $map['name'] = array('like', '%'.$keywords.'%');

        if ($cate_id) {

            $noids = $this->_mod->get_tag_ids($cate_id);

            $noids && $map['id'] = array('not in', $noids);

        }

        $data = $tag_mod->where($map)->limit('0,60')->select();

        $this->ajax_return(1, '', $data);

    }



    /**

     * 分类标签设置

     */

    public function tag_add() {

        if (IS_POST) {

            $cate_id = $this->_post('cate_id', 'intval');

            !$cate_id && $this->ajax_return(0, L('illegal_parameters'));

            $tag_ids = $this->_post('tag_ids', 'trim');

            $custom_tags = $this->_post('custom_tags', 'trim');

            $tag_ids_arr = array();

            if ($tag_ids) {

                $tag_ids = substr($tag_ids, 1);

                $tag_ids_arr = explode('|', $tag_ids);

            }

            if ($custom_tags) {

                $tag_mod = M('tag');

                $custom_tags_arr = explode(',', $custom_tags);

                foreach ($custom_tags_arr as $val) {

                    $tag_id = $tag_mod->where("name='".$val."'")->getField('id');

                    if (!$tag_id) {

                        $tag_id = $tag_mod->add(array('name' => $val,));

                    }

                    if ($tag_id) {

                        $tag_ids_arr[] = $tag_id;

                    }

                }

            }

            $cate_tag_mod = M('item_cate_tag');

            $cate_tag_mod->where(array('cate_id'=>$cate_id))->delete();

            foreach ($tag_ids_arr as $val) {

                $cate_tag_mod->add(array(

                    'cate_id' => $cate_id,

                    'tag_id' =>$val

                ));

            }

            $this->ajax_return(1, L('operation_success'), '', 'tag_add');

        } else {

            $cate_id = I('cate_id',0, 'intval');

            $this->assign('cate_id', $cate_id);

            $resp = $this->fetch();

            $this->ajax_return(1, '', $resp);

        }

    }



    /**

     * 删除标签

     */

    public function tag_delete() {

        $cate_tag_mod = M('item_cate_tag');

        $cate_id = I('cate_id',0, 'intval');

        $ids = trim(I('id'), ',');

        if ($ids) {

            $map = array('cate_id'=>$cate_id, 'tag_id'=>array('in', $ids));

            $cate_tag_mod->where($map)->delete();

            $this->ajax_return(1, L('operation_success'));

        } else {

            $this->ajax_return(0, L('illegal_parameters'));

        }

    }



    public function ajax_getchilds() {
        $id = I('id','', 'intval');
        $return = $this->_mod->field('id,name')->where(array('pid'=>$id))->select();
        if ($return) {
            $this->ajax_return(1, L('operation_success'), $return);
        } else {
            $this->ajax_return(0, L('operation_failure'));
        }
    }

    /**

     * 获取紧接着的下一级分类ID

     */

//    public function ajax_getchilds() {
//
//        $id = I('id',0, 'intval');
//
//        $type = I('type', null, 'intval');
//
//        $map = array('pid'=>$id);
//
//        if (!is_null($type)) {
//
//            $map['type'] = $type;
//
//        }
//
//        $return = $this->_mod->field('id,name')->where($map)->select();
//
//        if ($return) {
//
//            $this->ajax_return(1, L('operation_success'), $return);
//
//        } else {
//
//            $this->ajax_return(0, L('operation_failure'));
//
//        }
//
//    }



    public function ajax_upload_img() {

        //上传图片

        if (!empty($_FILES['img']['name'])) {

            $result = $this->_upload($_FILES['img'], 'member_cate', array(

                    'width' => C('pin_itemcate_img.width'),

                    'height' => C('pin_itemcate_img.height'),

                    'remove_origin' => true,

                )

            );

            if ($result['error']) {

                $this->ajax_return(0, $result['info']);

            } else {

                $ext = array_pop(explode('.', $result['info'][0]['savename']));

                $data['img'] = str_replace('.' . $ext, '_thumb.' . $ext, $result['info'][0]['savename']);

                $this->ajax_return(1, L('operation_success'), $data['img']);

            }

        } else {

            $this->ajax_return(0, L('illegal_parameters'));

        }

    }

}