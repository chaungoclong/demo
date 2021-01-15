<?php 
	require_once 'common.php';
	if($_SERVER['REQUEST_METHOD'] === 'POST'){

		//lấy thông tin
		$cus_id  = $_SESSION['user_token']['id'];
		$name    = data_input(input_post('name'));
		$phone   = data_input(input_post('phone'));
		$address = data_input(input_post('address'));
		$notice  = data_input(input_post('notice'));
		$notice  = $notice === false ? "" : $notice;

		//validate
		if($name === false || $phone === false || $address === false) {

			// thiếu thông tin
			echo 1;
		} else if(!check_name($name) || !check_phone($phone)) {

			// thông tin sai
			echo 2;
		} else {
			/**
			 * nếu input đúng
			 * thêm hóa đơn vào bảng hóa đơn->thêm từng sản phẩm trong giỏ hàng
			 * vào hóa đơn chi tiết
			 */
			
			$addOrderSQL = "INSERT INTO db_order(cus_id, receiver_name, receiver_phone, receiver_add, or_notice)
			VALUES(?, ?, ?, ?, ?)
			";

			$runAddOrder = db_run(
				$addOrderSQL,
				[$cus_id, $name, $phone, $address, $notice],
				"issss"
			);

			// nếu thêm hóa đơn thành công -> thêm các sản phẩm trong giỏ hàng vào hóa đơn chi tiết
			if($runAddOrder) {

				// id của hóa đơn vừa thêm
				$orderID = $connect->insert_id;

				// lặp giỏ hàng thêm các sản phẩm vào hóa đơn chi tiết
				foreach ($_SESSION['cart'] as $pro_id => $qty) {

					$product = getProductById($pro_id);

					// thêm sản phẩm vào hóa đơn chi tiết
					$addOrderDetailSQL = "INSERT INTO db_order_detail(or_id, pro_id, amount, price) 
					VALUES(?, ?, ?, ?) 
					";
					db_run($addOrderDetailSQL, [$orderID, $pro_id, $qty, $product['pro_price']], "iiii");

					// cập nhật só lượng sản phẩm trong bảng sản phẩm
					$updateProQtySQL = "UPDATE db_product SET pro_qty = pro_qty - ? WHERE pro_id = ?";
					db_run($updateProQtySQL, [$qty, $pro_id], "ii");

				}

				// đặt hàng thành công
				delete_session('cart');
				echo 5;
			} else {

				// đặt hàng thất bại
				echo 6;
			}
		}
	}
 ?>