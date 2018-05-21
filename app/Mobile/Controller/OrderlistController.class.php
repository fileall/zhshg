<?php

namespace Mobile\Controller;

class OrderlistController extends HomeController
{
    public function y_order()
    {
        $uid = is_login();
        $status = I('status','','trim');
        $array = ['0'=>'全部','1'=>'待付款','2'=>'待发货','3'=>'待收货','4'=>'待评价'];
        if(IS_AJAX){
            $type1 = $_POST['type1'];
            $oid = $_POST['oid'];
            if($type1 == 1){
                $re_d = M('order')->where(['id'=>$oid])->setField('status',6);
                $re_d && $this->ajaxReturn(array('status'=>1,'msg'=>'取消成功'));
                !$re_d && $this->ajaxReturn(array('status'=>0,'msg'=>'取消失败'));
            }
            if($type1 == 2){
                $re_d = M('order')->where(['id'=>$oid])->setField('status',4);
                $re_d && $this->ajaxReturn(array('status'=>1,'msg'=>'收货完成'));
                !$re_d && $this->ajaxReturn(array('status'=>0,'msg'=>'收货失败'));
            }
        $where['uid'] = $uid;
            $where['user_display'] = 0;
        $status = I('status','','trim');
        $status && $where['status'] = $status-1 ;
        if($where['status'] == 0) unset($where['status']);
        $count = M('order')->where($where)->count();
        $count = ceil($count/6);
        $Page = new \Think\Page($count,6);// 实例化分页类
                $list = M('order')
                    ->where($where)
                    ->field('zftype,coin_price_dk,id,uid,dingdan,status,total_amount,type,fruit_price,express_num')
                    ->order('add_time desc')
                    ->limit($Page->firstRow.','.$Page->listRows)
                    ->select();
        if($list != null){
        $ids = array_unique(array_column($list,'id'));
        $goods = M("Order_list")->where(array('oid'=>array('in',$ids)))->field("oid,item_id,attr_id,title,num,img,price")->select();
        foreach ($list as $k=>$v){
            foreach ($goods as $k1=>$v1) {
                if (false !== array_search($v['id'], $v1)) {
                    $list[$k]['goods'][$k1]['title'] = $v1['title'];
                    $list[$k]['goods'][$k1]['img'] = $v1['img'];
                    $list[$k]['goods'][$k1]['item_id'] = $v1['item_id'];
                    $list[$k]['goods'][$k1]['attr_id'] = $v1['attr_id'];
                    $list[$k]['goods'][$k1]['num'] = $v1['num'];
                    $list[$k]['goods'][$k1]['price'] = $v1['price'];
                    $list[$k]['goods'][$k1]['sli_price'] = $v1['price']/$v1['num'];//金果单价？
//                    $list[$k]['total_num'] = count($list[$k]['goods']);
                    $list[$k]['total_num'] = $goods[$k1]['oid'] == $list[$k]['id']? $list[$k]['total_num'] + $goods[$k1]['num']: $list[$k]['total_num'];
//                    $list[$k]['zftype'] = $v['zftype'];
//                    $list[$k]['coin_price_dk'] = $v['coin_price_dk'];//混合支付时银币抵扣
                }
            }
        }
        }
        $this->assign('list',$list);
        $data = $this->fetch('y_list_fetch');

        $this->assign('status',$status);
            $this->ajaxReturn(array('list'=>$data,'length'=>$count));//流加载
        }else{
            $caozuo = I('caozuo');
            if($caozuo == 1){

            }
            $this->assign('array',$array);
            $this->assign('status',$status);
            $this->display();
        }
    }
    //查看订单详情
    public function y_orderdetails()
    {

        $order_id = I('order_id','','trim');
        $type = I('zftype','','trim');
        $where = array('id'=>$order_id);
        $list = M('order')->where($where)->find();
        $goods = M("Order_list")->where(['oid'=>$list['id']])->field("id,oid,item_id,attr_id,title,num,img,price")->select();
        $count = 0;//统计订单商品总数量
        foreach ($goods as $k=>$v) {
            $goods[$k]['sli_price'] = $v['price']/$v['num'];
            $count += $v['num'];
        }
        $list['count'] = $count;
//        var_dump($list);die;
        $this->assign('type',$type);
        $this->assign('goods',$goods);
        $this->assign('list',$list);
        $this->display();
    }

    public function y_orderComment()
    {
        $order_id = I('oid','','trim');
        $goods = M("Order_list")->where(array('oid'=>$order_id))->field('item_id,img')->select();
        if($_POST){
            $data = $_POST['data'];
            $uid = is_login();
            $nickname = M('member')->where(array('id'=>$uid))->field('nickname')->find();
            $item_name = M('order_list')->where(array('oid'=>$data[0]['order_id']))->field('item_id,title')->select();
            foreach ($item_name as $key=>$value){
                $item_name[$value['item_id']] = $value['title'];
            }
            foreach($data as $k=>$v){
                M()->startTrans();
                  $arr = array(
                      'item_id'     =>$v['item_id'],
                      'item_name'   =>$item_name[$v['item_id']],
                      'order_id'    =>$v['order_id'],
                      'member_id'   =>$uid,
                      'nickname'    =>$nickname['nickname'],
                      'add_time'    =>time(),
                      'score'       => $v['comment'],
                      'status'      =>1,
                      'memos'       =>$v['msg']
                  );
                  $db = M('item_comment')->add($arr);
                  if($v['comment_img']){
                      foreach($v['comment_img'] as $k1=>$v1){
                          $arrImg[] = array(
                              'item_comment_id'  =>$db,
                              'url'              =>$v1,
                              'add_time'         =>time()
                          );
                      }
                      $db1 = M('item_comment_img')->addAll($arrImg);
                      if(!($db && $db1)){
                          M()->rollback();
                          $this->ajaxReturn(array('status'=>0,'msg'=>'评论失败！'));
                      }
                      $arrImg = array();
                  }
                  if($db){
                      M()->commit();
                  }else{
                      M()->rollback();
                      $this->ajaxReturn(array('status'=>0,'msg'=>'评论失败！'));
                  }
            }
            M('order')->where(array('id'=>$data[0]['order_id']))->setField('status',5);
               $this->ajaxReturn(array('status'=>1,'msg'=>'评论成功！'));
        }
        $this->assign('order_id',$order_id);
        $this->assign('goods',$goods);
        $this->display();
    }

    public function sub_image()
    {
        //上传图片
        if (!empty($_FILES)){

            $thumb = [
                'width' => C('pin_item_bimg.width'),
                'height' => C('pin_item_bimg.height'),
                'suffix' => '_b',
            ];
            $files_path = $this->_upload($_FILES['file'],'comment_img/'.date('ym'),$thumb);
             if($files_path['error'] == 0){
                 $path =  date('ym').'/'.$files_path['info'][0]['savename'];
                 $this->ajaxReturn(array('path'=>$path));
             }
        }

    }





































}?>