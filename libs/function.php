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
					$link = str_replace("{page}", $currentPage + 1, $currentLink);
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
		$value = htmlspecialchars(stripcslashes(trim($value)));
		$value = $connect->real_escape_string($value);
		return $value;
	}

	function check_email($string) {
		$pattern = '/^[a-z][a-z0-9_\.]{5,32}@[a-z0-9]{2,}(\.[a-z0-9]{2,4}){1,2}$/';
		return preg_match($pattern, $string);
	}

	function check_date($string) {
		$pattern = '/^[12]\d{3}-(0[1-9]|1[12])-(0[1-9]|[12]\d|3[01])$/';
		return preg_match($pattern, $string);
	}

	function formatDate($string) {
		return implode('-', array_reverse(explode('-', $string)));
	}

	function check_name($string) {
		$pattern = '/^([a-zA-Z]{3,10}\s?)+$/';
		return preg_match($pattern, $string);
	}

	function check_password($string) {
		$pattern = '/^[a-zA-Z0-9+-\@\*\#]{8,32}$/';
		return preg_match($pattern, $string);
	}

	function check_phone($string) {
		$pattern = '/^(84|0[3|5|7|8|9])+([0-9]{8})$/';
		return preg_match($pattern, $string);
	}

	function emailExist($table, $fieldName, $email) {
		$sql = "select {$fieldName} from {$table} where {$fieldName} = ?";
		return db_get($sql, [$email], 2);
	}

	function phoneExist($table, $fieldName, $phone) {
		$sql = "select {$fieldName} from {$table} where {$fieldName} = ?";
		return db_get($sql, [$phone], 2);
	}

	function userExist($table, $fieldName, $user) {
		$sql = "select {$fieldName} from {$table} where {$fieldName} = ?";
		return db_get($sql, [$user], 2);
	}

	function vd($value) {
		echo "<pre>" . print_r($value, true) . "</pre>";
	}