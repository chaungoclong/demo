<?php
require_once 'common.php';
if(!empty($_POST['action']) && $_POST['action'] == "fetch") {
	$minPrice = input_post('min_price');
	$maxPrice = input_post('max_price');
	$brand    = input_post('brand');
	$category = input_post('category');
	$getProductSQL = "SELECT * FROM db_product WHERE pro_active = 1";
	$param = [];
	$format = "";
	
	// price
	if($minPrice && $maxPrice) {
		$getProductSQL .= " AND pro_price BETWEEN ? AND ?";
		$param = [...$param, $minPrice, $maxPrice];
		$format .= "ii";
	}

	// brand
	if($brand) {
		// tạo ra danh sách tham số truy vấn bằng cách tạo ra 1 mảng chứa có số lượng dấu ? = số lượng
		// tham số truyền vào -> chuyển mảng về chuỗi
		$paramQuery = implode(',', array_fill(0, count($brand), "?"));
		// thêm đối số và kí tự định dạng vào mảng đối số và chuỗi định dạng
		foreach ($brand as $key => $braID) {
			$param[] = $braID;
			$format .= "i";
		}
		// thêm điều kiện vào câu truy vấn
		$getProductSQL .= " AND bra_id IN(" . $paramQuery . ")";
	}
	// category
	if($category) {
		$paramQuery = implode(',', array_fill(0, count($category), "?"));
		foreach ($category as $key => $catID) {
			$param[] = $catID;
			$format .= "i";
		}
		$getProductSQL .= " AND cat_id IN(" . $paramQuery . ")";
	}

	// sort
	$sort = !empty($_POST['sort']) ? (int)$_POST['sort'] : 1;
	switch ($sort) {
		case 1:
			$getProductSQL .= " ORDER BY pro_name ASC";
			break;
		case 2:
			$getProductSQL .= " ORDER BY pro_name DESC";
			break;
		case 3:
			$getProductSQL .= " ORDER BY pro_price ASC";
			break;
		case 4:
			$getProductSQL .= " ORDER BY pro_price DESC";
			break;
		case 5:
			$getProductSQL .= " ORDER BY pro_create_at ASC";
			break;
		case 6:
			$getProductSQL .= " ORDER BY pro_create_at DESC";
			break;
		
		default:
			$getProductSQL .= " ORDER BY pro_name ASC";
			break;
	}
	// echo $getProductSQL;
	// vd($param);
	
	// danh sách sản phẩm trước khi chia trang
	$listProduct = db_get($getProductSQL, 0, $param, $format);

	// chia trang
	$currentPage = !empty($_POST['currentPage']) ? (int)$_POST['currentPage'] : 1;
	$proPerPage = 9;
	$totalProduct = count($listProduct);
	$totalPage = ceil($totalProduct / $proPerPage);
	$offset = ((int)$currentPage - 1) * $proPerPage;

	$getProductSQL .= " LIMIT ? OFFSET ?";
	$param = [...$param, $proPerPage, $offset];
	$format .= "ii";
	// danh sách sản phẩm sau khi chia trang
	$listProduct = db_get($getProductSQL, 0, $param, $format);
}
?>
<section class="product">
	<div class="row m-0 bg-white">
		<?php foreach($listProduct as $key => $pro): ?>
			<?php if ($pro['pro_active']): ?>
			<!-- ------------------------------------product ----------------------------------- -->
			<div class="card text-center col-4">
				<?php if ($pro['pro_qty'] == 0): ?>
				<span class="product_status badge badge-pill badge-warning">Bán hết</span>
				<?php endif ?>
				<a href='<?= create_link(base_url("product_detail.php"), ["proid"=> $pro["pro_id"]]); ?>'>
					<img src="image/<?= $pro['pro_img']; ?>" alt="" class="card-img-top">
				</a>
				<div class="card-body">


					<!-- thông tin sản phẩm -->
					<h5 class="card-title text-uppercase">
						<a href="
							<?php
								echo create_link(
									base_url("product_detail.php"),
									['proid' => $pro['pro_id']]
								);
							?>
						">
							<?= $pro['pro_name']; ?>
						</a>
					</h5>
					<h6 class="text-danger">
					<strong><?= number_format($pro['pro_price'], 0, ',', '.'); ?> &#8363;</strong>
					</h6>
					<hr>

					<!-- thêm vào giỏ hàng -->
					<?php if ($pro['pro_qty']): ?>
					<a class="btn_add_cart_out btn btn-success text-light" data-pro-id="<?= $pro['pro_id']; ?>"
						data-toggle="tooltip" data-placement="top" title="Thêm vào giỏ hàng"
						>
						<i class="fas fa-cart-plus fa-lg"></i>
					</a>
					<?php endif ?>

					<!-- xem chi tiết sản phẩm -->
					<a href='<?= create_link(base_url("product_detail.php"), ["proid"=> $pro["pro_id"]]); ?>' class="btn btn-default btn-primary" data-toggle="tooltip" data-placement="top" title="chi tiết sản phẩm">
						<i class="far fa-eye fa-lg"></i>
					</a>
				</div>
			</div>
			<!-- ------------------------------------/product ----------------------------------- -->
			<?php endif ?>
		<?php endforeach; ?>
	</div>

	<!-- phân trang -->
	<div class="mt-3">
		<?php if ($totalPage): ?>
		<nav aria-label="...">
			<ul class="pagination justify-content-center">

				<!-- previous page link -->
				<?php if ($totalPage > 1 && $currentPage > 1): ?>
				<li class="page-item page-link" data-page-number="<?= $currentPage - 1; ?>">Trước</li>
				<?php endif ?>

				<!-- page link -->
				<?php for($i = 1; $i <= $totalPage; ++$i): ?>
					<?php if ($i != $currentPage): ?>
					<li class="page-item page-link" data-page-number="<?= $i; ?>"><?= $i; ?></li>
					<?php else: ?>
					<li class="page-item active" data-page-number="<?= $i; ?>">
						<span class="page-link">
							<?= $i; ?>
							<span class="sr-only">(current)</span>
						</span>
					</li>
					<?php endif ?>
				<?php endfor; ?>

				<!-- next page link -->
				<?php if ($totalPage > 1 && $currentPage < $totalPage): ?>
				<li class="page-item page-link" data-page-number="<?= $currentPage + 1; ?>">Sau</li>
				<?php endif ?>
			</ul>
		</nav>
		<?php endif ?>
	</div>
</section>