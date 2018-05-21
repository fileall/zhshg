<?php
namespace Common\Model;
use Admin\Org\Tree;
use Think\Model\RelationModel;
use Admin\Org\pscws4\PSCWS4;
class TagModel extends RelationModel {
    //关联关系
    protected $_link = array(
        'cate' => array(
            'mapping_type' => BELONGS_TO,
            'class_name' => 'article_cate',
            'foreign_key' => 'cate_id',
        ),
        'article' => array(
            'mapping_type'      =>  self::MANY_TO_MANY,
            'class_name'        =>  'Article',
            'mapping_name'      =>  'article',
            'foreign_key'       =>  'tag_id',
            'mapping_fields' => 'id,title,intro,add_time',
            'relation_foreign_key'  =>  'article_id',
            'relation_table'    =>  '__ARTICLE_TAG__'
        )

    );
    public function get_tags_by_title($title, $num=10)
    {
        $pscws = new PSCWS4();
        $pscws->set_dict(PIN_DATA_PATH . 'scws/dict.utf8.xdb');
        $pscws->set_rule(PIN_DATA_PATH . 'scws/rules.utf8.ini');
        $pscws->set_ignore(true);
        $pscws->send_text($title);
        $words = $pscws->get_tops($num);
        $pscws->close();
        $tags = array();
        foreach ($words as $val) {
            $tags[] = $val['word'];
        }
        return $tags;
    }

    /**
     * 检测标签是否存在
     *
     * @param string $name
     * @param int $pid
     * @return bool
     */
    public function name_exists($name, $id=0)
    {
        $pk = $this->getPk();
        $where = "name='" . $name . "'  AND ". $pk ."<>'" . $id . "'";
        $result = $this->where($where)->count($pk);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}
