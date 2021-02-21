<?php 
require_once '../../common.php';

// THAY ĐỔI TRẠNG THÁI
if (!empty($_POST['action']) && $_POST['action'] == "switch_active") {
	$ok = true;
	$cusID = $_POST['cusID'];
	$active = $_POST['active'];
	$switchSQL = "UPDATE db_customer SET cus_active = ? WHERE cus_id = ?";
	$runSwitch = db_run($switchSQL, [$active, $cusID], "ii");
	$ok = $runSwitch ? true : false;

	$output = ['ok'=>$ok];
	echo json_encode($output);
}


// CHỈNH SỬA THÔNG TIN
if (!empty($_POST['action']) && $_POST['action'] == "edit") {
	$status = "fail";
	$cusID = $address = $name = $dob = $gender = $email = $phone = $oldAvatar = $active = $file = $fileName = "";
	$folder = "../../image/";
	$extension = ['png', 'jpeg', 'jpg'];
	$error = [];
	$ok = true;

	// customer ID
	if(empty($_POST['cusID'])) {
		$ok = false;
	} else {
		$cusID = data_input($_POST['cusID']);
	}

	// address
	if(empty($_POST['address'])) {
		$ok = false;
		$error[] = "ĐỊA CHỈ: không được để trống";
	} else {
		$address = data_input($_POST['address']);
		if(!check_word($address)) {
			$ok = false;
			$error[] = "ĐỊA CHỈ: sai định dạng";
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
			$checkEmailSQL = "SELECT COUNT(*) FROM db_customer WHERE cus_email = ? AND cus_id != ?";
			$runCheckEmail = s_cell($checkEmailSQL, [$email, $cusID], "si");

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
			$checkPhoneSQL = "SELECT COUNT(*) FROM db_customer WHERE cus_phone = ? AND cus_id != ?";
			$runCheckPhone = s_cell($checkPhoneSQL, [$phone, $cusID], "si");

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

	$active = !empty($_POST['active']) ? 1 : 0;

	if($ok) {
		$updateSQL = "UPDATE db_customer
			SET 
			cus_name    = ?, 
			cus_dob     = ?,
			cus_gender  = ?,
			cus_email   = ?,
			cus_phone   = ?,
			cus_avatar  = ?,
			cus_address = ?,
			cus_active  = ?
			WHERE cus_id = ?
			";
			$param = [$name, $dob, $gender, $email, $phone, $fileName, $address, $active, $cusID];
			$runUpdate = db_run($updateSQL, $param, "ssissssii");
			$status = $runUpdate ? "success" : "fail";
	}

	$output = ['status'=>$status, 'error'=>$error];
	echo json_encode($output);
}


// XÓA NGƯỜI DÙNG
if (!empty($_POST['action']) && $_POST['action'] == "delete") {
	$status = "error";
	$cusID = $_POST['cusID'];

	// has order
	$checkHasOrderSQL = "SELECT COUNT(*) FROM db_order WHERE cus_id = ?";
	$runCheck = s_cell($checkHasOrderSQL, [$cusID], "i");

	if($runCheck > 0) {
		$status = "has_order";
	} else {
		$deleteSQL = "DELETE FROM db_customer WHERE cus_id = ?";
		$runDelete = db_run($deleteSQL, [$cusID], "i");
		$status = $runDelete ? "success" : "error";
	}

	$output = ['status'=>$status];
	echo json_encode($output);
}
?>