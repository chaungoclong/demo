<?php 
	require_once '../../common.php';
	if(!empty($_POST['action']) && $_POST['action'] == "fetch") {
		$getNewsSQL = "SELECT * FROM db_news WHERE 1";
		$param = [];
		$format = "";

		// tìm kiếm theo tên, mã danh mục
		$q = !empty($_POST['q']) ? $_POST['q'] : "%%";
		$getNewsSQL .= " AND CONCAT(news_title, news_desc, news_content, create_by) LIKE(?)";
		$param[] = $q;
		$format .= "s";

		// tìm kiếm theo trạng thái
		$status = !empty($_POST['status']) ? $_POST['status'] : "all";
		switch ($status) {
			case "all":
				break;
			case 'on':
				$getNewsSQL .= " AND news_active = 1";
				break;
			case 'off':
				$getNewsSQL .= " AND news_active = 0";
				break;
			default:
				break;
		}

		// sắp xếp
		$sort = !empty($_POST['sort']) ? (int)$_POST['sort'] : 3;
		switch ($sort) {
			case 1:
				$getNewsSQL .= " ORDER BY news_title ASC";
				break;
			case 2:
				$getNewsSQL .= " ORDER BY news_title DESC";
				break;
			case 3: 
				$getNewsSQL .= " ORDER BY create_at DESC";
				break;
			case 4:
				$getNewsSQL .= " ORDER BY create_at ASC";
				break;
			default:
				$getNewsSQL .= " ORDER BY create_at ASC";
				break;
		}
		
		$listNews  = db_get($getNewsSQL, 0, $param, $format);
		$totalNews = count($listNews);
		
		// chia trang
		$newsPerPage  = !empty($_POST['numRows']) ? (int)$_POST['numRows'] : 5;
		$totalPage   = ceil($totalNews / $newsPerPage);
		$currentPage = !empty($_POST['currentPage']) ? (int)$_POST['currentPage'] : 1;
		$currentPage = $currentPage > $totalPage ? $totalPage : $currentPage;
		$offset      = ($currentPage - 1) * $newsPerPage;

		$getNewsSQL .= " LIMIT ? OFFSET ?";
		$param          = [...$param, $newsPerPage, $offset];
		$format         .= "ii";
		$listNews   = db_get($getNewsSQL, 0, $param, $format);
		
		$list_news     = '';

		if ($totalNews > 0) {
			foreach ($listNews as $key => $news) {
				$checked = $news['news_active'] ? "checked" : "";
				$list_news .= '   
				<tr>
					<!-- ảnh  -->
					<td class="align-middle">
						<img src="../../image/' . $news['news_img'] . '" style="width:50px; height:50px" class="card-img">
					</td>

					<!-- tiêu đề -->
					<td class="align-middle">' . $news['news_title'] . '</td>

					<!-- mô tả -->
					<td class="align-middle">' . $news['news_desc'] . '</td>

					<!-- tác giả -->
					<td class="align-middle">' . $news['create_by'] . '</td>

					<!-- active -->
					<td class="align-middle">
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

					<!-- action -->
					<td width="115px" class="align-middle">
						<a
							href="
							' . 
								create_link(base_url('admin/news/update.php'), [
									"newsid"=>$news['news_id']
								])
							. '
							"
							class="btn_edit_news btn btn-success"
							id="btn_edit_' . $news['news_id'] . '"
							data-news-id="' . $news['news_id'] . '">
							<i class="fas fa-edit"></i>
						</a>

						<a 
							class="btn_delete_news btn btn-danger"
							id="btn_delete_' . $news['news_id'] . '"
							data-news-id="' . $news['news_id'] . '">
							<i class="fas fa-trash-alt"></i>
						</a>
					</td>
				</tr>
				';
			}
		}

		$pagination = paginateAjax($totalPage, $currentPage);
		$output = ['list_news'=>$list_news, 'pagination'=>$pagination];
		echo json_encode($output);
	}
