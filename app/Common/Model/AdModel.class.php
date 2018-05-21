<?php
namespace Common\Model;
use Think\Model\RelationModel;
class AdModel extends RelationModel {
    //关联关系
    protected $_link = array(
        'adbord' => array(
            'mapping_type' =>self:: BELONGS_TO,
            'class_name' => 'Adboard',
            'foreign_key' => 'board_id',
        ),
    );
}