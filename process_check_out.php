<?php 
	require_once 'common.php';
	if($_SERVER['REQUEST_METHOD'] === 'POST'){

		$status = 5;
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
			$status = 1;
		} else if(!check_name($name) || !check_phone($phone)) {

			// thông tin sai
			$status = 2;
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

				/**
				 * đặt hàng thành công -> xóa giỏ hàng -> tạo thông báo đã đặt hàng -> return trạng thái 5
				 */
				
				 // xóa giỏ hàng
				delete_session('cart');

				// tạo thông báo
				createMessage('notify', 'check_out', 'đơn hàng mới', base_url('admin/order/order_detail.php?orid=' . $orderID));

				// tạo tín hiệu gửi thông báo email đơn hàng mới
				sendEmailOrder('email', 'email_new_order', $orderID, $cus_id);

				$status = 5;
			} else {

				// đặt hàng thất bại
				$status = 6;
			}
		}
		$res = ["status"=>$status, "orID"=>$orderID];
		echo json_encode($res);
	}
 ?>