<?php 
require_once '../../common.php';

// THAY ĐỔI TRẠNG THÁI
if (!empty($_POST['action']) && $_POST['action'] == "switch_active") {
	$status = 5;

		// mã khách hàng
	$customerID = data_input(input_post("customerID"));

		// trạng thái muốn cập nhật
	$newActive = $_POST['newActive'] ?? null;

		// validate
	if($customerID === false || $newActive === null) {
		$status = 1;
	} else {
		$switchActiveSQL = "UPDATE db_customer SET cus_active = ? WHERE cus_id = ?";
		$runSwitchActiveSQL = db_run($switchActiveSQL, [$newActive, $customerID], 'ii');
		if($runSwitchActiveSQL) {
			$status = 5;
		} else {
			$status = 6;
		}
	}

	$res = [
		"status"=>$status,
		"customerID"=>$customerID,
		"active"=>$newActive
	];

	echo json_encode($res);
}


// CHỈNH SỬA THÔNG TIN
if (!empty($_POST['action']) && $_POST['action'] == "edit") {
		// lấy dữ liệu gửi lên từ ajax
	$cusID     = data_input(input_post("cusID"));
	$address   = data_input(input_post("address"));
	$name      = data_input(input_post("name"));
	$dob       = formatDate(data_input(input_post("dob")));
	$gender    = data_input(input_post("gender"));
	$email     = data_input(input_post("email"));
	$phone     = data_input(input_post("phone"));
	$oldAvatar = data_input(input_post('oldAvatar'));
	$active    = data_input(input_post("active"));
	$active    = $active ? 1 : 0;

	
	// echo $cusID.$address.$name.$dob.$gender.$email.$phone.$oldAvatar.$active;
	// nếu không có file tải lên thì tên file = tên file avatar cũ
	$imgFile = !empty($_FILES['avatar']) ? $_FILES['avatar'] : null;
	if(!empty($imgFile)) {
		$imgFileName = up_file($imgFile, "../../image/", ['png', 'jpeg', 'jpg', 'gif']);
	} else {
		$imgFileName = $oldAvatar;
	}

	//validate
	if($cusID === false || $name === false || $dob === false || $gender === false || 
		$email === false || $phone === false || $oldAvatar === false || $address === false) {
		$status = 1;
	} else if(!check_name($name) || !check_date($dob) || !check_email($email)
		|| !check_phone($phone)) {
		$status = 2;
	} else {

		// kiểm tra email đã tồn tại
		$checkEmail = s_row(
			"SELECT * FROM db_customer WHERE cus_email = ? AND cus_id != ?",
			[$email, $cusID], 
			"si"
		);

		// kiểm tra điện thoại đã tồn tại
		$checkPhone = s_row(
			"SELECT * FROM db_customer WHERE cus_phone = ? AND cus_id != ?",
			[$phone, $cusID], 
			"si"
		);

		//nếu tồn tại email || số điện thoại -> bóa lỗi{3: email, 4: phone}
		//ngược lại -> cập nhật
		if($checkEmail) {
			$status = 3;
		} else if($checkPhone) {
			$status = 4;
		} else {
			// SQL update info
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

			// dữ liệu 
			$data      = [$name, $dob, $gender, $email, $phone, $imgFileName, $address, $active, $cusID];

			// update
			$runUpdate = db_run($updateSQL, $data, "ssissssii");

			$status = $runUpdate ? 5 : 6;

		}	
	}

	//tập hợp dữ liệu trả về
	$res = [
		"status"   => $status
	];

	// trả về dữ liệu
	echo json_encode($res);
}


// XÓA NGƯỜI DÙNG
if (!empty($_POST['action']) && $_POST['action'] == "remove") {
	$status = 5;

	// mã người dùng
	$customerID = data_input(input_post("customerID"));

	if($customerID === false) {
		$status = 1;
	} else {
		$removeCustomerSQL = "DELETE FROM db_customer WHERE cus_id = ?";
		$runRemoveCustomer = db_run($removeCustomerSQL, [$customerID], "i");
		$status = ($runRemoveCustomer) ? 5 : 6;
	}

	echo $status;
}
?>