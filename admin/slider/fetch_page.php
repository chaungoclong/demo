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
				SELECT db_slider.*, db_category.cat_name FROM `db_slider` 
				JOIN db_category ON db_slider.cat_id = db_category.cat_id
				WHERE 
					db_category.cat_name LIKE(?) 
				";

				$param = [$key];
				$listSlide = db_get($searchSQL, 1, $param, "s");
			} else {

				$listSlide = getListSlide();
			}
			

			// chia trang
			$totalSlide = $listSlide->num_rows;
			$slidePerPage = 5;
			$currentPage = isset($_POST['currentPage']) ? $_POST['currentPage'] : 1;
			$currentLink = create_link(base_url("admin/slider/index.php"), ["page"=>'{page}', 'q'=>$q]);
			$page = paginate($currentLink, $totalSlide, $currentPage, $slidePerPage);

			// danh sách slide sau khi chia trang
			if($q != "") {
				$searchResultSQL = $searchSQL . " LIMIT ? OFFSET ?";
				$param = [$key, $page['limit'], $page['offset']];

				// danh sách người dùng sau khi tìm kiếm và chia trang chia trang
				$listSlidePaginate = db_get($searchResultSQL, 1, $param, "sii");
			} else {

				$listSlidePaginate = getListSlide($page['limit'], $page['offset']);
			}

			
			$totalSlidePaginate = $listSlidePaginate->num_rows;

			// số thứ tự
			$stt = 1 + (int)$page['offset'];

		$html .= ' 
			<table class="table table-hover table-bordered" style="font-size: 13px;">
				<tr>
					<th>STT</th>
					<th>Mã</th>
					<th>Thể loại</th>
					<th width="45%">Preview</th>
					<th>vị trí</th>
					<th>Sửa</th>
					<th>Xóa</th>
				</tr>
		';

		// in các slide
		
		if ($totalSlidePaginate > 0) {
			foreach ($listSlidePaginate as $key => $slide) {

				$lastPos = lastPostion();

				$button = '';

				if($slide['sld_pos'] != 1) {
					$button .= '  
						<button 
							class="btn_up_pos btn btn-primary" 
							style="width: 40px;"
							data-sld-id="' . $slide['sld_id'] . '"
						>
							<i class="fas fa-caret-up"></i>
						</button>
					';
				}

				if($slide['sld_pos'] != $lastPos) {
					$button .= '  
						<button 
							class="btn_down_pos btn btn-danger" 
							style="width: 40px;"
							data-sld-id="' . $slide['sld_id'] . '"
						>
							<i class="fas fa-caret-down"></i>
						</button>
					';
				}

				$html .= '   
				<tr>
					<!-- mã -->
					<td>' . $stt++ . '</td>

					<!-- mã -->
					<td>' . $slide['sld_id'] . '</td>

					<!-- thể loại-->
					<td>' . $slide['cat_name'] . '</td>

					<!-- ảnh  -->
					<td>
						<img src="' . $slide['sld_image'] . '" width="100%">
					</td>

					<!-- vị trí -->
					<td>
						<div class="text-center d-flex flex-column align-items-center" style="width: 100%; height: 100%;">
							' . $button . '
						</div>
					</td>

					<!-- edit -->
					<td class="text-center">
						<a
							href="
							' . 
								create_link(base_url('admin/slider/update.php'), [
									"sldid"=>$slide['sld_id'],
									"from"=>getCurrentURL()
								])
							. '
							"
							class="btn_edit_sld btn btn-success"
							data-sld-id="' . $slide['sld_id'] . '">
							<i class="fas fa-edit"></i>
						</a>
					</td>

					<!-- remove -->
					<td class="text-center">
						<a 
							class="btn_remove_sld btn btn-danger"
							data-sld-id="' . $slide['sld_id'] . '">
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