<?php
namespace Mobile\Controller;

/**钱包的其他项：聚宝盆、银楼
 * Class WalletOtherController
 * @package Mobile\Controller
 */
class WalletOtherController extends HomeController
{
    public function _initialize()
    {
        parent::_initialize();
        $this->uid = is_login();
        if (!$this->uid) {
            $this->redirect('Login/enter');
        }
        $this->_member = D('Member');
        $this->_account = D('Account');
        $this->_jbp = D('MemberJbp');

        $member = $this->_member->find($this->uid);
        $this->assign('member', $member);


    }


    #聚宝盆开始***************************************
    //聚宝盆
    public function basin()
    {
        $member = $this->_member->find($this->uid);

        $all_acer_jc = $member['gold_acer_jc'] + $member['silver_acer_jc'];
        empty($all_acer_jc) && $all_acer_jc = 0;
        $sy = C('pin_jbp_bs') * 100;

        $this->assign('sy', $sy);
        $this->assign('all_acer_jc', $all_acer_jc);
        $this->display();
    }

    //我的钱包》聚宝盆明细界面&&可提取界面
    public function basin_account0()
    {
        $data['member_id'] = $this->uid;
        $status = I('status');
        $data['status'] = ($status != 2) ? array('in', [1, 2,3]) : 2;//状态 1寄存中2未提取3已提取
        //判断聚宝盆可提现状态
        $jbp = M('member_jbp')->where($data)->order('id desc')->select();

        $today = strtotime(date('Y-m-d', time()));//今天0点
        $member_jbp = M('member_jbp');
        $tq_ids=[];
        foreach ($jbp as $k => $v) {
            $start = strtotime(date('Y-m-d', $v['add_time']));//开始时间
            $tian = $v['jbp_zq'];//寄存时候周期
            $aaa = ($start + $tian * 3600 * 24 - $today) / (24 * 3600);
            $jbp[$k]['other_days'] = $aaa;
            //找到可提取的记录
            if ($aaa == 0 || $aaa < 0) {
                $tq_ids[]=$v['id'];
                $jbp[$k]['status'] = 2;
            }
//            else{
//                $tq_ids[]=$v['id'];
//                $jbp[$k]['status'] = 2;
//             }
        }
        !empty($tq_ids)&&$res = $member_jbp->where(array('id' => ['in',$tq_ids]))->save(array('status' => 2));//立即修改记录

        $this->assign('jbp', $jbp);
        $this->display();

    }

    //我的钱包》聚宝盆明细界面&&可提取界面
    //为提取时候防止重复提取=>需要对数据进行处理改为可提取
    public function basin_account()
    {

        $uid=$this->uid;
        $member_jbp = M('member_jbp');
        $status = I('status');//状态 1寄存中2未提取3已提取
        if($status==2){//未提取
             $sql= 'member_id = '.$uid.' and status=2 or (status=1 and add_time <('.time().'- (jbp_zq * 3600 * 24)))';
             $jbp = $member_jbp->where($sql)->order('id desc')->select();//寄存时间到的可提取数据
             foreach ($jbp as $k => $v) {
                $tq_ids[]=$v['id'];
                $jbp[$k]['status'] = 2;
             }
        }else{// 其他：全部
            $map['member_id'] = $uid;
            $jbp = $member_jbp->where($map)->order('id desc')->select();

            $today = strtotime(date('Y-m-d', time()));//今天0点
            $tq_ids=[];
            foreach ($jbp as $k => $v) {
                if($v['status']==1){
                    $start = strtotime(date('Y-m-d', $v['add_time']));//开始时间
                    $tian = $v['jbp_zq'];//寄存时候周期
                    $aaa = ($start + $tian * 3600 * 24 - $today) / (24 * 3600);
                    $jbp[$k]['other_days'] = $aaa;//寄存中=>剩余天数
                    if ($aaa == 0 || $aaa < 0) {//找到时间到期的记录
                        $tq_ids[]=$v['id'];
                        $jbp[$k]['status'] = 2;
                    }
                }
            }
        }
        //寄存中到期修改为可提取
        !empty($tq_ids)&&$res = $member_jbp->where(array('id' => ['in',$tq_ids]))->save(array('status' => 2));

        $this->assign('jbp', $jbp);
        $this->display();

    }

    //我的钱包》聚宝盆寄存
    public function basin_in()
    {
        $uid = $this->uid;
        if (IS_POST) {
            $pos = I('post.');
            $nums = $pos['nums'];
            $now = time();
            $member = $this->_member->find($uid);
            //不允许3s内重复操作
            $map=['totalprices'=>$nums,'member_id'=>$uid,'add_time'=>['gt',$now-3]];
            $over_order=$this->_jbp ->where($map)->count();
            $over_order&&$this->ajaxReturn(['status'=>0,'msg'=>'请勿重复提交']);

            ($nums < 0 || $nums == 0) && $this->ajaxReturn(['status' => 0, 'msg' => '请输入金额!']);
            (!$pos['pas'])&& $this->ajaxReturn(['status'=>0,'msg'=>'请输入支付密码']);
            (!$member['paypassword'])&& $this->ajaxReturn(['status'=>0,'msg'=>'您还未设置支付密码']);
            (st_md5($pos['pas']) != $member['paypassword'])&& $this->ajaxReturn(['status'=>0,'msg'=>'支付密码有误']);
            ($member['gold_acer'] <$nums) && exit(json_encode(array('status'=>0,'msg'=>'您的余额不足')));

            $coin = $nums * C('pin_jbp_bs');//送银币数量
            $start = M();
            $start->startTrans();
            //生成聚宝盆记录
            $jyb['member_id'] = $uid;
            $jyb['totalprices'] = $nums;
            $jyb['coin'] = $coin;
            $jyb['jbp_zq'] = C('pin_jbp_zq');//寄存时候周期
            $jyb['memos'] = '聚宝盆存金元宝';
            $jyb['status'] = 1;//1寄存中/2未提取/3已提取
            $jyb['add_time'] = $now;
            $res_jyb =$this->_jbp ->add($jyb);
            if (!$res_jyb) {
                $start->rollback();
                $this->ajaxReturn(['status' => 0, 'msg' => '操作失败!']);
            }

            //金额变化
            $acer['gold_acer_jc'] = $member['gold_acer_jc'] + $nums;//聚宝盆加元宝
            $acer['gold_acer'] = $member['gold_acer'] - $nums;//减元宝
            $acer['silver_coin'] = $member['silver_coin'] + $coin;

            $res_member = $this->_member->where(array('id' => $uid))->save($acer);//银币
            if (!$res_member) {
                $start->rollback();
                $this->ajaxReturn(['status' => 0, 'msg' => '操作失败!']);
            }
            //流水明细
            $data[] = account_arr(2, $uid, '-' . $nums, '聚宝盆存金元宝', $now);
            ($coin > 0) && $data[] = account_arr(4, $uid, $coin, '聚宝盆送银币', $now);
            $res_acc = $this->_account->addAll($data);//生成金元宝流水单
            if (!$res_acc) {
                $start->rollback();
                $this->ajaxReturn(['status' => 0, 'msg' => '操作失败!']);
            }

            $start->commit();
            $this->ajaxReturn(['status' => 1, 'msg' => '操作成功!', 'url' => U('WalletOther/basin')]);
        } else {
            $this->display();

        }

    }

    //我的钱包》聚宝盆提取
    public function basin_out()
    {
        $pos = I('post.');
        $uid = $this->uid;
        $jbp_modle=$this->_jbp;
        $member_modle=$this->_member;
        $member = $member_modle->find($uid);
        $jbp = $jbp_modle->where(['id'=>$pos['id'],'status'=>2])->find();//status 支出状态 1寄存中2未提取3已提取

        !$jbp&& $this->ajaxReturn(['status' => 0, 'msg' => '已提取!']);
        ($jbp['totalprices'] > $member['gold_acer_jc'])&& $this->ajaxReturn(['status' => 0, 'msg' => '操作异常!']);

        $start = M();
        $start->startTrans();
        $tq =$jbp_modle->where(array('id' => $pos['id']))->save(['status' => 3]);//改订单状态
        if (!$tq) {
            $start->rollback();
            $this->ajaxReturn(['status' => 0, 'msg' => '操作失败!']);
        }

        $acer = array();
        //用户表加元宝减寄存的元宝
        $acer['gold_acer'] = ['exp', ' gold_acer +' . $jbp['totalprices']];
        $acer['gold_acer_jc'] = ['exp', ' gold_acer_jc -' . $jbp['totalprices']];
        $change_money =$member_modle->where(array('id' => $member['id']))->save($acer);
        if (!$change_money) {
            $start->rollback();
            $this->ajaxReturn(['status' => 0, 'msg' => '操作失败!']);
        }
        //元宝流水单
        $data = account_arr(2, $uid, $jbp['totalprices'], '聚宝盆提取金元宝', time());

        $acc_res = M('Account')->add($data);//生成金元宝流水单
        if (!$acc_res) {
            $start->rollback();
            $this->ajaxReturn(['status' => 0, 'msg' => '操作失败!']);
        }

        $start->commit();
        $this->ajaxReturn(['status' => 1, 'msg' => '操作成功!', 'url' => U('WalletOther/basin')]);
    }
    #聚宝盆结束***************************************



    #银楼开始***************************************
    //银楼
    public function silver_store()
    {
        if (IS_POST) {//置换
            $pos = I('post.');
            $uid = $this->uid;
            $account = $this->_account;
            $now = time();
            $nums = $pos['nums'];//将要置换的元宝数量
//            //不允许3s内重复操作
//            $map=['totalprices'=>$nums,'uid'=>$uid,'type'=>3,'add_time'=>['gt',$now-3]];
//            $over_order=$account->where($map)->count();
//            $over_order&&$this->ajaxReturn(['status'=>0,'msg'=>'请勿重复提交']);

            (!$nums||$nums < 0) && $this->ajaxReturn(array('status' => 0, 'msg' => '请输入金额'));
//            ($nums> 300) && $this->ajaxReturn(array('status' => 0, 'msg' => '每次购买的个数不超过300元宝'));

            $member = $this->_member->find($uid);//己方
            $vip = $member['vips'];
            ($vip == 1) && $this->ajaxReturn(['status' => 0, 'msg' => '您还不是掌柜']);
            ($member['paypassword'] !== st_md5($pos['pas'])) && $this->ajaxReturn(array('status' => 0, 'msg' => '请输入正确支付密码'));
            ($member['gold_acer'] < $nums) && $this->ajaxReturn(array('status' => 0, 'msg' => '您的余额不足'));

            $start = M();
            $start->startTrans();
            //计算当天时间
            $time_str = date('Y-m-d', $now);//今天0点
            $time_start = strtotime($time_str);//时间戳s
            $time_end = $time_start + 24 * 3600;
            $time_rule['add_time'] = array('between', array($time_start, $time_end));
            $time_rule['account_type'] = 6;//银楼置换
            $time_rule['type'] = 2;
            $time_rule['uid'] = $uid;
            //等级规定每日所换银币数量
            $vip_max_nums = M("grade_rule")->where("id='$vip'")->getField("max_acer_nums");//当前级别最大元宝限制
            $account_nums = abs($this->_account->where($time_rule)->getField("sum(totalprices)"));//当天元宝总置换金额
            $account_nums += $nums;
            $flag = $vip_max_nums < $account_nums;
            $flag && $this->ajaxReturn(['status' => 0, 'msg' => '当前级别每日最高可置换' . $vip_max_nums . '元宝']);

            $yb_nums = $nums * C('pin_yl_bs');
            //减元宝&&加银币
            $save = [
                'gold_acer' => $member['gold_acer'] - $nums,
                'silver_coin' => $member['silver_coin'] + $yb_nums,
            ];
            $res_self = $this->_member->where(array('id' => $uid))->save($save);
            if (!$res_self) {
                $start->rollback();
                $this->ajaxReturn(['status' => 0, 'msg' => '操作失败!']);
            }
            //$type,$uid,$totalprices,$change_desc,$add_time,$oid=0,$account_type=0,$account_nums,$attach_field=0
            //减元宝&&加银币明细
            $recharge[] = account_arr(2, $uid, '-' . $nums, '银楼置换', $now, 0, 6);
            $recharge[] = account_arr(4, $uid, $yb_nums, '银楼置换', $now, 0, 6);
            $res_account = $account->addAll($recharge);

            if ($res_account) {
                $start->commit();
                $this->ajaxReturn(['status' => 1, 'msg' => '操作成功', 'url' => U('member/mine')]);
            } else {
                $start->rollback();
                $this->ajaxReturn(['status' => 0, 'msg' => '操作失败',]);
            }
        } else {//展示
            $this->display();
        }
    }
    #银楼结束***************************************


}?>