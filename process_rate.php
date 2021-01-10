<?php 
	require_once 'common.php';

	//THÊM ĐÁNH GIÁ
	if(isset($_POST) && $_POST['action'] == "add_rate") {
		$status = 1;
		$msg = "";
		$cusID       = data_input(input_post('cusID'));
		$proID       = data_input(input_post('proID'));
		$rateContent = data_input(input_post('rateContent'));
		$rateValue   = data_input(input_post('rateValue'));

		$checkBought = checkCustomerBought($cusID, $proID);
		if(!$checkBought) {
			$status = 0;
			$msg = "BẠN CHƯA MUA SẢN PHẨM NÀY";
		} else {
			$addRateSQL = "INSERT INTO db_rate(cus_id, pro_id, r_content, r_star)
			VALUES(?, ?, ?, ?)
			";

			$runSQL = db_run($addRateSQL, $cusID, $proID, $rateContent, $rateValue);
			if($runSQL) {
				$status = 1;
				$msg    = "THÊM ĐÁNH GIÁ THÀNH CÔNG";
			} else {
				$status = 0;
				$msg    = "THÊM ĐÁNH GIÁ KHÔNG THÀNH CÔNG";
			}
		}
		echo json_encode(['status'=>$status, 'msg'=>$msg ]);
	}


	//CẬP NHẬT ĐÁNH GIÁ
	if(isset($_POST) && $_POST['action'] == "update_rate") {
		$status = 1;
		$msg = "";
		$cusID       = data_input(input_post('cusID'));
		$proID       = data_input(input_post('proID'));
		$rateContent = data_input(input_post('rateContent'));
		$rateValue   = data_input(input_post('rateValue'));

		//kiểm tra người dùng đã mua sản phẩm này chưa
		$checkBought = checkCustomerBought($cusID, $proID);
		if(!$checkBought) {
			$status = 0;
			$msg = "BẠN CHƯA MUA SẢN PHẨM NÀY";
		} else {
			$updateRateSQL = "UPDATE db_rate 
			SET r_content = ?, r_star = ?
			WHERE cus_id = ? AND pro_id = ?
			";

			$runSQL = db_run($updateRateSQL, $rateContent, $rateValue, $cusID, $proID);
			if($runSQL) {
				$status = 1;
				$msg    = "CẬP NHẬT SẢN PHẨM THÀNH CÔNG";
			} else {
				$status = 0;
				$msg    = "CẬP NHẬT SẢN PHẨM KHÔNG THÀNH CÔNG";
			}
		}
		echo json_encode(['status'=>$status, 'msg'=>$msg ]);

	}

	// KIỂM TRA ĐÁNH GIÁ CỦA NGƯỜI DÙNG CUSID VỀ SẢN PHẨM PROID ĐÃ TỒN TẠI HAY CHƯA
	if(isset($_POST) && $_POST['action'] == "rate_exist") {
		$cusID       = data_input(input_post('cusID'));
		$proID       = data_input(input_post('proID'));

		$checkRateExist = checkRateExist($cusID, $proID);
		if($checkRateExist) {
			echo 1;
		} else {
			echo 0;
		}
	}

	// LẤY ĐÁNH GIÁ
	if(isset($_POST) && $_POST['action'] == "fetch_rate") {
		$html = "";

		$cusID       = data_input(input_post('cusID'));
		$proID       = data_input(input_post('proID'));

		$fetchRateSQL = " SELECT db_customer.cus_name, db_customer.cus_avatar, db_rate.r_content, db_rate.r_star, db_rate.r_create_at, db_rate.r_update_at 
		FROM db_rate JOIN db_customer
		ON db_rate.cus_id = db_customer.cus_id 
		ORDER BY db_rate.r_update_at DESC";

		$result = db_get($fetchRateSQL);

		if (!empty($result)) {
			foreach ($result as $key => $rate) {
				$starRate = '';
				for ($i = 0; $i < $rate['r_star'] ; $i++) { 
					$starRate .= '  
						<li><i class="fas fa-star"></i></li>
					';
				}

				$Date = "";
				if($rate['r_update_at'] != null) {
					$time = strtotime($rate['r_update_at']);
					$Date = date( "D:M:Y", $time);
					$Date .= "<br>(edited)";
				} else {
					$time = strtotime($rate['r_create_at']);
					$Date = date( "D:M:Y", $time);
				}
				
				$html .= '  
					<div class="row">
						<div class="col-3">
							<img src="image/'. $rate["cus_avatar"] .'" alt="" width="60px" height="60px">
							<ul class="nav flex-column">
								<li class="mt-2">'. $rate["cus_name"] . '</li>
								<li class="text-danger">' . $Date . '</li>
							</ul>
						</div>
						<div class="col-9">

							<!-- rate star -->
							<div class="rate_star mb-2">
								<ul class="nav">
									' . $starRate . '
								</ul>
							</div>

							<!-- rate content -->
							<div class="rate_content">
								<p>
									' . $rate["r_content"]. '
								</p>
							</div>

						</div>
					</div>
					<hr class="w-100">
				';
			}
		} else {
			$html .= "<div>
				khong co binh luan.
			</div>";
		}
		echo json_encode(['html'=>$html]);
	}
 ?>