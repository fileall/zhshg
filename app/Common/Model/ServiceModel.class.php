<?php
namespace Common\Model;
use Think\Model;
use Think\Model\RelationModel;
class ServiceModel extends RelationModel {
    protected $_auto = array(
        array('add_time', 'time', 1, 'function'),
    );
	
	
   

   
}