<?php
namespace Common\Model;
use Think\Model\RelationModel;
class MerchantBindModel extends RelationModel {

    //关联关系
    protected $_link = array(
        'member' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'Member',
            'mapping_name'      =>  'member',
            'as_fields' => 'nickname',
        ),
    );
    //自动完成
    protected $_auto = array (
        array('status','1'),  // 新增的时候把status字段设置为1
        array('type','1'),
    );
}