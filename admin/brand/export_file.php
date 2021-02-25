<?php 
	require_once '../../common.php';
	require_once '../../dist/excel/vendor/shuchkin/simplexlsxgen/src/SimpleXLSXGen.php';

	if(!is_login() || !is_admin()) {
		redirect('admin/form_login.php');
	} 
	$getBrandSQL = "
	SELECT db_brand.* , SUM(db_product.pro_qty) AS current_product, SUM(db_order_detail.amount) AS sold_product 
	, SUM(db_order_detail.amount * db_order_detail.price) AS sum_money 
	FROM db_brand 
		LEFT JOIN db_product ON db_brand.bra_id = db_product.bra_id 
		LEFT JOIN db_order_detail ON db_order_detail.pro_id = db_product.pro_id 
		LEFT JOIN db_order ON db_order_detail.or_id = db_order.or_id 
	WHERE db_order.or_status = 1 OR db_order.or_status is NULL 
	GROUP BY(db_brand.bra_id)";

	$listBrand = db_get($getBrandSQL, 0);
	$field = ['ID', 'TÊN HÃNG', 'TRẠNG THÁI', 'NGÀY TẠO', 'SẢN PHẨM TRONG KHO', 'SẢN PHẨM ĐÃ BÁN', 'TỔNG THU'];
	$output[] = $field;

	foreach ($listBrand as $key => $brand) {
		$output[] = [
			$brand['bra_id'], $brand['bra_name'], $brand['bra_active'], $brand['bra_create_at']
			, $brand['current_product'], $brand['sold_product'], $brand['sum_money']
		];
	}

	$xlsx = SimpleXLSXGen::fromArray( $output );
	$xlsx->downloadAs('list_brand'. time() .'.xlsx');
 ?>