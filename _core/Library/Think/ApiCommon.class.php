<?php
// +----------------------------------------------------------------------
// | HDPY APP API Common Class
// | Date 2016/01/15
// | Desc APP端API请求辅助类
// +----------------------------------------------------------------------

namespace Think;

class ApiCommon {
	
	//输出错误信息
	public function Error($C_Status_Code,$error_type,$pack_no,$data = NULL){
		if($error_type=='')
			return;
		$err_info = array('pack_no' => $pack_no , 'data' => $data , 'status' => $C_Status_Code[$error_type]);
		//清空缓冲区内容
		ob_end_clean();
		
		//设置json响应头
		header("Content-type: application/json");
		echo json_encode($err_info);
		exit();
	}	
}
?>