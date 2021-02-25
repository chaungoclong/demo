<?php 
require_once '../../common.php';


// XÁC NHẬN ĐƠN HÀNG
if (!empty($_POST['action']) && $_POST['action'] == "confirm") {
	$ok         = true;
	$orderID    = $_POST['orID'];
	$currentStatus = s_cell("SELECT or_status FROM db_order WHERE or_id = ?", [$orderID], "i");

	if($currentStatus == 1 || $currentStatus == 2) {
		$ok = false;
	} else {
		$confirmSQL = "UPDATE db_order SET or_status = 1 WHERE or_id = ?";
		$runConfirm = db_run($confirmSQL, [$orderID], 'i');
		$ok         = $runConfirm ? true : false;
	}
	
	$output     = ['ok'=>$ok];
	echo json_encode($output);
}

// HỦY ĐƠN HÀNG
if (!empty($_POST['action']) && $_POST['action'] == "cancel") {
	$ok = true;
	$orderID = $_POST['orID'];
	$currentStatus = s_cell("SELECT or_status FROM db_order WHERE or_id = ?", [$orderID], "i");

	if($currentStatus == 1 || $currentStatus == 2) {
		$ok = false;
	} else {
		// lấy danh sách các sản phẩm của đơn hàng cần hủy(đơn hàng chi tiết)
		$getListProSQL = "  
		SELECT pro_id, amount FROM db_order_detail
		WHERE or_id = ?
		";
		$listProduct = db_get($getListProSQL, 1, [$orderID], "i");

		// tăng số lượng của các sản phẩm trong đơn hàng cần hủy 1 lượng = lượng đẫ đặt
		foreach ($listProduct as $key => $product) {
			$proID = $product['pro_id'];
			$amount = $product['amount'];
			$resetQtySQL = "UPDATE db_product SET pro_qty = pro_qty + ? WHERE pro_id = ?";
			db_run($resetQtySQL, [$amount, $proID], "ii");
		}

		// hủy đơn: đặt trạng thái về đã hủy
		$cancelSQL = "UPDATE db_order SET or_status = 2 WHERE or_id = ?";
		$runCancel = db_run($cancelSQL, [$orderID], 'i');
		$ok = $runCancel ? true : false;
	}

	$output = ['ok'=>$ok];
	echo json_encode($output);
}
