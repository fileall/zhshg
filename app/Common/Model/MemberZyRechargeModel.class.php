<?php
namespace Common\Model;
use Think\Model\RelationModel;
class MemberZyRechargeModel extends RelationModel {
    protected $_link = array(
     	'member' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'Member',
            'foreign_key'   => 'member_id',
			'mapping_fields'=>'realname,mobile,vips',  
            'as_fields' => 'realname:realname,mobile:mobile,vips:vips',
        ),
    
      
    );

}
