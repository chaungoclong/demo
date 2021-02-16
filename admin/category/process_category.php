<?php 
require_once '../../common.php';


// THÊM DANH MỤC
if (!empty($_POST['action']) && $_POST['action'] == "add") {
	$name      = $active = $file = $fileName =  "";
	$status    = "fail";
	$folder    = "../../image/";
	$extension = ['jpg', 'jpeg', 'png'];
	$error     = ['name'=>'', 'file'=>''];

	// tên
	if(empty($_POST['name'])) {
		$error['name'] = "tên không được để trống";
	} else {
		$name = data_input($_POST['name']);

		if(!check_word($name)) {
			$error['name'] = "Tên sai định dạng";
		} 
		if(categoryExist($name)) {
			$error['name'] = "Danh mục đã tồn tại";
		}
	}

	// trạng thái
	$active = !empty($_POST['active']) ? 1 : 0;

	// file
	if(empty($_FILES['image'])) {
		$error['file'] = "không được để trống";
	} else {
		$file = $_FILES['image'];
		$fileName = up_file($file, $folder, $extension);

		if(!$fileName) {
			$error['file'] = "file không hợp lệ";
		}
	}

	if(!$error['name'] && !$error['file']) {
		$addCategorySQL = "  
		INSERT INTO db_category(cat_name, cat_logo, cat_active)
		VALUES(?, ?, ?)
		";

		$param = [$name, $fileName, $active];
		$runAddCategory = db_run($addCategorySQL, $param, "ssi");

		$status = !$runAddCategory ? "fail" : "success";
	}

	$output = ['error'=>$error, 'status'=>$status];
	echo json_encode($output);
}


// SỬA DANH MỤC
if (!empty($_POST['action']) && $_POST['action'] == "edit") {
	$catID = $oldImage = $name = $active = $file = $fileName = '';
	$error = ['name'=>'', 'file'=>''];
	$folder    = "../../image/";
	$extension = ['jpg', 'jpeg', 'png'];
	$status = "fail";

	// mã danh mục
	$catID = data_input(int($_POST['catID']));

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

		// kiểm tra tên danh mục có trùng với các danh mục khác
		$nameIsExistSQL = "SELECT cat_id FROM db_category WHERE cat_name = ? AND cat_id != ? LIMIT 1";
		$runCheckName = s_cell($nameIsExistSQL, [$name, $catID], "si");

		if($runCheckName) {
			$error['name'] = "Danh mục đã tồn tại";
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
		$updateCategorySQL = "  
			UPDATE db_category
			SET 
			cat_name   = ?,
			cat_logo   = ?,
			cat_active = ?
			WHERE
			cat_id = ?
		";

		$param             = [$name, $fileName, $active, $catID];
		$runUpdateCategory = db_run($updateCategorySQL, $param, "ssii");
		$status            = $runUpdateCategory ? "success" : "fail";

		// ẩn hiện các sản phẩm của danh mục này nếu cập nhật danh mục thành công
		if($status == "success") {
			$turnOffSQL = "UPDATE db_product SET pro_active = ? WHERE cat_id = ?";
			$runTurnOff = db_run($turnOffSQL, [$active, $catID], 'ii');
		}
	}
	
	// biến lưu kết quả trả về
	$output = ["status" => $status, "error" => $error];
	echo json_encode($output);
}

// THAY ĐỔI TRẠNG THÁI
if (!empty($_POST['action']) && $_POST['action'] == "switch_active") {
	$ok                 = true;
	$catID              = input_post("catID");
	$newActive          = $_POST['active'];
	$switchActiveSQL    = "UPDATE db_category SET cat_active = ? WHERE cat_id = ?";
	$runSwitchActiveSQL = db_run($switchActiveSQL, [$newActive, $catID], 'ii');

	if($runSwitchActiveSQL) {
		// ẩn các sản phẩm của danh mục này
		$turnOffSQL    = "UPDATE db_product SET pro_active = ? WHERE cat_id = ?";
		$runTurnOff = db_run($turnOffSQL, [$newActive, $catID], 'ii');
		$ok = true;
	} else {
		$ok = false;
	}
	
	$output = ["ok" => $ok];
	echo json_encode($output);
}



// xóa danh mục
if (!empty($_POST['action']) && $_POST['action'] == "delete") {
	$status = "success";
	$catID = input_post("catID");

	if(hasProduct("cat_id", $catID)) {
		$status = "has_product";
	} else {
		$removeCatSQL = "DELETE FROM db_category WHERE cat_id = ?";
		$runRemoveCat = db_run($removeCatSQL, [$catID], "i");
		$status = ($runRemoveCat) ? "success" : "error";
	}

	$output = ['status' => $status];
	echo json_encode($output);
}



