<?php
namespace Common\Model;
use Think\Model\RelationModel;
class IntegralModel extends RelationModel {
//  protected $_link = array(
////      'identity' => array(
////          'mapping_type'  => self::HAS_ONE,
////          'class_name'    => 'Identity',
////          'mapping_name'      =>  'identity',
////      ),
////      'vip' => array(
////          'mapping_type'  => self::HAS_ONE,
////          'class_name'    => 'MemberVip',
////          'foreign_key'   => 'member_id',
////          'mapping_name'      =>  'vip',
////      ),
//      'member' => array(
//          'mapping_type'  => self::BELONGS_TO,
//          'class_name'    => 'Member',
//          'mapping_name'      =>  'member',
//          'foreign_key'   => 'uid',
//      ),
//  );


    //自动完成
    protected $_auto = array (
        array('status','1'),  // 新增的时候把status字段设置为1
        array('add_time','time',1,'function'),
    );


}
