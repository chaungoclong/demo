<?php 
	require_once '../common.php';

	$status = 5;

	if($_SERVER['REQUEST_METHOD'] == "POST") {
		$orderID = data_input(input_post("orderID"));

		if($orderID === false) {
			$status =  1;
		} else {
			$cancelOrderSQL = "UPDATE db_order SET or_status = 2 WHERE or_id = ?";
			$runCancelOrder = db_run($cancelOrderSQL, [$orderID], "i");
			$status = 5;
		}

		$res = ["status"=>$status, "orID"=>$orderID];
		echo json_encode($res);
	}
 ?>