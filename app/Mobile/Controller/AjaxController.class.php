<?php
namespace Mobile\Controller;
class AjaxController extends HomeController {
    public function _initialize() {
        parent::_initialize();
        $this->member=D('Member');
    }

	
	//是否实名0
	public function test_realname(){
		$member= $this->member->find(is_login());
		(!$member['realname'])&& exit(json_encode(array('status'=>0,'msg'=>'请先实名认证')));
	}

    //流加载 币种流水0
    public function ajax_bz_liu(){

        $page = I('page');
        //总页数
        $count=D('MemberRecharge')->where(array('dingdan'=>0,'member_id'=>is_login(),'item_type'=>1))
            ->order('add_time desc')->count();

        $pages =   ceil($count/ 4); //上取整    总条数/每页条数
        $list_aj[0] = $pages;//总页数
        //每页数据
        $list_ajs=D('MemberRecharge')->where(array('dingdan'=>0,'member_id'=>is_login(),'item_type'=>1))
            ->order('add_time desc')->limit(($page-1) * 4 , 4)->select();

        $this->assign('a',$list_ajs);
        $a=$this->fetch('w_bz_liu');

        $list_aj[1] = $a;//数据

        echo  json_encode($list_aj);
    }


  
}