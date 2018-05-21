<?php
namespace Common\Model;
use Think\Model\RelationModel;
class SalesmanModel extends RelationModel {

    //自动验证
    protected $_validate = array(
        array('store_id','require','门店不能为空'),
    );

    //自动完成
    protected $_auto = array (
        array('add_time','time',1,'function'),
    );  


}
