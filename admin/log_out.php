<?php 
	require_once '../common.php';
	set_logout();
	deleteCookie("ad_user");
	deleteCookie("ad_pwd");
	redirect('admin/form_login.php');
 ?>