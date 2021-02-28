<?php 
	require_once '../../common.php';
	if(!empty($_POST['action']) && $_POST['action'] == 'fetch') {
		//xoá các thông báo cũ
		$deleteSQL = "DELETE FROM db_notify_admin WHERE DATE(create_at) < DATE(NOW() - INTERVAL 7 DAY)";
		db_run($deleteSQL);

		$unRead = s_cell("SELECT COUNT(*) FROM db_notify_admin WHERE status = 0");

		$html = "";
		$listNotify = db_get('SELECT * FROM db_notify_admin ORDER BY create_at DESC');
		foreach ($listNotify as $key => $notify) {
			$bg = $notify['status'] ? "style='background: #fff;'" : "style='background: #a8d0e6;'";
			$bgBell = $notify['status'] ? " text-secondary" : " text-danger";
			$html .= '  
				<div class="notice_item px-4 dropdown-item" data-notify-id="'.$notify['id'].'"'.$bg.'>
					<a href="'.$notify['url'].'" class="d-flex align-items-center">
						<div class="notice_icon">
							<i class="fas fa-bell fa-lg'.$bgBell.'"></i>
						</div>
						<div class="notice_content">
							<p class="m-0 w-100"><strong>'.$notify['message'].'</strong></p>
							<span class="text-secondary">'.$notify['create_at'].'</span>
						</div>
					</a>
				</div>
			';
		}

		$output = ['unread' => $unRead, 'html' => $html];
		echo json_encode($output);
	}
?>