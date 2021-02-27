<?php 
require_once 'common.php';

if($_SERVER['REQUEST_METHOD'] == "POST") {
	$status = "fail";
	$ok = true;
	$error = [];
	$user = $pwd = "";

	// email/điện thoại
	if(empty($_POST['user'])) {
		$ok = false;
		$error[] = "VUI LÒNG NHẬP EMAIL HOẶC SỐ ĐIỆN THOẠI";
	} else {
		$user = data_input($_POST['user']);
		if(email($user) === false && phone($user) === false) {
			$ok = false;
			$error[] = "EMAIL HOẶC SỐ ĐIỆN THOẠI SAI ĐỊNH DẠNG";
		}
	}

	// mật khẩu
	if(empty($_POST['pwdLogin'])) {
		$ok = false;
		$error[] = "VUI LÒNG NHẬP MẬT KHẨU";
	} else {
		$pwd = password($_POST['pwdLogin']);
		if($pwd === false) {
			$ok = false;
			$error[] = "MẬT KHẨU SAI ĐỊNH DẠNG";
		}
	}

	if($ok) {
		$checkInfoSQL = "SELECT * FROM db_customer WHERE (cus_email = ? OR cus_phone = ?) AND cus_password = ?";
		$param = [$user, $user, $pwd];
		$check = s_row($checkInfoSQL, $param, "sss");

		if($check) {
			if($check['cus_active']) {
				set_login($check['cus_id'], $check['cus_email']);
				if(!empty($_POST['remember'])) {
					setcookie("cus_user", $user, time() + 86400 * 2);
					setcookie("cus_pwd", $pwd, time() + 86400 * 2);
				} else {
					setcookie("cus_user",  '', time() - 1);
					setcookie("cus_pwd", '', time() - 1);
				}
				$status = "success";
			} else {
				$error[] = "TÀI KHOẢN CỦA BẠN ĐÃ BỊ KHÓA";
				$status = "fail";
			}
		} else {
			$error[] = "TÀI KHOẢN HOẶC MẬT KHẨU KHÔNG CHÍNH XÁC";
			$status = "fail";
		}
	}

	$output = ['status'=>$status, 'error'=>$error];
	echo json_encode($output);
}
?>