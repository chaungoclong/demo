<?php 
require_once '../../common.php';


if (!empty($_POST['action']) && $_POST['action'] == "add") {

	$status = "fail";
	$title = $desc = $content = $auth = $active = $file = $fileName = "";
	$folder      = "../../image/";
	$extension   = ['jpg', 'jpeg', 'png'];
	$error = [];
	$ok = true;

	// tiêu đề
	if(empty($_POST['title'])) {
		$ok= false;
		$error[] = "TIÊU ĐỀ: không được để trống";
	} else {
		$title = $_POST['title'];
	}

	// mô tả
	if(empty($_POST['desc'])) {
		$ok= false;
		$error[] = "MÔ TẢ: không được để trống";
	} else {
		$desc = $_POST['desc'];
	}

	// nội dung
	if(empty($_POST['content'])) {
		$ok= false;
		$error[] = "NỘI DUNG: không được để trống";
	} else {
		$content = $_POST['content'];
	}

	// tác giả
	if(empty($_POST['auth'])) {
		$ok= false;
		$error[] = "TÁC GIẢ: không được để trống";
	} else {
		$auth = name($_POST['auth']);

		if($auth === false) {
			$ok = false;
			$error[] = "TÁC GIẢ: sai định dạng";
		}
	}

	// ảnh
	if(empty($_FILES['image'])) {
		$ok = false;
		$error[] = "ẢNH: không được để trống";
	} else {
		$file = $_FILES['image'];
		$fileName = up_file($file, $folder, $extension);

		if(!$fileName) {
			$ok = false;
			$error[] = "ẢNH: tải lêm không thành công";
		}
	}

	// trạng thái
	$active = !empty($_POST['active']) ? 1 : 0;

	if($ok) {
		$addNewsSQL = "  
		INSERT INTO db_news
		(news_img, news_title, news_desc, news_content, news_active, create_by)
		VALUES(?, ?, ?, ?, ?, ?)
		";
		$param = [$fileName, $title, $desc, $content, $active, $auth];
		$runAddNews = db_run($addNewsSQL, $param, "ssssis");

		$status = $runAddNews ? "success" : 'fail';
	}

	$output = ['status'=>$status, 'error'=>$error];
	echo json_encode($output);
}


// SỬA BÀI VIẾT
if (!empty($_POST['action']) && $_POST['action'] == "edit") {
	$status = "fail";
	$newsID = $title = $desc = $content = $auth = $active = $file = $fileName = $oldImage = "";
	$folder      = "../../image/";
	$extension   = ['jpg', 'jpeg', 'png'];
	$error = [];
	$ok = true;

	// id
	if(empty($_POST['newsID'])) {
		$ok = false;
	} else {
		$newsID = data_input($_POST['newsID']);
	}

	// tiêu đề
	if(empty($_POST['title'])) {
		$ok= false;
		$error[] = "TIÊU ĐỀ: không được để trống";
	} else {
		$title = $_POST['title'];
	}

	// mô tả
	if(empty($_POST['desc'])) {
		$ok= false;
		$error[] = "MÔ TẢ: không được để trống";
	} else {
		$desc = $_POST['desc'];
	}

	// nội dung
	if(empty($_POST['content'])) {
		$ok= false;
		$error[] = "NỘI DUNG: không được để trống";
	} else {
		$content = $_POST['content'];
	}

	// tác giả
	if(empty($_POST['auth'])) {
		$ok= false;
		$error[] = "TÁC GIẢ: không được để trống";
	} else {
		$auth = name($_POST['auth']);

		if($auth === false) {
			$ok = false;
			$error[] = "TÁC GIẢ: sai định dạng";
		}
	}

	// ảnh cũ
	if(!empty($_POST['oldImage'])) {
		$oldImage = data_input($_POST['oldImage']);
	}

	// ảnh
	if(!empty($_FILES['image'])) {
		$file = $_FILES['image'];
		$fileName = up_file($file, $folder, $extension);

		if(!$fileName) {
			$ok = false;
			$error[] = "ẢNH: tải lêm không thành công";
		}
	} else {
		$fileName = $oldImage;
	}

	// trạng thái
	$active = !empty($_POST['active']) ? 1 : 0;

	if($ok) {
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
		
		$status = $runEdit ? "success" : 'fail';
	}

	$output = ['status'=>$status, 'error'=>$error];
	echo json_encode($output);
}


// THAY ĐỔI TRẠNG THÁI
if (!empty($_POST['action']) && $_POST['action'] == "switch_active") {
	$ok           = true;
	$newsID       = data_input($_POST['newsID']);
	$active       = data_input($_POST['active']);
	$switchSQL    = "UPDATE db_news SET news_active = ? WHERE news_id = ?";
	$runSwitchSQL = db_run($switchSQL, [$active, $newsID], 'ii');
	$ok = $runSwitchSQL ? true : false;
	
	$output = ["ok" =>$ok];
	echo json_encode($output);
}



// xóa bài viết
if (!empty($_POST['action']) && $_POST['action'] == "delete") {
	$status       = 'error';
	$newsID       = data_input($_POST['newsID']);
	$deleteSQL    = "DELETE FROM db_news WHERE news_id = ?";
	$runDeleteSQL = db_run($deleteSQL, [$newsID], 'i');
	$status       = $runDeleteSQL ? 'success' : 'error';
	
	$output = ["status" =>$status];
	echo json_encode($output);
}
