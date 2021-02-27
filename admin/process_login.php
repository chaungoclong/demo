<?php 
require_once '../common.php';

// if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST)) {
// 	//lấy dữ liệu
// 	$user     = data_input(input_post("user"));
// 	$pwd      = data_input(input_post("pwdLogin"));
// 	$remember = data_input(input_post("remember"));

// 	//VALIDATE
// 	if(empty($user) || empty($pwd)) {
// 		echo 1;
// 	} else if((!check_phone($user) && !check_email($user) && !check_word($user)) 
// 		|| !check_password($pwd)) {
// 		echo 2;
// 	} else {
// 		//nếu không có lỗi đăng nhập
// 		$loginSQL = "
// 		SELECT * FROM db_admin
// 		WHERE (ad_email = ? OR ad_phone = ? OR ad_uname = ?)
// 		AND ad_password = ?
// 		";

// 		//lấy thông tin của người dùng có tài mật khẩu tương ứng
// 		//thông tin không trống và active = 1 => đăng nhập thành công
// 		$info = s_row($loginSQL, [$user, $user, $user, $pwd], "ssss");
// 		if(!empty($info)) {
// 			if($info['ad_active']) {
// 				set_login($info['ad_id'], $info['ad_email'], $info['ad_role']);
// 				$session = is_login();
// 				if(!empty($remember)) {
// 					setcookie("ad_user", $user, time() + 86400 * 2);
// 					setcookie("ad_pwd", $pwd, time() + 86400 * 2);
// 				} else {
// 					setcookie("ad_user",  '', time() - 1);
// 					setcookie("ad_pwd", '', time() - 1);
// 				}
// 				echo 5;
// 			} else {
// 				echo 8;
// 			}
			
// 		} else {
// 			echo 6;
// 		}
// 	}

// }

if($_SERVER['REQUEST_METHOD'] == "POST") {
	$status = "fail";
	$ok = true;
	$error = [];
	$user = $pwd = "";

	// user
	if(empty($_POST['user'])) {
		$ok = false;
		$error[] = "VUI LÒNG NHẬP EMAIL HOẶC SỐ ĐIỆN THOẠI HOẶC TÊN ĐĂNG NHẬP";
	} else {
		$user = data_input($_POST['user']);
		if(email($user) === false && phone($user) === false && word($user) === false) {
			$ok = false;
			$error[] = "EMAIL HOẶC SỐ ĐIỆN THOẠI HOẶC TÊN ĐĂNG NHẬP SAI ĐỊNH DẠNG";
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
		$checkInfoSQL = "SELECT * FROM db_admin 
		WHERE (ad_uname = ? OR ad_email = ? OR ad_phone = ?) AND ad_password = ?";
		$param = [$user, $user, $user, $pwd];
		$check = s_row($checkInfoSQL, $param, "ssss");

		// thông tin khớp
		if($check) {
			//tài khoản không khóa
			if($check['ad_active']) {
				set_login($check['ad_id'], $check['ad_email'], $check['ad_role']);

				if(!empty($_POST['remember'])) {
					setcookie("ad_user", $user, time() + 86400 * 2);
					setcookie("ad_pwd", $pwd, time() + 86400 * 2);
				} else {
					setcookie("ad_user",  '', time() - 1);
					setcookie("ad_pwd", '', time() - 1);
				}
				$status = "success";
			} else {
				$error[] = "TÀI KHOẢN CỦA BẠN ĐÃ BỊ KHÓA";
				$status = "fail";
			}
		} else {
			$error[] = "THÔNG TIN ĐĂNG NHẬP KHÔNG CHÍNH XÁC";
			$status = "fail";
		}
	}

	$output = ['status'=>$status, 'error'=>$error];
	echo json_encode($output);
}
?>