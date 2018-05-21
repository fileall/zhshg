<?php
namespace Admin\Controller;
class OrderController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Order');
        $this->set_mod('Order');
    }

    //搜索
    protected function _search() {
        $map = array();
        ($time_start = I('time_start','', 'trim')) && $map['add_time'][] = array('egt', strtotime($time_start));
        ($time_end = I('time_end','', 'trim')) && $map['add_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));
        ($status = I('status','', 'trim')) && $map['status'] = $status;
        ($keyword = I('keyword','', 'trim')) && $map['_string'] = "(dingdan like '%".$keyword."%' or uid in(select id from jrkj_member where mobile like '%".$keyword."%'))";

        //订单支付方式  支付方式1金元宝2金果3金元宝+银币
        ($zftype = I('zftype'))&&$map['zftype'] = $zftype;
        //订单状态1=待支付,2=待发货,3=待收货，4=待评价, 5=已评价 6交易已取消
        ($status = I('status','','intval')) && $map['status'] = $status;
        //$is_tk1退款界面2接单界面
        ($is_tk = I('is_tk','','intval')) && $map['is_tk'] = $is_tk;
        if($is_tk){
            if($is_tk==1){
                $tk_status= I('tk_status','','intval');
                $map['tk_status'] = $tk_status?$tk_status:['neq','0'];
            }
            ($is_tk==2)&&$map['status'] = ['in','2,3'];
        }

        $this->assign('search', array(
            'time_start' => $time_start,
            'time_end' => $time_end,
            'status'  => $status,
            'keyword' => $keyword,
            'zftype'        => $zftype,
            'is_tk' 		=> $is_tk,
            'tk_status' 	=> $tk_status,
        ));

        return $map;
    }

    public function _after_list($list)
    {
        if(!empty($list)){
            $uids=array_unique(array_column($list,'uid'));
            $uids&&$member_list=M('member')->where(['id'=>['in',$uids]])->getField('id,nickname,mobile');

//             $this->assign('order_type',C('order_type'));
             $this->assign('member_list',$member_list);
        }


        return $list;
    }

	//发货
    public function send(){
        if(IS_POST){
            $data = I('post.');
            $data['status']=3;//订单状态1=待支付,2=待发货,3=待收货，4=待评价, 5=已评价 6交易已取消

            if (false !== $this->_mod->save($data)) {
                $this->ajax_return(1, L('operation_success'), '', 'edit');
            } else {
                $this->ajax_return(0, L('operation_failure'));
            }
        } else{
            $id = I('id','','intval');
            $this->assign('id',$id);

            $response = $this->fetch();
            $this->ajax_return(1,'',$response,'examine');
        }
    }
	//订单 打印
	public function order_print(){
		$this->display();
	}

	//订单 详情
	public function detail(){
        $mod = D($this->_name);
        $pk = $mod->getPk();
        $id = I($pk, 'intval');
        $info = $mod->find($id);

        $oid=$info['id'];
        $uid=$info['uid'];
        $user=M('member')->where(['id'=>$uid])->field('id,nickname,realname,mobile')->find();//会员
//        $info['time']=M('order_time')->where(['oid'=>$oid,'type'=>1])->order('id asc')->select();//订单时间
        $info['order_list']=M('order_list')->where(['oid'=>$oid])->select();//订单列表

        $this->assign('user',$user);
        $this->assign('info',$info);
        if (IS_AJAX) {
            $response = $this->fetch();
            $this->ajax_return(1,'',$response,'edit');
        }else{
            $this->display();
        }
	}

}
