<?php
/**
 * #Xử lý dữ liệu gửi lên để thêm, sửa, xóa các phần tử của mảng $_SESSION['cart'];
 * #từ mảng $_SESSION['cart'] có :id sản phẩm + số lượng sản phẩm trong giỏ hàng
 * #từ id sản phẩm : lấy được thông tin sản phẩm 
 * #thông tin sản phẩm + số lượng hiện có trong giỏ hàng => mã html
 * #chuyển dữ liệu về json để phản hồi lại
 * {'notice':'', 'html': '', 'totalItem': ''}
 */
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
			if($quantity <= $proLimit) {
				$_SESSION['cart'][$proID] = $quantity;
			} else {
				$notice = "tối đa 10 sản phẩm";
			}
		}
		// XÓA SẢN PHẨM
		if(isset($action) && $action === "delete" && !empty($proID)) {
			unset($_SESSION['cart'][$proID]);
		}
		//ĐẾM SỐ LƯỢNG CỦA TỪNG SẢN PHẨM
		if(isset($action) && $action === "count_one" && !empty($proID)) {
			echo $_SESSION['cart'][$proID];
		}
		//ĐẾM SỐ SẢN PHẨM
		foreach ($_SESSION['cart'] as $pro_id => $qty) {
			$totalItem += $qty;
		}
		// start table
		$html .= "
		<table class='cart_table table table-hover table-borderless'>
			<tr class='cart_table_title bg-info'>
				<th width='5%'><strong>ID</strong></th>
				<th width='45%' colspan='2'><strong>SẢN PHẨM</strong></th>
				<th width='15%'><strong>GIÁ</strong></th>
				<th width='10%'><strong>SỐ LƯỢNG</strong></th>
				<th width='15%'><strong>TỔNG</strong></th>
				<th width='10%' class='text-center'><strong>TÙY CHỌN</strong></th>
			</tr>
			";
			if(!empty($_SESSION['cart'])) {
				foreach ($_SESSION['cart'] as $pro_id => $qty) {
					$getOneProSQL = "SELECT * FROM db_product
					WHERE pro_id = ?
					";

					//thông tín sản phẩm có id = $pro_id
					$product = s_row($getOneProSQL, [$pro_id]);

					//giới hạn số lượng sản phẩm được chọn
					$limit = $proLimit > $product['pro_qty'] ? $product['pro_qty'] : $proLimit;

					//danh sách lựa chọn 
					$option = "";

					//nếu lựa chọn == số lượng sản phẩm hiện có trong giỏ hàng=> selected lựa chọn đó
					for ($i = 1; $i <= $limit ; $i++) {
						if($i == $qty) {
							$option .= "
							<option value='$i' selected>$i</option>
							";
						} else {
							$option .= "
							<option value='$i'>$i</option>
							";
						}
					}

					//in mỗi sản phẩm một hàng
					$html .= "
					<tr class='cart_table_body'>
						<td>" . $product['pro_id'] . "</td>
						<td width='8%'>
							<a href='"
								. create_link(
									base_url('product_detail.php'),
									['proid' => $product['pro_id']]
								) .
								"'>
								<img src=" . $product['pro_img'] . " width='100%' class='img-thumbnail'>
							</a>
						</td>
						<td>
							<h5>
							<a href='" .
								create_link(
									base_url('product_detail.php'),
									['proid' => $product['pro_id']]
								)
								. "'>
								" . $product['pro_name'] . "
							</a>
							</h5>
							<h6>" .$product['pro_color']. "</h6>
						</td>
						<td>" .
							number_format($product['pro_price'], 0, ',', '.')
							. " <span class='unit'>&#8363;</span>
						</td>
						<td>
							<select name='quantity' value='" . $qty . "' class='quantity text-center' 
							data-pro-id='" .$product['pro_id'] . "'>
							"
							. $option .
							"	
							</select>
						</td>
						<td>"
							. number_format($product['pro_price'] * $qty, 0, ',', '.')
							. "
							<span class='unit'>&#8363;</span>
						</td>
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
				<tr class='all_total'>
					<td colspan='5' class='text-right'><strong>TỔNG SẢN PHẨM:</strong></td>
					<td id='totalItem'>"
						. $totalItem
						. " sản phẩm
					</td>
					<td></td>
				</tr>
				<tr class='all_total'>
					<td colspan='5' class='text-right'><strong>TỔNG TIỀN:</strong></td>
					<td>"
						. number_format($totalMoney, 0, ',', '.')
						. " <span class='unit'>&#8363;</span>
					</td>
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