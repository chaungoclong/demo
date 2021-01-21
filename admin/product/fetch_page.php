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
			SELECT db_product.*, db_brand.bra_name, db_category.cat_name FROM db_product 
			JOIN db_category ON db_product.cat_id = db_category.cat_id
			JOIN db_brand 	 ON db_product.bra_id = db_brand.bra_id
			WHERE 
				pro_name LIKE(?)
			";

			$param = [$key];
			$listProduct = db_get($searchSQL, 1, $param, "s");
		} else {

			$listProduct = getListProduct();
		}

		// chia trang
		$totalProduct = $listProduct->num_rows;
		$productPerPage = 5;
		$currentPage = isset($_POST['currentPage']) ? $_POST['currentPage'] : 1;
		$currentLink = create_link(base_url("admin/product/index.php"), ["page"=>'{page}', 'q'=>$q]);
		$page = paginate($currentLink, $totalProduct, $currentPage, $productPerPage);

		// danh sách sản phẩm sau khi chia trang
		if($q != "") {
			$searchResultSQL = $searchSQL . " LIMIT ? OFFSET ?";
			$param = [$key, $page['limit'], $page['offset']];

			// danh sách sản phẩm sau khi tìm kiếm và chia trang chia trang
			$listProductPaginate = db_get($searchResultSQL, 1, $param, "sii");
		} else {

			$listProductPaginate = getListProduct($page['limit'], $page['offset']);
		}

		
		$totalProductPaginate = $listProductPaginate->num_rows;

		// số thứ tự
		$stt = 1 + (int)$page['offset'];

		$html .= ' 
			<table class="table table-hover table-bordered" style="font-size: 13px;">
				<tr>
					<th>STT</th>
					<th>Mã</th>
					<th>Tên</th>
					<th>Ảnh</th>
					<th>Hãng</th>
					<th>Thể loại</th>
					<th>Giá</th>
					<th>Số lượng</th>
					<th>Trạng thái</th>
					<th>Sửa</th>
					<th>Xóa</th>
				</tr>
		';

		// in các đơn hàng
		
		if ($totalProductPaginate > 0) {
			foreach ($listProductPaginate as $key => $product) {
				$checked = $product['pro_active'] ? "checked" : "";
				$html .= '   
				<tr>
					<!-- mã -->
					<td>' . $stt++ . '</td>

					<!-- mã -->
					<td>' . $product['pro_id'] . '</td>

					<!-- tên sản phẩm -->
					<td>' . $product['pro_name'] . '</td>

					<!-- ảnh  -->
					<td>
						<img src="' . $product['pro_img'] . '" width="30px" height="30px">
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
									"proid"=>$product['pro_id'],
									"from"=>$_POST['prevPage']
								])
							. '
							"
							class="btn_edit_pro btn btn-success"
							data-pro-id="' . $product['pro_id'] . '">
							<i class="fas fa-edit"></i>
						</a>
					</td>

					<!-- remove -->
					<td>
						<a 
							class="btn_remove_pro btn btn-danger"
							data-pro-id="' . $product['pro_id'] . '">
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