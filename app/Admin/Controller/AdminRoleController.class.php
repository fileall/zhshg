<?php
namespace Admin\Controller;

use Admin\Org\Tree;

class AdminRoleController extends AdminCoreController
{
    public function _initialize()
    {
        parent::_initialize();
        $this->_mod = D('AdminRole');
        $this->set_mod('AdminRole');
    }

    public function index()
    {
        $admin = session('admin');
        if(1 == $admin['role_id']){
            $member =	$this->_mod->relation(true) ->select();
        }else{
            $member =	$this->_mod ->where('id != 1')->relation(true)->select();
        }
        $this->assign('list_table', true);
        $this->assign('list',$member);
        $this->display();
    }

    public function _before_index()
    {
        $this->list_relation = true;
        $big_menu = [
            'title'     => '添加角色',
            'iframe'    => U('admin_role/add'),
            'id'        => 'add',
            'width'     => '550',
            'height'    => '190',
        ];
        $this->assign('big_menu', $big_menu);
    }

    public function auth()
    {
        $menu_mod = D('Menu');
        $auth_mod = D('AdminAuth');
        if (IS_POST) {
            $id = intval($_POST['id']);
            //清空权限
            $auth_mod->where(['role_id'=>$id])->delete();
            if (is_array($_POST['menu_id']) && count($_POST['menu_id']) > 0) {
                foreach ($_POST['menu_id'] as $menu_id) {
                    $auth_mod->add([
                        'role_id' => $id,
                        'menu_id' => $menu_id
                    ]);
                }
            }
            $this->success(L('operation_success'));
        } else {
            $id = I('id','', 'intval');
            $tree = new Tree();
            $tree->icon = ['│ ','├─ ','└─ '];
            $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
            $result = $menu_mod->field('id,name,pid,controller_name,action_name')->order('ordid')->select();

            //获取被操作角色权限
            $role_data = $this->_mod->relation('role_priv')->find($id);
            $role_priv = $auth_mod->where(['role_id'=>$id])->select();
            $priv_ids = [];
            foreach ($role_priv as $val) {
                $priv_ids[] = $val['menu_id'];
            }
            foreach($result as $k=>$v) {
                $result[$k]['level'] = $menu_mod->get_level($v['id'],$result);
                $result[$k]['checked'] = (in_array($v['id'], $priv_ids)) ? ' checked' : '';
                $result[$k]['parentid_node'] = ($v['pid']) ? ' class="child-of-node-'.$v['pid'].'"' : '';
            }
            $str  = "<tr id='node-\$id' \$parentid_node>" .
                "<td style='padding-left:10px;'>\$spacer<input type='checkbox' name='menu_id[]' value='\$id' class='J_checkitem' level='\$level' \$checked> \$name</td>
                    </tr>";
            $tree->init($result);
            $menu_list = $tree->get_tree(0, $str);
            unset($result,$str,$priv_ids);
            $this->assign('list', $menu_list);
            $this->assign('role', $role_data);
            $this->display();
        }
    }

}