<?php
namespace Common\Model;
use Think\Model\RelationModel;
class MemberCardModel extends RelationModel {

    //自动验证
    protected $_validate = array(
      	array('price','require','余额不能为空',1),
        array('card','','有已存在的卡号',0,'unique'), //被占用  
    );


}
