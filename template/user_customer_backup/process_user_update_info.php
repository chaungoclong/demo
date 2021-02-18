<?php 
require_once '../common.php';

$status = 5;

if($_SERVER['REQUEST_METHOD'] == "POST") {

	// lấy dữ liệu gửi lên từ ajax
	$userID    = data_input(input_post("userID"));
	$name      = data_input(input_post("name"));
	$dob       = formatDate(data_input(input_post("dob")));
	$gender    = data_input(input_post("gender"));
	$email     = data_input(input_post("email"));
	$phone     = data_input(input_post("phone"));
	$address   = data_input(input_post("address"));
	$oldAvatar = data_input(input_post('oldAvatar'));

	// nếu không có file tải lên thì tên file = tên file avatar cũ
	$imgFile = !empty($_FILES['avatar']) ? $_FILES['avatar'] : null;
	if(!empty($imgFile)) {
		$imgFileName = up_file($imgFile, "../image/", ['png', 'jpeg', 'jpg', 'gif']);
	} else {
		$imgFileName = $oldAvatar;
	}

	//validate
	if($userID === false || $name === false || $dob === false || $gender === false || 
		$email === false || $phone === false || $address === false || $oldAvatar === false) {
		$status = 1;
	} else if(!check_name($name) || !check_date($dob) || !check_email($email)
		|| !check_phone($phone)) {
		$status = 2;
	} else {

		// kiểm tra email đã tồn tại
		$checkEmail = s_row(
			"SELECT * FROM db_customer WHERE cus_email = ? AND cus_id != ?",
			[$email, $userID], 
			"si"
		);

		// kiểm tra điện thoại đã tồn tại
		$checkPhone = s_row(
			"SELECT * FROM db_customer WHERE cus_phone = ? AND cus_id != ?",
			[$phone, $userID], 
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
			cus_name = ?, 
			cus_dob = ?,
			cus_gender = ?,
			cus_email = ?,
			cus_phone = ?,
			cus_avatar = ?,
			cus_address = ?
			WHERE cus_id = ?
			";

			// dũ liệu 
			$data      = [$name, $dob, $gender, $email, $phone, $imgFileName, $address, $userID];

			// update
			$runUpdate = db_run($updateSQL, $data, "ssissssi");

			$status = $runUpdate ? 5 : 6;

		}	
	}

	//dữ liệu trả về để cập nhật lại html
	$dataRes = [
		"name"=>$name,
		"dob" =>$dob,
		"gender"=>$gender,
		"email"=>$email,
		"phone"=>$phone,
		"avatar"=>$imgFileName,
		"address"=>$address
	];


	//tập hợp dữ liệu trả về
	$res = [
		"status" => $status,
		"info" => $dataRes
	];

	// trả về dữ liệu
	echo json_encode($res);
}
?>