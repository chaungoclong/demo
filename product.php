<?php 
require_once 'common.php';

$sql = "SELECT * FROM db_product WHERE pro_active = 1";
//lấy dữ liệu
$category = input_get("cat");
$brand = input_get("bra");

//tạo câu sql lấy sản phẩm
$sql = $category ? $sql .= " AND cat_id = '{$category}'" : $sql;
$sql = $brand ? $sql .= " AND bra_id = '{$brand}'" : $sql;

//lấy số hàng để hiển thị
$listPro = get_list($sql, 2);
$numPro = $listPro->num_rows;
$numCol = 4;
$numRow = row_qty($numPro, $numCol);
$cat = fetch_rows("db_category", "cat_id = '{$category}'", ["*"]);

/**
 * [tạo câu sql lấy các hãng tương ứng với danh mục. nếu không có danh mục hiện hết]
 */
$getBrandSQL = 
"SELECT * FROM db_brand
 WHERE bra_id IN
 (
 	SELECT bra_id FROM db_product
 	WHERE cat_id = '{$category}'
 )
";

if($category) {
	$listBrands = get_list($getBrandSQL, 1);
} else {
	$listBrands = fetch_tbl("db_brand", 1);
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<link rel="stylesheet" href="dist/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="assets/font/css/all.css">
	<link rel="stylesheet" href="assets/css/home.css">
	<script src="dist/jquery/jquery-3.5.1.js"></script>
	<script src="assets/js/home.js"></script>
	<script src="dist/popper/popper.min.js"></script>
	<script src="dist/bootstrap/js/bootstrap.js"></script>
</head>
<body>
	<?php
	require_once RF . '/include/header.php';
	require_once RF . '/include/navbar.php';
	require_once RF . '/include/slider.php';
	?>

	<!-- list brand -->
	<div id="listBrand" class="py-3" style="">
		<div class="d-flex justify-content-start flex-wrap">
			<?php foreach ($listBrands as $key => $brand): ?>
				<a class="card" href='
					<?php 
						/**
						 * Nếu có danh mục sản phẩm: in các sản phẩm có danh mục = danh mục && hãng = hãng
						 * Nếu chỉ có hãng: in các sản phẩm có hãng bằng hãng
						 */
						$cat = input_get("cat");
						if($cat) {
							echo create_link(base_url("product.php"), ["cat"=>$cat, "bra"=>$brand["bra_id"]]);
						} else {
							echo create_link(base_url("product.php"), ["bra"=>$brand["bra_id"]]);
						}
					 ?> 
				'>
					<img src="<?= $brand['bra_logo']; ?>" alt="">
				</a>
			<?php endforeach ?>
		</div>
	</div>
	<!-- /list brand -->

	<section class="product py-5">
		<h2 class="text-center mb-3"><?= isset($cat["cat_name"]) ? $cat["cat_name"] : ""; ?></h2>
		<div class="list_product_body">
			<!-- list products bar -->
			<div class="product_bar bg-info px-2 py-2 d-flex justify-content-between">
				<span class="badge  bg-faded"><?= $numPro; ?> products</span>
			</div>
			<!-- list products -->
			<?php for ($i = 0; $i < $numRow ; $i++): ?>
				<?php $countCol = 0; ?>
				<div class="card-group">
					<?php while ($pro = $listPro->fetch_assoc()): ?>
						<!-- ------------------------------------product ----------------------------------- -->
						<div class="card text-center" style="max-width: 25%;">
							<?php if ($pro['pro_qty'] == 0): ?>
								<span class="product_status badge badge-pill badge-warning">Sale out</span>
							<?php endif ?>
							<a href='<?= create_link(base_url("product_detail.php"), ["proid"=> $pro["pro_id"]]); ?>'>
								<img src="<?= $pro['pro_img']; ?>" alt="" class="card-img-top">
							</a>
							<div class="card-body">
								<!-- thông tin sản phẩm -->
								<h5 class="card-title"><a href=""><?= $pro['pro_name']; ?></a></h5>

								<?php 
								$cat = fetch_rows("db_category", "cat_id = '{$pro["cat_id"]}'", ["cat_name"]);
								?>
								<p class="text-uppercase"><?= $cat['cat_name']; ?></p>
								<h6 class="text-danger"><?= number_format($pro['pro_price'], 2, ',', '.'); ?> &#8363;</h6>
								<hr>
								<!-- thêm vào giỏ hàng -->
								<?php if ($pro['pro_qty']): ?>
									<a href='<?= create_link(base_url("card.php"), ["proid"=> $pro["pro_id"]]); ?>' class="btn btn-default btn-success">Add to card</a>
								<?php endif ?>
								<!-- xem chi tiết sản phẩm -->
								<a href='<?= create_link(base_url("product_detail.php"), ["proid"=> $pro["pro_id"]]); ?>' class="btn btn-default btn-primary">Detail</a>
								<!-- danh sách yêu thích -->
								<a href='<?= create_link(base_url("wishlist.php"), ["proid"=> $pro["pro_id"]]); ?>' class="btn btn-default btn-danger"><i class="far fa-heart"></i></a>
							</div>
						</div>
						<!-- ------------------------------------/product ----------------------------------- -->
						<?php
						$countCol++ ;
						if($countCol == $numCol) {
							break;
						}
						?>
					<?php endwhile ?>
				</div>
			<?php endfor ?>
		</div>
	</section>
	<!-- /product -->

	<?php
	require_once RF . '/include/footer.php';
	?>
</body>
</html>