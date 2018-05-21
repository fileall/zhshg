<?php
namespace Admin\Controller;
use Admin\Org\Image;
class IndexController extends AdminCoreController {
	public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Menu');
//		session('admin', array(
//              'id' => 1,
//              'role_id' => 1,
//              'username' => 1,
//              'city_id' => 1,
//      ));
    }
	public function index() {
        $top_menus = $this->_mod->admin_menu(0);
        $this->assign('top_menus', $top_menus);
		$city_id = $_SESSION['admin']['city_id'];
		$name = M('place') -> where(array('type'=>2,'bd_city_code'=>$city_id)) -> getField('name');
        $my_admin = array('username'=>$_SESSION['admin']['username'], 'rolename'=>$_SESSION['admin']['role_id'],'name'=>$name);
        $this->assign('my_admin', $my_admin);
        $this->display();
        $Home = A('Home/Home');
        //$Home->auto();
    }
    public function test(){
        //header("Content-type:text/html;charset=utf-8");
       // $lang = json_decode(L('js_lang'));
        $stone_name = L('stone_name');
        $js_lang = L('js_lang_st');
        echo($js_lang);
        echo($stone_name);
		

    }

    public function panel() {
        $message = array();
        if (is_dir('./install')) {
            $message[] = array(
                'type' => 'error',
                'content' => "您还没有删除 install 文件夹，出于安全的考虑，我们建议您删除 install 文件夹。",
            );
        }
        if (APP_DEBUG == true) {
            $message[] = array(
                'type' => 'error',
                'content' => "您网站的 DEBUG 没有关闭，出于安全考虑，我们建议您关闭程序 DEBUG。",
            );
        }

        $this->assign('message', $message);
        $system_info = array(
            'pinphp_version' => PIN_VERSION . ' RELEASE '. PIN_RELEASE .' [<a href="http://www.pinphp.com/" class="blue" target="_blank">查看最新版本</a>]',
            'server_domain' => $_SERVER['SERVER_NAME'] . ' [ ' . gethostbyname($_SERVER['SERVER_NAME']) . ' ]',
            'server_os' => PHP_OS,
            'web_server' => $_SERVER["SERVER_SOFTWARE"],
            'php_version' => PHP_VERSION,
            'mysql_version' => mysql_get_server_info(),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'max_execution_time' => ini_get('max_execution_time') . '秒',
            'safe_mode' => (boolean) ini_get('safe_mode') ?  L('yes') : L('no'),
            'zlib' => function_exists('gzclose') ?  L('yes') : L('no'),
            'curl' => function_exists("curl_getinfo") ? L('yes') : L('no'),
            'timezone' => function_exists("date_default_timezone_get") ? date_default_timezone_get() : L('no')
        );
        $this->assign('system_info', $system_info);
        $this->display();
    }



    public function login() { 
        if (IS_POST) {
            $username = I('username','', 'trim');
            $password = I('password','', 'trim'); 
            $verify_code = I('verify_code','', 'trim');
            if(session('verify') != md5($verify_code)){
                $this->error(L('verify_code_error'));
            }
            $admin = M('Admin')->where(array('username'=>$username, 'status'=>1))->find();
            if (!$admin) {
                $this->error(L('admin_not_exist'));
            }
            if ($admin['password'] != md5($password)) {
                $this->error(L('password_error'));
            }

            session('admin', array(
                'id' => $admin['id'],
                'role_id' => $admin['role_id'],
                'username' => $admin['username'],
                'city_id' => $admin['city_id'],
            ));
            M('Admin')->where(array('id'=>$admin['id']))->save(array('last_time'=>time(), 'last_ip'=>get_client_ip()));
            $this->success(L('login_success'), U('index/index'));
        } else {
            $this->display();
        }
    }

    public function logout() {
        session('admin', null);
        $this->success(L('logout_success'), U('index/login'));
        exit;
    }

    public function verify_code() {
    	ob_clean();
        Image::buildImageVerify(4,1,'gif','50','24');
    }

    public function left() {
        $menuid = I('menuid',"",'intval');
        if ($menuid) {
            $left_menu = $this->_mod->admin_menu($menuid);
            foreach ($left_menu as $key=>$val) {
                $left_menu[$key]['sub'] = $this->_mod->admin_menu($val['id']);
            }
        } else {
            /*$left_menu[0] = array('id'=>0,'name'=>L('common_menu'));
            $left_menu[0]['sub'] = array();
            if ($r = $this->_mod->where(array('often'=>1))->select()) {
                $left_menu[0]['sub'] = $r;
            }
            array_unshift($left_menu[0]['sub'], array('id'=>0,'name'=>L('common_menu_set'),'module_name'=>'index','action_name'=>'often_menu'));*/
        }
        $this->assign('left_menu', $left_menu);
        $this->display();
    }

    public function often() {
        if (isset($_POST['do'])) {
            $id_arr = isset($_POST['id']) && is_array($_POST['id']) ? $_POST['id'] : '';
            $this->_mod->where(array('ofen'=>1))->save(array('often'=>0));
            $id_str = implode(',', $id_arr);
            $this->_mod->where('id IN('.$id_str.')')->save(array('often'=>1));
            $this->success(L('operation_success'));
        } else {
            $r = $this->_mod->admin_menu(0);
            $list = array();
            foreach ($r as $v) {
                $v['sub'] = $this->_mod->admin_menu($v['id']);
                foreach ($v['sub'] as $key=>$sv) {
                    $v['sub'][$key]['sub'] = $this->_mod->admin_menu($sv['id']);
                }
                $list[] = $v;
            }
            $this->assign('list', $list);
            $this->display();
        }
    }

    public function map() {
        $r = $this->_mod->admin_menu(0);
        $list = array();
        foreach ($r as $v) {
            $v['sub'] = $this->_mod->admin_menu($v['id']);
            foreach ($v['sub'] as $key=>$sv) {
                $v['sub'][$key]['sub'] = $this->_mod->admin_menu($sv['id']);
            }
            $list[] = $v;
        }
        $this->assign('list', $list);
        $this->display();
    }

    public function auto(){
        //每天自动侦测结算利息
        //实例化模式
        $LoanInterest = D('LoanInterest');
        $Loan = D('Loan');
        $Invest = D('Invest');
        $Member = D('Member');
        $Finance = D('Finance');

        $t_day = strtotime(date('Y-m-d'));
        $loan_interest = $LoanInterest->where(array('op_time'=>$t_day,'status'=>0))->select();
        $finance_data = array();
        if($loan_interest){
            foreach($loan_interest as $val){
                //启动事务，防止断点掉数据
                $Invest->startTrans();
                $loan = $Loan->find($val['loan_id']);
                //调用投资人信息
                $invest_list = $Invest->field('id,order_id,member_id,invest_amount')->where(array('loan_id'=>$loan['id'],'array'=>3))->select();
                //分配利息到投资人 记录资金流水
                $interest = interest($loan['total'] *10000,$loan['interest_rate']/100,1);//这个配资的每个月月息

                foreach($invest_list as $invest){
                    //回利息到余额
                    $member_interest_earning = ($interest * $invest['invest_amount'])/($loan['total'] * 10000);//各投资人的利息收入
                    $member_earning_op = $Member->where(array('id'=>$invest['member_id']))->setInc('balance',$member_interest_earning);
                    if(!$member_earning_op){
                        $this->error($Member->getError());
                    }
                    //生成资金流水记录
                    $finance_data[] = array(
                        'order_id' => make_order_id('Finance'),
                        'total' => $member_interest_earning,
                        'log_type' => 6,
                        'member_id' => $invest['member_id'],
                        'status' => 1,
                        'remark' => '订单号：'.$invest['order_id'].'的利息收入',
                        'item_id' => $invest['id'],
                        'create_time' => time(),
                    );
                }
                $member_interest_earning_finance_op = $Finance->addAll($finance_data);
                $loan_interest_op = $LoanInterest->where(array('id'=>$val['id']))->setField(array('status'=>1));
                if($member_earning_op && $member_interest_earning_finance_op && $loan_interest_op){
                    $Invest->commit();
                    IS_AJAX && $this->ajax_return(1, L('operation_success'), '', 'edit');
                    $this->success('结算成功！');
                }else{
                    $Invest->rollback();//不成功，则回滚
                    $this->error('失败！');
                }
            }

        }
    }
   
}