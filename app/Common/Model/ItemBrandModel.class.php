<?php
namespace Common\Model;
use Think\Model;
use Think\Model\RelationModel;
class ItemBrandModel extends RelationModel {
	 //关联关系
    protected $_link = array(
        'item_cate' => array(
            'mapping_type' => self::BELONGS_TO,
            'foreign_key' => 'cate_id',
            'mapping_fields' => 'name',
        )
    );
	


}