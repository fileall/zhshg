<?php
namespace Admin\Controller;

class CouponCateController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Yhq_cate');
        $this->set_mod('Yhq_cate');
    }
	
    public function _before_index() {
        $p = I('p',1,'intval');
		$r=M('item_cate')->where(array('pid'=>0,'status'=>1))->select();
		
		$a=M('yhq_cate')->select();
		foreach($a as $k=>$v){
			if($v['endtime']&&($v['endtime']+24*60*60)<time()){
				M('yhq_cate')->where(array('id'=>$v['id']))->setField('status',2);
			}
		}
		
		$this->assign('r',$r);
        $this->assign('p',$p);
    }
	
	
    protected function _search() {
        $map = array();
        ($time_start = I('time_start','', 'trim')) && $map['starttime'][] = array('egt', strtotime($time_start));
        ($time_end = I('time_end','', 'trim')) && $map['endtime'][] = array('elt', strtotime($time_end)+(24*60*60-1));
        ($status = I('status','', 'trim')) && $map['status'] = $status;
		($price = I('price','', 'trim')) && $map['price'] = $price;
		($sncode = I('sncode','', 'trim')) && $map['sncode'] = array('like', '%'.$sncode.'%');
        ($keyword = I('keyword','', 'trim')) && $map['title'] = array('like', '%'.$keyword.'%');
        $this->assign('search', array(
            'time_start' => $time_start,
            'time_end' => $time_end,
            'cate_id' => $cate_id,
            'status'  => $status,
            'keyword' => $keyword,
        ));
		
        return $map;
    }


	//优惠券生成
	public function coupon(){
		$yhq=M("yhq");
		$act=I("get.act");
		if($act=='yhq'){
			$data['title']=I("post.title");
			$data['price']=I("post.price");
			$data['integral']=I("post.integral");
			$data['minprice']=I("post.minprice");
			$data['apply']=I("post.apply");
			//$quan=I('quan');
			$fa=I('fa');
//			if($quan){
				$data['apply']=99;
//			}else if(!$quan&&$fa){
//				$data['apply']=json_encode($fa);
//			}else{
//				$this->error('请选择使用范围！');
//			}
			$data['introductions']=I("post.introductions");
			($starttime = I("post.starttime")) && $data['starttime'] = strtotime($starttime);	
			($endtime = I("post.endtime")) && $data['endtime'] = strtotime($endtime);	
				
			if($data['price']&&$data['integral']){
				if($this->_mod->add($data)){
					$this->success('添加成功！');	
				}else{
					$this->error('操作失败！');
				}  
			}else{
				$this->error('面值或兑换积分不能为空！');
			}
		}else{
			$this->display();
		}
		
	}


}