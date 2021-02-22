<?php 
require_once '../../common.php';

// THÊM NGƯỜI DÙNG
if (!empty($_POST['action']) && $_POST['action'] == "add") {
	$status = "fail";
	$role = $uname = $name = $dob = $gender = $email = $phone = $pwd = $rePwd =  $active = $file = $fileName = "";
	$folder = "../../image/";
	$extension = ['png', 'jpeg', 'jpg'];
	$error = [];
	$ok = true;

	// uname
	if(empty($_POST['uname'])) {
		$ok = false;
		$error[] = "USERNAME: không được để trống";
	} else {
		$uname = data_input($_POST['uname']);
		if(!check_word($uname)) {
			$ok = false;
			$error[] = "USERNAME: sai định dạng";
		} elseif(userExist('db_admin', 'ad_uname', $uname)) {
			$ok = false;
			$error[] = "USERNAME: username đã tồn tại";
		}
	}

	// tên
	if(empty($_POST['name'])) {
		$ok = false;
		$error[] = "TÊN: không được để trống";
	} else {
		$name = data_input($_POST['name']);
		if(!check_name($name)) {
			$ok = false;
			$error[] = "TÊN: sai định dạng";
		}
	}

	// password
	if(empty($_POST['pwdRegister'])) {
		$ok = false;
		$error[] = "PASSWORD: không được để trống";
	} else {
		$pwd = password($_POST['pwdRegister']);
		if($pwd === false) {
			$ok = false;
			$error[] = "PASSWORD: sai định dạng";
		}
	}

	// repassword
	if(empty($_POST['rePwdRegister'])) {
		$ok = false;
		$error[] = "RE_PASSWORD: không được để trống";
	} else {
		$rePwd = password($_POST['rePwdRegister']);
		if($rePwd === false) {
			$ok = false;
			$error[] = "RE_PASSWORD: sai định dạng";
		} elseif($rePwd != $pwd) {
			$ok = false;
			$error[] = "RE_PASSWORD: không khớp";
		}
	}

	// ngày sinh
	if(empty($_POST['dob'])) {
		$ok = false;
		$error[] = "NGÀY SINH: không được để trống";
	} else {
		$dob = formatDate(data_input($_POST['dob']));
	}

	// giới tính
	if(isset($_POST['gender'])) {
		$gender = data_input($_POST['gender']);
	} else {
		$ok = false;
		$error[] = "GIỚI TÍNH: không được để trống";
	}

	// email
	if(empty($_POST['email'])) {
		$ok = false;
		$error[] = "EMAIL: không được để trống";
	} else {
		$email = email($_POST['email']);

		if($email === false) {
			$ok = false;
			$error[] = "EMAIL: sai định dạng";
		} else if(emailExist("db_admin", "ad_email", $email)) {
			$ok = false;
			$error[] = "EMAIL: đã tồn tại";
		}
	}

	// điện thoại
	if(empty($_POST['phone'])) {
		$ok = false;
		$error[] = "PHONE: không được để trống";
	} else {
		$phone = phone($_POST['phone']);
		if($phone === false) {
			$ok = false;
			$error[] = "PHONE: sai định dạng";
		} elseif(phoneExist("db_admin", "ad_phone", $phone)) {
			$ok = false;
			$error[] = "PHONE: đã tồn tại";	
		}
	}

	// file
	if(!empty($_FILES['avatar'])) {
		$file = $_FILES['avatar'];
		$fileName = up_file($file, $folder, $extension);

		if(!$fileName) {
			$ok = false;
			$error[] = "ẢNH: tải lên không thành công";
		}
	}

	// trạng thái
	$active = !empty($_POST['active']) ? 1 : 0;

	// quyền
	$role = data_input($_POST['role']);

	if($ok) {
		$addSQL = "INSERT INTO db_admin(
		ad_uname, ad_name, ad_dob, ad_gender, ad_email, ad_phone, ad_password, ad_avatar, ad_role, ad_active)
		VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
		";
		$param   = [$uname, $name, $dob, $gender, $email, $phone, $pwd, $fileName, $role, $active];
		$runAdd = db_run($addSQL, $param, "sssissssii");
		$status = $runAdd ? "success" : "fail";
	}

	$output = ['status'=>$status, 'error'=>$error];
	echo json_encode($output);
}


// THAY ĐỔI TRẠNG THÁI
if (!empty($_POST['action']) && $_POST['action'] == "switch_active") {
	$ok = true;
	$userID = $_POST['userID'];
	$role = s_cell("SELECT ad_role FROM db_admin WHERE ad_id = ?", [$userID], "i");
	$active = $_POST['active'];

	// kiểm tra quyền
	if($role == 1) {
		$ok = false;
	} else {
		$switchSQL = "UPDATE db_admin SET ad_active = ? WHERE ad_id = ?";
		$runSwitch = db_run($switchSQL, [$active, $userID], "ii");
		$ok = $runSwitch ? true : false;
	}
	
	$output = ['ok'=>$ok];
	echo json_encode($output);
}


// CHỈNH SỬA THÔNG TIN
if (!empty($_POST['action']) && $_POST['action'] == "edit") {
	$status = "fail";
	$userID = $role = $uname = $name = $dob = $gender = $email = $phone = $oldAvatar = $active = $file = $fileName = "";
	$folder = "../../image/";
	$extension = ['png', 'jpeg', 'jpg'];
	$error = [];
	$ok = true;

	// user ID
	if(empty($_POST['userID'])) {
		$ok = false;
	} else {
		$userID = data_input($_POST['userID']);
	}

	// uname
	if(empty($_POST['uname'])) {
		$ok = false;
		$error[] = "USERNAME: không được để trống";
	} else {
		$uname = data_input($_POST['uname']);
		if(!check_word($uname)) {
			$ok = false;
			$error[] = "USERNAME: sai định dạng";
		}
	}

	// tên
	if(empty($_POST['name'])) {
		$ok = false;
		$error[] = "TÊN: không được để trống";
	} else {
		$name = data_input($_POST['name']);
		if(!check_name($name)) {
			$ok = false;
			$error[] = "TÊN: sai định dạng";
		}
	}

	// ngày sinh
	if(empty($_POST['dob'])) {
		$ok = false;
		$error[] = "NGÀY SINH: không được để trống";
	} else {
		$dob = formatDate(data_input($_POST['dob']));
	}

	// giới tính
	if(isset($_POST['gender'])) {
		$gender = data_input($_POST['gender']);
	} else {
		$ok = false;
		$error[] = "GIỚI TÍNH: không được để trống";
	}

	// email
	if(empty($_POST['email'])) {
		$ok = false;
		$error[] = "EMAIL: không được để trống";
	} else {
		$email = email($_POST['email']);

		if($email === false) {
			$ok = false;
			$error[] = "EMAIL: sai định dạng";
		} else {
			$checkEmailSQL = "SELECT COUNT(*) FROM db_admin WHERE ad_email = ? AND ad_id != ?";
			$runCheckEmail = s_cell($checkEmailSQL, [$email, $userID], "si");

			if($runCheckEmail > 0) {
				$ok = false;
				$error[] = "EMAIL: đã tồn tại";
			}
		}
	}

	// điện thoại
	if(empty($_POST['phone'])) {
		$ok = false;
		$error[] = "PHONE: không được để trống";
	} else {
		$phone = phone($_POST['phone']);
		if($phone === false) {
			$ok = false;
			$error[] = "PHONE: sai định dạng";
		} else {
			$checkPhoneSQL = "SELECT COUNT(*) FROM db_admin WHERE ad_phone = ? AND ad_id != ?";
			$runCheckPhone = s_cell($checkPhoneSQL, [$phone, $userID], "si");

			if($runCheckPhone > 0) {
				$ok = false;
				$error[] = "PHONE: đã tồn tại";
			}
		}
	}

	// old avatar
	if(!empty($_POST['oldAvatar'])) {
		$oldAvatar = data_input($_POST['oldAvatar']);
	}

	// file
	if(!empty($_FILES['avatar'])) {
		$file = $_FILES['avatar'];
		$fileName = up_file($file, $folder, $extension);

		if(!$fileName) {
			$ok = false;
			$error[] = "ẢNH: tải lên không thành công";
		}
	} else {
		$fileName = $oldAvatar;
	}

	// trạng thái
	$active = !empty($_POST['active']) ? 1 : 0;

	// quyền
	$role = data_input($_POST['role']);

	if($ok) {
		$updateSQL = "UPDATE db_admin
			SET 
			ad_uname  = ?,
			ad_name   = ?, 
			ad_dob    = ?,
			ad_gender = ?,
			ad_email  = ?,
			ad_phone  = ?,
			ad_avatar = ?,
			ad_role   = ?,
			ad_active = ?
			WHERE ad_id = ?
			";
			$param = [$uname, $name, $dob, $gender, $email, $phone, $fileName, $role, $active, $userID];
			$runUpdate = db_run($updateSQL, $param, "sssisssiii");
			$status = $runUpdate ? "success" : "fail";
	}

	$output = ['status'=>$status, 'error'=>$error];
	echo json_encode($output);
}


// XÓA NGƯỜI DÙNG
if (!empty($_POST['action']) && $_POST['action'] == "delete") {
	$status = "error";
	$userID = $_POST['userID'];
	$role = s_cell("SELECT ad_role FROM db_admin WHERE ad_id = ?", [$userID], "i");

	if($role == 1) {
		$status = "error";
	} else {
		$deleteSQL = "DELETE FROM db_admin WHERE ad_id = ?";
		$runDelete = db_run($deleteSQL, [$userID], "i");
		$status = $runDelete ? "success" : "error";
	}

	$output = ['status'=>$status];
	echo json_encode($output);
}
?>