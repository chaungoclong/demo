<?php 
	require_once '../../common.php';
	if(!empty($_POST['action']) && $_POST['action'] == "fetch") {
		$getRateSQL = "
		SELECT db_rate.*, db_customer.cus_name, db_customer.cus_avatar, db_customer.cus_id, db_product.pro_name, db_product.pro_img , db_product.pro_id
		FROM db_rate
		JOIN db_customer ON db_rate.cus_id = db_customer.cus_id
		JOIN db_product ON db_rate.pro_id = db_product.pro_id
		WHERE 1";
		$param = [];
		$format = "";

		// tìm kiếm theo tên khách hàng - sản phẩm và nội dung - ngày tạo đánh giá
		$q = !empty($_POST['q']) ? $_POST['q'] : "%%";
		$getRateSQL .= " AND CONCAT(db_customer.cus_name, db_product.pro_name, db_rate.r_content, db_rate.r_create_at) LIKE(?)";
		$param[] = $q;
		$format .= "s";

		// tìm kiếm theo số sao
		$star = !empty($_POST['numStars']) ? $_POST['numStars'] : "all";
		if($star != "all") {
			$getRateSQL .= " AND r_star = ?";
			$param[] = $star;
			$format .= "i";
		}

		// sắp xếp
		$sort = !empty($_POST['sort']) ? (int)$_POST['sort'] : 1;
		switch ($sort) {
			case 1:
				$getRateSQL .= " ORDER BY r_create_at DESC";
				break;
			case 2:
				$getRateSQL .= "  ORDER BY r_create_at ASC";
				break;
			default:
				$getRateSQL .= "  ORDER BY r_create_at DESC";
				break;
		}

		$listRate  = db_get($getRateSQL, 0, $param, $format);
		$totalRate = count($listRate);
		
		// chia trang
		$ratePerPage  = !empty($_POST['numRows']) ? (int)$_POST['numRows'] : 5;
		$totalPage   = ceil($totalRate / $ratePerPage);
		$currentPage = !empty($_POST['currentPage']) ? (int)$_POST['currentPage'] : 1;
		$currentPage = $currentPage > $totalPage ? $totalPage : $currentPage;
		$offset      = ($currentPage - 1) * $ratePerPage;

		$getRateSQL .= " LIMIT ? OFFSET ?";
		$param          = [...$param, $ratePerPage, $offset];
		$format         .= "ii";
		$listRate   = db_get($getRateSQL, 0, $param, $format);
		
		$rates     = '';

		if ($totalRate > 0) {
			foreach ($listRate as $key => $rate) {
				$starIcon = str_repeat('<i class="fas fa-star"></i>', (int)$rate['r_star']);
				$rates .= '   
				<tr>
					<!-- khách hàng -->
					<td class="align-middle">
						<img src="../../image/' . $rate["cus_avatar"].'" alt="" class="card-img" style="width:50px; height: 50px;">
						<h6><a href="../customer/update.php?cusid=' . $rate['cus_id'] . '">' . $rate["cus_name"]. '</a></h6>
					</td>

					<!-- sản phẩm-->
					<td class="align-middle">
						<img src="../../image/' . $rate["pro_img"].'" alt="" class="card-img" style="width:50px; height: 50px;">
						<h6><a href="../product/update.php?proid=' . $rate['pro_id'] . '">' . $rate["pro_name"]. '</a></h6>
					</td>

					<!-- nội dung  -->
					<td class="align-middle">"' . $rate["r_content"]. '"</td>

					<!-- số sao  -->
					<td class="align-middle text-warning">' . $starIcon . '</td>

					<!-- ngày tạo  -->
					<td class="align-middle">' . strToTimeFormat($rate["r_create_at"], "d-m-Y"). '</td>
				</tr>
				';
			}
		}

		$pagination = paginateAjax($totalPage, $currentPage);
		$output = ['rates'=>$rates, 'pagination'=>$pagination];
		echo json_encode($output);
	}
