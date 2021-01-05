<?php 
require_once 'common.php';

if(!empty($_POST)) {
	if(!is_login()) {
		echo "bạn chưa đăng nhập";
	}
	else {
		$quantity = input_post('quantity');
		echo "bạn vừa thêm $quantity sản phẩm vào giỏ hàng";
	}

}
?>