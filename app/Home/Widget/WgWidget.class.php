<?php
namespace Home\Widget;
use Think\Controller;
class WgWidget extends Controller {

    //底部导航
    public function footer(){
        //导航列表
        $list = D('ArticleCate')->where(array('pid'=>0,'status'=>1))
            ->relation('self')
            ->field('id,name,url')
            ->order('ordid,id')
            ->select();

        $this->assign('list',$list);
        $this->display('Widget:footer');
	}
		
    //头部帮助中心
    public function nav(){
        //导航列表
        $info = D('ArticleCate')->where(array('id'=>13))
            ->relation('self')
            ->field('id,name,url')
            ->order('ordid,id')
            ->find();

        $this->assign('info',$info);
        $this->display('Widget:nav');
    }

    //头部登录状态
    public function login_status(){
        $uid = $_SESSION['user_auth']['id'];
        if($uid){
            $info = M('Member')->where(array('id'=>$uid))->field('realname')->find();
        }else{
            $info = null;
        }

        $this->assign('info',$info);
        $this->display('Widget:login_status');
    }

    //个人中心头部返回登录人姓名
    public function return_name(){
        $uid = $_SESSION['user_auth']['id'];
        $realnmae = M('Member')->where(array('id'=>$uid))->getfield('realname');
        echo $realnmae;
    }
}