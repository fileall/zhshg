<?php

namespace Home\Controller;

class MemberController extends HomeController {

    public function _initialize() {

        parent::_initialize();

        $this->uid = $this->checklogin();

        $this->Member = D('Member');

    }


	public function test() {
		$this->display();
    }
	
    //个人中心首页
    public function index(){ 
        //详情
        $info = $this->Member->find($this->uid);

        empty($info['realname']) && $this->redirect('Member/self');

        //订单列表

        $type = I('type',1,'intval');

        $list = M('CurriculumOrder')->alias('c')

            ->join('__CURRICULUM__ u on c.c_id = u.id')

            ->field('c.*,u.cate_id c_cate_id,u.title,u.img,u.type c_type,(select count(*) from jrkj_curriculum_ext where c_id = c.c_id) nums')

            ->where(array('c.uid'=>$this->uid,'c.status'=>array('neq',5)))

            ->order('c.id desc')

            ->limit(3)

            ->select();

        //dump($list);



        $this->assign('info',$info);

        $this->assign('list',$list);

        $this->display();

    }



    //课程未支付订单去支付

    public function go_pay(){

        $id = I('id','','intval');

        session('set_order_id',$id);

        $this->redirect('Order/shopCart');

    }



    //个人资料

    public function self(){

        if(IS_POST){

            $data = I('post.');

            $data['id'] = $this->uid;

            $result = $this->Member->save($data);

            if($result){

                exit(json_encode(array(1,'保存成功')));

            }elseif ($result === false){

                exit(json_encode(array(0,'保存失败，请重试')));

            }else{

                exit(json_encode(array(0,'保存成功')));

            }

        }else{

            $info = $this->Member->find($this->uid);

            //获取分类

            $spid = M('CurriculumCate')->where(array('id'=>$info['cate_id']))->getField('spid');

            if( $spid==0 ){

                $spid = $info['cate_id'];

            }else{

                $spid .= $info['cate_id'];

            }



            //动态关闭表单令牌

            C('TOKEN_ON',false);

            $this->assign('info',$info);

            $this->assign('selected_ids',$spid);

            $this->display();

        }

    }



    //上传头像

    public function upload_avatar(){

        $base64 = I('str','','trim');

        $date_dir = date('ym/d/');

        if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64, $result)){

            //随机数

            $str = substr(time(),-5).substr(microtime(),2,5);

            $new_file=$str.".{$result[2]}";

            $this->_upload($new_file,'avatar/'.$date_dir,  array());

            $img= "./data/attachment/avatar/".$date_dir.$str.".{$result[2]}";



            if(file_put_contents($img, base64_decode(str_replace($result[1], '', $base64)))){

                $avatar = $this->Member->where(array('id'=>$this->uid))->setField('avatar',$img);

                if($avatar){

                    exit(json_encode(array(1,$img)));

                }else{

                    exit(json_encode(array(0,'上传失败，请重试')));

                }

            }else{

                exit(json_encode(array(0,'上传失败，请重试')));

            }

        }

    }



    //账户安全

    public function security(){

        if(IS_POST){



        }else{

            $info = $this->Member->find($this->uid);



            //动态关闭表单令牌

            C('TOKEN_ON',false);

            $this->assign('info',$info);

            $this->display();

        }

    }



    //修改密码

    public function edit_pwd(){

        if(IS_POST){

            $data = I('post.');

            //用户详情

            $info = $this->Member->find($this->uid);

            ($info['password'] != st_md5($data['old_pwd'])) && exit(json_encode(array(0,'原始密码错误')));

            (check_pwd($data['new_pwd']) === false) && exit(json_encode(array(0,'新密码格式错误')));

            ($data['con_pwd'] != $data['new_pwd']) && exit(json_encode(array(0,'两次密码输入不一致')));

            if($this->Member->where(array('id'=>$this->uid))->setfield('password',st_md5($data['new_pwd']))){

                session('user_auth',null);

                cookie('user_auth',null);

                exit(json_encode(array(1,'修改成功')));

            }else{

                exit(json_encode(array(0,'修改失败，请重试')));

            }

        }else{

            //动态关闭表单令牌

            C('TOKEN_ON',false);

            $this->display();

        }

    }



    //修改手机

    public function edit_mobile(){

        if(IS_POST){

            $code = I('code','','trim');

            ($code != cookie('old_mobile_code')) && exit(json_encode(array(0,'手机验证码错误或已过期')));

            cookie('old_mobile_code',null);

            exit(json_encode(array(1,'验证通过')));

        }else{

            $info = $this->Member->find($this->uid);



            //动态关闭表单令牌

            C('TOKEN_ON',false);

            $this->assign('info',$info);

            $this->display();

        }

    }



    //验证新手机

    public function new_mobile(){

        if(IS_POST){

            $code = I('code','','trim');

            $mobile = I('mobile','','trim');

            $data = cookie('new_mobile_data');

            ($mobile != $data['mobile']) && exit(json_encode(array(0,'接收验证码手机已更换')));

            ($code != $data['code']) && exit(json_encode(array(0,'手机验证码错误或已过期')));

            if($this->Member->where(array('id'=>$this->uid))->setfield('mobile',$mobile)){

                cookie('new_mobile_data',null);

                session('user_auth',null);

                cookie('user_auth',null);

                exit(json_encode(array(1,'修改成功')));

            }else{

                exit(json_encode(array(0,'修改失败，请重试')));

            }

        }else{

            $info = $this->Member->find($this->uid);



            //动态关闭表单令牌

            C('TOKEN_ON',false);

            $this->assign('info',$info);

            $this->display();

        }

    }



    //图形验证码

    public function verify_code() {

        ob_clean();

        $config =    array(

            'codeSet'     =>    '0123456789',

            'fontSize'    =>    25,    // 验证码字体大小

            'length'      =>    4,     // 验证码位数

            'useNoise'    =>    false, // 关闭验证码杂点

            'useCurve'    =>    false, // 关闭验证码杂点

        );

        $Verify =     new \Think\Verify($config);

        $Verify->entry();

    }



    //原手机获取短信验证码

    public function get_code(){

        $info = $this->Member->find($this->uid);

        $verify = new \Think\Verify();

        $code = I('verify_img','','trim');

        $result = $verify->check($code, '');

        ($result === false) && exit(json_encode(array(0,'图形验证码输入错误')));

        $code = rand(100000,999999);

        $result = $this->sendSms($info['mobile'],'1904612',('#code#').'='.urlencode((string)$code));

        if($result['code'] === 0){

            cookie('old_mobile_code',$code,600);

            exit(json_encode(array(1,'短信已发送')));

        }else{

            exit(json_encode(array(0,'发送失败，请重试')));

        }

    }



    //新手机获取短信验证码

    public function new_get_code(){

        $info = $this->Member->find($this->uid);

        $verify = new \Think\Verify();

        $code = I('verify_img','','trim');

        $mobile = I('mobile','','trim');

        $result = $verify->check($code, '');

        ($result === false) && exit(json_encode(array(0,'图形验证码输入错误')));

        ($mobile == $info['mobile']) && exit(json_encode(array(0,'新手机不能与绑定手机相同')));

        $code = rand(100000,999999);

        $result = $this->sendSms($mobile,'1904612',('#code#').'='.urlencode((string)$code));

        if($result['code'] === 0){

            cookie('new_mobile_data',array('mobile'=>$mobile,'code'=>$code),600);

            exit(json_encode(array(1,'短信已发送')));

        }else{

            exit(json_encode(array(0,'发送失败，请重试')));

        }

    }



    //课程订单

    public function curriculum_order(){

        //订单类型

        $type = I('type',1,'intval');

        $status = I('status','','intval');

        $order = D('CurriculumOrder');

        //监听家庭教育服务开通时间

        if($type == 2){

            $order->monitor_open_time($this->uid);

        }

        //处理查询条件

        $map = array();

        $map['c.uid'] = $this->uid;

        $map['c.type'] = $type;

        if($status){

            $map['c.status'] = $status;

        }else{

            $map['c.status'] = array('neq',5);

        }

        ($time_start = I('time_start','', 'trim')) && $map['c.add_time'][] = array('egt', strtotime($time_start));

        ($time_end = I('time_end','', 'trim')) && $map['c.add_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));

        if($keywords = I('keywords','', 'trim')){

            $where['c.order_sn'] = array('like', '%'.$keywords.'%');

            $where['_string'] = "c.c_id in(select id from jrkj_curriculum where title like '%".$keywords."%')";

            $where['_logic'] = 'OR';

            $map['_complex'] = $where;

        }



        //获取所有订单各状态订单数量

        $count_nums = $order->where(array('uid'=>$this->uid,'type'=>$type,'status'=>array('neq',5)))

            ->field('status,count(id) nums')

            ->group('status')

            ->select();

        $count_list = array();

        foreach ($count_nums as $v){

            $count_list[$v['status']] = $v['nums'];

        }



        //分页

        $count = $order->alias('c')->where($map)->count();

        $Page = new \Think\Page($count,5);

        $show = $Page->show();

        //列表

        $list = $order->alias('c')

            ->join('__CURRICULUM__ u on c.c_id = u.id')

            ->field('c.*,u.cate_id c_cate_id,u.title,u.img,u.type c_type,u.cycle,(select count(*) from jrkj_curriculum_ext where c_id = c.c_id) nums')

            ->where($map)

            ->order('c.id desc')

            ->limit($Page->firstRow.','.$Page->listRows)

            ->select();

        //dump($count_list);



        //动态关闭表单令牌

        C('TOKEN_ON',false);

        $this->assign('list',$list);

        $this->assign('page',$show);

        $this->assign('type',$type);

        $this->assign('count_list',$count_list);

        $this->assign('status',$status);

        $this->assign('time_start',$time_start);

        $this->assign('time_end',$time_end);

        $this->assign('keywords',$keywords);

        $this->display();

    }



    //老师课堂评价

    public function curriculum_order_evaluate(){

        $co = M('CurriculumOrder');

        if(IS_POST){

            $data = I('post.');

            //验证

            empty($data['content']) && exit(json_encode(array(0,'请输入评论内容')));

            $info = $co->where(array('id'=>$data['o_id'],'uid'=>$this->uid,'status'=>3))->find();

            empty($info) && exit(json_encode(array(2,'非法提交')));

            $evaluate = M('CurriculumEvaluate');

            if (!$evaluate->autoCheckToken($_POST)){

                exit(json_encode(array(2,'重复提交')));

            }

            //必填字段

            $data['c_id'] = $info['c_id'];

            $data['uid'] = $this->uid;

            $data['add_time'] = time();

            //事务

            $evaluate->startTrans();

            //添加评论

            $e_id = $evaluate->add($data);

            //修改订单状态

            $set_co = $co->where(array('id'=>$data['o_id']))->setfield('status',4);



            if($e_id && $set_co){

                //成功

                $evaluate->commit();

                exit(json_encode(array(1,'评论成功')));

            }else{

                //失败

                $evaluate->rollback();

                exit(json_encode(array(0,'操作失败，请重试')));

            }

        }else{

            $id = I('id','','intval');

            $info = $co->where(array('id'=>$id,'uid'=>$this->uid,'status'=>3))->find();

            empty($info) && $this->error('非法访问');

            $this->assign('id',$id);

            $this->display();

        }

    }



    //课程订单详情

    public function curriculum_order_detail(){

        $id = I('id','','intval');

        $info = M('CurriculumOrder')->alias('c')

            ->join('__CURRICULUM__ u on c.c_id = u.id')

            ->join('__ADMIN__ a on u.teacher_id = a.id')

            ->join('__MEMBER__ m on c.uid = m.id')

            ->join('__CURRICULUM_CATE__ cc on m.cate_id = cc.id')

            ->field('c.*,u.cate_id c_cate_id,u.title,u.img,u.type c_type,a.name,m.cate_id,m.realname,m.mobile,cc.spid,(select count(*) from jrkj_curriculum_ext where c_id = c.c_id) nums')

            ->where(array('c.id'=>$id,'c.uid'=>$this->uid,'c.status'=>array('neq',5)))

            ->find();



        //获取课程阶段情况

        $cate_info = explode('|',rtrim($info['spid'],'|'));

        unset($cate_info[0]);

        $cate_info[] = $info['cate_id'];

        foreach ($cate_info as $k=>$v){

            $info['cate'][] = M('CurriculumCate')->where(array('id'=>$v))->getfield('name');

        }

        //dump($info);



        $this->assign('info',$info);

        $this->display();

    }



    //取消课程订单

    public function cancel_curriculum_order(){

        $id = I('id','','intval');

        if(M('CurriculumOrder')->where(array('id'=>$id,'uid'=>$this->uid,'status'=>1))->setfield('status',5)){

            echo 1;

        }else{

            echo 0;

        }

    }



    //我的课程

    public function my_curriculum(){

        //调用百度云VOD

        $vod = $this->baidu_cloud_vod();

        $order = D('CurriculumOrder');

        $order->monitor_open_time($this->uid);



        //分类

        $list = M('CurriculumCate')->alias('a')

            ->join('__CURRICULUM__ c on a.id = c.cate_id')

            ->where(array('a.pid'=>2,'a.status'=>1))

            ->field('a.id,a.name,c.id c_id,c.cycle')

            ->order('a.ordid,a.id')

            ->select();



        $ext = M('CurriculumExt');

        foreach ($list as $k=>$v){

            $info = $order->where(array('c_id'=>$v['c_id'],'uid'=>$this->uid,'status'=>array('neq','1,5'),'is_check'=>3))

                ->field('id,c_id,is_check,check_time')

                ->find();

            //验证服务周期

            if($info){

                //服务结束时间

                $end_time = strtotime("+{$v['cycle']} months", $info['check_time']);

                if($end_time < time()){

                    //已经到了结束时间

                    $order->where(array('id'=>$info['id']))->setfield('is_check',4);

                    unset($info);

                }else{

                    //开始服务天数

                    $day = intval((time()-$info['check_time'])/86400);

                    if($day <= C('pin_grant_day')){

                        //小于一周

                        $limit = 1;

                    }else{

                        $limit = intval($day/C('pin_grant_day'));

                    }

                    //视频

                    $vod_list = $ext->where(array('c_id'=>$v['c_id'],'status'=>1))

                        ->field('id,ext_name,media_id')

                        ->order('ordid,id')

                        ->limit($limit)

                        ->select();

                    //获取媒资信息

                    foreach ($vod_list as $kk=>$vv){

                        $media = object_to_array($vod->getMediaDelivery($vv['media_id']));

                        $vod_list[$kk]['media_img'] = $media['cover'];

                    }

                    $info['vod_list'] = $vod_list;

                }

            }

            $list[$k]['info'] = $info;

        }



        //dump($list);

        $this->assign('list',$list);

        $this->display();

    }



    //我的作业

    public function my_task(){

        $task = M('Task');

        $map = array('t.uid'=>$this->uid);

        //分页

        $count = $task->alias('t')->where($map)->count();

        $Page = new \Think\Page($count,5);

        $show = $Page->show();

        //列表

        $list = $task->alias('t')

            ->join('__ADMIN__ a on t.teacher_id = a.id')

            ->field('t.id,t.title,t.task,t.add_time,t.status,a.name')

            ->where($map)

            ->order('t.status,t.id desc')

            ->limit($Page->firstRow.','.$Page->listRows)

            ->select();



        $this->assign('list',$list);

        $this->assign('page',$show);

        $this->display();

    }



    //答题

    public function todo_task(){

        $task = M('Task');

        if(IS_POST){

            $data = I('post.');

            $info = $task->where(array('id'=>$data['id'],'uid'=>$this->uid,'status'=>1))->find();

            empty($info) && exit(json_encode(array(0,'非法提交')));

            $data['answer_time'] = time();

            $data['status'] = 2;

            if($task->save($data)){

                exit(json_encode(array(1,'提交成功')));

            }else{

                exit(json_encode(array(0,'操作失败，请重试')));

            }

        }else{

            $id = I('id','','intval');

            $info = $task->where(array('id'=>$id,'uid'=>$this->uid,'status'=>1))->find();



            $this->assign('info',$info);

            $this->display();

        }

    }



    //查看

    public function task_comment(){

        $id = I('id','','intval');

        $info = M('Task')->where(array('id'=>$id,'uid'=>$this->uid,'status'=>3))->find();



        $this->assign('info',$info);

        $this->display();

    }



    //评价管理

    public function curriculum_evaluate(){

        $evaluate = M('CurriculumEvaluate');

        $map = array('e.uid'=>$this->uid,'e.type'=>1);

        //分页

        $count = $evaluate->alias('e')->where($map)->count();

        $Page = new \Think\Page($count,10);

        $show = $Page->show();

        //列表

        $list = $evaluate->alias('e')

            ->join('__MEMBER__ m on e.uid = m.id')

            ->field('e.*,m.avatar,m.mobile')

            ->where($map)

            ->order('e.id desc')

            ->limit($Page->firstRow.','.$Page->listRows)

            ->select();



        $this->assign('list',$list);

        $this->assign('page',$show);

        $this->display();

    }



    //我的收藏

    public function my_collect(){

        $collect = M('Collection');

        if(IS_POST){

            $id = I('id','','intval');

            if($collect->where(array('id'=>$id,'uid'=>$this->uid))->delete()){

                echo 1;

            }else{

                echo 0;

            }

        }else{

            //课程收藏列表

            $list = $collect->alias('c')

                ->join('__CURRICULUM__ m on c.relation_id = m.id')

                ->field('c.*,m.id m_id,m.title,m.img,m.price,m.type m_type')

                ->where(array('c.uid'=>$this->uid,'c.type'=>1))

                ->order('c.id desc')

                ->select();



            //商品收藏列表

            $item_list = $collect->alias('c')

                ->join('__ITEM__ i on c.relation_id = i.id')

                ->field('c.*,i.id i_id,i.title,i.img,i.price')

                ->where(array('c.uid'=>$this->uid,'c.type'=>2))

                ->order('c.id desc')

                ->select();

//            dump($item_list);



            $this->assign('list',$list);

            $this->assign('item_list',$item_list);

            $this->display();

        }

    }

    

    //课程周期

    public function cycle(){

        $order = D('CurriculumOrder');

        $order->monitor_open_time($this->uid);

        

        $list = $order->alias('o')

            ->join('__CURRICULUM__ c on o.c_id = c.id')

            ->join('__ADMIN__ a on c.teacher_id = a.id')

            ->where(array('o.uid'=>$this->uid,'o.status'=>array('not in','1,5'),'o.type'=>2))

            ->field('o.id,o.status,o.is_check,o.check_time,c.title,c.cycle,a.name')

            ->select();



        //计算结束时间

        foreach ($list as $k=>$v){

            if($v['is_check'] == 1){

                $end_time = 0;

                $day = 0;

            }else{

                $end_time = strtotime("+{$v['cycle']} months", $v['check_time']);

                if($v['is_check'] == 3){

                    if($end_time < time()){

                        //已经到了结束时间

                        M('CurriculumOrder')->where(array('id'=>$v['id']))->setfield('is_check',4);

                        $day = 0;

                    }else{

                        $day = intval(($end_time-time())/86400);

                    }

                }else{

                    $day = 0;

                }

            }

            $list[$k]['end_time'] = $end_time;

            $list[$k]['day'] = $day;

        }



        $this->assign('list',$list);

    	$this->display();

    }



    //收货地址

    public function shipping_address(){

        $list = M('Address')->where(array('uid'=>$this->uid,'status'=>1))

            ->order('is_default desc')

            ->getfield('id,province,city,county,address,shperson,mobile,is_default');



        $this->assign('list',$list);

        $this->display();

    }



    //新增、编辑地址

    public function add_address(){

        if(IS_POST){

            $mod = D('Address');

            $count = $mod->where(array('uid'=>$this->uid,'status'=>1))->count();

            if($count >= 10){

                exit(json_encode(array(0,'最多可创建10个地址，你已达到上限')));

            }else{

                //自动验证

                if (false === $mod->create()) {

                    exit(json_encode(array(0,$mod->getError())));

                }

                //操作类型

                if($mod->id){

                    $result = $mod->save();

                }else{

                    $mod->uid = $this->uid;

                    $result = $mod->add();

                }



                //验证操作结果

                if($result === false){

                    exit(json_encode(array(0,'操作失败，请重试')));

                }elseif ($result > 0){

                    exit(json_encode(array(1,'操作成功')));

                }else{

                    exit(json_encode(array(0,'请输入修改内容')));

                }

            }

        }else{

            $this->error('非法访问');

        }

    }



    //默认地址

    public function set_default(){

        $id = I('id','','intval');

        $mod = M('Address');

        $info = $mod->where(array('id'=>$id,'uid'=>$this->uid,'status'=>1))->find();

        empty($info) && exit(json_encode(array(0,'地址不存在')));

        ($info['is_default'] == 1) && exit(json_encode(array(0,'该地址已设为默认')));

        $mod->where(array('uid'=>$this->uid,'status'=>1))->setfield('is_default',0);

        $mod->where(array('id'=>$id,'uid'=>$this->uid,'status'=>1))->setfield('is_default',1);

        exit(json_encode(array(1,'操作成功')));

    }



    //删除地址

    public function del_address(){

        $id = I('id','','intval');

        if(M('Address')->where(array('id'=>$id,'uid'=>$this->uid))->setfield('status',0)){

            exit(json_encode(array(1,'操作成功')));

        }else{

            exit(json_encode(array(0,'操作失败，请重试')));

        }

    }



    //商品订单

    public function item_order(){

        $status = I('status','','intval');

        //处理查询条件

        $map = array();

        $map['uid'] = $this->uid;

        if($status){

            $map['status'] = $status;

        }else{

            $map['status'] = array('neq',6);

        }

        ($time_start = I('time_start','', 'trim')) && $map['add_time'][] = array('egt', strtotime($time_start));

        ($time_end = I('time_end','', 'trim')) && $map['add_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));

        ($keywords = I('keywords','', 'trim')) && $map['order_sn'] = array('like', '%'.$keywords.'%');



        $order = D('Order');

        //获取所有订单各状态订单数量

        $count_nums = $order->where(array('uid'=>$this->uid,'status'=>array('neq',6)))

            ->field('status,count(id) nums')

            ->group('status')

            ->select();

        $count_list = array();

        foreach ($count_nums as $v){

            $count_list[$v['status']] = $v['nums'];

        }



        //分页

        $count = $order->where($map)->count();

        $Page = new \Think\Page($count,5);

        $show = $Page->show();

        //列表

        $list = $order->relation('list')

            ->where($map)

            ->order('id desc')

            ->limit($Page->firstRow.','.$Page->listRows)

            ->select();

        //dump($list);



        //动态关闭表单令牌

        C('TOKEN_ON',false);

        $this->assign('list',$list);

        $this->assign('page',$show);

        $this->assign('count_list',$count_list);

        $this->assign('status',$status);

        $this->assign('time_start',$time_start);

        $this->assign('time_end',$time_end);

        $this->assign('keywords',$keywords);

        $this->display();

    }



    //商品订单详情

    public function item_order_detail(){

        $id = I('id','','intval');

        $info = D('Order')->relation('list')

            ->where(array('id'=>$id,'uid'=>$this->uid,'status'=>array('neq',6)))

            ->find();

        empty($info) && $this->error('订单不存在');



        $this->assign('info',$info);

        $this->display();

    }



    //取消商品订单

    public function cancel_item_order(){

        $id = I('id','','intval');

        if(M('Order')->where(array('id'=>$id,'uid'=>$this->uid,'status'=>1))->setfield('status',6)){

            echo 1;

        }else{

            echo 0;

        }

    }



    //确认收货

    public function receipt_item_order(){

        $id = I('id','','intval');

        if(M('Order')->where(array('id'=>$id,'uid'=>$this->uid,'status'=>3))->setfield('status',4)){

            echo 1;

        }else{

            echo 0;

        }

    }



    //商品订单评价

    public function item_order_evaluate(){

        $this->display();

    }



    //我的积分

    public function integral(){

        //详情

        $info = $this->Member->find($this->uid);

        //积分记录列表

        $inte = M('Integral');

        $count      = $inte->where(array('uid'=>$this->uid))->count();

        $Page       = new \Think\Page($count,20);

        $show       = $Page->show();

        $list = $inte->where(array('uid'=>$this->uid))

            ->order('id desc')

            ->limit($Page->firstRow.','.$Page->listRows)

            ->select();

        //dump($list);

        

        $this->assign('info',$info);

        $this->assign('list',$list);

        $this->assign('page',$show);

    	$this->display();

    }

    

    //我的测评

    public function my_evaluation(){

        $evaluation = M('Evaluation');

        $map = array('uid'=>$this->uid);

        //分页

        $count = $evaluation->where($map)->count();

        $Page = new \Think\Page($count,5);

        $show = $Page->show();

        //列表

        $list = $evaluation

            ->where($map)

            ->order('id desc')

            ->limit($Page->firstRow.','.$Page->listRows)

            ->select();

        //dump($list);



        $this->assign('list',$list);

        $this->assign('types',C('EvaluationType'));

        $this->assign('page',$show);

        $this->display();

    }



}