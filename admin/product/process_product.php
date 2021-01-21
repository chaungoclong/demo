<?php 
require_once '../../common.php';


if (!empty($_POST['action']) && $_POST['action'] == "add") {

	// lấy dữ liệu gửi lên từ ajax
	$uname     = data_input(input_post("uname"));
	$name      = data_input(input_post("name"));
	$dob       = formatDate(data_input(input_post("dob")));
	$gender    = data_input(input_post("gender"));
	$email     = data_input(input_post("email"));
	$phone     = data_input(input_post("phone"));
	$active    = data_input(input_post("active"));
	$role      = data_input(input_post("role"));
	$pwd       = data_input(input_post("pwdRegister"));
	$rePwd     = data_input(input_post("rePwdRegister"));
	$active    = $active ? 1 : 0;

	$prevLink = isset($_POST['prevLink']) ? $_POST['prevLink'] : "index.php";

	// nếu không có file tải lên thì tên file = ""
	$imgFile = !empty($_FILES['avatar']) ? $_FILES['avatar'] : null;
	if(!empty($imgFile)) {
		$imgFileName = up_file($imgFile, "../../image/", ['png', 'jpeg', 'jpg', 'gif']);
	} else {
		$imgFileName = "";
	}

	//validate
	if($uname === false || $name === false || $dob === false || $gender === false || 
		$email === false || $phone === false || $role === false || $pwd === false || $rePwd === false) {

		// thiếu dữ liệu
		$status = 1;
	} else if(!check_name($name) || !check_date($dob) || !check_email($email)
		|| !check_phone($phone) || $pwd != $rePwd) {

		// dữ liệu sai
		$status = 2;
	} else if(emailExist("db_admin", "ad_email", $email)) {

		//email đẫ tồn tại
		$status = 3; 
	} else if(phoneExist("db_admin", "ad_phone", $phone)) {

		// só điện thoại đã tồn tại
		$status = 4 ;
	} else {
		// SQL update info
		$addSQL = "INSERT INTO db_admin(
		ad_uname, ad_name, ad_dob, ad_gender, ad_email, ad_phone, ad_password, ad_avatar, ad_role, ad_active)
		VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
		";
		
		// dũ liệu 
		$data   = [$uname, $name, $dob, $gender, $email, $phone, $pwd, $imgFileName, $role, $active];
		
		// add
		$runAdd = db_run($addSQL, $data, "sssissssii");
		
		$status = $runAdd ? 5 : 6;
	}

	//tập hợp dữ liệu trả về
	$res = [
		"status"   => $status,
		"prevPage" => $prevLink
	];

	// trả về dữ liệu
	echo json_encode($res);
}


// THAY ĐỔI TRẠNG THÁI
if (!empty($_POST['action']) && $_POST['action'] == "switch_active") {
	$status = 5;

		// mã nhân viên
	$proID = data_input(input_post("proID"));

		// trạng thái muốn cập nhật
	$newActive = $_POST['newActive'] ?? null;

		// validate
	if($proID === false || $newActive === null) {
		$status = 1;
	} else {
		$switchActiveSQL    = "UPDATE db_product SET pro_active = ? WHERE pro_id = ?";
		$runSwitchActiveSQL = db_run($switchActiveSQL, [$newActive, $proID], 'ii');
		if($runSwitchActiveSQL) {
			$status = 5;
		} else {
			$status = 6;
		}
	}

	$res = [
		"status"=>$status,
		"userID"=>$userID,
		"active"=>$newActive
	];

	echo json_encode($res);
}



// CHỈNH SỬA THÔNG TIN
if (!empty($_POST['action']) && $_POST['action'] == "edit") {

	// lấy dữ liệu gửi lên từ ajax
	$userID    = data_input(input_post("userID"));
	$uname     = data_input(input_post("uname"));
	$name      = data_input(input_post("name"));
	$dob       = formatDate(data_input(input_post("dob")));
	$gender    = data_input(input_post("gender"));
	$email     = data_input(input_post("email"));
	$phone     = data_input(input_post("phone"));
	$oldAvatar = data_input(input_post('oldAvatar'));
	$active    = data_input(input_post("active"));
	$role      = data_input(input_post("role"));
	$active    = $active ? 1 : 0;

	$prevLink = isset($_POST['prevLink']) ? $_POST['prevLink'] : "index.php";

	// nếu không có file tải lên thì tên file = tên file avatar cũ
	$imgFile = !empty($_FILES['avatar']) ? $_FILES['avatar'] : null;
	if(!empty($imgFile)) {
		$imgFileName = up_file($imgFile, "../../image/", ['png', 'jpeg', 'jpg', 'gif']);
	} else {
		$imgFileName = $oldAvatar;
	}

	//validate
	if($userID === false || $uname === false || $name === false || $dob === false || $gender === false || 
		$email === false || $phone === false || $oldAvatar === false || $role === false) {
		$status = 1;
	} else if(!check_name($name) || !check_name($name) || !check_date($dob) || !check_email($email)
		|| !check_phone($phone)) {
		$status = 2;
	} else {

		// kiểm tra email đã tồn tại
		$checkEmail = s_row(
			"SELECT * FROM db_admin WHERE ad_email = ? AND ad_id != ?",
			[$email, $userID], 
			"si"
		);

		// kiểm tra điện thoại đã tồn tại
		$checkPhone = s_row(
			"SELECT * FROM db_admin WHERE ad_phone = ? AND ad_id != ?",
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

			// dũ liệu 
			$data      = [$uname, $name, $dob, $gender, $email, $phone, $imgFileName, $role, $active, $userID];

			// update
			$runUpdate = db_run($updateSQL, $data, "sssisssiii");

			$status = $runUpdate ? 5 : 6;

		}	
	}

	//tập hợp dữ liệu trả về
	$res = [
		"status"   => $status,
		"prevPage" => $prevLink
	];

	// trả về dữ liệu
	echo json_encode($res);
}


// XÓA NGƯỜI DÙNG
if (!empty($_POST['action']) && $_POST['action'] == "remove") {
	$status = 5;

	// mã người dùng
	$proID = data_input(input_post("proID"));

	if($proID === false) {
		$status = 1;
	} else if(hasOrder($proID)) {
		$status = 2;
	} else {
		$removeProSQL = "DELETE FROM db_product WHERE pro_id = ?";
		$runRemovePro = db_run($removeProSQL, [$proID], "i");
		$status = ($runRemovePro) ? 5 : 6;
	}

	echo $status;
}
?>