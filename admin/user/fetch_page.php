<?php 
	require_once '../../common.php';
	if(isset($_POST['action']) && $_POST['action'] == "fetch") {
		$html = '';
		$html .= '    
		<div>
			<h5>NHÂN VIÊN</h5>
			<p class="mb-4">Nhân viên là nơi bạn kiểm tra và chỉnh sửa thông tin nhân viên</p>
			<hr>
		</div>
		<div class="row m-0 mb-3">
			<div class="col-12 p-0 d-flex justify-content-between">
				<a href="' . base_url("admin/user/add.php") . '" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Thêm nhân viên">
					<i class="fas fa-user-plus"></i>
				</a>
			</div>
		</div>
		';

		$listUser = getListUser(1);
		// vd($listUser->fetch_all(MYSQLI_ASSOC));

		//  exit;

		// chia trang
		$totalUser = $listUser->num_rows;
		$userPerPage = 5;
		$currentPage = isset($_POST['currentPage']) ? $_POST['currentPage'] : 1;
		$currentLink = create_link(base_url("admin/user/index.php"), ["page"=>'{page}']);
		$page = paginate($currentLink, $totalUser, $currentPage, $userPerPage);

		//đơn hàng sau khi chia trang
		$listUserPaginate = getListUser(1, $page['limit'], $page['offset']);
		$totalUserPaginate = $listUserPaginate->num_rows;

		// số thứ tự
		$stt = 1 + (int)$page['offset'];


		$html .= ' 
		<div>
			<table class="table table-hover table-bordered" style="font-size: 13px;">
				<tr>
					<th>STT</th>
					<th>Mã</th>
					<th>Username</th>
					<th>Tên</th>
					<th>Ngày sinh</th>
					<th>Giới tính</th>
					<th>Email</th>
					<th>Điện thoại</th>
					<th>Ảnh</th>
					<th>Trạng thái</th>
					<th>Sửa</th>
					<th>Xóa</th>
				</tr>
		';

		// in các đơn hàng
		
		if ($totalUserPaginate > 0) {
			foreach ($listUserPaginate as $key => $user) {
				$checked = $user['ad_active'] ? "checked" : "";
				$gender = $user['ad_gender'] ? "Nam" : "Nữ";

				$html .= '   
				<tr>
					<td>' . $stt++ .'</td>
					<td>' . $user['ad_id'] . '</td>
					<td>' . $user['ad_uname'] . '</td>
					<td>' . $user['ad_name'] . '</td>
					<td>' . $user['ad_dob'] . '</td>
					<td>
						' . $gender . '
					</td>
					<td>' . $user['ad_email'] . '</td>
					<td>' . $user['ad_phone'] . '</td>
					<td>
						<img src="../../image/' . $user['ad_avatar'] . '" width="30px" height="30px">
					</td>
					<td>
						<div class="custom-control custom-switch">
							<input 
								type="checkbox" 
								id="switch_active_' . $user['ad_id'] . '" 
								data-user-id="' . $user['ad_id'] . '"
								class="btn_switch_active custom-control-input" 
								value="' . $user['ad_active'] . '"
								' . $checked . '
							>
							<label for="switch_active_' . $user['ad_id'] . '" class="custom-control-label"></label>
						</div>
					</td>
					<td>
						<a
							href="
							' . 
								create_link(base_url('admin/user/update.php'), [
									"userid"=>$user['ad_id'],
									"from"=>$_POST['prevPage']
								])
							. '
							"
							class="btn_edit_user btn btn-success"
							data-user-id="' . $user['ad_id'] . '">
							<i class="fas fa-edit"></i>
						</a>
					</td>
					<td>
						<a 
							class="btn_remove_user btn btn-danger"
							data-user-id="' . $user['ad_id'] . '">
							<i class="fas fa-trash-alt"></i>
						</a>
					</td>
				</tr>
				';
			}
		}
		$html .= '   
			</table>
		';

		$html .= $page['html'];

		echo $html;
				
	}