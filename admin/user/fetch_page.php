<?php 
	require_once '../../common.php';
	if(!empty($_POST['action']) && $_POST['action'] == "fetch") {
		$getUserSQL = "SELECT * FROM db_admin WHERE 1";
		$param = [];
		$format = "";

		// tìm kiếm theo tên, mã nhân viên
		$q = !empty($_POST['q']) ? $_POST['q'] : "%%";
		$getUserSQL .= " AND CONCAT(ad_name, ad_uname, ad_id, ad_dob, ad_email, ad_phone) LIKE(?)";
		$param[] = $q;
		$format .= "s";

		// tìm kiếm theo trạng thái
		$status = !empty($_POST['status']) ? $_POST['status'] : "all";
		switch ($status) {
			case "all":
				break;
			case 'on':
				$getUserSQL .= " AND ad_active = 1";
				break;
			case 'off':
				$getUserSQL .= " AND ad_active = 0";
				break;
			default:
				break;
		}

		// tìm kiếm theo quyền
		$status = !empty($_POST['role']) ? $_POST['role'] : "all";
		switch ($status) {
			case "all":
				break;
			case 'sa':
				$getUserSQL .= " AND ad_role = 1";
				break;
			case 'a':
				$getUserSQL .= " AND ad_role = 2";
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
				$getUserSQL .= " AND ad_gender = 1";
				break;
			case 'female':
				$getUserSQL .= " AND ad_gender = 0";
				break;
			default:
				break;
		}

		// sắp xếp
		$sort = !empty($_POST['sort']) ? (int)$_POST['sort'] : 3;
		switch ($sort) {
			case 1:
				$getUserSQL .= " ORDER BY ad_uname ASC";
				break;
			case 2:
				$getUserSQL .= " ORDER BY ad_uname DESC";
				break;
			case 3: 
				$getUserSQL .= " ORDER BY ad_create_at DESC";
				break;
			case 4:
				$getUserSQL .= " ORDER BY ad_create_at ASC";
				break;
			default:
				$getUserSQL .= " ORDER BY ad_create_at ASC";
				break;
		}

		$listUser  = db_get($getUserSQL, 0, $param, $format);
		$totalUser = count($listUser);
		
		// chia trang
		$userPerPage  = !empty($_POST['numRows']) ? (int)$_POST['numRows'] : 5;
		$totalPage   = ceil($totalUser / $userPerPage);
		$currentPage = !empty($_POST['currentPage']) ? (int)$_POST['currentPage'] : 1;
		$currentPage = $currentPage > $totalPage ? $totalPage : $currentPage;
		$offset      = ($currentPage - 1) * $userPerPage;

		$getUserSQL .= " LIMIT ? OFFSET ?";
		$param          = [...$param, $userPerPage, $offset];
		$format         .= "ii";
		$listUser   = db_get($getUserSQL, 0, $param, $format);
		
		$users     = '';
		$stt            = 1 + $offset;

		if ($totalUser > 0) {
			foreach ($listUser as $key => $user) {
				// quyền
				$role = '';
				$canNotEdit = "";
				if($user['ad_role'] == 1) {
					$role .= '  
					<span class="badge badge-danger">SuperAdmin</span>
					';
					$canNotEdit = "disabled";
				} else {
					$role .= '  
					<span class="badge badge-primary">Admin</span>
					';
				}


				// trạng thái
				$checked = $user['ad_active'] ? "checked" : "";

				// giới tính
				$gender = $user['ad_gender'] ? "Nam" : "Nữ";

				$users .= '   
				<tr>
					<!-- mã -->
					<td class="align-middle">' . $user['ad_id'] . '</td>

					<!-- username nhân viên -->
					<td class="align-middle">
						<img src="../../image/' . $user['ad_avatar'] . '" width="50px" style="width: 50px; height: 50px;" class="card-img">
						<h6 class="mt-2">' . $user['ad_uname'] . '</h6>
					</td>

					<!-- tên -->
					<td class="align-middle">' . $user['ad_name'] . '</td>

					<!-- ngày sinh  -->
					<td class="align-middle">' . strToTimeFormat($user['ad_dob'], "d-m-Y") . '</td>

					<!-- giới tính  -->
					<td class="align-middle">' . $gender . '</td>

					<!-- email  -->
					<td class="align-middle">' . $user['ad_email'] . '</td>

					<!-- điện thoại  -->
					<td class="align-middle">' . $user['ad_phone'] . '</td>

					<!-- quyền  -->
					<td class="align-middle">' . $role . '</td>

					<!-- active -->
					<td class="align-middle">
						<div class="custom-control custom-switch">
							<input 
								'. $canNotEdit .'
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

					<!-- action -->
					<td class="align-middle" width="115px">
						<a
							href="
							' . 
								create_link(base_url('admin/user/update.php'), [
									"userid"=>$user['ad_id']
								])
							. '
							"
							class="btn_edit_user btn btn-success '. $canNotEdit .'"
							data-user-id="' . $user['ad_id'] . '">
							<i class="fas fa-edit"></i>
						</a>

						<a 
							class="btn_delete_user btn btn-danger '. $canNotEdit .'"
							id="btn_delete_' . $user['ad_id'] . '"
							data-user-id="' . $user['ad_id'] . '">
							<i class="fas fa-trash-alt"></i>
						</a>
					</td>
				</tr>
				';
			}
		}

		$pagination = paginateAjax($totalPage, $currentPage);
		$output = ['users'=>$users, 'pagination'=>$pagination];
		echo json_encode($output);
	}
