<?php 
	require_once 'common.php';

	if(!empty($_POST['action']) && $_POST['action'] == "fetch") {

		$opt = $_POST['opt'] ?? '';
		$catID = input_post('catID');
		$braID = input_post('braID');

		$getProSQL = "SELECT * FROM db_product WHERE pro_active = 1";

		// tồn tại hãng -> lấy theo hãng , tồn tại danh mục -> lấy theo danh mục 
		$getProSQL .= $catID ? " AND cat_id = ?" : "";
		$getProSQL .= $braID ? " AND bra_id = ?" : "";

		// đối số truyền vào
		$param = [];
		$format = "";
		if($catID && $braID) {
			$param  = [$catID, $braID];
			$format = "ii";
		} elseif($catID) {
			$param = [$catID];
			$format = "i";
		} elseif($braID) {
			$param = [$braID];
			$format = "i";
		}

		// echo $getProSQL;
		// vd($param);
		// echo $format;

		// danh sách sản phẩm trước khi chia trang
		$listProBefore = db_get($getProSQL, 1, $param, $format);
		//vd($listProAfterBefore);
		
		// ====================CHIA TRANG===========================
		
		// tổng số sản phẩm
		$totalPro = $listProBefore->num_rows;

		// số hàng số cột
		$numCol  = 4;
		$numRow  = row_qty($totalPro, $numCol);

		// số sản phẩm trên 1 trang
		$proPerPage = 16;

		// trang hiện tại
		$currentPage = input_get('page') ? input_get('page') : 1;

		// link trang hiện tại
		$currentLink = create_link(base_url('product.php'), 
			['cat'=>$catID, 'bra'=>$braID, 'page'=>'{page}']
		);

		$page = paginate($currentLink, $totalPro, $currentPage, $proPerPage);

		if(!empty($opt)) {
			switch ($opt) {
				case 'a_z':
					$getProSQL .= " ORDER BY pro_name ASC";
					break;
				case 'z_a':
					$getProSQL .= " ORDER BY pro_name DESC";
					break;
				case 'h_l':
					$getProSQL .= " ORDER BY pro_price DESC";
					break;
				case 'l_h':
					$getProSQL .= " ORDER BY pro_price ASC";
					break;
				
				default:
					$getProSQL .= " ORDER BY pro_name ASC";
					break;
			}
		}

		// danh sách sản phẩm sau khi chia trang
		$getProSQL .= " LIMIT ? OFFSET ?";

		$param     = [...$param, $page['limit'], $page['offset']];
		$format    .= "ii";
		
		$listProAfter = db_get($getProSQL, 1, $param, $format);

		$html = '';

		$start = $totalPro > 0 ? (int)$page["offset"] + 1 : 0;
		$end   = (int)$page["offset"] + (int)$page["limit"];
		$end   = $end <= $totalPro ? $end : $totalPro;

		$html = '';
							
		foreach ($listProAfter as $key => $pro) {
			$saleOut = '';

			if($pro["pro_qty"] == 0) {
				$saleOut .= '
					<span class="product_status badge badge-pill badge-warning">Bán hết</span>
				';
			}

			$catName = s_cell(
				"SELECT cat_name FROM db_category WHERE cat_id = ?",
				[$pro['cat_id']],
				"i"
			);

			$addCartBtn = '';
			if($pro['pro_qty']) {
				$addCartBtn .= '  
				<a class="btn_add_cart_out btn btn-success text-light" data-pro-id="' . $pro['pro_id'] . '"
					data-toggle="tooltip" data-placement="top" title="Thêm vào giỏ hàng"
					>
					<i class="fas fa-cart-plus fa-lg"></i>
				</a>
				';
			}
		
			$html .= ' 
			<div class="card text-center" style="max-width: 25%;">
				' . $saleOut .'
				<a href="' . create_link(base_url("product_detail.php"), ["proid"=> $pro["pro_id"]]) . '">
					<img src="image/' . $pro['pro_img'] . '" alt="" class="card-img-top">
				</a>
				<div class="card-body">
					<!-- thông tin sản phẩm -->
					<h5 class="card-title">
					<a href="
						' .
						create_link(
						base_url("product_detail.php"),
						['proid' => $pro['pro_id']]
						)
						. '
						">
						' . $pro['pro_name'] . '
					</a>
					</h5>
					<p class="text-uppercase card-subtitle">
						' . $catName . '
					</p>
					<h6 class="text-danger">
					<strong>' . number_format($pro['pro_price'], 0, ',', '.') . ' &#8363</strong>
					</h6>
					<hr>
					<!-- thêm vào giỏ hàng -->
					' . $addCartBtn .'
					<!-- xem chi tiết sản phẩm -->
					<a href="' . create_link(base_url("product_detail.php"), ["proid"=> $pro["pro_id"]]) . '"" class="btn btn-default btn-primary" data-toggle="tooltip" data-placement="top" title="chi tiết sản phẩm">
						<i class="far fa-eye fa-lg"></i>
					</a>
				</div>
			</div>
			';
		}

		$res = [
			'html'=>$html,
			'page'=>$page,
			'start'=>$start,
			'end'=>$end,
			'total'=>$totalPro,
			'sql'=>$getProSQL
		];

		echo json_encode($res);
	}


