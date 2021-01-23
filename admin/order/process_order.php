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
		"orderID"   =>$orderID,
		"prevLink" =>$prevLink
	];

	echo json_encode($res);
}

// XÁC NHẬN ĐƠN HÀNG
if (!empty($_POST['action']) && $_POST['action'] == "cancel") {
	$status = 5;

	// mã đơn hàng
	$orderID = data_input(input_post("orderID"));

		// validate
	if($orderID === false) {
		$status = 1;
	} else {
		$confirmSQL    = "UPDATE db_order SET or_status = 3 WHERE or_id = ?";
		$runConfirm = db_run($confirmSQL, [$orderID], 'i');
		if($runConfirm) {
			$status = 5;
		} else {
			$status = 6;
		}
	}

	$res = [
		"status"   =>$status,
		"orderID"   =>$orderID,
		"prevLink" =>$prevLink
	];

	echo json_encode($res);
}
