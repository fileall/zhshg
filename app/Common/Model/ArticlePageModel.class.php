<?php
namespace Common\Model;
use Think\Model;
class ArticlePageModel extends Model {
    
   //自动完成
    protected $_auto = array(
        array('last_time', 'time', 3, 'function'),
    );
    //自动验证
    protected $_validate = array(
        array('title', 'require', '{%article_title_empty}'),
    );
}