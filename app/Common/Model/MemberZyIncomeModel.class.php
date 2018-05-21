<?php
namespace Common\Model;
use Think\Model\RelationModel;
class MemberZyIncomeModel extends RelationModel {
    protected $_link = array(
     	'member' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'Member',
            'foreign_key'   => 'member_id',
			'mapping_fields'=>'realname,mobile,vips',  
            'as_fields' => 'realname:realname,mobile:mobile,vips:vips',
        ),
    
      
    );

   //自动验证
    protected $_validate = array(
        array('member_id','require','会员不能为空'),
        array('member_id','','该会员已被禁用', 0, 'unique'), //被占用
    );
    
    //自动完成
    protected $_auto = array (
        array('add_time','time',1,'function'),
    );



}
