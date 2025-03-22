

<?php 

if (empty($_POST['username'] and $_POST['password'] and $_POST['ip_address'])) {

	echo json_encode(array('status'=>"error",'info'=>'กรุณากรอกข้อมูลให้ครบ'));

}else{

	include '../class.php';

	$use = new Bannawat;

	$login = $use->login($_POST['username'],$_POST['password'],$_POST['ip_address']);

}



?>