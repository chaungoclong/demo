<?php
require_once 'common.php';
// if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST)) {
// 	$name    = data_input(input_post("name"));
// 	$dob     = formatDate(data_input(input_post("dob")));
// 	$gender  = data_input(input_post("gender"));
// 	$email   = data_input(input_post("email"));
// 	$phone   = data_input(input_post("phone"));
// 	$address = data_input(input_post("address"));
// 	$pwd     = data_input(input_post("pwdRegister"));
// 	$rePwd   = data_input(input_post("rePwdRegister"));
// 	$file    = !empty($_FILES['avatar']) ? $_FILES['avatar'] : null;

// 	if($name === false || $dob === false || $gender === false 
// 		|| $email === false || $phone === false || $pwd === false 
// 		|| $rePwd === false || $address === false) {

// 		echo 1; //thiếu thông tin
// 	} else if(!check_name($name) || !check_date($dob)
// 		|| !check_password($pwd) || !check_email($email)
// 		|| !check_phone($phone) || $rePwd != $pwd) {

// 		echo 2; //thông tin sai
// 	} else if(emailExist("db_customer", "cus_email", $email)) {

// 		echo 3; //email đẫ tồn tại
// 	} else if(phoneExist("db_customer", "cus_phone", $phone)) {
		
// 		echo 4; //số điện thoại đã tồn tại
// 	} else {
// 		$imgName = ""; //tên file ảnh avatar thêm  vào database
// 		if($file == null) {
// 			$imgName = "";
// 		} else {
// 			$imgName = up_file($file, "image/", ['jpeg', 'jpg', 'png', 'gif']);
// 		}

// 		//câu sql thêm người dùng
// 		$addCustomerSQL = "  
// 		INSERT INTO db_customer(cus_name, cus_dob, cus_gender, cus_email, cus_phone, cus_password, cus_avatar, cus_address)
// 		VALUES(?, ?, ?, ?, ?, ?, ?, ?);
// 		";

// 		//nếu thêm thành công 5 : 6
// 		$add = db_run(
// 			$addCustomerSQL,
// 			[$name, $dob, $gender, $email, $phone, $pwd, $imgName, $address],
// 			"ssisssss"
// 		);
// 		if($add) {
// 			echo 5; //đăng ký thành công
// 		} else {
// 			echo 6; //đăng kí thất bại
// 		}
// 	}
// }

if(!empty($_POST['action']) && $_POST['action'] == "register") {
	$status = "fail";
	$ok = true;
	$name = $dob = $gender = $email = $phone = $address = $pwd = $rePwd = $file = $fileName = "";
	$folder = "image/";
	$extension = ['jpg', 'png', 'jpeg'];
	$error = [];

	// tên
	if(empty($_POST['name'])) {
		$ok = false;
		$error[] = "TÊN: không được để trống";
	} else {
		$name = name($_POST['name']);
		if($name === false) {
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
		} else if(emailExist("db_customer", "cus_email", $email)) {
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
		} elseif(phoneExist("db_customer", "cus_phone", $phone)) {
			$ok = false;
			$error[] = "PHONE: đã tồn tại";	
		}
	}

	// địa chỉ
	if(empty($_POST['address'])) {
		$ok = false;
		$error[] = "ĐỊA CHỈ: không được để trống";
	} else {
		$address = word($_POST['address']);
		if($address === false) {
			$ok = false;
			$error[] = "ĐỊA CHỈ: sai định dạng";
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

	if($ok) {
		$registerSQL = "
		INSERT INTO db_customer(cus_name, cus_dob, cus_gender, cus_email, cus_phone, cus_password, cus_avatar, cus_address)
		VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
		$param = [$name, $dob, $gender, $email, $phone, $pwd, $fileName, $address];
		$runRegister = db_run($registerSQL, $param, "ssisssss");

		$status = $runRegister ? "success" : "fail";
	}

	$output = ['status'=>$status, 'error'=>$error];
	echo json_encode($output);





//  $name    = data_input(input_post("name"));
// 	$dob     = formatDate(data_input(input_post("dob")));
// 	$gender  = data_input(input_post("gender"));
// 	$email   = data_input(input_post("email"));
// 	$phone   = data_input(input_post("phone"));
// 	$address = data_input(input_post("address"));
// 	$pwd     = data_input(input_post("pwdRegister"));
// 	$rePwd   = data_input(input_post("rePwdRegister"));
}
?>