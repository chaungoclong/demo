<?php 
	require_once '../common.php';
	require_once 'include/header.php';

	if(!is_login() || !is_admin()) {
		redirect('admin/form_login.php');
	}
	
	require_once 'include/sidebar.php';
	require_once 'include/navbar.php';
	require_once 'include/home.php';
 ?>

 