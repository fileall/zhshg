<?phpnamespace Admin\Controller;/** * 消费详情类 * Class AccountController * @package Admin\Controller */class MemberRechargeController extends AdminCoreController{    public function _initialize()    {        parent::_initialize();        $this->_mod=D('MemberRecharge');        $this->set_mod('MemberRecharge');        $this->_member=D('Member');    }    public function index() {        $map = $this->_search();        $mod = $this->_mod;        !empty($mod) && $this->_list($mod, $map);        $this->display();    }    public function _search()    {        $map=array('dingdan'=>'0','item_type'=>1,'status'=>2,'type'=>2);        ($uid = I('uid','','intval'))   && $map['uid']          = $uid;//用户或商家id        ($type = I('type','','intval'))   && $map['type']          = $type;        ($time_start = I('time_start')) && $map['add_time'][]   = ['egt',strtotime($time_start)];        ($time_end = I('time_end'))     && $map['add_time'][]   = ['elt',strtotime($time_end) + 86399];        $map['member_id']=['not in',[843,1,2,3,4,306,321,325]];        $this->assign('search',[            'time_start'    => $time_start,            'time_end'      => $time_end,        ]);        !empty($uid) && $this->assign('uid',$uid);        !empty($type) && $this->assign('type',$type);        return $map;    }            public function _after_list($list) {        $uids=array_column($list,'member_id');        if (!empty($list)){            $uids&& $member=$this->_member->where(['id'=>['in',$uids]])->getField('id,nickname,mobile');        }   		$this->assign('member',$member);        return $list;    }    //元宝充值记录    public function export()    {        ob_end_clean();        $map = $this->_search();        $map['member_id']=['not in',[843,1,2,3,4,306,321,325]];        $list = $this->_mod->where($map)->select();        if($list){            $member_ids=array_unique(array_column($list,'member_id'));            $member_ids&&$member=M('member')->where(['id'=>['in',$member_ids]])->getField('id,realname,mobile,nickname');        }        $data = array();        foreach ($list as $k => $v) {            $data[$k]['nickname']= $member[$v['member_id']]['nickname']; //会员名            $data[$k]['realname']= $member[$v['member_id']]['realname']; //会员名            $data[$k]['mobile'] =  $member[$v['member_id']]['mobile'];            $data[$k]['memos']= $v['memos']; //备注            $data[$k]['totalprices']= $v['totalprices']; //开户名            $data[$k]['add_time'] = date('Y-m-d',$v['add_time']); //时间        }        $headArr = array();        $headArr[] = '昵称';        $headArr[] = '会员名';        $headArr[] = '会员电话';        $headArr[] = '备注';        $headArr[] = '金额';        $headArr[] = '时间';        $filename="会员元宝充值记录";        getExceltjab($filename, $headArr, $data);    }}