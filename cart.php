<?php
require_once 'common.php';
if(isset($_POST)) {
	//id sản phẩm
	$proID = input_post("proid");
	//số lượng sản phẩm
	$quantity = input_post("quantity");
	//hành động (thêm, xóa, thay đổi số lượng)
	$action = input_post("action");
	//kết quả trả về dưới dạng html
	$html = "";
	//thông báo
	$notice = "";
	//tổng tiền các sản phẩm trong giỏ
	$totalMoney = 0;
	//tổng số lượng sản phẩm trong giỏ
	$numCartItem = 0;
	//số sản phẩm tối đa được mua
	$proLimit = 10;
	//kiểm tra đăng nhập
	if(!is_login()) {
		$notice = "<div class='alert-danger'>bạn chưa đăng nhập <a href='". base_url("login_form.php") ."'>  ĐĂNG NHẬP</a></div>";
		echo json_encode([
			'notice' => $notice
		]);
	} else {
		//THÊM SẢN PHẨM VÀO GIỎ HÀNG
		if(isset($action) && $action === "add" && !empty($proID) && !empty($quantity)) {
			//nếu giỏ hàng của sản phẩm có id = proID đã tồn tại thì cộng thêm số lượng của sản phẩm đó
			if(!empty($_SESSION['cart'][$proID])) {
				//nếu số lượng của sản phẩm có id = proID khi thêm <= 10 ? thêm : lỗi
				if($_SESSION['cart'][$proID] + $quantity <= $proLimit) {
					$_SESSION['cart'][$proID] += $quantity;
					$notice = "<div class='alert-danger'>bạn đã thêm $quantity sản phẩm vào giỏ hàng</div>";
				} else {
					$notice = "<div class='alert-danger'>tối đa 10 sản phẩm</div>";
				}
			}
			//nếu giỏ hàng của sản phẩm có id = proID chưa tồn tại thì số lượng của sản phẩm đó = số lượng thêm vào
			else {
				
				if($quantity <= 10) {
					$_SESSION['cart'][$proID] = $quantity;
					$notice = "<div class='alert-danger'>bạn đã thêm $quantity sản phẩm vào giỏ hàng</div>";
				} else {
					$notice = "<div class='alert-danger'>tối đa 10 sản phẩm</div>";
					}
			}
		}

		// THAY ĐỔI SỐ LƯỢNG SẢN PHẨM
		if(isset($action) && $action === "change" && !empty($proID) && !empty($quantity)) {
			$_SESSION['cart'][$proID] = $quantity;
		}

		// XÓA SẢN PHẨM
		if(isset($action) && $action === "delete" && !empty($proID)) {
			unset($_SESSION['cart'][$proID]);
		}

		// start table
		$html .= "
		<table class='table table-sm'>
			<tr>
				<th>ID</th>
				<th>Ảnh</th>
				<th>Số lượng</th>
				<th>Giá</th>
				<th>Tổng</th>
				<th>Xóa</th>
			</tr>
			";
			if(!empty($_SESSION['cart'])) {
				foreach ($_SESSION['cart'] as $pro_id => $qty) {
					$getOneProSQL = "SELECT * FROM db_product
					WHERE pro_id = ?
					";
					$product = s_row($getOneProSQL, [$pro_id]);
					$html .= "
					<tr>
						<td>" . $product['pro_id'] . "</td>
						<td>" .$product['pro_name']. "</td>
						<td><img src=" . $product['pro_img'] . " width='50px'></td>
						<td>
							<input type='number' min='0' name='quantity' 
							value=" . $qty . " class='quantity text-center' data-pro-id='" . $product['pro_id'] . "'>
						</td>
						<td>" .
							number_format($product['pro_price'], 0, ',', '.')
							. "
						</td>
						<td>" .number_format($product['pro_price'] * $qty, 0, ',', '.'). "</td>
						<td>
							<button class='delete btn btn-danger' id='" . $product['pro_id'] . "' data-pro-id='" .$product['pro_id']. "'>Xóa</button>
						</td>
					</tr>
					";
					$totalMoney += $product['pro_price'] * $qty;
				}
				$html .= "
				<tr>
					<td>Total</td>
					<td colspan='6'>" . number_format($totalMoney, 0, ',', '.') . "</td>
				</tr>
				";
			}
		$html .= "</table>";
		// end table
		
		//in kết quả trả về
		echo json_encode([
			'notice' => $notice,
			'html' => $html
		]);
	}
}
?>