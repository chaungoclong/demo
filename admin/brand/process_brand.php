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

	// lấy đường dẫn trang trước
	$prevLink  = isset($_POST['prevLink']) ? $_POST['prevLink'] : "index.php";

	//validate
	if($name === false || $imageFile == null) {
		$status = 1;
	} elseif(brandExist($name)) {
		$status = 3;
	} else {

		// thêm sản phẩm vào bảng sản phẩm
		$imageName = up_file($imageFile, $folder, $extension);
		if(!$imageName) {
			$upImageError = "Tải ảnh đại diện không thành công";
		}

		$addBrandSQL = "  
		INSERT INTO db_brand(bra_name, bra_logo, bra_active)
		VALUES(?, ?, ?)
		";

		$param = [$name, $imageName, $active];

		$runAddBrand = db_run($addBrandSQL, $param, "ssi");

		$status = $runAddBrand ? 5 : 6;

	}
	
	// biến lưu kết quả trả về
	$res = [
		"status"     => $status,
		"imageErr"   => $upImageError,
		"prevLink"   =>$prevLink
	];

	echo json_encode($res);
}


// SỬA DANH MỤC
if (!empty($_POST['action']) && $_POST['action'] == "edit") {

	$status       = 5;
	$upImageError = "";
	
	//lấy dữ liệu gửi lên từ ajax
	$brandID      = data_input(input_post("braID"));
	$oldImage     = data_input(input_post("oldImage"));
	$name         = data_input(input_post("name"));
	$active       = data_input(input_post("active"));
	$active       = $active ? 1 : 0;
	
	// đường dẫn đến thư mục lưu ảnh
	$folder       = "../../image/";
	
	//  danh sách đuôi file hợp lệ
	$extension    = ['jpg', 'jpeg', 'png'];
	
	// lấy ảnh
	$imageFile    = !empty($_FILES['image']) ? $_FILES['image'] : null;
	
	// lấy đường dẫn trang trước
	$prevLink     = isset($_POST['prevLink']) ? $_POST['prevLink'] : "index.php";

	//validate
	if($name === false) {
		$status = 1;
	} else {

		// kiểm tra tên danh mục có trùng với các danh mục khác
		$nameIsExistSQL = "SELECT bra_id FROM db_brand WHERE bra_name = ? AND bra_id != ? LIMIT 1";
		$runCheckName = s_cell($nameIsExistSQL, [$name, $brandID], "si");

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

			$updateBrandSQL = "  
			UPDATE db_brand
			SET 
				bra_name   = ?,
				bra_logo   = ?,
				bra_active = ?
			WHERE
				bra_id = ?
			";

			$param = [$name, $imageName, $active, $brandID];

			$runUpdateBrand = db_run($updateBrandSQL, $param, "ssii");

			$status = $runUpdateBrand ? 5 : 6;
		}
		
	}
	
	// biến lưu kết quả trả về
	$res = [
		"status"     => $status,
		"imageErr"   => $upImageError,
		"prevLink"   => $prevLink
	];

	echo json_encode($res);
}


// THAY ĐỔI TRẠNG THÁI
if (!empty($_POST['action']) && $_POST['action'] == "switch_active") {
	$status = 5;

		// mã danh mục
	$braID = data_input(input_post("braID"));

		// trạng thái muốn cập nhật
	$newActive = $_POST['newActive'] ?? null;

		// validate
	if($braID === false || $newActive === null) {
		$status = 1;
	} else {
		$switchActiveSQL    = "UPDATE db_brand SET bra_active = ? WHERE bra_id = ?";
		$runSwitchActiveSQL = db_run($switchActiveSQL, [$newActive, $braID], 'ii');
		if($runSwitchActiveSQL) {

			$turnOffSQL = "UPDATE db_product SET pro_active = ? WHERE bra_id = ?";
			$runTurnOff = db_run($turnOffSQL, [$newActive, $braID], 'ii');
			$status     = 5;
		} else {
			$status = 6;
		}
	}

	$res = [
		"status"   =>$status,
		"id"=>$braID
	];

	echo json_encode($res);
}



// xóa sản phẩm
if (!empty($_POST['action']) && $_POST['action'] == "remove") {
	$status = 5;

	// mã sản phẩm
	$braID = data_input(input_post("braID"));

	if($braID === false) {
		$status = 1;
	} else if(hasProduct("bra_id", $braID)) {
		$status = 2;
	} else {
		$removeBraSQL = "DELETE FROM db_brand WHERE bra_id = ?";
		$runRemoveBra = db_run($removeBraSQL, [$braID], "i");
		$status = ($runRemoveBra) ? 5 : 6;
	}

	echo $status;
}



