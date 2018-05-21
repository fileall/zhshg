<?php

namespace Admin\Controller;

class AdminController extends AdminCoreController {
	
	public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Admin');
        $this->set_mod('Admin');
    }

    protected function _search() { 
        $map = array();
        $admin = session('admin');
        if($admin['role_id'] != 1){
            $map['role_id'] = array('neq',1);
        }

        return $map;
    }
	
    public function _before_index() {
        //部门
        $d_list = M('Department')->getfield('id,name');

        $this->assign('d_list',$d_list);
        $this->list_relation = true;
        //默认排序
        $this->sort = 'id';
        $this->order = 'asc';
    }

    //导出
    public function export(){
        //导出
        ob_end_clean();
        set_time_limit(0);
        ini_set('memory_limit', '800M');
        $d_list = M('Department')->getfield('id,name');
        //列表
        $list = $this->_mod->relation(true)->select();
        //处理列表
        $data = array();
        foreach ($list as $k=>$val){
            $data[$k] = array(
                $val['username'],
                $val['name'],
                $val['id'],
                $val['mobile'],
                ($val['sex']==1) ? "男": "女",
                $val['birthday'],
                $d_list[$val['d_id']],
                $val['role']['name'],
                date("Y-m-d",$val['add_time']),
                ($val['status']==1) ? "正常": "禁用"
            );
        }
        $headArr=array(
            '账号',
            '姓名',
            '工号',
            '手机',
            '性别',
            '生日',
            '部门',
            '职位',
            '创建时间',
            '状态',
        );
        $this->getExcel("员工",$headArr,$data);
    }

    //导入
    public function admin_upload()
    {
        $data_list = $this->ru_upload();
        //部门
        $d_list = M('Department')->getfield('name,id');
        //职位
        $role = M('AdminRole')->getfield('name,id');
        //dump($role);exit;

        $data = array();
        foreach ($data_list as $k=>$v){
            $data[] = array(
                'username' => $v['A'],
                'password' => st_md5($v['B']),
                'name' => $v['C'],
                'mobile' => $v['D'],
                'sex' => ($v['E'] === "男") ? 1 : 0,
                'birthday' => $v['F'],
                'd_id' => $d_list[$v['G']],
                'role_id' => $role[$v['H']],
                'add_time' => time()
            );
        }
        unset($data_list);
        //添加数据
        $this->_mod->startTrans();
        $ok = $this->_mod->addAll($data);
        if($ok){
            $this->_mod->commit();
            $this->success('导入成功');
        }else{
            $this->_mod->rollback();
            $this->error('导入失败');
        }
    }

    public function _before_add() {
    	$admin = session('admin');
    	if($admin['role_id'] != 1){
			$where = "id != 1 and ";
		}
		$where .= "status=1";

        //职位
        $role_list = M('admin_role')->where($where)->select();
        //部门
        $d_list = M('Department')->select();

        $this->assign('role_list', $role_list);
        $this->assign('d_list', $d_list);
    }
	
    public function _before_insert($data='') {
        if( ($data['password']=='')||(trim($data['password']=='')) ){
            unset($data['password']);
        }else{
            $data['password'] = md5($data['password']);
        }
		$data['add_time'] = time();
        return $data;
    }

    public function _after_insert($id){
        //上传相册
        $item_imgs = array();
        if($_POST['imgs']){
            foreach ($_POST['imgs'] as $k=>$v){
                $item_imgs[] = array(
                    'article_id' => $id,
                    'url'    => $v,
                    'order'   => $k+1,
                );
            }
        }
        //更新图片和相册
        $item_imgs && M('AdminImg')->addAll($item_imgs);
    }

    public function _before_edit() {
        $id = I('id','','intval');
        //相册
        $img_list = M('AdminImg')->where(array('article_id'=>$id))->select();
        //获取分类信息
        $info = $this->_mod->field('id,cate_id')->where(array('id'=>$id))->find();

        $spid = D('CurriculumCate')->where(array('id'=>$info['cate_id']))->getField('spid');
        if( $spid==0 ){
            $spid = $info['cate_id'];
        }else{
            $spid .= $info['cate_id'];
        }

        $this->assign('img_list', $img_list);
        $this->assign('spid', ltrim(ltrim($spid,'1'),'|'));
        $this->_before_add();
    }

    public function _before_update($data=''){
        //处理密码
        if( ($data['password']=='')||(trim($data['password']=='')) ){
            unset($data['password']);
        }else{
            $data['password'] = md5($data['password']);
        }

        //上传相册
        $item_imgs = array();
        if($_POST['imgs']){
            foreach ($_POST['imgs'] as $k=>$v){
                $item_imgs[] = array(
                    'article_id' => $data['id'],
                    'url'    => $v,
                    'order'   => $k+1,
                );
            }
        }
        //更新图片和相册
        $item_imgs && M('AdminImg')->addAll($item_imgs);

        return $data;
    }

    public function ajax_check_name() {
        $name = I('username','', 'trim');
        $id = I('id','', 'intval');
        if ($this->_mod->name_exists($name, $id)) {
            echo 0;
        } else {
            echo 1;
        }
    }

    //异步上传图片
    public function ajax_upload_img(){
        $date_dir = date('ym/d/'); //上传目录
        $result = $this->_upload($_FILES['file'], 'admin/'.$date_dir, array());

        if ($result['error']) {
            echo json_encode(array("error" => $result['info']));
        } else {
            $data['thumb_img'] = $date_dir .$result['info'][0]['savename'];
            echo json_encode(array("error" => "0", "src" => $data['thumb_img'], "name" => $result['info'][0]['savename']));
        }
        exit;
    }

    //删除图集
    public function del_imgs(){
        $id = I('id','','intval');
        //删除原图
        $old_img = M('AdminImg')->where(array('id'=>$id))->getField('url');
        $old_img = '.'.attach($old_img,'admin');
        is_file($old_img) && @unlink($old_img);
        //删除数据
        $del = M('AdminImg')->delete($id);
        if($del){
            echo 1;
        }else{
            echo 0;
        }
    }

}