<?php
namespace Home\Controller;
class EvaluationController extends HomeController {
    public function _initialize() {
        parent::_initialize();
    }

    //测评首页
    public function evaluation(){
        $this->display();
    }

    //选择
    public function start(){
        $this->checklogin();
        $type = I('type',1,'intval');
        //评测类型
        $types = C('EvaluationType');

        C('TOKEN_ON',false);
        $this->assign('info',array('type'=>$type,'title'=>$types[$type]));
        $this->display();
    }

    //评测列表
    public function evaluation_list(){
        $this->checklogin();
        $data = I('post.');
        $params_data=array(
            "ws_authcode"=>"149d5c54-982c-11e7-9973-5254002e444c",
            "birthday"=>$data['birthday'],
            "ExamType"=>$data['ExamType']
        );
        //获取评测列表
        $res = $this->_curl("http://www.spc-360.com/WebserviceByJson/getExam",$params_data);

        $info = array(
            'examID' => $res->data->examID,
            'agesId' => $res->data->agesId,
            'Subject' => $res->data->Subject,
            'ExamType' => $res->data->ExamType
        );
        $list = array();
        //处理列表
        foreach($res->data->dimension as $k=>$v){
            foreach ($v->sub as $sk=>$sv) {
                $list[$sv->subNum]['subNum'] = $sv->subNum;
                $list[$sv->subNum]['Title'] = $sv->Title;
                foreach ($sv->options as $ok=>$ov) {
                    $list[$sv->subNum]['list'][] = array(
                        'subID' => $sv->subID,
                        'optionsID' => $ov->optionsID,
                        'OptionName' => $ov->OptionName
                    );
                }
            }
        }

        C('TOKEN_ON',false);
        $this->assign('result',array('list'=>$list,'info'=>$info));
        $this->display();
    }

    //提交测评
    public function submit_evaluation(){
        $this->uid = $this->checklogin();
        $post_data = I('post.');
        $ExamType = $post_data['ExamType'];
        unset($post_data['ExamType']);
        $post_data['ws_authcode'] = "149d5c54-982c-11e7-9973-5254002e444c";
        $res = $this->_curl("http://www.spc-360.com/WebserviceByJson/sendExam",$post_data);
        if($res->status == 1){
            if(M('Evaluation')->add(array('uid'=>$this->uid,'docno'=>$res->DocNo,'type'=>$ExamType,'add_time'=>time()))){
                $this->redirect('Evaluation/ok');
            }else{
                $this->error('系统繁忙，请重试！');
            }
        }else{
            $this->error('系统繁忙，请重试！');
        }
    }

    //提交评测成功后
    public function ok(){
        $this->display();
    }

    //查看测评结果
    public function result(){
        header("Content-type: text/html; charset=utf-8");
        $this->uid = $this->checklogin();
        $id = I('id','','intval');
        $info = M('Evaluation')->where(array('id'=>$id,'uid'=>$this->uid))->find();
        empty($info) && $this->error('非法访问！');

        //获取结果
        $params_data=array(
            "ws_authcode"=>"149d5c54-982c-11e7-9973-5254002e444c",
            "DocNo"=>$info['docno']
        );
        $res = $this->_curl("http://www.spc-360.com/WebserviceByJson/getExamResult",$params_data);
        empty($res->status)&& $this->error('系统繁忙');
        //dump(object_to_array($res->data));exit;

        $this->assign('info',object_to_array($res->data));
        $this->assign('level',C('AlarmLevel'));
        $this->display();
    }

    private function _curl($url,$post_data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
        $output = curl_exec($ch);
        curl_close($ch);
        //打印获得的数据
        return json_decode($output);
    }

}