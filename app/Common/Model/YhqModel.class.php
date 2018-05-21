<?php
namespace Common\Model;
use Think\Model\RelationModel;
class YhqModel extends RelationModel
{
    //自动完成
    protected $_auto = array(
//      array('add_time', 'time', 1, 'function'),
//		array('last_time', 'time', 3, 'function'),
    );
    //自动验证
    protected $_validate = array(
//      array('title', 'require', '{%article_title_empty}'),
    );
    //关联关系
    protected $_link = array(
//      'cate' => array(
//          'mapping_type' => BELONGS_TO,
//          'class_name' => 'article_cate',
//          'foreign_key' => 'cate_id',
//      ),
        'user' => array(
            'mapping_type'      =>  self::BELONGS_TO,
            'class_name'        =>  'Member',
            'mapping_name'      =>  'user',
            'foreign_key'       =>  'uid',
//          'mapping_fields'	=>  'realname',
            'as_fields'			=> 	'realname',
        ),
        
		 'cate1' => array(
            'mapping_type'      =>  self::BELONGS_TO,
            'class_name'        =>  'Yhq_cate',
            'mapping_name'      =>  'cate1',
            'foreign_key'       =>  'cate_id',
//          'mapping_fields'	=>  'realname',
            'as_fields'			=> 	'isass',
        ),
        

    );
  
}