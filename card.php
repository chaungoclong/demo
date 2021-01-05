<?php 
	require_once 'common.php';

	if(!empty($_POST)) {
		$quantity = input_post('quantity');
		echo "bạn vừa thêm $quantity sản phẩm vào giỏ hàng";
	}
 ?>