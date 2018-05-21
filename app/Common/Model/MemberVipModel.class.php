<?php
namespace Common\Model;
use Think\Model\RelationModel;
class MemberVipModel extends RelationModel {

    //关联关系
    protected $_link = array(
        'member' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'Member',
            'mapping_name'      =>  'member',
            'as_fields' => 'nickname',
        ),
    );
}