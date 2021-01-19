<?php 
	require_once '../../common.php';
	if(isset($_POST['action']) && $_POST['action'] == "fetch") {
		$html = '';
		$html .= '    
		<div>
			<h5>KHÁCH HÀNG</h5>
			<p class="mb-4">Khách hàng là nơi bạn kiểm tra và chỉnh sửa thông tin khách hàng</p>
			<hr>
		</div>
		';

		$listCustomer = getListUser(0);

		// chia trang
		$totalCustomer = $listCustomer->num_rows;
		$customerPerPage = 5;
		$currentPage = isset($_POST['currentPage']) ? $_POST['currentPage'] : 1;
		$currentLink = create_link(base_url("admin/customer/index.php"), ["page"=>'{page}']);
		$page = paginate($currentLink, $totalCustomer, $currentPage, $customerPerPage);

		//đơn hàng sau khi chia trang
		$listCustomerPaginate = getListUser(0, $page['limit'], $page['offset']);
		$totalCustomerPaginate = $listCustomerPaginate->num_rows;

		// số thứ tự
		$stt = 1 + (int)$page['offset'];


		$html .= ' 
		<div>
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
									"cusid"=>$customer['cus_id'],
									"from"=>$_POST['prevPage']
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