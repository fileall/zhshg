<?php
namespace Common\Model;
use Think\Model\RelationModel;
class OrderModel extends RelationModel{
    
    //关联关系
    protected $_link = array(
        'list' => array(
            'mapping_type'  => self::HAS_MANY,
            'class_name'    => 'order_list',
            'foreign_key'   => 'oid',
		),
    );
	

}