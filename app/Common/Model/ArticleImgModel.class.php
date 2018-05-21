<?php
namespace Common\Model;
use Think\Model;
class ArticleImgModel extends Model {
    //自动完成
    protected $_auto = array(
        array('add_time', 'time', 1, 'function'),
    );
}