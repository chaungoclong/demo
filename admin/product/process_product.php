<?php 
require_once '../../common.php';

/**
 * trường ảnh mô tả không bắt buộc-> nếu sai hoặc thiếu vẫn tải sản phẩm lên mà không tải những file bị sai
 */
if (!empty($_POST['action']) && $_POST['action'] == "add") {
	$status = "fail";
	$name = $brand = $category = $price = $quantity = $color = $desc = $detail = $active = $fileImg = $fileLib = $fileName 
	= $listFileName = "";
	$folder      = "../../image/";
	$extension   = ['jpg', 'jpeg', 'png'];
	$error = ['name'=>'', 'library'=>[]];
	$ok = true;

	// tên
	if(empty($_POST['name'])) {
		$error['name'] = "Tên không được để trống";
		$ok = false;
	} else {
		$name = data_input($_POST['name']);

		if(!check_word($name)) {
			$error['name'] = "Tên sai định dạng";
			$ok = false;
		}

		if(productExist($name)) {
			$error['name'] = "sản phẩm đã tồn tại";
			$ok = false;
		}
	}

	// danh mục
	if(empty($_POST['category'])) {
		$ok = false;
	} else {
		$category = $_POST['category'];
	}

	// hãng
	if(empty($_POST['brand'])) {
		$ok = false;
	} else {
		$brand = $_POST['brand'];
	}

	// giá
	if(empty($_POST['price'])) {
		$ok = false;
	} else {
		$price = data_input($_POST['price']);

		if(float($price) === false || (float)$price <= 0) {
			$ok = false;
		}
	}

	// só lượng
	if(empty($_POST['quantity'])) {
		$ok = false;
	} else {
		$quantity = data_input($_POST['quantity']);

		if(int($quantity) === false || (int)$price <= 0) {
			$ok = false;
		}
	}

	// màu sắc
	if(empty($_POST['color'])) {
		$ok = false;
	} else {
		$color = data_input($_POST['color']);

		if(!check_name($color)) {
			$ok = false;
		}
	}

	// mô tả
	if(empty($_POST['desc'])) {
		$ok = false;
	} else {
		$desc = $_POST['desc'];
	}

	// Thông số
	if(empty($_POST['detail'])) {
		$ok = false;
	} else {
		$detail = $_POST['detail'];
	}

	// trạng thái
	$active = !empty($_POST['active']) ? 1 : 0;

	// ảnh đại diện
	if(empty($_FILES['image'])) {
		$ok = false;
	} else {
		$fileImg = $_FILES['image'];

		$fileName = up_file($fileImg, $folder, $extension);
		if(!$fileName) {
			$ok = false;
		}
	}

	if($ok) {
		$addProductSQL ="INSERT INTO db_product
		(cat_id, bra_id, pro_name, pro_img, pro_color, pro_price, pro_qty, pro_desc, pro_detail, pro_active)
		VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
		";
		$param = [$category, $brand, $name, $fileName, $color, $price, $quantity, $desc, $detail, $active];
		$runAddProduct = db_run($addProductSQL, $param, "iisssiissi");

		if($runAddProduct) {
			$proID = $connect->insert_id;
			$limitImg = 10;
			$status = "success";

			if(!empty($_FILES['library'])) {
				$fileLib          = $_FILES['library'];
				$totalImgInsert   = count($fileLib['name']);

				if($totalImgInsert > $limitImg) {
					$error['library'][] = "Số lượng ảnh tải lên vượt quá giới hạn cho phép $totalImgInsert";
				}

				if(!$error['library']) {
					$upLib            = multiUploadFile($fileLib, $folder, $extension);
					$error['library'] = $upLib['error'];
					$listFileName     = $upLib['result'];

					if($listFileName) {
						foreach ($listFileName as $key => $fileName) {
							$addLibSQL = "INSERT INTO db_image(pro_id, img_url) VALUES(?, ?)";
							$runAddLib = db_run($addLibSQL, [$proID, $fileName], "is");
						}
					}
				}	
			}
		} else {
			$status = "fail";
		}
	}

	$output = ['status'=>$status, 'error'=>$error];
	echo json_encode($output);
}


// SỬA SẢN PHẨM
if (!empty($_POST['action']) && $_POST['action'] == "edit") {
	$status = "fail";
	$proID = $oldImage = $name = $brand = $category = $price = $quantity = $color = $desc = $detail = $active = $fileImg = $fileLib = $fileName 
	= $listFileName = "";
	$folder      = "../../image/";
	$extension   = ['jpg', 'jpeg', 'png'];
	$error = ['name'=>'', 'library'=>[]];
	$ok = true;

	// id
	$proID = data_input($_POST['proID']);

	// ảnh cũ
	$oldImage  = data_input($_POST['oldImage']);

	// tên
	if(empty($_POST['name'])) {
		$error['name'] = "Tên không được để trống";
		$ok = false;
	} else {
		$name = data_input($_POST['name']);

		if(!check_word($name)) {
			$error['name'] = "Tên sai định dạng";
			$ok = false;
		}

		$nameIsExistSQL = "SELECT pro_id FROM db_product WHERE pro_name = ? AND pro_id != ? LIMIT 1";
		$runCheckName = s_cell($nameIsExistSQL, [$name, $proID], "si");

		if($runCheckName) {
			$error['name'] = "Tên đã tồn tại";
			$ok = false;
		}
	}

	// danh mục
	if(empty($_POST['category'])) {
		$ok = false;
	} else {
		$category = $_POST['category'];
	}

	// hãng
	if(empty($_POST['brand'])) {
		$ok = false;
	} else {
		$brand = $_POST['brand'];
	}

	// giá
	if(empty($_POST['price'])) {
		$ok = false;
	} else {
		$price = data_input($_POST['price']);

		if(float($price) === false || (float)$price <= 0) {
			$ok = false;
		}
	}

	// só lượng
	if(empty($_POST['quantity'])) {
		$ok = false;
	} else {
		$quantity = data_input($_POST['quantity']);

		if(int($quantity) === false || (int)$price <= 0) {
			$ok = false;
		}
	}

	// màu sắc
	if(empty($_POST['color'])) {
		$ok = false;
	} else {
		$color = data_input($_POST['color']);

		if(!check_name($color)) {
			$ok = false;
		}
	}

	// mô tả
	if(empty($_POST['desc'])) {
		$ok = false;
	} else {
		$desc = $_POST['desc'];
	}

	// Thông số
	if(empty($_POST['detail'])) {
		$ok = false;
	} else {
		$detail = $_POST['detail'];
	}

	// trạng thái
	$active = !empty($_POST['active']) ? 1 : 0;

	// ảnh đại diện
	if(empty($_FILES['image'])) {
		$fileName = $oldImage;
	} else {
		$fileImg = $_FILES['image'];

		$fileName = up_file($fileImg, $folder, $extension);
		if(!$fileName) {
			$ok = false;
		}
	}

	if($ok) {
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
			pro_desc       = ?,
			pro_detail     = ?,
			pro_active     = ?
			WHERE
			pro_id = ?
			";

			$param = [
				$category, $brand, $name, $fileName, $color, $price, $quantity, $desc, $detail, $active, $proID
			];

			$runUpdateProduct = db_run($updateProductSQL, $param, "iisssiissii");

		if($runUpdateProduct) {
			$limitImg = 10;
			$status = "success";

			if(!empty($_FILES['library'])) {
				$fileLib          = $_FILES['library'];
				$totalImgInsert   = count($fileLib['name']);
				$totalCurrentImg  = count(getImageProduct($proID));

				if($totalImgInsert > $limitImg) {
					$error['library'][] = "Số lượng ảnh tải lên vượt quá giới hạn cho phép. Ảnh sẽ không được tải lên";
				} elseif(($totalImgInsert < $limitImg) && ($totalImgInsert + $totalCurrentImg > $limitImg)) {
					$error['library'][] = "Kho ảnh đã đầy. Ảnh sẽ không được tải lên";
				}

				if(!$error['library']) {
					$upLib            = multiUploadFile($fileLib, $folder, $extension);
					$error['library'] = $upLib['error'];
					$listFileName     = $upLib['result'];

					if($listFileName) {
						foreach ($listFileName as $key => $fileName) {
							$addLibSQL = "INSERT INTO db_image(pro_id, img_url) VALUES(?, ?)";
							$runAddLib = db_run($addLibSQL, [$proID, $fileName], "is");
						}
					}
				}	
			}
		} else {
			$status = "fail";
		}
	}

	$output = ['status'=>$status, 'error'=>$error];
	echo json_encode($output);
}


// THAY ĐỔI TRẠNG THÁI
if (!empty($_POST['action']) && $_POST['action'] == "switch_active") {
	$ok                 = true;
	$proID              = input_post("proID");
	$newActive          = $_POST['active'];
	$switchActiveSQL    = "UPDATE db_product SET pro_active = ? WHERE pro_id = ?";
	$runSwitchActiveSQL = db_run($switchActiveSQL, [$newActive, $proID], 'ii');

	$ok = $runSwitchActiveSQL ? true : false;
	$output = ["ok" => $ok];
	echo json_encode($output);
}



// xóa sản phẩm
if (!empty($_POST['action']) && $_POST['action'] == "delete") {
	$status = "error";

	// mã sản phẩm
	$proID = input_post("proID");
	if(hasOrder($proID)) {
		$status = "has_order";
	} else {
		$deleteSQL = "DELETE FROM db_product WHERE pro_id = ?";
		$runDelete = db_run($deleteSQL, [$proID], "i");
		$status = $runDelete ? "success" : "error";
	}

	$output = ["status" => $status];
	echo json_encode($output);
}



// xóa ảnh chi tiết
if (!empty($_POST['action']) && $_POST['action'] == "remove_img_lib") {
	$status = "fail";
	$result = $imgID = $proID =  "";

	// id ảnh cần xóa
	$imgID = input_post('imgID');

	// id sản phẩm chứa ảnh cần xóa
	$proID = input_post('proID');

	// xóa
	$removeSQL = "DELETE FROM db_image WHERE img_id = ?";
	$runRemove = db_run($removeSQL, [$imgID], "i");
	$status    = $runRemove ? "success" : "fail";

	// danh sách ảnh sau khi xóa
	$listLibrary = getImageProduct($proID);

	// kết quả sau khi xóa
	$result = '<div class="oldLibrary d-flex">';
	foreach ($listLibrary as $key => $img) {
		$result .= ' 
		<div class="img_lib_box text-center mr-2 bg-info" style="width: 80px !important;">

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
	$result .= "</div>";

	$output = ["status"=>$status, "result"=>$result];

	echo json_encode($output);
}