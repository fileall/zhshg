<?php
namespace Home\Controller;
class ItemOrderController extends HomeController {
    public function _initialize() {
        parent::_initialize();
        $this->Order = D('Order');
        $this->uid = $this->checklogin();
    }

    //确认订单
    public function confirm_order(){
        $cart = M('Cart');
        if(IS_POST){
            $id = I('id','','trim');
            //防止恶意提交
            $id_all = $cart->where(array('id'=>array('in',rtrim($id,',')),'uid'=>$this->uid))->getfield('id',true);
            if($id_all){
                session('confirm_id_all',$id_all);
                echo 1;
            }else{
                echo 0;
            }
        }else{
            $id_all = $_SESSION['confirm_id_all'];
            empty($id_all) && $this->error('订单已提交或有效期已过，请重新选择商品提交',U('ItemOrder/cart'),5);
            //提交商品列表
            $list = M('Cart')->alias('c')
                ->where(array('c.id'=>array('in',$id_all),'c.uid'=>$this->uid))
                ->join('__ITEM__ i on c.item_id = i.id')
                ->field('c.*,i.title,i.img,i.price')
                ->select();
            empty($list) && $this->errot('提交的数据有误');

            $this->assign('list',$list);
            $this->display();
        }
    }

    //异步获取收货地址
    public function get_address(){
        //地址列表
        $list = M('Address')->where(array('uid'=>$this->uid,'status'=>1))
            ->order('is_default desc')
            ->field('id,province,city,county,address,shperson,mobile,is_default')
            ->select();

        $this->assign('list',$list);
        $this->display();
    }

    //确认订单
    public function place_order(){
        if(IS_POST){
            $data = I('post.');
            //收货地址
            $dz = M('Address')->where(array('id'=>$data['aid'],'uid'=>$this->uid,'status'=>1))->find();
            empty($dz) && exit(json_encode(array(0,'请选择有效的收货地址')));
            //购物车商品
            $id_all = $_SESSION['confirm_id_all'];
            empty($id_all) && exit(json_encode(array(2,'订单提交有效期已过，请重新去购物车提交')));
            $cart = M('Cart');
            $list = $cart->alias('c')
                ->where(array('c.id'=>array('in',$id_all),'c.uid'=>$this->uid))
                ->join('__ITEM__ i on c.item_id = i.id')
                ->field('c.*,i.id item_id,i.title,i.img,i.price')
                ->select();
            empty($list) && exit(json_encode(array(2,'订单提交有效期已过，请重新去购物车提交')));
            //订单号
            $sn = substr_replace(time().rand(10000, 99999), 288, 0, 6);
            //计算订单总价及组合订单详情
            $total_price = 0;
            $all = $del_id_all = array();
            foreach ($list as $v){
                $total_price += $v['price']*$v['nums'];
                $del_id_all[] = $v['id'];
                $all[] = array(
                    'uid' => $this->uid,
                    'item_id' => $v['item_id'],
                    'title' => $v['title'],
                    'price' => $v['price'],
                    'nums' => $v['nums'],
                    'img' => $v['img'],
                    'add_time' => time()
                );
            }

            $cart->startTrans();
            //添加订单
            $oid = M('Order')->add(array(
                'order_sn' => $sn,
                'uid' => $this->uid,
                'shperson' => $dz['shperson'],
                'mobile' => $dz['mobile'],
                'address' => $dz['province'].$dz['city'].$dz['county'].$dz['address'],
                'totalprices' => $total_price,
                'memos' => $data['memos'],
                'add_time' => time()
            ));
            //添加订单详情
            array_walk($all, function(&$v, $k, $p) { $v = array_merge($v, $p); }, array('oid'=>$oid));
            $ol = M('OrderList')->addAll($all);
            //删除购物车商品
            $cd = $cart->where(array('id'=>array('in',$del_id_all)))->delete();

            //验证各操作状态
            if($oid && $ol && $cd){
                $cart->commit();
                session('confirm_id_all',null);
                exit(json_encode(array(1,$oid)));
            }else{
                $cart->rollback();
                exit(json_encode(array(0,'操作失败，请重试')));
            }
        }
    }

    //提交订单选择支付方式
    public function shopCart(){
        $id = I('id','','intval');
        //订单详情
        $info = $this->Order->where(array('id'=>$id,'uid'=>$this->uid))
            ->field('id,order_sn,totalprices,status')
            ->find();
        empty($info) && $this->error('该订单不存在');
        ($info['status'] == 2) && $this->error('该订单已经支付');

        $this->assign('info',$info);
        $this->display();
    }

    public function wxPay(){
        header("Content-Type:text/html;charset=utf-8");
        ini_set('date.timezone','Asia/Shanghai');
        //导入微信支付sdk
        import("Vendor.Wxpay.lib.WxPay#Api", '', '.php');
        import("Vendor.Wxpay.example.WxPay#NativePay", '', '.php');

        //订单详情
        $id = I('id','','intval');
        $info = $this->Order->where(array('id'=>$id,'uid'=>$this->uid))
            ->field('id,order_sn,totalprices,status')
            ->find();
        
        empty($info) && $this->error('订单不存在');
        //验证是否已支付
        ($info['status'] == 2) && $this->error('该订单已经支付');

        /**
         * 流程：
         * 1、调用统一下单，取得code_url，生成二维码
         * 2、用户扫描二维码，进行支付
         * 3、支付完成之后，微信服务器会通知支付成功
         * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
         */
        $notify = new \NativePay();
        $input = new \WxPayUnifiedOrder();

        $input->SetBody("兴智教育");
        $input->SetAttach("item");
        $input->SetOut_trade_no($info['order_sn']);
        $input->SetTotal_fee($info['totalprices']*100);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("商城");
        $input->SetNotify_url("http://www.aimeichuang.cn/item_notify.php");
        $input->SetTrade_type("NATIVE");
        $input->SetProduct_id($info['id']);
        $result = $notify->GetPayUrl($input);
        $url = $result["code_url"];

        $this->assign('info',$info);
        $this->assign('url',$url);
        $this->display();
    }

    //验证微信扫码支付是否成功
    public function ckeck_pay_ok(){
        $id = I('id','','intval');
        $status = $this->Order->where(array('id'=>$id))->getfield('status');
        echo $status;
    }

    //支付成功页面
    public function payOk(){
        $id = I('id','','intval');
        //订单详情
        $info = $this->Order->field('id,order_sn,totalprices,status')->find($id);

        $this->assign('info',$info);
        $this->display();
    }

    //购物车
    public function cart(){
        //购物车列表
        $list = M('Cart')->alias('c')
            ->where(array('c.uid'=>$this->uid))
            ->join('__ITEM__ i on c.item_id = i.id')
            ->field('c.*,i.title,i.img,i.price')
            ->select();

        //推荐列表
        $tj = M('Item')->where(array('tj'=>1,'status'=>1,'type'=>1))
            ->field('id,title,img,price')
            ->order('ordid,id desc')
            ->limit(5)
            ->select();
//        dump($tj);

        $this->assign('list',$list);
        $this->assign('tj',$tj);
        $this->display();
    }

    //删除购物车商品
    public function del_cart_item(){
        $id = I('id','','trim');
        if(M('Cart')->where(array('id'=>array('in',rtrim($id,',')),'uid'=>$this->uid))->delete()){
            echo 1;
        }else{
            echo 0;
        }
    }

}