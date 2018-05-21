<?php
namespace Mobile\Controller;
//use Think\Controller;
//use Think\View;
class GoldfruitshopController extends HomeController {
    //金果商城
    public function gold_shop()
    {
        $uid = is_login();
        $gold = M("member")->where("id='$uid'")->field("gold_fruit")->find();
        $this->assign("uid",$uid);
        $this->assign("gold",$gold);
        if(IS_AJAX){
            $good = M('Item')->where("is_fruit = 1")->field('id,title,img')->select();
            $goodIds = array_column($good,'id');
            if(!empty($goodIds)){
                $count = M('item_attr')->where(array('item_id'=>['in',$goodIds],'is_fruit'=>1))->count();
                $count = ceil($count/6);
                $Page       = new \Think\Page($count,6);// 实例化分页类
                $item = M('item_attr')->where(['item_id'=>['in',$goodIds],'is_fruit'=>1])
                    ->field("id,item_id,gold_fruit")
                    ->limit($Page->firstRow.','.$Page->listRows)
                    ->select();
                foreach ($item as $k=>$v){
                    foreach ($good as $k1=>$v1)
                        if(false !== array_search($v['item_id'],$v1)){
                            $item[$k]['title'] = $v1['title'];
                            $item[$k]['img'] = $v1['img'];
                        }
                }
            }

            $this->assign("item",$item);
            $list = $this->fetch('list_fetch');
            $this->ajaxReturn(array('list'=>$list,'length'=>$count));//流加载
        }else{
            $this->display();
        }
    }

    //金果商城商品详情
    public function gold_shop_detail()
    {
        $uid = is_login();
        $this->assign("uid",$uid);
            $id = I('id','','trim');//规格id
            $item_id = I('item_id','','trim');//商品id
            //商品轮播图
            $url = M("ItemImg")->where("item_id=$item_id")->field("url")->select();
            $this->assign("url",$url);
            //商品详情信息
            $item_info = M("ItemAttr")->where(['id'=>$id,'is_fruit'=>1])->field('id,gold_fruit,attr_value')->find();
            $item_info['title'] = M("Item")->where("id=$item_id")->getField('title');
            $this->assign("item_info",$item_info);
            $this->assign("item_id",$item_id);
            $this->display();
    }

    //订单提交
    public function y_goldShopOrder(){
        $uid = is_login();
        $id = I('id','','trim');//规格id
        $item_id = I('item_id','','trim');//商品id
        $addr_id = I('addr_id','','trim');//地址id
        $attr_id = I('attr_id','','trim');//规格id
        ($id == null) && $id = $attr_id;
//        cookie('attr_id',$id,600);
//        cookie('item_id',$item_id,600);
        //获取商品信息
        $item_info = M("ItemAttr")->where(['id'=>$id,'is_fruit'=>1])->field('id,gold_fruit,attr_value')->find();
        $item_info['item'] = M("Item")->where("id=$item_id")->field('title,img')->find();
        if($addr_id){
            $has_addr = M('member_address')->where(['id' => $addr_id])->find();
        }else {
            $has_addr = M('member_address')->where(['uid' => $uid, 'is_default' => '1'])->find();
            !$has_addr && $has_addr = M('member_address')->where(['uid' => $uid])->limit(1)->order('id desc')->find();
        }
//        cookie('addr_id',$has_addr['id'],600);
//        dump($has_addr);exit;
        $gold = M("member")->where("id='$uid'")->getfield("gold_fruit");
        $this->assign("item_info",$item_info);
        $this->assign("gold",$gold);
        $this->assign("has_addr",$has_addr);
        $this->assign("id",$id);
        $this->assign("item_id",$item_id);
        $this->assign("addr_id",$has_addr['id']);
        $this->display();
    }

    public function sub_gold_order(){
        if($_POST != null){
            $addr_id = $_POST['addr_id'];
            $attr_id = $_POST['attr_id'];
            $item_id = $_POST['item_id'];
            if(!$addr_id && !$attr_id){
                $this->ajaxReturn(array('status'=>0,'msg'=>'订单超时，请重新下单'));
            }
            if($_POST['now'] > $_POST['have']){
                $this->ajaxReturn(array('status'=>0,'msg'=>'金果不足'));
            }
            $goods = M("ItemAttr")->where(['id'=>$attr_id,'is_fruit'=>1])->find();
            $address = M('member_address')->where(['id'=>$addr_id])->find();
            if(!$address){
                $this->ajaxReturn(array('status'=>0,'msg'=>'请添加地址'));
            }
            $item = M("Item")->where(array('id'=>$item_id))->field("title,img")->find();
            $item_num=$_POST['now']/$goods['gold_fruit'];
            //写入订单
            $data['dingdan'] = create_order_sn();
            $data1['uid'] = $data['uid'] = is_login();
            $data['sh_person'] = $address['shperson'];
            $data['attr_id'] = $attr_id;
            $data['shop_id'] = $item_id;
            $data['sh_mobile'] = $address['mobile'];
            $data['sh_address'] = $address['province'].$address['city'].$address['district'].$address['address'];
            $data['type'] = 2;//1普通商品2金果商城
            $data['status'] = 1;
            $data['zftype'] = 2;//支付方式1金元宝2金果3金元宝+银币
            $data['item_num'] = $item_num;
            $data['fruit_price'] = $_POST['now'];//金果支付的总金额
            $data['order_amount'] = $goods['price']*$item_num;//应付元宝总金额
            $data['total_amount'] = 0;//实际支付元宝总金额


            $data['add_time'] = time();
            $re1 = M('order')->add($data);
            //写入订单明细
            $data1['oid'] = $re1;
            $data1['attr_id'] = $attr_id;
            $data1['item_id'] = $item_id;
            $data1['price'] = $goods['gold_fruit'];//金果单价
            $data1['oldprice'] = $goods['oldprice'];//原价（元宝价格）
            $data1['num'] = $item_num;
            $data1['img'] = $item['img'];
            $data1['title'] = $item['title'];
            $re2 = M('order_list')->add($data1);
            if($re1&&$re2){
                cookie('order_id',$re1,300);
                $this->ajaxReturn(array('status'=>1,'msg'=>'下单完成'));
            }


        }
    }

    //支付金果商城订单
    public function y_pay(){
        $uid = is_login();
        $order_id = I('oid');
        $this->assign("oid",$order_id);

        cookie('order_id',$order_id,300);
        $order_id = cookie('order_id');
        !$order_id &&  $this->ajaxReturn(array('status'=>0,'msg'=>'支付过期'));
        $paypwd = M('member')->where(['id'=>$uid])->getField('paypassword');
        $count = M('order')->where(['id'=>$order_id])->getField('fruit_price');
        $this->assign("count",$count);
        $this->assign("paypwd",$paypwd);
        if($_POST){
            $msg = M('member')->where(['id'=>$uid])->find();
            if($msg['paypassword'] != st_md5($_POST['pas'])){
                $this->ajaxReturn(array('status'=>0,'msg'=>'支付密码错误'));
            }
            if($msg['gold_fruit'] < $_POST['count']){
                $this->ajaxReturn(array('status'=>0,'msg'=>'金果不足'));
            }
            $order_list = M('order_list')->where(['oid'=>$order_id])->find();

//            $data['uid'] = $uid;
//            $data['oid'] = $order_id;
//            $data['totalprices'] = '-'.$_POST['count'];
//            $data['type'] = 3;
//            $data['account_type'] = 6;
//            $data['change_desc'] = '金果商城消费';
//            $data['add_time'] = time();
            $now=time();
            $money=$_POST['count'];
            $save_member['gold_fruit']=['exp','gold_fruit -'.$money];
            $data[] = account_arr(3, $uid,'-'.$money , '金果商城消费', $now,$order_id,7);//金果明细

            $send_coin=$money*30;
            if($send_coin>0){
                $save_member['silver_coin']=['exp','silver_coin +'.$send_coin];
                $data[] = account_arr(4, $uid,$send_coin, '金果商城送银币', $now,$order_id,7);//银币明细
            }

            M()->startTrans();
            //会员表
            $re1 = M('member')->where(['id'=>$uid])->save($save_member);
            //减少库存
            $re2 = M('item_attr')->where(['id'=>$order_list['attr_id']])->setDec('attr_value',$order_list['num']);
            //添加消费记录
            $re3 = M('account')->addAll($data);
            //增加销量
            $re4 = M('item')->where(['id'=>$order_list['item_id']])->setInc('sales',$order_list['num']);
            //修改订单状态
            $re5 = M('order')->where(['id'=>$order_id])->setField('status',2);
            if($re1&&$re2&&$re3&&$re4&&$re5){
                M()->commit();
                $this->ajaxReturn(array('status'=>1,'msg'=>'支付成功','url'=>U('index/index')));
            }else{
                M()->rollback();
                $this->ajaxReturn(array('status'=>0,'msg'=>'支付失败'));
            }
        }
        $this->display();
    }

    public function y_order_Details(){
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

    //商品列表(带流加载)
    public function commodity($item_cate=0,$order='id',$asc='desc'){
        //筛选页
        if(I('search')){
            $tpl='search';
        }else {
            $tpl=''	;
            $map['cate_id']=$item_cate;
            $map['status']=1;
            $count =M('Merchant')->where($map)->count();// 查询满足要求的总记录数
            $Page       = new \Think\Page($count,10);// 实例化分页类
            $products= M('Item')->where($map)->limit($Page->firstRow.','.$Page->listRows)
                ->order($order.' '.$asc)->select();

            foreach ( $products as $key=> $val){
                $products[$key]['img']=attach($val['img'], 'item');
            }

            if(IS_AJAX) $this->ajaxReturn($products);//流加载

            $this->assign('order', $order);
            $this->assign('asc', $asc);
            $this->assign('item_cate', $item_cate);
            $this->assign('lists', $products);
        }

        $this->display($tpl);
    }

    //商品详情
    public function detail($id=0){

        $info= M('item')->where(['id'=>$id])->find();
        M('item')->where(['id'=>$id])->setInc('hits',1);
        //相册
        $img_list = M('item_img')->where(['item_id'=>$id])->select();
        //规格
        $attr=M('item_attr')->where(['item_id'=>$id])->order('ordid asc,id desc')->select();

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
        $size=5;
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


    public function y_goldShopRecord()
    {
        $uid = is_login();
        $list1 = M('order')->where(['uid'=>$uid,'type'=>2])->field('id,add_time,status')->order('id desc')->select();
        $ids = array_column($list1,'id');
        $list2 = M('order_list')->where(array('oid'=>array('in',$ids)))->field('oid as id,num,title,img,oldprice,item_id,attr_id')->order('id desc')->select();
        foreach ($list1 as $k=>$v){
            foreach ($list2 as $k1=>$v1)
                if(false !== array_search($v['id'],$v1)){
                    $list2[$k]['add_time'] = $v['add_time'];
                    $list2[$k]['status'] = $v['status'];
                }
        }
        $this->assign('list',$list2);
        $this->display();
    }


    public function test()
    {
        die;
    }

}