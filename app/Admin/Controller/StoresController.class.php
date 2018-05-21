<?php

namespace Admin\Controller;

class StoresController extends AdminCoreController {

    public function _initialize() {

        parent::_initialize();
		
        $this->_mod = D('Stores');

        $this->set_mod('Stores');
    }



    protected function _search() {

      $map = array(); 
//      $map['type'] = 2;
        ($time_start=I('time_start'))&&$map['add_time'][]=array('egt',strtotime($time_start));

        ($time_end=I('time_end'))&&$map['add_time'][]=array('elt',strtotime($time_end)+24*60*60);


           ($keywords = I('keywords', '', 'trim')) && $map['_string'] = "uid in (select id from jrkj_member where realname like '%".$keywords."%') or title like '%".$keywords."%'";



//      $cate_id = I('cate_id',0,'intval');
//
//      if ($cate_id) {
//
//          $id_arr = D('Place')->get_child_ids($cate_id, true);
//
//          $map['cate_id'] = array('IN', $id_arr);
//
//          $spid = D('Place')->where(array('id'=>$cate_id))->getField('spid');
//
//          if( $spid==0 ){
//
//              $spid = $cate_id;
//
//          }else{
//
//              $spid .= $cate_id;
//
//          }
//
//      }



//		$type=I('type','','intval');
        $this->assign('search', array(

            'keywords' => $keywords,

            'time_start' =>$time_start,

            'time_end'  =>$time_end,

            'selected_ids' => $spid,

            'cate_id' => $cate_id,
//			'type' => $type,

        ));
//		if($type==1){
//			$map['status']=0;
//		}
//		else{
//			$map['status']=array('neq',0);
//		}
        return $map;

    }

	

    public function _before_index()
    {
//  	 $aa=$this->_mod->select();
//  	 var_dump($aa);
		$this->list_relation = true;
        //分类
        $cate_list = M('MemberCate')->getfield('id,name,spid');
        foreach ($cate_list as $k=>$v){
            if($v['spid'] != 0){
                $arr = explode('|',rtrim($v['spid'],'|'));
                $arr[] = $v['id'];
                $str = "";
                foreach ($arr as $vv){
                    $str .= $cate_list[$vv]['name']."||";
                }

                $cate_list[$k]['cate_name'] = rtrim($str,"||");

            }else{

                $cate_list[$k]['cate_name'] = $v['name'];

            }
        }
        
        

		$type =I('type','0','intval');

		$this->assign('type',$type);

        $this->assign('cate_list',$cate_list);



    }
	/**
     * ajax修改单个字段值
     */
    public function ajax_status_edit()
    {
        //AJAX修改数据
        $mod = D($this->_name);
        $pk = $mod->getPk();
        $id = I($pk, 'intval');
        $field = I('field', 'trim');
        $val = I('val', 'trim');
		$s =$val==0?1:2;
        //允许异步修改的字段列表  放模型里面去 TODO
       $mod->where(array($pk=>$id))->setField($field, $s);
        $this->ajax_return(1);
    }
    //驳回
	public function refuse(){
		$id=I('id','','intval');
		if($id){
			$res =M('stores')->where(array('id'=>$id))->setField('status',1);
			!$res&&$this->error('请重试');
		}
		else{
			$this->error('参数非法');
		}
		$this->redirect("Stores/index",array('type'=>1));
	}
	/**
     * 商家审核通过操作
     */
    public function pass()
    {
		$id = I('id', 'intval');
		$mod=M('stores');
		$info = $mod->where(array('id'=>$id))->find();
		$imgs = M('sh_img')->where(array('withdraw_id'=>$id))->getField('img',true);
		
		if (IS_POST) {
            if (false === $data = $mod->create()) {
                IS_AJAX && $this->ajax_return(0, $mod->getError());
                $this->error($mod->getError());
            }
		
            if (method_exists($this, '_before_update')) {
                $data = $this->_before_update($data);
            }
            if (false !== $mod->where(array('id'=>$id))->save($data)) {
            	
            	//被推荐人是shop*
            	
            	#商铺送银币
            	$reg_silver = D('GradeRule')->where(array('id'=>1))->field('reg_silver,tj_silver')->find();
				
            	$mod->where(array('id'=>$id))->setInc('silver_coin',$reg_silver['reg_silver']);
            	all_ls_shop($id, $reg_silver['reg_silver'], 2, 2, '注册商家奖励银币');
				
				$member_model = D("Member");
				#推荐人赠送银币
				if ($info['tuijian']) {  
					#会员   
					D("Member")->where(array('id'=>$info['tuijian']))->setInc('silver_coin', $reg_silver['tj_silver']);
					all_ls($info['tuijian'], $reg_silver['tj_silver'], 6, 2, '推荐商家奖励银币'); 
				}
				  
				$member_model->where(array('id'=>$info['uid']))->setField('type', 2); 
				
                IS_AJAX && $this->ajax_return(1, L('operation_success'), '', 'pass');
                $this->success(L('operation_success'));
            } else {
                IS_AJAX && $this->ajax_return(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }
        }
		else{
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
	//店铺修改信息列表、修改申请表
	 public function update_info() { 
	 
	 	//$this->list_relation = true;
		//echo 434;exit;
		$wh=$this->_search();
		
		$mod=D('AlterStores'); 
		
        !empty($mod) && $this->_list($mod, $wh,'add_time','desc');
		
        $this->display();
    }
	 
	public function merchant_type(){ 
		$merchant_type=M('member_cate')->where(array('status'=>1))
		->field('id,name')->select();
		return $merchant_type;
	}
	
	public function _after_update($id, $data)
	{  
	$res =0;
	//附图处理
		$org_img=M('sh_img')->where(array('withdraw_id'=>$id))
		->getField('img',true);//找出更新前的附图
		$arr =array_diff($org_img,$data['imgs']);//作差集 取出$org_img要删除的图
		if(count($data['imgs'])!=count($org_img)){
        	$res =$this->img_process($id,$data);
		}
		else{
			!empty($arr)&&$res =$this->img_process($id,$data);
		}
		$res && $this->update_member($data['uid']); 
	}
	//图集处理
	public function img_process($id,$data){
		
		$ids=M('sh_img')->where(array('withdraw_id'=>$id))
		->getField('id',true);//找出更新前的附
		$org_img=M('sh_img')->where(array('withdraw_id'=>$id))
							->getField('img',true);//找出更新前的附图
		if($data['imgs']){
			//更新附图
			$arr_imgs=array();
			foreach($data['imgs'] as $k=>$v){
				$arr_imgs[$k]=array(
				'img'=>$v,
				'add_time'=>time(),
				'member_id'=>$data['uid'],
				'withdraw_id'=>$data['id']
				);
			}
			$res =D('ShImg')->addAll($arr_imgs);
			if($res){//删除图集操作
				foreach($org_img as $k=>$v)
				{
					$old_img[$k] = '.'.attach($v,'sh_img');
					$old_img[$k]&& @unlink($old_img[$k]);
					M('sh_img')->where(array('id'=>array('in',$ids)))->delete();
				}
			}
		}
		else{
			//删除图集操作
				foreach($org_img as $k=>$v)
				{
					$old_img[$k] = attach($v,'sh_img');
					is_file($old_img[$k]) && @unlink($old_img[$k]);
					M('sh_img')->where(array('id'=>array('in',$ids)))->delete();
				}
		}
		return $res;
	}
	public function _after_insert($id,$data){
//		$this->update_member($data['uid']);
		
		if($data['imgs']){
			//更新附图
			$arr_imgs=array();
			foreach($data['imgs'] as $k=>$v){
				$arr_imgs[$k]=array(
				'img'=>$v,
				'add_time'=>time(),
				'member_id'=>$data['uid'],
				'withdraw_id'=>$id
				);
			}
			$res =D('ShImg')->addAll($arr_imgs);
			if($res){//删除图集操作
				foreach($org_img as $k=>$v)
				{
					$old_img[$k] = '.'.attach($v,'sh_img');
					$old_img[$k]&& @unlink($old_img[$k]);
					M('sh_img')->where(array('id'=>array('in',$ids)))->delete();
				}
			}
		}
		else{
			//删除图集操作
				foreach($org_img as $k=>$v)
				{
					$old_img[$k] = attach($v,'sh_img');
					is_file($old_img[$k]) && @unlink($old_img[$k]);
					M('sh_img')->where(array('id'=>array('in',$ids)))->delete();
				}
		}
	}
	//更新会员类型type1会员2商家3服务平台
	public function update_member($uid){
		$type =M('member')->where(array('id'=>$uid))->getField('type');
		$type==1&&$uid&&M('member')->where(array('id'=>$uid))->setField('type',3);
	}


    //添加商家时获取会员列表

    public function get_user_list(){

        $parm = I('parm','','trim');
		
        $list = M('member')->where(array('realname|mobile'=>array('like', '%'.$parm.'%'),'type'=>1))

            ->field('id,realname,mobile')

            ->select();
		$real_list =array();
		foreach($list as $k=>$v){
			//过滤掉已添加的服务商
			if(!M('stores')->where(array('uid'=>$v['id']))->count()){
				$real_list[]=$v;
			}
		}
        echo json_encode($real_list);

    }

    public function _before_edit(){
		$this->_relation = true;
		$stores_type=$this->merchant_type();
		$this->assign('stores_type',$stores_type);
    }
	  
	public function _before_add(){
        $stores_type=$this->merchant_type();
		$this->assign('stores_type',$stores_type);
    }
    public function add0(){
    	if(IS_POST){
    		$pos = I('post.'); 
    		var_dump($pos);die;   


	    	$am = D('Stores');
	    	$uid=$pos['uid']; 
	    	$member = D('Member')->find($uid);
	    	$mer = D('Stores')->where(array('tel'=>$pos['tel'],'status'=>array('in',array(0,1,2))))->find();
			
	    	($mer) && exit(json_encode(array('status'=>0,'msg'=>'该服务号码已注册过店铺,请勿重复申请')));//一个商家一个固定电话

	    	$mer_have = D('Stores')->where(array('uid'=>$uid,'status'=>array('in',array(0,1,2))))->find();
	    	($mer_have)&& exit(json_encode(array('status'=>0,'msg'=>'该用户已申请过店铺,请勿重复申请')));//一个用户一个商家   
	    	

	    	
			$data['title']=$pos['title'];
			$data['tuijian']=0;//推荐人
			$data['tel'] = $pos['tel']; 
			$data['cate_id']=$pos['cate_id'];
			$data['shop_hours']=$pos['shop_hours'];
			$data['desc']=$pos['desc'];
			$data['info']=$pos['info'];
			$data['long_lat']=$pos['long_lat'];
			$data['address']=$pos['address'];
			$data['rangli']=$pos['rangli'];
			$data['set_coin']=$pos['set_coin']; 
			
	        $data['uid']=$uid; 
	        $data['add_time']=time();  
	        $data['yy_img'] = $pos['yy_img'];   
	      	$data['zftype']=implode(',', $pos['zftype']);  
			$data['status']=0;//商家申请状态  0为未审核 1为驳回 2为通过	
			$data['is_cat']=2;//启用状态 0禁用 1开启 2未处理',	        
			        
	        //推荐二维码(用户表电话直接去推荐、不作二维码)
//	     	$data['ewm_tj'] =  $this->set_qrcode("http://".$_SERVER['HTTP_HOST'].U('Stores/add_shop',array('tel'=>$pos['tel'])));
			//收款二维码(商户表电话、返银币倍数)、在审核通过后制作ewm
			$uri = "http://".$_SERVER['HTTP_HOST']."/index.php?m=mobile&c=member&a=check_pay&tel=".$pos['tel'];
	       	$data['ewm'] =  $this->set_qrcode($uri);

			$row = $am->add($data);
			//商家轮播图
			if ($row) { 
				$imgs = $pos['imgs'];
				$sh_img['withdraw_id']=$row; 
				$sh_img['member_id']=$uid;
				$sh_img['add_time']=time();  
				foreach ($imgs as $k) {    
					$sh_img['img'] = $k; 
					D('ShImg')->add($sh_img);
				} 
			} 
			
			exit(json_encode(array('status'=>1,'msg'=>'操作成功')));
//			$this->error('操作成功') ;
    	}else{ 
    		$this->display();
    	}
    }
	

    protected function _before_insert($data) {

//		//图片集
//
//       $data['imgs'] = implode(",",$data['imgs']);
//
//		 //上传图片 店招
//
//      if (!empty($_FILES['img']['name'])) {
//
//          $art_add_time = date('ym/');
//
//			$is_thumd = I('is_thumd',0,'intval');
//
//			if($is_thumd){
//
//				$result = $this->_upload($_FILES['img'], 'stores/' . $art_add_time, array('width'=>C('pin_article_img.width'), 'height'=>C('pin_article_img.height'), 'remove_origin'=>true));
//
//			}else{
//
//				$result = $this->_upload($_FILES['img'], 'stores/' . $art_add_time);
//
//			}
//
//          if ($result['error']) {
//
//              $this->error($result['info']);
//
//          } else {
//
//              $ext = array_pop(explode('.', $result['info'][0]['savename']));
//
//				$data['img'] =  empty($is_thumd)?$art_add_time.$result['info'][0]['savename']:$art_add_time.str_replace('.' . $ext, '_thumb.' . $ext, $result['info'][0]['savename']);
//
//          }
//      }else{
//
//			$data['img'] = I('img');
//
//		}

        return $data;

    }

	
	//营业执照
	  public function ajax_licence_upload_img() {

        //上传图片

        if (!empty($_FILES['yy_img']['name'])) {

            $result = $this->_upload($_FILES['yy_img'], 'stores');

            if ($result['error']) {

                $this->ajax_return(0, $result['info']);

            } else {

                $ext = array_pop(explode('.', $result['info'][0]['savename']));
                
				$data['yy_img'] = $result['info'][0]['savename'];
				
//              $data['img'] = str_replace('.' . $ext, '_thumb.' . $ext, $result['info'][0]['savename']);

                $this->ajax_return(1, L('operation_success'), $data['yy_img']);

            }

        } else {

            $this->ajax_return(0, L('illegal_parameters'));

        }

    }

    protected function _before_update($data) {

        if (!empty($_FILES['img']['name'])) {

            $art_add_time = date('ym/d/');

            //删除原图

            $old_img = $this->_mod->where(array('id'=>$data['id']))->getField('img');

            $old_img = '.'.attach($old_img,'stores');

            is_file($old_img) && @unlink($old_img);



          		$is_thumd = I('is_thumd',0,'intval');

			if($is_thumd){

				$result = $this->_upload($_FILES['img'], 'stores/' . $art_add_time, array('width'=>C('pin_article_img.width'), 'height'=>C('pin_article_img.height'), 'remove_origin'=>true));

			}else{

				$result = $this->_upload($_FILES['img'], 'stores/' . $art_add_time);

			}

            if ($result['error']) {

                $this->error($result['info']);

            } else {

                $ext = array_pop(explode('.', $result['info'][0]['savename']));

                $data['img'] =  empty($is_thumd)?$art_add_time.$result['info'][0]['savename']:$art_add_time.str_replace('.' . $ext, '_thumb.' . $ext, $result['info'][0]['savename']);

            }

        } else {

            unset($data['img']);

        }
		
		$zftype=implode(',',I('zftype'));
		IS_AJAX&&!$zftype && $this->ajax_return(0, '请选择支付类型');
		$zftype&&$data['zftype']=$zftype;
		
		return $data;

	 }







    public function ajax_getRangli() {

	    $return=array('0.1','0.15','0.2','0.25','0.3','0.35','0.4','0.45','0.50');

        $this->ajax_return(1, L('operation_success'), $return);

    }

	

	

    //异步上传图片

    public function ajax_upload_img(){

        $date_dir = date('ym/d/'); //上传目录

        $result = $this->_upload($_FILES['file'], 'stores_img/'.$date_dir, array());

        if ($result['error']) {

            echo json_encode(array("error" => $result['info']));

        } else {

            $data['thumb_img'] = $date_dir .$result['info'][0]['savename'];

            echo json_encode(array("error" => "0", "src" => $data['thumb_img'], "name" => $result['info'][0]['savename']));

        }

        exit;

    }

	//下载报表 商家列表
     public function export()
	{
		ob_end_clean();
		
		$map = $this->_search();
    	$list = $this->_mod->where($map)->relation(true)->select();//数据
        $cate_list = M('MemberCate')->getfield('id,name'); //分类
        $data = array(); 
        foreach ($list as $k => $v) {
            $data[$k]['id']= $v['id']; //序号
            $data[$k]['title'] = $v['title']; //店名
            $data[$k]['realname'] = $v['member_uid']['realname']; //会员名
            $data[$k]['tel'] = $v['tel'];//手机
            $data[$k]['address'] = $v['address'];//地址
            $data[$k]['cate_name']=$cate_list[$v['cate_id']];//分类
            $data[$k]['shouyi']=$v['shouyi'];//收益
            $data[$k]['rangli']=$v['rangli'].'%';//让利
            $data[$k]['set_coin']=$v['set_coin'];//返银倍数
             $aa=explode(',', $v['zftype']);
             $data[$k]['sy_type']='';//收银类型
             foreach($aa as $vv){
             	($vv==1)&&$data[$k]['sy_type'] .='金宝';
             	($vv==2)&&$data[$k]['sy_type'] .='银宝';
             	($vv==3)&&$data[$k]['sy_type'] .='金果';
             }
            $data[$k]['tuijian'] = $v['member_tuijian']['realname'];//推荐人
            $data[$k]['add_time'] = date('Y-m-d H:i' ,$v['add_time']);//添加日期
            ($v['status']==1)&& $data[$k]['status']='冻结';//状态
            ($v['status']==2)&& $data[$k]['status']='正常';
        	
        }
		
        $headArr = array();
		$headArr[] = '序号';
		$headArr[] = '店名';
		$headArr[] = '会员名';
		$headArr[] = '手机';
		$headArr[] = '地址';
		$headArr[] = '分类';
		$headArr[] = '收益';
		$headArr[] = '让利';
		$headArr[] = '返银倍数';
		$headArr[] = '收银类型';
		$headArr[] = '推荐人';
		$headArr[] = '添加日期';
		$headArr[] = '状态';
        $filename="直营店列表".date("Y-m-d");
        $this->getExceltjab($filename, $headArr, $data); 
	
	}
	
	


}