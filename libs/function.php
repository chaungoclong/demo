<?php
	//lấy giá trị của biến $_POST có key = $key
function input_post($key) {
	return isset($_POST[$key]) ? trim($_POST[$key]) : false;
}
	//lấy giá trị của biến $_GET có key = $key
function input_get($key) {
	return isset($_GET[$key]) ? trim($_GET[$key]) : false;
}
	//hàm tạo url(chỉ dùng để điều hướng thanh menu)
function base_url($path = '') {
	return "http://localhost/do_an_1/" . $path;
}
	//hàm chuyển hướng
function redirect($path) {
	header("Location:" . base_url($path));
}
	//hàm trả về ngày tháng hiện tại
function now(){
	return date('Y-m-d H:i:s');
}

//hàm thay thế phần tử đầu tiên
function replace_first($search, $replace, $string) {
	return implode($replace, explode($search, $string, 2));
}
	//hàm tạo link
function create_link($uri, $data = []) {
	$string = '';
	foreach ($data as $key => $value) {
		if($value) {
			$string .= "&{$key}={$value}";
		}
	}
	return $uri . replace_first("&", "?", $string);
}
function leap_Year($year) {
	return $year % 4 == 0 && ($year % 100 != 0 || $year % 400 == 0);
}
function read_date($time) {
	$second = 1;
	$minute = 60;
	$hour   = 3600;
	$day    = 86400;
	$week   = 7 * $day;
	$month  = 30 * $day;
	$year   = leap_Year(date("Y")) ? 366 * $day : 365 * $day;
	$word   = "";
	$time = time() - $time;
	if($time / $year >= 1) {
		return $word = (int)($time / $year) . " năm trước";
	} else if($time / $month  >= 1) {
		return $word = (int)($time / $month) . " tháng trước";
	} else if($time / $week >= 1) {
		return $word = (int)($time / $week) . " tuần trước";
	} else if($time / $day  >= 1) {
		return $word = (int)($time / $day) . " ngày trước";
	} else if($time / $hour >= 1) {
		return $word = (int)($time / $hour) . " giờ trước";
	} else if($time / $minute  >= 1) {
		return $word = (int)($time / $minute) . " phút trước";
	} else if($time / $second  >= 1) {
		return $word = (int)($time / $second) . " giây trước";
	}
}
	/**
	* [get_display description]
	* @param  [type]  $data   [dữ liệu vào]
	* @param  [type]  $numCol [số cột trên hàng]
	* @param  integer $limit  [giới hạn số hàng]
	* @return [type]          [số hàng có thể có]
	*/
	function row_qty($numItem, $numCol, $limit = 0) {
		$numRow = 0;
		if($numItem === 0 || $numCol === 0) {
			return $numRow;
		}
		$numRow = ceil($numItem / $numCol);
		//nếu tồn tại giới hạn và số hàng lớn hơn giới hạn thì số hàng bằng giới hạn
		if($limit && $numRow > $limit) {
			$numRow = $limit;
		}
		return $numRow;
	}
	/**
	* [up_file hàm upload file]
	* @param  [type] $file      [file từ thẻ input]
	* @param  [type] $folder    [tệp cần tải lên]
	* @param  [type] $extension [đuôi file được chấp nhận]
	* @return [type]            [nếu tải lên thành công trả về tên mới của file : false]
	*/
	function up_file($file, $folder, $extension){
		//kiểm tra lỗi
		if($file['error']) { return false;}
		//kiểm tra đuôi file
		$file_type = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
		if(!in_array($file_type, $extension)) { return false;}
		$file_name = time() . rand() . "." . $file_type;
		$file_path = $folder . $file_name;
		$file_tmp = $file['tmp_name'];
		//tải file vào folder
		if(move_uploaded_file($file_tmp, $file_path)){
			return $file_name;
		}
		return false;
	}
	/**
	* [paginate description]
	* @param  [string] $currentLink [link hiện tại của trang kèm theo tham số page={page}
	* tham số page sẽ được thay bằng vị trí trang hiện tại khi một trang được chọn.
	* khi 1 nút phân trang được nhấn -> đi đến link của nút phân trang -> trang refresh ->
	* currentLink được tạo lại.
	* ]
	* @param  [int] $totalItem   [tất cả phần tử]
	* @param  [int] $currentPage [trang hiện tại]
	* @param  [int] $itemPerPage [số phần tử trên trang]
	* @return [array]            [trả về một mảng chứa html , số phần tử bỏ qua , số phần tử trên 1 trang]
	*/
	function paginate($currentLink, $totalItem, $currentPage, $itemPerPage) {
		//echo $currentLink;
		$html = '';
		//tổng số trang
		$totalPage = ceil($totalItem / $itemPerPage);

		$currentPage = ($currentPage > $totalPage) ? $totalPage : $currentPage;
		//số item bỏ qua
		$offsetItem = ((int)$currentPage - 1) * (int)$itemPerPage;
		//số trang > 0 hiện nút phân trang
		if($totalPage) {
			$html .=
			'
			<nav aria-label="..." style="padding: 0px 85px;">
			<ul class="pagination justify-content-center">
			';
		}
				//thêm nút trang đầu tiên
		if($currentPage > 1 &&  $totalPage > 1) {
					//thay thế {page} bằng só thứ tự trang
			$link = str_replace("{page}", 1, $currentLink);
			$html .= '
			<li class="page-item d-flex align-items-center">
			<a class="page-link h-100" href="' . $link . '">' 
			. "<i class='fas fa-angle-double-left'></i>" .
			'</a>
			</li>
			';
		}
				//thêm nút trang trước
		if($currentPage > 1 &&  $totalPage > 1) {
					//thay thế {page} bằng só thứ tự trang
			$link = str_replace("{page}", $currentPage - 1, $currentLink);
			$html .= '
			<li class="page-item d-flex align-items-center">
			<a class="page-link h-100" href="' . $link . '">' 
			. "<i class='fas fa-angle-left'></i>" .
			'</a>
			</li>
			';
		}
				//thêm các nút phân trang ở giữa
		for($i = 1; $i <= $totalPage; ++$i) {
					//nếu khác trang hiện tại thì thêm thẻ a : thẻ span
			if($i != $currentPage) {
						//thay thế {page} bằng só thứ tự trang
				$link = str_replace("{page}", $i , $currentLink);
				$html .= '
				<li class="page-item d-flex align-items-center">
				<a class="page-link h-100" href="' . $link . '">' . $i . '</a>
				</li>
				';
			} else {
				$html .= '
				<li class="page-item active">
				<span class="page-link">'
				. $i .
				'<span class="sr-only">(current)</span>
				</span>
				</li>
				';
			}
		}
				//thêm nút trang tiếp theo
		if($currentPage < $totalPage &&  $totalPage > 1) {
					//thay thế {page} bằng só thứ tự trang
			$link = str_replace("{page}", (int)$currentPage + 1, $currentLink);
			$html .= '
			<li class="page-item">
			<a class="page-link h-100" href="' . $link . '">' 
			. "<i class='fas fa-angle-right'></i>" .
			'</a>
			</li>
			';
		}
				//thêm nút trang cuối cùng
		if($currentPage < $totalPage &&  $totalPage > 1) {
					//thay thế {page} bằng só thứ tự trang
			$link = str_replace("{page}", $totalPage, $currentLink);
			$html .= '
			<li class="page-item d-flex align-items-center">
			<a class="page-link h-100" href="' . $link . '">' 
			. "<i class='fas fa-angle-double-right'></i>" .
			'</a>
			</li>
			';
		}

				//số trang > 0 hiện nút phân trang
		if($totalPage) {
			$html .= '
			</ul>
			</nav>
			';
		}
		return [
			"html"   => $html,
			"offset" => $offsetItem,
			"limit"  => $itemPerPage
		];
	}

	//hàm check dữ liệu
	function data_input($value){
		db_connect();
		global $connect;
		$value = htmlspecialchars(stripslashes(trim($value)));
		$value = $connect->real_escape_string($value);
		return $value;
	}

	// hàm kiểm tra email
	function check_email($string) {
		$pattern = '/^[a-z][a-z0-9_\.]{5,32}@[a-z0-9]{2,}(\.[a-z0-9]{2,4}){1,2}$/';
		return preg_match($pattern, $string);
	}

	// hàm kiểm tra ngày tháng
	function check_date($string) {
		$pattern = '/^[12]\d{3}-(0[1-9]|1[12])-(0[1-9]|[12]\d|3[01])$/';
		return preg_match($pattern, $string);
	}

	// hàm định dạng ngày (d-m-y => y-m-d)
	function formatDate($string) {
		return implode('-', array_reverse(explode('-', $string)));
	}

	// hàm kiểm tra tên
	function check_name($string) {
		$pattern = '/^([a-zA-Z]{1,10}\s?)+$/';
		return preg_match($pattern, $string);
	}

	// hàm kiểm tra mật khẩu
	function check_password($string) {
		$pattern = '/^[a-zA-Z0-9+-\@\*\#]{8,32}$/';
		return preg_match($pattern, $string);
	}

	// hàm kiểm tra số điện thoại
	function check_phone($string) {
		$pattern = '/^(84|0[3|5|7|8|9])+([0-9]{8})$/';
		return preg_match($pattern, $string);
	}

	// hàm kiểm tra email đã tồn tại
	function emailExist($table, $fieldName, $email) {
		$sql = "select {$fieldName} from {$table} where {$fieldName} = ?";
		return db_get($sql, 2, [$email], "s");
	}

	// hàm kiểm tra số điện thoại đã tồn tại
	function phoneExist($table, $fieldName, $phone) {
		$sql = "select {$fieldName} from {$table} where {$fieldName} = ?";
		return db_get($sql, 2, [$phone], "s");
	}

	// hàm kiểm tra người dùng đã tồn tại
	function userExist($table, $fieldName, $user) {
		$sql = "select {$fieldName} from {$table} where {$fieldName} = ?";
		return db_get($sql, 2, [$user], "s");
	}

	// hàm in dữ liệu để debug dễ nhìn
	function vd($value) {
		echo "<pre>" . print_r($value, true) . "</pre>";
	}

	// hàm kiểm tra khách hàng đã mua một sản phẩm
	function checkCustomerBought($cusID, $proID) {
		$sql = "SELECT db_order.cus_id FROM db_order JOIN db_order_detail
		ON db_order.or_id = db_order_detail.or_id
		WHERE db_order.cus_id = ? AND db_order.or_status = 4 AND db_order_detail.pro_id = ?
		";

		$result = s_row($sql, [$cusID, $proID], "ii");

		return $result;
	}

	// hàm kiểm tra đánh giá đã tồn tại
	function checkRateExist($cusID, $proID) {
		$sql = "SELECT cus_id, pro_id FROM db_rate
		WHERE cus_id = ? AND pro_id = ?
		";

		$result = db_get($sql, 2, [$cusID, $proID], "ii");

		return $result;
	}

	// hàm lấy một sản phẩm theo id
	function getProductById($id) {
		$getProSQL = "SELECT * FROM db_product WHERE pro_id = ?";
		$result = s_row($getProSQL, [$id], "i");
		return $result;
	}


	// hàm kiểm tra được chuyển sang trang checkout hay không
	// nếu số lượng hiện tại của sản phẩm = 0 || hoặc nhỏ hơn số lượng của sản phẩm
	// trong giỏ hàng => không được chuyển và ngược lại
	function checkOutOK() {
		if(!empty($_SESSION['cart'])) {
			foreach ($_SESSION['cart'] as $pro_id => $qty) {
				$product = getProductById($pro_id);

				$proQty = $product['pro_qty'];

				if($proQty == 0 || $proQty < $qty) {
					return false;
				}
			}
			return true;
		}
		return false;
	}

	// hàm chuyển chuỗi về thời gian theo định dạng
	function strToTimeFormat($string, $format) {
		$time = strtotime($string);
		return date($format, $time);
	}

	// hàm lấy đơn hàng theo mã người dùng
	function getOrderByUser($userID, $limit = 0, $offset = 0) {
		$getOrderSQL = "SELECT * FROM db_order JOIN db_customer
		ON db_order.cus_id = db_customer.cus_id
		WHERE db_order.cus_id = ?
		ORDER BY db_order.or_create_at DESC
		";

		if($limit) {
			$getOrderSQL .= " LIMIT ? OFFSET ?";
			$result = db_get($getOrderSQL, 1, [$userID, $limit, $offset], "iii");
		} else {
			$result = db_get($getOrderSQL, 1, [$userID], "i");
		}

		return $result;
	}

	function getOrderByID($orderID) {
		$getOrderSQL = "SELECT db_order.*, db_customer.cus_name FROM db_order JOIN db_customer
		ON db_order.cus_id = db_customer.cus_id
		WHERE db_order.or_id = ?
		";
		$result = s_row($getOrderSQL, [$orderID], "i");
		return $result;
	}

	// hàm lấy đơn hàng chi tiết theo mã người dùng
	function getOrderDetailByID($orderID) {
		$getOrderSQL = "SELECT * FROM db_order JOIN db_order_detail
		ON db_order.or_id = db_order_detail.or_id
		JOIN db_product
		ON db_order_detail.pro_id = db_product.pro_id
		WHERE db_order.or_id = ?
		";

		$result = db_get($getOrderSQL, 1, [$orderID], "i");
		return $result;
	}

	// hàm lấy danh sách người dùng
	function getListUser($typeUser, $limit = 0, $offset = 0) {
		if($typeUser == 1) {
			$getListUserSQL = "SELECT * FROM db_admin WHERE ad_role > 1";
		} else {
			$getListUserSQL = "SELECT * FROM db_customer";
		}

		if($limit) {
			$getListUserSQL .= " LIMIT ? OFFSET ?";
			$result = db_get($getListUserSQL, 1, [$limit, $offset], "ii");
		} else {
			$result = db_get($getListUserSQL, 1);
		}

		return $result;
	}

	//hàm lấy đường dẫn hiện tại của trang
	function getCurrentURL() {
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off" 
		|| $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

		return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	}
	