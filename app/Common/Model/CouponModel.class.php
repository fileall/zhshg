<?php
namespace Common\Model;
use Think\Model\RelationModel;
class CouponModel extends RelationModel
{
    //自动完成
    protected $_auto = array(
//      array('add_time', 'time', 1, 'function'),
//		array('last_time', 'time', 3, 'function'),
    );
    //自动验证
    protected $_validate = array(
        array('title', 'require', '{%article_title_empty}'),
    );
//  //关联关系
//  protected $_link = array(
//      'cate' => array(
//          'mapping_type' => BELONGS_TO,
//          'class_name' => 'article_cate',
//          'foreign_key' => 'cate_id',
//      ),
//      'tag' => array(
//          'mapping_type'      =>  self::MANY_TO_MANY,
//          'class_name'        =>  'Tag',
//          'mapping_name'      =>  'tag',
//          'foreign_key'       =>  'article_id',
//          'relation_foreign_key'  =>  'tag_id',
//          'relation_table'    =>  '__ARTICLE_TAG__'
//      ),
//      'attr' => array(
//          'mapping_type'      =>  self::MANY_TO_MANY,
//          'class_name'        =>  'Attr',
//          'mapping_name'      =>  'attr',
//          'foreign_key'       =>  'article_id',
//          'relation_foreign_key'  =>  'attr_id',
//          'relation_table'    =>  '__ARTICLE_ATTR__'
//      ),
//
//  );
    public function addtime()
    {
        return date("Y-m-d H:i:s",time());
    }
}