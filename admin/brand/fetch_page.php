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

		$q = data_input(input_get('q'));
			$key = "%" . $q . "%";

			if($q != "") {
				$searchSQL = "
				SELECT * FROM db_brand
				WHERE 
					bra_name LIKE(?) 
				";

				$param = [$key];
				$listBrand = db_get($searchSQL, 1, $param, "s");
			} else {

				$listBrand = db_fetch_table("db_brand", 1);
			}
			

			// chia trang
			$totalBrand = $listBrand->num_rows;
			$brandPerPage = 5;
			$currentPage = isset($_POST['currentPage']) ? $_POST['currentPage'] : 1;
			$currentLink = create_link(base_url("admin/brand/index.php"), ["page"=>'{page}', 'q'=>$q]);
			$page = paginate($currentLink, $totalBrand, $currentPage, $brandPerPage);

			// danh sách nhân viên sau khi chia trang
			if($q != "") {
				$searchResultSQL = $searchSQL . " LIMIT ? OFFSET ?";
				$param = [$key, $page['limit'], $page['offset']];

				// danh sách người dùng sau khi tìm kiếm và chia trang chia trang
				$listBrandPaginate = db_get($searchResultSQL, 1, $param, "sii");
			} else {

				$listBrandPaginate = db_fetch_table("db_brand", 1, $page['limit'], $page['offset']);
			}

			
			$totalBrandPaginate = $listBrandPaginate->num_rows;

			// số thứ tự
			$stt = 1 + (int)$page['offset'];

		$html .= ' 
			<table class="table table-hover table-bordered" style="font-size: 13px;">
				<tr>
					<th>STT</th>
					<th>Mã</th>
					<th>Tên</th>
					<th>Ảnh</th>
					<th>Trạng thái</th>
					<th>Sửa</th>
					<th>Xóa</th>
				</tr>
		';

		// in các đơn hàng
		
		if ($totalBrandPaginate > 0) {
			foreach ($listBrandPaginate as $key => $brand) {
				$checked = $brand['bra_active'] ? "checked" : "";
				$html .= '   
				<tr>
					<!--stt -->
					<td>' . $stt++ . '</td>

					<!-- mã -->
					<td>' . $brand['bra_id'] . '</td>

					<!-- tên danh mục -->
					<td>' . $brand['bra_name'] . '</td>

					<!-- ảnh  -->
					<td>
						<img src="../../image/' . $brand['bra_logo'] . '" width="30px" height="30px">
					</td>

					<!-- active -->
					<td>
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

					<!-- edit -->
					<td>
						<a
							href="
							' . 
								create_link(base_url('admin/brand/update.php'), [
									"braid"=>$brand['bra_id'],
									"from"=>getCurrentURL()
								])
							. '
							"
							class="btn_edit_bra btn btn-success"
							data-bra-id="' . $brand['bra_id'] . '">
							<i class="fas fa-edit"></i>
						</a>
					</td>

					<!-- remove -->
					<td>
						<a 
							class="btn_remove_bra btn btn-danger"
							data-bra-id="' . $brand['bra_id'] . '">
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