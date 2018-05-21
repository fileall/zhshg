<?php
namespace Common\Model;
use Think\Model\RelationModel;
class MemberRechargeModel extends RelationModel {
    protected $_link = array(
        'member' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'member',
            'mapping_name'      =>  'member',
			'foreign_key'   => 'member_id',
			'mapping_fields'      =>  'realname,mobile',
			'as_fields' => 'realname:realname,mobile:mobile',
        )
    );

}
