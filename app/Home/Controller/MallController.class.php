<?php
namespace Home\Controller;
class MallController extends HomeController {
    public function _initialize() {
        parent::_initialize();
        $this->Item = D('Item');
        $this->uid = $this->get_uid();

        //购物车数量
        $cart_num = M('Cart')->where(array('uid'=>$this->uid))->field('SUM(nums) num')->select();
        $this->assign('cart_num',$cart_num[0]['num']);
    }

    //商城首页
    public function index(){
        //轮播图
        $ad = M('Ad')->where(array('board_id'=>8,'status'=>1))->order('ordid,id desc')->select();
        //一级栏目列表
        $cate_mode = D('ItemCate');
        $cate_list = $cate_mode->where(array('pid'=>0,'status'=>1))->order('ordid,id')->field('id,name')->select();
        //获取分类下产品
        foreach ($cate_list as $k=>$v){
            $cate_id_all = $cate_mode->get_child_ids($v['id'],true);
            $cate_list[$k]['list'] = $this->Item->where(array('cate_id'=>array('in',$cate_id_all),'status'=>1))
                ->field('id,title,img,price,(select count(*) from jrkj_collection where relation_id = jrkj_item.id and uid = "'.$this->uid.'" and type = 2) coll_nums')
                ->order('ordid,id desc')
                ->limit(10)
                ->select();
            switch ($k)
            {
                case 0:
                    $cate_list[$k]['class'] = '';
                    break;
                case 1:
                    $cate_list[$k]['class'] = 'two';
                    break;
                case 2:
                    $cate_list[$k]['class'] = 'three';
                    break;
            }
        }
        //dump($cate_list);

        $this->assign('ad',$ad);
        $this->assign('cate_list',$cate_list);
        $this->assign('uid',$this->uid);
        $this->display();
    }

    //产品详情页
    public function goodsDetail(){
        $id = I('id','','intval');
        //详情
        $info = $this->Item->where(array('id'=>$id,'status'=>1))->field('*,(select count(*) from jrkj_collection where relation_id = jrkj_item.id and uid = "'.$this->uid.'" and type = 2) coll_nums')->find();
        empty($info) && $this->error('商品不存在或已下架');
        //图集
        $imgs = M('ItemImg')->where(array('item_id'=>$id))->select();
        //推荐商品列表
        $list = $this->Item->where(array('tj'=>1,'status'=>1,'type'=>$info['type']))
            ->field('id,title,img,price,integral')
            ->order('ordid,id desc')
            ->limit(5)
            ->select();

        $this->assign('info',$info);
        $this->assign('imgs',$imgs);
        $this->assign('list',$list);
        $this->assign('uid',$this->uid);
        $this->display();
    }

    //立即购买
    public function buy_now(){
        $id = I('id','','intval');
        $num = I('num','','intval');
        empty($this->uid) && $this->error('请先登录！');
        empty($num) && $this->error('非法提交！');
        $info = $this->Item->where(array('id'=>$id,'status'=>1))->find();
        empty($info) && $this->error('非法提交！');
        //验证是否已加入购物车
        $cart = M('Cart');
        $check = $cart->where(array('item_id'=>$id,'uid'=>$this->uid))->find();
        $id_all = array();
        if($check){
            $result = $cart->where(array('item_id'=>$id,'uid'=>$this->uid))->setfield('nums',$num);
            if($result){
                $id_all[] = $check['id'];
            }else{
                $this->error('操作失败，请重试！');
            }
        }else{
            $result = $cart->add(array(
                'uid' => $this->uid,
                'item_id' => $id,
                'nums' => $num,
                'add_time' => time()
            ));
            if($result){
                $id_all[] = $result;
            }else{
                $this->error('操作失败，请重试！');
            }
        }
        session('confirm_id_all',$id_all);
        $this->redirect('ItemOrder/confirm_order');
    }

    //加入购物车
    public function join_cart(){
        $id = I('id','','intval');
        $nums = I('nums','','intval');
        empty($this->uid) && exit(json_encode(array(0,'请选登录')));
        $cart = M('Cart');
        //验证是否已加入购物车
        $check = $cart->where(array('item_id'=>$id,'uid'=>$this->uid))->count();
        if($check){
            $result = $cart->where(array('item_id'=>$id,'uid'=>$this->uid))->setInc('nums',$nums);
        }else{
            $result = $cart->add(array(
                'uid' => $this->uid,
                'item_id' => $id,
                'nums' => $nums,
                'add_time' => time()
            ));
        }

        //验证操作结果
        if($result){
            exit(json_encode(array(1,'加入成功')));
        }else{
            exit(json_encode(array(0,'操作失败，请重试')));
        }
    }

    //收藏商品
    public function collect_item(){
        $id = I('id','','intval');
        empty($this->uid) && exit(json_encode(array(0,'请选登录')));
        $collect = M('Collection');
        $check = $collect->where(array('relation_id'=>$id,'uid'=>$this->uid,'type'=>2))->count();
        ($check > 0) && exit(json_encode(array(1,'已收藏')));
        if($collect->add(array('relation_id'=>$id,'uid'=>$this->uid,'type'=>2))){
            exit(json_encode(array(1,'已收藏')));
        }else{
            exit(json_encode(array(0,'操作失败，请重试')));
        }
    }
    
	//电子产品
	public function dz_goods(){
        $id = I('id',0,'intval');
        $brand_id = I('brand_id',0,'intval');
        $price_id = I('price_id',0,'intval');

        $cate_mode = D('ItemCate');
        //产品一级分类
        $cate_list = $cate_mode->where(array('pid'=>0,'status'=>1))->field('id,name')->order('ordid,id')->select();
        //品牌列表
        $brand_list = M('ItemBrand')->where(array('status'=>1))->field('id,name')->order('ordid,id')->select();
        //价格区间
        $price_range = C('PriceRange');

        //条件
        $map = array();
        $map['status'] = 1;
        $map['type'] = 1;
        //当前分类下面所有子分类ID
        $id_all = $cate_mode->get_child_ids($id,true);
        empty($id_all) && $this->error('数据错误！');
        if($id){
            $map['cate_id'] = array('in',$id_all);
        }
        !empty($brand_id) && $map['brand_id'] = $brand_id;
        if($price_id){
            $price_range_info = $price_range[$price_id];
            if($price_range_info['end']){
                $map['price'] = array('between',array($price_range_info['start'],$price_range_info['end']));
            }else{
                $map['price'] = array('egt',$price_range_info['start']);
            }
        }

        $count      = $this->Item->where($map)->count();
        $Page       = new \Think\Page($count,10);
        $show       = $Page->show();
        $list = $this->Item->where($map)
            ->field('id,title,img,price,(select count(*) from jrkj_collection where relation_id = jrkj_item.id and uid = "'.$this->uid.'" and type = 2) coll_nums')
            ->order("'ordid,id desc'")
            ->limit($Page->firstRow.','.$Page->listRows)
            ->select();
        //dump($list);

        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->assign('count',$count);
        $this->assign('cate_list',$cate_list);
        $this->assign('brand_list',$brand_list);
        $this->assign('price_range',$price_range);
        $this->assign('id',$id);
        $this->assign('brand_id',$brand_id);
        $this->assign('price_id',$price_id);
        $this->assign('uid',$this->uid);
		$this->display();
	}
	
	//积分产品
	public function jf_goods(){
		$this->display(); 
	}
}