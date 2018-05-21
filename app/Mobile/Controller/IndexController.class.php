<?php
namespace Mobile\Controller;
//use Think\Controller;
//use Think\View;
class IndexController extends HomeController {

    public function _initialize()
    {
        parent::_initialize();
        $this->uid = is_login();
        $this->_item = D('Item');
    }



    public function index()
    {
        $item_modle=M('item');
        $ad_modle=M('ad');

        //首页轮播
        $lux = $ad_modle->where(array('board_id'=>22,'status'=>1))->order('ordid asc,id desc')->limit(3)->select();
        //金果商城入口
        $new_user =$ad_modle->where(array('board_id'=>23,'status'=>1))->order('ordid asc,id desc')->find();
        //右上角消息提醒
        $uid=is_login();$is_read=false;//表示没有未读
        $new_list=M('Article')->where('cate_id=23')->select();
        foreach($new_list as $k=>$v){
            $am=D('ArticleMember')->where(array('aid'=>$v['id'],'uid'=>$uid))->find();
            (!$am)&& $is_read=true;
        }

        //消息推送
        $active_go = M('Article')->where(array('cate_id'=>23,'status'=>1))->order('ordid asc,id desc')->select();

        //发现好货:标签fx
        $fl[0]=$item_modle->where('fx=1 and status=1')->field('id,img')->order('ordid asc,update_time desc')->limit(2)->select();
        //臻会买:标签zhm
        $fl[1]=$item_modle->where('zhm=1 and status=1')->field('id,img')->order('ordid asc,update_time desc')->limit(2)->select();
        //新品首发:时间倒序
        $fl[4]=$item_modle->where(array('status'=>1))->field('id,img')->order('ordid asc,id desc')->limit(2)->select();
        //臻怡家精选:标签jx
        $fl[3]=$item_modle->where('jx=1 and status=1')->field('id,img')->order('ordid asc,update_time desc')->limit(2)->select();
        //秒杀展示：秒杀活动待开放0
        $favour =M('item')->where(array('status'=>1))
            ->limit(6)->field('id,title,price,img,hits')->order('id desc')->select();
        $this->assign('favour',$favour);

        //获取微信分享必要参数
        $jssdk = new \Mobile\Org\Jssdk(C("WX_CONFIG.appid"),C("WX_CONFIG.appsecret"));
        $js = $jssdk->GetSignPackage();
        $this->assign('js',$js);

        $this->assign('lux',$lux);
        $this->assign('new_user',$new_user);
        $this->assign('is_read',$is_read);
        $this->assign('active_go',$active_go);
        $this->assign('fl',$fl);

        $this->display();
    }
    //猜你喜欢
    public function guesslike_list(){
        $item_modle=M('item');

        $count=$item_modle->where(array('status'=>1))->order('ordid asc,id desc')->count();//总条数 255?

        $page=I('page');//当前页
        $pages =   ceil($count/ 8); //上取整    总条数/每页条数
        $list_aj[0] = $pages;//总页数
        $list_aj1=$item_modle->where(array('status'=>1))->order('ordid asc,id desc')->limit($page * 8 , 8)->select(); //每页数据
        foreach($list_aj1 as $kk=>$vv){
            $list_aj1[$kk]['href']=U('index/detail',array("id"=>$vv['id']));
            $list_aj1[$kk]['title']=(strlen($vv['title']) > 15)?mb_substr($vv['title'],0,15,"UTF-8").'..':$vv['title'];
            $list_aj1[$kk]['sales']=$vv['sales']?$vv['sales']:0;
            $list_aj1[$kk]['img']=attach($vv['img'],'item');
        }
        $list_aj[1]=$list_aj1;
        echo  exit(json_encode($list_aj));

    }

     public function index_old(){
		//首页轮播
		$lux = M('ad')->where(array('board_id'=>22,'status'=>1))->order('ordid asc,id desc')->limit(3)->select();
		//新人报道
		$new_user = M('ad')->where(array('board_id'=>23,'status'=>1))->order('ordid asc,id desc')->find();
		//右上角消息提醒
		$uid=is_login();$is_read=false;//表示没有未读
		$new_list=M('Article')->where('cate_id=23')->select();
		foreach($new_list as $k=>$v){
    		$am=D('ArticleMember')->where(array('aid'=>$v['id'],'uid'=>$uid))->find();
    		(!$am)&& $is_read=true;
    	}
		
		//消息推送
		$active_go = M('Article')->where(array('cate_id'=>23,'status'=>1))->order('ordid asc,id desc')->select();
		

		//发现好货:标签fx
		$fl[0]=M('Item')->where('fx=1 and status=1')->order('ordid asc,id desc')->limit(2)->select();
		//臻会买:标签zhm
		$fl[1]=M('Item')->where('zhm=1 and status=1')->order('ordid asc,id desc')->limit(2)->select();
		//臻怡家精选:标签jx
		$fl[3]=M('Item')->where('jx=1 and status=1')->order('ordid asc,id desc')->limit(2)->select();
		//排行榜:销量
		$fl[2]=M('item')->where(array('status'=>1))->limit(2)->order('sales desc')->select();//hits或sales
		//新品首发:add_time
		$fl[4]=M('item')->where(array('status'=>1))->limit(2)->order('ordid asc,add_time desc')->select();
	
//		//上新:
//		$fl[5]=M('item')->where(array('status'=>1,'tj'=>1))->order('id desc')->find();
//		//闪购
//		$fl[6]=M('item')->where(array('status'=>1,'rm'=>1))->order('id desc')->find();
		
		
		//爱生活(一级分类》二级分类》所属分类的商品):生活用品550、食品百货562
		 $all_ash=D('ItemCate')->field('id,name')->where('pid=562 and status=1')->order('ordid asc,id desc')->limit(6)->select();
         foreach($all_ash as $k=>$v){
             $id_arr=M('item_cate')->where('FIND_IN_SET('.$all_ash[$k]['id'].',REPLACE (spid,"|",","))>0')->getField('id',true);
             if(!$id_arr){
                 $item_pz=M('item')->where(array('cate_id'=>$all_ash[$k]['id']))->limit(2)->select();
             }else{
                 $id_arr[]=$all_ash[$k]['id'];
                 $item_pz=M('item')->where(array('cate_id'=>array('in',$id_arr)))
                     ->field('id,img')->order('ordid asc,id desc')->limit(2)->select();
             }
             $all_ash[$k]['xx']=$item_pz;
             if(!$item_pz) unset($all_ash[$k]);
         }
//		$all_ash=D('ItemCate')->where('pid=550 and status=1')->order('ordid asc,id desc')->limit(6)->select();
//		foreach($all_ash as $k=>$v){
//			$item_pz=M('item')->where(array('cate_id'=>$v['id'],'status'=>1))->field('id,img')->order('ordid asc,id desc')->limit(2)->select();
//			$all_ash[$k]['xx']=$item_pz;
//		}
	
		//购品质(品牌) 
		$allpp=D('ItemBrand')->where(array('status'=>1,'pid'=>0))->order('ordid asc,id desc')->limit(6)->select();
		foreach($allpp as $k=>$v){
			$item_pz=M('item')->where(array('brand_id'=>$v['id'],'status'=>1))->field('id,img')->order('ordid asc,id desc')->limit(2)->select();
			$allpp[$k]['xx']=$item_pz;
		}

		//猜你喜欢:点击率
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
		$this->assign('is_read',$is_read);
		$this->assign('active_go',$active_go);
		$this->assign('all_ash',$all_ash);
		$this->assign('allpp',$allpp);
		$this->assign('fl',$fl);
		$this->display();
    }
    
    //首页点击》推送消息列表
    public function new_list(){
    	$new_list=M('Article')->where('cate_id=23')->select();
    	$uid=is_login();
    	foreach($new_list as $k=>$v){
    		$am=D('ArticleMember')->where(array('aid'=>$v['id'],'uid'=>$uid))->find();
    		($am)&& $new_list[$k]['is_read']=1;
    	}
		$this->assign('new_list',$new_list);
    	$this->display();
    }
    //推送消息详情
    public function new_details(){
    	$data['aid']=$aid=I('id');
    	$data['uid']=$uid=is_login();
    	$add_ago=D('ArticleMember')->where(array('aid'=>$aid,'uid'=>$uid))->find();
    	(!$add_ago)&&($aid&&$uid)&&D('ArticleMember')->add($data);//标记已读未读
    	$new_details=M('Article')->where(array('id'=>$aid))->find();
		$this->assign('new_details',$new_details);
    	$this->display();
    }

    //金果商城
    public function gold_shop()
    {
//        $uid = $this->uid;
        $uid = is_login();
        $gold = M("member")->where("id='$uid'")->field("gold_fruit")->find();
        $this->assign("gold",$gold);
        $item = M("ItemAttr")->where("is_fruit=1")->field("item_id,gold_fruit")->select();
        foreach($item as $k => $v){
            $item[$k]['cate'] = M("Item")->where(array('id'=>$v['item_id']))->field("id,title,img")->select();
//            $jg_scj = C('pin_jg_scj');
//            $item['price'] = $item['price']*(1/$jg_scj);
        }
        $this->assign("item",$item);

        $this->display();
    }

    //金果商城商品详情
    public function gold_shop_detail()
    {
        $id = $_GET['id'];
        //商品轮播图
        $url = M("ItemImg")->where("item_id=$id")->field("url")->select();
//        dump($url);die;
        $this->assign("url",$url);
        //商品详情信息
        $item_info = M("Item")->where("id=$id")->find();
//        dump($item_info);die;
        $jg_scj = C('pin_jg_scj');
        $item_info['price'] = $item_info['price']*(1/$jg_scj);
        $this->assign("item_info",$item_info);
        $this->display();
    }
    
    //正在开发中
    public function exploit(){
    	$this->display();
    }

    //商品开始***********************************************
	//商品分类
    public function cate($pid=0){
		 //一级
		 $pcates= M('ItemCate')->where('pid=0 and status=1')->select();
		 if($pid) $map['pid']=$pid;
		 else {
		 $map['is_home']=1;
		  $map['pid'] = 0 ;
		  $pid = I('pid',$pcates[0]['id'],'intval');
		  $map['pid'] = $pid;
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

	 //商品列表(带流加载)
	 public function commodity($order='id',$asc='desc')
     {
         //筛选页
         $list['title'] = I('search');
         if ($list['title']) {
             $map['title'] = array('like', '%' . $list['title'] . '%');
             $brand_id = M('item_brand')->where(array('name'=>$list['title']))->field('id')->select();
             $brand = array_column($brand_id,'id');
             if(!empty($brand)){
                 $map['brand_id'] = array('in',$brand);
             }
             $map['_logic'] = 'OR';
         }
         $param =   I();
         $item_cate=$param['item_cate'];
         if($item_cate) $map['cate_id'] = $item_cate;
         $order = $param['order']?$param['order']:'id';
         $asc = $param['asc']?$param['asc']:'desc';
         $title = $param['title']?$param['title']:'';
         $map['status']=1;
         if($title) $map['title'] = array('like','%'.$title.'%');
         $count = M('Item')->where($map)->count();// 查询满足要求的总记录数
         if (IS_AJAX) {
             $page = I('page');//当前页
             $pages = ceil($count / 4); //上取整    总条数/每页条数
             $products = M('Item')->where($map)->field('id,title,img,cate_id,price,oldprice,sales')
                 ->limit(($page-1)*4, 4)->order($order . ' ' . $asc)->select();
             if ($products) {
                 foreach ($products as $key => $val) {
                     $products[$key]['img'] = attach($val['img'], 'item');
                 }
             }
             $list[0] = $pages;
             $list[1] = $products;
             $list['title'] = $list['title'];
//            echo M()->_sql();die;
             $this->ajaxReturn($list);
         } else {
             $this->assign('item_cate',$item_cate);
             $this->assign('order',$order);
             $this->assign('asc',$asc);
             $this->assign('list',$list);
             $this->assign('is_kk', $count ? 0 : 1);//数据为空 1是
             $this->display();
         }
     }
    //商品搜索
    public function search()
    {
         $searchKeywords = M('search_keywords')->where(array('status'=>1))->order('ordid desc,id desc')->limit(6)->select();
         $this->assign('searchKeywords',$searchKeywords);
         $this->display();
    }

	 //商品详情
	  public function detail($id=0){

        $info= M('item')->where(['id'=>$id])->find();
        $info['count']= M('item')->count();
        M('item')->where(['id'=>$id])->setInc('hits',1);
        //相册
        $img_list = M('item_img')->where(['item_id'=>$id])->select();
        //规格
        $attr=M('item_attr')->where(['item_id'=>$id])->order('ordid asc,id desc')->select();
          $member_id = $this->uid;
          $User = M('gwc');
          $nums = $User->where(array('uid'=>$member_id))->sum('num');
          $this->assign('nums',$nums);
        $this->assign('img_list', $img_list);
		$this->assign('info', $info);
		$this->assign('attr', $attr);
		$this->display();
	  }


    //商品列表(带流加载)0
    public function ambitus($cate=0){
		$cates= M('MemberCate')->where('status=1')->select();
		$this->assign('cates', $cates);
		 //筛选页
		if(I('search')){
			$tpl='screen'	;
		}else {
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
		   (IS_AJAX)&&$this->ajaxReturn($lists);
		}
		


     
 	  	 $this->display($tpl);
	  }

    //商品结束***********************************************


    //商户定位
	    public function location(){ 
			$this->display();
		}
		
	   //定位头部(商户列表)
	  public function ambitus_head($cate=0){ 
		$cates= M('MerchantCate')->where('status=1')->select();

          $this->assign('cates', $cates);
		 //筛选页
		if(I('search')){
		    $tpl='screen'	;
		}else {
            $tpl=''	;
            $this->assign('cate', $cate);
            $this->assign('title', I('title'));
            $this->assign('address', I('address'));
		}
		//定位中心点 商铺距离排序 start
		$location=I('info');
		cookie('location',$location);//当前位置的经纬度
		$this->assign('location',$location);
		$this->display();
	  }
	  
	    //ajax搜索
	    public function ajax_ambitus_content($cate=0){ 
            $location=cookie('location');//当前位置的经纬度
            if($cate){
                $map['cate_id']=$cate;
            }else{
                $map['tj']=1;
            }
             $map['status']=2;
           I('title') && $map['title'] = array('like','%'.I('title').'%');
           I('address') && $map['address'] = array('like','%'.I('address').'%');
           $count =M('Merchant')->where($map)->count();// 查询满足要求的总记
           $p=I('p','','intval')?I('p','','intval'):1;
           $size=5;
           $lists= M('Merchant')->where($map)->limit(($p-1)*$size,$size)->select();
            foreach($lists as $k=>$v){
                $aa=str_replace("1",'金元宝',$v['zftype']);
                $bb=str_replace("2",'银元宝',$aa);
                $lists[$k]['zftype']=str_replace("3",'金果',$bb);
            }
            $this->assign('lists', $lists);
                    foreach ( $lists as $key=> $val){
                $lists[$key]['img']=attach($val['img'], 'avatar');
                $lists[$key]['desc']=$val['desc']? $val['desc'] :'暂无介绍';
            }
            foreach($lists as $k=>$v){
                $lng1=$v['longitude'];
                $lat1=$v['latitude'];
                $zftype = explode(',',$v['zftype']);
                $lists[$k]['distance']=round(getdistance($lng1,$lat1,$location['lng'],$location['lat'])/1000,2);
            }
            $lists=array_sort($lists,'distance','asc');
            $array[0]=$lists;
            $array[1]=ceil($count/$size);
            if(IS_AJAX) $this->ajaxReturn($array);
		}
		
		
	 	 //商户详情
	    public function shop_details($id){ 
			$info= M('Merchant')->where('id='.$id)->find();
			
			
			$info['sh_img'] = M('merchant_img')->where('merchant_id='.$id)->field('img')->order('ordid asc,id desc')->select();

			$info['cate']= M('MerchantCate')->where('id='.$info['cate_id'])->find();
			
			$info['xyz']=Convert_GCJ02_To_BD09($info['latitude'],$info['longitude']);//纬度、经度
			
			$aa=str_replace("1",'金元宝',$info['zftype']);
			$bb=str_replace("2",'银元宝',$aa);
			$info['zftype']=str_replace("3",'金果',$bb);
			
			$this->assign('info', $info);
			$tpl=I('map')? 'shop_map':'';
			
			$this->display($tpl);
		}


    //注册协议、关于我们
    public function about()
	{
	    $data=I();
        $type=$data['type'];//1注册协议2关于我们3使用帮助
        switch($type) {
           case 1:$find=180;break;
           case 2:$find=178;break;
           case 3:$find=181;break;
           default:;
        }
        $list=M('article')->where('status =1')->find($find);
        $this->assign('list', $list);
        $this->display();
	}

}