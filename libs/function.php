<?php
	//lấy giá trị của biến $_POST có key = $key
function input_post($key) {
	return isset($_POST[$key]) ? $_POST[$key] : false;
}
	//lấy giá trị của biến $_GET có key = $key
function input_get($key) {
	return isset($_GET[$key]) ? $_GET[$key] : false;
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

		$currentPage = ($currentPage > $totalPage && $totalPage != 0) ? $totalPage : $currentPage;
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

	function email($email) {
		$email = filter_var($email, FILTER_SANITIZE_EMAIL);
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	// hàm kiểm tra số nguyên
	function int($value) {
		return filter_var(data_input($value), FILTER_VALIDATE_INT);
	}

	// hàm kiểm tra số thực
	function float($value) {
		return filter_var(data_input($value), FILTER_VALIDATE_FLOAT);
	}

	// hàm kiểm tra boolean
	function bool($value) {
		return filter_var(data_input($value), FILTER_VALIDATE_BOOLEAN);
	}

	// hàm kiểm tr URL
	function url($url) {
		$url = filter_var($url, FILTER_SANITIZE_URL);
		return filter_var($url, FILTER_VALIDATE_URL);
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
		$pattern = '/^([a-zA-ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂẾưăạảấầẩẫậắằẳẵặẹẻẽềềểếỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ]{1,20}\s?)+$/';
		return preg_match($pattern, $string);
	}

	function check_word($string) {
		$pattern = '/^([a-zA-Z0-9ÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂẾưăạảấầẩẫậắằẳẵặẹẻẽềềểếỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ\-\_\.\,\/\(\)\[\]]{1,20}\s?)+$/';
		return preg_match($pattern, $string);
	}

	// hàm kiểm tra mật khẩu
	function check_password($string) {
		$pattern = '/^[a-zA-Z0-9+-\@\*\#]{8,32}$/';
		return preg_match($pattern, $string);
	}

	function password($pwd) {
		$pattern = '/^[a-zA-Z0-9+-\@\*\#]{8,32}$/';
		if(preg_match($pattern, $pwd)) {
			return $pwd;
		}
		return false;
	}

	// hàm kiểm tra số điện thoại
	function check_phone($string) {
		$pattern = '/^(84|0[3|5|7|8|9])+([0-9]{8})$/';
		return preg_match($pattern, $string);
	}

	function phone($phone) {
		$pattern = '/^(84|0[3|5|7|8|9])+([0-9]{8})$/';
		if(preg_match($pattern, $phone)) {
			return $phone;
		}
		return false;
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
		WHERE db_order.cus_id = ? AND db_order.or_status = 1 AND db_order_detail.pro_id = ?
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
		$getOrderSQL = "SELECT db_order.*, db_customer.cus_name, db_customer.cus_phone, db_customer.cus_address FROM db_order JOIN db_customer
		ON db_order.cus_id = db_customer.cus_id
		WHERE db_order.or_id = ?
		";
		$result = s_row($getOrderSQL, [$orderID], "i");
		return $result;
	}

	// hàm lấy đơn hàng chi tiết theo mã người dùng
	function getOrderDetailByID($orderID) {
		$getOrderSQL = "
		SELECT 
			db_product.pro_id, 
			db_product.pro_name, 
			db_product.pro_img, 
			db_order_detail.price, 
			db_order_detail.amount
		FROM db_order 
		JOIN db_order_detail
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

	// hàm lấy tất cả bản ghi của 1 bảng
	function db_fetch_table($table, $mode = 0, $limit = 0, $offset = 0) {
		$fetchSQL = "SELECT * FROM {$table}";
		
		if($limit) {
			$fetchSQL .= " LIMIT ? OFFSET ?";
			return db_get($fetchSQL, $mode, [$limit, $offset], "ii");
		}

		return db_get($fetchSQL, $mode);
	}

	// hàm lấy tất cả hãng
	function getListBrand($limit = 0, $offset = 0) {
		$getSQL = "SELECT * FROM db_brand ORDER BY bra_id DESC";

		if($limit) {
			$getSQL .= " LIMIT ? OFFSET ?";
			return db_get($getSQL, 1, [$limit, $offset], "ii");
		}

		return db_get($getSQL, 1);
	}

	// hàm lấy tất cả danh mục
	function getListCategory($limit = 0, $offset = 0) {
		$getSQL = "SELECT * FROM db_category ORDER BY cat_id DESC";

		if($limit) {
			$getSQL .= " LIMIT ? OFFSET ?";
			return db_get($getSQL, 1, [$limit, $offset], "ii");
		}

		return db_get($getSQL, 1);
	}

	// hàm lấy danh sách sản phẩm
	function getListProduct($limit = 0, $offset = 0) {
		$getSQL = "
		SELECT db_product.*, db_brand.bra_name, db_category.cat_name FROM db_product 
		JOIN db_category ON db_product.cat_id = db_category.cat_id
		JOIN db_brand 	 ON db_product.bra_id = db_brand.bra_id
		ORDER BY pro_id ASC";

		if($limit) {
			$getSQL .= " LIMIT ? OFFSET ?";
			return db_get($getSQL, 1, [$limit, $offset], "ii");
		}

		return db_get($getSQL, 1);
	}

	// hàm lấy danh sách slide
	function getListSlide($limit = 0, $offset = 0) {
		$getSQL = "
		SELECT db_slider.*, db_category.cat_name FROM `db_slider` 
		JOIN db_category ON db_slider.cat_id = db_category.cat_id
		ORDER BY sld_pos ASC";

		if($limit) {
			$getSQL .= " LIMIT ? OFFSET ?";
			return db_get($getSQL, 1, [$limit, $offset], "ii");
		}

		return db_get($getSQL, 1);
	}

	// hàm lấy danh sách tin tức
	function getListNews($limit = 0, $offset = 0) {
		$getSQL = "
		SELECT * FROM db_news ";

		if($limit) {
			$getSQL .= " LIMIT ? OFFSET ?";
			return db_get($getSQL, 1, [$limit, $offset], "ii");
		}

		return db_get($getSQL, 1);
	}

	// hàm lấy 1 tin tức theo ID
	function getNewsByID($newsID) {

		$getSQL = "SELECT * FROM db_news WHERE news_id = ?";

		return s_row($getSQL, [$newsID], "i");
	}

	// hàm lấy 1 slide theo ID
	function getSlideByID($sldID) {

		$getSQL = "SELECT * FROM db_slider WHERE sld_id = ?";

		return s_row($getSQL, [$sldID], "i");
	}

	// hàm kiểm tra sản phẩm có hóa đơn không
	function hasOrder($proID) {
		$checkSQL = "SELECT pro_id FROM db_order_detail WHERE pro_id = ? LIMIT 1";
		return s_cell($checkSQL, [$proID], "i");
	}

	function hasProduct($field, $value) {
		$checkSQL = "SELECT {$field} FROM db_product WHERE {$field} = ? LIMIT 1";
		return s_cell($checkSQL, [$value], "i");
	}

	// hàm upload nhiều file
	function multiUploadFile($file, $folder, $extension) {

		// biến lưu kết quả
		$result = [];

		// biến lưu lỗi
		$error = [];

		// biến lưu output
		$output = [];

		// tổng số file
		$totalFile = count($file['name']);

		//kiểm tra file rỗng
		if($totalFile == 0) {
			$error[] = "file rỗng";	
		} else {
			for ($i = 0; $i < $totalFile ; $i++) { 

				// tên file
				$fileName = $file['name'][$i];

				// tên file lưu trữ tạm thời
				$fileTmp = $file['tmp_name'][$i];

				// loại file
				$fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

				// kiểm tra có đúng loại file 
				if(!in_array($fileType, $extension)) {
					$error[] = $fileName . " :đuôi file không hợp lệ";
				} else {

					// tên mới
					$newFileName = time() . rand() . $i . "." . $fileType;

					// đường dẫn di chuyển ảnh vào
					$filePath = $folder . $newFileName;

					if(move_uploaded_file($fileTmp, $filePath)) {
						$result[] = $newFileName;
					} else {
						$error[] = $fileName . " :tải lên không thành công";
					}
				}

			}
		}

		$output = [
			"result" => $result,
			"error" => $error
		];

		return $output;
		
	}
	

	// hàm kiểm tra số 
	function check_number($string) {
		if(is_numeric($string) && (int)$string > 0) {
			return true;
		}
		return false;
	}

	// hàm kiểm tra sản phẩm đã tồn tại 
	function productExist($productName) {
		$checkSQL = "SELECT pro_name FROM db_product WHERE pro_name = ? LIMIT 1";
		return s_cell($checkSQL, [$productName], "s");
	}

	// hàm kiểm tra danh mục đã tồn tại 
	function categoryExist($categoryName) {
		$checkSQL = "SELECT cat_name FROM db_category WHERE cat_name = ? LIMIT 1";
		return s_cell($checkSQL, [$categoryName], "s");
	}

	// hàm kiểm tra hãng đã tồn tại 
	function brandExist($brandName) {
		$checkSQL = "SELECT bra_name FROM db_brand WHERE bra_name = ? LIMIT 1";
		return s_cell($checkSQL, [$brandName], "s");
	}

	// hàm lấy tất cả ảnh của một sản phẩm
	function getImageProduct($proID) {
		$getSQL = "SELECT * FROM db_image WHERE pro_id = ?";
		return db_get($getSQL, 0, [$proID], "i");
	}

	// hàm lấy về danh mục theo id
	function getCategoryByID($categoryID) {
		$getCatSQL  = "SELECT * FROM db_category WHERE cat_id = ?";
		return s_row($getCatSQL, [$categoryID], "i");
	}

	// hàm lấy về hãng theo id
	function getBrandByID($brandID) {
		$getBraSQL  = "SELECT * FROM db_brand WHERE bra_id = ?";
		return s_row($getBraSQL, [$brandID], "i");
	}

	function getListOrder($limit = 0, $offset = 0) {
			$getSQL = "
			SELECT db_order.*, db_customer.cus_name, db_customer.cus_address, db_customer.cus_phone FROM db_order
			JOIN db_customer
			ON db_order.cus_id = db_customer.cus_id
			";

			if($limit) {
				$getSQL .= " LIMIT ? OFFSET ?";
				return db_get($getSQL, 1, [$limit, $offset], "ii");
			}

			return db_get($getSQL, 1);
	}

	// hàm lấy vị trí cuối cùng của slide
	function lastPostion() {
		$getSQL = "SELECT MAX(sld_pos) as last_pos FROM db_slider";
		return s_cell($getSQL);
	}

	// hàm đếm số bản ghi cho bảng
	function countRow($table) {
		$getSQL = "SELECT COUNT(*) AS totalRow FROM {$table}";
		return s_cell($getSQL);
	}

	function isMyOrder($orderID, $cusID) {
		if(!is_login() || is_admin()) {
			redirect('login_from.php');
		}
		$checkOrderSQL = "SELECT COUNT(*) FROM db_order WHERE or_id = ? AND cus_id = ?";
		return s_cell($checkOrderSQL, [$orderID, $cusID], "ii");
	}

	function getStar($proID) {
		$star = 0;
		$getStarSQL = "
		SELECT SUM(r_star) as total_star, COUNT(*) as time_rate FROM db_rate
		WHERE pro_id = ? 
		AND cus_id IN(SELECT cus_id FROM db_customer)";
		$result = s_row($getStarSQL, [$proID], "i");
		$totalStar = (int)$result['total_star'];
		$timeRate = (int)$result['time_rate'];
		if($timeRate) {
			$star = round($totalStar / $timeRate, 1);
		}
		return ['totalStar'=>$totalStar, 'timeRate'=>$timeRate, 'star'=>$star];
	}

	function showStar($star) {
		for ($i = 1; $i <= 5 ; $i++) { 
			if(round($star - 0.25) >= $i) {
				echo "<i class='fas fa-star'></i>";
			} elseif(round($star + 0.25) >= $i) {
				echo "<i class='fas fa-star-half-alt'></i>";
			} else {
				echo "<i class='far fa-star'></i>";
			}
		}
	}

	// hàm chia trang ajax
	function paginateAjax($totalPage, $currentPage) {
		$pagination = "";
		if($totalPage) {
		$pagination .= '
		<nav aria-label="...">
			<ul class="pagination justify-content-center">
				';
				// link trang trước
				if($totalPage > 1 && $currentPage > 1) {
				$pagination .= '
				<li class="page-item page-link" data-page-number="' .($currentPage - 1). '">Trước</li>
				';
				}
				// link các trang
				for ($i = 1; $i <= $totalPage ; $i++) {
				if($i != $currentPage) {
				$pagination .= '
				<li class="page-item page-link" data-page-number="' .$i. '">' .$i. '</li>
				';
				} else {
				$pagination .= '
				<li class="page-item active" data-page-number="' .$i. '">
					<span class="page-link">
						' .$i. '
						<span class="sr-only">(current)</span>
					</span>
				</li>
				';
				}
				}
				// link trang sau
				if($totalPage > 1 && $currentPage < $totalPage) {
				$pagination .= '
				<li class="page-item page-link" data-page-number="' .($currentPage + 1). '">Sau</li>
				';
				}
				$pagination .= '
			</ul>
		</nav>
		';
		}

		return $pagination;
	}

	// hàm lấy tổng doanh thu của một đơn hàng
	function getTotalMoneyAnOrder($orderID) {
		$order = getOrderDetailByID($orderID);
		$totalMoney = 0;

		foreach ($order as $key => $each) {
			$totalMoney += ((float)$each['price'] * (int)$each['amount']);
		}

		return $totalMoney;
	}
