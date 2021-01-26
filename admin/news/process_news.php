<?php 
require_once '../../common.php';

/**
 * trường ảnh mô tả không bắt buộc-> nếu sai hoặc thiếu vẫn tải sản phẩm lên mà không tải những file bị sai
 */
if (!empty($_POST['action']) && $_POST['action'] == "add") {

	$status         = 5;
	
	//lấy dữ liệu gửi lên từ ajax
	$title   = input_post("title");

	$desc    = input_post("desc");

	$content = input_post("content");

	$auth    = data_input(input_post("auth"));

	$active  = data_input(input_post("active"));

	$active  = $active ? 1 : 0;


	// đường dẫn đến thư mục lưu ảnh
	$folder      = "../../image/";
	
	//  danh sách đuôi file hợp lệ
	$extension   = ['jpg', 'jpeg', 'png'];
	
	// lấy ảnh
	$imageFile   = !empty($_FILES['image']) ? $_FILES['image'] : null;
	

	//validate
	if(
		$title     === false ||
		$desc      === false ||
		$content   === false ||
		$auth      === false ||
		$imageFile === null
	) {
		$status = 1;
	} else {

		// thêm tin tức vào bảng tin tức
		$imageName = up_file($imageFile, $folder, $extension);
		if(!$imageName) {
			$imageName    = "";
		}

		$addNewsSQL = "  
		INSERT INTO db_news
		(news_img, news_title, news_desc, news_content, news_active, create_by)
		VALUES(?, ?, ?, ?, ?, ?)
		";

		$param = [$imageName, $title, $desc, $content, $active, $auth];

		$runAddNews = db_run($addNewsSQL, $param, "ssssis");

		if($runAddNews) {

			$status = 5;

		} else {
			// tải sản phẩm thất bại
			$status = 6; 
		}
	}
	
	// biến lưu kết quả trả về
	$res = [
		"status"     => $status
	];

	echo json_encode($res);
}


// SỬA BÀI VIẾT
if (!empty($_POST['action']) && $_POST['action'] == "edit") {

	$status         = 5;

	//lấy dữ liệu gửi lên từ ajax
	
	$newsID   = data_input(input_post('newsID'));

	$title    = input_post("title");

	$desc     = input_post("desc");

	$content  = input_post("content");

	$auth     = data_input(input_post("auth"));

	$oldImage = data_input(input_post('oldImage'));

	$active   = data_input(input_post("active"));

	$active   = $active ? 1 : 0;


	// đường dẫn đến thư mục lưu ảnh
	$folder      = "../../image/";
	
	//  danh sách đuôi file hợp lệ
	$extension   = ['jpg', 'jpeg', 'png'];
	
	// lấy ảnh
	$imageFile   = !empty($_FILES['image']) ? $_FILES['image'] : null;
	


	//validate
	if(
		$newsID    === false ||
		$oldImage  === false ||
		$title     === false ||
		$desc      === false ||
		$content   === false ||
		$auth      === false 
	) {
		$status = 1;
	} else {

		// tên file tải lên database
		$fileName = "";

		if($imageFile != null) {

			$fileName     = up_file($imageFile, $folder, $extension);
		} else {

			$fileName     = $oldImage;
		}

		
		$editNewsSQL = "
		UPDATE db_news 
		SET 
			news_img     = ?, 
			news_title   = ?, 
			news_desc    = ?, 
			news_content = ?, 
			news_active  = ?,
			create_by    = ?
		WHERE 
			news_id      = ?
		";

		$param = [$fileName, $title, $desc, $content, $active, $auth, $newsID];
		$runEdit = db_run($editNewsSQL, $param, "ssssisi");

		$status = $runEdit ? 5 : 6;
		
	}
	
	// biến lưu kết quả trả về
	$res = [
		"status"     => $status
	];

	echo json_encode($res);
}


// THAY ĐỔI TRẠNG THÁI
if (!empty($_POST['action']) && $_POST['action'] == "switch_active") {
	$status = 5;

		// mã bài viết
	$newsID = data_input(input_post("newsID"));

		// trạng thái muốn cập nhật
	$newActive = $_POST['newActive'] ?? null;

		// validate
	if($newsID === false || $newActive === null) {
		$status = 1;
	} else {

		$switchActiveSQL    = "UPDATE db_news SET news_active = ? WHERE news_id = ?";

		$runSwitchActiveSQL = db_run($switchActiveSQL, [$newActive, $newsID], 'ii');

		if($runSwitchActiveSQL) {
			$status = 5;
		} else {
			$status = 6;
		}
	}

	$res = [
		"status"   =>$status
	];

	echo json_encode($res);
}



// xóa bài viết
if (!empty($_POST['action']) && $_POST['action'] == "remove") {
	$status = 5;

	// mã bài viết
	$newsID = data_input(input_post("newsID"));

	if($newsID === false) {
		$status = 1;
	} else {
		$removeNewsSQL = "DELETE FROM db_news WHERE news_id = ?";
		$runRemoveNews = db_run($removeNewsSQL, [$newsID], "i");
		$status = ($runRemoveNews) ? 5 : 6;
	}

	echo $status;
}
