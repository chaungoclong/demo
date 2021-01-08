<?php 
require_once 'common.php';
if(!is_login()) {
	echo "error";
} else {
	$proID = input_post("proid");
	$action = input_post("action");

		//ĐẾM SỐ LƯỢNG CỦA TỪNG SẢN PHẨM TRONG GIỎ
	if(isset($action) && $action === "pro_cart_qty" && !empty($proID)) {
		if(!empty($_SESSION['cart'][$proID])) {
			echo $_SESSION['cart'][$proID];
		} else {
			echo 0;
		}
	}

		//ĐẾM SỐ LƯỢNG CỦA TỪNG SẢN PHẨM
	if(isset($action) && $action === "pro_qty" && !empty($proID)) {
		$getOneProSQL = "SELECT pro_qty FROM db_product
		WHERE pro_id  = ?
		";
		$product = s_cell($getOneProSQL, [$proID]);
		echo $product;
	}
}
?>