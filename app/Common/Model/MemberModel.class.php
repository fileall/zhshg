<?php
namespace Common\Model;
use Think\Model\RelationModel;
class MemberModel extends RelationModel {
    protected $_link = array(
//        'identity' => array(
//            'mapping_type'  => self::HAS_ONE,
//            'class_name'    => 'Identity',
//            'mapping_name'      =>  'identity',
//        ),
//        'vip' => array(
//            'mapping_type'  => self::HAS_ONE,
//            'class_name'    => 'MemberVip',
//            'foreign_key'   => 'member_id',
//            'mapping_name'      =>  'vip',
//        ),
//         //自关联
//        'relation' => array(
//            'mapping_type'  => self::BELONGS_TO,
//            'class_name'    => 'Member',
//            'parent_key'      =>  'relation_id',
//			'mapping_fields'      =>  'mobile',
//			'as_fields' => 'mobile:relation_mobile',
//        ),
//        //升级时间
//        'sjjl' => array(
//		    'mapping_type'  => self::HAS_ONE,
//		    'class_name'    => 'MemberRecharge',
//		    'foreign_key'   => 'member_id',
//	        'mapping_name'  => 'sjjl',
//		    'condition' => 'item_type = 5 and status = 2 and dingdan != 0',
//		    'mapping_order' => 'add_time desc',
//		    'mapping_limit'=>1,
//			'as_fields' => 'add_time:sj_time',
//
//		),
//
//        'invite' => array(
//            'mapping_type'  => self::BELONGS_TO,
//            'class_name'    => 'Member',
//            'mapping_name'      =>  'invite',
//            'foreign_key'   => 'invite_id',
//            //'as_fields' => 'nickname:invite_name',
//        ),
//         'yhq' => array(
//            'mapping_type'  => self::HAS_MANY,
//            'class_name'    => 'Yhq',
//            'foreign_key'   => 'uid',
//            'mapping_name'  => 'yhq',
//            'condition' => 'status = 0 ',
//            'mapping_fields' => 'count(*) as yhq_total',
////          'as_fields' => 'count(*)',
////          'mapping_order' => 'loan_total desc',
//        ),
//        'order' => array(
//            'mapping_type'  => self::HAS_MANY,
//            'class_name'    => 'Order',
//            'foreign_key'   => 'uid',
//            'mapping_name'  => 'order',
////          'condition' => 'status = 0 ',
//            'mapping_fields' => 'count(*) as order_total',
////          'as_fields' => 'count(*)',
////          'mapping_order' => 'loan_total desc',
//        ),
//            'address' => array(
//            'mapping_type'  => self::HAS_MANY,
//            'class_name'    => 'MemberGoodsaddress',
//            'foreign_key'   => 'member_id',
//            //'mapping_name'  => 'address',
////          'condition' => 'status = 0 ',
//            //'mapping_fields' => 'count(*) as order_total',
////          'as_fields' => 'count(*)',
////          'mapping_order' => 'loan_total desc',
//        ),
//            'MemberCard' => array(
//            'mapping_type'  => self::HAS_MANY,
//            'class_name'    => 'MemberCard',
//            'mapping_name'  => 'MemberCard',
//            'foreign_key'   => 'member_id',
//             'as_fields' => 'card,face_value,add_time,price',
//        ),
    );

    //自动验证
    protected $_validate = array(
        array('mobile','','该手机号码已注册', 0, 'unique'), //被占用
//        array('relation_mobile','check_relation','请正确填写推荐人111', 0, 'function'), //推荐人
//        array('code','require', '手机验证码不能为空',0,'',1),
//        array('code','check_code','手机验证码错误或已过期',0, 'callback',1),
//        array('address','require','地址不能为空'),
//        array('confirm_password', 'password', '两次输入的密码不一致', 0, 'confirm'), //比较两次密码
        array('password', 'require','密码不能为空',1,'',1), // 自定义函数验证密码格式
        array('password','check_pwd','密码格式不正确',2,'function'), // 自定义函数验证密码格式
    );
    
    //自动完成
    protected $_auto = array (
        array('password','st_md5',3,'function') ,
        array('paypassword','st_md5',3,'function') ,
        array('reg_ip','get_client_ip',1,'function'),
        array('reg_time','time',1,'function'),
        array('last_login_time','time',3,'function'),
        array('last_login_ip','get_client_ip',3,'function'),
    );

    //验证接收验证码手册
    protected function check_mobile(){
        if($_POST['code']){
            $reg_data = cookie('reg_data');
            if($reg_data['mobile'] != $_POST['mobile']){
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }
    }

    //验证手机验证码
    public function check_code(){
        if($_POST['code']) {
            $reg_data = cookie('reg_data');
            if ($reg_data['code'] != $_POST['code']) {
                return false;
            } else {
                return true;
            }
        }else{
            return true;
        }
    }



    public function ajax_check_email($name,  $id){
        $result = $this->where(array('email'=>$name,'id'=>array('neq',$id)))->getField('id');
        return $result;
    }
    
    
    
      //验证用户名
    public function check_acc($condition,$password){
       // return 55;
        if($condition['mobile'] == ''||$password == '') return false;
        $res = $this->where($condition)->find();
        if($res){
            if($res['password'] == $password){
                return $res; //登录成功
            }else{
                return 2; //密码错误
            }
        }else{
            return 3; // 用户名错误
        }
    }
     //更改最后登录时间 登录次数 ip
    public function set_ip($mobile){
        if($mobile == '') return false;
        
        $user = array();
        $user['last_login_time'] = time();
        $user['last_login_ip'] = get_client_ip();
        $user['login'] = array('exp','login+1');
        $res2 = $this->where(array('mobile'=>$mobile))->setField($user);
        return ($res2>0);
    }
 //获取用户信息
    public function get_info($id){
        if($id == '')return false;
        $res = $this->where(array('id'=>$id))->find();
        
        return $res;
    }

    //设置用户的字段值
    public function set_userinfo($field,$value,$uid){
        if($field=='' || $value=='' || $uid=='' || !is_numeric($uid)) return false;
        $res = $this->where(array('id'=>$uid))->setField(array($field => $value));
        return $res;
    }

    /**
     * 修改用户信息
     * @access public
     * @param int $id 用户id
     * @param array $arr 数组 需要修改的信息
     * @return 返回修改结果
     */
    public function set_user($id,$arr)
    {
        if ($id === '' || !is_numeric($id) || $arr === '' || !is_array($arr)) return false;
        return $this->where('id='.$id)->save($arr);
    }

    
    //搜索
    public function get_names($keyword = ''){
        if($keyword == "") return false;
        $where['_string'] = "mobile like '%".$keyword."%' or nickname like '%".$keyword."%'";
        $list = $this->where($where)->field('id,mobile,nickname')->select();
        return $list;
    }

    /**
     * 获取多个用户
     * @param array $user 用户id集合
     * @return bool|false|mixed|\PDOStatement|string|\think\Collection 返回用户信息
     */
    public function get_more_user($user = array()){
        if (count($user) == 0 || !is_array($user)) return false;
        return $this->field('id,mobile,nickname,avatar')->where($user)->select();
    }


}
