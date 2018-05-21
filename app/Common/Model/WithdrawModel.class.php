<?php
namespace Common\Model;
use Think\Model\RelationModel;
class WithdrawModel extends RelationModel {
    protected $_link = array(
        'member' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'member',
            'foreign_key'      =>  'member_id',
			'mapping_fields'      =>  'realname,mobile',
			'as_fields' => 'realname:realname,mobile:mobile',
        ),
		'member_bankcard' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'member_bankcard',
            'foreign_key'      =>  'bankcard_id',
			'mapping_fields'      =>  'member_name,province,city,name,title,nums',
			'as_fields' => 'member_name:member_name,province:province,'
					.'city:city,name:bank_name,title:bank_address,nums:bank_nums',
        )
    );

}
