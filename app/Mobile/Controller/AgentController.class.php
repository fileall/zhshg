<?php

namespace Mobile\Controller;
/**区代类
 * Class AgentController
 * @package Mobile\Controller
 */
class AgentController extends HomeController {

    public function _initialize() {

        parent::_initialize();
        $this->uid=is_login();
        if(!$this->uid) $this->redirect('Login/enter');

        $this->_member=D('Member');//会员表
        $this->_account=D('Account');


        $member=$this->_member->find($this->uid);
        if($member['is_qd']!=1) $this->redirect('Member/mine');
        $this->assign('member',$member);

    }



    //我是区代（服务中心）
    public function agent(){
        $uid=$this->uid;
        $member=$this->get('member');
        $vips_qd=$member['vips_qd'];//区代等级 1区2市3省',
        if($vips_qd==1){
            $field='district_id';
        }else if($vips_qd==2){
            $field='city_id';
        }else{
            $field='province_id';
        }
        $member_count= $this->_member->where([$field=>$member[$field]])->count();
        $merchant_count =M('merchant')->where([$field=>$member[$field]])->count();
        $member['place']=M('qd_rule')->where(['id'=>$vips_qd])->getField('name');//等级

        $member['member_count']=$member_count?$member_count:0;
        $member['merchant_count']=$merchant_count?$merchant_count:0;


        $this->assign('member',$member);
        $this->display();

    }



    //微信传图(已封装)
    public function uploadImage()
    {
        $data = I();
        $nums=$data['nums'];//nums1营业执照2店铺多图
        $folder=($nums==1)?'merchant_img/':'merchant_yyimg/';//nums1店铺多图2营业执照

        $res= wx_upload_img($data,$folder);
//      file_put_contents('wx_file.txt', var_export($res,true));

        if($res){
            $this->ajaxReturn(array('status'=>1,'msg'=>'上传成功','name'=>$res));
        }else{
            $this->ajaxReturn(array('status'=>-1,'msg'=>'上传失败'));
        }

    }

    //区代钱包*********************************************************
    //区代钱包》工资
    public function agent_prices(){
        $this->display();

    }
    //区代钱包》金果
    public function agent_fruit(){
        $this->display();

    }

    //区代钱包》金果兑换
    public function agent_fruit_exchange(){
        if(IS_AJAX){

            $pos = I();
            $uid=$this->uid;
            $now= time();
            $account=$this->_account;
            $member = $this->_member->find($uid);
            $face_value=$pos['face_value'];
            $page_num=$pos['page_num'];
            $face_value<0&&$this->ajaxReturn(['status'=>0,'msg'=>'请输入正确的面值']);
            $page_num<0&&$this->ajaxReturn(['status'=>0,'msg'=>'请输入正确的数量']);
            $nums=$face_value*$page_num;//金额

            //不允许3s内重复操作
            $map=['totalprices'=>'-'.$nums,'uid'=>$uid,'type'=>3];
            $map['add_time']=['gt',$now-3];
            $over_order=$account->where($map)->count();
            $over_order&&$this->ajaxReturn(['status'=>0,'msg'=>'请勿重复提交']);

            //验证
            (!$pos['pas'])&& $this->ajaxReturn(['status'=>0,'msg'=>'请输入支付密码']);
            (!$member['paypassword'])&& $this->ajaxReturn(['status'=>0,'msg'=>'您还未设置支付密码']);
            (st_md5($pos['pas']) != $member['paypassword'])&& $this->ajaxReturn(['status'=>0,'msg'=>'支付密码有误']);
            $nums<0 && exit(json_encode(array('status'=>0,'msg'=>'请输入金额')));
            ($member['gold_fruit'] <$nums) && exit(json_encode(array('status'=>0,'msg'=>'您的余额不足')));
            $data['uid']=$uid;
            $data['face_value']=$face_value;//面值
            $data['nums']=$page_num;//张数
            $data['totalprices']=$nums;//金果总数
            $data['status']=1;//状态 1未审核 2通过 3驳回'
            $data['add_time']=$now;

            $start=M();
            $start->startTrans();

            $res_withdraw=M('withdraw_qd_fruit')->add($data);	//提交审核
            if(!$res_withdraw){
                $start->rollback();
                $this->ajaxReturn(['status'=>0,'msg'=>'操作失败']);
            }
            //减金果
            $jj['gold_fruit'] =['exp',' gold_fruit -'.$nums];//减金果
            $res=$this->_member->where(array('id'=>$uid))->save($jj);
            if(!$res){
                $start->rollback();
                $this->ajaxReturn(['status'=>0,'msg'=>'操作失败']);
            }

            //减金果:明细
            $recharge[] = account_arr(3, $uid,'-'.$nums, '区代金果兑换', $now);//减金果

            $res_account=$account->addAll($recharge);
            if($res_account){
                $start->commit();
                $this->ajaxReturn(['status'=>1,'msg'=>'操作成功','url'=> U('agent')]);
            }else{
                $start->rollback();
                $this->ajaxReturn(['status'=>0,'msg'=>'操作失败',]);
            }
        }else{
            $this->display();
        }

    }

    //区代钱包》银币(共用member)
    //区代钱包》币种流水(共用member)
    //区代钱包》提现(共用member)
    //区代资料修改(共用member)

    //商家钱包*********************************************************











}