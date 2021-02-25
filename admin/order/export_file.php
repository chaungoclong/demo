<?php 
	require_once '../../common.php';
	require_once '../../dist/excel/vendor/shuchkin/simplexlsxgen/src/SimpleXLSXGen.php';

	if(!is_login() || !is_admin()) {
		redirect('admin/form_login.php');
	} 
	
	$getOrderSQL = "SELECT * FROM db_order";
	$listCustomer = db_get($getOrderSQL, 0);
	$field = ['ID', "TỔNG TIỀN", "NGÀY ĐẶT", "TRẠNG THÁI", "ĐỊA CHỈ GIAO HÀNG", "TÊN NGƯỜI NHẬN", "SĐT NGƯỜI NHẬN"];
	$output[] = $field;
	foreach ($listCustomer as $key => $order) {
		$row = [$order['or_id'], number_format(getTotalMoneyAnOrder($order['or_id'])), $order['or_create_at'], $order['or_status'], $order['receiver_add'], $order['receiver_name'], $order['receiver_phone']];
		$output[] = $row;
	}
	$xlsx = SimpleXLSXGen::fromArray( $output );
	$xlsx->downloadAs('list_order' .time(). '.xlsx'); // or downloadAs('books.xlsx')
?>