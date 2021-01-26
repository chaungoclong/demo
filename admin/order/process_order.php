<?php 
require_once '../../common.php';


// XÁC NHẬN ĐƠN HÀNG
if (!empty($_POST['action']) && $_POST['action'] == "confirm") {
	$status = 5;

	// mã đơn hàng
	$orderID = data_input(input_post("orderID"));

		// validate
	if($orderID === false) {
		$status = 1;
	} else {
		$confirmSQL    = "UPDATE db_order SET or_status = 1 WHERE or_id = ?";
		$runConfirm = db_run($confirmSQL, [$orderID], 'i');
		if($runConfirm) {
			$status = 5;
		} else {
			$status = 6;
		}
	}

	$res = [
		"status"   =>$status,
		"orderID"   =>$orderID
	];

	echo json_encode($res);
}

// HỦY ĐƠN HÀNG
if (!empty($_POST['action']) && $_POST['action'] == "cancel") {
	$status = 5;

	// mã đơn hàng
	$orderID = data_input(input_post("orderID"));

		// validate
	if($orderID === false) {
		$status = 1;
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

		$cancelSQL = "UPDATE db_order SET or_status = 3 WHERE or_id = ?";
		$runCancel = db_run($cancelSQL, [$orderID], 'i');
		if($runCancel) {
			$status = 5;
		} else {
			$status = 6;
		}
	}

	$res = [
		"status"   =>$status,
		"orderID"   =>$orderID
	];

	echo json_encode($res);
}
