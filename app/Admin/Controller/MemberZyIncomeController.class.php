<?php
namespace Admin\Controller;
use Admin\Org\Tree;
class MemberZyIncomeController extends AdminCoreController {
    public function _initialize() {
		parent::_initialize();
		$this->list_relation=false;
        $this->_mod = D('MemberZyIncome');
		$this->set_mod('MemberZyIncome');
    }

	 public function qrcode($url='http://www.0791jr.com/',$level=3,$size=4){
         echo $this->set_qrcode($url='http://www.0791jr.com/',$level=3,$size=4);
     }
	
	
	
	
    protected function _search() {
        $map = array();
        ($time_start=I('time_start'))&&$map['add_time'][]=array('egt',strtotime($time_start));
		($time_end=I('time_end'))&&$map['add_time'][]=array('elt',strtotime($time_end)+24*60*60);
       ($keywords = I('keywords','', 'trim')) && $map['_string'] = " member_id in (select id from ".C('DB_PREFIX')."member where realname like '%".$keywords."%' or mobile like '%".$keywords."%')";
//var_dump($map['_string']);die;
        $this->assign('search', array(
            'keywords' => $keywords,
            'time_start' =>$time_start,
            'time_end'  =>$time_end
        ));
        return $map;
    }

    public function _before_index(){
		$this->list_relation=true;
    	
        $grade = M('GradeRule')->getfield('id,name');
        $this->assign('grade',$grade);
    }
    
    
    
    
    
    
    public function _before_insert($data){
    	$mem_mod=D('Member');
    	$mem=$mem_mod->where(array('id'=>$data['member_id']))->find();
    	
    	$data['vips_ago']=$mem['vips'];
		//查询超级会员表,已经是启用状态的超级会员的，不允许下线有超级会员
		$supermem=$mem_mod->where(array('id'=>240))->field('id,relation_id,is_super')->find();//本人是否已有超级会员
		while($supermem['relation_id']) {
			$supermem=$mem_mod->where(array('id'=>$supermem['relation_id']))->field('id,relation_id,is_super')->find();//本人是否已有超级会员
		} 	
		$have_first3=$mem_mod->where(array('id'=>$supermem['id']))->getField('is_super');//上面是否有超级会员
		
		$have_first1=$mem_mod->where(array('id'=>$mem['id']))->getField('is_super');//本人是否已有超级会员
		$have_first2=$mem_mod->where(array('id'=>$mem['relation_id']))->getField('is_super');//上线是否有超级会员
//		$have_first3=$mem_mod->where(array('id'=>$mem['first_relation_id']))->getField('is_super');//上面是否有超级会员
		$have_first4=$mem_mod->where(array('relation_id'=>$mem['id']))->getField('is_super');//下线是否有超级会员
    	$data['flag']=666;
    	($have_first4)&&$data['flag']=4;
    	($have_first3)&&$data['flag']=3;
    	($have_first2)&&$data['flag']=2;
    	($have_first1)&&$data['flag']=1;
    	
//  	if(($have_first0)||($have_first1)||($have_first2)||($have_first3)){
//  		$data['flag']=1;
//  	}
    	return $data;
    }
	
   	public function _after_insert($id,$data){
   		($data['status']==1)&&D('Member')->where(array('id'=>$data['member_id']))->save(array('is_super'=>1));
    }
    
    
    /**
     * 添加
     */
    public function add() {
        $mod = D('MemberZyIncome');
        if (IS_POST) { 
				
            if (false === $data = $mod->create()) {
	            IS_AJAX && $this->ajax_return(0, $mod->getError());
	            $this->error($mod->getError());
            }  
             if (method_exists($this, '_before_insert')) { 
                $data = $this->_before_insert($data);
                if($data['flag']!=666){
	            	 IS_AJAX && $this->ajax_return(0, L('operation_failure'));
	            	($data['flag']==4)&&$msg='下线是超级会员';
	            	($data['flag']==3)&&$msg='上面已有超级会员';
	            	($data['flag']==2)&&$msg='上线是超级会员';
	                ($data['flag']==1)&&$msg='本人已有超级会员';
	           		  //下面有超级会员?
	           		 $this->error($msg);
           		 }
           		 ($data['flag'])&& array_splice($data,-1,1);
            } 
			
		
            if( $mod->add($data) ){
                if( method_exists($this, '_after_insert')){
                    $id = $mod->getLastInsID();
                    $this->_after_insert($id,$data);
                }  
                
                IS_AJAX && $this->ajax_return(1, L('operation_success'), '', 'add');
                $this->success(L('operation_success'));
            } else { 
                IS_AJAX && $this->ajax_return(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }
        } else {
            $this->assign('open_validator', true);
            if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1,'',$response,'add');
            } else {
                $this->display();
            }
        }
    }
    
    
    
    
 	//添加时获取会员列表
    public function get_user_list(){

        $parm = I('parm','','trim');
		
        $list = M('member')->where(array('realname|mobile'=>array('like', '%'.$parm.'%')))
            ->field('id,realname,mobile')
            ->select();
		$real_list =array();
		foreach($list as $k=>$v){
			//过滤掉已成为超级会员的人
			if(!M('member')->where(array('id'=>$v['id'],'is_super'=>1))->count()){
				$real_list[]=$v;
			}
		}
        echo json_encode($real_list);
    }

    /**
     * ajax修改单个字段值
     */
    public function ajax_edit()
    {
        //AJAX修改数据
        $mod = $this->_mod;
        $pk = $mod->getPk();

        $id = I($pk, 'intval');
        $field = I('field', 'trim');
        $val = I('val', 'trim');
        //允许异步修改的字段列表  放模型里面去 TODO
     	$field_ok=  $mod->where(array($pk=>$id))->setField($field, $val);
     	//禁用超级会员
     	if($field_ok&&$field='status'){
     		$super=$mod->find($id);
     		D('Member')->where(array('id'=>$super['member_id']))->setField('is_super',$val);
     	}
        $this->ajax_return(1);
    }






    public function _before_edit(){
         $grade = M('GradeRule')->where(array('status'=>1))->field('id,name,upgrade_price')->select();
        $this->assign('grade',$grade);
		
        $id = I('id', '', 'intval');
        $place = $this->_mod->field('id,place')->where(array('id' => $id))->find();
        $place_spid = M('Place')->where(array('id' => $place['place']))->getField('spid');
        if ($place_spid == 0) {
            $place_spid = $place['place'];
        } else {
            $place_spid .= $place['place'];
        }

        $this->assign('spid', $place_spid);
        $this->assign('place_type',C('place_type'));
    }
	//后置操作 更新流水表
	public function _after_update($id,$data){
		$act_type =I('act_type','','intval');
		$uid =I('member_id','','intval');
		$amount =I('amount','','intval');
		$vips =I('vips','','intval');
		$vips_info=M('grade_rule')->where(array('id'=>$vips))->field('upgrade_silver,upgrade_price,upgrade_one_price,upgrade_two_price')->find();
		if($act_type==1){
			$session=session('admin');
			$da =array(
			'member_id'=>$uid,
			'totalprices'=>$amount,
			'add_time'=>time(),
			'status'=>2,
			'item_type'=>5,
			'type'=>2,
			'after_vip'=>$vips,
			'old_vip'=>I('old_vip'),
			'operator'=>$session['username'],
			);
			$da&&M('member_recharge')->add($da);//充值流水记录
			//银币操作
			if($da&&$vips_info['upgrade_silver']){
				$res=M('member')->where(array('id'=>$uid))->setInc('silver_coin',$vips_info['upgrade_silver']);
			}
			//赠送金额
			if($da&&$vips_info['upgrade_price']){
				$res=M('member')->where(array('id'=>$uid))->setInc('prices',$vips_info['upgrade_price']);
			}
			//上线赠送金额
			$price =$vips==2?$vips_info['upgrade_one_price']:$vips_info['upgrade_two_price'];
			if($da&&$price){
				$relation_id =M('member')->where(array('id'=>$uid))->getField('relation_id');
				
				$relation_id&&M('member')->where(array('id'=>$relation_id))->setInc('prices',$prices);
			}
		}
	}
	/**
     * 修改
     */
    public function edit()
    {
        $mod = D($this->_name);
        $pk = $mod->getPk();	
        if (IS_POST) {
            if (false === $data = $mod->create()) {
                IS_AJAX && $this->ajax_return(0, $mod->getError());
                $this->error($mod->getError());
            }
            if (method_exists($this, '_before_update')) {
                $data = $this->_before_update($data);
            }
			$ad_password=I('admin_password','trim');
			if($ad_password){
         		$admin=M('admin')->where(array('id'=>1))->getField('password');
				IS_AJAX && md5($ad_password)!=$admin&&$this->ajax_return(0,'管理员密码不正确');
			}
            if (false !== $mod->save($data)) {
                if( method_exists($this, '_after_update')){
                    $id = $data['id'];
                    $this->_after_update($id,$data);
                }
                IS_AJAX && $this->ajax_return(1, L('operation_success'), '', 'edit');
                $this->success(L('operation_success'));
            } else {
                IS_AJAX && $this->ajax_return(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }
        } else {
            $id = I($pk, 'intval');
			$type = I('type','1', 'intval');
            $this->_relation && $mod->relation(true);
            $info = $mod->find($id);
			$before_vip=M('grade_rule')->where(array('id'=>$info['vips']))->getField('name');
            $this->assign('info', $info);
			$this->assign('before_vip', $before_vip);
			$this->assign('type', $type);
            $this->assign('open_validator', true);
			//dump($info);
            if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1, '', $response);
            } else {
                $this->display();
            }
        }
    }
	/**
     * 修改
     */
    public function check_img()
    {
        
            $id = I('id', 'intval');
			$imgs=M('member_idcard')->where(array('id'=>$id))
			->field('img_one,img_two')->find();
			$this->assign('imgs',$imgs);
            if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1, '', $response);
            } else {
                $this->display();
            }
    }
	 //导出
    public function export0(){
        $id = I('id','','trim');
        //课堂分类
        $cate_list = M('CurriculumCate')->getfield('id,name,spid');
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
        //学生列表
        $list = $this->_mod->where(array('id'=>array('in',$id)))->select();

        //处理列表
        $data = array();
        foreach ($list as $k=>$val){
            switch ($val['status']){
                case 0:
                    $status = "冻结";
                    break;
                case 1:
                    $status = "正常";
                    break;
            }

            $data[$k] = array(
                $val['id'],
                $val['realname'],
                $val['mobile'],
                $cate_list[$val['cate_id']]['cate_name'],
                $val['integral'],
                date("Y-m-d",$val['reg_time']),
                $status
            );
        }
        $headArr=array(
            'ID',
            '姓名',
            '手机',
            '所属分类',
            '积分',
            '注册日期',
            '状态'
        );
        $this->getExcel("学生会员",$headArr,$data);
    }


	//升级记录
	public function upgrade(){
		($time_start = I('time_start', '', 'trim')) && $map['add_time'][] = array('egt', strtotime($time_start));
        ($time_end = I('time_end', '', 'trim')) && $map['add_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));
		($keywords = I('keywords', '', 'trim')) && $map['_string'] = "member_id in (select id from jrkj_member where mobile like '%".$keywords."%' or realname like '%".$keywords."%') or operator like '%".$keywords."%'";
		$map['item_type'] = 5;
		$map['status'] = '2';
		$map['dingdan'] = array('gt', 0); 
		$mod = D('MemberRecharge'); 
		
		 $search = array(     
            'time_start' => $time_start, 
            'time_end' => $time_end,  
            'keywords' =>$keywords,
        ); 
		$this->assign('search', $search);
		$this->list_relation=true;
        !empty($mod) && $this->_list($mod, $map);
        $this->display();
	}
	
	//会员审核列表
	public function check_index(){
		$map=array();
		($time_start = I('time_start', '', 'trim')) && $map['add_time'][] = array('egt', strtotime($time_start));
        ($time_end = I('time_end', '', 'trim')) && $map['add_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));
		($keywords = I('keywords', '', 'trim')) && $map['_string'] = "member_id in (select id from jrkj_member where mobile like '%".$keywords."%') or realname like '%".$keywords."%'";
		$mod = D('MemberIdcard'); 
		 $search = array(     
            'time_start' => $time_start, 
            'time_end' => $time_end,  
            'keywords' =>$keywords,
        ); 
		$this->assign('search', $search);
		$this->list_relation=true;
        !empty($mod) && $this->_list($mod, $map);
        $this->display();
	}
	//会员审核 驳回
	public function act_check(){
		$mod = D('MemberIdcard'); 
		$id=I('id','intval');
		$status=I('status','intval');
		$status=$status==1?0:1;
		$info=$mod->where(array('id'=>$id))
		->field('realname,member_id')->find();
		$res=$mod->where(array('id'=>$id))->setField('status',$status);
		if($res&&$status==1){
			//跟新昵称
			$result =M('member')->where(array('id'=>$info['member_id']))->setField('realname',$info['realname']);
		}
		$res&&$this->ajax_return(1, L('operation_success'), '', 'act_check');
		!$res&&$this->ajax_return(0, L('operation_failure'), '', 'act_check');
	}
    //导入
    public function member_upload()
    {
        $data_list = $this->ru_upload();
        $ip = get_client_ip();

        $data = array();
        foreach ($data_list as $k=>$v){
            $data[] = array(
                'password' => st_md5($v['C']),
                'realname' => $v['A'],
                'mobile' => $v['B'],
                'reg_time' => time(),
                'reg_ip' => $ip
            );
        }
        unset($data_list);
        //添加数据
        $this->_mod->startTrans();
        $ok = $this->_mod->addAll($data);
        if($ok){
            $this->_mod->commit();
            $this->success('导入成功');
        }else{
            $this->_mod->rollback();
            $this->error('导入失败');
        }
    }

    public function _before_update($data){
        if($data['password'] == 'd85c61834e9239b7bef468a430bbb3dc')
            unset($data['password']);
        unset($data['last_login_time'],$data['last_login_ip']);

        return $data;
    }	
	
	//	余额详情
	public function balance(){
        $uid = I('id','intval');
        $balance = M('Member_recharge')-> where(array('member_id'=>$uid)) -> order('id desc')  -> select();
        $member_recharge_status=C('member_recharge_status');
        $zftype=C('zftype');
        $this->assign('member_recharge_status', $member_recharge_status);
        $this->assign('balance', $balance);
        $this->assign('zftype', $zftype);
        if (IS_AJAX) {
            $response = $this->fetch();
            $this->ajax_return(1, '', $response);
        } else {
            $this->display();
        }
    }
	
	
//	积分详情
	public function integral(){
        $uid = I('id','intval');
        $integral = M('integral')-> where(array('member_id'=>$uid)) -> order('id desc')  -> select();
        $this->assign('integral', $integral);
        if (IS_AJAX) {
            $response = $this->fetch();
            $this->ajax_return(1, '', $response);
        } else {
            $this->display();
        }
    }




    public function user_thumb($id,$img){
        $img_path= avatar_dir($id);
        //会员头像规格
        $avatar_size = explode(',', C('pin_avatar_size'));
        $paths =C('pin_attach_path');

        foreach ($avatar_size as $size) {
            if($paths.'avatar/'.$img_path.'/' . md5($id).'_'.$size.'.jpg'){
                @unlink($paths.'avatar/'.$img_path.'/' . md5($id).'_'.$size.'.jpg');
            }
            !is_dir($paths.'avatar/'.$img_path) && mkdir($paths.'avatar/'.$img_path, 0777, true);
            Image::thumb($paths.'avatar/temp/'.$img, $paths.'avatar/'.$img_path.'/' . md5($id).'_'.$size.'.jpg', '', $size, $size, true);
        }

        @unlink($paths.'avatar/temp/'.$img);
    }


    public function ajax_upload_imgs() {
        $date_dir = date('ym/d/');
        if (!empty($_FILES['avatar']['name'])) {
            $result = $this->_upload($_FILES['avatar'], 'avatar/'.$date_dir, array());
            if ($result['error']) {
                $this->ajax_return(0, L('illegal_parameters'));
            } else {
                $ext = array_pop(explode('.', $result['info'][0]['savename']));
                $data['img'] ="./data/attachment/avatar/".$date_dir.$result['info'][0]['savename'];
//              $data['thumb_img'] = str_replace('.' . $ext, '_thumb.' . $ext, $result['info'][0]['savename']);
                $this->ajax_return(1, L('operation_success'), $data['img']);
            }
        }

    }

    /**
     * ajax检测身份证是否存在
     */
    public function ajax_check_id_card() {

        $id_card = I('J_id_card','', 'trim');
        $id = I('id',0, 'intval');
        $mod = D('Identity');
		$result = $mod->where(array('id_card'=>$id_card,'id'=>array('neq',$id)))->find();
        if ($result) {
            $this->ajax_return(0, '该身份证号已经存在');
        } else {
            $this->ajax_return();
        }
    }

    /**
     * ajax检测会员是否存在
     */
    public function ajax_check_name() {
        $name = $this->_get('username', 'trim');
        $id = $this->_get('id', 'intval');
        if ($this->_mod->name_exists($name,  $id)) {
            $this->ajax_return(0, '该会员已经存在');
        } else {
            $this->ajax_return();
        }
    }

    /**
     * ajax检测邮箱是否存在
     */
    public function ajax_check_val() {
        $key = I('clientid','','trim');
        $val = I($key,'','trim');
        $id = I('id',0, 'intval');
        $result = $this->_mod->where(array($key=>$val,'id'=>array('neq',$id)))->getField('id');
        if ($result) {
            $this->ajax_return(0, '该邮箱已经存在');
        } else {
            $this->ajax_return();
        }
    }

	//导出数据
	public function dao(){
		 $fileName ="会员列表";
		 $xlisCell = array('会员id','会员昵称','会员姓名','手机号','邮箱','VIP等级','头像','余额','积分');
		 $data=M('member')->field('id,nickname,realname,mobile,email,vips,avatar,prices,integral')->select();
		 $this->getExcel($fileName,$xlisCell,$data);
	}
	
    //注册送积分
	public function reg_integral(){
	    $rule = M('IntegralRule');
	    if(IS_POST){
            $rule->create();
            $rule->start_time = strtotime($rule->start_time);
            $rule->end_time = strtotime($rule->end_time);
            if($rule->save()){
                $this->success('修改成功');
            }else{
                $this->error('操作失败，请重试');
            }
        }else{
            $info = $rule->where(array('id'=>1))->find();

            $this->assign('info',$info);
            $this->display();
        }
	}

	//开启，关闭积分规则
    public function operation_integral_rule(){
	    $id = I('rule_id','','trim');
	    $status = I('status','','trim');
        $status = $status ? 1 : 0;
        if(M('IntegralRule')->where(array('id'=>array('in',$id)))->setfield('status',$status)){
            if($status){
                exit(json_encode(array(1,'注册送积分已开启')));
            }else{
                exit(json_encode(array(1,'注册送积分已关闭')));
            }
        }else{
            exit(json_encode(array(0,'操作失败，请重试')));
        }
    }

    //购物送积分
	public function shop_integral(){
        $rule = M('IntegralRule');
        if(IS_POST){
            $data = I('post.');
            $data['two']['status'] = $data['two']['status'] ? 1 : 0;
            $check = true;
            foreach ($data as $v){
                if(false === $rule->save($v)){
                    $check = false;
                }
            }
            if($check){
                $this->success('修改成功');
            }else{
                $this->error('操作失败，请重试');
            }
        }else{
            //第一个
            $one = $rule->where(array('id'=>2))->find();
            //第二个
            $two = $rule->where(array('id'=>3))->find();

            $this->assign('one',$one);
            $this->assign('two',$two);
            $this->display();
        }
	}
	
	
	 //下载报表 会员列表
     public function export()
	{
		ob_end_clean();
		
		$map = $this->_search();
    	$list = $this->_mod->where($map)->relation(true)->select();
        $grade = M('GradeRule')->getfield('id,name');
        $data = array(); 
        foreach ($list as $k => $v) {
            $data[$k]['id']= $v['id']; //序号
            $data[$k]['realname'] = $v['realname']; //会员名
            $data[$k]['mobile'] = $v['mobile']; //手机
            $data[$k]['vips'] = $grade[$v['vips']]; //级别
            $data[$k]['recommend_nums'] = recommend_nums($v['id']); //推荐人数
            $data[$k]['jbp'] = $v['gold_acer_jc']+$v['silver_acer_jc']; //聚宝盆
            $data[$k]['prices'] = $v['prices'];//余额
            $data[$k]['gold_acer']=$v['gold_acer'];//金元宝
            $data[$k]['silver_acer']=$v['silver_acer'];//银元宝
            $data[$k]['silver_coin']=$v['silver_coin'];//银币
            $data[$k]['gold_coin']=$v['gold_coin'];//金币
            $data[$k]['gold_fruit']=$v['gold_fruit'];//金果
            $data[$k]['reg_time'] = date('Y-m-d H:i' ,$v['reg_time']);//注册日期
            ($v['status']==0)&& $data[$k]['status']='冻结';//状态
            ($v['status']==1)&& $data[$k]['status']='正常';
        	
        }
		
        $headArr = array();
		$headArr[] = '序号';
		$headArr[] = '会员名';
		$headArr[] = '手机';
		$headArr[] = '级别';
		$headArr[] = '推荐人数';
		$headArr[] = '聚宝盆';
		$headArr[] = '余额';
		$headArr[] = '金元宝';
		$headArr[] = '银元宝';
		$headArr[] = '银币';
		$headArr[] = '金币';
		$headArr[] = '金果';
		$headArr[] = '注册日期';
		$headArr[] = '状态';
        $filename="会员列表".date("Y-m-d");
        $this->getExceltjab($filename, $headArr, $data); 
	
	}
	
	
	
	
	   private  function getExceltjab($fileName, $headArr, $data){
        //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
        import("Org.Util.PHPExcel");
        import("Org.Util.PHPExcel.Writer.Excel5");
        import("Org.Util.PHPExcel.IOFactory.php");
        $date = date("Y_m_d",time());
        $fileName .= "_{$date}.xls"; 

        //创建PHPExcel对象，注意，不能少了\

        $objPHPExcel = new \PHPExcel();

        $objProps = $objPHPExcel->getProperties();

        //设置表头
        $key = ord("A");
        //print_r($headArr);exit;
        foreach($headArr as $v){
            $colum = chr($key);
            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
            $key += 1;
        }

        $column = 2;
        $objActSheet = $objPHPExcel->getActiveSheet();

        //print_r($data);exit;
        foreach($data as $key => $rows){ //行写入
            $span = ord("A");
            foreach($rows as $keyName=>$value){// 列写入
                $j = chr($span);
                $objActSheet->setCellValue($j.$column, $value.' ');
                $span++;
            }
            $column++;
        }

        $fileName = iconv("utf-8", "gb2312", $fileName);
        //重命名表
        //$objPHPExcel->getActiveSheet()->setTitle('test');
        //设置活动单指数到第一个表,所以Excel打开这是第一个表
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms 

-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\""); 
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); //文件通过浏览器下载
        exit;
    } 
	
	
	
	
	
	
	

}