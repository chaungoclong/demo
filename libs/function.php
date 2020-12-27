<?php 
	
	//lấy giá trị của biến $_POST có key = $key
	function input_post($key) {
		return isset($_POST[$key]) ? trim($_POST[$key]) : "";
	}

	//lấy giá trị của biến $_GET có key = $key
	function input_get($key) {
		return isset($_GET[$key]) ? trim($_GET[$key]) : "";
	}

	//hàm tạo url(chỉ dùng để điều hướng thanh menu)
	function base_url($path = '') {
		return "http://localhost/do_an_1/" . $path;
	}

	//hàm chuyển hướng
	function redirect($path) {
		header("Location:{$path}");
	}

	//hàm trả về ngày tháng hiện tại
	function now(){
		return date('Y-m-d H:i:s');
	}

	//hàm check dữ liệu 
	function check_input($value){
		$value = stripslashes($value);
		$value = htmlspecialchars($value);
		$value = trim($value);
		return $value;
	}

	//hàm thay thế phần tử đầu tiên
	function replace_first($search, $replace, $string) {
		return implode($replace, explode($search, $string, 2));
	}

	//hàm tạo link
	function create_link($uri, $data = []) {
		$string = '';
		foreach ($data as $key => $value) {
			if($value != '') {
				$string .= "&{$key}={$value}";
			}
		}
		echo $uri . replace_first("&", "?", $string);
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

