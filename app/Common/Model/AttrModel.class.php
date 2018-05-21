<?php
namespace Common\Model;
use Think\Model\RelationModel;
class AttrModel extends RelationModel {
    //关联关系
    protected $_link = array(

        'article' => array(
            'mapping_type'      =>  self::MANY_TO_MANY,
            'class_name'        =>  'Article',
            'mapping_name'      =>  'article',
            'foreign_key'       =>  'attr_id',
            'relation_foreign_key'  =>  'article_id',
            'relation_table'    =>  '__ARTICLE_ATTR__',
            'mapping_fields' => 'id,title,img',
            'mapping_limit' => 10
        ),
		
		'case' => array(
            'mapping_type'      =>  self::MANY_TO_MANY,
            'class_name'        =>  'Article',
            'mapping_name'      =>  'top_case',
            'foreign_key'       =>  'attr_id',
			'condition'			=>	'cate_id = 3',
            'relation_foreign_key'  =>  'article_id',
            'relation_table'    =>  '__ARTICLE_ATTR__',
            'mapping_fields' => 'id,title,img',
            'mapping_limit' => 6
        ),
		'top_active' => array(
            'mapping_type'      =>  self::MANY_TO_MANY,
            'class_name'        =>  'Article',
            'mapping_name'      =>  'top_active',
            'foreign_key'       =>  'attr_id',
			'condition'			=>	'cate_id in(18)',
            'relation_foreign_key'  =>  'article_id',
            'relation_table'    =>  '__ARTICLE_ATTR__',
            'mapping_fields' => 'id,title,img,url,intro',
            'mapping_limit' => 6,
			'mapping_order' => 'ordid desc,id desc'
        ),

    );
   
    /**
     * 检测标签是否存在
     *
     * @param string $name
     * @param int $pid
     * @return bool
     */
    public function name_exists($name, $id=0)
    {
        $pk = $this->getPk();
        $where = "name='" . $name . "'  AND ". $pk ."<>'" . $id . "'";
        $result = $this->where($where)->count($pk);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}
