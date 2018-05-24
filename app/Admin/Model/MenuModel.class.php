<?php
namespace Admin\Model;
use Think\Model;
class MenuModel extends Model {
    
    protected $_validate = array(
        array('name', 'require', '{%menu_name_require}'), //菜单名称为必须
        array('name', 'require', '{%module_name_require}'), //模块名称必须
        array('name', 'require', '{%action_name_require}'), //方法名称必须
    );

    public function admin_menu($role_id,$pid, $with_self = false,$map = [])
    {
        $pid = intval($pid);

        if(1 != $role_id){
            $condition = ['m.pid' => $pid];
            if ($with_self) {
                $condition['m.id'] = $pid;
                $condition['_logic'] = 'OR';
            }
            $map['_complex'] = $condition;
            $map['m.display'] = 1;
            $map['a.role_id'] = $role_id;

            $menus = D()->table('__MENU__ m')
                ->join('__ADMIN_AUTH__ a on m.id=a.menu_id','LEFT')
                ->field('m.*')
                ->where($map)
                ->order('m.ordid,m.dialog DESC')->select();
           // dump(M()->_sql());die;

        }else{

            $condition = ['pid' => $pid];
            if ($with_self) {
                $condition['id'] = $pid;
                $condition['_logic'] = 'OR';
            }
            $map['_complex'] = $condition;
            $map['display'] = 1;

            $menus = M("Menu")->where($map)->order('ordid,dialog desc')->select();

        }

        return $menus;
    }
    
    public function sub_menu($role_id,$pid = '', $big_menu = false) {
        $array = $this->admin_menu($role_id,$pid, false);
        /*$numbers = count($array);
        if ($numbers==1 && !$big_menu) {
            return '';
        }*/
        return $array;
    }
    
    public function get_level($id,$array=array(),$i=0) {
        foreach($array as $n=>$value){
            if ($value['id'] == $id) {
                if($value['pid']== '0') return $i;
                $i++;
                return $this->get_level($value['pid'],$array,$i);
            }
        }
    }
}