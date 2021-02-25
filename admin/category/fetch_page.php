<?php 
	require_once '../../common.php';
	if(!empty($_POST['action']) && $_POST['action'] == "fetch") {
		$getCategorySQL = "SELECT * FROM db_category WHERE 1";
		$param = [];
		$format = "";

		// tìm kiếm theo tên, mã danh mục
		$q = !empty($_POST['q']) ? $_POST['q'] : "%%";
		$getCategorySQL .= " AND CONCAT(cat_name, cat_id) LIKE(?)";
		$param[] = $q;
		$format .= "s";

		// tìm kiếm theo trạng thái
		$status = !empty($_POST['status']) ? $_POST['status'] : "all";
		switch ($status) {
			case "all":
				break;
			case 'on':
				$getCategorySQL .= " AND cat_active = 1";
				break;
			case 'off':
				$getCategorySQL .= " AND cat_active = 0";
				break;
			default:
				break;
		}

		// sắp xếp
		$sort = !empty($_POST['sort']) ? (int)$_POST['sort'] : 3;
		switch ($sort) {
			case 1:
				$getCategorySQL .= " ORDER BY cat_name ASC";
				break;
			case 2:
				$getCategorySQL .= " ORDER BY cat_name DESC";
				break;
			case 3: 
				$getCategorySQL .= " ORDER BY cat_create_at DESC";
				break;
			case 4:
				$getCategorySQL .= " ORDER BY cat_create_at ASC";
				break;
			default:
				$getCategorySQL .= " ORDER BY cat_create_at ASC";
				break;
		}

		$listCategory  = db_get($getCategorySQL, 0, $param, $format);
		$totalCategory = count($listCategory);
		
		// chia trang
		$catPerPage  = !empty($_POST['numRows']) ? int($_POST['numRows']) : 5;
		$totalPage   = ceil($totalCategory / $catPerPage);
		$currentPage = !empty($_POST['currentPage']) ? (int)$_POST['currentPage'] : 1;
		$currentPage = $currentPage > $totalPage ? $totalPage : $currentPage;
		$offset      = ($currentPage - 1) * $catPerPage;

		$getCategorySQL .= " LIMIT ? OFFSET ?";
		$param          = [...$param, $catPerPage, $offset];
		$format         .= "ii";
		$listCategory   = db_get($getCategorySQL, 0, $param, $format);
		
		$categories     = '';
		$stt            = 1 + $offset;

		if ($totalCategory > 0) {
			foreach ($listCategory as $key => $category) {
				$checked = $category['cat_active'] ? "checked" : "";
				$categories .= '   
				<tr>
					<!-- mã -->
					<td class="align-middle">' . $category['cat_id'] . '</td>

					<!-- tên danh mục -->
					<td class="align-middle">' . $category['cat_name'] . '</td>

					<!-- ảnh  -->
					<td class="align-middle">
						<img src="../../image/' . $category['cat_logo'] . '" width="30px" height="30px">
					</td>

					<!-- active -->
					<td class="align-middle">
						<div class="custom-control custom-switch">
							<input 
								type="checkbox" 
								id="switch_active_' . $category['cat_id'] . '" 
								data-cat-id="' . $category['cat_id'] . '"
								class="btn_switch_active custom-control-input" 
								value="' . $category['cat_active'] . '"
								' . $checked . '
							>
							<label for="switch_active_' . $category['cat_id'] . '" class="custom-control-label"></label>
						</div>
					</td>

					<!-- action -->
					<td class="align-middle">
						<a
							href="
							' . 
								create_link(base_url('admin/category/update.php'), [
									"catid"=>$category['cat_id']
								])
							. '
							"
							class="btn_edit_cat btn btn-success"
							data-cat-id="' . $category['cat_id'] . '">
							<i class="fas fa-edit"></i>
						</a>

						<a 
							class="btn_delete_cat btn btn-danger"
							id="btn_delete_' . $category['cat_id'] . '"
							data-cat-id="' . $category['cat_id'] . '">
							<i class="fas fa-trash-alt"></i>
						</a>
					</td>

				</tr>
				';
			}
		}

		$pagination = paginateAjax($totalPage, $currentPage);
		$output = ['categories'=>$categories, 'pagination'=>$pagination];
		echo json_encode($output);
	}
