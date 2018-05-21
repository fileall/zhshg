<?php
namespace Admin\Model;
use Think\Model\RelationModel;
class AdminModel extends RelationModel
{
    protected $_validate = array(
        array('username', 'require', '{%admin_username_empty}'), //不能为空
        array('username', '', '{%admin_name_exists}', 0, 'unique', 1), //新增的时候检测重复
        array('password', 'require','密码不能为空',1,'',1), // 自定义函数验证密码格式
        array('repassword', 'password', '两次输入的密码不一致', 0, 'confirm'), //确认密码
    );

    protected $_link = array(
        //关联角色
        'role' => array(
            'mapping_type'  => self::BELONGS_TO,
			'class_name'    => 'AdminRole',
			'foreign_key'   => 'role_id',
			'mapping_name'  => 'role',
        ),
    );

    /*
     * 检测名称是否存在
     *
     * @param string $name
     * @param int $id
     * @return bool
     */
    public function name_exists($name, $id=0) {
        $pk = $this->getPk();
        $where = "username='" . $name . "'  AND ". $pk ."<>'" . $id . "'";
        $result = $this->where($where)->count($pk);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}