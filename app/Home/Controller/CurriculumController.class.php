<?php
namespace Home\Controller;
class CurriculumController extends HomeController {
    public function _initialize() {
        parent::_initialize();
        $this->Curriculum = D('Curriculum');
        $this->CurriculumCate = D('CurriculumCate');
    }

    //搜索
    public function search(){
        $keywords = I('keywords','','trim');
        empty($keywords) && $this->error('请输入关键字');
        //所有课程列表按ID查询
        $cate_list = $this->CurriculumCate->where(array('status'=>1))->getfield('id,pid,name');

        $map = "(title like '%".$keywords."%' or teacher_id in(select id from jrkj_admin where name like '%".$keywords."%' and role_id = 9)) and type in(1,2) and status = 1";

        $field = "id,cate_id,title,img,class_time,price,old_price,people,info,type,(select name from jrkj_admin where id = jrkj_curriculum.teacher_id) name,(select count(*) from jrkj_curriculum_ext where c_id = jrkj_curriculum.id and status = 1) nums,(select spid from jrkj_curriculum_cate where id = jrkj_curriculum.cate_id) spid";

        //老师课程列表
        $count      = $this->Curriculum->where($map)->count();
        $Page       = new \Think\Page($count,6);
        $show       = $Page->show();
        $list = $this->Curriculum->where($map)
            ->field($field)
            ->limit($Page->firstRow.','.$Page->listRows)
            ->order('ordid,id desc')
            ->select();

        //获取年级科目信息
        foreach ($list as $k=>$v){
            $cate_info = explode('|',rtrim($v['spid'],'|'));
            unset($cate_info[0]);
            $cate_info[] = $v['cate_id'];
            $str = "";
            foreach ($cate_info as $kk=>$vv){
                $str .= $cate_list[$vv]['name'];
            }
            $list[$k]['cate_name'] =$str;
        }

        //推荐列表
        $tj_list = $this->Curriculum->where(array('is_tj'=>1,'status'=>1))
            ->field('id,title,img,price,type')
            ->order('ordid,id desc')
            ->limit(5)
            ->select();
        //dump($list);exit;

        $this->assign('page',$show);
        $this->assign('list',$list);
        $this->assign('cate_list',$cate_list);
        $this->assign('tj_list',$tj_list);

        $this->display();
    }

    //语数外课堂
    public function classroom(){
        //语数外课堂栏目ID
        $id = I('id',1,'intval');
        //语数外课堂栏目详情
        $cate_info = $this->CurriculumCate->where(array('id'=>$id))->find();
        $cate_info['img'] =  $this->CurriculumCate->get_img_url($cate_info['img']);

        //直播回放
        $id_all = $this->CurriculumCate->get_child_ids($id);
        $list[] = array(
            'name' => '全部',
            'list' => $this->Curriculum->where(array('cate_id'=>array('in',$id_all),'type'=>1,'status'=>1))
                ->field('id,title,img,class_time,people')
                ->limit(4)
                ->order('ordid,id desc')
                ->select(),
            'teacher' => $this->Curriculum->where(array('cate_id'=>array('in',$id_all),'type'=>2,'status'=>1))
                ->field('id,title,img,class_time,people,(select count(*) from jrkj_curriculum_ext where c_id = jrkj_curriculum.id and status = 1) nums')
                ->limit(4)
                ->order('ordid,id desc')
                ->select()
        );
        $cate_list = $this->CurriculumCate->where(array('pid'=>$id,'status'=>1))->field('id,name')->select();
        foreach ($cate_list as $k=>$v){
            $id_all = $this->CurriculumCate->get_child_ids($v['id']);
            if($id_all){
                $cate_list[$k]['list'] = $this->Curriculum->where(array('cate_id'=>array('in',$id_all),'type'=>1,'status'=>1))
                    ->field('id,title,img,class_time,people')
                    ->limit(4)
                    ->order('ordid,id desc')
                    ->select();
                $cate_list[$k]['teacher'] = $this->Curriculum->where(array('cate_id'=>array('in',$id_all),'type'=>2,'status'=>1))
                    ->field('id,title,img,class_time,people,(select count(*) from jrkj_curriculum_ext where c_id = jrkj_curriculum.id and status = 1) nums')
                    ->limit(4)
                    ->order('ordid,id desc')
                    ->select();
            }
            $list[]= $cate_list[$k];
        }
        //dump($list);

        $this->assign('cate_info',$cate_info);
        $this->assign('list',$list);
        $this->display();
    }


    //课程列表
    private function _list($type){
        //所有课程列表按ID查询
        $cate_list = $this->CurriculumCate->where(array('status'=>1))->getfield('id,pid,name');
        //按PID分组
        $cate_list_all = array();
        foreach ($cate_list as $k=>$v){
            $cate_list_all[$v['pid']][] = $v;
        }

        //阶段列表
        $stage = $cate_list_all[1];
        //年级列表
        $stage_id = I('stage_id','','intval');
        if ($stage_id)
            $grade = $cate_list_all[$stage_id];
        //科目列表
        $grade_id = I('grade_id','','intval');
        if($grade_id)
            $subject = $cate_list_all[$grade_id];
        $subject_id = I('subject_id','','intval');
        //教龄阶段
        $seniority_id = I('seniority_id','','intval');

        //处理条件
        $map['type'] = $type;
        $map['status'] = 1;
        if($subject_id){
            $map['cate_id'] = $subject_id;
        }elseif ($grade_id){
            $id_all = $this->CurriculumCate->get_child_ids($grade_id);
            if ($id_all){
                $map['cate_id'] = array('in',$id_all);
            }else{
                $map['cate_id'] = 1000000000000000000000;
            }
        }elseif ($stage_id){
            $id_all = $this->CurriculumCate->get_child_ids($stage_id);
            if ($id_all){
                $map['cate_id'] = array('in',$id_all);
            }else{
                $map['cate_id'] = 1000000000000000000000;
            }
        }

        if($type == 1){
            $field = "id,cate_id,title,img,price,old_price,people,info,(select name from jrkj_admin where id = jrkj_curriculum.teacher_id) name,(select spid from jrkj_curriculum_cate where id = jrkj_curriculum.cate_id) spid";
        }else{
            $field = "id,cate_id,title,img,class_time,people,info,(select count(*) from jrkj_curriculum_ext where c_id = jrkj_curriculum.id and status = 1) nums,(select spid from jrkj_curriculum_cate where id = jrkj_curriculum.cate_id) spid";
        }

        //老师课程列表
        $count      = $this->Curriculum->where($map)->count();
        $Page       = new \Think\Page($count,6);
        $show       = $Page->show();
        $list = $this->Curriculum->where($map)
            ->field($field)
            ->limit($Page->firstRow.','.$Page->listRows)
            ->order('ordid,id desc')
            ->select();

        //获取年级科目信息
        foreach ($list as $k=>$v){
            $cate_info = explode('|',rtrim($v['spid'],'|'));
            unset($cate_info[0]);
            $cate_info[] = $v['cate_id'];
            $str = "";
            foreach ($cate_info as $kk=>$vv){
                $str .= $cate_list[$vv]['name'];
            }
            $list[$k]['cate_name'] =$str;
        }

        //推荐列表
        $tj_list = $this->Curriculum->where(array('is_tj'=>1,'status'=>1,'type'=>$type))
            ->field('id,title,img,price')
            ->order('ordid,id desc')
            ->limit(5)
            ->select();
        //dump($tj_list);

        $this->assign('stage',$stage);
        $this->assign('stage_id',$stage_id);
        $this->assign('grade',$grade);
        $this->assign('grade_id',$grade_id);
        $this->assign('subject',$subject);
        $this->assign('subject_id',$subject_id);
        $this->assign('seniority_id',$seniority_id);
        $this->assign('page',$show);
        $this->assign('list',$list);
        $this->assign('cate_list',$cate_list);
        $this->assign('tj_list',$tj_list);
    }

    //直播回放列表
    public function liveList(){
        $this->_list(1);
        $this->display();
    }

    //老师列表
    public function techerList(){
        //教龄设置列表
        //$seniority = C('JIAOLING');
        /*if($seniority_id){
            $seniority_info =  $seniority[$seniority_id];
            $map['seniority'] = array('between',array($seniority_info['start'],$seniority_info['end']));
        }*/
        //$this->assign('seniority',$seniority);

        $this->_list(2);
        $this->display();
    }

    //老师详情
    public function techerDetail(){
        $id = I('id','','intval');
        //课程详情
        $info = $this->Curriculum->where(array('id'=>$id,'type'=>2))->find();
        //所有课程列表按ID查询
        $cate_list = $this->CurriculumCate->where(array('status'=>1))->getfield('id,spid,name');
        //获取课程阶段情况
        $cate_info = explode('|',rtrim($cate_list[$info['cate_id']]['spid'],'|'));
        unset($cate_info[0]);
        $cate_info[] = $info['cate_id'];
        foreach ($cate_info as $kk=>$vv){
            $cate_info[$kk] = $cate_list[$vv]['name'];
        }

        //老师详情
        $teacher = M('Admin')->find($info['teacher_id']);
        //老师相册
        $imgs = M('AdminImg')->where(array('article_id'=>$info['teacher_id']))->select();
        //老师所有课程
        $list = M('CurriculumExt')->where(array('c_id'=>$id,'status'=>1))
            ->order('ordid,id desc')
            ->select();
        //会员信息
        $uid = $_SESSION['user_auth']['id'];
        if($uid){
            $user = M('Member')->where(array('id'=>$uid))->field('id,avatar,(select count(*) from jrkj_collection where relation_id = '.$id.' and uid = jrkj_member.id and type = 1) is_collect')->find();
            $this->assign('user',$user);
        }

        //相关评价
        $ce = M('CurriculumEvaluate');
        $pages = 10;
        $count = $ce->where(array('c_id'=>$id,'status'=>1))->count();
        $evaluate_list = $ce->alias('e')
            ->join('__MEMBER__ m on e.uid = m.id')
            ->field('e.*,m.avatar')
            ->where(array('e.c_id'=>$id,'e.status'=>1))
            ->order('id desc')
            ->limit($pages)
            ->select();

        $this->assign('info',$info);
        $this->assign('list',$list);
        $this->assign('imgs',$imgs);
        $this->assign('teacher',$teacher);
        $this->assign('cate_info',$cate_info);
        $this->assign('evaluate_list',$evaluate_list);
        $this->assign('evaluate_page',ceil($count/$pages));
        $this->display();
    }

    //评论分页
    public function evaluate_list(){
        $id = I('id','','intval');
        $p = I('p','','intval');
        $pages = 10;
        $list = M('CurriculumEvaluate')->alias('e')
            ->join('__MEMBER__ m on e.uid = m.id')
            ->field('e.*,m.avatar')
            ->where(array('e.c_id'=>$id,'e.status'=>1))
            ->order('id desc')
            ->limit(($p-1)*$pages,$pages)
            ->select();
        foreach ($list as $k=>$v){
            $list[$k]['avatar'] = $v['avatar']?$v['avatar']:'/pc/images/moren.png';
            $list[$k]['add_time'] = date("Y-m-d H:i",$v['add_time']);
        }

        echo json_encode($list);
    }

    //直播回放详情
    public function playbackDetail(){
        $id = I('id','','intval');
        //课程详情
        $info = $this->Curriculum->where(array('id'=>$id,'type'=>1))->find();
        //课程视频
        $vod = M('CurriculumExt')->where(array('c_id'=>$id))->find();
        //所有课程列表按ID查询
        $cate_list = $this->CurriculumCate->where(array('status'=>1))->getfield('id,spid,name');
        //获取课程阶段情况
        $cate_info = explode('|',rtrim($cate_list[$info['cate_id']]['spid'],'|'));
        unset($cate_info[0]);
        $cate_info[] = $info['cate_id'];
        foreach ($cate_info as $kk=>$vv){
            $cate_info[$kk] = $cate_list[$vv]['name'];
        }

        //老师详情
        $teacher = M('Admin')->find($info['teacher_id']);

        //猜你喜欢
        $list = $this->Curriculum
            ->where(array('teacher_id'=>$info['teacher_id'],'type'=>1,'status'=>1))
            ->field('id,title,img,price')
            ->order('ordid,id desc')
            ->limit(10)
            ->select();
        //会员信息
        $uid = $_SESSION['user_auth']['id'];
        if($uid){
            $user = M('Member')->where(array('id'=>$uid))->field('id,avatar,(select count(*) from jrkj_collection where relation_id = '.$id.' and uid = jrkj_member.id and type = 1) is_collect')->find();
            $this->assign('user',$user);
        }

        //相关评价
        $ce = M('CurriculumEvaluate');
        $pages = 10;
        $count = $ce->where(array('c_id'=>$id,'status'=>1))->count();
        $evaluate_list = $ce->alias('e')
            ->join('__MEMBER__ m on e.uid = m.id')
            ->field('e.*,m.avatar')
            ->where(array('e.c_id'=>$id,'e.status'=>1))
            ->order('id desc')
            ->limit($pages)
            ->select();

        $this->assign('teacher',$teacher);
        $this->assign('info',$info);
        $this->assign('vod',$vod);
        $this->assign('cate_info',$cate_info);
        $this->assign('list',$list);
        $this->assign('evaluate_list',$evaluate_list);
        $this->assign('evaluate_page',ceil($count/$pages));
        $this->display();
    }

    //收藏课程
    public function collect_curriculum(){
        $id = I('id','','intval');
        empty($id) && exit(json_encode(array(0,'非法提交')));
        $uid = $_SESSION['user_auth']['id'];
        empty($uid) && exit(json_encode(array(2,'请先登录')));
        $collect = M('Collection');
        $check = $collect->where(array('relation_id'=>$id,'uid'=>$uid,'type'=>1))->count();
        ($check > 0) && exit(json_encode(array(0,'已收藏')));
        if($collect->add(array('relation_id'=>$id,'uid'=>$uid))){
            exit(json_encode(array(1,'已收藏')));
        }else{
            exit(json_encode(array(0,'操作失败，请重试')));
        }
    }

    //验证是否购买课程
    public function check_vod(){
        $id = I('id','','intval');
        empty($id) && exit(json_encode(array(0,'非法提交')));
        $uid = $_SESSION['user_auth']['id'];
        empty($uid) && exit(json_encode(array(2,'请先登录')));
        $check =  M('CurriculumOrder')->where(array('c_id'=>$id,'uid'=>$uid,'status'=>array('in','2,3,4')))->find();
        empty($check) && exit(json_encode(array(3,'您还没有购买该课程')));
        exit(json_encode(array(1,'OK')));
    }

    //看视频
    public function watch_videos(){
        $id = I('id','','intval');
        $curr = M('Curriculum')->where(array('id'=>$id))->field('type')->find();
        empty($curr) && $this->error('非法访问');
        $ex_id = I('ex_id','','trim');
        empty($ex_id) && $this->error('非法访问');
        $uid = $_SESSION['user_auth']['id'];
        empty($uid) && $this->error('请先登录');
        $order = M('CurriculumOrder');
        $check = $order->where(array('c_id'=>$id,'uid'=>$uid,'status'=>array('in','2,3,4')))->find();
        empty($check) && $this->error('您还没有购买该课程');
        if($check['type'] == 2 && ($check['is_check'] == 1 or $check['is_check'] == 3))
            $this->error('您所购买的课程还未审核或已经结束服务');
        $ext = M('CurriculumExt');
        $info = $ext->where(array('id'=>$ex_id,'c_id'=>$id))->find();
        empty($info) && $this->error('非法访问');

        //修改订单状态
        if($check['status'] == 2){
            $mode = $order->where(array('id'=>$check['id']));
            if($curr['type'] == 1 || $curr['type'] == 3){
                $mode->setfield('status',4);
            }else{
                $mode->setfield('status',3);
            }
        }

        //记录播放次数
        $ext->where(array('id'=>$ex_id))->setInc('hits');
        //调用百度云VOD
        $vod = $this->baidu_cloud_vod();
        $result = object_to_array($vod->getMediaDelivery($info['media_id']));

        $this->assign('result',$result);
        $this->assign('ak',$vod->ak);
        $this->display();
    }

    //直播回放评论
    public function submit_evaluate(){
        $uid = $_SESSION['user_auth']['id'];
        //验证
        empty($uid) && exit(json_encode(array(2,'请先登录')));
        $content = I('content','','trim');
        empty($content) && exit(json_encode(array(0,'请输入评论内容')));
        $id = I('id','','intval');
        empty($id) && exit(json_encode(array(0,'非法提交')));
        //添加评论
        $data = array(
            'c_id' => $id,
            'o_id' => 0,
            'uid' => $uid,
            'content' => $content,
            'add_time' => time(),
            'type' => 2
        );
        if(M('CurriculumEvaluate')->add($data)){
            exit(json_encode(array(1,'评论成功')));
        }else{
            exit(json_encode(array(0,'操作失败，请重试')));
        }
    }

    //家庭教育指导
    public function homeEducation(){
        $id = I('id',2,'intval');
        //语数外课堂栏目详情
        $cate_info = $this->CurriculumCate->where(array('id'=>$id))->find();
        $cate_info['img'] =  $this->CurriculumCate->get_img_url($cate_info['img']);

        $list = $this->CurriculumCate->where(array('pid'=>$id,'status'=>1))
            ->order('ordid,id')
            ->field('id,name')
            ->select();
        //dump($list);

        $this->assign('cate_info',$cate_info);
        $this->assign('list',$list);
        $this->display();
    }

    //指导课程详情
    public function homeEduDetail(){
        $id = I('id','','intval');
        //栏目详情
        $cate_info = $this->CurriculumCate->where(array('id'=>$id))->find();
        $cate_info['img'] =  $this->CurriculumCate->get_img_url($cate_info['img']);
        //获取课程详情
        $info = $this->Curriculum->where(array('cate_id'=>$id,'status'=>1,'type'=>3))
            ->field('id,title,price,info,outline')
            ->find();

        $this->assign('info',$info);
        $this->assign('cate_info',$cate_info);
        $this->assign('uid',$_SESSION['user_auth']['id']);
        $this->display();
    }

    //验证是否购买课程
    public function check_home_edu(){
        $id = I('id','','intval');
        empty($id) && exit(json_encode(array(0,'非法提交')));
        $uid = $_SESSION['user_auth']['id'];
        empty($uid) && exit(json_encode(array(2,'请先登录')));
        $check =  M('CurriculumOrder')->where(array('c_id'=>$id,'uid'=>$uid,'status'=>array('in','2,3,4'),'is_check'=>array('neq',4)))->find();
        empty($check) && exit(json_encode(array(3,'您还没有购买该课程')));
        exit(json_encode(array(1,'OK')));
    }
    
}