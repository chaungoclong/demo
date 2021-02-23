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
				SELECT * FROM db_news
				WHERE 
					news_title LIKE(?) OR
					news_desc  LIKE(?)
				";

				$param = [$key, $key];
				$listNews = db_get($searchSQL, 1, $param, "ss");
			} else {

				$listNews = getListNews();
			}
			

			// chia trang
			$totalNews = $listNews->num_rows;
			$newsPerPage = 5;
			$currentPage = isset($_POST['currentPage']) ? $_POST['currentPage'] : 1;
			$currentLink = create_link(base_url("admin/news/index.php"), ["page"=>'{page}', 'q'=>$q]);
			$page = paginate($currentLink, $totalNews, $currentPage, $newsPerPage);

			// danh sách tin tức sau khi chia trang
			if($q != "") {
				$searchResultSQL = $searchSQL . " LIMIT ? OFFSET ?";
				$param = [$key, $key, $page['limit'], $page['offset']];

				// danh sách người dùng sau khi tìm kiếm và chia trang chia trang
				$listNewsPaginate = db_get($searchResultSQL, 1, $param, "ssii");
			} else {

				$listNewsPaginate = getListNews($page['limit'], $page['offset']);
			}

			
			$totalNewsPaginate = $listNewsPaginate->num_rows;

			// số thứ tự
			$stt = 1 + (int)$page['offset'];

		$html .= ' 
			<table class="table table-hover table-bordered" style="font-size: 13px;">
				<tr>
					<th>STT</th>
					<th>Ảnh</th>
					<th>Tiêu đề</th>
					<th>Mô tả</th>
					<th>Tác giả</th>
					<th>Trạng thái</th>
					<th>Sửa</th>
					<th>Xóa</th>
				</tr>
		';

		// in các đơn hàng
		
		if ($totalNewsPaginate > 0) {
			foreach ($listNewsPaginate as $key => $news) {
				$checked = $news['news_active'] ? "checked" : "";
				$html .= '   
				<tr>
					<!-- ảnh  -->
					<td>
						<img src="../../image/' . $news['news_img'] . '" width="30px" height="30px">
					</td>

					<!-- tiêu đề -->
					<td>' . $news['news_title'] . '</td>

					<!-- mô tả -->
					<td>' . $news['news_desc'] . '</td>

					<!-- tác giả -->
					<td>
						' . $news['create_by'] . '
					</td>
					
					<!-- active -->
					<td>
						<div class="custom-control custom-switch">
							<input 
								type="checkbox" 
								id="switch_active_' . $news['news_id'] . '" 
								data-news-id="' . $news['news_id'] . '"
								class="btn_switch_active custom-control-input" 
								value="' . $news['news_active'] . '"
								' . $checked . '
							>
							<label for="switch_active_' . $news['news_id'] . '" class="custom-control-label"></label>
						</div>
					</td>

					<!-- edit -->
					<td>
						<a
							href="
							' . 
								create_link(base_url('admin/news/update.php'), [
									"newsid"=>$news['news_id']
								])
							. '
							"
							class="btn_edit_news btn btn-success"
							data-news-id="' . $news['news_id'] . '">
							<i class="fas fa-edit"></i>
						</a>
					</td>

					<!-- remove -->
					<td>
						<a 
							class="btn_remove_news btn btn-danger"
							data-news-id="' . $news['news_id'] . '">
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