<?php 
	require_once '../../common.php';
	if(!empty($_POST['action']) && $_POST['action'] == "fetch") {
		$getBrandSQL = "SELECT * FROM db_brand WHERE 1";
		$param = [];
		$format = "";

		// tìm kiếm theo tên, mã hãng
		$q = !empty($_POST['q']) ? $_POST['q'] : "%%";
		$getBrandSQL .= " AND CONCAT(bra_name, bra_id) LIKE(?)";
		$param[] = $q;
		$format .= "s";

		// tìm kiếm theo trạng thái
		$status = !empty($_POST['status']) ? $_POST['status'] : "all";
		switch ($status) {
			case "all":
				break;
			case 'on':
				$getBrandSQL .= " AND bra_active = 1";
				break;
			case 'off':
				$getBrandSQL .= " AND bra_active = 0";
				break;
			default:
				break;
		}

		// sắp xếp
		$sort = !empty($_POST['sort']) ? (int)$_POST['sort'] : 1;
		switch ($sort) {
			case 1:
				$getBrandSQL .= " ORDER BY bra_name ASC";
				break;
			case 2:
				$getBrandSQL .= " ORDER BY bra_name DESC";
				break;
			case 3: 
				$getBrandSQL .= " ORDER BY bra_create_at DESC";
				break;
			case 4:
				$getBrandSQL .= " ORDER BY bra_create_at ASC";
				break;
			default:
				$getBrandSQL .= " ORDER BY bra_create_at ASC";
				break;
		}

		$listBrand  = db_get($getBrandSQL, 0, $param, $format);
		$totalBrand = count($listBrand);
		
		// chia trang
		$braPerPage  = !empty($_POST['numRows']) ? int($_POST['numRows']) : 5;
		$totalPage   = ceil($totalBrand / $braPerPage);
		$currentPage = !empty($_POST['currentPage']) ? (int)$_POST['currentPage'] : 1;
		$currentPage = $currentPage > $totalPage ? $totalPage : $currentPage;
		$offset      = ($currentPage - 1) * $braPerPage;

		$getBrandSQL .= " LIMIT ? OFFSET ?";
		$param          = [...$param, $braPerPage, $offset];
		$format         .= "ii";
		$listBrand   = db_get($getBrandSQL, 0, $param, $format);
		
		$brands     = '';
		$stt            = 1 + $offset;

		if ($totalBrand > 0) {
			foreach ($listBrand as $key => $brand) {
				$checked = $brand['bra_active'] ? "checked" : "";
				$brands .= '   
				<tr>

					<!-- mã -->
					<td class="align-middle">' . $brand['bra_id'] . '</td>

					<!-- tên hãng -->
					<td class="align-middle">' . $brand['bra_name'] . '</td>

					<!-- ảnh  -->
					<td class="align-middle">
						<img src="../../image/' . $brand['bra_logo'] . '" height="30px">
					</td>

					<!-- active -->
					<td class="align-middle">
						<div class="custom-control custom-switch">
							<input 
								type="checkbox" 
								id="switch_active_' . $brand['bra_id'] . '" 
								data-bra-id="' . $brand['bra_id'] . '"
								class="btn_switch_active custom-control-input" 
								value="' . $brand['bra_active'] . '"
								' . $checked . '
							>
							<label for="switch_active_' . $brand['bra_id'] . '" class="custom-control-label"></label>
						</div>
					</td>

					<!-- action -->
					<td class="align-middle">
						<a
							href="
							' . 
								create_link(base_url('admin/brand/update.php'), [
									"braid"=>$brand['bra_id']
								])
							. '
							"
							class="btn_edit_bra btn btn-success"
							data-bra-id="' . $brand['bra_id'] . '">
							<i class="fas fa-edit"></i>
						</a>

						<a 
							class="btn_delete_bra btn btn-danger"
							id="btn_delete_' . $brand['bra_id'] . '"
							data-bra-id="' . $brand['bra_id'] . '">
							<i class="fas fa-trash-alt"></i>
						</a>
					</td>

				</tr>
				';
			}
		}

		$pagination = paginateAjax($totalPage, $currentPage);
		$output = ['brands'=>$brands, 'pagination'=>$pagination];
		echo json_encode($output);
	}
