<?php 
	require_once 'common.php';
	set_logout();
	deleteCookie("cus_user");
	deleteCookie("cus_pwd");
	redirect('index.php');
?>