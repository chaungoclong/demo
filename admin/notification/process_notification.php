<?php 
	require_once '../../common.php';
	if(!empty($_POST['action']) && $_POST['action'] == "read") {
		$id = $_POST['id'];
		$read = db_run("UPDATE db_notify_admin SET status = 1 WHERE id = ?", [$id], "i");
		$status = $read ? 1 : 0;
		echo json_encode(['status'=>$status]);
	}
