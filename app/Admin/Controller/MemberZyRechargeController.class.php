<?php
namespace Admin\Controller;
class MemberZyRechargeController extends AdminCoreController {
	public function _initialize()
    {
        parent::_initialize();
		$this->list_relation=false;
        $this->_mod = D('MemberZyRecharge');
        $this->set_mod('MemberZyRecharge');
    }
    
     /**
     * 列表页面
     */
    public function index() {

        $map = array();
		if(I('is_xq')==1){
			($member_id=I('id'))&&$map['member_id']=$member_id;
	    	cookie('zy_recharge_member_id',$member_id);
		}
    	$map['member_id']=cookie('zy_recharge_member_id');
        ($time_start=I('time_start'))&&$map['add_time'][]=array('egt',strtotime($time_start));
		($time_end=I('time_end'))&&$map['add_time'][]=array('elt',strtotime($time_end)+24*60*60);
//     ($keywords = I('keywords','', 'trim')) && $map['_string'] = " member_id in (select id from ".C('DB_PREFIX')."member where realname like '%".$keywords."%' or mobile like '%".$keywords."%')";
    	 $this->assign('search', array(
            'keywords' => $keywords,
            'time_start' =>$time_start,
            'time_end'  =>$time_end
        ));
        
        
		$this->list_relation=true;
    	
        $mod = $this->_mod;
        !empty($mod) && $this->_list($mod, $map);
        $this->display();
    }



}