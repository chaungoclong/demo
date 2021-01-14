<?php
require_once 'common.php';
if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST)) {
	$name    = data_input(input_post("name"));
	$dob     = formatDate(data_input(input_post("dob")));
	$gender  = data_input(input_post("gender"));
	$email   = data_input(input_post("email"));
	$phone   = data_input(input_post("phone"));
	$address = data_input(input_post("address"));
	$pwd     = data_input(input_post("pwdRegister"));
	$rePwd   = data_input(input_post("rePwdRegister"));
	$file    = !empty($_FILES['avatar']) ? $_FILES['avatar'] : null;

	if($name === false || $dob === false || $gender === false 
		|| $email === false || $phone === false || $pwd === false 
		|| $rePwd === false || $address === false) {
		echo 1; //thiếu thông tin
	} else if(!check_name($name) || !check_date($dob)
		|| !check_password($pwd) || !check_email($email)
		|| !check_phone($phone) || $rePwd != $pwd) {
		echo 2; //thông tin sai
	} else if(emailExist("db_customer", "cus_email", $email)) {
		echo 3; //email đẫ tồn tại
	} else if(phoneExist("db_customer", "cus_phone", $phone)) {
		echo 4; //số điện thoại đã tồn tại
	} else {
		$imgName = ""; //tên file ảnh avatar thêm  vào database
		if($file == null) {
			$imgName = "";
		} else {
			$imgName = up_file($file, "image/", ['jpeg', 'jpg', 'png', 'gif']);
		}

		//câu sql thêm người dùng
		$addCustomerSQL = "  
		INSERT INTO db_customer(cus_name, cus_dob, cus_gender, cus_email, cus_phone, cus_password, cus_avatar, cus_address)
		VALUES(?, ?, ?, ?, ?, ?, ?, ?);
		";

		//nếu thêm thành công 5 : 6
		$add = db_run(
			$addCustomerSQL,
			[$name, $dob, $gender, $email, $phone, $pwd, $imgName, $address],
			"ssisssss"
		);
		if($add) {
			echo 5; //đăng ký thành công
		} else {
			echo 6; //đăng kí thất bại
		}
	}
}
?>