<?php
namespace Common\Model;
use Think\Model;
use Think\Model\RelationModel;
class CollectModel extends RelationModel {
	
	 //关联关系
    protected $_link = array(
        'Item' => array(
            'mapping_type'      =>  self::BELONGS_TO,
            'class_name'        =>  'Item',
            'foreign_key'       =>  'item_id',
            'as_fields'         =>'title,img,price,sales,oldprice', 
            'mapping_fields'    =>'title,img,price,sales,oldprice',
        ),

    );
	
}