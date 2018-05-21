<?php
namespace Common\Model;
use Think\Model\RelationModel;
class MerchantModel extends RelationModel {

    ////关联关系
    protected $_link = array(
        'member_uid' => array( 
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'Member',
			'foreign_key'       =>  'uid', 
			'mapping_fields'=>'realname',  

        ), 
        
//        'member_tuijian' => array(
//            'mapping_type'  => self::BELONGS_TO,
//            'class_name'    => 'Member',
//			'foreign_key'       =>  'relation_id',
//			'mapping_fields'=>'realname',
//        ),
//		'sh_img' => array(
//            'mapping_type'  => self::HAS_MANY,
//            'class_name'    => 'sh_img',
//			'foreign_key'       =>  'withdraw_id',
//			'mapping_fields'=>'img',
//        ),
    );
    
    //自动完成
      protected $_auto = array (

          array('status','0'),  // 新增的时候把status字段设置为1

      );
	protected $_validate = array(
		 array('uid','require','请先绑定会员！'), //默认情况下用正则进行验证
		 array('uid','','会员已经申请商户！',0,'unique',1), // 在新增的时候验证字段是否唯一
		 array('tel','','电话号码已经存在！',0,'unique',2), 

/*		 array('name','','帐号名称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
		 array('value',array(1,2,3),'值的范围不正确！',2,'in'), // 当值不为空的时候判断是否在一个范围内
		 array('repassword','password','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致
		 array('password','checkPwd','密码格式不正确',0,'function'), // 自定义函数验证密码格式
   */);
}