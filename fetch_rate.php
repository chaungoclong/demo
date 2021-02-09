<?php 
	require_once 'common.php';
	if(!empty($_POST['action']) && $_POST['action'] == "fetch_rate") {

		$param = [];
		$format = "";
		$proID = input_post('proID');
		$fetchRateSQL = " 
			SELECT db_customer.cus_name, db_customer.cus_avatar, db_rate.r_content, db_rate.r_star, db_rate.r_create_at
			FROM db_rate JOIN db_customer
			ON db_rate.cus_id = db_customer.cus_id 
			WHERE pro_id = ?
		";
		$param[] = $proID;
		$format .= "i";

		// sort
		$sort = !empty($_POST['sort']) ? (int)$_POST['sort'] : 1;
		if($sort == 1) {
			$fetchRateSQL .= " ORDER BY db_rate.r_create_at DESC";
		} else {
			$fetchRateSQL .= " ORDER BY db_rate.r_create_at ASC";
		}

		// get result
		$totalRate = db_get($fetchRateSQL, 0, $param, $format);
		$star = getStar($proID);
		$html = "";

		// pagination
		$numRate = count($totalRate);
		$ratePerPage = 1;
		$totalPage = ceil($numRate / $ratePerPage);
		$currentPage = !empty($_POST['currentPage']) ? (int)$_POST['currentPage'] : 1;
		$offset = ($currentPage - 1) * $ratePerPage;

		$fetchRateSQL .= " LIMIT ? OFFSET ?";
		$param = [...$param, $ratePerPage, $offset];
		$format .= "ii";
		$totalRate = db_get($fetchRateSQL, 0, $param, $format);


		if (!empty($totalRate)) {
			foreach ($totalRate as $key => $rate) {
				$starRate = '';

				$Date = "";
				$timeCreate = (int) strtotime($rate['r_create_at']);

				// tạo số sao đánh giá
				for ($i = 0; $i < $rate['r_star'] ; $i++) { 
					$starRate .= '  
					<li><i class="fas fa-star"></i></li>
					';
				}

				// ngày bình luận, cập nhật bình luận
				$Date = date("d/m/Y", $timeCreate);
				
				
				$html .= '  
					<div class="row m-0 shadow py-1">
						<div class="col-1">
							<img src="image/' . $rate['cus_avatar'] . '"width="60px" height="60px">
						</div>

						<div class="col-11">
							<h6 class="rate_name">' .$rate['cus_name']. '</h6>

							<!-- số sao đánh giá + ngày đánh giá -->
							<div class="star_rate_date d-flex mb-2">
								<ul class="star_rate nav mr-2">
									' . $starRate . '
								</ul>
								<span>' . $Date. '</span>
							</div>

							<p class="rate_content">
								' . $rate['r_content'] . '
							</p>

						</div>
					</div>
					<hr width="100%">
				';
			}

			// phân trang
			
			$prevLink = "";
			if($totalPage > 1 && $currentPage > 1) {
				$prevLink .= "  
				<li class='page-item page-link' data-page-number='" . ($currentPage - 1) . "'>prev</li>
				";
			}

			$betweenLink = '';
			for ($i = 1; $i <= $totalPage ; $i++) { 
				if($i != $currentPage) {
					$betweenLink .= "  
					<li class='page-item page-link' data-page-number='$i'>$i</li>
					";
				} else {
					$betweenLink .= "  
					<li class='page-item active' data-page-number='$i'>
						<span class='page-link'>
							$i
							<span class='sr-only'>(current)</span>
						</span>
					</li>
					";
				}
			}

			$nextLink = "";
			if($totalPage > 1 && $currentPage < $totalPage) {
				$nextLink .= "  
				<li class='page-item page-link' data-page-number='" . ($currentPage + 1 ). "'>next</li>
				";
			}

			$pagination = "";
			if($totalPage) {
				$pagination = "
				<div class='mt-3'>
					<nav aria-label='...'>
						<ul class='pagination justify-content-center'>
							" . $prevLink . $betweenLink . $nextLink . "
						</ul>
					</nav>
				</div>
				";
			}
			$html .= $pagination;
		} else {
			$html .= "<div>
				KHÔNG CÓ BÌNH LUẬN
			</div>";
		}

		$res = [
			"html" => $html,
			"star" => $star, 
			"pag" => $pagination
		];
		echo json_encode($res);
	}
 ?>