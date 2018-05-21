<?php
namespace Mobile\Controller;
class TestController extends  HomeController
{

    public function _initialize()
    {
        parent::_initialize();
        $uid=is_login();

        $this->_member=D('Member');
        $this->_merchant=D('Merchant');
        $this->_merchant_cate=D('MerchantCate');
        $this->_item = D('Item');

        $merchant=$this->_merchant->where(['uid'=>$this->uid])->find();
        $this->assign('merchant',$merchant);
    }

    public function index(){
//$aaa=M('account')->where(['id'=>['in','734,733,732,735']])->delete();
//var_dump($aaa);
//die;
        $this->display();
    }



    //猜你喜欢
    public function guesslike_list(){
        $item_modle=M('item');

        $count=$item_modle->where(array('status'=>1))->order('ordid asc,id desc')->count();//总条数 255?

        $page=I('page');//当前页
        $pages =   ceil($count/ 8); //上取整    总条数/每页条数
        $list_aj[0] = $pages;//总页数
        $list_aj1=$item_modle->where(array('status'=>1))->order('ordid asc,id desc')->limit($page * 8 , 8)->select(); //每页数据
        foreach($list_aj1 as $kk=>$vv){
            $list_aj1[$kk]['href']=U('index/detail',array("id"=>$vv['id']));
            $list_aj1[$kk]['title']=(strlen($vv['title']) > 15)?mb_substr($vv['title'],0,15,"UTF-8").'..':$vv['title'];
            $list_aj1[$kk]['sales']=$vv['sales']?$vv['sales']:0;
            $list_aj1[$kk]['img']=attach($vv['img'],'item');
        }
        $list_aj[1]=$list_aj1;
        echo  exit(json_encode($list_aj));

    }

    public function index0511()
    {

        $item_modle=M('item');
        $ad_modle=M('ad');

        //首页轮播
        $lux = $ad_modle->where(array('board_id'=>22,'status'=>1))->order('ordid asc,id desc')->limit(3)->select();
        //金果商城入口
        $new_user =$ad_modle->where(array('board_id'=>23,'status'=>1))->order('ordid asc,id desc')->find();
        //右上角消息提醒
        $uid=is_login();$is_read=false;//表示没有未读
        $new_list=M('Article')->where('cate_id=23')->select();
        foreach($new_list as $k=>$v){
            $am=D('ArticleMember')->where(array('aid'=>$v['id'],'uid'=>$uid))->find();
            (!$am)&& $is_read=true;
        }

        //消息推送
        $active_go = M('Article')->where(array('cate_id'=>23,'status'=>1))->order('ordid asc,id desc')->select();

        //发现好货:标签fx
        $fl[0]=$item_modle->where('fx=1 and status=1')->field('id,img')->order('ordid asc,update_time desc')->limit(2)->select();
        //臻会买:标签zhm
        $fl[1]=$item_modle->where('zhm=1 and status=1')->field('id,img')->order('ordid asc,update_time desc')->limit(2)->select();
        //新品首发:时间倒序
        $fl[4]=$item_modle->where(array('status'=>1))->field('id,img')->order('ordid asc,id desc')->limit(2)->select();
        //臻怡家精选:标签jx
        $fl[3]=$item_modle->where('jx=1 and status=1')->field('id,img')->order('ordid asc,update_time desc')->limit(2)->select();
        //秒杀展示：秒杀活动待开放0
        $favour =M('item')->where(array('status'=>1))
            ->limit(6)->field('id,title,price,img,hits')->order('id desc')->select();
        $this->assign('favour',$favour);

        //获取微信分享必要参数
        $jssdk = new \Mobile\Org\Jssdk(C("WX_CONFIG.appid"),C("WX_CONFIG.appsecret"));
        $js = $jssdk->GetSignPackage();
        $this->assign('js',$js);

        $this->assign('lux',$lux);
        $this->assign('new_user',$new_user);
        $this->assign('is_read',$is_read);
        $this->assign('active_go',$active_go);
        $this->assign('fl',$fl);
        $this->display();
    }





}
?>