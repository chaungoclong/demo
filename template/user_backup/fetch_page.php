<?php 

	/**
	 * 	==============================TRANG LÀM MỚI====================================
	 * 	Mô tả: làm mới nội dung bảng ở trang index sau khi có 1 hàng thay đổi(cập nhật, xóa)
	 * 	Hoạt động:
	 * 	 - nhận dữ liệu gửi sang từ ajax -> kiểm tra có action = "fetch"
	 * 	 - kiểm tra có biến yêu cầu tìm kiếm:
	 * 	 	+ rỗng => lấy hết danh sách kết quả có thể lấy
	 * 	 	+ không rỗng => lấy hết danh sách kết quả theo yêu cầu
	 * 	 - chia trang
	 * 	 	+ xác định trang hiện tại thông qua biến $_POST['currentPage'](để khi làm mới vẫn giữ được đúng trang 	ban đầu)
	 * 	 	+ tạo đường link của trang yêu cầu
	 * 	 	+ phân trang bằng hàm 
	 *   - lấy danh sách kết quả sau khi chia trang
	 *   	+ có tìm kiếm : lấy danh sách kết quả thỏa mãn yêu cầu tìm kiếm sau khi phân trang
	 *   	+ không có tìm kiếm: lấy danh sách kết quả sau khi phân trang
	 * 
	 */
	



	require_once '../../common.php';
	if(isset($_POST['action']) && $_POST['action'] == "fetch") {
		$html = '';

		//============================ LẤY DANH SÁCH NGƯỜI DÙNG =========================
		$q = data_input(input_post('q'));
		$key = "%" . $q . "%";

		// nếu từ khóa tìm kiếm không rỗng -> lấy danh sách người dùng theo tìm kiếm
		if($q != "") {
			$searchSQL = "
			SELECT * FROM db_admin WHERE 
			(
				ad_id LIKE(?) OR
				ad_name LIKE(?) OR 
				ad_uname LIKE(?) OR
				ad_phone LIKE(?) OR
				ad_email LIKE(?) OR
				ad_dob LIKE(?) OR
				ad_phone LIKE(?)
			)
			AND ad_role > 1
			";

			$param = [$key, $key, $key, $key, $key, $key, $key];
			$listUser = db_get($searchSQL, 1, $param, "sssssss");
		} else {

			// từ khóa tìm kiếm rỗng -> lấy hết danh sách người dùng
			$listUser = getListUser(1);
		}
		

		// ============= CHIA TRANG ===================================================
		
		// tổng số người dùng
		$totalUser = $listUser->num_rows;

		// số người trên một trang
		$userPerPage = 5;

		// trang hiện tại
		$currentPage = isset($_POST['currentPage']) ? $_POST['currentPage'] : 1;

		// link trang hiện tại(URI)
		$currentLink = create_link(base_url("admin/user/index.php"), ["page"=>'{page}', 'q'=>$q]);

		// kết quả phân trang
		$page = paginate($currentLink, $totalUser, $currentPage, $userPerPage);

		//===================== DANH SÁCH NGƯỜI DÙNG SAU KHI CHIA TRANG ============================
		
		if($q != "") {
			$searchResultSQL = $searchSQL . " LIMIT ? OFFSET ?";
			$param = [$key, $key, $key, $key, $key, $key, $key, $page['limit'], $page['offset']];

			// danh sách người dùng sau khi tìm kiếm và chia trang chia trang
			$listUserPaginate = db_get($searchResultSQL, 1, $param, "sssssssii");
		} else {

			// danh sách người dùng sau khi chia trang
			$listUserPaginate = getListUser(1, $page['limit'], $page['offset']);
		}

		// tổng số bản ghi sau khi phân trang
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
									"userid"=>$user['ad_id']
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