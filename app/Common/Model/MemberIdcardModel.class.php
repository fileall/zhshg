<?php
namespace Common\Model;
use Think\Model\RelationModel;
class MemberIdcardModel extends RelationModel {
    protected $_link = array(
        'member' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'member',
            'mapping_name'      =>  'member',
			'foreign_key'   => 'member_id',
			'mapping_fields'      =>  'vips,mobile,relation_id,prices',
			'as_fields' => 'mobile:mobile,vips:vips,relation_id:relation_id,prices',
        )
    );

}
