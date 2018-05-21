<?php

namespace Admin\Controller;

class MerchantController extends AdminCoreController {

    public function _initialize() {

        parent::_initialize();
		
        $this->_mod = D('Merchant');
        $this->set_mod('Merchant');
        $this->_mod_cate=D('MerchantCate');

    }



    protected function _search() {

        $map = array();

        ($time_start=I('time_start'))&&$map['add_time'][]=array('egt',strtotime($time_start));

        ($time_end=I('time_end'))&&$map['add_time'][]=array('elt',strtotime($time_end)+24*60*60);

         ($zftype=I('zftype', '', 'trim'))&& $map['zftype'] =['like','%'.$zftype.'%'];

       if($keywords = I('keywords', '', 'trim')){
     	  $map['_string'] = " uid in (select id from jrkj_member where nickname like '%".$keywords."%') or ";
     	  $map['_string'] .= " title like '%".$keywords."%' or ";
     	  $map['_string'] .= " tel like '%".$keywords."%' ";
       }

		//分类
        $cate_id = I('cate_id',0,'intval');
        if ($cate_id) {

            $id_arr = $this->_mod_cate->get_child_ids($cate_id, true);
            $map['cate_id'] = array('IN', $id_arr);
            $spid = $this->_mod_cate->where(array('id'=>$cate_id))->getField('spid');
            if( $spid==0 ){
                $spid = $cate_id;
            }else{
                $spid .= $cate_id;
            }
        }
        //商家申请状态  0为未审核 1为驳回 2为通过3暂停营业',
        $map['status']=array('in',[2,3]);
        $this->assign('search', array(
            'keywords' => $keywords,
            'time_start' =>$time_start,
            'time_end'  =>$time_end,
            'selected_ids' => $spid,
            'cate_id' => $cate_id,
			'zftype'=>$zftype

        ));
        return $map;

    }

	public function index() {

        $p = I('p',1,'intval');
        $this->assign('p',$p);

        $type =I('type','0','intval');
        $this->assign('type',$type);

        $map = $this->_search();
        $mod = $this->_mod;//商家
        $member_mod=D('Member');//用户表
        
        $list=$mod->where($map)->field('id,relation_id,uid')->select();
        if(!empty($list)){

        	//会员表
            $member_ids=array_unique(array_merge(array_column($list,'uid'),array_column($list,'relation_id')));
            !empty($member_ids)&&$member = $member_mod->where(['id'=>['in',$member_ids]])->getField('id,realname,mobile');
        }
   		//商家分类表
//		$merchant_cate=$this->_mod_cate->getField('id,name');
//        $this->assign('merchant_cate',$merchant_cate);
        $res = $this->_mod_cate->field('id,name,pid')->select();
        $cate_list = array();
        foreach ($res as $val) {
            $cate_list[$val['id']] = $val['name'];
            $big_cate2[$val['id']] = $this->_mod_cate -> where(array('id'=>$val['pid'])) ->getField('name');
            $a=$this->_mod_cate -> where(array('id'=>$val['pid'])) ->getField('pid');
            $big_cate3[$val['id']] = $this->_mod_cate -> where(array('id'=>$a)) ->getField('name');
        }
        $this->assign('cate_list', $cate_list);//只有一级
        $this->assign('big_cate2', $big_cate2);//有二级分类时
        $this->assign('big_cate3', $big_cate3);//有三级分类时

        $this->assign('member',$member);
        !empty($mod) && $this->_list($mod, $map);
        $this->display();
    }




    /**
     * ajax修改 is_act
     */
    public function ajax_status_edit()
    {
//        var_dump( I());die;
        //AJAX修改数据
        $mod = D($this->_name);
        $pk = $mod->getPk();
        $id = I($pk, 'intval');
        $field = I('field', 'trim');
        $val = I('val', 'trim');
		$s =$val==0?0:1;//框架0x1g,对应3x2g
        //允许异步修改的字段列表  放模型里面去 TODO
       $mod->where(array($pk=>$id))->setField($field, $s);
        $this->ajax_return(1);
    }


	
    //修改开始**********************************************************
    public function edit()
    {

        $mod = $this->_mod;
        $pk = $mod->getPk();	
        if (IS_POST) {
            $data=I();
            $long_lat=explode(',',$data['long_lat']);
            $data['latitude']=$long_lat[0]?$long_lat[0]:0;
            $data['longitude']=$long_lat[1]?$long_lat[1]:0;
            $data['zftype']=implode(',', $data['zftype']);

            if (false === $data = $mod->create($data)) {
                IS_AJAX && $this->ajax_return(0, $mod->getError());
                $this->error($mod->getError());
            }

            if (method_exists($this, '_before_update')) {
                $data = $this->_before_update($data);
            }
    		#商家电话修改则二维码修改
        	$old_tel=$mod->where('id ='.$data['id'])->getField('tel');
//			if($data['tel'] !=$old_tel){
//				$uri = "http://".$_SERVER['HTTP_HOST']."/index.php?m=mobile&c=member&a=check_pay&tel=".$data['tel'];
//			   	$data['ewm']=$this->set_qrcode($uri);
//			}

            //修改金额
            if (method_exists($this, 'update_money')) {
                $update_money= $this->update_money($data);
                $data=$update_money['data'];
                $mr=$update_money['mr'];
            }
			        	 
            if (false !== $mod->save($data)) {
                //添加明细
                if (!empty($mr) && false === M('account')->addAll($mr)){
                    $this->error(L('operation_failure'));
                }
                
                if( method_exists($this, '_after_update')){
                    $id = $data['id'];
                    $this->_after_update($id,$data,$old_tel);//删除更新前的图片资源
                }
                IS_AJAX && $this->ajax_return(1, L('operation_success'), '', 'edit');
                $this->success(L('operation_success'));
            } else {
                IS_AJAX && $this->ajax_return(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }
        } else {
            $id = I($pk, 'intval');
            $this->_relation && $mod->relation(true);
            $info = $mod->find($id);
            $info['long_lat']=$info['latitude'].','.$info['longitude'];

            //店铺分类
            $spid = $this->_mod_cate->where(array('id'=>$info['cate_id']))->getField('spid');
            $cate_spid=( $spid==0 )?$info['cate_id']:($spid.$info['cate_id']);
            //省市区
            $place_spid=$info['province_id']."|".$info['city_id']."|".$info['district_id'];
            //店铺图片
            $img_list = M('merchant_img')->where(array('merchant_id'=>$id))->order('ordid asc,id desc')->select();

            $this->assign('img_list', $img_list);
            $this->assign('cate_selected_ids',$cate_spid);
            $this->assign('place_selected_ids',$place_spid);
            $this->assign('info', $info);
            $this->assign('open_validator', true);
            if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1, '', $response);
            } else {
                $this->display();
            }
        }
    }

    //修改金额
    public function update_money($data)
    {
        $mr = [];
        $now=$_SERVER['REQUEST_TIME'];
        //修改金元宝
        if (0 < $data['gold_acer'] && $gold_acer_exp = I('gold_acer_exp','','trim')){

            $gold_acer=$data['gold_acer'];
            $gold_acer = ($gold_acer_exp == '+') ? (0+$gold_acer) :(0-$gold_acer);
            $mr[] = [
                'type' 			=> 2,
                'shop_id'			=> $data['id'],
                'totalprices'	=> $gold_acer,
                'change_desc'	=>'系统调整',
                'add_time'		=> $now
            ];
            $data['gold_acer'] = ['exp','gold_acer'.$gold_acer_exp.$data['gold_acer']];
        }else{
            unset($data['gold_acer']);
        }
        //修改金果
        if (0 < $data['gold_fruit'] && $gold_fruit_exp = I('gold_fruit_exp','','trim')){//余额

            $gold_fruit=$data['gold_fruit'];
            $gold_fruit = ($gold_fruit_exp == '+') ? (0+$gold_fruit) :(0-$gold_fruit);
            $mr[] = [
                'type' 			=> 3,
                'shop_id'			=> $data['id'],
                'totalprices'	=> $gold_fruit,
                'change_desc'	=>'系统调整',
                'add_time'		=> $now
            ];
            $data['gold_fruit'] = ['exp','gold_fruit'.$gold_fruit_exp.$data['gold_fruit']];
        }else{
            unset($data['gold_fruit']);
        }

        $update_money=['data'=>$data,'mr' =>$mr];
        return $update_money;
    }

    public function _before_update($data) {
        $merchant_id=$data['id'];
        //修改主图
        if (!empty($_FILES['img']['name'])) {
            $art_add_time = date('ym/');
            $result = $this->_upload($_FILES['img'],'avatar/'.$art_add_time, array('width'=>C('pin_article_cate_img.width'), 'height'=>C('pin_article_cate_img.height')));
            if ($result['error']) {
                $this->ajax_return(0, $result['info']);
            } else {
                $ext = array_pop(explode('.', $result['info'][0]['savename']));
                $data['img'] = $art_add_time.$result['info'][0]['savename'];

            }
        }
        //修改营业执照
        if (!empty($_FILES['yy_img']['name'])) {
            $art_add_time = date('ym/');
            $result = $this->_upload($_FILES['yy_img'],'merchant_yyimg/'.$art_add_time, array('width'=>C('pin_article_cate_img.width'), 'height'=>C('pin_article_cate_img.height')));
            if ($result['error']) {
                $this->ajax_return(0, $result['info']);
            } else {
                $ext = array_pop(explode('.', $result['info'][0]['savename']));
                $data['yy_img'] = $art_add_time.$result['info'][0]['savename'];

            }
        }

        //上传多图
        $item_imgs =[];
        if($imgs_list=I('imgs')){
            foreach ($imgs_list as $k=>$v){
                $item_imgs[] = array(
                    'merchant_id' => $merchant_id,
                    'img'     => $v,
                    'ordid'   => $k,
                );
            }
            $imgs_modle=M('merchant_img');
            $imgs_modle->where(array('merchant_id'=>$merchant_id))->delete();
            $imgs_modle->addAll($item_imgs);
        }

        return $data;
    }

    //生成二维码
    public function ewm(){

        $id=I('id');
        (!$id) && $this->ajaxReturn(['status'=>0,'msg'=>'系统繁忙']);

        $list=$this->_mod->find($id);
        $uri = "http://".$_SERVER['HTTP_HOST']."/index.php?m=mobile&c=wallet&a=scan_pay&merchant_id=".$id;
        $ewm_url=  $this->set_qrcode($uri);
        $res=$this->_mod->where(['id'=>$id])->setField('ewm',$ewm_url);

        if($res){
            del_file($list['ewm'],'ewm/');
            $this->ajaxReturn(['status'=>1,'url'=>$ewm_url]);
        }else{
            $this->ajaxReturn(['status'=>0,'url'=>$ewm_url]);
        }

    }
    //删除图片
    public function del_imgs(){
        $id = I('id','','intval');
        //删除原图
        $old_img = M('MerchantImg')->where(array('id'=>$id))->getField('img');
        $old_img = '.'.attach($old_img,'item');
        is_file($old_img) && @unlink($old_img);
        //删除数据
        $del = M('MerchantImg')->delete($id);
        if($del){
            echo 1;
        }else{
            echo 0;
        }
    }
    //修改结束**********************************************************





//    public function merchant_type(){
//        $merchant_type=M('member_cate')->where(array('status'=>1))
//            ->field('id,name')->select();
//        return $merchant_type;
//    }
//
//    public function _before_edit(){
//		$this->_relation = true;
//		$merchant_type=$this->merchant_type();
//		$this->assign('merchant_type',$merchant_type);
//    }
//
//	public function _before_add(){
//        $merchant_type=$this->merchant_type();
//		$this->assign('merchant_type',$merchant_type);
//    }

    //添加开始**********************************************************
    public function add() {
        if (IS_POST) {
            $data=I();

            $uid=$data['uid'];
            $mod=$this->_mod;
            $this->_mod_cate;
            ( !$data['uid'])&& $this->error('请先选择用户');
            ( !$data['tel'])&& $this->error('请填写店铺电话号码');
            ( !$data['title'])&& $this->error('请填写店铺名称');
            ( !$data['cate_id'])&& $this->error('请填写分类');
            ( !$data['province_id'])&& $this->error('请选择地区');
            $data['add_time'] = time();

            $check['_string']='tel ='.$data['tel'].' or uid='.$uid;
            $have_shop = $this->_mod->where($check)->find();
            $have_shop&& $this->error('该号码或会员已注册过店铺,请勿重复申请');

            $long_lat=explode(',',$data['long_lat']);
            $data['latitude']=$long_lat[0]?$long_lat[0]:0;
            $data['longitude']=$long_lat[1]?$long_lat[1]:0;
            $data['zftype']=implode(',', $data['zftype']);
            $data['status']=2;//商家申请状态  0为未审核 1为驳回 2为通过 3禁用
            if (false === $data = $this->_mod->create($data)) {
                $this->error($this->_mod->getError());
            }

            if (method_exists($this, '_before_insert')) {
                $data = $this->_before_insert($data);
            }

            if (false !== $id=$this->_mod->add($data)) {
                #收款二维码
                $uri = "http://".$_SERVER['HTTP_HOST']."/index.php?m=mobile&c=wallet&a=scan_pay&merchant_id=".$id;
                $ewm=$this->set_qrcode($uri);
                $this->_mod->where(['id'=>$id])->setField('ewm',$ewm);
                #上传多图
                $item_imgs =[];
                if($imgs_list=I('imgs')){
                    foreach ($imgs_list as $k=>$v){
                        $item_imgs[] = array(
                            'merchant_id' => $id,
                            'img'     => $v,
                            'ordid'   => $k,
                        );
                    }
                    M('merchant_img')->addAll($item_imgs);
                }

                IS_AJAX && $this->ajax_return(1, L('operation_success'), '', 'add');
                $this->success(L('operation_success'));
            } else {
                IS_AJAX && $this->ajax_return(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }

        }else{
            $admin = session('admin');
            $this -> assign('admin',$admin);
            $this->display();
        }
    }

    public function _before_insert($data) {
        //主图
        if (!empty($_FILES['img']['name'])) {
            $art_add_time = date('ym/');
            $result = $this->_upload($_FILES['img'],'avatar/'.$art_add_time, array('width'=>C('pin_article_cate_img.width'), 'height'=>C('pin_article_cate_img.height')));
            if ($result['error']) {
                $this->ajax_return(0, $result['info']);
            } else {
                $ext = array_pop(explode('.', $result['info'][0]['savename']));
                $data['img'] = $art_add_time.$result['info'][0]['savename'];

            }
        }
        //营业执照
        if (!empty($_FILES['yy_img']['name'])) {
            $art_add_time = date('ym/');
            $result = $this->_upload($_FILES['yy_img'],'merchant_yyimg/'.$art_add_time, array('width'=>C('pin_article_cate_img.width'), 'height'=>C('pin_article_cate_img.height')));
            if ($result['error']) {
                $this->ajax_return(0, $result['info']);
            } else {
                $ext = array_pop(explode('.', $result['info'][0]['savename']));
                $data['yy_img'] = $art_add_time.$result['info'][0]['savename'];

            }
        }
        return $data;
    }


    //添加商家时获取会员列表
    public function get_user_list(){

        $parm = I('parm','','trim');

        $list = M('member')->where(array('realname|mobile'=>array('like', '%'.$parm.'%')))

            ->field('id,realname,mobile')

            ->select();
        $real_list =array();
        foreach($list as $k=>$v){
            //过滤掉已添加的商家
            if(!$this->_mod->where(array('uid'=>$v['id']))->count()){
                $real_list[]=$v;
            }
        }
        echo json_encode($real_list);

    }
    //添加结束**********************************************************


    public function ajax_getRangli() {

	    $return=array('0.1','0.15','0.2','0.25','0.3','0.35','0.4','0.45','0.50');

        $this->ajax_return(1, L('operation_success'), $return);

    }

    //图集上传
    public function ajax_upload_img(){
        $date_dir = date('ym/d/'); //上传目录
        $result = $this->_upload($_FILES['file'], 'merchant_img/'.$date_dir, array());
        if ($result['error']) {
            echo json_encode(array("error" => $result['info']));
        } else {
            $data['thumb_img'] = $date_dir .$result['info'][0]['savename'];
            echo json_encode(array("error" => "0", "src" => $data['thumb_img'], "name" => $result['info'][0]['savename']));
        }
        exit;//不断点，会继续执行后面代码

    }



	//下载报表 商家列表0
     public function export()
	{
		ob_end_clean();

		$map = $this->_search();
    	$list = $this->_mod->where($map)->select();//数据
        if($list){
            $cate_list =$this-> _mod_cate->getfield('id,name'); //分类
            $member_ids=array_unique(array_column($list,'uid'));
            $member_ids&&$member=M('member')->where(['id'=>['in',$member_ids]])->getField('id,realname,mobile');
        }
        $data = array();
        foreach ($list as $k => $v) {
            $data[$k]['xh']= $k+1; //序号
            $data[$k]['id']= $v['id']; //id
            $data[$k]['title'] = $v['title']; //店名
//            $data[$k]['realname'] = $v['member_uid']['realname']; //会员名
            $data[$k]['mobile'] = $member[$v['uid']]['mobile']; //会员电话
            $data[$k]['tel'] = $v['tel'];//店铺电话
            $data[$k]['address'] = $v['address'];//地址
            $data[$k]['cate_name']=$cate_list[$v['cate_id']];//分类
//            $data[$k]['shouyi']=$v['shouyi'];//收益
            $data[$k]['gold_acer']=$v['gold_acer'];//金元宝
            $data[$k]['gold_fruit']=$v['gold_fruit'];//金果
            $data[$k]['rangli']=$v['rangli'].'%';//让利
            $data[$k]['set_coin']=$v['set_coin'];//返银倍数
             $aa=explode(',', $v['zftype']);
             $data[$k]['sy_type']='';//收银类型
             foreach($aa as $vv){
             	($vv==1)&&$data[$k]['sy_type'] .='金宝';
             	($vv==2)&&$data[$k]['sy_type'] .='银宝';
             	($vv==3)&&$data[$k]['sy_type'] .='金果';
             }
            $data[$k]['tuijian'] = $v['member_tuijian']['mobile'];//推荐人
            $data[$k]['add_time'] = date('Y-m-d H:i' ,$v['add_time']);//添加日期
            ($v['status']==1)&& $data[$k]['status']='冻结';//状态
            ($v['status']==2)&& $data[$k]['status']='正常';

        }

        $headArr = array();
		$headArr[] = '序号';
        $headArr[] = 'id';
		$headArr[] = '店名';
//		$headArr[] = '会员名';
        $headArr[] = '会员电话';
        $headArr[] = '店铺电话';
		$headArr[] = '地址';
		$headArr[] = '分类';
		$headArr[] = '元宝';
		$headArr[] = '金果';
//		$headArr[] = '收益';
		$headArr[] = '让利';
		$headArr[] = '返银倍数';
		$headArr[] = '收银类型';
		$headArr[] = '推荐人';
		$headArr[] = '添加日期';
		$headArr[] = '状态';
        $filename="商家列表".date("Y-m-d");
        $this->getExceltjab($filename, $headArr, $data);

	}

//*********************************************************
//    //附图处理
//    public function _after_update($id,$data,$old_tel)
//    {
//        $res =0;
//        $org_img=M('sh_img')->where(array('withdraw_id'=>$id))
//            ->getField('img',true);//找出更新前的附图
//        $arr =array_diff($org_img,$data['imgs']);//作差集 取出$org_img要删除的图
//        if(count($data['imgs'])!=count($org_img)){
//            $res =$this->img_process($id,$data);
//        }
//        else{
//            !empty($arr)&&$res =$this->img_process($id,$data);
//        }
//        $res && $this->update_member($data['uid']);
//    }
//    //图集处理
//    public function img_process($id,$data){
//
//        $ids=M('sh_img')->where(array('withdraw_id'=>$id))
//            ->getField('id',true);//找出更新前的附
//        $org_img=M('sh_img')->where(array('withdraw_id'=>$id))
//            ->getField('img',true);//找出更新前的附图
//        if($data['imgs']){
//            //更新附图
//            $arr_imgs=array();
//            foreach($data['imgs'] as $k=>$v){
//                $arr_imgs[$k]=array(
//                    'img'=>$v,
//                    'add_time'=>time(),
//                    'member_id'=>$data['uid'],
//                    'withdraw_id'=>$data['id']
//                );
//            }
//            $res =D('ShImg')->addAll($arr_imgs);
//            if($res){//删除图集操作
//                foreach($org_img as $k=>$v)
//                {
//                    $old_img[$k] = '.'.attach($v,'sh_img');
//                    $old_img[$k]&& @unlink($old_img[$k]);
//                    M('sh_img')->where(array('id'=>array('in',$ids)))->delete();
//                }
//            }
//        }
//        else{
//            //删除图集操作
//            foreach($org_img as $k=>$v)
//            {
//                $old_img[$k] = attach($v,'sh_img');
//                is_file($old_img[$k]) && @unlink($old_img[$k]);
//                M('sh_img')->where(array('id'=>array('in',$ids)))->delete();
//            }
//        }
//        return $res;
//    }
//    public function _after_insert($id,$data){
//        $this->update_member($data['uid']);
//    }
//    //更新会员类型
//    public function update_member($uid){
//        $type =M('member')->where(array('id'=>$uid))->getField('type');
//        $type==1&&$uid&&M('member')->where(array('id'=>$uid))->setField('type',2);
//    }
//    protected function _before_insert($data) {
//
//        $data['add_time']=  time();
//
//        //图片集
//
//        $data['imgs'] = implode(",",$data['imgs']);
//
//        //上传图片 店招
//
//        if (!empty($_FILES['img']['name'])) {
//
//            $art_add_time = date('ym/');
//
//            $is_thumd = I('is_thumd',0,'intval');
//
//            if($is_thumd){
//
//                $result = $this->_upload($_FILES['img'], 'merchant/' . $art_add_time, array('width'=>C('pin_article_img.width'), 'height'=>C('pin_article_img.height'), 'remove_origin'=>true));
//
//            }else{
//
//                $result = $this->_upload($_FILES['img'], 'merchant/' . $art_add_time);
//
//            }
//
//            if ($result['error']) {
//
//                $this->error($result['info']);
//
//            } else {
//
//                $ext = array_pop(explode('.', $result['info'][0]['savename']));
//
//                $data['img'] =  empty($is_thumd)?$art_add_time.$result['info'][0]['savename']:$art_add_time.str_replace('.' . $ext, '_thumb.' . $ext, $result['info'][0]['savename']);
//
//            }
//
//        }else{
//
//            $data['img'] = I('img');
//
//        }
//
//        return $data;
//
//    }
//
//    //营业执照
//    public function ajax_licence_upload_img() {
//
//        //上传图片
//
//        if (!empty($_FILES['yy_img']['name'])) {
//
//            $result = $this->_upload($_FILES['yy_img'], 'merchant');
//
//            if ($result['error']) {
//
//                $this->ajax_return(0, $result['info']);
//
//            } else {
//
//                $ext = array_pop(explode('.', $result['info'][0]['savename']));
//
//                $data['yy_img'] = $result['info'][0]['savename'];
//
////              $data['img'] = str_replace('.' . $ext, '_thumb.' . $ext, $result['info'][0]['savename']);
//
//                $this->ajax_return(1, L('operation_success'), $data['yy_img']);
//
//            }
//
//        } else {
//
//            $this->ajax_return(0, L('illegal_parameters'));
//
//        }
//
//    }
//
//    //头像
//    protected function _before_update($data) {
//
//        if (!empty($_FILES['img']['name'])) {
//
//            $art_add_time = date('ym/d/');
//
//            //删除原图
//
//            $old_img = $this->_mod->where(array('id'=>$data['id']))->getField('img');
//
//            $old_img = '.'.attach($old_img,'merchant');
//
//            is_file($old_img) && @unlink($old_img);
//
//
//
//            $is_thumd = I('is_thumd',0,'intval');
//
//            if($is_thumd){
//
//                $result = $this->_upload($_FILES['img'], 'merchant/' . $art_add_time, array('width'=>C('pin_article_img.width'), 'height'=>C('pin_article_img.height'), 'remove_origin'=>true));
//
//            }else{
//
//                $result = $this->_upload($_FILES['img'], 'merchant/' . $art_add_time);
//
//            }
//
//            if ($result['error']) {
//
//                $this->error($result['info']);
//
//            } else {
//
//                $ext = array_pop(explode('.', $result['info'][0]['savename']));
//
//                $data['img'] =  empty($is_thumd)?$art_add_time.$result['info'][0]['savename']:$art_add_time.str_replace('.' . $ext, '_thumb.' . $ext, $result['info'][0]['savename']);
//
//            }
//
//        } else {
//
//            unset($data['img']);
//
//        }
//
//        $zftype=implode(',',I('zftype'));
//        IS_AJAX&&!$zftype && $this->ajax_return(0, '请选择支付类型');
//        $zftype&&$data['zftype']=$zftype;
//
//        return $data;
//
//    }
//*******************************************************



    //审核*************************************************************
    //商家审核列表
    public function sh_list(){
        $map=array();
        $map['type'] = 2;//1普通会员，2实名认证中,3已实名认证，4实名认证失败
        ($time_start = I('time_start', '', 'trim')) && $map['rz_time'][] = array('egt', strtotime($time_start));
        ($time_end = I('time_end', '', 'trim')) && $map['rz_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));
        if($keywords = I('keywords', '', 'trim')){
            $map['_string'] = " uid in (select id from jrkj_member where realname like '%".$keywords."%') or ";
            $map['_string'] .= " title like '%".$keywords."%' or ";
            $map['_string'] .= " tel like '%".$keywords."%' ";
        }

        $map['status']=array('in',[0,1]);//待审核
        //分类
        $cate_id = I('cate_id',0,'intval');
        if ($cate_id) {

            $id_arr = $this->_mod_cate->get_child_ids($cate_id, true);
            $map['cate_id'] = array('IN', $id_arr);
            $spid = $this->_mod_cate->where(array('id'=>$cate_id))->getField('spid');
            if( $spid==0 ){
                $spid = $cate_id;
            }else{
                $spid .= $cate_id;
            }
        }
        $search = array(
            'time_start' => $time_start,
            'time_end' => $time_end,
            'keywords' =>$keywords,
            'selected_ids' => $spid,//分类
        );
        $this->assign('search', $search);

        $mod = $this->_mod;//商家表
        $member_mod=D('Member');//用户表
        !empty($mod) && $this->_list($mod, $map);
        $list=$this->get('list');

        if($list){
            //省市区
            $place_ids=array_unique(array_merge (array_column($list,'province_id'),array_column($list,'city_id'),array_column($list,'district_id')));
            $place=M('place')->where(['id'=>['in',$place_ids]])->getField('id,name');
            $this->assign('place', $place);

            //会员表
            $member = $member_mod->where(['id'=>['in',array_column($list,'uid')]])->getField('id,realname,mobile');
            $tuijian_arr=array_filter(array_column($list,'relation_id'));
            !empty($tuijian_arr)&&$member_tj = $member_mod->where(['id'=>['in',$tuijian_arr]])->getField('id,realname,mobile');
            $this->assign('member',$member);
            $this->assign('member_tj',$member_tj);
            //商家分类表
            $res = $this->_mod_cate->field('id,name,pid')->select();
            $cate_list = array();
            foreach ($res as $val) {
                $cate_list[$val['id']] = $val['name'];
                $big_cate2[$val['id']] = $this->_mod_cate -> where(array('id'=>$val['pid'])) ->getField('name');
                $a=$this->_mod_cate -> where(array('id'=>$val['pid'])) ->getField('pid');
                $big_cate3[$val['id']] = $this->_mod_cate -> where(array('id'=>$a)) ->getField('name');
            }
            $this->assign('cate_list', $cate_list);//只有一级
            $this->assign('big_cate2', $big_cate2);//有二级分类时
            $this->assign('big_cate3', $big_cate3);//有三级分类时
        }



        $this->display();
    }


    //审核图片详情
    public function sh_details()

    {
//        M('merchant')->where('id=139')->setField('status',0);
        $id=I('id');

        $list1=$this->_mod->where(['id'=>$id])->getField('yy_img');
        $list2=M('merchant_img')->where(array('merchant_id'=>$id))->getField('img',true);
        $this->assign('list1',$list1);
        $this->assign('list2',$list2);
        $response = $this->fetch();
        $this->ajax_return(1, '', $response);

    }


    //驳回
    public function refuse(){
        $id=I('id','','intval');
        !$id&&$this->ajax_return(0, L('operation_failure'));

        if (false !== M('merchant')->where(['id' => $id])->setField(['status' => 1])){
            IS_AJAX && $this->ajax_return(1, L('operation_success'), '', 'apply');
            $this->success(L('operation_success'));
        } else {
            IS_AJAX && $this->ajax_return(0, L('operation_failure'));
            $this->error(L('operation_failure'));
        }
    }
    /**
     * 商家审核通过操作
     */
    public function pass()
    {
        $id = I('id', 'intval');
        $mod=M('merchant');
        $info = $mod->where(array('id'=>$id))->find();

        if (IS_POST) {

            if (false === $data = $mod->create()) {
                IS_AJAX && $this->ajax_return(0, $mod->getError());
                $this->error($mod->getError());
            }
            $data['zftype']=implode(',', $data['zftype']);

            $start=M();
            $start->startTrans();
            if (false !== $mod->save($data)) {

                //赠送规则
                $tj_silver = 1000;
                $reg_silver =10000;
                $now=time();
                $member_model = D("Member");

                #商铺主人送银币
                $res_member=$member_model->where(array('id'=>$info['uid']))->setInc('silver_coin',$reg_silver);

                if(!$res_member){
                    $start->rollback();
                    $this->ajax_return(0, L('operation_failure'));
                }
                $recharge[] = account_arr(4, $info['uid'], $reg_silver, '注册商家奖励银币', $now);

                #推荐人赠送银币
                if ($info['relation_id']) {
                    $res_up=$member_model->where(array('id'=>$info['relation_id']))->setInc('silver_coin', $tj_silver);

                    if(!$res_up){
                        $start->rollback();
                        $this->ajax_return(0, L('operation_failure'));
                    }
                    $recharge[] = account_arr(4, $info['relation_id'], $tj_silver, '推荐商家奖励银币', $now);
                }

                //添加所有明细
                $res_account = M('account')->addAll($recharge);

                if (!$res_account) {
                    $start->rollback();
                    $this->ajaxReturn(['status'=>0,'msg'=>'系统繁忙']);
                }

                $start->commit();
                $this->ajax_return(1, L('operation_success'), '', 'pass');
            } else {
                $start->rollback();
                IS_AJAX && $this->ajax_return(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }
        }else{
            $imgs = M('merchant_img')->where(array('merchant_id'=>$id))->getField('img',true);

            $this->assign('info',$info);
            $this->assign('imgs',$imgs);

            if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1, '', $response);
            } else {
                $this->display();
            }
        }

    }
    //审核*************************************************************




}