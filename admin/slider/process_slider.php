<?php 
	require_once '../../common.php';


	// THÊM SLIDE
	if(!empty($_POST['action']) && $_POST['action'] == "add") {

		// số lượng slide giới hạn
		$limitSlide  = 20;

		// số lượng slide hiện tại
		$currenQtySlide = (int)count(getListSlide()->fetch_all(MYSQLI_ASSOC));

		// trạng thái xử lý
		$status      = 5;
		
		// thông báo lỗi khi up file
		$errorUpFile = "";
		
		$catID       = data_input(input_post('cat'));
		
		$folder      = "../../image/";
		
		$extension   = ['jpeg', 'jpg', 'png'];
		
		$fileSlide   = !empty($_FILES['slide']) ? $_FILES['slide'] : null;

		if($catID === false || $fileSlide == null) {
			$status = 1;
		} elseif((int)count($fileSlide['name']) + $currenQtySlide > $limitSlide) {

			$errorUpFile = "số lượng ảnh vượt quá giới hạn cho phép($limitSlide ảnh)";
			$status = 6;
		} else {

			// tải tất cả ảnh slide vào thư mục chưa ảnh
			$upSlideFile = multiUploadFile($fileSlide, $folder, $extension);

			// lấy danh sách tên file trả về từ hàm up file
			$listFileName = $upSlideFile['result'];

			// tải tên file slide + vị trí slide + danh mục slide lên database
			foreach ($listFileName as $key => $fileName) {
				
				// vị trí cuối cùng
				$lastPos = lastPostion();

				// vị trí mới = vị trí cuối + 1
				$newPos = (int)$lastPos + 1;

				// up tên file + vị trí + danh mục lên database
				$upSlideSQL = "INSERT INTO db_slider(cat_id, sld_image, sld_pos) VALUES(?, ?, ?)";
				$runUp = db_run($upSlideSQL, [$catID, $fileName, $newPos], "isi");
				
			}

			$errorUpFile = implode('<br>', $upSlideFile['error']);

			$status = 5;
		}

		$res = ['status'=>$status, 'error'=>$errorUpFile];

		echo json_encode($res);
	}

	// ===================THAY ĐỔI VỊ TRÍ SLIDE===================================
	if(!empty($_POST['action']) && $_POST['action'] == 'change_pos') {

		$status = 5;

		$sldID = data_input(input_post('sldID'));

		$opt = data_input(input_post('opt'));
		
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
			$upSQL = "UPDATE db_slider SET sld_pos = sld_pos - 1 WHERE sld_id = ?";
			$runUp1 = db_run($upSQL, [$sldID], "i");

			//B2
			$upSQL = "
			UPDATE db_slider SET sld_pos = sld_pos + 1 
			WHERE
				sld_pos IN(SELECT sld_pos FROM db_slider WHERE sld_id = ?)
				AND sld_id != ?
			";
			$runUp2 = db_run($upSQL, [$sldID, $sldID], "ii");

			$status = $runUp1 && $runUp2 ? 5 : 6;

		}

		if($opt == "down") {

			// B1
			$downSQL = "UPDATE db_slider SET sld_pos = sld_pos + 1 WHERE sld_id = ?";
			$runUp1 = db_run($downSQL, [$sldID], "i");

			//B2
			$downSQL = "
			UPDATE db_slider SET sld_pos = sld_pos - 1 
			WHERE
				sld_pos IN(SELECT sld_pos FROM db_slider WHERE sld_id = ?)
				AND sld_id != ?
			";
			$runUp2 = db_run($downSQL, [$sldID, $sldID], "ii");

			$status = $runUp1 && $runUp2 ? 5 : 6;
		}

		echo $status;

	}


	// ============== SỬA SLIDE ========================== //
	if(!empty($_POST['action']) && $_POST['action'] == "edit") {

		$status       = 5;
		
		$folder       = '../../image/';
		
		$extension    = ['jpeg', 'jpg', 'png'];
		
		$sldID        = data_input(input_post('sldID'));
		
		$catID        = data_input(input_post('cat'));
		
		$oldSlide     = data_input(input_post('oldSlide'));
		
		$oldPos       = data_input(input_post('oldPos'));
		
		$newPos       = data_input(input_post('newPos'));
		
		$newSlideFile = !empty($_FILES['newSlide']) ? $_FILES['newSlide'] : null;
		


		if(
			$sldID    === false || 
			$catID    === false || 
			$oldSlide === false || 
			$oldPos   === false || 
			$newPos   === false
		) {

			// thiếu dữ liệu
			$status = 1;
		} else {
			$fileName = "";

			if($newSlideFile != null) {
				$fileName = up_file($newSlideFile, $folder, $extension);
			} else {
				$fileName = $oldSlide;
			}

			/**
			 * B1: cập nhật slide cần sửa
			 * B2: cập nhật vị trí của slide có vị trí = vị trí mới của slide vừa sửa
			 * 
			 */
			$editSlideSQL = "
			UPDATE db_slider SET 
				cat_id    = ?,
				sld_image = ?,
				sld_pos   = ?
			WHERE 
				sld_id = ?";

			$runEdit1     = db_run($editSlideSQL, [$catID, $fileName, $newPos, $sldID], 'isii');
			
			$editSlideSQL = "UPDATE db_slider SET sld_pos = ? WHERE sld_pos = ? AND sld_id != ?";
			
			$runEdit2     = db_run($editSlideSQL, [$oldPos, $newPos, $sldID], 'iii');
			
			$status       = $runEdit1 && $runEdit2 ? 5 : 6;

		}

		$res = ['status'=>$status];

		echo json_encode($res);
	}


	// XÓA SLIDE
	/**
	 * B1: giảm giá trị vị trí của các slide có vị trí lớn hơn slide cần xóa
	 * B2: xóa slide cần xóa
	 */
	if(!empty($_POST['action']) && $_POST['action'] == "remove") {

		$status = 5;
		
		$sldID  = data_input(input_post('sldID'));

		// giảm vị trí của các slide có vị trí lớn hơn slide cần xóa
		$setPosNextSlideSQL = "
		UPDATE db_slider SET sld_pos = sld_pos - 1
		WHERE sld_pos > (SELECT sld_pos FROM db_slider WHERE sld_id = ?)
		";
		
		$runSet    = db_run($setPosNextSlideSQL, [$sldID], "i");
		

		// xóa slide cần xóa
		$removeSQL = "DELETE FROM db_slider WHERE sld_id = ?";
		
		$runRemove = db_run($removeSQL, [$sldID], 'i');

		$status = $runSet && $runRemove ? 5 : 6;

		echo $status;
	}
