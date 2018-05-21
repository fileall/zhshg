<?php
namespace Mobile\Controller;
/**
 * 商品、购物车
 * Class ItemController
 * @package Mobile\Controller
 *
 */
class ItemController extends HomeController{
	
	public function _initialize() {
		parent::_initialize();
        $this->uid=is_login();
        if(!$this->uid){
            $this->redirect('Login/enter');
        }
        $this->_member=D('Member');
        $this->_item=D('Item');
        $this->_item_attr=D('ItemAttr');
        $this->_gwc=D('Gwc');

    }


    //购物车 开始*******************************************************
    //购物车列表
    public function gwc()
    {
        $list = $this->_gwc->where(['uid'=>$this->uid])->select();
        if($list){
            $item_ids=array_column($list,'item_id');
            $attr_ids=array_column($list,'attr_id');

            $item=$this->_item->where(['id'=>['in',$item_ids]])->getField('id,img,title');
            $item_attr=$this->_item_attr->where(['id'=>['in',$attr_ids]])->getField('id,attr_name,attr_value,price,oldprice');

            foreach($list as $kk=>$vv){
                unset($item[$vv['item_id']]['id']);
                unset($item_attr[$vv['attr_id']]['id']);
                $list[$kk]=array_merge($list[$kk],$item[$vv['item_id']],$item_attr[$vv['attr_id']]);
            }
        }else{
            $list=[];
        }


        $this->assign('list',$list);
        $this->display();
    }

     //添加购物车
    public function add_gwc()
    {
        $data=$this->_gwc->create();
        $data['uid']=$this->uid;
        (!$data['num'] || $data['num']<0) && $this->ajaxReturn(['status'=>0]);

        $is_exist=$this->_gwc->where(array('attr_id'=>$data['attr_id'],'uid'=>$this->uid))->getField('id');
        if($is_exist){//存在加
            $res=$this->_gwc->where(['id'=>$is_exist])->setInc('num',$data['num']);
        }else{//新增
            $res=$this->_gwc->add($data);
        }

        $this->ajaxReturn(['status'=>$res?1:0,'url'=>U('item/gwc')]);

    }

    //购物车商品删除
    public function gwc_del(){
        $del_ids=I('id');
        !$del_ids[0]&&$this->ajaxReturn(['status'=>0,'msg'=>'删除失败']);
        $uid=$this->uid;
        $db=M('gwc')->where(array('id'=>array('in',$del_ids),'uid'=>$uid))->delete();
        if($db)
            $re=[ 'status'=>1,'msg'=>'删除成功','del_ids'=>$del_ids];
        else
            $re=[ 'status'=>0,'msg'=>'删除失败','del_ids'=>$del_ids];

        $this->ajaxReturn($re);

    }

    //购物车加*****
    public function  gwc_plus(){

	    $data=I();
        $gwc_mod=$this->_gwc;
        //购物车id
        (!$gwc_id=$data['id'])&&$this->ajaxReturn(['status'=>0,'msg'=>'请选择商品']);

        $gwc=$gwc_mod->where(['id'=>$gwc_id])->find();
        $item_attr=$this->_item_attr->where(array('id'=>$gwc['attr_id']))->find();

        if($gwc['num']+1>$item_attr['attr_value']){//超过库存
            $res=$gwc_mod->where(array('id'=>$gwc_id))->setField('num',$item_attr['attr_value']);
            $msg=['status'=>-1,'msg'=>'超过库存','max_num'=>$item_attr['attr_value']];
        }else{//没超过=>加购物车数量
            $res=$gwc_mod->where(array('id'=>$gwc_id))->setInc('num');
            $msg=$res?['status'=>1,'msg'=>'操作成功']:['status'=>0,'msg'=>'操作失败'];
        }

        $this->ajaxReturn($msg);

    }


    //购物车减*****
    public function  gwc_reduce(){

        $data=I();
        $gwc_mod=$this->_gwc;
        (!$gwc_id=$data['id'])&&$this->ajaxReturn(['status'=>0,'msg'=>'请选择商品']);
        $gwc=$gwc_mod->where(['id'=>$gwc_id])->find();
        $item_attr=$this->_item_attr->where(array('id'=>$gwc['attr_id']))->find();
        //超过库存
        if($gwc['num']-1>$item_attr['attr_value']) {//再减1超过库存=>恢复默认值
            $res=$gwc_mod->where(array('id'=>$gwc_id))->setField('num',$item_attr['attr_value']);
            $msg=['status'=>-1,'msg'=>'超过库存','max_num'=>$item_attr['attr_value']];
            $this->ajaxReturn($msg);
        }
        //购物车
        if($gwc['num']-1==0){
            $this->ajaxReturn(['status'=>0,'msg'=>'请选择至少选择一件']);
        }else{
            $res=$gwc_mod->where(array('id'=>$gwc_id))->setDec('num');
            $msg=$res?['status'=>1,'msg'=>'操作成功']:['status'=>0,'msg'=>'操作失败'];
        }
        $this->ajaxReturn($msg);

    }

    //订单结算前页面(购物车结算&&立即购买)
    public function gwc_settlement(){
        $uid=$this->uid;
        $param=I();
        $aaa=json_decode(cookie('cart_ids'),true);
//        if(!$param && empty($aaa[0])){//I()没有值 && cookie没有值 说明是订单提交过
//            IS_AJAX&&$this->ajaxReturn(['status'=>0,'msg'=>'系统繁忙...']);
//            redirect(U('index/index'), 1, '系统繁忙...');
//        }
        if(!$param&&(!empty($aaa[0])||count($aaa)>0)){//I()没有值 && cookie有值 说明是跳转地址后回来的
            $param=$aaa;
        }

        $cart_ids=$param['cart_ids'];
        if($cart_ids){//购物车
            $ret=gwc_item(['uid'=>$uid,'id'=>['in',$cart_ids]]);
            if(!$ret){
                IS_AJAX&&$this->ajaxReturn(['status'=>0,'msg'=>'您访问的网页已过期...']);
                redirect(U('index/index'), 1, '您访问的网页已过期...');
            }

            $msg=$ret['msg'];
            $list=$ret['list'];
            $cart_ids=json_encode($param);
            cookie('cart_ids',$cart_ids);//购物车id字符串
        }else{//立即购买
            $item_id=$param['item_id'];
            $attr_id=$param['attr_id'];
            $num=$param['num'];
            //商品信息
            $item=$this->_item->where(['id'=>$item_id])->getField('id,img,title');
            $item_attr= $this->_item_attr->where(['id'=>$attr_id])->getField('id,item_id,attr_name,attr_value,price,oldprice,acer,coin');
//            if($num>$item_attr['attr_value']){
//                $this->ajaxReturn(['status'=>0,'msg'=>'库存不足']);
//            }

            //unset($item[$item_id]['id']);
            unset($item_attr[$attr_id]['id']);
            $list[0]=array_merge($item[$item_id],$item_attr[$attr_id]);
            $list[0]['num']=$num;
            $money=$num*$item_attr[$attr_id]['price'];
            $msg['money']=$money;
            $msg['count']=1;
            $param=json_encode($param);
            cookie('cart_ids',$param);//立即购买字符串
        }
//        cookie('is_frist',1);

        $address=M('member_address')->where(['uid'=>$uid,'status'=>1])->order('is_default desc,id desc')->find();
        $this->assign('address',$address);
        $this->assign('msg',$msg);
        $this->assign('list',$list);
        cookie('location',null);
        $this->display();



    }
    //立即购买库存验证
    public function  check_value(){
        $param=I();
        $attr_value=$this->_item_attr->where(['id'=>$param['attr_id']])->getField('attr_value');
        $res=($attr_value>$param['num'])?['status'=>1,'msg'=>'操作成功']:['status'=>0,'msg'=>'库存不足'];
        $this->ajaxReturn($res);
    }


        //立即购买结算前页面0
    public function  nowbuy_settlement(){
        $uid=$this->uid;
        $param=I();
        $aaa=json_decode(cookie('cart_ids'),true);
//        if(!$param && empty($aaa[0])){//I()没有值 && cookie没有值 说明是订单提交过
//            IS_AJAX&&$this->ajaxReturn(['status'=>0,'msg'=>'系统繁忙...']);
//            redirect(U('index/index'), 1, '系统繁忙...');
//        }

        if(!$param&&(!empty($aaa[0])||count($aaa)>0)){//I()没有值 && cookie有值 说明是跳转地址后回来的
            $param=$aaa;
        }

        $cart_ids=$param['cart_ids'];
        if($cart_ids){//购物车
            $ret=gwc_item(['uid'=>$uid,'id'=>['in',$cart_ids]]);
            if(!$ret){
                IS_AJAX&&$this->ajaxReturn(['status'=>0,'msg'=>'表单已过期...']);
                redirect(U('index/index'), 1, '表单已过期...');
            }

            $msg=$ret['msg'];
            $list=$ret['list'];
            $cart_ids=json_encode($param);
            cookie('cart_ids',$cart_ids);//购物车id字符串
        }else{//立即购买
            $item_id=$param['item_id'];
            $attr_id=$param['attr_id'];
            $num=$param['num'];
            //商品信息
            $item=$this->_item->where(['id'=>$item_id])->getField('id,img,title');
            $item_attr= $this->_item_attr->where(['id'=>$attr_id])->getField('id,item_id,attr_name,attr_value,price,oldprice,acer,coin');
//            unset($item[$item_id]['id']);
            unset($item_attr[$attr_id]['id']);
            $list[0]=array_merge($item[$item_id],$item_attr[$attr_id]);
            $list[0]['num']=$num;
            $money=$num*$item_attr[$attr_id]['price'];
            $msg['money']=$money;
            $msg['count']=1;
            $param=json_encode($param);
            cookie('cart_ids',$param);//立即购买字符串
        }
//        cookie('is_frist',1);

        $address=M('member_address')->where(['uid'=>$uid,'status'=>1])->order('is_default desc,id desc')->find();
        $this->assign('address',$address);
        $this->assign('msg',$msg);
        $this->assign('list',$list);
        $this->assign('id',$item_id);//返回商品详情页需要

        cookie('location',null);
        $this->display();
    }
    //购物车 结束*******************************************************



     //首页发现好货等活动标签跳转
      public function lable(){
          $fx = I('fx');
          $shm = I('zhm');
          $jx = I('jx');
          if($fx) $map['fx']= 1;//dump($data_like);die;
          if($shm) $map['zhm']= 1;
          if($jx) $map['jx']= 1;
	    $list = M('item')->where($map)->field('id,title,img,cate_id,price,oldprice,sales')->limit(20)->order('update_time desc')->select();
          if ($list) {
              foreach ($list as $key => $val) {
                  $list[$key]['img'] = attach($val['img'], 'item');
              }
          }
	    $this->assign('list',$list);
	    $this->display();
      }

}