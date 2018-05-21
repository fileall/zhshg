<?php
namespace Mobile\Controller;
use Think\Controller;
use Think\View;
use Admin\Org\UploadFile;
class HomeController extends Controller {
    protected $visitor = null;
	
    protected function _initialize(){
        header("Content-type: text/html; charset=utf-8");
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

	//访问不存在的控制器
    public function _empty(){
        $this->error('你猜的没错，技术部的程序猿和程序媛正在玩命开发中！');
    }


    //验证登录
	public function checklogin(){
		//是否登陆
        $user = session('user_auth');
        if(!$user){
            $this->redirect(U('Login/enter'));
        }else{
            return $user['id'];
        }
	}

	//获取uid
    public function get_uid(){ 
        $user = session('user_auth');
        return $user['id'];
    }

    //ajax方式返回
    protected function ajax_return($status, $msg = '', $data = '', $dialog = ''){
        $ajax_data = array(
            'status' => $status,
            'msg' => $msg,
            'data' => $data,
            'dialog' => $dialog
        );
        $this->ajaxReturn($ajax_data);
    }
    
    /**
     * 上传文件默认规则定义
     */
    protected function _upload_init($upload) {
        $allow_max = C('pin_attr_allow_size'); //读取配置
        $allow_exts = explode(',', C('pin_attr_allow_exts')); //读取配置
        $allow_max && $upload->maxSize = $allow_max * 1024;   //文件大小限制
        $allow_exts && $upload->allowExts = $allow_exts;  //文件类型限制
        $upload->saveRule = 'uniqid';
        return $upload;
    }

    /**
     * 上传文件
     */
    protected function _upload($file, $dir = '', $thumb = array(), $save_rule='uniqid') {
        $upload = new UploadFile();
        if ($dir) {
            $upload_path = C('pin_attach_path') . $dir . '/';
            $upload->savePath = $upload_path;
        }
        if ($thumb) {
            $upload->thumb = true;
            $upload->thumbMaxWidth = $thumb['width'];
            $upload->thumbMaxHeight = $thumb['height'];
            $upload->thumbPrefix = '';
            $upload->thumbSuffix = isset($thumb['suffix']) ? $thumb['suffix'] : '_thumb';
            $upload->thumbExt = isset($thumb['ext']) ? $thumb['ext'] : '';
            $upload->thumbRemoveOrigin = isset($thumb['remove_origin']) ? true : false;
        }

        //自定义上传规则
        $upload = $this->_upload_init($upload);
        if( $save_rule!='uniqid' ){
            $upload->saveRule = $save_rule;
        }

        if ($result = $upload->uploadOne($file)) {
            return array('error'=>0, 'info'=>$result);
        } else {
            return array('error'=>1, 'info'=>$upload->getErrorMsg());
        }
    }



    public function verify($id=1){
        $config =    array(
        'fontSize'    =>    18,    // 验证码字体大小
        'length'      =>    4,     // 验证码位数
        'imageH'      =>    34, // 关闭验证码杂点
        'useNoise'    =>    false, // 关闭验证码杂点
        'useCurve'    =>    false,
        );
        $Verify = new \Think\Verify($config);
        $Verify->entry($id);
    }



	public function getOrderTracesByJson($dingdan,$wuliu,$dan){
        $requestData= "{'OrderCode':'".$dingdan."','ShipperCode':'".$wuliu."','LogisticCode':'".$dan."'}";

        $datas = array(
            'EBusinessID' => '1270083',
            'RequestType' => '1002',
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2',
        );
        $datas['DataSign'] = $this->encrypt($requestData, 'c9ae2c93-ab2c-4213-97ab-fe562bfb5107');
        $result=$this->sendPost('http://api.kdniao.cc/Ebusiness/EbusinessOrderHandle.aspx', $datas);

        //根据公司业务处理返回的信息......
        return $result;
    }

    /**
     *  云片指定模板单发
     * @param  string $mobile 手机号
     * @param  string $tpl_id 模板ID
     * @param  string $tpl_value 设置的键值对
     * @return url响应返回的数组
     */
//  protected function sendSms($mobile,$tpl_id,$tpl_value) {
//      $url = 'https://sms.yunpian.com/v2/sms/tpl_single_send.json';
//      $data = array(
//              'tpl_id'=>$tpl_id,
//              'tpl_value'=>$tpl_value,
//              'apikey'=>'76f75fec654e59fc20a55ba4caebede6',
//              'mobile'=>$mobile
//      );
//
//      //发送请求
//      $result = $this->sendPost($url, $data);
//      return $result;
//  }

    /**
     *  post提交数据
     * @param  string $url 请求Url
     * @param  array $data 提交的数据
     * @return url响应返回的html
     */
//  protected function sendPost($url, $data) {
//      $ch = curl_init();
//
//      curl_setopt ($ch, CURLOPT_URL, $url);
//      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:text/plain;charset=utf-8', 'Content-Type:application/x-www-form-urlencoded','charset=utf-8'));
//      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//      curl_setopt($ch, CURLOPT_TIMEOUT, 10);
//      curl_setopt($ch, CURLOPT_POST, 1);
//      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
//      $json_data = curl_exec($ch);
//
//      return json_decode($json_data,true);
//  }

    /**
     * 电商Sign签名生成
     * @param data 内容
     * @param appkey Appkey
     * @return DataSign签名
     */
    public function encrypt($data, $appkey) {
        return urlencode(base64_encode(md5($data.$appkey)));
    }

    //百度云点播
    public function baidu_cloud_vod(){
        import("Vendor.BaiduCloud.VodClientSample", '', '.php');
        $vod = new \VodClientSample();
        $client = $vod->getVod();
        $client->ak = $vod::$ak;
        $client->sk = $vod::$sk;
        return $client;
    }

    /**
     * 简单对称加密算法之加密
     * @param String $string 需要加密的字串
     * @param String $skey 加密EKY
     * @author Anyon Zou <zoujingli@qq.com>
     * @date 2013-08-13 19:30
     * @update 2014-10-10 10:10
     * @return String
     */
    function encode($string = '', $skey = 'cxphp') {
        $strArr = str_split(base64_encode($string));
        $strCount = count($strArr);
        foreach (str_split($skey) as $key => $value)
            $key < $strCount && $strArr[$key].=$value;
        return str_replace(array('=', '+', '/'), array('a1b2c', 'a123b', 'ab12c'), join('', $strArr));
    }

    /**
     * 简单对称加密算法之解密
     * @param String $string 需要解密的字串
     * @param String $skey 解密KEY
     * @author Anyon Zou <zoujingli@qq.com>
     * @date 2013-08-13 19:30
     * @update 2014-10-10 10:10
     * @return String
     */
    function decode($string = '', $skey = 'cxphp') {
        $strArr = str_split(str_replace(array('a1b2c', 'a123b', 'ab12c'), array('=', '+', '/'), $string), 2);
        $strCount = count($strArr);
        foreach (str_split($skey) as $key => $value)
            $key <= $strCount  && isset($strArr[$key]) && $strArr[$key][1] === $value && $strArr[$key] = $strArr[$key][0];
        return base64_decode(join('', $strArr));
    }

    //生成二维码
    protected function set_qrcode($url,$level=3,$size=4){
        Vendor('phpqrcode');

        //二维码名称
        $ewm_name = uniqid().rand(1000,9999).'.png';
        //二维码存储路径
        $path = C('pin_attach_path').'ewm/'.$ewm_name;
        $errorCorrectionLevel =intval($level) ;//容错级别
        $matrixPointSize = intval($size);//生成图片大小
        //生成二维码图片
        $object = new \QRcode();
        $object->png($url,$path, $errorCorrectionLevel, $matrixPointSize, 2);

        return $ewm_name;
    }

}