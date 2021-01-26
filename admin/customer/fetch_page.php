<?php 
	require_once '../../common.php';
	if(isset($_POST['action']) && $_POST['action'] == "fetch") {

		$html = '';

		//============================ LẤY DANH SÁCH KHÁCH HÀNG =========================
		$q = data_input(input_post('q'));
		$key = "%" . $q . "%";

		// nếu từ khóa tìm kiếm không rỗng -> lấy danh sách khách hàng theo tìm kiếm
		if($q != "") {
			$searchSQL = "
			SELECT * FROM db_customer WHERE 
				cus_id LIKE(?) OR
				cus_name LIKE(?) OR 
				cus_address LIKE(?) OR
				cus_phone LIKE(?) OR
				cus_email LIKE(?) OR
				cus_dob LIKE(?) OR
				cus_phone LIKE(?)
			";

			$param = [$key, $key, $key, $key, $key, $key, $key];
			$listCustomer = db_get($searchSQL, 1, $param, "sssssss");
		} else {

			// từ khóa tìm kiếm rỗng -> lấy hết danh sách khách hàng
			$listCustomer = getListUser(0);
		}
		

		// ============= CHIA TRANG ===================================================
		
		// tổng số khách hàng
		$totalCustomer = $listCustomer->num_rows;

		// số khách hàng trên một trang
		$customerPerPage = 5;

		// trang hiện tại
		$currentPage = isset($_POST['currentPage']) ? $_POST['currentPage'] : 1;

		// link trang hiện tại(URI)
		$currentLink = create_link(base_url("admin/customer/index.php"), ["page"=>'{page}', 'q'=>$q]);

		// kết quả phân trang
		$page = paginate($currentLink, $totalCustomer, $currentPage, $customerPerPage);

		//===================== DANH SÁCH KHÁCH HÀNG SAU KHI CHIA TRANG ============================
		
		if($q != "") {
			$searchResultSQL = $searchSQL . " LIMIT ? OFFSET ?";
			$param = [$key, $key, $key, $key, $key, $key, $key, $page['limit'], $page['offset']];

			// danh sách khách hàng sau khi tìm kiếm và chia trang chia trang
			$listCustomerPaginate = db_get($searchResultSQL, 1, $param, "sssssssii");
		} else {

			// danh sách khách hàng sau khi chia trang
			$listCustomerPaginate = getListUser(0, $page['limit'], $page['offset']);
		}

		// tổng số bản ghi sau khi phân trang
		$totalCustomerPaginate = $listCustomerPaginate->num_rows;

		// số thứ tự
		$stt = 1 + (int)$page['offset'];

		$html .= ' 
			<table class="table table-hover table-bordered" style="font-size: 13px;">
				<tr>
					<th>STT</th>
					<th>Mã</th>
					<th>Tên</th>
					<th>Ngày sinh</th>
					<th>Giới tính</th>
					<th>Email</th>
					<th>Điện thoại</th>
					<th>Ảnh</th>
					<th>Địa chỉ</th>
					<th>Trạng thái</th>
					<th>Sửa</th>
					<th>Xóa</th>
				</tr>
		';

		// in các đơn hàng
		
		if ($totalCustomerPaginate > 0) {
			foreach ($listCustomerPaginate as $key => $customer) {
				$checked = $customer['cus_active'] ? "checked" : "";
				$gender = $customer['cus_gender'] ? "Nam" : "Nữ";

				$html .= '   
				<tr>
					<td>' . $stt++ .'</td>
					<td>' . $customer['cus_id'] . '</td>
					<td>' . $customer['cus_name'] . '</td>
					<td>' . $customer['cus_dob'] . '</td>
					<td>
						' . $gender . '
					</td>
					<td>' . $customer['cus_email'] . '</td>
					<td>' . $customer['cus_phone'] . '</td>
					<td>
						<img src="../../image/' . $customer['cus_avatar'] . '" width="30px" height="30px">
					</td>
					<td>' . $customer['cus_address'] . '</td>
					<td>
						<div class="custom-control custom-switch">
							<input 
								type="checkbox" 
								id="switch_active_' . $customer['cus_id'] . '" 
								data-customer-id="' . $customer['cus_id'] . '"
								class="btn_switch_active custom-control-input" 
								value="' . $customer['cus_active'] . '"
								' . $checked . '
							>
							<label for="switch_active_' . $customer['cus_id'] . '" class="custom-control-label"></label>
						</div>
					</td>
					<td>
						<a
							href="
							' . 
								create_link(base_url('admin/customer/update.php'), [
									"cusid"=>$customer['cus_id']
								])
							. '
							"
							class="btn_edit_customer btn btn-success"
							data-customer-id="' . $customer['cus_id'] . '">
							<i class="fas fa-edit"></i>
						</a>
					</td>
					<td>
						<a 
							class="btn_remove_customer btn btn-danger"
							data-customer-id="' . $customer['cus_id'] . '">
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