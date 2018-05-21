<?php
namespace Admin\Controller;

use Admin\Org\Image;

class ArticleController extends AdminCoreController
{
    public function _initialize()
    {
        parent::_initialize();
        $this->_mod = D('Article');
        $this->_cate_mod = D('ArticleCate');
        $this->set_mod('Article');
    }

    public function _before_index()
    {
        $res = $this->_cate_mod->field('id,name')->select();
        $cate_list = [];
        foreach ($res as $val) {
            $cate_list[$val['id']] = $val['name'];
        }
        $this->assign('cate_list', $cate_list);

        $p = I('p',1,'intval');
        $this->assign('p',$p);

		$cate=M('ArticleCate')->getField('id,name');
        $this->assign('cate',$cate);
        //默认排序
        //$this->sort = 'ordid';
        //$this->order = 'ASC';、
    }

    protected function _search()
    {
        $map = [];
        ($time_start = I('time_start','', 'trim'))  && $map['add_time'][] = ['egt', strtotime($time_start)];
        ($time_end = I('time_end','', 'trim'))      && $map['add_time'][] = ['elt', strtotime($time_end)+(24*60*60-1)];
        ($status = I('status','', 'trim'))          && $map['status'] = $status;
        ($keyword = I('keyword','', 'trim'))        && $map['title'] = ['like', '%'.$keyword.'%'];
        $cate_id = I('cate_id','', 'intval');
        $selected_ids = '';
        if ($cate_id) {
            $id_arr = $this->_cate_mod->get_child_ids($cate_id, true);
            $map['cate_id'] = array('IN', $id_arr);
            $spid = $this->_cate_mod->where(array('id'=>$cate_id))->getField('spid');
            $selected_ids = $spid ? $spid . $cate_id : $cate_id;
        }
        $this->assign('search', [
            'time_start'    => $time_start,
            'time_end'      => $time_end,
            'cate_id'       => $cate_id,
            'selected_ids'  => $selected_ids,
            'status'        => $status,
            'keyword'       => $keyword,
        ]);


        return $map;
    }

    public function _before_add()
    {
        $author = $_SESSION['pp_admin']['username'];
        $this->assign('author',$author);

        $site_name = D('setting')->where(['name'=>'site_name'])->getField('data');
        $this->assign('site_name',$site_name);

        $first_cate = $this->_cate_mod->field('id,name')->where(['pid'=>0])->order('ordid DESC')->select();
        $this->assign('first_cate',$first_cate);

        //取属性列表
        $attrs = D('Attr')->select();
        $this->assign('attrs',$attrs);
    }

 	/**
     * 删除
     */
    public function delete()
    {
        $mod = D($this->_name);
        $pk = $mod->getPk();
        $ids = trim(I($pk), ',');
        $arr_ids=explode(',',$ids);
        
        $result=array_intersect([19,20,21,22,24],$arr_ids);

		$result&& $this->ajax_return(0, '协议和发现不能删除');
        	
        if ($ids) {
            if (false !== $mod->delete($ids)) {
                IS_AJAX && $this->ajax_return(1, L('operation_success'));
                $this->success(L('operation_success'));
            } else {
                IS_AJAX && $this->ajax_return(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }
        } else {
            IS_AJAX && $this->ajax_return(0, L('illegal_parameters'));
            $this->error(L('illegal_parameters'));
        }
    }

    protected function _before_insert($data)
    {
        //上传图片
        if (!empty($_FILES['img']['name'])) {
            $art_add_time = date('y-m-d');
			$is_thumd = I('is_thumd',0,'intval');
			if($is_thumd){
				$result = $this->_upload($_FILES['img'], 'article/' . $art_add_time, ['width'=>C('pin_article_img.width'), 'height'=>C('pin_article_img.height'), 'remove_origin'=>true]);
			}else{
				$result = $this->_upload($_FILES['img'], 'article/' . $art_add_time);
			}
            if ($result['error']) {
                $this->error($result['info']);
            } else {
                $ext = array_pop(explode('.', $result['info'][0]['savename']));
				$data['img'] =  empty($is_thumd) ? $art_add_time.'/'.$result['info'][0]['savename'] : $art_add_time.'/'.str_replace('.' . $ext, '_thumb.' . $ext, $result['info'][0]['savename']);
            }
        }else{
			$data['img'] = '';
		}

        if(empty($data['intro'])){
            $str = htmlspecialchars_decode($data['info']);
            $str = empty_replace($str);
            $data['intro'] =mb_substr($str, 0,100, 'utf-8');
        }
        (empty($data['seo_desc'])) && $data['seo_desc'] = $data['intro'];
        (empty($data['seo_keys'])) && $data['seo_keys'] = $data['tags'];
        (empty($data['seo_title'])) && $data['seo_title'] = $data['title'];

        return $data;
    }

    public function _after_insert($id)
    {
        //获取属性
        $attrs = I('attr');
        $tags = I('tags','','trim');
        //添加
        $article_attr = [];
        foreach($attrs as $val){
            $article_attr[] = [
                'article_id' => $id,
                'attr_id' => $val,
            ];
        }
        $this->update_tags($tags,$id);
        //扩展内容
        $ext = I('ext','', ',');
        if( $ext ){
            foreach( $ext['name'] as $key=>$val ){
                if( $val&&$ext['value'][$key] ){
                    $atr['article_id'] = $id;
                    $atr['ext_name'] = $val;
                    $atr['ext_value'] = $ext['value'][$key];
                    M('ArticleExt')->add($atr);
                }
            }
        }
        //上传相册
        $item_imgs = []; //相册
        $file_imgs = [];
        $date_dir = date('ym/');
        foreach( $_FILES['imgs']['name'] as $key=>$val ){
            if( $val ){
                $file_imgs['name'][] = $val;
                $file_imgs['type'][] = $_FILES['imgs']['type'][$key];
                $file_imgs['tmp_name'][] = $_FILES['imgs']['tmp_name'][$key];
                $file_imgs['error'][] = $_FILES['imgs']['error'][$key];
                $file_imgs['size'][] = $_FILES['imgs']['size'][$key];
            }
        }
        if( $file_imgs ){
            $result = $this->_upload($file_imgs, 'article/'.$date_dir,[
                'width'=>C('pin_item_simg.width'),
                'height'=>C('pin_item_simg.height'),
                'suffix' => '_s',
            ]);
            if ($result['error']) {
                $this->error($result['info']);
            } else {
                foreach( $result['info'] as $key=>$val ){
                    $item_imgs[] = [
                        'article_id' => $id,
                        'url'    => $date_dir . $val['savename'],
                        'order'   => $key + 1,
                    ];
                }
            }
        }

        //更新图片和相册
        $item_imgs && M('ArticleImg')->addAll($item_imgs);
    }

    public function update_tags($tags='',$article_id = 0,$edit = false)
    {
        if(!is_array($tags)){
            $tags = str_replace(' ',',',$tags);
            $tags = str_replace('，',',',$tags);
            $tags = explode(',',$tags);
        }

        if($edit){
            D('ArticleTag')->where(['article_id'=>$article_id])->delete();
        }
        if(!empty($tags)){
            foreach($tags as $val){
                $val = trim($val);
                if(!empty($val)){
                    $tag_id = D('Tag')->getFieldByName($val,'id');
                    (!$tag_id) && $tag_id = D('Tag')->add(['name'=>$val]);
                    D('ArticleTag')->add(['article_id'=>$article_id,'tag_id'=>$tag_id]);
                }
            }
        }
    }

    public function _before_edit()
    {
        $id = I('id','','intval');
        $article = $this->_mod->field('id,cate_id')->where(['id'=>$id])->find();
//        $attr_list = array_map('array_shift', $article['attr']);
        $spid = $this->_cate_mod->where(['id'=>$article['cate_id']])->getField('spid');
        if( $spid==0 ){
            $spid = $article['cate_id'];
        }else{
            $spid .= $article['cate_id'];
        }
        $this->assign('selected_ids',$spid);
        //相册
        /*$img_list = M('ArticleImg')->where(['article_id'=>$id])->select();
        $this->assign('img_list', $img_list);*/
//        $this->assign('attr_list',$attr_list);
    }

    protected function _before_update($data)
    {
        if (!empty($_FILES['img']['name'])) {
            $art_add_time = date('ym/d/');
            //删除原图
            $old_img = $this->_mod->where(['id'=>$data['id']])->getField('img');
            $old_img = '.'.attach($old_img,'article');
			if(!is_file($old_img)){
				$ext = array_pop(explode('.', $result['info'][0]['savename']));
				$old_img = str_replace('.' . $ext, '_thumb.' . $ext, $old_img);
			}
            is_file($old_img) && @unlink($old_img);
            //上传新图
			$is_thumd = I('is_thumd',0,'intval');
			if($is_thumd){
				$result = $this->_upload($_FILES['img'], 'article/' . $art_add_time, ['width'=>C('pin_article_img.width'), 'height'=>C('pin_article_img.height'), 'remove_origin'=>true]);
			}else{
				$result = $this->_upload($_FILES['img'], 'article/' . $art_add_time);
			}
            
            if ($result['error']) {
                $this->error($result['info']);
            } else {
                $ext = array_pop(explode('.', $result['info'][0]['savename']));
				$data['img'] =  empty($is_thumd) ? $art_add_time.'/'.$result['info'][0]['savename'] : $art_add_time.'/'.str_replace('.' . $ext, '_thumb.' . $ext, $result['info'][0]['savename']);
            }
        } else {
            unset($data['img']);
        }

        //上传相册
        $item_imgs = []; //相册
        $file_imgs = [];
        $date_dir = date('ym/');
        foreach( $_FILES['imgs']['name'] as $key=>$val ){
            if( $val ){
                $file_imgs['name'][] = $val;
                $file_imgs['type'][] = $_FILES['imgs']['type'][$key];
                $file_imgs['tmp_name'][] = $_FILES['imgs']['tmp_name'][$key];
                $file_imgs['error'][] = $_FILES['imgs']['error'][$key];
                $file_imgs['size'][] = $_FILES['imgs']['size'][$key];
            }
        }
        if( $file_imgs ){
            $result = $this->_upload($file_imgs, 'article/'.$date_dir,[
                'width'=>C('pin_item_simg.width'),
                'height'=>C('pin_item_simg.height'),
                'suffix' => '_s',
            ]);
            if ($result['error']) {
                $this->error($result['info']);
            } else {
                foreach( $result['info'] as $key=>$val ){
                    $item_imgs[] = [
                        'article_id' => $data['id'],
                        'url'    => $date_dir . $val['savename'],
                        'order'   => $key + 1,
                    ];
                }
            }
        }

        //更新图片和相册
        /*$item_imgs && M('ArticleImg')->addAll($item_imgs);

        //扩展内容
        $ext = I('ext');
        if( $ext ){
            foreach( $ext['name'] as $key=>$val ){
                if( $val&&$ext['value'][$key] ){
                    $atr['article_id'] = $data['id'];
                    $atr['ext_name'] = $val;
                    $atr['ext_value'] = $ext['value'][$key];
                    M('ArticleExt')->add($atr);
                }
            }
        }

        //属性
        $attrs = I('attr');
        M('ArticleAttr')->where(['article_id'=>$data['id']])->delete();
        $article_attr = [];

        foreach($attrs as $val){
            $article_attr[] = [
                'article_id' => $data['id'],
                'attr_id' => $val,
            ];
        }

        $this->update_tags($data['tags'],$data['id'],true);

        M('ArticleAttr')->addAll($article_attr);*/

        if(empty($data['intro'])){
            $str = htmlspecialchars_decode($data['info']);
            $str = empty_replace($str);
            $data['intro'] =mb_substr($str, 0,100, 'utf-8');
        }
        (empty($data['seo_desc'])) && $data['seo_desc'] = $data['intro'];
        (empty($data['seo_keys'])) && $data['seo_keys'] = $data['tags'];
        (empty($data['seo_title'])) && $data['seo_title'] = $data['title'];

        return $data;
    }

    //删除图册
    function delete_album()
    {
        $album_mod = M('ArticleImg');
        $album_id = I('album_id',0,'intval');
        $album_img = $album_mod->where('id='.$album_id)->getField('url');
        if( $album_img ){
            $ext = array_pop(explode('.', $album_img));
            $album_min_img = C('pin_attach_path') . 'article/' . str_replace('.' . $ext, '_s.' . $ext, $album_img);
            is_file($album_min_img) && @unlink($album_min_img);
            $album_img = C('pin_attach_path') . 'article/' . $album_img;
            is_file($album_img) && @unlink($album_img);
            $album_mod->delete($album_id);
        }
        echo '1';
        exit;
    }

    //删除扩展内容
    function delete_ext()
    {
        $mod = M('ArticleExt');
        $ext_id = I('ext_id',0,'intval');
        $mod->delete($ext_id);
        echo '1';
        exit;
    }

    /**
     * ajax获取标签
     */
    public function ajax_gettags()
    {
        $title = I('title','', 'trim');
        if ($title) {
            $tags = D('Tag')->get_tags_by_title($title);
            $tags = implode(' ', $tags);
            $this->ajax_return(1, L('operation_success'), $tags);
        } else {
            $this->ajax_return(0, L('operation_failure'));
        }
    }
}