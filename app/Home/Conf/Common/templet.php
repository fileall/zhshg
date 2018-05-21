<?php

/*获取类目列表*/
function get_category_list(){
	
}

/*获取文章列表*/
function get_article_list($cate_id=0,$field='*',$map='',$order='ordid desc,id desc',$offset=0,$limit=10){
	$map['cate_id'] = 9;
	return $list = D('Article')->where($map)->field($field)->limit($offset,$limit)->relation('tag')->order($order)->select();
	
}