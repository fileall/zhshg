<?php
namespace Home\Controller;
class LoginController extends HomeController {
    public function _initialize() {
        parent::_initialize();
    }

    //注册
    public function register() { 
        if(IS_POST){
            $user = D('Member');
            if (false === $data = $user->create()){
                exit(json_encode(array(0,$user->getError())));
            }else{
                //注册送积分规则
                $time = time(); 
                $info = M('IntegralRule')->find(1);
                $user->startTrans();
                if($info['status'] == 1 && $time >= $info['start_time'] && $time <= $info['end_time']){
                    $user->integral = $info['integral'];
                    $uid = $user->add();
                    $integral_log = M('Integral')->add(array(
                        'uid' => $uid,
                        'integral' => $info['integral'],
                        'describe' => '注册送积分',
                        'add_time' => $time,
                        'type' => 1
                    ));
                } else {
                    $uid = $user->add();
                    $integral_log = true;
                }

                //验证操作
                if($uid && $integral_log){
                    $user->commit();
                    cookie('reg_data',null);
                    exit(json_encode(array(1,'注册成功')));
                }else{
                    $user->rollback();
                    exit(json_encode(array(0,'操作失败，请重试')));
                }
            }
        } else {
            $cate = M('CurriculumCate')->where(array('pid'=>0,'status'=>1))
                ->field('id,name')
                ->order('ordid,id desc')
                ->select();

            $this->assign('cate',$cate);
            $this->display();
        }
    }

    //订单分类联动
    public function ajax_getchilds() {
        $id = I('id','', 'intval');
        $return = D('CurriculumCate')->field('id,name')->where(array('pid'=>$id))->select();
        if ($return) {
            $this->ajax_return(1, L('operation_success'), $return);
        } else {
            $this->ajax_return(0, L('operation_failure'));
        }
    }

    public function teacher(){
        $this->display();
    }

    //登录
    public function enter(){
        if(IS_POST){
            $data = I('post.');
            empty($data['mobile']) && exit(json_encode(array(0,'请输入手机号')));
            (check_mobile($data['mobile']) === false) && exit(json_encode(array(0,'手机号格式错误')));
            empty($data['password']) && exit(json_encode(array(0,'请输入密码')));
            (check_pwd($data['password']) === false) && exit(json_encode(array(0,'密码格式错误')));
            $info = M('Member')->where(array('mobile'=>$data['mobile']))->find();
            empty($info) && exit(json_encode(array(0,'手机号不存在')));
            empty($info['status']) && exit(json_encode(array(0,'手机号被禁止登录')));
            (st_md5($data['password']) != $info['password']) && exit(json_encode(array(0,'密码错误')));
            session('user_auth',array(
                'id' => $info['id'],
                'mobile' => $info['mobile']
            ));
            cookie('user_auth',array(
                'id' => $info['id'],
                'mobile' => $info['mobile']
            ));
            exit(json_encode(array(1,'登录成功')));
        }else{
            //动态关闭表单令牌
            C('TOKEN_ON',false);
            $this->display();
        }
    }

    //忘记密码
    public function forgot_pwd(){
        if(IS_POST){
            $data = I('post.');
            empty($data['mobile']) && exit(json_encode(array(0,'请输入手机号')));
            (check_mobile($data['mobile']) === false) && exit(json_encode(array(0,'手机号格式错误')));
            empty($data['code']) && exit(json_encode(array(0,'请输入验证码')));
            empty($data['password']) && exit(json_encode(array(0,'请输入密码')));
            (check_pwd($data['password']) === false) && exit(json_encode(array(0,'密码格式错误')));
            ($data['password'] != $data['confirm_password']) && exit(json_encode(array(0,'两次密码输入不一致')));
            $pwd_data = cookie('pwd_data');
            ($pwd_data['mobile'] != $data['mobile']) && exit(json_encode(array(0,'手机号更改或验证码已过期')));
            ($pwd_data['code'] != $data['code']) && exit(json_encode(array(0,'验证码错误或已过期')));

            $info = M('Member')->where(array('mobile'=>$data['mobile'],'status'=>1))->find();
            empty($info) && exit(json_encode(array(0,'该手机号被禁止登录')));

            $pwd = st_md5($data['password']);
            if($pwd == $info['password']) {
                cookie('pwd_data',null);
                exit(json_encode(array(1,'修改成功')));
            }elseif(M('Member')->where(array('mobile'=>$data['mobile']))->setfield('password',$pwd)){
                cookie('pwd_data',null);
                exit(json_encode(array(1,'修改成功')));
            }else{
                exit(json_encode(array(0,'操作失败，请重试')));
            }
        }else{
            //动态关闭表单令牌
            C('TOKEN_ON',false);
            $this->display();
        }
    }

    //获取课程分类
    public function get_curriculum(){
        $id = I('id','','intval');
        empty($id) && exit(json_encode(array(0,'')));
        $list = M('CurriculumCate')->where(array('pid'=>$id,'status'=>1))->field('id,name')->order('ordid,id desc')->select();
        if($list){
            exit(json_encode(array(1,$list)));
        }else{
            exit(json_encode(array(0,'')));
        }
    }

    //注册获取验证码
    public function reg_send_sms(){
        $mobile = I('mobile','','trim');
        empty($mobile) && exit(json_encode(array(0,'请输入手机号')));
        (check_mobile($mobile) === false) && exit(json_encode(array(0,'手机号格式错误')));
        $only = M('Member')->where(array('mobile'=>$mobile))->count();
        ($only > 0) && exit(json_encode(array(0,'该手机号已注册')));
        $code = rand(100000,999999);
        $result = $this->sendSms($mobile,'1904612',('#code#').'='.urlencode((string)$code));
        if($result['code'] === 0){
            cookie('reg_data',array('mobile'=>$mobile,'code'=>$code),600);
            exit(json_encode(array(1,'短信已发送')));
        }else{
            exit(json_encode(array(0,'发送失败，请重试')));
        }
    }

    //注册获取验证码
    public function pwd_send_sms(){
        $mobile = I('mobile','','trim');
        empty($mobile) && exit(json_encode(array(0,'请输入手机号')));
        (check_mobile($mobile) === false) && exit(json_encode(array(0,'手机号格式错误')));
        $only = M('Member')->where(array('mobile'=>$mobile,'status'=>1))->count();
        empty($only) && exit(json_encode(array(0,'该手机号不存在或被禁止登录')));
        $code = rand(100000,999999);
        $result = $this->sendSms($mobile,'1904612',('#code#').'='.urlencode((string)$code));
        if($result['code'] === 0){
            cookie('pwd_data',array('mobile'=>$mobile,'code'=>$code),600);
            exit(json_encode(array(1,'短信已发送')));
        }else{
            exit(json_encode(array(0,'发送失败，请重试')));
        }
    }

    //验证是否登录
    public function pc_is_login(){
        $user_auth = session('user_auth');
        if($user_auth){
            echo 1;
        }else{
            echo 0;
        }
    }

    //退出登录
    public function sign_out(){
        session('user_auth',null);
        cookie('user_auth',null);
        $this->redirect('Index/index');
    }

}