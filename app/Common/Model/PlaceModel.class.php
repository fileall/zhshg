<?php
namespace Common\Model;
use Think\Model;
class PlaceModel extends Model {

    protected $_auto = array(
        array('create_time', 'time', 1, 'function'),
        array('update_time', 'time', 3, 'function'),
    );

    /**
     * 生成spid 
     * 
     * @param int $pid 父级ID
     */
    public function get_spid($pid) {
        if (!$pid) {
            return 0; 
        }
        $pspid = $this->where(array('id'=>$pid))->getField('spid');
        if ($pspid) {
            $spid = $pspid . $pid . '|';
        } else {
            $spid = $pid . '|';
        }
        return $spid;
    }
    
    /**
     * 获取分类下面的所有子分类的ID集合
     * 
     * @param int $id
     * @param bool $with_self
     * @return array $array 
     */
    public function get_child_ids($id, $with_self=false) {
        $spid = $this->where(array('id'=>$id))->getField('spid');
        $spid = $spid ? $spid .= $id .'|' : $id .'|';
        $id_arr = $this->field('id')->where(array('spid'=>array('like', $spid.'%')))->select();
        $array = array();
        foreach ($id_arr as $val) {
            $array[] = $val['id'];
        }
        $with_self && $array[] = $id;
        return $array;
    }

    /**
     * 获取和分类关联的标签ID集合
     */
    public function get_tag_ids($place_id) {
        $res = M('item_place_tag')->field('tag_id')->where(array('place_id'=>$place_id))->select();
        $ids = array();
        foreach($res as $tag) {
            $ids[] = $tag['tag_id'];
        }
        return $ids;
    }

    /**
     * 根据ID获取分类名称
     */
    public function get_name($id) {
        //分类数据
        if (false === $place_data = F('place_data')) {
            $place_data = $this->place_data_cache();
        }
        return $place_data[$id]['name'];
    }

    /**
     * 获取标签分类紧接上级实体分类
     */
    public function get_pentity_id($id) {
        $pentity_id = 0;
        if (false === $place_data = F('place_data')) {
            $place_data = $this->place_data_cache();
        }
        $spid = array_reverse(explode('|', trim($place_data[$id]['spid'], '|')));
        foreach ($spid as $val) {
            if ($place_data[$val]['type'] == 0) {
                $pentity_id = $val;
                break;
            }
        }
        return $pentity_id;
    }

    /**
     * 读取写入缓存(有层级的分类数据)
     */
    public function place_cache() {
        $place_list = array();
        $place_data = $this->field('id,pid,name,fcolor,type')->where('status=1')->order('ordid')->select();
        foreach ($place_data as $val) {
            if ($val['pid'] == '0') {
                $place_list['p'][$val['id']] = $val;
            } else {
                $place_list['s'][$val['pid']][$val['id']] = $val;
            }
        }
        F('place_list', $place_list);
        return $place_list;
    }

    /**
     * 读取写入缓存(无层级分类列表)
     */
    public function place_data_cache() {
        $place_data = array();
        //$result = $this->field('id,pid,spid,name,fcolor,type,seo_title,seo_keys,seo_desc')->where('status=1')->order('ordid')->select();
        $result = $this->field('id,pid,spid,name,fcolor,type')->where('status=1')->order('ordid')->select();
        foreach ($result as $val) {
            $place_data[$val['id']] = $val;
        }
        F('place_data', $place_data);
        return $place_data;
    }

    /**
     * 分类关系读取写入缓存
     */
    public function relate_cache() {
        $place_relate = array();
        $place_data = $this->field('id,pid,spid')->where('status=1')->order('ordid')->select();
        foreach ($place_data as $val) {
            $place_relate[$val['id']]['sids'] = $this->get_child_ids($val['id']); //子孙
            if ($val['pid'] == '0') {
                $place_relate[$val['id']]['tid'] = $val['id']; //祖先
            } else {
                $place_relate[$val['id']]['tid'] = array_shift(explode('|', $val['spid'])); //祖先
            }
        }
        F('place_relate', $place_relate);
        return $place_relate;
    }

    /**
     * 检测分类是否存在
     * 
     * @param string $name
     * @param int $pid
     * @param int $id
     * @return bool 
     */
    public function name_exists($name, $pid, $id=0) {
        $where = "name='" . $name . "' AND pid='" . $pid . "' AND id<>'" . $id . "'";
        $result = $this->where($where)->count('id');
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 更新则删除缓存
     */
    protected function _before_write(&$data) {
        F('place_data', NULL);
        F('place_list', NULL);
        F('place_relate', NULL);
        F('index_place_list', NUll);
    }

    /**
     * 删除也删除缓存
     */
    protected function _after_delete($data, $options) {
        F('place_data', NULL);
        F('place_list', NULL);
        F('place_relate', NULL);
        F('index_place_list', NUll);
    }

}