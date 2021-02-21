<?php 
	require_once '../../common.php';
	require_once '../../dist/excel/vendor/shuchkin/simplexlsxgen/src/SimpleXLSXGen.php';
	$listCustomer = db_fetch_table("db_customer", 0);
	$field = ['ID', "TÊN", "NGÀY SINH", "GIỚI TÍNH", "EMAIL", "PHONE", "TRẠNG THÁI"];
	$output[] = $field;
	foreach ($listCustomer as $key => $customer) {
		$row = [$customer['cus_id'], $customer['cus_name'], $customer['cus_dob'], $customer['cus_gender'], $customer['cus_email'], $customer['cus_phone'], $customer['cus_active']];
		$output[] = $row;
	}
	$xlsx = SimpleXLSXGen::fromArray( $output );
	$xlsx->downloadAs('books.xlsx'); // or downloadAs('books.xlsx')
?>