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
	$totalItem = 0;
	//số sản phẩm tối đa được mua
	$proLimit = 10;
	//kiểm tra đăng nhập
	if(!is_login()) {
		$notice = "Bạn chưa đăng nhập";
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
					$notice = "bạn đã thêm $quantity sản phẩm vào giỏ hàng";
				} else {
					$notice = "tối đa 10 sản phẩm";
				}
			}
			//nếu giỏ hàng của sản phẩm có id = proID chưa tồn tại thì số lượng của sản phẩm đó = số lượng thêm vào
			else {
				
				if($quantity <= 10) {
					$_SESSION['cart'][$proID] = $quantity;
					$notice = "bạn đã thêm $quantity sản phẩm vào giỏ hàng";
				} else {
					$notice = "tối đa 10 sản phẩm";
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

		//ĐẾM SỐ SẢN PHẨM
		foreach ($_SESSION['cart'] as $pro_id => $qty) {
			$totalItem += $qty;
		}

		// start table
		$html .= "
		<table class='table table-hover table-borderless'>
			<tr>
				<th>ID</th>
				<th class='text-center' colspan='2'>SẢN PHẨM</th>
				<th class='text-center'>GIÁ</th>
				<th class='text-center'>SỐ LƯỢNG</th>
				<th class='text-center'>TỔNG</th>
				<th class='text-center'>TÙY CHỌN</th>
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

						<td>
							<a href=''>
								<img src=" . $product['pro_img'] . " width='50px' class='img-thumbnail'>
							</a>
						</td>

						<td>
							<h5>" .$product['pro_name']. "</h5>
							<h5>" .$product['pro_color']. "</h5>
						</td>

						<td class='text-center'>" .
							number_format($product['pro_price'], 0, ',', '.')
							. " &#8363;
						</td>

						<td class='text-center'>
							<input type='number' min='0' name='quantity' 
							value=" . $qty . " class='quantity text-center' data-pro-id='" . $product['pro_id'] . "'>
						</td>

						<td>" . number_format($product['pro_price'] * $qty, 0, ',', '.') . " &#8363;</td>

						<td class='text-center'>
							<button class='delete btn btn-danger' id='" . $product['pro_id'] . "' data-pro-id='" .$product['pro_id']. "'>
								<i class='far fa-trash-alt'></i>
							</button>
						</td>
					</tr>
					";
					$totalMoney += $product['pro_price'] * $qty;
				}

				$html .= "
				<tr>
					<td colspan='5' class='text-right'><strong>TOTAL:</strong></td>
					<td>" . number_format($totalMoney, 0, ',', '.') . " &#8363;</td>
					<td></td>
				</tr>
				";
			} else {
				$html .= "   
					<tr>
						<td class='text-center' colspan='7'>
							<h5>GIỎ HÀNG TRỐNG</h5>
						</td>
					</tr>
				";
			}
		$html .= "</table>";
		// end table
		
		//in kết quả trả về
		echo json_encode([
			'notice' => $notice,
			'html' => $html,
			'totalItem' => $totalItem
		]);
	}
}
?>