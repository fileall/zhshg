<?php
namespace Mobile\Controller;
/**用户收货地址
 * Class AddressController
 * @package Mobile\Controller
 */
class AddressController extends HomeController {
    public function _initialize() {

        parent::_initialize();
        $this->uid = is_login();
        if (!$this->uid) {
            $this->redirect('Login/enter');
        }

        $this->_member = D('Member');
        $this->_address= D('MemberAddress');

        $member = $this->_member->find($this->uid);
        $this->assign('member', $member);
    }

    //删除地址
    public function delete_address()
    {
//        $uid    = session('uid');
        $uid=$this->uid;
        $address    = $this->_address;
        if(IS_AJAX){
            $id     = I('id');
            $count=$address->where(['member_id'=>$uid])->count();
            ($count==1)&&$this->ajaxReturn(['status'=>0,'msg'=>"请保留一个默认的收获地址"]);

            $address->where(['member_id'=>$uid])->delete($id);
            if(false == $address){
                $this->ajaxReturn(['status'=>0,'msg'=>"删除地址失败"]);
            }else{
                $this->ajaxReturn(['status'=>1,'msg'=>"删除地址成功"]);
            }
        }
        $this->display();
    }

    //添加&&修改收货地址
    public function add_location()
    {
        $uid = $this->uid;
        $id     = I('id','','intval');
        $attr_id = cookie('attr_id');
        $item_id = cookie('item_id');
        $address_model    = $this->_address;
        if(IS_POST){
            $post= I('post.');

            //改变其他默认地址状态 为0
            $is_default=$post['is_default'];
            if($is_default==1){
                $address_model->where(['uid'=>$uid])->setField('is_default',0);
            }
            //如果不存在id 就是添加操作
            if (!$post['id']) {
                $post['uid'] = $uid;
                $is_default&&$post['is_default'] = $is_default;
                $post['addtime'] = time();
                //拆分省市区储存字段
                $region = $post['region'];
                $str = explode(",",$region);
                if($str[0]) $post['province'] = $str[0];
                if($str[1]) $post['city'] = $str[1];
                if($str[2]) $post['county'] = $str[2];
                $res = $address_model->add($post);
            } else {

                $post['is_default'] = $is_default?$is_default:0;
                $post['addtime'] = time();
                //拆分省市区储存字段
                $region = $post['region'];
                $str = explode(",",$region);
                if($str[0]) $post['province'] = $str[0];
                if($str[1]) $post['city'] = $str[1];
                if($str[2]) $post['county'] = $str[2];

                $res=$address_model->where(array('id'=>$post['id']))->save($post);
            }
            if ($res !== false) {
                if($attr_id != null && $item_id != null){//成功后=》金果商城订单
                    $this->ajaxReturn(array('err_code'=>0, 'err_msg'=>'操作成功', 'uri'=>U('Goldfruitshop/y_goldShopOrder',array('id'=>$attr_id,'item_id'=>$item_id,'addr_id'=>$_POST['id']))));
                }else{//成功后=》地址列表
                    $location=cookie('location');//分发页面1地址列表2订单提交前
                    $uri=($location==2)?U('item/gwc_settlement'):U('location');
                    $this->ajaxReturn(array('err_code'=>0, 'err_msg'=>'操作成功', 'uri'=>$uri));
                }
            } else {
                $this->ajaxReturn(array('err_code'=>1, 'err_msg'=>'系统异常'));
            }
        }else{
            $address    = $address_model->where(['member_id'=>$uid,'id'=>$id])->find();
            $address['region']=$address['province'];
            $address['city']  && ($address['region'].=','.$address['city'].',');
            $address['county']&& $address['region'].=$address['county'];

            $this->assign('address',$address);
            $this->display();
        }
    }

    //收货地址列表
    public function location()
    {
        //来源 1地址管理 2订单
        if($location=I('location')){//来源1地址管理 2订单
            cookie('location',$location?$location:1);//用于设置默认地址后，分发页面
        }
        //来源 金果商城
        $attr_id = I('attr_id','','trim');
        $item_id = I('item_id','','trim');
        cookie('attr_id',$attr_id,4800);
        cookie('item_id',$item_id,4800);
        $addr_id = I('addr_id','','trim');
        $this->assign('item_id',$item_id);
        $this->assign('addr_id',$addr_id);
        $this->assign('attr_id',$attr_id);

        $uid    = $this->uid;
        $place  = $this->_address->where(['uid'=>$uid])->order('is_default desc,id desc')->select();
        $this->assign('place',$place);
//        var_dump($place);die;
        //改变默认地址
        if(IS_AJAX){
            $id     = I('id');
            $status = I('status');
            $attr_id = I('attr_id','','trim');
            $item_id = I('item_id','','trim');
            if($status==1){
                $this->_address->where(['uid'=>$uid])->setField('is_default',0);
                $res    = $this->_address->where(['id'=>$id])->setField('is_default',1);

            }else{
                $res    = $this->_address->where(['id'=>$id])->setField('is_default',0);
            }
            if ($res) {
                $location=cookie('location');//分发页面1地址列表2订单提交前
                if($attr_id != null && $item_id != null){
                    $uri = U('Goldfruitshop/y_goldShopOrder',array('item_id'=>$item_id,'attr_id'=>$attr_id));
                }else{
                    $uri=($location==2)?U('item/gwc_settlement'):U('location');
                }
                $this->ajaxReturn(array('status'=>1, 'msg'=>'操作成功', 'uri'=>$uri));
            } else {
                $this->ajaxReturn(array('status'=>0, 'msg'=>'系统异常'));
            }
        }
        $this->display();
    }
}