<?php
namespace Admin\Controller;
use Admin\Org\Tree;
class MemberController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
//		$this->list_relation=false;
        $this->_mod = D('Member');
		$this->set_mod('Member');
    }

	 public function qrcode($url='http://www.0791jr.com/',$level=3,$size=4){
         echo $this->set_qrcode($url='http://www.0791jr.com/',$level=3,$size=4);
     }


    public function _before_index()
    {

        $this->sort = 'id';
        $this->order = 'ASC';
        
        $p = I('p',1,'intval');
        $this->assign('p',$p);

        $grade = M('GradeRule')->getfield('id,name');
        $this->assign('grade',$grade);


    }

    protected function _search() {
        $map = array();
        ($time_start=I('time_start'))&&$map['reg_time'][]=array('egt',strtotime($time_start));
        ($time_end=I('time_end'))&&$map['reg_time'][]=array('elt',strtotime($time_end)+24*60*60);
        if($time_start>$time_end) unset($map['reg_time']);
        ($vips=I('vips'))&&$map['vips']=$vips;
        if( $keywords = I('keyword','', 'trim') ){
            $map['_string'] = " nickname like '%".$keywords."%' or mobile like '%".$keywords."%' ";
        }

        $this->assign('search', array(
            'keywords' => $keywords,
            'time_start' =>$time_start,
            'time_end'  =>$time_end,
			'vips'  =>$vips
        ));
        return $map;
    }

     //下线列表页面*************************
    protected function next_list_search() {
          $map = array();
			($member_id=I('id'))&&$map['relation_id']=$member_id;
			($vips=I('vips'))&&$map['vips']=$vips;
	        ($time_start=I('time_start'))&&$map['reg_time'][]=array('egt',strtotime($time_start));
			($time_end=I('time_end'))&&$map['reg_time'][]=array('elt',strtotime($time_end)+24*60*60);
	//     ($keywords = I('keywords','', 'trim')) && $map['_string'] = " member_id in (select id from ".C('DB_PREFIX')."member where realname like '%".$keywords."%' or mobile like '%".$keywords."%')";
	    	 $this->assign('search', array(
	            'vips'  =>$vips,
	            'id'=>$member_id
	        ));
     	  return $map;
		
    }

    public function next_list() {
    	$p = I('p',1,'intval');
        $this->assign('p',$p);
		$grade = M('GradeRule')->getfield('id,name');
        $this->assign('grade',$grade);

        $map=$this->next_list_search();
		//所有下线

        $list = $this->_mod->field('id,vips,realname,mobile,reg_time,status,relation_id')->where($map)->select();
    	//该推荐人本人的手机
    	$member = $this->_mod->where(['id'=>$map['relation_id']])->getField('id,mobile');
        $this->assign('member',$member);

        //所有下线的升级时间
//    	$sj['_string']=' type = 3 and status = 2 and dingdan != 0 and member_id in('.implode(',',array_column($list,'id')).')';
//    	$member_recharge = M('OrderRecharge')->where($sj)->getField('member_id,add_time');
//		$this->assign('member_recharge',$member_recharge);
		
        $mod = $this->_mod;
        !empty($mod) && $this->_list($mod, $map);
        $this->display();
    }
    //下线列表页面 结束*************************
    protected function merchant_list_search(){
        $map = array();
        ($member_id = I('id')) && $map['relation_id'] = $member_id;
        ($time_start=I('time_start'))&&$map['add_time'][]=array('egt',strtotime($time_start));
        ($time_end=I('time_end'))&&$map['add_time'][]=array('elt',strtotime($time_end)+24*60*60);
        ($keywords = I('keywords','', 'trim')) && $map['_string'] = "address like '%".$keywords."%' or title like '%".$keywords."%')";
        $this->assign('search', array(
            'keywords'     =>$keywords,
            'time_end'     =>$time_end,
            'time_start'   =>$time_start,
             'id'          =>$member_id
        ));
        return $map;
    }
    public function merchant_list() {
        $p = I('p',1,'intval');
        $this->assign('p',$p);
        $map = $this->merchant_list_search();
        $mod = M('merchant');
        !empty($mod) && $this->_list($mod, $map);
        $this->display();
    }

    public function index() {
        $map = $this->_search();
        $mod = $this->_mod;
        $list=$mod->where($map)->field('id,relation_id')->select();
        //上线的手机
        $list&&$member = $this->_mod->where(['id'=>['in',array_unique(array_column($list,'relation_id'))]])->getField('id,mobile');
        $this->assign('member',$member);
        !empty($mod) && $this->_list($mod, $map);

        $this->display();
    }

    public function _before_add(){
        $grade = M('GradeRule')->field('id,name')->select();
        $this->assign('grade',$grade);
		
		$menus = M('place')->where('status=1')->select();
		 foreach ($menus as $key=> $val) {
		$return[$val['id']]=$val;
		 }

	   $tree = new Tree();
	   $tree->icon = array('│ ','├─ ','└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
	   $tree->init($return);
      $this->assign('area',$tree->getArray());
    }

	/**
     * 修改
     */
    public function edit()
    {
        $mod = D($this->_name);
        $pk = $mod->getPk();
        $id = I($pk, 'intval');
        $info = $mod->find($id);
        if (IS_POST) {
            if (false === $data = $mod->create()) {
                IS_AJAX && $this->ajax_return(0, $mod->getError());
                $this->error($mod->getError());
            }
            if (method_exists($this, '_before_update')) {
                $data = $this->_before_update($data);
            }
			$ad_password=I('admin_password','','trim');

			if($ad_password){
         		$admin=M('admin')->where(array('id'=>1))->getField('password');
				IS_AJAX && md5($ad_password)!=$admin&&$this->ajax_return(0,'管理员密码不正确');
			}

            //修改头像
            if (!empty($_FILES['avatar']['name'])) {
                $result = $this->_upload($_FILES['avatar'],'avatar', array('width'=>C('pin_article_cate_img.width'), 'height'=>C('pin_article_cate_img.height')));
                if ($result['error']) {
                    $this->ajax_return(0, $result['info']);
                } else {
                    $ext = array_pop(explode('.', $result['info'][0]['savename']));
                    $data['avatar'] = $result['info'][0]['savename'];
                }
            }

            //修改金额
            if (method_exists($this, 'update_money')) {
                $update_money= $this->update_money($data,$info);
                if($update_money['status']>0){
                    $this->error( $update_money['msg']);//余额不足
                }
                $data=$update_money['data'];
                $mr=$update_money['mr'];
            }


            $start=M();
            $start->startTrans();
            $model = M('member_idcard');
            $id = $data['id'];

            //修改身份证正面
            if (!empty($_FILES['id_card1']['name'])) {
                //$result = $this->_upload($_FILES['id_card1'],'id_card', array('width'=>C('pin_article_cate_img.width'), 'height'=>C('pin_article_cate_img.height')));
                $result = $this->_upload($_FILES['id_card1'],'id_card');
                if ($result['error']) {
                    $this->error($result['info']);
                } else {
                //$ext = array_pop(explode('.', $result['info'][0]['savename']));
                //$img = str_replace('.' . $ext, '_thumb.' . $ext, $result['info'][0]['savename']);
                    $img=$result['info'][0]['savename'];
                    $aa = $model->where(['uid'=>$id,'type'=>1])->select();
                    if($aa){
                        $res1=$model->where(['uid'=>$id,'type'=>1])->setField('img',$img);
                    }else{
                        $sfz1= array('uid'=>$id,'type'=>1, 'img'=>$img);
                        $res1 =$model->add($sfz1);
                    }

                    if (false == $res1){
                        $start->rollback();
                        $this->error(L('operation_failure'));
                    }
                }
            }

            //修改身份证反面
            if (!empty($_FILES['id_card2']['name'])) {
                $result = $this->_upload($_FILES['id_card2'],'id_card');

                if ($result['error']) {
                    $this->error($result['info']);
                } else {
                    $img=$result['info'][0]['savename'];
                    $bb = $model->where(['uid'=>$id,'type'=>2])->select();
                    if($bb) {
                        $res2 = $model->where(['uid' => $id, 'type' => 2])->setField('img', $img);
                    }else{
                        $sfz2= array('uid'=>$id,'type'=>2, 'img'=>$img);
                        $res2 =$model->add($sfz2);
                    }

                    if (false == $res2){
                        $start->rollback();
                        $this->error(L('operation_failure'));
                    }
                }
            }


            if (false !== $mod->save($data)) {
                //添加明细
                if (!empty($mr) && false === M('account')->addAll($mr)){
                    $start->rollback();
                    $this->error(L('operation_failure'));
                }

                $start->commit();
                $this->success(L('operation_success'));
            } else {
                $start->rollback();
                $this->error(L('operation_failure'));
            }
        } else {


            $type = I('type','1', 'intval');
            //身份证
            $idcard_imgs = M('member_idcard')->where(['uid'=>$id])->getField('type,img');
            //省市区
            $place_spid=$info['province_id']."|".$info['city_id']."|".$info['district_id'];


            $this->assign('place_selected_ids',$place_spid);
            $this->assign('idcard_imgs', $idcard_imgs);
            $this->assign('info', $info);
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

    //修改金额
    public function update_money($data,$info)
    {
        $mr = [];
        $now=$_SERVER['REQUEST_TIME'];

        //修改余额
        if (0 < $data['prices'] && $prices_exp = I('prices_exp','','trim')){//余额
            $prices=$data['prices'];
            if($prices_exp == '-' && $prices>$info['prices']){//减金额余额不足
                return ['status'=>1,'msg'=>'余额不足'];
            }
            $prices = ($prices_exp == '+') ? (0+$prices) :(0-$prices);

            $mr[] = [
                'type' 			=> 1,
                'uid'			=> $data['id'],
                'totalprices'	=> $prices,
                'change_desc'	=>'系统调整',
                'add_time'		=> $now
            ];
            $data['prices'] = ['exp','prices'.$prices_exp.$data['prices']];
        }else{
            unset($data['prices']);
        }
        //修改金元宝
        if (0 < $data['gold_acer'] && $gold_acer_exp = I('gold_acer_exp','','trim')){//余额

            $gold_acer=$data['gold_acer'];
            if($gold_acer_exp == '-' && $gold_acer>$info['gold_acer']){//减金额余额不足
                return ['status'=>1,'msg'=>'余额不足'];
            }
            $gold_acer = ($gold_acer_exp == '+') ? (0+$gold_acer) :(0-$gold_acer);
            $mr[] = [
                'type' 			=> 2,
                'uid'			=> $data['id'],
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
            if($gold_fruit_exp == '-' && $gold_fruit>$info['gold_fruit']){//减金额余额不足
                return ['status'=>1,'msg'=>'余额不足'];
            }
            $gold_fruit = ($gold_fruit_exp == '+') ? (0+$gold_fruit) :(0-$gold_fruit);
            $mr[] = [
                'type' 			=> 3,
                'uid'			=> $data['id'],
                'totalprices'	=> $gold_fruit,
                'change_desc'	=>'系统调整',
                'add_time'		=> $now
            ];
            $data['gold_fruit'] = ['exp','gold_fruit'.$gold_fruit_exp.$data['gold_fruit']];
        }else{
            unset($data['gold_fruit']);
        }

        //修改银币
        if (0 < $data['silver_coin'] && $silver_coin_exp = I('silver_coin_exp','','trim')){//余额

            $silver_coin=$data['silver_coin'];
            if($silver_coin_exp == '-' && $silver_coin>$info['silver_coin']){//减金额余额不足
                return ['status'=>1,'msg'=>'余额不足'];
            }
            $silver_coin = ($silver_coin_exp == '+') ? (0+$silver_coin) :(0-$silver_coin);
            $mr[] = [
                'type' 			=> 4,
                'uid'			=> $data['id'],
                'totalprices'	=> $silver_coin,
                'change_desc'	=>'系统发放',
                'add_time'		=> $now
            ];
            $data['silver_coin'] = ['exp','silver_coin'.$silver_coin_exp.$data['silver_coin']];
        }else{
            unset($data['silver_coin']);
        }

        $update_money=['data'=>$data,'mr' =>$mr];
        return $update_money;
    }

    //生成二维码
    public function ewm(){

        $id=I('id');
        (!$id) && $this->ajaxReturn(['status'=>0,'msg'=>'系统繁忙']);

        $list=$this->_mod->find($id);
        $uri = "http://".$_SERVER['HTTP_HOST']."/index.php?m=mobile&c=login&a=register&ewid=".$list['id'];
        $ewm_url=  $this->set_qrcode($uri);
        $res=$this->_mod->where(['id'=>$id])->setField('ewm',$ewm_url);

        if($res){
            del_file($list['ewm'],'ewm/');
            $this->ajaxReturn(['status'=>1,'url'=>$ewm_url]);
        }else{
            $this->ajaxReturn(['status'=>0,'url'=>$ewm_url]);
        }

    }


    public function _before_update($data)
    {
        if($data['password'] == 'd85c61834e9239b7bef468a430bbb3dc'||is_null($data['password'])){
            unset($data['password']);
        }
        if($data['paypassword'] == 'd85c61834e9239b7bef468a430bbb3dc'||is_null($data['paypassword'] )){
            unset($data['paypassword']);
        }
        return $data;
    }

    //会员审核列表
    public function check_index(){
        $map=array();
        $map['type'] = 2;//1普通会员，2实名认证中,3已实名认证，4实名认证失败
        ($time_start = I('time_start', '', 'trim')) && $map['rz_time'][] = array('egt', strtotime($time_start));
        ($time_end = I('time_end', '', 'trim')) && $map['rz_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));
        ($keywords = I('keywords', '', 'trim')) && $map['_string'] = "id in (select id from jrkj_member where mobile like '%".$keywords."%') or realname like '%".$keywords."%'";
        $mod = $this->_mod;
        $search = array(
            'time_start' => $time_start,
            'time_end' => $time_end,
            'keywords' =>$keywords,
        );
        $this->assign('search', $search);
        !empty($mod) && $this->_list($mod, $map);
        $list=$this->get('list');
        if($list){
            $place_ids=array_unique(array_merge (array_column($list,'province_id'),array_column($list,'city_id'),array_column($list,'district_id')));
            $place=M('place')->where(['id'=>['in',$place_ids]])->getField('id,name');
            $this->assign('place', $place);
        }
//var_dump($list);
//        var_dump($place);
//        die;
        $this->display();
    }


    //会员审核  0驳回1通过
    public function act_check(){

        $id = I('id',0,'intval');
        $status = I('status','','intval');//$status1=通过 2=不通过
        $mod = $this->_mod;
        $info = $mod->find($id);

        if (2 != $info['type'] ||!$id ){
            IS_AJAX && $this->ajax_return(0, L('operation_failure'));
            $this->error(L('operation_failure'));
        }

        $type = ($status==1) ? 3 : 4;
        if (false !== $mod->where(['id' => $id])->setField(['type' => $type])){
            IS_AJAX && $this->ajax_return(1, L('operation_success'), '', 'apply');
            $this->success(L('operation_success'));
        } else {
            IS_AJAX && $this->ajax_return(0, L('operation_failure'));
            $this->error(L('operation_failure'));
        }
    }

    public function check_img(){

        $id = I('id',0,'intval');
        $list=M('member_idcard')->where(['uid'=>$id])->getField('type,img');//1=身份证正面 2=身份证反面',
        $this->assign('list', $list);
        if (IS_AJAX) {
            $response = $this->fetch();
            $this->ajax_return(1,'',$response,'add');
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

    //数据导出 开始********************************************
	//导出会员数据
	public function dao(){
		 $fileName ="会员列表";
		 $xlisCell = array('会员id','会员昵称','会员姓名','手机号','邮箱','VIP等级','头像','余额','积分');
		 $data=M('member')->field('id,nickname,realname,mobile,email,vips,avatar,prices,integral')->select();
		 $this->getExcel($fileName,$xlisCell,$data);
	}

	//下载报表 会员下线列表
	 public function export_next(){
 		ob_end_clean();
		$map = $this->next_list_search();
    	//所有下线
    	$list = $this->_mod->field('id,vips,realname,mobile,reg_time,status,relation_id')->where($map)->select();
    	//该推荐人本人的手机
    	$member = $this->_mod->where(['id'=>['in',array_column($list,'relation_id')]])->getField('id,mobile');
    	//所有下线的升级时间
    	/*$sj['_string']=' item_type = 5 and status = 2 and dingdan != 0 and member_id in('.implode(',',array_column($list,'id')).')';
    	$member_recharge = M('MemberRecharge')->where($sj)->getField('member_id,add_time');*/
        $grade = M('GradeRule')->getfield('id,name');
     	$data = array();
        foreach ($list as $k => $v) {
            $data[$k]['xh']= $k+1; //序号
            $data[$k]['id']= $v['id']; //id
            $data[$k]['realname'] = $v['realname']; //会员名
            $data[$k]['mobile'] = $v['mobile']; //手机
//            $data[$k]['relation'] = $member[$v['relation_id']]; //推荐人手机
            $data[$k]['vips'] = $grade[$v['vips']]; //级别
            $data[$k]['reg_time'] = date('Y-m-d H:i' ,$v['reg_time']);//注册日期
//            $data[$k]['sj_time'] = !empty($member_recharge[$v['id']]) ? date('Y-m-d H:i' ,$member_recharge[$v['id']]) : '';//升级日期
            ($v['status']==0)&& $data[$k]['status']='冻结';//状态
            ($v['status']==1)&& $data[$k]['status']='正常';
        }
		
        $headArr = array();
		$headArr[] = '序号';
		$headArr[] = 'id';
		$headArr[] = '会员名';
		$headArr[] = '手机';
		$headArr[] = '级别';
		$headArr[] = '注册日期';
		$headArr[] = '状态';
		$mobile=$this->_mod->where(' id ='.$map['relation_id'])->getField('mobile');
        $filename="会员下线列表".$mobile;
        $this->getExceltjab($filename, $headArr, $data); 
	 }
    //下载报表 商家下线列表
    public function export_merchant(){
        ob_end_clean();
        $map = $this->merchant_list_search();
        //所有下线
        $list = M('merchant')->field('id,uid,title,add_time,address,status,relation_id')->where($map)->select();
        if($list){
            $member = $this->_mod->where(['id'=>['in',array_column($list,'uid')]])->getField('id,mobile');
        }
        $data = array();
        foreach ($list as $k => $v) {
            $data[$k]['xh']= $k+1; //序号
            $data[$k]['id']= $v['id']; //id
            $data[$k]['title'] = $v['title']; //店铺
            $data[$k]['address'] = $v['address']; //地址
            $data[$k]['mobile'] = $member[$v['uid']]; //手机
            $data[$k]['add_time'] = date('Y-m-d H:i' ,$v['add_time']);//注册日期
            ($v['status']==0)&& $data[$k]['status']='未审核';//状态
            ($v['status']==1)&& $data[$k]['status']='驳回';
            ($v['status']==2)&& $data[$k]['status']='通过';
            ($v['status']==3)&& $data[$k]['status']='暂停营业';
        }

        $headArr = array();
        $headArr[] = '序号';
        $headArr[] = 'id';
        $headArr[] = '店铺';
        $headArr[] = '地址';
        $headArr[] = '手机';
        $headArr[] = '注册日期';
        $headArr[] = '状态';
        $mobile = $this->_mod->where(' id ='.$map['relation_id'])->getField('mobile');
        $filename="会员下线列表".$mobile;
        $this->getExceltjab($filename, $headArr, $data);
    }
	 //下载报表 会员列表
     public function export()
	{
		ob_end_clean();
		$map = $this->_search();
//  	$list = $this->_mod->where($map)->relation(true)->select();
    	$list = $this->_mod->where($map)->select();
    	$member = $this->_mod->where(['id'=>['in',array_column($list,'relation_id')]])->getField('id,mobile');
        $grade = M('GradeRule')->getfield('id,name');
         $sj['_string']=' item_type = 5 and status = 2 and dingdan != 0 and member_id in('.implode(',',array_column($list,'id')).')';
//         $member_recharge = M('MemberRecharge')->where($sj)->getField('member_id,add_time');
         $data = array();
        foreach ($list as $k => $v) {
            $data[$k]['xh']= $k+1; //序号
            $data[$k]['id']= $v['id']; //id
            $data[$k]['nicknme'] = $v['nickname']; //会员名
            $data[$k]['realname'] = $v['realname']; //会员名
            $data[$k]['mobile'] = $v['mobile']; //手机
            $data[$k]['vips'] = $grade[$v['vips']]; //级别
            $data[$k]['recommend_nums'] = recommend_nums($v['id']); //推荐人数
            $data[$k]['relation'] = $member[$v['relation_id']]; //推荐人
            $data[$k]['gold_acer_jc'] = $v['gold_acer_jc']; //聚宝盆金元宝
            $data[$k]['prices'] = $v['prices'];//工资
            $data[$k]['gold_acer']=$v['gold_acer'];//金元宝
//            $data[$k]['silver_acer_jc'] = $v['silver_acer_jc']; //聚宝盆银元宝
            $data[$k]['gold_fruit']=$v['gold_fruit'];//金果
//            $data[$k]['silver_acer']=$v['silver_acer'];//银元宝
           $data[$k]['silver_coin']=$v['silver_coin'];//银币
//            $data[$k]['gold_coin']=$v['gold_coin'];//金币
            $data[$k]['reg_time'] = date('Y-m-d H:i' ,$v['reg_time']);//注册日期
//            $sj_time=date('Y-m-d H:i' ,$member_recharge[$v['id']]);//升级日期
//            $data[$k]['sj_time']=$sj_time?$sj_time:'';
            ($v['status']==0)&& $data[$k]['status']='冻结';//状态
            ($v['status']==1)&& $data[$k]['status']='正常';
        	
        }
		
        $headArr = array();
		$headArr[] = '序号';
		$headArr[] = 'id';
		$headArr[] = '会员名';
		$headArr[] = '姓名';
		$headArr[] = '手机';
		$headArr[] = '级别';
		$headArr[] = '推荐人数';
		$headArr[] = '推荐人';
		$headArr[] = '聚宝盆';
		$headArr[] = '工资';
		$headArr[] = '元宝';
		$headArr[] = '金果';
        $headArr[] = '银币';
		$headArr[] = '注册日期';
		$headArr[] = '状态';
        $filename="会员列表";
        $this->getExceltjab($filename, $headArr, $data); 
	
	}

    //升级记录
    public function upgrade(){
        ($time_start = I('time_start', '', 'trim')) && $map['add_time'][] = array('egt', strtotime($time_start));
        ($time_end = I('time_end', '', 'trim')) && $map['add_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));
        if($time_start>$time_end) unset($map['reg_time']);

        ($keywords = I('keywords', '', 'trim')) && $map['_string'] = " uid in (select id from jrkj_member where mobile like '%".$keywords."%' or nickname like '%".$keywords."%') ";
        $map['dingdan'] = array('gt', 0);
        $mod = D('OrderRecharge');
        $search = array(
            'time_start' => $time_start,
            'time_end' => $time_end,
            'keywords' =>$keywords,
        );
        $this->assign('search', $search);
        //$this->list_relation=true;
        !empty($mod) && $this->_list($mod, $map);
       // $list = $this->_get('list');
        $this->display();
    }

    public function _after_list($list)
    {
        if (ACTION_NAME == 'upgrade') {
            if($list){
                $member_ids = array_unique(array_column($list,'uid'));
                $member_ids && $member = M('member')->where(['id'=>['in',$member_ids]])->getField('id,realname,mobile,nickname');
            }
            $vip = [
                '1'=>'普通',
                '2'=>'掌柜',
                '3'=>'银掌柜',
                '4'=>'金掌柜',
                '5'=>'超级掌柜',
            ];
            $zftype = [
              '1' => '微信',
              '2' => '支付宝',
              '3' => '余额',
            ];
            $status = [
              '1' => '待支付',
              '2' => '支付成功',
              '3' => '支付失败',
            ];
             foreach ($list as $k =>$v){
                 $list[$k]['nickname'] = $member[$v['uid']]['nickname'];
                 $list[$k]['mobile'] = $member[$v['uid']]['mobile'];
                 $list[$k]['old_vip'] = $vip[$v['old_vip']];
                 $list[$k]['after_vip'] = $vip[$v['after_vip']];
                 $list[$k]['zftype'] = $zftype[$v['zftype']];
                 $list[$k]['status'] = $status[$v['status']];
             }

        }
        if (ACTION_NAME == 'merchant_list') {
            if($list) {
                $status = [
                    '0'=>'未审核',
                    '1'=>'驳回',
                    '2'=>'通过',
                    '3'=>'暂停营业',
                ];
                $listIds = array_column($list,'uid');
                if($listIds){
                    $member = M('member')->where(['id'=>['in',$listIds]])->getField('id,mobile');
                    foreach($list as $k=>$v){
                        $list[$k]['status'] = $status[$v['status']];
                        $list[$k]['mobile'] = $member[$v['uid']];
                    }
                }
            }}
          return $list;
    }

    //会员升级记录
    public function export_sj()
    {
        ob_end_clean();
        $mod = D('OrderRecharge');
        $map['dingdan'] = array('gt', 0);
        $list = $mod->where($map)->select();
        if($list){
            $member_ids = array_unique(array_column($list,'uid'));
            $member_ids && $member = M('member')->where(['id'=>['in',$member_ids]])->getField('id,realname,mobile,nickname');
        }
        $vip = [
            '1'=>'普通',
            '2'=>'掌柜',
            '3'=>'银掌柜',
            '4'=>'金掌柜',
            '5'=>'超级掌柜',
        ];
        $zftype = [
            '1' => '微信',
            '2' => '支付宝',
            '3' => '余额',
        ];
        $status = [
            '1' => '待支付',
            '2' => '支付成功',
            '3' => '支付失败',
        ];
        $data = array();
        foreach ($list as $k => $v) {
            $data[$k]['realname'] = $member[$v['id']]['realname']; //会员名
            $data[$k]['mobile'] =  $member[$v['uid']]['mobile'];//手机号
            $data[$k]['totalprices'] =$v['totalprices'] ; //升级金额
            $data[$k]['old_vip'] = $vip[$v['old_vip']]; //升级前级别
            $data[$k]['after_vip'] = $vip[$v['after_vip']]; //升级后级别
            $data[$k]['operator'] = $zftype[$v['zftype']];//支付方式
            $data[$k]['add_time'] = date('Y-m-d',$v['add_time']); //升级时间
            $data[$k]['status'] = $status[$v['status']]; //状态
        }

        $headArr = array();
        $headArr[] = '会员名';
        $headArr[] = '会员电话';
        $headArr[] = '升级金额';
        $headArr[] = '升级前级别';
        $headArr[] = '升级后级别';
        $headArr[] = '支付方式';
        $headArr[] = '升级时间';
        $headArr[] = '状态';
        $filename="会员升级记录";
        $this->getExceltjab($filename, $headArr, $data);

    }
	
	


}