<?php 
	require_once '../../common.php';
	require_once '../../dist/excel/vendor/shuchkin/simplexlsxgen/src/SimpleXLSXGen.php';

	if(!is_login() || !is_admin()) {
		redirect('admin/form_login.php');
	} 

	$listUser = db_fetch_table('db_admin', 0);
	$field = ['ID', 'USERNAME', 'NAME', 'GENDER', 'EMAIL', 'PHONE', 'ROLE'];
	$output[] = $field;

	foreach ($listUser as $key => $user) {
		$output[] = [$user['ad_id'], $user['ad_uname'], $user['ad_name'], $user['ad_gender'], $user['ad_email'], $user['ad_phone'], $user['ad_active']];
	}

	$xlsx = SimpleXLSXGen::fromArray( $output );
	$xlsx->downloadAs('list_user.xlsx');
 ?>