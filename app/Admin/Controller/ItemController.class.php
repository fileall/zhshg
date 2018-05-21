<?php
namespace Admin\Controller;
class ItemController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Item');
		$this->set_mod('Item');
        $this->_cate_mod = D('ItemCate');
        $this->_item_attr=D('ItemAttr');
        $this->_item_img=D('ItemImg');

        //品牌列表
        $brand_list = D('ItemBrand')->getField('id,name');
        $this->assign('brand_list',$brand_list);

    }

      public function _before_index() {
        //显示模式
        $sm = I('sm', '','trim');
        $this->assign('sm', $sm);

        //分类信息
        $res = $this->_cate_mod->field('id,name,pid')->select();
        $cate_list = array();
        foreach ($res as $val) {
            $cate_list[$val['id']] = $val['name'];
			$big_cate2[$val['id']] = $this->_cate_mod -> where(array('id'=>$val['pid'])) ->getField('name');
			$a=$this->_cate_mod -> where(array('id'=>$val['pid'])) ->getField('pid');
			$big_cate3[$val['id']] = $this->_cate_mod -> where(array('id'=>$a)) ->getField('name');
        }

        $this->assign('cate_list', $cate_list);
		$this->assign('big_cate2', $big_cate2);
		$this->assign('big_cate3', $big_cate3);

        //默认排序
        $this->sort = 'ordid ASC,';
        $this->order ='id desc,add_time DESC';
    }
//    public function _after_list($list){
//        if($list){
//            //省市区
////            $place_ids = array_unique(array_merge (array_column($list,'cate_id')));
//            $cate0 = M('item_cate')->field('id,spid,name')->limit(6)->select();
//            //$cate = array_column($cate,'spid');
//            foreach ($cate0 as $k => $v) {
//                $cate[$v['id']] = $v;
//            }
//
////            $getPath($)
////            foreach($cate as $k=>$v){
////                $tmpArr = explode('|', $v['spid']);
////                $tmpStr = '';
////                foreach ($tmpArr as $value) {
////                    if($value){
////                        $tmpStr .= $cate[$value]['name'] . '|';
////                    }
////                }
////                $cate[$k]['spstr'] = $tmpStr.$cate[$k]['name'];
////            }
//            $getPath
//                = function ($id) use ($cate) {
//                    $tmpArr = explode('|', $cate[$id]['spid']);
//                    $tmpStr = '';
//                    foreach ($tmpArr as $value) {
//                        if($value){
//                            $tmpStr .= $cate[$value]['name'] . '|';
//                        }
//                    }
//
//                return $tmpStr.$cate[$id]['name'];
//                };
//            foreach($list as $k=>$v){
//                $list[$k]['cate_name'] = $getPath($v['cate_id'],$cate);
//
//            }
//        }
//        return $list;
//    }



    protected function _search() {
        $map = array();
        ($time_start = I('time_start','', 'trim')) && $map['add_time'][] = array('egt', strtotime($time_start));
        ($time_end = I('time_end','',  'trim')) && $map['add_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));
        ($price_min = I('price_min','',  'trim')) && $map['price'][] = array('egt', $price_min);
        ($price_max = I('price_max','',  'trim')) && $map['price'][] = array('elt', $price_max);
		($brand_id = I('brand_id','',  'trim')) && $map['brand_id'] =$brand_id;
        $cate_id = I('cate_id',0, 'intval');
        if ($cate_id) {
            $id_arr = $this->_cate_mod->get_child_ids($cate_id, true);
            $map['cate_id'] = array('IN', $id_arr);
            $spid = $this->_cate_mod->where(array('id'=>$cate_id))->getField('spid');
            if( $spid==0 ){
                $spid = $cate_id;
            }else{
                $spid .= $cate_id;
            }
        }

        $is_fruit=I('is_fruit');
        ($is_fruit==1)&& $map['is_fruit'] = $is_fruit;//金果商城

        $status=( $_GET['status']==null )?-1:intval($_GET['status']);//状态
        $status>=0 && $map['status'] = array('eq',$status);

        ($keyword = I('keyword','',  'trim')) && $map['title'] = array('like', '%'.$keyword.'%');
        $this->assign('search', array(
            'time_start' => $time_start,
            'time_end' => $time_end,
            'price_min' => $price_min,
            'price_max' => $price_max,
            'status' =>$status,
            'selected_ids' => $spid,
            'cate_id' => $cate_id,
            'keyword' => $keyword,
			'brand_id' =>$brand_id,
            'is_fruit' =>$is_fruit,
        ));
        return $map;
    }

    //添加***************************************************
    public function add() {
        if (IS_POST) {
            $param=I();
            if (false === $data = $this->_mod->create($param)) {
                $this->error($this->_mod->getError());
            }


            ( !$data['cate_id'])&& $this->error('请选择商品分类');
            ( !$data['title'])&& $this->error('请填写商品名称');
            ( !$data['price'])&& $this->error('请填写商品价格');


            if (method_exists($this, '_before_insert')) {
                $data = $this->_before_insert($data);
            }

            if (false !== $id=$this->_mod->add($data)) {
                if( method_exists($this, '_after_insert')){
                    $data['id']=$id;
                    $this->_after_insert($data);
                }
                IS_AJAX && $this->ajax_return(1, L('operation_success'), '', 'add');
                $this->success(L('operation_success'));
            } else {
                IS_AJAX && $this->ajax_return(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }

        } else {
            $admin = session('admin');
            $this -> assign('admin',$admin);
            $this->display();
        }
    }

    public function _before_insert($data)
    {
        //修改主图
        if (!empty($_FILES['img']['name'])) {
            $art_add_time = date('ym/');
            $result = $this->_upload($_FILES['img'],'item/'.$art_add_time, array('width'=>C('pin_article_cate_img.width'), 'height'=>C('pin_article_cate_img.height')));
            if ($result['error']) {
                $this->ajax_return(0, $result['info']);
            } else {
                $ext = array_pop(explode('.', $result['info'][0]['savename']));
                $data['img'] = $art_add_time.$result['info'][0]['savename'];
            }
        }
        return $data;
    }

    public function _after_insert($data)
    {
        $item_id=$data['id'];
        //上传多图
        $item_imgs =[];
        if($imgs_list=I('imgs')){
            foreach ($imgs_list as $k=>$v){
                $item_imgs[] = array(
                    'item_id' => $item_id,
                    'url'    => $v,
                    'ordid'   => $k,
                );
            }
            $item_imgs && M('item_img')->addAll($item_imgs);
        }

        //附加属性
        $attr = I('attr');
        if($attr&&$attr['price'][0]){
            foreach( $attr['price'] as $key=>$val ){
                if($val&&$attr['price'][$key]){
                    $atr[]=[
                        'cate_id'       =>$data['cate_id'],
                        'item_id'       =>$item_id,
                        'attr_name'     =>($attr['attr_name'][$key])?$attr['attr_name'][$key]:'默认',
                        'attr_value'    =>($attr['attr_value'][$key])?$attr['attr_value'][$key]:0,
                        'price'         =>($attr['price'][$key])?$attr['price'][$key]:$data['price'],
                        'oldprice'      =>($attr['oldprice'][$key])?$attr['oldprice'][$key]:0,
                        'ordid'         =>($attr['ordid'][$key])?$attr['ordid'][$key]:255,
                        'acer'          =>($attr['acer'][$key])?$attr['acer'][$key]:$data['price'],
                        'coin'          =>($attr['coin'][$key])?$attr['coin'][$key]:0,

                    ];
                }
            }
        }else{//添加默认属性(第一次添加商品时)
            $atr[]=[
                'cate_id'       =>$data['cate_id'],
                'item_id'       =>$item_id,
                'attr_name'     =>'默认',
                'attr_value'    =>0,
                'price'         =>$data['price'],
                'oldprice'      =>($data['oldprice'])?$data['oldprice']:0,
                'ordid'         =>255,
                'acer'          =>$data['price'],//元宝（元宝+银币支付时）
                'coin'          =>0,//银币（元宝+银币支付时）
            ];
        }
        $atr&& $aaa= $this->_item_attr->addAll($atr);//添加属性

    }
    //添加结束***************************************************

    //修改***************************************************
    public function edit()
    {
        $mod = $this->_mod;
        $pk = $mod->getPk();
        if (IS_POST) {
            $param=I();
            if (false === $data = $this->_mod->create($param)) {
                $this->error($this->_mod->getError());
            }
            ( !$data['cate_id'])&& $this->error('请选择商品分类');
            ( !$data['title'])&& $this->error('请填写商品名称');
            ( !$data['price'])&& $this->error('请填写商品价格');
            $data['jx']=$data['jx']?1:2;
            $data['zhm']=$data['zhm']?1:2;
            $data['fx']=$data['fx']?1:2;

            if (method_exists($this, '_before_update')) {
                $data = $this->_before_update($data);
            }
            if (false !== $mod->save($data)) {
                $this->success(L('operation_success'));
            } else {
                $this->error(L('operation_failure'));
            }

        } else {
            $id = I($pk, 'intval');
            $info = $mod->find($id);
            $this->assign('info', $info);
            //分类
            $spid = $this->_cate_mod->where(array('id'=>$info['cate_id']))->getField('spid');
            $spid=( $spid==0 )?$info['cate_id']:($spid.$info['cate_id']);
            $this->assign('selected_ids',$spid);
            //相册
            $img_list = M('item_img')->where(array('item_id'=>$id))->order('ordid asc,id desc')->select();
            $this->assign('img_list', $img_list);
            //规格
            $attr_list=$this->_item_attr->where(['item_id'=>$id])->order('ordid asc,id desc')->select();
            $this->assign('attr_list', $attr_list);

            if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1, '', $response);
            } else {
                $this->display();
            }
        }
    }


    public function _before_update($data) {
        $item_id=$data['id'];
        //修改主图
        if (!empty($_FILES['img']['name'])) {
            $art_add_time = date('ym/');
            $result = $this->_upload($_FILES['img'],'item/'.$art_add_time, array('width'=>C('pin_article_cate_img.width'), 'height'=>C('pin_article_cate_img.height')));
            if ($result['error']) {
                $this->ajax_return(0, $result['info']);
            } else {
                $ext = array_pop(explode('.', $result['info'][0]['savename']));
                $data['img'] = $art_add_time.$result['info'][0]['savename'];

            }
        }

        //上传多图
        $item_imgs =[];
        if($imgs_list=I('imgs')){
            foreach ($imgs_list as $k=>$v){
                $item_imgs[] = array(
                    'item_id' => $item_id,
                    'url'     => $v,
                    'ordid'   => $k,
                );
            }

            $this->_item_img->where(array('item_id'=>$item_id))->delete();
            $this->_item_img->addAll($item_imgs);
        }

        //附加属性
        $attr = I('attr');
        if($attr&&$attr['price'][0]){
            foreach( $attr['price'] as $key=>$val ){
                if($val&&$attr['price'][$key]){
                    $atr[]=[
                        'cate_id'       =>$data['cate_id'],
                        'item_id'       =>$item_id,
                        'attr_name'     =>($attr['attr_name'][$key])?$attr['attr_name'][$key]:'默认',
                        'attr_value'    =>($attr['attr_value'][$key])?$attr['attr_value'][$key]:0,
                        'price'         =>($attr['price'][$key])?$attr['price'][$key]:0,
                        'oldprice'      =>($attr['oldprice'][$key])?$attr['oldprice'][$key]:0,
                        'ordid'         =>($attr['ordid'][$key])?$attr['ordid'][$key]:255,
                        'acer'          =>($attr['acer'][$key])?$attr['acer'][$key]:$data['price'],
                        'coin'          =>($attr['coin'][$key])?$attr['coin'][$key]:0,
                    ];
                }
            }
        }

        $atr&&$res_attr= $this->_item_attr->addAll($atr);//添加属性

        return $data;
    }

    //单规格加入金果商城
    public function ajax_is_fruit(){
        $param=I();
        $id=$param['id'];
        $val=$param['val'];//1加入
        if($val==1){
            $is_fruit=0;$html='否';
        }else{
            $is_fruit=1;$html='是';
        }
        $res=$this->_item_attr->where(['id'=>$id])->setField('is_fruit',$is_fruit);

        if(false!==$res){
            echo exit(json_encode(array("status" => 1,'val'=>$is_fruit,'html'=>$html)));
        }else{
            echo exit(json_encode(array("status" => 0)));
        }

    }
    //修改结束***************************************************



    //图集上传
    public function ajax_upload_img(){

    	$date_dir = date('ym/d/'); //上传目录

        $result = $this->_upload($_FILES['file'], 'item/'.$date_dir, array());
        if ($result['error']) {
            echo json_encode(array("error" => $result['info']));
        } else {
            $data['thumb_img'] = $date_dir .$result['info'][0]['savename'];
            echo json_encode(array("error" => "0", "src" => $data['thumb_img'], "name" => $result['info'][0]['savename']));
        }
        exit;//不断点，会继续执行后面代码

    }

    //删除图片
    public function del_imgs(){
        $id = I('id','','intval');
        //删除原图
        $old_img = M('ItemImg')->where(array('id'=>$id))->getField('url');
        $old_img = '.'.attach($old_img,'item');
        is_file($old_img) && @unlink($old_img);
        //删除数据
        $del = M('ItemImg')->delete($id);
        if($del){
            echo 1;
        }else{
            echo 0;
        }
    }


    /**
     * ajax修改规格单个字段值
     */
    public function ajax_edit_attr()
    {
        //AJAX修改数据
        $mod = $this->_item_attr;
        $pk = $mod->getPk();
        $id = I($pk, 'intval');
        $field = I('field', 'trim');
        $val = I('val', 'trim');

        $mod->where(array($pk=>$id))->setField($field, $val);//dump($aa);exit;
        $this->ajax_return(1);
    }

    /**
     * ajax删除规格
     */
    function delete_attr() {


        $item_id = I('item_id', 'intval');//商品的id
        $attr_id = I('attr_id',0,'intval');//该条的id
        $item_attr = $this->_item_attr;

        $count=$item_attr->where(['item_id'=>$item_id])->count();
        ($count==1)&&  $this->ajaxReturn(['status'=>0]);

        $res=$item_attr->delete($attr_id);
        $this->ajaxReturn(['status'=>$res?1:0]);
    }

}







