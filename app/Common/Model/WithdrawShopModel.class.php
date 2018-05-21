<?php
namespace Common\Model;
use Think\Model\RelationModel;
class WithdrawShopModel extends RelationModel {
    protected $_link = array(
        'merchant' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'merchant',
            'foreign_key'      =>  'shop_id',
			'mapping_fields'      =>  'title,tel',
			'as_fields' => 'title:title,tel:tel',
        ),
		'merchant_bankcard' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'merchant_bankcard',
            'foreign_key'      =>  'bankcard_id',
			'mapping_fields'      =>  'member_name,province,city,name,title,nums',
			'as_fields' => 'member_name:member_name,province:province,'
					.'city:city,name:bank_name,title:bank_address,nums:bank_nums',
        )
    );

}
