<?php
namespace Common\Model;
use Think\Model\RelationModel;
class StoresModel extends RelationModel {

    ////关联关系
    protected $_link = array(
        'member_uid' => array( 
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'Member',
			'foreign_key'       =>  'uid', 
			'mapping_fields'=>'realname',  

        ), 
        
        'member_tuijian' => array( 
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'Member',
			'foreign_key'       =>  'tuijian', 
			'mapping_fields'=>'realname',  
        ), 
		'sh_img' => array( 
            'mapping_type'  => self::HAS_MANY,
            'class_name'    => 'sh_img',
			'foreign_key'       =>  'withdraw_id', 
			'mapping_fields'=>'img',  
        ), 
    );
    
    //自动完成
      protected $_auto = array (

          array('status','0'),  //商家申请状态  0为未审核 1为驳回 2为通过	
          array('is_act','2'), //启用状态 0禁用 1开启 2未处理',	
		  array('add_time','time',1,'function'), // 对update_time字段新增的时候写入当前时间戳
//        array('type','1'),

      );
	protected $_validate = array(
		 array('uid','require','请先绑定会员！'), //默认情况下用正则进行验证
	     array('cate_id','require','请选择分类',1),
		 array('title','require','请先填写店名！'), //
		 array('tel','require','请填写联系电话！'), //
		 
		 array('uid','','会员已经申请商户！',0,'unique',1), // 在新增的时候验证字段是否唯一
		 array('tel','','电话号码已经存在！',0,'unique',3), //新增/编辑

/*		 array('name','','帐号名称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
		 array('value',array(1,2,3),'值的范围不正确！',2,'in'), // 当值不为空的时候判断是否在一个范围内
		 array('repassword','password','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致
		 array('password','checkPwd','密码格式不正确',0,'function'), // 自定义函数验证密码格式
   */);
}