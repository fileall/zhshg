<?php
namespace Common\Model;
use Think\Model\RelationModel;
class ArticleCateModel extends RelationModel {
    
	    //关联关系
    protected $_link = array(
        'article' => array(
            'mapping_type' => self::HAS_MANY,
            //'class_name' => 'article',
            //'mapping_name'=>'article',
            'foreign_key' => 'cate_id',
            'mapping_fields' => 'title,url,id,cate_id',
            //'as_fields' => 'title,url',
        ),
        'at_list' => array(
            'mapping_type' => self::HAS_MANY,
            'class_name' => 'Article',
            'foreign_key' => 'cate_id',
            'mapping_fields' => 'id,title,img,intro,add_time',
            'condition' => 'status = 1',
            'mapping_limit' => 5,
            'mapping_order' =>'ordid,id desc'
        ),'self' => array(
            'mapping_type'  => self::HAS_MANY,
            'class_name'    => 'ArticleCate',
            'parent_key'   => 'pid',
            'condition' => 'status = 1',
            'mapping_order' => 'ordid,id',
            'mapping_fields' => 'id,name',
        )
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
     * 写入缓存
     */
    public function cate_cache() {
        $artcate_list = array();
        $cate_data = $this->field('id,pid,name')->where('status=1')->order('ordid')->select();
        foreach ($cate_data as $val) {
            if ($val['pid'] == '0') {
                $artcate_list['p'][$val['id']] = $val;
            } else {
                $artcate_list['s'][$val['pid']][] = $val;
            }
        }
        F('artcate_list', $artcate_list);
        return $artcate_list;
    }

    /**
     * 更新则删除缓存
     */
    protected function _before_write(&$data) {
        F('artcate_list', NULL);
    }

    /**
     * 删除也删除缓存
     */
    protected function _after_delete($data, $options) {
        F('artcate_list', NULL);
    }
}