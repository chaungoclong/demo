<?php 
	//hàm tạo session
	function start_session() {
		if(session_id() === '') {
			session_start();
		}
	}

	//hàm gán giá trị cho session
	function set_session($key, $data) {
		start_session();
		$_SESSION[$key] = $data;
	}

	//hàm lấy session
	function get_session($key) {
		start_session();
		return $_SESSION[$key] ?? false;
	}

	//hàm xóa session
	function delete_session($key) {
		start_session();
		if(isset($_SESSION[$key])) {
			unset($_SESSION[$key]);
		}
	}

	//hàm đăng nhập
	function set_login($id, $email, $role = 3) {
		start_session();
		set_session("user_token", [
			"id"    => $id,
			"email" =>$email,
			"role"  =>$role
		]);
	}

	//hàm đăng xuất
	function set_logout() {
		start_session();
		delete_session("user_token");
	}

	//hàm kiểm tra đã đăng nhập?
	function is_login() {
		start_session();
		$user = get_session("user_token");
		return $user;
	}

	//hàm kiểm tra có phải admin
	function is_admin() {
		start_session();
		$user = is_login();
		if(!empty($user["role"]) && $user["role"] <= 2) {
			return true;
		}
		return false;
	}

	//hàm lấy thông tin ngưởi dùng bằng id
	function getUserById($id) {
		start_session();
		db_connect();

		global $connect;
		$data = [];

		if(is_admin()) {
			$fromTable = "db_admin";
			$field = "ad_id";
		} else {
			$fromTable = "db_customer";
			$field = "cus_id";
		}

		$sql = "SELECT * FROM {$fromTable} WHERE $field = ?";
		$data = s_row($sql, [$id]);
		
		return $data;
	}

	
	function deleteCookie($key) {
		if(isset($_COOKIE[$key])) {
			setcookie($key, '', time() - 1);
		}
	}


