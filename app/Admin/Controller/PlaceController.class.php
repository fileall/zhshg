<?php
namespace Admin\Controller;
use Admin\Org\Tree;
class PlaceController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Place');
        $this->set_mod('Place');
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
            $r['str_img'] = $r['img'] ? '<span class="img_border"><img src="'.attach($r['img'], 'place').'" style="width:26px; height:26px;" class="J_preview" data-bimg="'.attach($r['img'], 'place').'" /></span>' : '';
            $r['str_status'] = '<img data-tdtype="toggle" data-id="'.$r['id'].'" data-field="status" data-value="'.$r['status'].'" src="'.C('TMPL_PARSE_STRING.__STATIC__').'/images/toggle_' . ($r['status'] == 0 ? 'disabled' : 'enabled') . '.gif" />';
            $r['str_manage'] = '<a href="javascript:;" class="J_showdialog" data-uri="'.U('place/add',array('pid'=>$r['id'])).'" data-title="'.L('add_item_cate').'" data-id="add" data-width="520" data-height="360">'.L('add_item_subcate').'</a> |
                                <a href="javascript:;" class="J_showdialog" data-uri="'.U('place/edit',array('id'=>$r['id'])).'" data-title="'.L('edit').' - '. $r['name'] .'" data-id="edit" data-width="520" data-height="360">'.L('edit').'</a> |
                                <a href="javascript:;" class="J_confirmurl" data-acttype="ajax" data-uri="'.U('place/delete',array('id'=>$r['id'])).'" data-msg="'.sprintf(L('confirm_delete_one'),$r['name']).'">'.L('delete').'</a>';

            $r['parentid_node'] = ($r['pid'])? ' class="child-of-node-'.$r['pid'].'"' : '';
            $array[] = $r;
        }
        $str  = "<tr id='node-\$id' \$parentid_node>
                <td align='center'><input type='checkbox' value='\$id' class='J_checkitem'></td>
                <td align='center'>\$id</td>
                <td>\$spacer<span data-tdtype='edit' data-field='name' data-id='\$id' class='tdedit'  style='color:\$fcolor'>\$name</span></td>
                <td align='center'><span data-tdtype='edit' data-field='ordid' data-id='\$id' class='tdedit'>\$ordid</span></td>
                <td align='center'>\$str_status</td>
                <td align='center'>\$str_manage</td>
                </tr>";
        $tree->init($array);
        $list = $tree->get_tree(0, $str);
        $this->assign('list', $list);
        $this->assign('list_table', true);
        $this->display();
    }

    public function _before_add(){
        $pid = I('pid', 0,'intval');
        if ($pid) {
            $spid = $this->_mod->where(array('id'=>$pid))->getField('spid');
            $spid = $spid ? $spid.$pid : $pid;
            $this->assign('spid', $spid);
        }
       
        $this->assign('place_type',C('place_type'));
    }


    /**
     * 入库数据整理
     */
    protected function _before_insert($data = '') {
        //检测分类是否存在
//      if($this->_mod->name_exists($data['name'], $data['pid'])){
//          $this->ajax_return(0, L('item_cate_already_exists'));
//      }
      
        //生成spid
        $data['spid'] = $this->_mod->get_spid($data['pid']);
 
        return $data;
    }

    public function _before_edit(){
        $id = I('id', '', 'intval');
        $place = $this->_mod->field('id,pid')->where(array('id' => $id))->find();
        $place_spid = M('Place')->where(array('id' => $place['pid']))->getField('spid');
        if ($place_spid == 0) {
            $place_spid = $place['pid'];
        } else {
            $place_spid .= $place['pid'];
        }

        $this->assign('spid', $place_spid);
        $this->assign('place_type',C('place_type'));
    }

    /**
     * 修改提交数据
     */
    protected function _before_update($data = '') {
        if ($this->_mod->name_exists($data['name'], $data['pid'], $data['id'])) {
            $this->ajax_return(0, L('item_cate_already_exists'));
        }
        $place = $this->_mod->field('img,pid')->where(array('id'=>$data['id']))->find();
        if ($data['pid'] != $place['pid']) {
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
     * 获取紧接着的下一级分类ID
     */
    public function ajax_getchilds() {
        $id = I('id',0, 'intval');
        $type = I('type', 0, 'intval');
        $map = array('pid'=>$id);
        if (!empty($type)) {
            $map['type'] = $type;
        }
        $return = $this->_mod->field('id,name')->where($map)->select();
        if ($return) {
            $this->ajax_return(1, L('operation_success'), $return);
        } else {
            $this->ajax_return(0, L('operation_failure'));
        }
    }

    public function get_city_info(){
        $kw = I('kw');
        $url = 'http://api.map.baidu.com/geocoder/v2/?ak='.C('baidu_lbs_key').'&output=json&address='.$kw.'&city='.$kw.'';
        //$url = 'http://api.map.baidu.com/place/v2/suggestion?query='.$kw.'&region=136&output=json&ak='.C('baidu_lbs_key');
        $data_json = file_get_contents($url);
        $data_json = json_decode($data_json,true);

        //经纬度
        $address_url = "http://api.map.baidu.com/geocoder?location=".$data_json['result']['location']['lat'].",".$data_json['result']['location']['lng']."&coord_type=bd09ll&output=json";
        $address_data_json = file_get_contents($address_url);
        $address_data = json_decode($address_data_json,true);

        $return_data = array(
            'lng' => $address_data['result']['location']['lng'],
            'lat' => $address_data['result']['location']['lat'],
            'city_code' => $address_data['result']['cityCode'],
        );

        echo json_encode($return_data);
    }
	
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

}