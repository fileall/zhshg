<?php
$key = "n'NI&u#+lFA0y@;$6Wj=5(~9";
//API请求基本参数配置
$pack_no = '20003';
//时间秒数2
$date = '1453433081';
//登录用户ID，没有就为空
$user_id ='2ba0oaim74jEOJG1GA4vze|jia|sjleAh4wn4OozI2q4f5I';
//设备编号，不用变
$deviceId = '357143046443542';
//权限，没有为空
$roles = '';
//请求Thonk 
$token = md5($pack_no.$user_id.$date.$key);
//请求待处理的数据 
$data = array( 
   	"name" => "11",
   	"title" => "11",
   	"member_name" => "1112",
   	"nums" => "123456",
);

//求的URLkk
$url = "./app.php?m=App&c=api&a=processing&requestData=";
//请求参数
$request_data = array('pack_no' => $pack_no , 'date' => $date , 'user_id' => $user_id , 'deviceId' => $deviceId , 'token' => $token , 'roles' => $roles , 'data' => $data);
//输出URL
echo "<a href='".$url.json_encode($request_data)."'>".$url.json_encode($request_data)."</a>";




//$key = "n'NI&u#+lFA0y@;$6Wj=5(~9";
//$str ='{"token":"555755D46BBC5B6D12A013263B860E2A","deviceId":"863709034488397","roles":"","pack_no":"10002","date":"1510539386038","user_id":"2ba0oaim74jEOJG1GA4vze|jia|sjleAh4wn4OozI2q4f5I","data":{"gender":"1"}}';
//$da =json_decode($str,true);
//$pack_no = $da['pack_no'];
////时间秒数2 
//$date = '1502795679101';
////登录用户ID，没有就为空2a84Tp2FY4oij1|jia|Zbfkb7RWg6uDSLS2SlVWLXsY
//$user_id = $da['user_id']; 
////设备编号，不用变
//$deviceId = '862758038353249';  
////权限，没有为空
//$roles = '';   
////请求Thonk        
//$token = md5($pack_no.$user_id.$date.$key);
//
//$data = $da['data'];     
////求的URLkk     
//$url = "http://hhsc6.0791jr.com/app.php?m=App&c=api&a=processing&requestData= 
//
//";
////请求参数
//$request_data = array('pack_no' => $pack_no , 'date' => $date , 'user_id' => $user_id , 'deviceId' => $deviceId , 'token' => $token , 'roles' => $roles , 'data' => $data); 
////输出URL
//echo "<a href='".$url.json_encode($request_data)."'>".$url.json_encode($request_data)."</a>";

?>

