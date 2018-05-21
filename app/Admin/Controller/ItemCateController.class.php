<?php
namespace Admin\Controller;
use Admin\Org\Tree;
class ItemCateController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('ItemCate');
        $this->set_mod('ItemCate');
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
            $r['str_img'] = $r['img'] ? '<span class="img_border"><img src="'.attach($r['img'], 'item_cate').'" style="width:100px;" class="J_preview" data-bimg="'.attach($r['img'], 'item_cate').'" /></span>' : '';
			$r['str_img1'] = $r['home_img'] ? '<span class="img_border"><img src="'.attach($r['home_img'], 'item_cate').'" style="width:100px;" class="J_preview" data-bimg="'.attach($r['home_img'], 'item_cate').'" /></span>' : '';
            $r['str_img2'] = $r['pc_img'] ? '<span class="img_border"><img src="'.attach($r['pc_img'], 'item_cate').'" style="width:100px;" class="J_preview" data-bimg="'.attach($r['pc_img'], 'item_cate').'" /></span>' : '';
			$r['str_img3'] = $r['pc_home_img'] ? '<span class="img_border"><img src="'.attach($r['pc_home_img'], 'item_cate').'" style="width:100px;" class="J_preview" data-bimg="'.attach($r['pc_home_img'], 'item_cate').'" /></span>' : '';
//			$r['str_img1'] = $r['bign'] ? '<span class="img_border"><img src="'.attach($r['bign'], 'item_cate').'" style="width:50px; height:50px;" class="J_preview" data-bimg="'.attach($r['bign'], 'item_cate').'" /></span>' : '';
            $r['str_status'] = '<img data-tdtype="toggle" data-id="'.$r['id'].'" data-field="status" data-value="'.$r['status'].'" src="'.C('TMPL_PARSE_STRING.__STATIC__').'/images/toggle_' . ($r['status'] == 0 ? 'disabled' : 'enabled') . '.gif" />';
            $r['str_is_home'] = '<img data-tdtype="toggle" data-id="'.$r['id'].'" data-field="is_home" data-value="'.$r['is_home'].'" src="'.C('TMPL_PARSE_STRING.__STATIC__').'/images/toggle_' . ($r['is_home'] == 0 ? 'disabled' : 'enabled') . '.gif" />';
            $r['str_manage'] = '<a href="javascript:;" class="J_showdialog" data-uri="'.U('item_cate/add',array('pid'=>$r['id'])).'" data-title="'.L('add_item_cate').'" data-id="add" data-width="520" data-height="360">'.L('add_item_subcate').'</a> |

                                <a href="javascript:;" class="J_showdialog" data-uri="'.U('item_cate/edit',array('id'=>$r['id'])).'" data-title="'.L('edit').' - '. $r['name'] .'" data-id="edit" data-width="520" data-height="360">'.L('edit').'</a> |
                                <a href="javascript:;" class="J_confirmurl" data-acttype="ajax" data-uri="'.U('item_cate/delete',array('id'=>$r['id'])).'" data-msg="'.sprintf(L('confirm_delete_one'),$r['name']).'">'.L('delete').'</a>';
            $r['parentid_node'] = ($r['pid'])? ' class="child-of-node-'.$r['pid'].'"' : '';
            $array[] = $r;
        }
        $str  = "<tr id='node-\$id' \$parentid_node>
                <td><input type='checkbox' value='\$id' class='J_checkitem'></td>
                <td>\$id</td>
                <td class='tl'>\$spacer<span data-tdtype='edit' data-field='name' data-id='\$id' class='tdedit'  style='color:\$fcolor'>\$name</span></td>
                <td align='center'>\$str_img</td>
                <!--<td align='center'>\$str_img1</td>
                <td align='center'>\$str_img2</td>
                <td align='center'>\$str_img3</td>-->
                <td align='center'><span data-tdtype='edit' data-field='ordid' data-id='\$id' class='tdedit'>\$ordid</span></td>
                <td align='center'>\$str_status</td>
                <td align='center'>\$str_is_home</td>
                <td align='center'>\$str_manage</td>
                </tr>";
        $tree->init($array);
        $list = $tree->get_tree(0, $str);
        $this->assign('list', $list);
        $this->assign('list_table', true);
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

        return $data;

    }



    /**

     * 修改提交数据

     */

    protected function _before_update($data = '') {

        if ($this->_mod->name_exists($data['name'], $data['pid'], $data['id'])) {

            $this->ajax_return(0, L('item_cate_already_exists'));

        }

        $item_cate = $this->_mod->field('img,pid')->where(array('id'=>$data['id']))->find();

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



    /**

     * 获取紧接着的下一级分类ID

     */

    public function ajax_getchilds() {

        $id = I('id',0, 'intval');

        $type = I('type', null, 'intval');

        $map = array('pid'=>$id);

        if (!is_null($type)) {

            $map['type'] = $type;

        }
        $map['status'] = 1;
        $return = $this->_mod->field('id,name')->where($map)->order('ordid,id')->select();

        if ($return) {

            $this->ajax_return(1, L('operation_success'), $return);

        } else {

            $this->ajax_return(0, L('operation_failure'));

        }

    }



    public function ajax_upload_img() {

        //上传图片

        if (!empty($_FILES['img']['name'])) {

            $result = $this->_upload($_FILES['img'], 'item_cate');

            if ($result['error']) {

                $this->ajax_return(0, $result['info']);

            } else {

                $ext = array_pop(explode('.', $result['info'][0]['savename']));
                
				$data['img'] = $result['info'][0]['savename'];
				
//              $data['img'] = str_replace('.' . $ext, '_thumb.' . $ext, $result['info'][0]['savename']);

                $this->ajax_return(1, L('operation_success'), $data['img']);

            }

        } else {

            $this->ajax_return(0, L('illegal_parameters'));

        }

    }

}