<?php 
	require_once 'common.php';

	//THÊM ĐÁNH GIÁ
	if(isset($_POST['action']) && $_POST['action'] == "add_rate") {
		$status = 1;
		$msg = "";
		$cusID       = data_input(input_post('cusID'));
		$proID       = data_input(input_post('proID'));
		$rateContent = data_input(input_post('rateContent'));
		$rateValue   = data_input(input_post('rateValue'));

		$checkBought = checkCustomerBought($cusID, $proID);
		if(!$checkBought) {
			$status = 0;
			$msg    = "BẠN CHƯA MUA SẢN PHẨM NÀY";
		} else {
			$addRateSQL = "INSERT INTO db_rate(cus_id, pro_id, r_content, r_star)
			VALUES(?, ?, ?, ?)
			";

			$runSQL = db_run($addRateSQL, [$cusID, $proID, $rateContent, $rateValue], "iisi");
			if($runSQL) {
				$status = 1;
				$msg    = "THÊM ĐÁNH GIÁ THÀNH CÔNG";

				// tạo thông báo
				$channel = "notify";
				$event = 'add_rate';
				$message = "Có đánh giá mới";
				$url = base_url('admin/rate/');
				createMessage($channel, $event, $message, $url);

			} else {
				$status = 0;
				$msg    = "THÊM ĐÁNH GIÁ KHÔNG THÀNH CÔNG";
			}
		}
		echo json_encode(['status'=>$status, 'msg'=>$msg ]);
	}


	//CẬP NHẬT ĐÁNH GIÁ
	if(isset($_POST['action']) && $_POST['action'] == "update_rate") {
		$status      = 1;
		$msg         = "";
		$cusID       = data_input(input_post('cusID'));
		$proID       = data_input(input_post('proID'));
		$rateContent = data_input(input_post('rateContent'));
		$rateValue   = data_input(input_post('rateValue'));

		//kiểm tra người dùng đã mua sản phẩm này chưa
		$checkBought = checkCustomerBought($cusID, $proID);
		if(!$checkBought) {
			$status = 0;
			$msg    = "BẠN CHƯA MUA SẢN PHẨM NÀY";
		} else {
			$updateRateSQL = "UPDATE db_rate 
			SET r_content = ?, r_star = ?
			WHERE cus_id = ? AND pro_id = ?
			";

			$runSQL = db_run($updateRateSQL, [$rateContent, $rateValue, $cusID, $proID], "siii");
			if($runSQL) {
				$status = 1;
				$msg    = "CẬP NHẬT ĐÁNH GIÁ THÀNH CÔNG";

				// tạo thông báo
				$channel = "notify";
				$event = 'update_rate';
				$message = "Một khách hàng đã cập nhật đánh giá";
				$url = base_url('admin/rate/');
				createMessage($channel, $event, $message, $url);
				
			} else {
				$status = 0;
				$msg    = "CẬP NHẬT ĐÁNH GIÁ KHÔNG THÀNH CÔNG";
			}
		}
		echo json_encode(['status'=>$status, 'msg'=>$msg ]);

	}

	// KIỂM TRA ĐÁNH GIÁ CỦA NGƯỜI DÙNG CUSID VỀ SẢN PHẨM PROID ĐÃ TỒN TẠI HAY CHƯA
	if(isset($_POST['action']) && $_POST['action'] == "rate_exist") {
		$cusID          = data_input(input_post('cusID'));
		$proID          = data_input(input_post('proID'));
		
		$checkRateExist = checkRateExist($cusID, $proID);
		if($checkRateExist) {
			echo 1;
		} else {
			echo 0;
		}
	}
 ?>