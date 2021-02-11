<?php 
require_once '../../common.php';


// THÊM DANH MỤC
if (!empty($_POST['action']) && $_POST['action'] == "add") {

	$status = 5;
	$upImageError = "";

	//lấy dữ liệu gửi lên từ ajax
	$name   = data_input(input_post("name"));
	$active = data_input(input_post("active"));
	$active = $active ? 1 : 0;

	// đường dẫn đến thư mục lưu ảnh
	$folder = "../../image/";

	//  danh sách đuôi file hợp lệ
	$extension = ['jpg', 'jpeg', 'png'];

	// lấy ảnh
	$imageFile = !empty($_FILES['image']) ? $_FILES['image'] : null;

	//validate
	if($name === false || $imageFile == null) {
		$status = 1;
	} elseif(categoryExist($name)) {
		$status = 3;
	} else {

		// thêm sản phẩm vào bảng sản phẩm
		$imageName = up_file($imageFile, $folder, $extension);
		if(!$imageName) {
			$upImageError = "Tải ảnh đại diện không thành công";
		}

		$addCategorySQL = "  
		INSERT INTO db_category(cat_name, cat_logo, cat_active)
		VALUES(?, ?, ?)
		";

		$param = [$name, $imageName, $active];

		$runAddCategory = db_run($addCategorySQL, $param, "ssi");

		$status = $runAddCategory ? 5 : 6;

	}
	
	// biến lưu kết quả trả về
	$res = [
		"status"     => $status,
		"imageErr"   => $upImageError
	];

	echo json_encode($res);
}


// SỬA DANH MỤC
if (!empty($_POST['action']) && $_POST['action'] == "edit") {

	$status       = 5;
	$upImageError = "";

	//lấy dữ liệu gửi lên từ ajax
	$categoryID = data_input(input_post("catID"));
	$oldImage   = data_input(input_post("oldImage"));
	$name       = data_input(input_post("name"));
	$active     = data_input(input_post("active"));
	$active     = $active ? 1 : 0;

	// đường dẫn đến thư mục lưu ảnh
	$folder = "../../image/";

	//  danh sách đuôi file hợp lệ
	$extension = ['jpg', 'jpeg', 'png'];

	// lấy ảnh
	$imageFile = !empty($_FILES['image']) ? $_FILES['image'] : null;
	

	//validate
	if($name === false) {
		$status = 1;
	} else {

		// kiểm tra tên danh mục có trùng với các danh mục khác
		$nameIsExistSQL = "SELECT cat_id FROM db_category WHERE cat_name = ? AND cat_id != ? LIMIT 1";
		$runCheckName = s_cell($nameIsExistSQL, [$name, $categoryID], "si");

		if($runCheckName) {

			// tên danh mục đã tồn tại
			$status = 3;
		} else {
			
			// thêm sản phẩm vào bảng sản phẩm
			$imageName = "";
			if($imageFile != null) {
				$imageName = up_file($imageFile, $folder, $extension);
			} else {
				$imageName = $oldImage;
			}

			$updateCategorySQL = "  
			UPDATE db_category
			SET 
				cat_name   = ?,
				cat_logo   = ?,
				cat_active = ?
			WHERE
				cat_id = ?
			";

			$param = [$name, $imageName, $active, $categoryID];

			$runUpdateCategory = db_run($updateCategorySQL, $param, "ssii");

			$status = $runUpdateCategory ? 5 : 6;
		}
		
	}
	
	// biến lưu kết quả trả về
	$res = [
		"status"     => $status,
		"imageErr"   => $upImageError
	];

	echo json_encode($res);
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



// xóa sản phẩm
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



