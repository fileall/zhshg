<?php

namespace Admin\Controller;
class ServiceController extends AdminCoreController
{
    public function _initialize()
    {
        parent::_initialize();
        $this->_mod = D('member');
        $this->_cate_mod = D('Place');
        $this->set_mod('member');

    }


    public function _after_list($list){
        if($list){
            //省市区
            $place_ids=array_unique(array_merge (array_column($list,'province_id'),array_column($list,'city_id'),array_column($list,'district_id')));
            $place=M('place')->where(['id'=>['in',$place_ids]])->getField('id,name');
            $this->assign('place', $place);
        }
        return $list;
    }

    protected function _search()
    {
        $map = array();
        ($time_start = I('time_start')) && $map['reg_time'][] = array('egt', strtotime($time_start));
        ($time_end = I('time_end')) && $map['reg_time'][] = array('elt', strtotime($time_end) + 24 * 60 * 60);
        if ($time_start > $time_end) unset($map['reg_time']);
        if ($keywords = I('keyword', '', 'trim')) {
            $map['_string'] = "realname like '%" . $keywords . "%' OR mobile like '%" . $keywords . "%' OR id like '%" . $keywords . "%' OR nickname like '%" . $keywords . "%'";
        }
        $cate_id = I('cate_id', 0, 'intval');
        if ($cate_id) {
            $id_arr = $this->_cate_mod->get_child_ids($cate_id, true);
            $map['cate_id'] = array('IN', $id_arr);//dump($id_arr);die;
            $spid = $this->_cate_mod->where(array('id' => $cate_id))->getField('spid');
            if ($spid == 0) {
                $spid = $cate_id;
            } else {
                $spid .= $cate_id;
            }
        }
        $map['is_qd'] = 1;
        $this->assign('search', array(
            'time_start' => $time_start,
            'time_end' => $time_end,
            'keyword' => $keywords,
            'selected_ids' => $spid,
            'cate_id' => $cate_id,
        ));
        return $map;
    }

    //金果兑换
    public function coupon()
    {
        if (IS_POST) {
            $data = $_POST;
            $goldFruit = M('member')->where(array('id' => $data['uid']))->field('gold_fruit')->find();
            $prices = $data['face_value'] * $data['nums']; //兑换总金果数
            ($data['nums']<0)&& $this->ajax_return(0, '请填写金果张数');
            ($goldFruit['gold_fruit'] < $prices)&& $this->ajax_return(0, '金果不足');

            M()->startTrans();
            //会员表member减去兑换的金果
            $res_money = M('member')->where(['id' => $data['uid']])->setdec('gold_fruit', $prices);
            if (!$res_money) {
                M()->rollback();
                $this->ajax_return(0, L('operation_failure'));
            }
            //金果记录明细
            $arr = [
                'type' => 3,//币种1工资2金元宝3金果4银币
                'uid' => $data['uid'],
                'totalprices' => -$prices,
                'change_desc' => '金果转换成金果劵',
                'add_time' => $_SERVER['REQUEST_TIME']
            ];
            $db = M('account')->add($arr);
            //添加兑换记录到区代金果表withdraw_qd_fruit
            $arr = array(
                'uid'        => $data['uid'],
                'face_value' => $data['face_value'],
                'nums'       => $data['nums'],
                'totalprices'=> $prices,
                'memos'      => $data['memos'],
                'status'     => 2,
                'add_time'   => $_SERVER['REQUEST_TIME']
            );
            $db1 = M('withdraw_qd_fruit')->add($arr);
            //添加金果劵入库到表fruit_coupon
            //$code = substr(md5(time()), 0, 6);
            $valuesData = array();
            for ($i = 0; $i < $data['nums']; $i++) {
                $valuesData[] = array(
                    'uid'         => $data['uid'],
                    'oid'         => $db1,
                    'code'        =>  rand(10000000,99999999),
                    'totalprices' => $data['face_value'],
                    'memos'       => $data['memos'],
                    'status'      => 1,
                    'add_time'    => $_SERVER['REQUEST_TIME']
                );
            }
            $db2 = M('fruit_coupon')->addAll($valuesData); //dump($a);die;
            //回滚操作
            if ($db && $db1 && $db2) {
                M()->commit();
                IS_AJAX && $this->ajax_return(1, L('operation_success'), '', 'coupon');
                $this->success(L('operation_success'));
            } else {
                M()->rollback();
                IS_AJAX && $this->ajax_return(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }
        } else {
            $uid = I('uid');
            $this->assign('uid', $uid);
            if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1, '', $response);
            } else {
                $this->display();
            }
        }

    }

    //添加区代
    public function add()
    {
        if (IS_POST) {
            $user=D('Member');
            if (false === $data = $user->create())
                $this->ajaxReturn(array('status'=>0,'msg'=>$user->getError()));
            if (method_exists($this, '_before_update')) {
                $data = $this->_before_update($data);
            }
            if (empty($data['cate_id'])){
                $this->error('地址不能为空');
            }
            if(!empty($user->where(['cate_id'=>$data['cate_id'],'is_qd'=>1])->find())) {
                $this->error('地址重复');
            }

            if(!false == $user->where(['mobile'=>$data['mobile']])->find()){
                $this->error('手机号重复');
            }

            //添加头像
            if (!empty($_FILES['avatar']['name'])) {
                $result = $this->_upload($_FILES['avatar'], 'avatar', array('width' => C('pin_article_cate_img.width'), 'height' => C('pin_article_cate_img.height')));
                if ($result['error']) {

                    $this->error($result['info']);
                } else {
                    $ext = array_pop(explode('.', $result['info'][0]['savename']));
                    $data['avatar'] = $result['info'][0]['savename'];
                }
            }
            $data['reg_time'] = $_SERVER['REQUEST_TIME'];
            $start = M('member');
            $start->startTrans();
            $db1 = M('member')->add($data);//添加
            $model = M('member_idcard');
            $id = $db1;
            //添加身份证正面
            if (!empty($_FILES['id_card1']['name'])) {
                //$result = $this->_upload($_FILES['id_card1'],'id_card', array('width'=>C('pin_article_cate_img.width'), 'height'=>C('pin_article_cate_img.height')));
                $result = $this->_upload($_FILES['id_card1'], 'id_card');
                if ($result['error']) {
                    $this->error( $result['info']);
                } else {
                    //$ext = array_pop(explode('.', $result['info'][0]['savename']));
                    //$img = str_replace('.' . $ext, '_thumb.' . $ext, $result['info'][0]['savename']);
                    $img = $result['info'][0]['savename'];
                    if ($img) {
                        $sfz1 = array(
                            'type' => 1,
                            'uid' => $id,
                            'img' => $img,
                        );
                        $res1 = $model->add($sfz1);
                    }
                    if (false === $res1) {
                        $start->rollback();
                        $this->error('图片入库失败');
                    }
                }
            } else {
                $res1 = 1;
            }
            //修改身份证反面
            if (!empty($_FILES['id_card2']['name'])) {
                $result = $this->_upload($_FILES['id_card2'], 'id_card');

                if ($result['error']) {
                    $this->error( $result['info']);
                } else {
                    $img = $result['info'][0]['savename'];
                    if ($img) {
                        $sfz2 = array(
                            'type' => 2,
                            'uid' => $id,
                            'img' => $img,
                        );
                        $res2 = $model->add($sfz2);
                    }
                    if (false === $res2) {
                        $start->rollback();
                        $this->error(L('图片入库失败'));
                    }
                }
            } else {
                $res2 = 1;
            }
            //($db1 && $res1 && $res2) ? M('member')->commit() : M('member')->rollback();
            if ($db1 && $res1 && $res2) {
                //我的二维码=mobile
//                $uri = "http://".$_SERVER['HTTP_HOST']."/index.php?m=mobile&c=login&a=register&ewid=".$db1;
//                $ewm=$this->set_qrcode($uri);
//                $res_ewm= M('member')->where(array('id'=>$db1))->setField('ewm',$ewm);
//                if(!$res_ewm){
//                    $start->rollback();
//                    (IS_AJAX) && $this->ajax_return(0, L('operation_failure'));
//                    $this->error(L('operation_failure'));
//                }

                $start->commit();
                (IS_AJAX) && $this->ajax_return(1, L('operation_success'), '', 'add');
                $this->success(L('operation_success'));
            } else {
                $start->rollback();
               (IS_AJAX) && $this->ajax_return(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }

        } else {
            if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1, '', $response);
            } else {
                $this->display();
            }
        }
    }

    /**
     * 修改
     */
    public function edit()
    {

        $mod = D('Member');
        $pk = $mod->getPk();
        if (IS_POST) {
            if (false === $data = $mod->create()) {
                IS_AJAX && $this->ajax_return(0, $mod->getError());
                $this->error($mod->getError());

            }
            if (method_exists($this, '_before_update')) {
                $data = $this->_before_update($data);
            }

            //修改头像
            if (!empty($_FILES['avatar']['name'])) {
                $result = $this->_upload($_FILES['avatar'], 'avatar', array('width' => C('pin_article_cate_img.width'), 'height' => C('pin_article_cate_img.height')));
                if ($result['error']) {
                    $this->ajax_return(0, $result['info']);
                } else {
                    $ext = array_pop(explode('.', $result['info'][0]['savename']));
                    $data['avatar'] = $result['info'][0]['savename'];
                }
            }

            //修改金额
            if (method_exists($this, 'update_money')) {
                $update_money = $this->update_money($data);
                $data = $update_money['data'];
                $mr = $update_money['mr'];
            }


            $start = M();
            $start->startTrans();
            $model = M('member_idcard');
            $id = $data['id'];

            //修改身份证正面
            if (!empty($_FILES['id_card1']['name'])) {
                //$result = $this->_upload($_FILES['id_card1'],'id_card', array('width'=>C('pin_article_cate_img.width'), 'height'=>C('pin_article_cate_img.height')));
                $result = $this->_upload($_FILES['id_card1'], 'id_card');
                if ($result['error']) {
                    $this->ajax_return(0, $result['info']);
                } else {
                    //$ext = array_pop(explode('.', $result['info'][0]['savename']));
                    //$img = str_replace('.' . $ext, '_thumb.' . $ext, $result['info'][0]['savename']);
                    $img = $result['info'][0]['savename'];
                    $aa = $model->where(['uid' => $id, 'type' => 1])->select();
                    if ($aa) {
                        $res1 = $model->where(['uid' => $id, 'type' => 1])->setField('img', $img);
                    } else {
                        $sfz1 = array('uid' => $id, 'type' => 1, 'img' => $img);
                        $res1 = $model->add($sfz1);
                    }

                    if (false == $res1) {
                        $start->rollback();
                        $this->error(L('operation_failure'));
                    }
                }
            }

            //修改身份证反面
            if (!empty($_FILES['id_card2']['name'])) {
                $result = $this->_upload($_FILES['id_card2'], 'id_card');

                if ($result['error']) {
                    $this->ajax_return(0, $result['info']);
                } else {
                    $img = $result['info'][0]['savename'];
                    $bb = $model->where(['uid' => $id, 'type' => 2])->select();
                    if ($bb) {
                        $res2 = $model->where(['uidid' => $id, 'type' => 2])->setField('img', $img);
                    } else {
                        $sfz2 = array('uid' => $id, 'type' => 2, 'img' => $img);
                        $res2 = $model->add($sfz2);
                    }

                    if (false == $res2) {
                        $start->rollback();
                        $this->error(L('operation_failure'));
                    }
                }
            }

            $data['reg_time'] = $_SERVER['REQUEST_TIME'];
            if (false !== $mod->save($data)) {
                //添加明细
                if (!empty($mr) && false === M('account')->addAll($mr)) {
                    $start->rollback();
                    $this->error(L('operation_failure'));
                }

                $start->commit();
                $this->success(L('operation_success'));
            } else {
                $start->rollback();
                $this->error(L('operation_failure'));
            }
        } else {
            $id = I($pk, 'intval');
            $type = I('type', '1', 'intval');
            $info = $mod->find($id);
            //身份证
            $idcard_imgs = M('member_idcard')->where(['uid' => $id])->getField('type,img');
            //省市区
            $place_spid = $info['province_id'] . "|" . $info['city_id'] . "|" . $info['district_id'];


            $this->assign('place_selected_ids', $place_spid);
            $this->assign('idcard_imgs', $idcard_imgs);
            $this->assign('info', $info);
            $this->assign('type', $type);
            $this->assign('open_validator', true);
            //dump($info);
            if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1, '', $response);
            } else {
                $this->display();
            }
        }
    }

    //修改金额
    public function update_money($data)
    {
        $mr = [];
        $now = $_SERVER['REQUEST_TIME'];

        //修改余额
        if (0 < $data['prices'] && $prices_exp = I('prices_exp', '', 'trim')) {//余额

            $prices = $data['prices'];
            $prices = ($prices_exp == '+') ? (0 + $prices) : (0 - $prices);
            $mr[] = [
                'type' => 1,
                'uid' => $data['id'],
                'totalprices' => $prices,
                'change_desc' => '系统调整',
                'add_time' => $now
            ];
            $data['prices'] = ['exp', 'prices' . $prices_exp . $data['prices']];
        } else {
            unset($data['prices']);
        }
        //修改金元宝
        if (0 < $data['gold_acer'] && $gold_acer_exp = I('gold_acer_exp', '', 'trim')) {//余额

            $gold_acer = $data['gold_acer'];
            $gold_acer = ($gold_acer_exp == '+') ? (0 + $gold_acer) : (0 - $gold_acer);
            $mr[] = [
                'type' => 2,
                'uid' => $data['id'],
                'totalprices' => $gold_acer,
                'change_desc' => '系统调整',
                'add_time' => $now
            ];
            $data['gold_acer'] = ['exp', 'gold_acer' . $gold_acer_exp . $data['gold_acer']];
        } else {
            unset($data['gold_acer']);
        }
        //修改金果
        if (0 < $data['gold_fruit'] && $gold_fruit_exp = I('gold_fruit_exp', '', 'trim')) {//余额

            $gold_fruit = $data['gold_fruit'];
            $gold_fruit = ($gold_fruit_exp == '+') ? (0 + $gold_fruit) : (0 - $gold_fruit);
            $mr[] = [
                'type' => 3,
                'uid' => $data['id'],
                'totalprices' => $gold_fruit,
                'change_desc' => '系统调整',
                'add_time' => $now
            ];
            $data['gold_fruit'] = ['exp', 'gold_fruit' . $gold_fruit_exp . $data['gold_fruit']];
        } else {
            unset($data['gold_fruit']);
        }

        //修改银币
        if (0 < $data['silver_coin'] && $silver_coin_exp = I('silver_coin_exp', '', 'trim')) {//余额

            $silver_coin = $data['silver_coin'];
            $silver_coin = ($silver_coin_exp == '+') ? (0 + $silver_coin) : (0 - $silver_coin);
            $mr[] = [
                'type' => 4,
                'uid' => $data['id'],
                'totalprices' => $silver_coin,
                'change_desc' => '系统发放',
                'add_time' => $now
            ];
            $data['silver_coin'] = ['exp', 'silver_coin' . $silver_coin_exp . $data['silver_coin']];
        } else {
            unset($data['silver_coin']);
        }

        $update_money = ['data' => $data, 'mr' => $mr];
        return $update_money;
    }

    //生成二维码
    public function ewm()
    {

        $id = I('id');
        (!$id) && $this->ajaxReturn(['status' => 0, 'msg' => '系统繁忙']);

        $list = $this->_mod->find($id);
        $uri = "http://" . $_SERVER['HTTP_HOST'] . "/index.php?m=mobile&c=login&a=register&ewid=" . $list['mobile'];
        $ewm_url = $this->set_qrcode($uri);
        $res = $this->_mod->where(['id' => $id])->setField('ewm', $ewm_url);

        if ($res) {
            del_file($list['ewm'], 'ewm/');
            $this->ajaxReturn(['status' => 1, 'url' => $ewm_url]);
        } else {
            $this->ajaxReturn(['status' => 0, 'url' => $ewm_url]);
        }

    }

    public function _before_update($data)
    {
        if($data['district_id']){
            $data['vips_qd'] = 1;
         }elseif($data['city_id']){
            $data['vips_qd'] = 2;
         }elseif($data['province_id']){
             $data['vips_qd'] = 3;
        }

        if($data['password'] == 'd85c61834e9239b7bef468a430bbb3dc'||is_null($data['password'])){
            unset($data['password']);
        }
        if($data['paypassword'] == 'd85c61834e9239b7bef468a430bbb3dc'||is_null($data['paypassword'] )){
            unset($data['paypassword']);
        }
        return $data;
    }

    public function user_thumb($id, $img)
    {
        $img_path = avatar_dir($id);
        //会员头像规格
        $avatar_size = explode(',', C('pin_avatar_size'));
        $paths = C('pin_attach_path');

        foreach ($avatar_size as $size) {
            if ($paths . 'avatar/' . $img_path . '/' . md5($id) . '_' . $size . '.jpg') {
                @unlink($paths . 'avatar/' . $img_path . '/' . md5($id) . '_' . $size . '.jpg');
            }
            !is_dir($paths . 'avatar/' . $img_path) && mkdir($paths . 'avatar/' . $img_path, 0777, true);
            Image::thumb($paths . 'avatar/temp/' . $img, $paths . 'avatar/' . $img_path . '/' . md5($id) . '_' . $size . '.jpg', '', $size, $size, true);
        }

        @unlink($paths . 'avatar/temp/' . $img);
    }


    public function ajax_upload_imgs()
    {
        $date_dir = date('ym/d/');
        if (!empty($_FILES['avatar']['name'])) {
            $result = $this->_upload($_FILES['avatar'], 'avatar/' . $date_dir, array());
            if ($result['error']) {
                $this->ajax_return(0, L('illegal_parameters'));
            } else {
                $ext = array_pop(explode('.', $result['info'][0]['savename']));
                $data['img'] = "./data/attachment/avatar/" . $date_dir . $result['info'][0]['savename'];
//              $data['thumb_img'] = str_replace('.' . $ext, '_thumb.' . $ext, $result['info'][0]['savename']);
                $this->ajax_return(1, L('operation_success'), $data['img']);
            }
        }

    }

    //数据导出 开始********************************************
    //导出会员数据
    public function dao()
    {
        $fileName = "会员列表";
        $xlisCell = array('会员id', '会员昵称', '会员姓名', '手机号', '邮箱', 'VIP等级', '头像', '余额', '积分');
        $data = M('member')->field('id,nickname,realname,mobile,email,vips,avatar,prices,integral')->select();
        $this->getExcel($fileName, $xlisCell, $data);
    }

    //下载报表 会员下线列表
    public function export_next()
    {
        ob_end_clean();
        $map = $this->next_list_search();
        //所有下线
        $list = $this->_mod->field('id,vips,realname,mobile,reg_time,status,relation_id')->where($map)->select();
        //该推荐人本人的手机
        $member = $this->_mod->where(['id' => ['in', array_column($list, 'relation_id')]])->getField('id,mobile');
        //所有下线的升级时间
        $sj['_string'] = ' item_type = 5 and status = 2 and dingdan != 0 and member_id in(' . implode(',', array_column($list, 'id')) . ')';
        $member_recharge = M('MemberRecharge')->where($sj)->getField('member_id,add_time');
        $grade = M('GradeRule')->getfield('id,name');
        $data = array();
        foreach ($list as $k => $v) {
            $data[$k]['xh'] = $k + 1; //序号
            $data[$k]['id'] = $v['id']; //id
            $data[$k]['realname'] = $v['realname']; //会员名
            $data[$k]['mobile'] = $v['mobile']; //手机
            $data[$k]['relation'] = $member[$v['relation_id']]; //推荐人手机
            $data[$k]['vips'] = $grade[$v['vips']]; //级别

            $data[$k]['reg_time'] = date('Y-m-d H:i', $v['reg_time']);//注册日期
            $data[$k]['sj_time'] = !empty($member_recharge[$v['id']]) ? date('Y-m-d H:i', $member_recharge[$v['id']]) : '';//升级日期

            ($v['status'] == 0) && $data[$k]['status'] = '禁用';//状态
            ($v['status'] == 1) && $data[$k]['status'] = '正常';
        }

        $headArr = array();
        $headArr[] = '序号';
        $headArr[] = 'id';
        $headArr[] = '会员名	';
        $headArr[] = '手机';
        $headArr[] = '推荐人';
        $headArr[] = '级别';
        $headArr[] = '注册日期';
        $headArr[] = '升级日期';
        $headArr[] = '状态';
        $mobile = $this->_mod->where(' id =' . $map['relation_id'])->getField('mobile');
        $filename = "会员下线列表" . $mobile;
        $this->getExceltjab($filename, $headArr, $data);
    }

    //下载报表 会员列表
    public function export()
    {
        ob_end_clean();

        $map = $this->_search();
        $list = $this->_mod->where($map)->select();
        $place_ids=array_unique(array_merge (array_column($list,'province_id'),array_column($list,'city_id'),array_column($list,'district_id')));
        $place=M('place')->where(['id'=>['in',$place_ids]])->getField('id,name');
        $data = array();
        foreach ($list as $k => $v) {
            $data[$k]['xh'] = $k + 1; //序号
            $data[$k]['id'] = $v['id']; //id
            $data[$k]['nickname'] = $v['nickname']; //呢称
            $data[$k]['realname'] = $v['realname']; //会员名
            $data[$k]['mobile'] = $v['mobile']; //手机
            $data[$k]['place'] = $place[$v['province_id']].$place[$v['city_id']].$place[$v['district_id']];
            $data[$k]['prices'] = $v['prices'];//工资
            $data[$k]['gold_acer'] = $v['gold_acer'];//金元宝
            $data[$k]['gold_fruit'] = $v['gold_fruit'];//金果
            $data[$k]['silver_coin'] = $v['silver_coin'];//银币
            $data[$k]['reg_time'] = date('Y-m-d H:i', $v['reg_time']);//注册日期
            ($v['status'] == 0) && $data[$k]['status'] = '冻结';//状态
            ($v['status'] == 1) && $data[$k]['status'] = '正常';

        }

        $headArr = array();
        $headArr[] = '序号';
        $headArr[] = 'id';
        $headArr[] = '呢称';
        $headArr[] = '会员名';
        $headArr[] = '手机';
        $headArr[] = '地区';
        $headArr[] = '工资';
        $headArr[] = '元宝';
        $headArr[] = '金果';
        $headArr[] = '银币';
        $headArr[] = '注册日期';
        $headArr[] = '状态';
        $filename = "会员列表";
        $this->getExceltjab($filename, $headArr, $data);

    }


}







