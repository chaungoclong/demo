<?php 
	require_once '../../common.php';


	// THÊM SLIDE
	if(!empty($_POST['action']) && $_POST['action'] == "add") {
		$limitSlide        = 20;
		// $currenQtySlide = (int)count(getListSlide()->fetch_all(MYSQLI_ASSOC));
		$currenQtySlide    = countRow("db_slider");
		$status            = "fail";
		$folder            = "../../image/";
		$extension         = ['jpeg', 'jpg', 'png'];
		$error             = ["category"=>"", "file"=>[]];
		$catID             = $link = $file = $listFileName = "";
		$ok                = true;
		
		// category ID
		if(empty($_POST['cat'])) {
			$error['category'] = "Danh mục không được để trống";
			$ok = false;
		} else {
			$catID = data_input($_POST['cat']);
		}

		// link
		if(empty($_POST['link'])) {
			$error['link'] = "Link không được để trống";
			$ok = false;
		} else {
			$link = url($_POST['link']);
			if(!$link) {
				$error['link'] = "Link sai định dạng";
			}
		}

		// file
		if(empty($_FILES['slide'])) {
			$error['file'][] = "Ảnh không được để trống";
			$ok = false;
		} else {
			$file = $_FILES['slide'];
			if(count($file['name']) + $currenQtySlide > $limitSlide) {
				$error['file'][] = "Số lượng slide vượt quá giới hạn";
				$ok = false;
			} else {
				$upFile = multiUploadFile($file, $folder, $extension);
				$listFileName = $upFile['result'];
				$error['file'] = $upFile['error'];
			}
		}
		
		if($ok) {
			// tải tên file slide + vị trí slide + danh mục slide lên database
			foreach ($listFileName as $key => $fileName) {
				// vị trí cuối cùng
				$lastPos = lastPostion();

				// vị trí mới = vị trí cuối + 1
				$newPos = (int)$lastPos + 1;

				// up tên file + vị trí + danh mục lên database
				$upSlideSQL = "INSERT INTO db_slider(cat_id, sld_image, sld_pos, sld_link) VALUES(?, ?, ?, ?)";
				$runUp = db_run($upSlideSQL, [$catID, $fileName, $newPos, $link], "isis");	
			}
			$status = "success";
		}

		$output = ['status'=>$status, 'error'=>$error];
		echo json_encode($output);
	}

	// ===================THAY ĐỔI VỊ TRÍ SLIDE===================================
	if(!empty($_POST['action']) && $_POST['action'] == 'move') {
		$ok = true;
		$sldID = input_post('sldID');
		$opt = data_input(input_post('option'));
		
		/**
		 * xử lý:
		 * B1:
		 * thay đổi vị trí của slide có id = id gửi lên +- 1
		 * sau khi thay đổi sẽ có 2 slide có vị trí giống nhau:
		 * 1 - 2 | 1 - 2
		 * 2 - 3 | 1 - 2
		 * =>
		 * B2: 
		 * thay đổi vị trí của slide có id != id gửi lên và 
		 * có vị trí = vị trí của slide có id = id gửi lên sau khi được cập nhật ở B1(-+)
		 * 
		 */
		if($opt == "up") {
			// B1
			$upSQL = "UPDATE db_slider SET sld_pos = sld_pos - 1 WHERE sld_id = ? AND sld_pos > 1";
			$runUp1 = db_run($upSQL, [$sldID], "i");

			//B2
			$upSQL = "
			UPDATE db_slider SET sld_pos = sld_pos + 1 
			WHERE
				sld_pos IN(SELECT sld_pos FROM db_slider WHERE sld_id = ?)
				AND sld_id != ?
			";
			$runUp2 = db_run($upSQL, [$sldID, $sldID], "ii");

			$ok = $runUp1 && $runUp2 ? true : false;
		}

		if($opt == "down") {
			$lastPos = lastPostion();
			// B1
			$downSQL = "UPDATE db_slider SET sld_pos = sld_pos + 1 WHERE sld_id = ? AND sld_pos < ?";
			$runUp1 = db_run($downSQL, [$sldID, $lastPos], "ii");

			//B2
			$downSQL = "
			UPDATE db_slider SET sld_pos = sld_pos - 1 
			WHERE
				sld_pos IN(SELECT sld_pos FROM db_slider WHERE sld_id = ?)
				AND sld_id != ?
			";
			$runUp2 = db_run($downSQL, [$sldID, $sldID], "ii");

			$ok = $runUp1 && $runUp2 ? true : false;
		}

		$output = ['ok'=>$ok];
		echo json_encode($output);
	}


	// ============== SỬA SLIDE ========================== //
	if(!empty($_POST['action']) && $_POST['action'] == "edit") {
		$status       = "fail";
		$folder       = '../../image/';
		$extension    = ['jpeg', 'jpg', 'png'];
		$sldID = $catID = $link = $oldSlide = $oldPos = $newPos = $file = $fileName = "";
		$error = [];
		$ok = true;

		// slide ID
		if(empty($_POST['sldID'])) {
			$ok = false;
		} else {
			$sldID = data_input($_POST['sldID']);
		}
		
		// category ID
		if(empty($_POST['cat'])) {
			$error[] = "Danh mục không được để trống";
			$ok = false;
		} else {
			$catID = data_input($_POST['cat']);
		}

		// link
		if(empty($_POST['link'])) {
			$error['link'] = "Link không được để trống";
			$ok = false;
		} else {
			$link = url($_POST['link']);
			if(!$link) {
				$error['link'] = "Link sai định dạng";
			}
		}

		// oldSlide
		if(empty($_POST['oldSlide'])) {
			$ok = false;
		} else {
			$oldSlide = data_input($_POST['oldSlide']);
		}

		// oldPos
		if(empty($_POST['oldPos'])) {
			$ok = false;
		} else {
			$oldPos = data_input($_POST['oldPos']);
		}

		// newPos
		if(empty($_POST['newPos'])) {
			$ok = false;
		} else {
			$newPos = data_input($_POST['newPos']);
		}
		
		// file ảnh
		if(!empty($_FILES['newSlide'])) {
			$file = $_FILES['newSlide'];
			$fileName = up_file($file, $folder, $extension);

			if(!$fileName) {
				$error[] = "Tải ảnh không thành công";
				$ok = false;
			}
		} else {
			$fileName = $oldSlide;
		}
		
		if($ok) {

			/**
			 * B1: cập nhật slide cần sửa
			 * B2: cập nhật vị trí của slide có vị trí = vị trí mới của slide vừa sửa
			 * 
			 */
			$editSlideSQL = "
			UPDATE db_slider SET 
			cat_id    = ?,
			sld_image = ?,
			sld_pos   = ?,
			sld_link  = ?
			WHERE 
			sld_id = ?";
			$runEdit1     = db_run($editSlideSQL, [$catID, $fileName, $newPos, $link, $sldID], 'isisi');

			$editSlideSQL = "UPDATE db_slider SET sld_pos = ? WHERE sld_pos = ? AND sld_id != ?";
			$runEdit2     = db_run($editSlideSQL, [$oldPos, $newPos, $sldID], 'iii');
			
			$status       = $runEdit1 && $runEdit2 ? "success" : "fail";
		}

		$output = ['status'=>$status, 'error'=>$error];
		echo json_encode($output);
	}


	// XÓA SLIDE
	/**
	 * B1: giảm giá trị vị trí của các slide có vị trí lớn hơn slide cần xóa
	 * B2: xóa slide cần xóa
	 */
	if(!empty($_POST['action']) && $_POST['action'] == "delete") {
		$status = "error";
		$sldID  = input_post('sldID');

		// giảm vị trí của các slide có vị trí lớn hơn slide cần xóa
		$setPosNextSlideSQL = "
		UPDATE db_slider SET sld_pos = sld_pos - 1
		WHERE sld_pos > (SELECT sld_pos FROM db_slider WHERE sld_id = ?) AND sld_id != ?
		";
		$runSet    = db_run($setPosNextSlideSQL, [$sldID, $sldID], "ii");
		
		// xóa slide cần xóa
		$deleteSQL = "DELETE FROM db_slider WHERE sld_id = ?";
		$runDelete = db_run($deleteSQL, [$sldID], 'i');
		$status = $runSet && $runDelete ? "success" : "error";

		$output = ['status'=>$status];
		echo json_encode($output);
	}
