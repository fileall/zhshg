<?php
header('Content-Type: text/html; charset=utf-8');
include '../pay/config.php'; 
$sql="select * from jrkj_yhq where endtime<'".strtotime(date('Y-m-d',strtotime('+0 day')))."' and status=0";
//echo $sql."<br>";
$rs=mysql_query($sql,$conn);
while($total = mysql_fetch_array($rs)){
	//echo $total['endtime']."------".$total['status']."<br>";
	$sqls="update jrkj_yhq set status=2 where id=".$total['id']."";
	mysql_query($sqls,$conn);
}

$sqlt="select * from jrkj_yhq_cate where endtime<'".strtotime(date('Y-m-d',strtotime('+0 day')))."'";
echo $sqlt."<br>";
$rst=mysql_query($sqlt,$conn);
while($totals = mysql_fetch_array($rst)){
	//echo $totals['endtime']."------".$totals['status']."<br>";
	$sqlt="update jrkj_yhq_cate set status=2 where id=".$totals['id']."";
	mysql_query($sqlt,$conn);
}
?>