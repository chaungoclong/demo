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
			$msg    = "BẠN CHƯA MUA SẢN PHẨM NÀY";
		} else {
			$addRateSQL = "INSERT INTO db_rate(cus_id, pro_id, r_content, r_star)
			VALUES(?, ?, ?, ?)
			";

			$runSQL = db_run($addRateSQL, [$cusID, $proID, $rateContent, $rateValue], "iisi");
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
			} else {
				$status = 0;
				$msg    = "CẬP NHẬT ĐÁNH GIÁ KHÔNG THÀNH CÔNG";
			}
		}
		echo json_encode(['status'=>$status, 'msg'=>$msg ]);

	}

	// KIỂM TRA ĐÁNH GIÁ CỦA NGƯỜI DÙNG CUSID VỀ SẢN PHẨM PROID ĐÃ TỒN TẠI HAY CHƯA
	if(isset($_POST) && $_POST['action'] == "rate_exist") {
		$cusID          = data_input(input_post('cusID'));
		$proID          = data_input(input_post('proID'));
		
		$checkRateExist = checkRateExist($cusID, $proID);
		if($checkRateExist) {
			echo 1;
		} else {
			echo 0;
		}
	}

	// LẤY ĐÁNH GIÁ
	if(isset($_POST) && $_POST['action'] == "fetch_rate") {
		$html      = "";
		$numRate   = 0;
		$totalStar = 0;
		
		$cusID     = data_input(input_post('cusID'));
		$proID     = data_input(input_post('proID'));

		$fetchRateSQL = " SELECT db_customer.cus_name, db_customer.cus_avatar, db_rate.r_content, db_rate.r_star, db_rate.r_create_at, db_rate.r_update_at 
		FROM db_rate JOIN db_customer
		ON db_rate.cus_id = db_customer.cus_id 
		WHERE pro_id = ?
		ORDER BY db_rate.r_update_at DESC";

		$result = db_get($fetchRateSQL, 0, [$proID], "i");

		if (!empty($result)) {

			//tổng số lượng đánh giá
			$numRate = count($result);

			foreach ($result as $key => $rate) {
				$starRate = '';

				//tổng số sao : lặp và cộng số sao của các đánh giá
				$totalStar += (int)$rate['r_star'];

				// tạo số sao đánh giá
				for ($i = 0; $i < $rate['r_star'] ; $i++) { 
					$starRate .= '  
						<li><i class="fas fa-star"></i></li>
					';
				}

				$Date = "";
				$timeCreate = (int) strtotime($rate['r_create_at']);
				$timeUpdate = (int) strtotime($rate['r_update_at']);

				if($timeUpdate > $timeCreate) {
					$Date = date("d/m/Y", $timeUpdate) . " <i class='rate_edit'>(đã chỉnh sửa)</i>";
				} else {
					$Date = date("d/m/Y", $timeCreate);
				}
				
				$html .= '  
					<div class="row m-0 shadow py-1">
						<div class="col-1">
							<img src="image/' . $rate['cus_avatar'] . '"width="60px" height="60px">
						</div>

						<div class="col-11">
							<h6 class="rate_name">' .$rate['cus_name']. '</h6>

							<!-- số sao đánh giá + ngày đánh giá -->
							<div class="star_rate_date d-flex mb-2">
								<ul class="star_rate nav mr-2">
									' . $starRate . '
								</ul>
								<span>' . $Date. '</span>
							</div>

							<p class="rate_content">
								' . $rate['r_content'] . '
							</p>

						</div>
					</div>
					<hr width="100%">
				';
			}
		} else {
			$html .= "<div>
				KHÔNG CÓ BÌNH LUẬN
			</div>";
		}

		//tính số sao trung bình trả về kết quả
		$avgStar = (!$numRate || !$totalStar) ? 0 : ceil($totalStar / $numRate);

		$resultAvgStar = '
		<h6>ĐÁNH GIÁ TRUNG BÌNH</h6>
			<ul class="nav avgStar">
		';
		for($i = 0; $i < $avgStar; ++$i) {
			$resultAvgStar .= ' 
			<li class="mr-2"><i class="fas fa-star fa-lg"></i></li>
			';
		}
		$resultAvgStar .= '</ul>';

		echo json_encode([
			'html'          => $html,
			'numRate'       => $numRate,
			'totalStar'     => $totalStar,
			'resultAvgStar' => $resultAvgStar
		]);
	}
 ?>