<?php
namespace Common\Model;
use Think\Model\RelationModel;
class IdentityModel extends RelationModel {
    protected $_auto = array(
        array('create_time', 'time', 1, 'function'),
        array('update_time', 'time', 3, 'function'),
        array('status', 2, 1),
    );
    protected $_validate = array(
        array('real_name','require','姓名不能为空！',1),
        array('id_card','require','身份证号码不能为空！',1),
        array('id_card','','身份证号码已经存在！',1,'unique',1),
        array('img_01','require','请上传手持身份证头部照！',1),
        array('img_02','require','请上传手势照片！',1),
        array('img_03','require','请上传身份证正面！',1),
        array('img_04','require','请上传身份证反面！',1),
    );

    //关联关系
    protected $_link = array(
        'member' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'Member',
            'mapping_name'      =>  'member',
            'as_fields' => 'email,nickname',
        ),
    );

    public function id_card_exists($id_card, $id = 0) {
        $where = "id_card='" . $id_card . "' AND id<>'" . $id . "'";
        $result = $this->where($where)->count('id');
        if ($result) {
            return true;
        } else {
            return false;
        }
    }


}