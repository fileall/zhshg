<?php
namespace Common\Model;
use Think\Model;
class MessageModel extends Model
{
    protected $_auto = array (
        array('add_ip','get_client_ip',1,'function'),
        array('add_time','time',1,'function'),
		array('status',0,1),  // 新增的时候把status字段设置为1
    );
	//自动验证
	protected $_validate = array(
        array('username','require','家长姓名不能为空'), //默认情况下用正则进行验证
        array('mobile','require','手机号码不能为空'), //默认情况下用正则进行验证
        array('mobile','check_mobile','手机号码格式不正确',0,'function'), // 自定义手机验证密码格式
        array('content','require','投诉内容不能为空'), //默认情况下用正则进行验证
	);
	  
}