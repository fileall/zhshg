<?php
namespace Mobile\Controller;

use Think\Controller;
use Think\View;

class AutoController extends Controller {
	
    protected function _initialize(){
        //初始化网站配置
        $setting = array();
        if(F('setting')){
            $setting = F('setting');
        }else{
            $setting = D('Setting')->setting_cache();
            F('setting',$setting);
        }
        C($setting); 
	} 

	//银币=>金果、工资、手续费
	public function currency_conversion()
	{
        $ip = $_SERVER["REMOTE_ADDR"];

        if ($ip != '101.200.72.164') {
            $this->redirect('Index/index');
            die;
        }
        G('begin');
        $now=time();
        $member_model = D('Member');
        $account_model=D('Account');//币种明细
        $member_model->startTrans();
        do{
            #余额比银币的倍数(所以转换时：余额=银币/$goal_to_price)
            $goal_to_price = 100;

            #金果比银币的倍数(所以转换时：金果=银币/$goal_to_jg)
            //$goal_to_jg = 100*C('pin_jg_scj');
            $goal_to_jg = 100;


            #掉出的银币个数最低值(5=10000*成长率)
            $gold_auto_critical = 5;

            #银币每日成长率
            $gold_auto_day = C('pin_silver_coin')/10000;

            #银币转换手续费%
            $gold_to_change_scale = C('pin_jb_ye_sxf')/100;

            #银币转余额转换率%
            $goal_than_price   =  C('pin_jb_ye') /100;

            #银币转金果转换率%
            $goal_than_fruit = C('pin_jb_ye_jg') / 100;

            #用户信息
            $member_list = $member_model->where(array('status'=>1))->field('id,silver_coin,prices,gold_fruit,mobile')->select();
            $count =0;

            foreach($member_list as $k => $v) {
                $data = array();
                $arr  = array();
                //将要转换银币个数
                $conversions_num = floor(($v['silver_coin']*$gold_auto_day));//舍去小数

                $goal_change_fee=0;//intval
                $change_price = 0;
                $change_fruit = 0;

                //银币转余额&&金果	#如果调出银币达到最低值
                if ($conversions_num >= $gold_auto_critical) {
                    #手续费(银币数)
                    //$goal_change_fee = $gold_to_change_scale * $conversions_num;
                    #转换余额(银币数*转换率/币种比例)
                    $change_price = intval(100*$goal_than_price * $conversions_num/$goal_to_price)/100;//保留两位小数
                    #转换金果
                    $change_fruit = intval(100*$goal_than_fruit * $conversions_num/$goal_to_jg)/100;
                    #转换后银币、金果、余额
                    $data['gold_fruit'] =  $v['gold_fruit'] + $change_fruit;
                    $data['prices'] =  $v['prices'] + $change_price;
                    $data['silver_coin'] =  $v['silver_coin']  - $conversions_num;

                    #币种明细 币种1工资2金元宝3金果4银币',
                    $arr[] = account_arr(1, $v['id'],$change_price,'银币成长',$now);
                    $arr[] = account_arr(3, $v['id'],$change_fruit,'银币成长',$now);
                    $arr[] = account_arr(4, $v['id'],'-'.$conversions_num,'银币成长',$now);
                    if (!$account_model->addAll($arr)) {
                        $member_model->rollback();
                        file_put_contents('AotoCoinError.log','['.date('Y-m-d H:i:s').']明细记录更新失败'.PHP_EOL,FILE_APPEND);
                        $bool = true;//明细生成失败=>回滚=>while循环重新开始币种成长
                        break;
                    }

                    #改用户表
                    $res = $member_model->where(array('id'=>$v['id']))->save($data);

                    if ($res === false) {
                        $member_model->rollback();
                        file_put_contents('AotoCoinError.log','['.date('Y-m-d H:i:s').']用户数据更新失败'.PHP_EOL,FILE_APPEND);
                        $bool = true;//用户表修改失败=>回滚并跳出for循环=>while循环重新开始币种成长
                        break;
                    } else{
                        $bool = false;
                    }
                    $count +=1;

                }


            }
        } while ($bool);
        $member_model->commit();//事务提交
        G('end');
        $auto_time=G('begin','end');
        echo $auto_time;
        file_put_contents('AotoCoin.log'
            ,'['.date('Y-m-d H:i:s').']银币成长结束:共计'.$count.'人,用时: '.$auto_time.'s'.PHP_EOL
            ,FILE_APPEND);

    }

    //银币=>金果、工资、手续费
    public function test()
    {
        die;
        $ip = $_SERVER["REMOTE_ADDR"];

        if ($ip != '101.200.72.164') {
            $this->redirect('Index/index');
            die;
        }
        G('begin');
        $now=time();
        $member_model = D('Member');
        $account_model=D('Account');//币种明细
        $member_model->startTrans();
        do{
            #余额比银币的倍数(所以转换时：余额=银币/$goal_to_price)
            $goal_to_price = 100;

            #金果比银币的倍数(所以转换时：金果=银币/$goal_to_jg)
            $goal_to_jg = 100*C('pin_jg_scj');

            #掉出的银币个数最低值(5=10000*成长率)
            $gold_auto_critical = 5;

            #银币每日成长率
            $gold_auto_day = C('pin_silver_coin')/10000;

            #银币转换手续费%
            $gold_to_change_scale = C('pin_jb_ye_sxf')/100;

            #银币转余额转换率%
            $goal_than_price   =  C('pin_jb_ye') /100;

            #银币转金果转换率%
            $goal_than_fruit = C('pin_jb_ye_jg') / 100;

            #用户信息
            $member_list = $member_model->where(array('status'=>1))->field('id,silver_coin,prices,gold_fruit,mobile')->select();
            $count =0;

            foreach($member_list as $k => $v) {
                $data = array();
                $arr  = array();
                //将要转换银币个数
                $conversions_num = floor(($v['silver_coin']*$gold_auto_day));//舍去小数

                $goal_change_fee=0;//intval
                $change_price = 0;
                $change_fruit = 0;

                //银币转余额&&金果	#如果调出银币达到最低值
                if ($conversions_num >= $gold_auto_critical) {
                    #手续费(银币数)
                    //$goal_change_fee = $gold_to_change_scale * $conversions_num;
                    #转换余额(银币数*转换率/币种比例)
                    $change_price = intval(100*$goal_than_price * $conversions_num/$goal_to_price)/100;//保留两位小数
                    #转换金果
                    $change_fruit = intval(100*$goal_than_fruit * $conversions_num/$goal_to_jg)/100;
                    #转换后银币、金果、余额
                    $data['gold_fruit'] =  $v['gold_fruit'] + $change_fruit;
                    $data['prices'] =  $v['prices'] + $change_price;
                    $data['silver_coin'] =  $v['silver_coin']  - $conversions_num;

                    #币种明细 币种1工资2金元宝3金果4银币',
                    $arr[] = account_arr(1, $v['id'],$change_price,'银币成长',$now);
                    $arr[] = account_arr(3, $v['id'],$change_fruit,'银币成长',$now);
                    $arr[] = account_arr(4, $v['id'],'-'.$conversions_num,'银币成长',$now);
                    if (!$account_model->addAll($arr)) {
                        $member_model->rollback();
                        file_put_contents('aoto_coin.log','['.date('Y-m-d H:i:s').']明细记录更新失败'.PHP_EOL,FILE_APPEND);
                        $bool = true;//明细生成失败=>回滚=>while循环重新开始币种成长
                        break;
                    }

                    #改用户表
                    $res = $member_model->where(array('id'=>$v['id']))->save($data);

                    if ($res === false) {
                        $member_model->rollback();
                        file_put_contents('aoto_coin.log','['.date('Y-m-d H:i:s').']用户数据更新失败'.PHP_EOL,FILE_APPEND);
                        $bool = true;//用户表修改失败=>回滚并跳出for循环=>while循环重新开始币种成长
                        break;
                    } else{
                        $bool = false;
                    }
                    $count +=1;

                }


            }
        } while ($bool);
        $member_model->commit();//事务提交
        G('end');
        $auto_time=G('begin','end');
        echo $auto_time;
        file_put_contents('AotoCoin.log'
            ,'['.date('Y-m-d H:i:s').']银币成长结束:共计'.$count.'人,用时: '.$auto_time.'s'.PHP_EOL
            ,FILE_APPEND);

    }




}