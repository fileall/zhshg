<?php
namespace Mobile\Controller;
//use Think\Controller;
//use Think\View;
class IndexController extends HomeController {
    public function index_old(){
		//首页轮播
		$lux = M('ad')->where(array('board_id'=>22,'status'=>1))->order('ordid asc,id desc')->limit(3)->select();
		//猜你喜欢
		$favour =M('item')->where(array('status'=>1))
		->limit(10)->field('id,title,price,img,hits')->order('hits desc')->select();
		$this->assign('favour',$favour);
		//dump($favour);
        //获取微信分享必要参数
        $jssdk = new \Mobile\Org\Jssdk(C("WX_CONFIG.appid"),C("WX_CONFIG.appsecret"));
        $js = $jssdk->GetSignPackage();
		 $this->assign('js',$js);
		$this->assign('lux',$lux);
		$this->display();
    }
    
    public function index(){
		//首页轮播
		$lux = M('ad')->where(array('board_id'=>22,'status'=>1))->order('ordid asc,id desc')->limit(3)->select();
		//新人报道
		$new_user = M('ad')->where(array('board_id'=>23,'status'=>1))->order('ordid asc,id desc')->find();
		
		
		//发现好货:男装(一级分类》二级分类显示图片)
		$fl[0]=M('ItemCate')->where('pid=502 and status=1')->order('ordid asc,id desc')->limit(2)->select();
		//臻会买:食品(一级分类》二级分类显示图片)
		$fl[1]=M('ItemCate')->where('pid=498 and status=1')->order('ordid asc,id desc')->limit(2)->select();
		//排行榜:点击率
		$fl[2]=M('item')->where(array('status'=>1))->limit(2)->order('hits desc')->select();
		//臻怡家精选:手机(一级分类》二级分类显示图片)
		$fl[3]=M('ItemCate')->where('pid=505 and status=1')->order('ordid asc,id desc')->limit(2)->select();
		//新品首发:
		$fl[4]=M('item')->where(array('status'=>1))->limit(2)->order('id desc')->select();
		//上新:
		$fl[5]=M('item')->where(array('status'=>1,'tj'=>1))->order('id desc')->find();
		//闪购
		$fl[6]=M('item')->where(array('status'=>1,'rm'=>1))->order('id desc')->find();
		
		
		//爱生活(一级分类》二级分类显示图片)
		$all_ash=D('ItemCate')->where('pid=0 and status=1')->order('ordid asc,id desc')->limit(8)->select();
		foreach($all_ash as $k=>$v){
			$all_ash[$k]['xx']=D('ItemCate')->where(array('pid'=>$v['id']))->limit(2)->select();
		}
		
		//购品质(品牌)
		$allpp=D('ItemBrand')->where(array('status'=>1,'pid'=>0))->order('ordid asc,id desc')->limit(6)->select();
		foreach($allpp as $k=>$v){
			$item_pz=M('item')->where(array('brand_id'=>$v['id'],'status'=>1))->field('id,img')->order('ordid asc,id desc')->limit(2)->select();
			$allpp[$k]['xx']=$item_pz;
		}

		//猜你喜欢
		$favour =M('item')->where(array('status'=>1))
		->limit(4)->field('id,title,price,img,hits')->order('hits desc')->select();
		$this->assign('favour',$favour);
		//dump($favour);
		
        //获取微信分享必要参数
        $jssdk = new \Mobile\Org\Jssdk(C("WX_CONFIG.appid"),C("WX_CONFIG.appsecret"));
        $js = $jssdk->GetSignPackage();
		$this->assign('js',$js);
		
		$this->assign('lux',$lux);
		$this->assign('new_user',$new_user);
		$this->assign('all_ash',$all_ash);
		$this->assign('allpp',$allpp);
		$this->assign('fl',$fl);
		$this->display();
    }
    
    
    
     public function index1(){
		//首页轮播
		$lux = M('ad')->where(array('board_id'=>22,'status'=>1))->order('ordid asc,id desc')->limit(3)->select();
		//新人报道
		$new_user = M('ad')->where(array('board_id'=>23,'status'=>1))->order('ordid asc,id desc')->find();
		
		
		//发现好货:男装(一级分类》二级分类显示图片)
		$fl[0]=M('ItemCate')->where('pid=502 and status=1')->order('ordid asc,id desc')->limit(2)->select();
		//臻会买:食品(一级分类》二级分类显示图片)
		$fl[1]=M('ItemCate')->where('pid=498 and status=1')->order('ordid asc,id desc')->limit(2)->select();
		//臻怡家精选:手机(一级分类》二级分类显示图片)
		$fl[3]=M('ItemCate')->where('pid=505 and status=1')->order('ordid asc,id desc')->limit(2)->select();
		
		//排行榜:点击率
		$fl[2]=M('item')->where(array('status'=>1))->limit(2)->order('hits desc')->select();
		//新品首发:
		$fl[4]=M('item')->where(array('status'=>1))->limit(2)->order('id desc')->select();
		//上新:
		$fl[5]=M('item')->where(array('status'=>1,'tj'=>1))->order('id desc')->find();
		//闪购
		$fl[6]=M('item')->where(array('status'=>1,'rm'=>1))->order('id desc')->find();
		
		
		//爱生活(一级分类》二级分类显示图片)
		$all_ash=D('ItemCate')->where('pid=0 and status=1')->order('ordid asc,id desc')->limit(8)->select();
		foreach($all_ash as $k=>$v){
			$all_ash[$k]['xx']=D('ItemCate')->where(array('pid'=>$v['id']))->limit(2)->select();
		}
		
		//购品质(品牌)
		$allpp=D('ItemBrand')->where(array('status'=>1,'pid'=>0))->order('ordid asc,id desc')->limit(6)->select();
		foreach($allpp as $k=>$v){
			$item_pz=M('item')->where(array('brand_id'=>$v['id'],'status'=>1))->field('id,img')->order('ordid asc,id desc')->limit(2)->select();
			$allpp[$k]['xx']=$item_pz;
		}

		//猜你喜欢
		$favour =M('item')->where(array('status'=>1))
		->limit(4)->field('id,title,price,img,hits')->order('hits desc')->select();
		$this->assign('favour',$favour);
		//dump($favour);
		
        //获取微信分享必要参数
        $jssdk = new \Mobile\Org\Jssdk(C("WX_CONFIG.appid"),C("WX_CONFIG.appsecret"));
        $js = $jssdk->GetSignPackage();
		$this->assign('js',$js);
		
		$this->assign('lux',$lux);
		$this->assign('new_user',$new_user);
		$this->assign('all_ash',$all_ash);
		$this->assign('allpp',$allpp);
		$this->assign('fl',$fl);
		$this->display();
    }
    
    
    //正在开发中
    public function exploit(){
    	$this->display();
    }
    
	//商品分类
	 public function cate($pid=0){
		 //一级
		 $pcates= M('ItemCate')->where('pid=0 and status=1')->select();
		 if($pid) $map['pid']=$pid;
		 else {
		 $map['is_home']=1;
		  $map['pid']=0;
		 }
		 $map['status']=1;
		 
		 //二级
		 $cates= M('ItemCate')->where($map)->select();
         foreach ( $cates as $key=> $val){
			 $map['pid']=$val['id'];
			$cates[$key]['son']=M('ItemCate')->where($map)->select();
		}
	$this->assign('pid', $pid);
		   $this->assign('pcates', $pcates);
		   $this->assign('cates', $cates);
		  $this->display();
	 }
	 //商品列表
	 public function commodity($item_cate=0,$order='id',$asc='desc'){
		 		 //筛选页
		if(I('search')){
		$tpl='search'	;
		}
		else {
		$tpl=''	;
	   $map['cate_id']=$item_cate;
	   $map['status']=1;
       $count =M('Merchant')->where($map)->count();// 查询满足要求的总记录数
       $Page       = new \Think\Page($count,10);// 实例化分页类
		$products= M('Item')->where($map)->limit($Page->firstRow.','.$Page->listRows)->order($order.' '.$asc)->select();
		
		foreach ( $products as $key=> $val){
			$products[$key]['img']=attach($val['img'], 'item');
		}
		
		if(IS_AJAX) $this->ajaxReturn($products);

		$this->assign('order', $order);
		$this->assign('asc', $asc);
		$this->assign('item_cate', $item_cate);
		$this->assign('lists', $products);
		}
		$this->display($tpl);
	 }
	 //商品详情
	  public function detail($id=0){
		$info= M('Item')->where('id='.$id)->find();
		//更新点击率
		M('Item')->where(array('id'=>$id))->setInc('hits',1);
		        //相册
        $img_list = M('ItemImg')->where(array('item_id'=>$id))->select();
        $this->assign('img_list', $img_list);
		$this->assign('info', $info);
		$this->display();
	  }
	  //商户列表
	  public function ambitus($cate=0){
		$cates= M('MemberCate')->where('status=1')->select();
		$this->assign('cates', $cates);
		 //筛选页
		if(I('search')){
			$tpl='screen'	;
		}
		else {
			$tpl=''	;
		  	$this->assign('cate', $cate);
			$this->assign('title', I('title'));
			$this->assign('address', I('address'));
		  	if($cate) $map['cate_id']=$cate;
			else $map['tj']=1;
			 $map['status']=1;
      		I('title') && $map['title'] = array('like','%'.I('title').'%');	
      		I('address') && $map['address'] = array('like','%'.I('address').'%');			 
      		$count =M('Merchant')->where($map)->count();// 查询满足要求的总记录数
      		$Page       = new \Think\Page($count,3);// 实例化分页类
			$lists= M('Merchant')->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
			$this->assign('lists', $lists);
			foreach ( $lists as $key=> $val){
				$lists[$key]['img']=attach($val['img'], 'merchant');
	            $lists[$key]['desc']=$val['desc']? $val['desc'] :'暂无介绍';
			}
		//dump($lists);exit;
		   if(IS_AJAX){ 
		   //dump($_GET);
		   $this->ajaxReturn($lists);
		   }
		}
		


     
 	  	 $this->display($tpl);
	  }
	  
	   //商户定位
	    public function location(){ 
			$this->display();
		}
		
	   //定位头部(商户列表)
	  public function ambitus_head($cate=0){ 
		$cates= M('MemberCate')->where('status=1')->select();
		$this->assign('cates', $cates);
		 //筛选页
		if(I('search')){
		$tpl='screen'	;
		}
		else {
		$tpl=''	;
	  	$this->assign('cate', $cate);
		$this->assign('title', I('title'));
		$this->assign('address', I('address'));
		}
		//定位中心点 商铺距离排序 start
		$location=I('info');//dump($location);
		cookie('location',$location);//保存信息 便于后面用
		$this->assign('location',$location);
 	  	 $this->display();
	  }
	  
	  //ajax搜索
	    public function ajax_ambitus_content($cate=0){ 
		$location=cookie('location');
			if($cate) $map['cate_id']=$cate;
		else $map['tj']=1;
		 $map['status']=2;
      I('title') && $map['title'] = array('like','%'.I('title').'%');	
      I('address') && $map['address'] = array('like','%'.I('address').'%');			 
       $count =M('Merchant')->where($map)->count();// 查询满足要求的总记
	   $p=I('p','','intval')?I('p','','intval'):1;
	   $size=3;
		$lists= M('Merchant')->where($map)->limit(($p-1)*$size,$size)
		->select();
		foreach($lists as $k=>$v){
			$aa=str_replace("1",'金元宝',$v['zftype']);
			$bb=str_replace("2",'银元宝',$aa);
			$lists[$k]['zftype']=str_replace("3",'金果',$bb);
		}
		$this->assign('lists', $lists);
				foreach ( $lists as $key=> $val){
			$lists[$key]['img']=attach($val['img'], 'merchant');
            $lists[$key]['desc']=$val['desc']? $val['desc'] :'暂无介绍';
		}
		foreach($lists as $k=>$v){
			$dis[$k]=explode(',',$v['long_lat']);
			$zftype = explode(',',$v['zftype']); 
			$lists[$k]['distance']=round(getdistance($dis[$k][1],$dis[$k][0],$location['lng'],$location['lat'])/1000,2);
		}
		$lists=array_sort($lists,'distance','asc');
		$array[0]=$lists;
		$array[1]=ceil($count/$size);
		if(IS_AJAX){ 
		   //dump($_GET);
		   $this->ajaxReturn($array);
		   }
		}
		
		
	 	 //商户详情
	    public function shop_details($id){ 
			$info= M('Merchant')->where('id='.$id)->find();
			
			
			$info['sh_img'] = M('ShImg')->where('withdraw_id='.$id)->field('img')->order('add_time desc')->select(); 

			$info['cate']= M('MemberCate')->where('id='.$info['cate_id'])->find();
			
			$xyz=explode(',',$info['long_lat']);
			$info['xyz']=Convert_GCJ02_To_BD09($xyz[0],$xyz[1]);
			
			$aa=str_replace("1",'金元宝',$info['zftype']);
			$bb=str_replace("2",'银元宝',$aa);
			$info['zftype']=str_replace("3",'金果',$bb);
			
			$this->assign('info', $info);
			$tpl=I('map')? 'shop_map':'';
			
			$this->display($tpl);
		}
		
	
	
	public function test()
	{
		$aa =array('1','22','33');
		$bb=implode(',',$aa);
		$cc="i2ioafwB7g_mZUqh3stDQ1nBNDDDO3_YV2KlRs189U5VJQXvu-d7HB8JyYhe8cXO.jpg,VPVk54drwgQANe_Szn6zu2dQCXg4OKmYTTxZvfgWedmEq4Ng2fbBmVpAbNdldDFg.jpg";
		var_dump($cc);
		
		
		
		 die;
		$array = array('17102367507d1185402918', '17102346fa490771837915', '171024fab9da5247092096', '1710244c74165178383858',
			'17102328abc13653672643', '171023d8b97f3017920048', '1710234813d22861735723', '1710234897916946372604', 
			'171023767be72005600596', '171023a519f41610244219', '171023efcced1392035319', '171023d1761a1365872480','171024bb18192533887516'
		);    
		$member_id_arr = array();
		$member_recharge_model = D('MemberRecharge');
		foreach ($array as $k => $v) {
			$recharge = $member_recharge_model->where(array('dingdan'=>substr($v,0,strlen($v)-1), 'status'=>2, 'member_id'=>array('in', '251')))->find();
			dump($recharge);
			dump(date("Y-m-d H:i:s", $recharge['pay_time'])); 
		}
		die;
	}
	 
}