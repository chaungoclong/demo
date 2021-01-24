<?php 
require_once '../../common.php';


if (!empty($_POST['action']) && $_POST['action'] == "add") {

	$status = 5;
	$upImageError = "";
	$upLibraryError = ""; 

	//lấy dữ liệu gửi lên từ ajax
	$name      = data_input(input_post("name"));
	$brand     = data_input(input_post("brand"));
	$category  = data_input(input_post("category"));
	$price     = data_input(input_post("price"));
	$quantity  = data_input(input_post("quantity"));
	$color     = data_input(input_post("color"));
	$shortDesc = input_post("shortDesc");
	$desc      = input_post("desc");
	$detail    = input_post("detail");
	$active    = data_input(input_post("active"));
	$active    = $active ? 1 : 0;



	// echo $name . "<br>";
	// echo $brand . "<br>";
	// echo $category . "<br>";
	// echo $price . "<br>";
	// echo $quantity . "<br>";
	// echo $color . "<br>";
	// echo $shortDesc . "<br>";
	// echo $desc . "<br>";
	// echo $detail . "<br>";
	// echo $active . "<br>";
	// exit;

	// đường dẫn đến thư mục lưu ảnh
	$folder = "../../image/";

	//  danh sách đuôi file hợp lệ
	$extension = ['jpg', 'jpeg', 'png'];

	// lấy ảnh
	$imageFile = !empty($_FILES['image']) ? $_FILES['image'] : null;
	$libraryFile = !empty($_FILES['library']) ? $_FILES['library'] : null;
	
	// lấy đường dẫn trang trước
	$prevLink  = isset($_POST['prevLink']) ? $_POST['prevLink'] : "index.php";

	//validate
	if(
		$name      === false ||
		$brand     === false ||
		$category  === false ||
		$price     === false ||
		$quantity  === false ||
		$color     === false ||
		$shortDesc === false ||
		$desc      === false ||
		$detail    === false ||
		$imageFile === null
	) {
		$status = 1;
	} elseif(productExist($name)) {
		$status = 3;
	} else {

		// thêm sản phẩm vào bảng sản phẩm
		$imageName = up_file($imageFile, $folder, $extension);
		if(!$imageName) {
			$upImageError = "Tải ảnh đại diện không thành công";
		}

		$addProductSQL = "  
		INSERT INTO db_product
		(cat_id, bra_id, pro_name, pro_img, pro_color, pro_price, pro_qty, pro_short_desc, pro_desc, pro_detail, pro_active)
		VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
		";

		$param = [
			$category, $brand, $name, $imageName, $color, $price, $quantity, $shortDesc, $desc, $detail, $active
		];

		$runAddProduct = db_run($addProductSQL, $param, "iisssiisssi");

		// tải sản phẩm thành công -> tải ảnh của sản phẩm
		if($runAddProduct) {

			// mã của sản phẩm vừa tải lên
			$productID = $connect->insert_id;

			// nếu tồn tại file ảnh -> tải lên
			if($libraryFile != null) {
				$upLibrary = multiUploadFile($libraryFile, $folder, $extension);
				$listFileName = $upLibrary['result'];
				
				foreach ($listFileName as $key => $fileName) {
					$addLibrarySQL = "INSERT INTO db_image(pro_id, img_url) VALUES(?, ?)";
					$runAddLibrary = db_run($addLibrarySQL, [$productID, $fileName], "is");
				}

				// danh sách lỗi upfile
				$upLibraryError = implode("<br>", $upLibrary['error']);
			}

			$status = 5;

		} else {
			// tải sản phẩm thất bại
			$status = 6; 
		}
	}
	
	// biến lưu kết quả trả về
	$res = [
		"status"     => $status,
		"libraryErr" => $upLibraryError,
		"imageErr"   => $upImageError,
		"prevLink"   =>$prevLink
	];

	echo json_encode($res);
}


// SỬA SẢN PHẨM
if (!empty($_POST['action']) && $_POST['action'] == "edit") {

	$status         = 5;
	$upLibraryError = ""; 
	$limitImgLib    = 10;

	//lấy dữ liệu gửi lên từ ajax
	$productID = data_input(input_post("proID"));
	$oldImage  = data_input(input_post("oldImage"));
	$name      = data_input(input_post("name"));
	$brand     = data_input(input_post("brand"));
	$category  = data_input(input_post("category"));
	$price     = data_input(input_post("price"));
	$quantity  = data_input(input_post("quantity"));
	$color     = data_input(input_post("color"));
	$shortDesc = input_post("shortDesc");
	$desc      = input_post("desc");
	$detail    = input_post("detail");
	$active    = data_input(input_post("active"));
	$active    = $active ? 1 : 0;



	// echo $name . "<br>";
	// echo $brand . "<br>";
	// echo $category . "<br>";
	// echo $price . "<br>";
	// echo $quantity . "<br>";
	// echo $color . "<br>";
	// echo $shortDesc . "<br>";
	// echo $desc . "<br>";
	// echo $detail . "<br>";
	// echo $active . "<br>";
	// exit;

	// đường dẫn đến thư mục lưu ảnh
	$folder = "../../image/";

	//  danh sách đuôi file hợp lệ
	$extension = ['jpg', 'jpeg', 'png'];

	// lấy ảnh
	$imageFile = !empty($_FILES['image']) ? $_FILES['image'] : null;
	$libraryFile = !empty($_FILES['library']) ? $_FILES['library'] : null;
	
	// lấy đường dẫn trang trước
	$prevLink  = isset($_POST['prevLink']) ? $_POST['prevLink'] : "index.php";

	//validate
	if(
		$name      === false ||
		$brand     === false ||
		$category  === false ||
		$price     === false ||
		$quantity  === false ||
		$color     === false ||
		$shortDesc === false ||
		$desc      === false ||
		$detail    === false ||
		$oldImage  === false
	) {
		$status = 1;
	} else {

		// thêm sản phẩm vào bảng sản phẩm
		$nameIsExistSQL = "SELECT pro_id FROM db_product WHERE pro_name = ? AND pro_id != ? LIMIT 1";
		$runCheckName = s_cell($nameIsExistSQL, [$name, $productID], "si");

		if($runCheckName) {
			$status = 3;
		} else {

			$imageName = "";
			if($imageFile != null) {
				$imageName = up_file($imageFile, $folder, $extension);
			} else {
				$imageName = $oldImage;
			}

			$updateProductSQL = "  
			UPDATE db_product
			SET 
			cat_id         = ?,
			bra_id         = ?,
			pro_name       = ?,
			pro_img        = ?,
			pro_color      = ?,
			pro_price      = ?,
			pro_qty        = ?,
			pro_short_desc = ?,
			pro_desc       = ?,
			pro_detail     = ?,
			pro_active     = ?
			WHERE
			pro_id = ?
			";

			$param = [
				$category, $brand, $name, $imageName, $color, $price, $quantity, $shortDesc, $desc, $detail, $active, $productID
			];

			$runUpdateProduct = db_run($updateProductSQL, $param, "iisssiisssii");

			// tải sản phẩm thành công -> tải ảnh của sản phẩm
			if($runUpdateProduct) {

				// nếu tồn tại file ảnh -> tải lên
				if($libraryFile != null) {

					// kiểm tra số lượng
					$imgQtyCurrent = count(getImageProduct($productID));
					$imgQtyNew = count($libraryFile['name']);

					if($imgQtyCurrent + $imgQtyNew > $limitImgLib) {
						$upLibraryError = "số lượng ảnh vượt quá giới hạn cho phép($limitImgLib ảnh)";
					} else {

						$upLibrary = multiUploadFile($libraryFile, $folder, $extension);
						$listFileName = $upLibrary['result'];

						foreach ($listFileName as $key => $fileName) {
							$addLibrarySQL = "INSERT INTO db_image(pro_id, img_url) VALUES(?, ?)";
							$runAddLibrary = db_run($addLibrarySQL, [$productID, $fileName], "is");
						}

						// danh sách lỗi upfile
						$upLibraryError = implode("<br>", $upLibrary['error']);
					}
				}

				$status = 5;

			} else {
				// tải sản phẩm thất bại
				$status = 6; 
			}
		}
		
	}
	
	// biến lưu kết quả trả về
	$res = [
		"status"     => $status,
		"libraryErr" => $upLibraryError,
		"prevLink"   =>$prevLink
	];

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
		"status"   =>$status,
		"userID"   =>$userID,
		"active"   =>$newActive,
		"prevLink" =>$prevLink
	];

	echo json_encode($res);
}



// xóa sản phẩm
if (!empty($_POST['action']) && $_POST['action'] == "remove") {
	$status = 5;

	// mã sản phẩm
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



// xóa ảnh chi tiết
if (!empty($_POST['action']) && $_POST['action'] == "remove_img_lib") {
	$status = 5;
	$html = "";

	// id ảnh cần xóa
	$imgID = data_input(input_post('imgID'));
	$proID = data_input(input_post('proID'));

	// xóa
	if($imgID) {
		$removeSQL = "DELETE FROM db_image WHERE img_id = ?";
		$runRemove = db_run($removeSQL, [$imgID], "i");
		$status    = $runRemove ? 5 : 6;
	} else {
		$status = 1;
	}

	// danh sách ảnh sau khi xóa
	$listLibrary = getImageProduct($proID);

	// kết quả sau khi xóa
	$html = '<div class="oldLibrary d-flex">';
	foreach ($listLibrary as $key => $img) {
		$html .= ' 
		<div class="img_lib_box text-center mr-2 bg-info" style="width: 150px !important;">

		<!-- ảnh -->
		<img src="../../image/' . $img['img_url'] . '" alt="" width="100%">

		<!-- nút xóa -->
		<button 
		type="button"
		class="btn_remove_img_lib btn btn-danger" 
		id="btn_remove_img_lib_' . $img['img_id'] . '" 
		data-img-id="' . $img['img_id'] . '"
		title="xóa ảnh"
		>
		<i class="fas fa-backspace"></i>
		</button>
		</div>
		';
	}
	$html .= "</div>";

	$res = ["status"=>$status, "html"=>$html];

	echo json_encode($res);
}