<?php 
require_once '../../common.php';


// THÊM hãng
if (!empty($_POST['action']) && $_POST['action'] == "add") {
	$status       = "fail";
	$name         = $active = $file = $fileName = "";
	$folder       = "../../image/";
	$extension    = ['jpg', 'jpeg', 'png'];
	$error = ['name' =>'', 'file'=>''];

	// tên
	if(empty($_POST['name'])) {
		$error['name'] = "Tên hãng không được để trống";
	} else {
		$name = data_input($_POST['name']);

		if(!check_word($name)) {
			$error['name'] = "Tên sai định dạng";
		}
		if(brandExist($name)) {
			$error['name'] = "Hãng đã tồn tại";
		}
	}

	// file
	if(empty($_FILES['image'])) {
		$error['file'] = "Ảnh không được để trống";
	} else {
		$file     = $_FILES['image'];
		$fileName = up_file($file, $folder, $extension);

		if(!$fileName) {
			$error['file'] = "File không hợp lệ";
		}
	}

	// trạng thái
	$active = !empty($_POST['active']) ? 1 : 0;

	if(!$error['name'] && !$error['file']) {
		$addBrandSQL = "INSERT INTO db_brand(bra_name, bra_logo, bra_active) VALUES(?, ?, ?)";
		$param       = [$name, $fileName, $active];
		$runAddBrand = db_run($addBrandSQL, $param, "ssi");
		
		$status      = $runAddBrand ? "success" : "fail";
	}

	$output = ['status' => $status, 'error' => $error];
	echo json_encode($output);
}


// SỬA hãng
if (!empty($_POST['action']) && $_POST['action'] == "edit") {
	$braID     = $oldImage = $name = $active = $file = $fileName = '';
	$error     = ['name'=>'', 'file'=>''];
	$folder    = "../../image/";
	$extension = ['jpg', 'jpeg', 'png'];
	$status    = "fail";

	// mã hãng
	$braID = data_input(int($_POST['braID']));

	// ảnh cũ
	$oldImage = data_input($_POST['oldImage']);

	// tên
	if(empty($_POST['name'])) {
		$error['name'] = "tên không được để trống";
	} else {
		$name = data_input($_POST['name']);

		if(!check_word($name)) {
			$error['name'] = "Tên sai định dạng";
		} 

		// kiểm tra tên hãng có trùng với các hãng khác
		$nameIsExistSQL = "SELECT bra_id FROM db_brand WHERE bra_name = ? AND bra_id != ? LIMIT 1";
		$runCheckName = s_cell($nameIsExistSQL, [$name, $braID], "si");

		if($runCheckName) {
			$error['name'] = "hãng đã tồn tại";
		} 
	}

	// file
	if(!empty($_FILES['image'])) {
		$file = $_FILES['image'];
		$fileName = up_file($file, $folder, $extension);

		if(!$fileName) {
			$error['file'] = "file không hợp lệ";
		}
	} else {
		$fileName = $oldImage;
	}

	// trạng thái
	$active = !empty($_POST['active']) ? 1 : 0;
	
	if(!$error['name'] && !$error['file']) {
		$updateBrandSQL = "  
			UPDATE db_brand
			SET 
			bra_name   = ?,
			bra_logo   = ?,
			bra_active = ?
			WHERE
			bra_id = ?
		";

		$param          = [$name, $fileName, $active, $braID];
		$runUpdateBrand = db_run($updateBrandSQL, $param, "ssii");
		$status         = $runUpdateBrand ? "success" : "fail";

		// ẩn hiện các sản phẩm của hãng này nếu cập nhật hãng thành công
		if($status == "success") {
			$switchSQL = "UPDATE db_product SET pro_active = ? WHERE bra_id = ?";
			$runSwitch = db_run($switchSQL, [$active, $braID], 'ii');
		}
	}
	
	// biến lưu kết quả trả về
	$output = ["status" => $status, "error" => $error];
	echo json_encode($output);
}


// THAY ĐỔI TRẠNG THÁI
if (!empty($_POST['action']) && $_POST['action'] == "switch_active") {
	$ok                 = true;
	$braID              = input_post("braID");
	$newActive          = $_POST['active'];
	$switchActiveSQL    = "UPDATE db_brand SET bra_active = ? WHERE bra_id = ?";
	$runSwitchActiveSQL = db_run($switchActiveSQL, [$newActive, $braID], 'ii');

	if($runSwitchActiveSQL) {
		// ẩn các sản phẩm của hãng này
		$switchSQL    = "UPDATE db_product SET pro_active = ? WHERE bra_id = ?";
		$runSwitchSQL = db_run($switchSQL, [$newActive, $braID], 'ii');
		$ok = true;
	} else {
		$ok = false;
	}
	
	$output = ["ok" => $ok];
	echo json_encode($output);
}



// xóa hãng
if (!empty($_POST['action']) && $_POST['action'] == "delete") {
	$status = "error";
	$braID = data_input(input_post("braID"));

	if(hasProduct("bra_id", $braID)) {
		$status = "has_product";
	} else {
		$deleteBraSQL = "DELETE FROM db_brand WHERE bra_id = ?";
		$runDeleteBra = db_run($deleteBraSQL, [$braID], "i");
		$status       = ($runDeleteBra) ? "success" : "error" ;
	}

	$output = ['status'=>$status];
	echo json_encode($output);
}



