<?php 
	require_once '../../common.php';
	require_once '../../dist/excel/vendor/shuchkin/simplexlsxgen/src/SimpleXLSXGen.php';

	if(!is_login() || !is_admin()) {
		redirect('admin/form_login.php');
	} 
	$getCategorySQL = "
	SELECT db_category.* , SUM(db_product.pro_qty) AS current_product, SUM(db_order_detail.amount) AS sold_product 
	, SUM(db_order_detail.amount * db_order_detail.price) AS sum_money 
	FROM db_category 
		LEFT JOIN db_product ON db_category.cat_id = db_product.cat_id 
		LEFT JOIN db_order_detail ON db_order_detail.pro_id = db_product.pro_id 
		LEFT JOIN db_order ON db_order_detail.or_id = db_order.or_id 
	WHERE db_order.or_status = 1 OR db_order.or_status is NULL 
	GROUP BY(db_category.cat_id)";

	$listCategory = db_get($getCategorySQL, 0);
	$field = ['ID', 'TÊN DANH MỤC', 'TRẠNG THÁI', 'NGÀY TẠO', 'SẢN PHẨM TRONG KHO', 'SẢN PHẨM ĐÃ BÁN', 'TỔNG THU'];
	$output[] = $field;

	foreach ($listCategory as $key => $category) {
		$output[] = [
			$category['cat_id'], $category['cat_name'], $category['cat_active'], $category['cat_create_at']
			, $category['current_product'], $category['sold_product'], $category['sum_money']
		];
	}

	$xlsx = SimpleXLSXGen::fromArray( $output );
	$xlsx->downloadAs('list_category'. time() .'.xlsx');
 ?>