<?php
namespace Common\Model;
use Think\Model;
class ApplyModel extends Model
{
    protected $_auto = array (
        array('status',0),  // 新增的时候把status字段设置为1
        array('add_ip','get_client_ip',1,'function'),
        array('add_time','time',1,'function'),
    );
	
	//自动验证
	protected $_validate = array(
		 array('username','require','姓名不能为空'), //默认情况下用正则进行验证
		 array('mobile','require','手机号码不能为空'), //默认情况下用正则进行验证
		 array('email','require','邮箱不能为空'), //默认情况下用正则进行验证
		  
		 /* 验证邮箱 */
		array('email', 'email', '邮箱格式不正确', self::EXISTS_VALIDATE), //邮箱格式不正确
		
	);
}