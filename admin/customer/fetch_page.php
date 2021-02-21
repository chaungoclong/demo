<?php 
	require_once '../../common.php';
	if(!empty($_POST['action']) && $_POST['action'] == "fetch") {
		$getCustomerSQL = "SELECT * FROM db_customer WHERE 1";
		$param = [];
		$format = "";

		// tìm kiếm theo tên, mã khách hàng
		$q = !empty($_POST['q']) ? $_POST['q'] : "%%";
		$getCustomerSQL .= " AND CONCAT(cus_name, cus_id, cus_dob, cus_address, cus_email, cus_phone) LIKE(?)";
		$param[] = $q;
		$format .= "s";

		// tìm kiếm theo trạng thái
		$status = !empty($_POST['status']) ? $_POST['status'] : "all";
		switch ($status) {
			case "all":
				break;
			case 'on':
				$getCustomerSQL .= " AND cus_active = 1";
				break;
			case 'off':
				$getCustomerSQL .= " AND cus_active = 0";
				break;
			default:
				break;
		}

		// tìm kiếm theo giới tính
		$gender = !empty($_POST['gender']) ? $_POST['gender'] : "all";
		switch ($gender) {
			case 'all':
				break;
			case 'male':
				$getCustomerSQL .= " AND cus_gender = 1";
				break;
			case 'female':
				$getCustomerSQL .= " AND cus_gender = 0";
				break;
			default:
				break;
		}

		// sắp xếp
		$sort = !empty($_POST['sort']) ? (int)$_POST['sort'] : 3;
		switch ($sort) {
			case 1:
				$getCustomerSQL .= " ORDER BY cus_name ASC";
				break;
			case 2:
				$getCustomerSQL .= " ORDER BY cus_name DESC";
				break;
			case 3: 
				$getCustomerSQL .= " ORDER BY cus_create_at DESC";
				break;
			case 4:
				$getCustomerSQL .= " ORDER BY cus_create_at ASC";
				break;
			default:
				$getCustomerSQL .= " ORDER BY cus_create_at ASC";
				break;
		}

		$listCustomer  = db_get($getCustomerSQL, 0, $param, $format);
		$totalCustomer = count($listCustomer);
		
		// chia trang
		$cusPerPage  = !empty($_POST['numRows']) ? (int)$_POST['numRows'] : 5;
		$totalPage   = ceil($totalCustomer / $cusPerPage);
		$currentPage = !empty($_POST['currentPage']) ? (int)$_POST['currentPage'] : 1;
		$currentPage = $currentPage > $totalPage ? $totalPage : $currentPage;
		$offset      = ($currentPage - 1) * $cusPerPage;

		$getCustomerSQL .= " LIMIT ? OFFSET ?";
		$param          = [...$param, $cusPerPage, $offset];
		$format         .= "ii";
		$listCustomer   = db_get($getCustomerSQL, 0, $param, $format);
		
		$customers     = '';
		$stt            = 1 + $offset;

		if ($totalCustomer > 0) {
			foreach ($listCustomer as $key => $customer) {
				$checked = $customer['cus_active'] ? "checked" : "";
				$gender = $customer['cus_gender'] ? "Nam" : "Nữ";
				$customers .= '   
				<tr>
					<!-- mã -->
					<td class="align-middle">' . $customer['cus_id'] . '</td>

					<!-- tên khách hàng -->
					<td class="align-middle">
						<img src="../../image/' . $customer['cus_avatar'] . '" width="50px" style="width: 50px; height: 50px;" class="card-img">
						<h6 class="mt-2">' . $customer['cus_name'] . '</h6>
					</td>

					<!-- ngày sinh  -->
					<td class="align-middle">' . strToTimeFormat($customer['cus_dob'], "d-m-Y") . '</td>

					<!-- giới tính  -->
					<td class="align-middle">' . $gender . '</td>

					<!-- email  -->
					<td class="align-middle">' . $customer['cus_email'] . '</td>

					<!-- điện thoại  -->
					<td class="align-middle">' . $customer['cus_phone'] . '</td>

					<!-- giới tính  -->
					<td class="align-middle">' . $customer['cus_address'] . '</td>

					<!-- active -->
					<td>
						<div class="custom-control custom-switch">
							<input 
								type="checkbox" 
								id="switch_active_' . $customer['cus_id'] . '" 
								data-cus-id="' . $customer['cus_id'] . '"
								class="btn_switch_active custom-control-input" 
								value="' . $customer['cus_active'] . '"
								' . $checked . '
							>
							<label for="switch_active_' . $customer['cus_id'] . '" class="custom-control-label"></label>
						</div>
					</td>

					<!-- edit -->
					<td>
						<a
							href="
							' . 
								create_link(base_url('admin/customer/update.php'), [
									"cusid"=>$customer['cus_id']
								])
							. '
							"
							class="btn_edit_cus btn btn-success"
							data-cus-id="' . $customer['cus_id'] . '">
							<i class="fas fa-edit"></i>
						</a>

						<a 
							class="btn_delete_cus btn btn-danger"
							id="btn_delete_' . $customer['cus_id'] . '"
							data-cus-id="' . $customer['cus_id'] . '">
							<i class="fas fa-trash-alt"></i>
						</a>
					</td>
				</tr>
				';
			}
		}

		$pagination = paginateAjax($totalPage, $currentPage);
		$output = ['customers'=>$customers, 'pagination'=>$pagination];
		echo json_encode($output);
	}
