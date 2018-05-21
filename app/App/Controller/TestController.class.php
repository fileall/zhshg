<?php
namespace App\Controller;
use Think\Controller;
class TestController extends Controller {
	
	public function aa(){
		ob_end_clean();
		echo json_encode(array('aa' => 1,'bb'=>2));
		exit;
	}
	
}
?>