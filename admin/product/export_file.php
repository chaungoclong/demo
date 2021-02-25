<?php 
	require_once '../../common.php';
	require_once '../../dist/excel/vendor/shuchkin/simplexlsxgen/src/SimpleXLSXGen.php';
	
	if(!is_login() || !is_admin()) {
		redirect('admin/form_login.php');
	} 

	$getProductSQL = "SELECT * FROM db_product JOIN db_category ON db_product.cat_id = db_category.cat_id JOIN db_brand ON db_product.bra_id = db_brand.bra_id";

	$listProduct = db_get($getProductSQL, 0);
	// vd($listProduct);
	$field = ['ID', 'NAME', 'CATEGORY', 'BRAND', 'PRICE', 'QUANTITY', 'QUANTITY SOLD', 'ACTIVE',];
	$output[] = $field;

	$totalProduct = 0;
	foreach ($listProduct as $key => $product) {
		$getTotalSold = "SELECT SUM(db_order_detail.amount) 
		FROM db_product 
		JOIN db_order_detail ON db_product.pro_id = db_order_detail.pro_id
		JOIN db_order ON db_order_detail.or_id = db_order.or_id
		WHERE db_order.or_status = 1 AND db_product.pro_id = ?
		";
		$quantitySold = s_cell($getTotalSold, [$product['pro_id']], "i");
		$quantitySold = $quantitySold ? $quantitySold : 0;

		$output[] = [$product['pro_id'], $product['pro_name'], $product['cat_name'], $product['bra_name'], $product['pro_price'], $product['pro_qty'], $quantitySold,  $product['pro_active']];
		$totalProduct += (int)$product['pro_qty'];
	}

	$output[] = ['', '', '', '', 'TOTAL PRODUCT:', $totalProduct, '', ''];
	$xlsx = SimpleXLSXGen::fromArray( $output );
	$xlsx->downloadAs('list_product' .time(). '.xlsx');
 ?>