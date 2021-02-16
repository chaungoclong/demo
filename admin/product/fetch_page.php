<?php 
	require_once '../../common.php';
	if(!empty($_POST['action']) && $_POST['action'] == "fetch") {
		$getProductSQL = "
			SELECT db_product.*, db_brand.bra_name, db_category.cat_name FROM db_product 
			JOIN db_category ON db_product.cat_id = db_category.cat_id
			JOIN db_brand 	 ON db_product.bra_id = db_brand.bra_id
			WHERE 1
		";
		$param  = [];
		$format = "";
		
		// tìm kiếm theo tên, giá, mã, số lượng
		$q = !empty($_POST['q']) ? $_POST['q'] : "%%";
		$getProductSQL .= " AND CONCAT(db_product.pro_name, db_product.pro_price, db_product.pro_id, db_product.pro_qty) LIKE(?)";
		$param[] = $q;
		$format .= "s";

		// tìm kiếm theo trạng thái
		$status = !empty($_POST['status']) ? $_POST['status'] : "all";
		switch ($status) {
			case 'all':
				break;
			case 'on':
				$getProductSQL .= " AND db_product.pro_active = 1";
				break;
			case 'off':
				$getProductSQL .= " AND db_product.pro_active = 0";
				break;
			default:
				break;
		}

		// tìm kiếm theo hãng
		$brand = !empty($_POST['brand']) ? $_POST['brand'] : "all";
		if($brand != "all") {
			$getProductSQL .= " AND db_brand.bra_id = ?";
			$param[] = $brand;
			$format .= "i";
		}

		// tìm kiếm theo danh mục
		$category = !empty($_POST['category']) ? $_POST['category'] : "all";
		if($category != "all") {
			$getProductSQL .= " AND db_category.cat_id = ?";
			$param[] = $category;
			$format .= "i";
		}

		// sắp xếp
		$sort = !empty($_POST['sort']) ? (int)$_POST['sort'] : 3;
		switch ($sort) {
			case 1:
				$getProductSQL .= " ORDER BY db_product.pro_name ASC";
				break;
			case 2:
				$getProductSQL .= " ORDER BY db_product.pro_name DESC";
				break;
			case 3:
				$getProductSQL .= " ORDER BY db_product.pro_create_at DESC";
				break;
			case 4:
				$getProductSQL .= " ORDER BY db_product.pro_create_at ASC";
				break;
			case 5:
				$getProductSQL .= " ORDER BY db_product.pro_price ASC";
				break;
			case 6:
				$getProductSQL .= " ORDER BY db_product.pro_price DESC";
				break;
			case 7:
				$getProductSQL .= " ORDER BY db_product.pro_qty ASC";
				break;
			case 8:
				$getProductSQL .= " ORDER BY db_product.pro_qty DESC";
				break;
			default:
				$getProductSQL .= " ORDER BY db_product.pro_create_at DESC";
				break;
		}
		// echo $getProductSQL;
		// vd($param);
		// echo $format;
		$listProduct = db_get($getProductSQL, 0, $param, $format);
		$totalProduct = count($listProduct);

		// chia trang
		$proPerPage = 5;
		$totalPage = ceil($totalProduct / $proPerPage);
		$currentPage = !empty($_POST['currentPage']) ? (int)$_POST['currentPage'] : 1;
		$currentPage = $currentPage > $totalPage ? $totalPage : $currentPage;
		$offset = ($currentPage - 1) * $proPerPage;

		$getProductSQL .= " LIMIT ? OFFSET ?";
		$param = [...$param, $proPerPage, $offset];
		$format .= "ii";
		$listProduct = db_get($getProductSQL, 0, $param, $format);
		
		$products = "";
		$stt = 1;
		if ($totalProduct > 0) {
			foreach ($listProduct as $key => $product) {
				$checked = $product['pro_active'] ? "checked" : "";
				$products .= '   
				<tr>
					<!-- mã -->
					<td>' . $stt++ . '</td>

					<!-- mã -->
					<td>' . $product['pro_id'] . '</td>

					<!-- tên sản phẩm -->
					<td>' . $product['pro_name'] . '</td>

					<!-- ảnh  -->
					<td>
						<img src="../../image/' . $product['pro_img'] . '" width="30px" height="30px">
					</td>

					<!-- hãng -->
					<td>' . $product['bra_name'] . '</td>

					<!-- thể loại -->
					<td>
						' . $product['cat_name'] . '
					</td>

					<!-- giá -->
					<td>' . $product['pro_price'] . '</td>

					<!-- số lượng -->
					<td>' . $product['pro_qty'] . '</td>
					

					<!-- active -->
					<td>
						<div class="custom-control custom-switch">
							<input 
								type="checkbox" 
								id="switch_active_' . $product['pro_id'] . '" 
								data-pro-id="' . $product['pro_id'] . '"
								class="btn_switch_active custom-control-input" 
								value="' . $product['pro_active'] . '"
								' . $checked . '
							>
							<label for="switch_active_' . $product['pro_id'] . '" class="custom-control-label"></label>
						</div>
					</td>

					<!-- edit -->
					<td>
						<a
							href="
							' . 
								create_link(base_url('admin/product/update.php'), [
									"proid"=>$product['pro_id']
								])
							. '
							"
							class="btn_edit_pro btn btn-success"
							data-pro-id="' . $product['pro_id'] . '">
							<i class="fas fa-edit"></i>
						</a>
					</td>

					<!-- delete -->
					<td>
						<a 
							class="btn_delete_pro btn btn-danger"
							id="btn_delete_' . $product['pro_id'] . '" 
							data-pro-id="' . $product['pro_id'] . '">
							<i class="fas fa-trash-alt"></i>
						</a>
					</td>
				</tr>
				';
			}
		}

		$pagination = paginateAjax($totalPage, $currentPage);
		$output = ['products'=>$products, 'pagination'=>$pagination];
		echo json_encode($output);
				
	}