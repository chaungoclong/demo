<?php 
	require_once '../../common.php';
	if(isset($_POST['action']) && $_POST['action'] == "fetch") {

		$html = '';

		// LẤY TOÀN BỘ DỮ LIỆU TRƯỚC KHI PHÂN TRANG(nếu có search thì lấy toàn bộ dữ liệu với seach)
		$q = data_input(input_post("q"));
		$keyWord = "%" . $q . "%";

		if($q != "") {
			
			$searchResultSQL = "SELECT * FROM db_admin WHERE 
			(ad_id LIKE(?) OR
			ad_name LIKE(?) OR 
			ad_uname LIKE(?) OR
			ad_phone LIKE(?) OR
			ad_email LIKE(?) OR
			ad_dob LIKE(?) OR
			ad_phone LIKE(?))
			AND ad_role > 1
			";

			$param = [$keyWord, $keyWord, $keyWord, $keyWord, $keyWord, $keyWord, $keyWord];
			$listUser = db_get($searchResultSQL, 1, $param, "sssssss");
		} else {
			$listUser = getListUser(1);
		}

		// chia trang
		$totalUser   = $listUser->num_rows;
		$userPerPage = 2;
		$currentPage = isset($_POST['currentPage']) ? $_POST['currentPage'] : 1;

		$currentLink = create_link(base_url("admin/user/index.php"), ["page"=>'{page}', "q"=>$q]);
		$page        = paginate($currentLink, $totalUser, $currentPage, $userPerPage);


		//danh sách người dùng sau khi chia trang
		if($q != "") {

			// câu sql lấy danh sách người dùng sau khi tìm kiếm và chia trang
			$searchResultPaginateSQL = $searchResultSQL . " LIMIT ? OFFSET ?";

			$param = [$keyWord, $keyWord, $keyWord, $keyWord, $keyWord, $keyWord, $keyWord, $page['limit'], $page['offset']];

			// danh sách người dùng sau khi tìm kiếm và chia trang chia trang
			$listUserPaginate = db_get($searchResultPaginateSQL, 1, $param, "sssssssii");
		} else {

			// danh sách người dùng sau khi chia trang không có tìm kiếm
			$listUserPaginate = getListUser(1, $page['limit'], $page['offset']);
		}
		

		// tổng số bản ghi
		$totalUserPaginate = $listUserPaginate->num_rows;

		// số thứ tự
		$stt = 1 + (int)$page['offset'];


		$html .= ' 
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