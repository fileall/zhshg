<?php
namespace Home\Controller;
use MongoDB\Driver\Query;

class IndexController extends HomeController {
    public function _initialize() {
        parent::_initialize();
        $this->ArticleCate = D('ArticleCate');
        $this->Article = D('Article');
    }

    //首页
    public function index(){
        //轮播图
        $ad = M('Ad')->where(array('board_id'=>9,'status'=>1))->order('ordid,id desc')->select();
        //教师风采
        $teacher = $this->ArticleCate->field('id,name')
            ->where(array('pid'=>6,'status'=>1))
            ->relation('at_list')
            ->order('ordid,id')
            ->select();
        //新闻动态
        $news = $this->ArticleCate->field('id,name,img')
            ->where(array('pid'=>2,'status'=>1))
            ->relation('at_list')
            ->limit(4)
            ->select();
        //处理分类图片路径及展示位置#后期需再加上个最新话题（论坛里的）
        foreach ($news as $k=>$v){
            $img_arr = explode('_',$v['img']);
            $type_arr = explode('.',$img_arr[1]);
            $news[$k]['img'] =  $img_arr[0].".".$type_arr[1];
            switch ($k)
            {
                case 0:
                    $news[$k]['class'] = 'one';
                    break;
                case 1:
                    $news[$k]['class'] = 'two';
                    break;
                case 2:
                    $news[$k]['class'] = 'two';
                    break;
                case 3:
                    $news[$k]['class'] = 'last';
                    break;
            }
        }

        //课程分类
        $cate_list = D('CurriculumCate')->where(array('pid'=>0,'status'=>1))
            ->relation('self')
            ->order('ordid,id')
            ->field('id,name,url')
            ->select();

        //搜索关键字
        $keywords = M('SearchKeywords')->where(array('status'=>1))->order("ordid")->select();


        $this->assign('ad',$ad);
        $this->assign('teacher',$teacher);
        $this->assign('news',$news);
        $this->assign('cate_list',$cate_list);
        $this->assign('keywords',$keywords);
        $this->display();
    }

    //关于们
    public function about(){
        //关于我们栏目ID
        $id = I('id',1,'intval');
        //关于我们栏目详情
        $cate_info = $this->ArticleCate->where(array('id'=>$id))->find();
        //下级栏目
        $cate_list = $this->ArticleCate->where(array('pid'=>$id,'status'=>1))->order('ordid,id')->select();
        $cate_list_jian = array();
        foreach ($cate_list as $k=>$v){
            $cate_list_jian[$v['id']] = $v;
        }

        //二级栏目ID，默认列表第一个
        $cate_id = I('cate_id',$cate_list[0]['id'],'intval');
        //二级栏目详情
        $er_info =$cate_list_jian[$cate_id];
        //三级栏目
        $san_list = $this->ArticleCate->where(array('pid'=>$cate_id,'status'=>1))->order('ordid,id')->select();
        if($san_list){
            //三级栏目ID，默认列表第一个
            $san_id = I('san_id',$san_list[0]['id'],'intval');
            $map = array('cate_id'=>$san_id,'status'=>1);
        }else{
            $map = array('cate_id'=>$cate_id,'status'=>1);
        }

        //文章列表
        $count      = $this->Article->where($map)->count();
        $Page       = new \Think\Page($count,12);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $at_list = $this->Article->where($map)->order('ordid,id desc')
            ->limit($Page->firstRow.','.$Page->listRows)
            ->select();

        $this->assign('cate_info',$cate_info);
        $this->assign('cate_list',$cate_list);
        $this->assign('er_info',$er_info);
        $this->assign('at_list',$at_list);
        $this->assign('san_list',$san_list);
        $this->assign('san_id',$san_id);
        $this->assign('page',$show);
        $this->display();
    }


    //文章详情
    public function news_details(){
        //文章ID
        $id = I('id','','intval');
        $type = I('type','','trim');
        //文章详情
        $info = $this->Article->find($id);
        //增加浏览量
        $this->Article->where(array('id'=>$id))->setInc('hits',1);

        //获取文章分类集合
        $cate_str = $this->ArticleCate->where(array('id'=>$info['cate_id']))->getfield('spid');
        $cate_all = explode('|',trim($cate_str,'|'));
        $cate_all[] = $info['cate_id'];
        $cate_list = $this->ArticleCate->where(array('id'=>array('in',$cate_all)))->field('id,name')->select();
        $id_all = array();
        foreach ($cate_list as $k=>$v){
            $id_all[] = $v['id'];
            if($id_all){
                switch ($k)
                {
                    case 1:
                        $cate_list[$k]['id'] = $id_all[0];
                        $cate_list[$k]['cate_id'] = $v['id'];
                        break;
                    case 2:
                        $cate_list[$k]['id'] = $id_all[0];
                        $cate_list[$k]['cate_id'] = $id_all[1];
                        $cate_list[$k]['san_id'] = $v['id'];
                        break;
                }
            }
        }

        //热门推荐
        $list = $this->Article->where(array('cate_id'=>8))
            ->field('id,title,img')
            ->order('ordid,id desc')
            ->limit(6)
            ->select();

        $this->assign('info',$info);
        $this->assign('cate_list',$cate_list);
        $this->assign('type',$type);
        $this->assign('list',$list);
        $this->display();
    }

    //新闻动态
    public function news(){
        //关于我们栏目ID
        $id = I('id',2,'intval');
        //新闻动态栏目详情
        $cate_info = $this->ArticleCate->where(array('id'=>$id))->find();
        $img_arr = explode('_',$cate_info['img']);
        $type_arr = explode('.',$img_arr[1]);
        $cate_info['img'] =  $img_arr[0].".".$type_arr[1];

        //下级栏目
        $cate_list = $this->ArticleCate->where(array('pid'=>$id,'status'=>1))->order('ordid,id')->select();
        $cate_list_jian = array();
        foreach ($cate_list as $k=>$v){
            $cate_list_jian[$v['id']] = $v;
        }

        //二级栏目ID，默认列表第一个
        $cate_id = I('cate_id',$cate_list[0]['id'],'intval');
        //二级栏目详情
        $er_info =$cate_list_jian[$cate_id];
        $map = array('cate_id'=>$cate_id,'status'=>1);

        //文章列表
        $count      = $this->Article->where($map)->count();
        $Page       = new \Think\Page($count,6);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $at_list = $this->Article->where($map)->order('ordid,id desc')
            ->limit($Page->firstRow.','.$Page->listRows)
            ->select();

        //热门推荐
        $list = $this->Article->where(array('cate_id'=>8))
            ->field('id,title,img')
            ->order('ordid,id desc')
            ->limit(6)
            ->select();
   
        $this->assign('cate_info',$cate_info);
        $this->assign('cate_list',$cate_list);
        $this->assign('er_info',$er_info);
        $this->assign('at_list',$at_list);
        $this->assign('page',$show);
        $this->assign('list',$list);
        $this->display();
    }
	
	//售后服务
	public function service(){
        if(IS_POST){
            $message = D('Message');
            if (!$message->create()){
                exit(json_encode(array(0,$message->getError())));
            }else{
                if($message->add()){
                    exit(json_encode(array(1,'提交成功')));
                }else{
                    exit(json_encode(array(0,'操作失败，请重试')));
                }
            }
        }else{
            //关于我们下面栏目
            $cate_list = $this->ArticleCate->where(array('pid'=>1,'status'=>1))->order('ordid,id')->select();

            $this->assign('cate_list',$cate_list);
            $this->display();
        }
	}

}