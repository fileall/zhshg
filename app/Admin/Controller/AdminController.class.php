<?php

namespace Admin\Controller;

class AdminController extends AdminCoreController
{
    public function _initialize()
    {
        parent::_initialize();
        $this->_mod = D('Admin');
        $this->set_mod('Admin');
    }

    public function index()
    {
        /*$map = $this->_search();
        $count      = $this->_mod ->where($map)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $member = $this->_mod ->where($map)->relation(true)->order('city_id asc,id asc')
                       ->limit($Page->firstRow.','.$Page->listRows)->select();*/
        $this->get_admin_list('','',20);
    }


    public function _before_index()
    {
        $this->list_relation = true;
    }

    public function _before_add()
    {
        /*if($_SESSION['admin']['role_id'] == 1){
            $city_id = M('place')->where('type=2 and status=1')->select();
        }else{
            $city_id = $_SESSION['admin']['city_id'];
        }*/
        //$city_id = region_division();

//        $city_id = M('ItemCate')->field('id,name')->where('pid=0 and spid=0')->select();
        $role_str = array();
        $area_str = array();
        $str['status'] = 1;
        $role_id = $_SESSION['admin']['role_id'];
        $area_list = array();
        switch ($role_id){
            case 15:
                $role_str['id'] = array('in',array(16,17));
                break;
            case 16:
                $role_str['id'] = 17;
                $area_str['role_id'] = 15;
                break;
            case 17:
                $role_str['id'] = 0;
                $area_str['role_id'] = 16;
                break;
            default:
                $role_str['id'] = array('in',array(15,16,17));
//                $area_str['_string'] = '(role_id = 16 AND ppid)'I('id','','intval');
        }

        $role_list = M('admin_role')->where($str)->select();
        !empty($area_str) && $area_list = $this->_mod->field('id,area_name')->where($area_str)->select();

        $this->assign('area_list',$area_list);
        $this->assign('role_list', $role_list);
//        $this->assign('city_id', $city_id);
    }

    public function _before_insert($data='')
    {
        $data = $this->set_before($data);
        /*$admin = session('admin');
        $data['city_id'] = $admin['city_id'];*/
        return $data;
    }

    public function set_before($data='')
    {
        if( ('' == $data['password']) || (trim('' == $data['password'])) ){
            unset($data['password']);
        }else{
            $data['password'] = md5($data['password']);
        }
        $city_id = I('city_id','','intval');
        if ($city_id){
            if (17 == $data['role_id']){//小区推广员
                $data['cid'] = $this->_mod->where(array('id'=>$city_id))->getField('cid');
                $data['pid'] = $city_id;
            }else{//一级推广员
                $data['cid'] = $city_id;
                $data['pid'] = $city_id;
            }
        }
        return $data;
    }

    public function _before_edit()
    {
        $this->_before_add();
    }

    public function _before_update($data='')
    {
        if( ($data['password']=='')||(trim($data['password']=='')) ){
            unset($data['password']);
        }else{
            $data['password'] = md5($data['password']);
        }
        return $data;
    }

    public function ajax_check_name()
    {
        $name = I('username','', 'trim');
        $id = I('id','', 'intval');
        if ($this->_mod->name_exists($name,'username', $id)) {
            echo 0;
        } else {
            echo 1;
        }
    }

    public function ajax_check_area_name()
    {
        $area_name = I('area_name','','trim');
        $id = I('id','', 'intval');
        if ($this->_mod->name_exists($area_name,'area_name', $id)){
            echo 0;
        } else {
            echo 1;
        }
    }

    public function ajax_role()
    {
        $role = I('role_id','','trim');
        if (18 == $role || 19 == $role){
            $role_id = (18 == $role) ? 17 : 18;
            $arr = $this->_mod->field('id,area_name')->where(array('role_id'=>$role_id))->select();
        }
        $this->ajax_return(1,'',$arr);
    }

    //查询管理员列表
    public function get_admin_list($str1,$page)
    {
        $arr    = $this->_mod->field('id')->where($str1)->select();
        $count  = empty($arr) ? 0 : count($arr);
        $Page   = new \Think\Page($count,$page);
        $show   = $Page->show();// 分页显示输出

        $member = D()->table('__ADMIN__ an')
            ->join('__ADMIN_ROLE__ ane on ane.id=an.role_id','left')
            ->field('ane.name,an.id,an.username,an.last_time,an.last_ip,an.status')
            ->where(array('an.id'=>array('in',array_column($arr,'id'))))
            ->limit($Page->firstRow.','.$Page->listRows)
            ->group('an.id')
            ->select();

        $this->assign('list',$member);
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('list_table', true);
        $this ->display();
    }


    //推广员发放工资
    public function promoters()
    {
        if (IS_POST){
            $ids = I('post.ids');
            $prices = I('post.prices');
            $type = I('post.type');
            $prices1 = $this->_mod->where('id in('.$ids.')')->order('prices')->getField('prices');

            if (2 == $type && $prices > $prices1){
                IS_AJAX && $this->ajax_return(0, L('lack_of_balance'));
                $this->error(L('lack_of_balance'));
            }

            $arr = array();
            foreach (explode(',',$ids) as $item) {
                $arr[] = array(
                    'aid'       => $item,
                    'cid'       => $_SESSION['admin']['id'],
                    'type'      => $type,
                    'price'     => $prices,
                    'add_time'  => $_SERVER['REQUEST_TIME'],
                );
            }

            $set = (2 == $type) ? 'setDec' : 'setInc';

            if (false !== $this->_mod->where('id in('.$ids.')')->$set('prices',$prices) && false !== M('PromotersPayrollRecords')->addAll($arr))
                $this->ajax_return(1, L('operation_success'));
            else
                IS_AJAX && $this->ajax_return(0, L('operation_failure'));

        }else{
            $ids = trim(I('id'), ',');
            $type = I('type','','intval');
            $this->assign('type',$type);
            $this->assign('ids', $ids);

            $resp = $this->fetch();
            $this->ajax_return(1, '', $resp);
        }
    }

    /**
     * 获取紧接着的下一级分类ID
     */
    public function ajax_getchilds()
    {

        $id = I('id',0, 'intval');
        $type = I('type', null, 'intval');
        $map = array(
            'pid'       => $id,
            'role_id'   => array('between',array(15,17)),
        );
        if (!is_null($type)) {
            $map['type'] = $type;
        }

        $return = $this->_mod->field('id,area_name as name')->where($map)->select();

        if ($return) {
            $this->ajax_return(1, L('operation_success'), $return);
        } else {
            $this->ajax_return(0, L('operation_failure'));
        }
    }

}