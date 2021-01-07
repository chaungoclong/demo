<?php 
require_once 'common.php';
if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST)) {
	//lấy dữ liệu
	$user     = data_input(input_post("user"));
	$pwd      = data_input(input_post("pwdLogin"));
	$remember = data_input(input_post("remember"));

	//VALIDATE
	if(empty($user) || empty($pwd)) {
		echo 1;
	} else if((!check_phone($user) 
		&& !check_email($user)) || !check_password($pwd)) {
		echo 2;
	} else {
		//nếu không có lỗi đăng nhập
		$loginSQL = "
		SELECT * FROM db_customer
		WHERE (cus_email = ? OR cus_phone = ?)
		AND cus_password = ?
		";

		//lấy thông tin của người dùng có tài mật khẩu tương ứng
		//thông tin không trống và active = 1 => đăng nhập thành công
		$info = s_row($loginSQL, [$user, $user, $pwd]);
		if(!empty($info)) {
			if($info['cus_active']) {
				set_login($info['cus_id'], $info['cus_email']);
				$session = is_login();
				if(!empty($remember)) {
					setcookie("cus_user", $user, time() + 86400 * 2);
					setcookie("cus_pwd", $pwd, time() + 86400 * 2);
				} else {
					setcookie("cus_user",  '', time() - 1);
					setcookie("cus_pwd", '', time() - 1);
				}
				echo 5;
			} else {
				echo 8;
			}
			
		} else {
			echo 6;
		}
	}

}
?>