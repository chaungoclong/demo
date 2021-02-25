<?php 
	require_once '../../common.php';
	require_once '../../dist/excel/vendor/shuchkin/simplexlsxgen/src/SimpleXLSXGen.php';

	if(!is_login() || !is_admin()) {
		redirect('admin/form_login.php');
	} 

	$getRateSQL = "
	SELECT db_rate.*, db_customer.cus_name, db_customer.cus_avatar, db_customer.cus_id, db_product.pro_name, db_product.pro_img , db_product.pro_id
	FROM db_rate
	JOIN db_customer ON db_rate.cus_id = db_customer.cus_id
	JOIN db_product ON db_rate.pro_id = db_product.pro_id
	WHERE 1";

	$listRate = db_get($getRateSQL, 0);
	$field = ['KHÁCH HÀNG', 'SẢN PHẨM', 'NỘI DUNG', 'SỐ SAO', 'NGÀY TẠO'];
	$output[] = $field;

	foreach ($listRate as $key => $rate) {
		$output[] = [$rate['cus_name'], $rate['pro_name'], $rate['r_content'], $rate['r_star'], $rate['r_create_at']];
	}
	//vd($output);
	$xlsx = SimpleXLSXGen::fromArray( $output );
	$xlsx->downloadAs('list_rate' . time(). '.xlsx');
 ?>