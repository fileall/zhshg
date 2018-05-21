<?php

namespace Mobile\Controller;
/**商家类
 * Class MerchantController
 * @package Mobile\Controller
 */
class MerchantController extends HomeController
{

    public function _initialize()
    {

        parent::_initialize();
        $this->uid = is_login();
        if (!$this->uid) {
            $this->redirect('Login/enter');
        }

        $this->_member = D('Member');
        $this->_merchant = D('Merchant');
        $this->_merchant_cate = D('MerchantCate');

        $merchant = $this->_merchant->where(['uid' => $this->uid])->find();
        $this->assign('merchant', $merchant);

    }

    //周边 商家列表
    public function merchant_list()
    {
        //一级分类$cate //二级分类$cate_next
        $cate = $this->_merchant_cate->field('id,name,pid')->where(['pid'=>0,'status'=>1])->order('ordid asc,id desc')->select();
        if($cate){
            $pids=array_column($cate,'id');
            $cate_next=$this->_merchant_cate->field('id,name,pid')->where(['pid'=>['in',$pids],'status'=>1])->select();
        }
        $this->assign('cate',$cate);
        $this->assign('cate_next',$cate_next);
        $this->display();

    }


    //商家列表数据
    public function merchant_list_ajax(){
        $data=I();
        $page = $data['page'];
        $merchant_modle=$this->_merchant;
        $map['status']=2;//商家申请状态  0为未审核 1为驳回 2为通过 3暂停营业,
        $map['is_act']=1;//是否禁用 0禁用 1开启


        ($title=$data['title']) && $map['title']=array('like','%'.$title.'%');
        ($zftype=$data['zftype'])&& $map['zftype'] = array('like','%'.$zftype.'%');
        //分类
        if($cate_id=$data['cate_id']) {
            $id_arr = $this->_merchant_cate->get_child_ids($cate_id, true);
            $map['cate_id']=['in',$id_arr];
        }
        //地区
        $address=array_filter(explode(',',$data['address']));
        if($address){
            $place_id = get_place_id($address[count($address)-1]);
            $map['province_id|city_id|district_id']=$place_id?$place_id:'-1';
        }

        //经纬度
        $lng_lat=explode(',',cookie('lng_lat'));
        $lng1= $lng_lat[0];// 115.883849;//经度
        $lat1=$lng_lat[1];//=28.678729;

        $dec_jl='1000000000000000';//单位米
        $sql=' round(6378.138*2*asin(sqrt(pow(sin( ('.$lat1.'*pi()/180-latitude*pi()/180)/2),2)
          +cos('.$lat1.'*pi()/180)*cos(latitude*pi()/180)
          * pow(sin( ('.$lng1.'*pi()/180-longitude*pi()/180)/2),2)))*1000) <'.$dec_jl;
        $map['_string'] = $sql;

        $count=$merchant_modle->where($map)->count();
        $pages =   ceil($count/ 8); //上取整    总条数/每页条数
        $list_aj[0] = $pages;//总页数
        //每页数据
        $list_ajs=$merchant_modle->where($map)
            ->field('round(6378.138*2*asin(sqrt(pow(sin( ('.$lat1.'*pi()/180-latitude*pi()/180)/2),2)
          +cos('.$lat1.'*pi()/180)*cos(latitude*pi()/180)
          * pow(sin( ('.$lng1.'*pi()/180-longitude*pi()/180)/2),2)))*1000) as juli,id,title,img,start,end,set_coin,zftype')
            ->order('juli asc')->limit(($page-1) * 8 , 8)->select();
        $this->assign('list',$list_ajs);
        $a=$this->fetch('merchant_list_ajax');

        $list_aj[1] = $a;//数据
        echo  json_encode($list_aj);

    }



    //商户详情
    public function merchant_details($id){
        $info= M('Merchant')->where('id='.$id)->find();


        $info['sh_img'] = M('merchant_img')->where('merchant_id='.$id)->field('img')->order('ordid asc,id desc')->select();

        $info['cate']= M('MerchantCate')->where('id='.$info['cate_id'])->find();

        $info['xyz']=Convert_GCJ02_To_BD09($info['latitude'],$info['longitude']);//纬度、经度
        $aa=str_replace("1",'金元宝',$info['zftype']);
        $bb=str_replace("2",'银元宝',$aa);
        $info['zftype']=str_replace("3",'金果',$bb);

        $this->assign('info', $info);
        $tpl=I('map')? 'merchant_map':'';

        $this->display($tpl);
    }

    //我是商家
    public function merchant(){
        $merchant=$this->get('merchant');
        $merchant=$this->_merchant->where(['id'=>$merchant['id']])->find();
        $this->assign('merchant',$merchant);
        $this->display();

    }
    //我是商家》二维码
    public function merchant_code(){
        $this->display();

    }

    //关闭&&开启店铺
    public function merchant_operation(){
        $status=I('status');
//        var_dump($status);die;
        $merchant=$this->get('merchant');
        $set=$this->_merchant->where(['id'=>$merchant['id']])->setField('status',$status);//商家申请状态  0为未审核 1为驳回 2为通过 3暂停营业',

        $resturn=$set?['status'=>1,'msg'=>'操作成功']:['status'=>0,'msg'=>'操作失败'];

        $this->ajaxReturn($resturn);

    }
    //商铺申请界面
    public function merchant_add(){
        //获取微信分享必要参数=>用户微信多图
        $jssdk = new \Mobile\Org\Jssdk(C("WX_CONFIG.appid"), C("WX_CONFIG.appsecret"));
        $js = $jssdk->GetSignPackage();
        $this->assign('js',$js);

        //一级分类$cate //二级分类$cate_next
        $cate = $this->_merchant_cate->field('id,name,pid')->where(['pid'=>0,'status'=>1])->order('ordid asc,id desc')->select();
        if($cate){
            $pids=array_column($cate,'id');
            $cate_next=$this->_merchant_cate->field('id,name,pid')->where(['pid'=>['in',$pids],'status'=>1])->select();
        }
        $this->assign('cate',$cate);
        $this->assign('cate_next',$cate_next);

        //修改时店铺的id
        if($merchant_id=I('merchant_id')){
            $info=$this->_merchant->find($merchant_id);
            $info['cate_id']&&$info['cate_name']=$this->_merchant_cate->where(['id'=>$info['cate_id']])->getField('name');
            $info['relation_id']&&$info['tuijian']=$this->_member->where(['id'=>$info['relation_id']])->getField('mobile');

            //省市区
            $place_ids=[$info['province_id'],$info['city_id'],$info['district_id']];
            $place=M('place')->where(['id'=>['in',$place_ids]])->getField('id,name');
            $info['province_id']&&$region=$place[$info['province_id']].',';
            $info['city_id']&&$region.=$place[$info['city_id']].',';
            $info['district_id']&&$region.=$place[$info['district_id']].',';
            $info['region']=$region?$region:'';
            //相册
            $info['imgs']=M('merchant_img')->where(['merchant_id'=>$merchant_id])->select();

            $this->assign('info',$info);
        }
        $this->display();
    }

    //店铺申请&&修改
    public function merchant_update(){
        $pos = I();
        $uid= $this->uid;
        $member_mod=$this->_member;
        $merchant_mod=$this->_merchant;
        $mc_mod=$this->_merchant_cate;
        $now=time();
        $merchant_id=$pos['merchant_id'];
        $member = $member_mod->find($uid);
        $merchant_id&&$merchant_old = $this->get('merchant');

        $mer = $merchant_mod->where(array('tel'=>$pos['tel']))->count();
        $merchant_old['tel']!=$pos['tel']&&($mer)&& exit(json_encode(array('status'=>0,'msg'=>'店铺号码被占用')));

        $mer_have = $merchant_mod->where(array('uid'=>$uid,'status'=>['in','0,2,3']))->find();
        ($mer_have)&& exit(json_encode(array('status'=>0,'msg'=>'您已申请过店铺,请勿重复申请')));
        //商家的上线
        if($pos['tuijian']){
            $have_c =$member_mod->where(array('mobile'=>$pos['tuijian']))->find();//推荐人手机查推荐人
            ($have_c&&($have_c['id'] == $uid))&& exit(json_encode(array('status'=>0,'msg'=>'请填写正确的推荐人')));
        }

        $arr_addr=explode(',',$pos['region']);
        $data['province_id']= get_place_id($arr_addr[0]);
        $data['city_id']=get_place_id($arr_addr[1]);
        $data['district_id']=get_place_id($arr_addr[2]);

        $data['uid']=$uid;
        $data['title']=$pos['title'];
        $data['relation_id']=$have_c['id'];//上级会员id
        $data['tel']=$pos['tel'];
        $data['cate_id']=$pos['cate_id'];
        $data['address']=$pos['address'];
        $data['longitude']=$pos['longitude'];//经度
        $data['latitude']=$pos['latitude'];//经度
        $data['yy_img']=$pos['img_2'][0];//营业执照
        $data['status']=0;//商家申请状态  0为未审核 1为驳回 2为通过
        $data['add_time']=$now;
        if($merchant_id>0){//修改
            $row = $merchant_mod->where(['id'=>$merchant_id])->save($data);
            $shop_id=$merchant_id;
        }else{//新增
            $row = $shop_id=$merchant_mod->add($data);
        }

        if($row){
            $img1=$pos['img_1'];//店铺图片数组
            if(!empty($img1)){
                //店铺图片
                foreach($img1 as $kk=>$vv){
                    $imgs[]=['merchant_id'=>$shop_id,'img'=>$vv];
                }
                $merchant_id&&M('merchant_img')->where(['merchant_id'=>$merchant_id])->delete();
                $res_imgs=M('merchant_img')->addAll($imgs);
                //收款二维码(商户表id)
                $ewm =  $this->set_qrcode("http://".$_SERVER['HTTP_HOST'].U('wallet/scan_pay',array('merchant_id'=>$row)));
                $merchant_mod->where(['id'=>$row])->setField('ewm',$ewm);
            }
            exit(json_encode(array('status'=>1,'msg'=>'操作成功','url'=>U('member/mine'))));
        }else{
            exit(json_encode(array('status'=>0,'msg'=>'操作失败')));

        }
    }

    //微信传图(已封装)
    public function uploadImage()
    {
        $data = I();
        $nums=$data['nums'];//nums1营业执照2店铺多图
        $folder=($nums==1)?'merchant_img/':'merchant_yyimg/';//nums1店铺多图2营业执照

        $res= wx_upload_img($data,$folder);
        if($res){
            $this->ajaxReturn(array('status'=>1,'msg'=>'上传成功','name'=>$res));
        }else{
            $this->ajaxReturn(array('status'=>-1,'msg'=>'上传失败'));
        }

    }

    //店铺头修修改
    public function uploadAvatar()
    {
        $data = I();
        $folder='avatar/';
        $res= wx_upload_img($data,$folder);

        if($res){
            $merchant=$this->get('merchant');
            $res_img=M('merchant')->where('id='.$merchant['id'])->setField('img',$res);
            !$res_img&& $this->ajaxReturn(array('status'=>-1,'msg'=>'上传失败'));

            $this->ajaxReturn(array('status'=>1,'msg'=>'上传成功','name'=>$res));
        }else{
            $this->ajaxReturn(array('status'=>-1,'msg'=>'上传失败'));
        }

    }

    //商家钱包*********************************************************
    //商家钱包》元宝
    public function merchant_ingot(){
        $this->display();

    }
    //商家钱包》金果
    public function merchant_fruit(){
        $this->display();
    }

    //提现：1会员工资2商家元宝3商家金果4区代工资0
    public function extract(){
        $uid=$this->uid;
        if(IS_POST){
            $pos = I('post.');
            $prices=$pos['nums'];
            $page_type=$pos['page_type'];//请求的页面：1会员工资2商家元宝3商家金果4区代工资

            $now=time();
            $withdraw_mod=M('withdraw_member');//申请表
            $account_model=M('account');//明细表
            $wallet_model=M('member');//钱包
            $wallet = $this->_member->find($uid);//提现对象数据
            $paypassword=$wallet['paypassword'];
            $service_charge=0;
            switch($page_type){
                case 1:
                    $account_model=M('account');//明细表
                    $service_charge=C('pin_tx_sxf');
                    $memos="工资提现";
                    $field='prices';
                    break;
                case 2:
                    $withdraw_mod=M('withdraw_merchant');//申请表
                    $account_model=M('account_shop');//明细表
                    $wallet_model=M('merchant');//钱包
                    $wallet=$this->_merchant->where(['uid'=>$uid])->find();
                    $memos="商家元宝提现";
                    $field='gold_acer';
                    break;
                case 3:
                    $withdraw_mod=M('withdraw_merchant');//申请表
                    $account_model=M('account_shop');//明细表
                    $wallet_model=M('merchant');//钱包
                    $wallet=$this->_merchant->where(['uid'=>$uid])->find();
                    $memos="商家金果提现";
                    $field='gold_fruit';

                    break;
                case 4:
                    $account_model=M('account_qd');//明细表
                    $memos="区代工资提现";
                    $field='prices';
                    break;
                default:
                    $this->ajaxReturn(['status'=>0,'msg'=>'系统繁忙!']);
            }



            !$pos['card_id']&&$this->ajaxReturn(['status'=>0,'msg'=>'请选择银行卡!']);
            (!$pos['pas'])&& $this->ajaxReturn(['status'=>0,'msg'=>'请输入支付密码']);
            (!$paypassword)&& $this->ajaxReturn(['status'=>0,'msg'=>'您还未设置支付密码']);
            (st_md5($pos['pas']) != $paypassword)&& $this->ajaxReturn(['status'=>0,'msg'=>'支付密码有误']);

            //余额是否足够
            $dec_money=$prices+$service_charge;
            ($wallet[$field] < $dec_money)&& $this->ajaxReturn(['status'=>0,'msg'=>'您的余额不足!']);

            //验证提现时间是否在周期内
//            $tx_day=C('pin_tx_zq');
//            if($tx_day>0){
//                $last_day = $withdraw_mod->where(['uid'=>$uid])->field('max(add_time)')->find();//上一单时间
//                $last_day&&($now-24*3600*$tx_day < $last_day['max(add_time)'])&& $this->ajaxReturn(['status'=>0,'msg'=>'提现周期为每三天一次!']);
//            }

            //不允许3s内重复操作
            $map=['totalprices'=>$prices,'uid'=>$uid,'status'=>1];
            $map['add_time']=['gt',$now-3];
            $over_order=$withdraw_mod->where($map)->count();
            $over_order&&$this->ajaxReturn(['status'=>0,'msg'=>'请勿重复提交']);

            //验证每日提现金额上限
            $time_start= strtotime(date('Y-m-d', $now));//今天0点时间戳s
            $time_end= $time_start+24*3600;
            $time_rule['add_time'] =  array('between',array($time_start,$time_end));
            $time_rule['uid'] = $uid;
            $time_rule['status']=array('in',array(1,3));

            $history_money = $withdraw_mod->where($time_rule)->getField('sum(totalprices) as all_money');
            $prices_today = $prices+ $history_money;
            $sx = C('pin_tx_mr_je')*10000;
            ($prices_today >$sx)&& $this->ajaxReturn(['status'=>0,'msg'=>'抱歉,每日最高提现金额为:'.$sx.'!']);


            $card = M('member_bankcard')->where(['id'=>$pos['card_id']])->field('id',true)->find();//银行卡id
            $data=$withdraw_mod->create($card);
            $data['uid']=$uid;
            $data['shop_id']=$wallet['id'];
            $data['totalprices']=$prices;//用户提现金额
            $page_type==1&&$data['service_charge']=$service_charge;//手续费
            $page_type==3&&$data['fruit_price']=C('pin_jg_scj');//当时金果价
            $data['status']=1;//状态 1未审核 2通过 3驳回'
            $data['add_time']=$now;

            $start=M();
            $start->startTrans();
            $yd=$withdraw_mod->add($data);	//提交审核

            $yd&&$res_member=$wallet_model->where(array('id'=>$wallet['id']))->setDec($field,$dec_money);//扣余额
            if($page_type==2||$page_type==3)
               $data_account=account_shop_arr(2,$wallet['id'],'-'.$dec_money,'商家'.$memos.'提现',$now);//明细
            else
               $data_account=account_arr(1,$uid,'-'.$dec_money,'工资提现',$now);//明细

            $res_member&&$account_arr=$account_model->add($data_account);

            if(!$yd || !$res_member || !$account_arr){
                $start->rollback();
                $this->ajaxReturn(['status'=>0,'msg'=>'提交申请失败!']);
            }

            $start->commit();
            $this->ajaxReturn(['status'=>1,'msg'=>'提交申请成功!','url'=>U('wallet/wallet')]);



        }else{
            //请求的页面
            $page_type=I('page_type');//请求的页面：1会员工资2商家元宝3商家金果4区代工资
            $this->assign('page_type',$page_type);

            //银行卡
            $bank_card_modle=D('MemberBankcard');
            $card = $bank_card_modle->where(array('member_id'=>$uid,'status'=>1))->order('is_default desc,id desc')->select();
            foreach($card as $k=>$v){
                $card[$k]['nums'] = substr($v['nums'],-4);
            }

            $this->assign('card',$card);
            $this->display();
        }


    }


    //商家钱包》币种流水
    public function merchant_account(){
        $type=I('type');//币种1工资2金元宝3金果4银币
        $member_id=$this->uid;
        $merchant=$this->get('merchant');
        $shop_id=$merchant['id'];


        switch($type){
            case 2:$item_type=2;$field='元宝';break;
            case 3:$item_type=3;$field='金果';break;
            default: echo "No find";
        }
        $account=M('account_shop');
        $page = I('page');
        $map=array('shop_id'=>$shop_id,'type'=>$item_type);
        $count=$account->where($map)->order('id desc')->count();//总条数

        if(IS_AJAX){
            $pages =   ceil($count/ 8); //上取整    总条数/每页条数
            $list_aj[0] = $pages;//总页数
            //每页数据
            $list_ajs=$account->where($map)->order('id desc')->limit(($page-1) * 8 , 8)->select();
            $this->assign('a',$list_ajs);
            $a=$this->fetch('w_bz_liu');

            $list_aj[1] = $a;//数据
            echo  json_encode($list_aj);
        }else{

            $this->assign('is_kk',$count?0:1);//数据为空 1是
            $this->assign('field',$field);
            $this->assign('type',$type);
            $this->display();
        }
    }
    //商家钱包*********************************************************



    //店铺详情 index/shop_details
    //店铺详情:地图 display(shop_map)
    //店铺列表 index/location
    //店铺列表：抓取页面 index/ambitus_head

    //店铺设置
    //提现:  wallet/balance_extract0



    //查下一级分类列表
    public function ajax_getchilds($id=0) {
        $return = $this->_merchant_cate->field('id,name')->where(array('pid'=>$id))->select();

        if ($return) {
            $this->ajax_return(1,'成功', $return);
        } else {
            $this->ajax_return(0, '失败');
        }
    }
    protected function ajax_return($status=1, $msg = '', $data = '', $dialog = ''){
        $ajax_data = array(
            'status' => $status,
            'msg' => $msg,
            'data' => $data,
            'dialog' => $dialog
        );
        $this->ajaxReturn($ajax_data);
    }
    //商家店铺设置
   public function merchant_store(){
       if(IS_POST){
           $tel = I('tel');
           $result = $this->_merchant->where(['tel'=>$tel])->find();
           if($result){
               $this->ajaxReturn(array('err_code'=>1, 'err_msg'=>'手机号已存在！'));
           }
       }else{
           $merchant=$this->get('merchant');
//           $avatar = $this->_member->where(['id'=>$merchant['uid']])->field('avatar')->find();
           $store = $this->_merchant->where(['id'=>$merchant['id']])->find();
           $aa=str_replace("1",'金元宝',$store['zftype']);
           $bb=str_replace("2",'银元宝',$aa);
           $store['zftype']=str_replace("3",'金果',$bb);

           $province_id=$store['province_id'];
           $city_id=$store['city_id'];
           $district_id=$store['district_id'];

           $place_ids=[$province_id,$city_id,$district_id];
           $place= M('place')->where(['id'=>['in',$place_ids]])->getField('id,name');

           $store['area'] = $place[$province_id].',';
           $city_id&& $store['area'].= $place[$city_id].',';
           $district_id&& $store['area'].= $place[$district_id];
           
//           $city = M('place')->where(['id'=>$store['city_id']])->getField('name');
//           $district = M('place')->where(['id'=>$store['district_id']])->getField('name');
//           if(!$store['district_id'] == 0){
//               $store['area'] = $province.$city.$district;
//           }elseif (!$store['city_id'] == 0){
//               $store['area'] = $province.$city;
//           }elseif (!$store['province_id']){
//               $store['area'] = $province;
//           }
           $store['cate'] = M('merchant_cate')->where(['id'=>$store['cate_id']])->getfield('name');


           //获取微信分享必要参数
           $jssdk = new \Mobile\Org\Jssdk(C("WX_CONFIG.appid"), C("WX_CONFIG.appsecret"));
           $js = $jssdk->GetSignPackage();
           $this->assign('js',$js);

           $this->assign('store',$store);
           $this->display();
       }

   }
    //店铺名称    营业时间修改
    public function store_update(){
        if(IS_POST){
            $pos = I('post.');
            $store = $this->_merchant->where(['uid'=>$this->uid])->find();
            //头像
            if($pos['is_avatar']==1){
                $avatar = $this->_merchant->where(array('id'=>$store['id']))->setField('img',$pos['avatar']);
                $re=array('msg'=>$avatar?'头像修改成功':'图片上传失败,请重试','status'=>$avatar?1:0);
            }
            //店铺名
            if($pos['is_title']==1){
                $title = $this->_merchant->where(array('id'=>$store['id']))->setField('title',$pos['title']);
                $re = array(
                    'msg'=>$title?'店铺名修改成功':'新的店铺名不能和旧店铺名相同',
//                    'status'=>$title?1:0,'url'=>$title?U("merchant/store_set"):'');
                    'status'=>$title?1:0,'url'=>$title?U('merchant/merchant_store',array('id'=>$store['id'])):'');
            }

            //营业时间
            if($pos['is_time']==1){
                $pos['start'] = date('H:i',strtotime($pos['start']));
                $pos['end'] = date('H:i',strtotime($pos['end']));
                $start = $this->_merchant->where(array('id'=>$store['id']))->setField('start',$pos['start']);
                $end = $this->_merchant->where(array('id'=>$store['id']))->setField('end',$pos['end']);
                $re = array(
                    'msg'=>($start && $end)?'营业时间修改成功':'新的营业时间不能和旧营业时间相同',
                    'status'=>($start && $end)?1:0,'url'=>($start && $end)?U('merchant/merchant_store',array('id'=>$store['id'])):'');
            }
            $this->ajaxReturn($re);exit;
        }else{
            $is_update = I('get.is_update');
            switch($is_update){
                case 1:
                    $title = $this->_merchant->where(array('uid'=>$this->uid))->field('title')->find();
                    $this->assign('title',$title);
                    $this->display('merchant_store_name');
                    break;
                case 2:
                    $this->display('merchant_store_time');
                    break;

                default:
                    //获取微信分享必要参数
                    $jssdk = new \Mobile\Org\Jssdk(C("WX_CONFIG.appid"), C("WX_CONFIG.appsecret"));
                    $js = $jssdk->GetSignPackage();
//				$this->assign('is_mine',I('get.is_mine'));
                    $this->assign('js',$js);
                    $this->display();
                    break;
            }
        }

    }










}