<?php
namespace Home\Controller;
class OrderController extends HomeController {
    public function _initialize() {
        parent::_initialize();
        $this->Order = D('CurriculumOrder');
        $this->uid = $this->checklogin();
    }

    //课程确认订单
    public function courseBuy(){
        $id = I('id','','intval');
        //详情
        $info = M('Curriculum')->alias('c')
                ->join('__CURRICULUM_CATE__ cc on c.cate_id = cc.id')
                ->join('__ADMIN__ a on c.teacher_id = a.id')
                ->where(array('c.id'=>$id))
                ->field('c.id,c.cate_id,c.title,c.price,c.type,cc.spid,a.name,(select count(*) from jrkj_curriculum_ext where c_id = c.id and status = 1) nums')
                ->find();
        empty($info) && $this->error('非法访问');
        //保存课程ID
        session('set_curriculum_id',$id);

        //获取课程阶段情况
        $cate_info = explode('|',rtrim($info['spid'],'|'));
        unset($cate_info[0]);
        $cate_info[] = $info['cate_id'];
        foreach ($cate_info as $k=>$v){
            $info['cate'][] = M('CurriculumCate')->where(array('id'=>$v))->getfield('name');
        }

        //学生家长信息
        $user_info = M('Member')->field('realname,mobile')->find($this->uid);

        $this->assign('info',$info);
        $this->assign('user_info',$user_info);
        $this->display();
    }

    //提交订单选择支付方式
    public function shopCart(){
        $id = $_SESSION['set_curriculum_id'];
        $o_id = $_SESSION['set_order_id'];

        if($id){
            $info = M('Member')->find($this->uid);
            empty($info['realname']) && $this->error('亲，请先完善个人信息！');

            //订单号
            $sn = substr_replace(time().rand(10000, 99999), 88, 1, 5);
            //课程详情
            $info = M('Curriculum')->field('price,type')->find($id);
            if($info['type'] == 3){
                $type = 2;
            }else{
                $type = 1;
            }

            //咨询老师信息
            $consult_teacher = json_decode($_COOKIE['set_teacher'],true);

            //添加订单
            $o_id = $this->Order->add(array(
                'order_sn' => $sn,
                'c_id' => $id,
                'uid' => $this->uid,
                'price' => $info['price'],
                'status' => 1,
                'add_time' => time(),
                'type' => $type,
                'teacher_name' => $consult_teacher['name'],
                'we_chat' => $consult_teacher['we_chat']
            ));
            empty($o_id) && $this->error('订单提交失败，请重试');
            //保存订单号，防止重复提交
            session('set_order_id',$o_id);
            session('set_curriculum_id',null);
            cookie('set_teacher',null);

            $info += array(
                'order_sn' => $sn,
                'o_id' => $o_id
            );

            $this->assign('info',$info);
            $this->display();
        }elseif ($o_id){
            //订单详情
            $info = $this->Order->field('id o_id,order_sn,price,status')->find($o_id);

            $this->assign('info',$info);
            $this->display();
        }else{
            $this->error('订单已提交或数据已过期');
        }
    }

    public function alipay(){
        header("Content-Type:text/html;charset=utf-8");
        require_once("_core/Library/Vendor/pay/alipay.config.php");
        import("Vendor.pay.lib.alipay_submit#class",'', '.php');

        //订单详情
        $id = I('id','','intval');
        $info = $this->Order->where(array('id'=>$id,'uid'=>$this->uid))
            ->field('id,c_id,order_sn,price,status')
            ->find();

        empty($info) && $this->error('订单不存在');
        //验证是否已支付
        ($info['status'] == 2) && $this->error('该订单已经支付');

        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "create_direct_pay_by_user",
            "partner" => trim($alipay_config['partner']),
            "seller_email" => trim($alipay_config['seller_email']),
            "payment_type"	=> 1,
            "notify_url"	=> "http://".$_SERVER['SERVER_NAME']."/alipay_notify.php",
            "return_url"	=> "http://".$_SERVER['SERVER_NAME']."/alipay_return.php",
            "out_trade_no"	=> $info['order_sn'],
            "subject"	=> '兴智教育在线课程',
            "total_fee"	=> $info['price'],
            "body"	=> "兴智教育",
            "show_url"	=> $_SERVER['SERVER_NAME'].U('Member/index'),
            "anti_phishing_key"	=> "",
            "exter_invoke_ip"	=> "",
            "_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
        );

        //建立请求
        $alipaySubmit = new \AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确定");
        echo $html_text;
    }

    //微信支付
    public function wxPay(){
        header("Content-Type:text/html;charset=utf-8");
        ini_set('date.timezone','Asia/Shanghai');
        //导入微信支付sdk
        import("Vendor.Wxpay.lib.WxPay#Api", '', '.php');
        import("Vendor.Wxpay.example.WxPay#NativePay", '', '.php');

        //订单详情
        $id = I('id','','intval');
        $info = $this->Order->where(array('id'=>$id,'uid'=>$this->uid))
            ->field('id,c_id,order_sn,price,status')
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
        $input->SetAttach("curriculum");
        $input->SetOut_trade_no($info['order_sn']);
        $input->SetTotal_fee($info['price']*100);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("课程");
        $input->SetNotify_url("http://".$_SERVER['SERVER_NAME']."/notify.php");
        $input->SetTrade_type("NATIVE");
        $input->SetProduct_id($info['c_id']);
        $result = $notify->GetPayUrl($input);
        $url = $result["code_url"];

        $this->assign('info',$info);
        $this->assign('url',$url);
        $this->display();
    }

    //支付成功页面
    public function payOk(){
        $id = I('id','','intval');
        //订单详情
        $info = $this->Order->field('id,c_id,order_sn,price,status')->find($id);

        $this->assign('info',$info);
        $this->display();
    }

    //验证微信扫码支付是否成功
    public function ckeck_pay_ok(){
        $id = I('id','','intval');
        $status = $this->Order->where(array('id'=>$id))->getfield('status');
        echo $status;
    }

    //家庭教育课程确认订单
    public function homeCourseBuy(){
        $id = I('id','','intval');
        //详情
        $info = M('Curriculum')
            ->where(array('id'=>$id,'status'=>1))
            ->field('id,title,price,cycle')
            ->find();
        empty($info) && $this->error('非法访问');
        //保存课程ID
        session('set_curriculum_id',$id);

        //学生家长信息
        $user_info = M('Member')->field('realname,mobile')->find($this->uid);

        $this->assign('info',$info);
        $this->assign('user_info',$user_info);
        $this->display();
    }

}