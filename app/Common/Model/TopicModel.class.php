<?php
namespace Common\Model;
use Think\Model;
class TopicModel extends Model
{
    //自动完成
    protected $_auto = array(
        array('add_time', 'time', 1, 'function'),
		array('last_time', 'time', 3, 'function'),
    );
    //自动验证
    protected $_validate = array(
        array('title', 'require', '{%article_title_empty}'),
    );

    public function addtime()
    {
        return date("Y-m-d H:i:s",time());
    }
}