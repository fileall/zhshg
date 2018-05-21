<?php
namespace Common\Model;
use Think\Model\RelationModel;
class AddressModel extends RelationModel {
    protected $_link = array(

    );

    //自动验证
    protected $_validate = array(
        array('shperson','require','收货人不能为空',1),
        array('mobile','require','手机号不能为空',1),
        array('mobile', 'check_mobile', '手机号码格式不正确',0,'function'), //不合法
        array('address','require','详情地址不能为空',1),
    );



}
