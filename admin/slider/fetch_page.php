<?php 
	require_once '../../common.php';
	if(isset($_POST['action']) && $_POST['action'] == "fetch") {
		$getSlideSQL = "
		SELECT db_slider.*, db_category.cat_name FROM `db_slider` 
		JOIN db_category ON db_slider.cat_id = db_category.cat_id
		WHERE 1";
		$param = [];
		$format = "";

		// tìm kiếm theo id slide
		$q = !empty($_POST['q']) ? $_POST['q'] : "%%";
		$getSlideSQL .= " AND CONCAT(db_slider.sld_id, db_slider.sld_pos, db_slider.sld_link, db_category.cat_name) LIKE(?)";
		$param[] = $q;
		$format .= "s";

		// tìm kiếm theo danh mục
		$category = !empty($_POST['category']) ? $_POST['category'] : "all";
		if($category != "all") {
			$getSlideSQL .= " AND db_slider.cat_id = ?";
			$param[] = $category;
			$format .= "i";
		}

		// sắp xếp
		$sort = !empty($_POST['sort']) ? (int)$_POST['sort'] : 3;
		switch ($sort) {
			case 1:
				$getSlideSQL .= " ORDER BY db_slider.sld_create_at DESC";
				break;
			case 2:
				$getSlideSQL .= " ORDER BY db_slider.sld_create_at ASC";
				break;
			case 3:
				$getSlideSQL .= " ORDER BY db_slider.sld_pos ASC";
				break;
			case 4:
				$getSlideSQL .= " ORDER BY db_slider.sld_pos DESC";
				break;
			default:
				$getSlideSQL .= " ORDER BY db_slider.sld_pos ASC";
				break;
		}
			
		$listSlide = db_get($getSlideSQL, 0, $param, $format);
		$totalSlide = count($listSlide);

		// chia trang
		$sldPerPage = !empty($_POST['numRows']) ? (int)$_POST['numRows'] : 5;
		$totalPage = ceil($totalSlide / $sldPerPage);
		$currentPage = !empty($_POST['currentPage']) ? (int)$_POST['currentPage'] : 1;
		$currentPage = $currentPage > $totalPage ? $totalPage : $currentPage;
		$offset = ($currentPage - 1) * $sldPerPage;

		$getSlideSQL .= " LIMIT ? OFFSET ?";
		$param = [...$param, $sldPerPage, $offset];
		$format .= "ii";
		$listSlide = db_get($getSlideSQL, 0, $param, $format);

		$slides = '';
		if ($totalSlide > 0) {
			foreach ($listSlide as $key => $slide) {
				$lastPos = lastPostion();
				$btnMovePos = '';

				// di chuyển lên
				if($slide['sld_pos'] > 1) {
					$btnMovePos .= '  
						<button 
							class="btn_up btn btn-primary" 
							id="btn_up_' . $slide['sld_id'] . '"
							style="width: 40px;"
							data-sld-id="' . $slide['sld_id'] . '"
						>
							<i class="fas fa-caret-up"></i>
						</button>
					';
				}

				// di chuyển xuống
				if($slide['sld_pos'] < $lastPos) {
					$btnMovePos .= '  
						<button 
							class="btn_down btn btn-danger" 
							id="btn_down_' . $slide['sld_id'] . '"
							style="width: 40px;"
							data-sld-id="' . $slide['sld_id'] . '"
						>
							<i class="fas fa-caret-down"></i>
						</button>
					';
				}

				$slides .= '   
				<tr>
					<!-- ID -->
					<td class="align-middle">' . $slide['sld_id'] . '</td>

					<!-- danh mục-->
					<td class="align-middle">' . $slide['cat_name'] . '</td>

					<!-- vị trí-->
					<td class="align-middle">' . $slide['sld_pos'] . '</td>

					<!-- link-->
					<td class="align-middle">
						<a href="' . $slide['sld_link'] . '">'. $slide['sld_link'] .'</a>
					</td>

					<!-- xem trước  -->
					<td class="align-middle">
						<img src="' . base_url("image/" . $slide["sld_image"]) . '" width="100%">
					</td>

					<!-- di chuyển -->
					<td class="align-middle">
						<div class="btn-group-vertical">
							' . $btnMovePos . '
						</div>
					</td>

					<!-- sửa -->
					<td class="align-middle">
						<a
							href="
							' . 
								create_link(base_url('admin/slider/update.php'), [
									"sldid"=>$slide['sld_id']
								])
							. '
							"
							class="btn_edit_sld btn btn-success"
							id="btn_delete_' . $slide["sld_id"] . '"
							data-sld-id="' . $slide['sld_id'] . '">
							<i class="fas fa-edit"></i>
						</a>
					</td>

					<!-- xóa-->
					<td class="align-middle">
						<a 
							class="btn_delete_sld btn btn-danger"
							id="btn_delete_' . $slide["sld_id"] . '"
							data-sld-id="' . $slide['sld_id'] . '">
							<i class="fas fa-trash-alt"></i>
						</a>
					</td>
				</tr>
				';
			}
		}

		$pagination = paginateAjax($totalPage, $currentPage);
		$output = ['slides'=>$slides, 'pagination'=>$pagination];
		echo json_encode($output);			
	}