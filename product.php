<?php
require_once 'common.php';
require_once RF . '/include/header.php';
require_once RF . '/include/navbar.php';
require_once RF . '/include/slider.php';
?>
<?php
/**
* [tạo câu sql lấy các hãng tương ứng với danh mục. nếu không có danh mục hiện hết]
*/
$cat = input_get("cat"); //danh mục
$bra = input_get("bra"); //hãng
$getBrandSQL =
"
SELECT * FROM db_brand
WHERE bra_id IN
(
	SELECT bra_id FROM db_product
	WHERE cat_id = '{$cat}'
)
";
if($cat) {
	$listBrands = get_list($getBrandSQL, 1);
} else {
	$listBrands = fetch_tbl("db_brand", 1);
}
?>
<main>
	<div style="padding-left: 85px; padding-right: 85px;">
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
		<?php
		$getProSQL = "SELECT * FROM db_product WHERE pro_active = 1";

		//tạo câu sql lấy sản phẩm
		$getProSQL = $cat ? $getProSQL .= " AND cat_id = '{$cat}'" : $getProSQL;
		$getProSQL = $bra ? $getProSQL .= " AND bra_id = '{$bra}'" : $getProSQL;

		//lấy số lượng sản phẩm
		$listRecord = get_list($getProSQL, 2);
		$numRecord  = $listRecord->num_rows;

		//phân trang
		//tạo link trang hiện tại
		$currentLink = create_link(base_url("product.php"), ["cat"=>$cat, "bra"=>$bra, "page"=>"{page}"]);

		//số sản phẩm trên trang
		$proPerPage  = 8;

		//trang hiện tại
		$currentPage = input_get("page") ? input_get("page") : 1;

		//biến chứa thông trả về từ hàm phân trang (offset, limit, html)
		$page = paginate($currentLink, $numRecord, $currentPage, $proPerPage);

		//câu SQL sau khi phân trang
		$getProSQL   .= " LIMIT {$page['limit']} OFFSET {$page['offset']}";

		//hiển thị sản phẩm sau khi phân trang
		$listPro = get_list($getProSQL, 2);
		$numPro  = $listPro->num_rows;
		$numCol  = 4;
		$numRow  = row_qty($numPro, $numCol);
		$catName = fetch_rows("db_category", "cat_id = '{$cat}'", ["*"]);
		?>

		<section class="product my-5 shadow">
			<h2 class="text-center mb-3"><?= isset($catName["cat_name"]) ? $catName["cat_name"] : ""; ?></h2>
			<div class="list_product_body">
				<!-- list products bar -->
				<div class="product_bar bg-info px-2 py-2 d-flex justify-content-between">
					<span class="badge  bg-faded">
						<span><?= $numRecord; ?> sản phẩm</span>
						<span>
							(
								<?php
								$start = (int)$page["offset"] + 1;
								$end   = (int)$page["offset"] + (int)$page["limit"];
								$end   = $end <= $numRecord ? $end : $numRecord;
								echo $start . " - " . $end;
								?>
							)
							</span>
						</span>
					</div>
					<!-- list products -->
					<?php for ($i = 0; $i < $numRow ; $i++): ?>
						<?php $countCol = 0; ?>
						<div class="card-group">
							<?php while ($pro = $listPro->fetch_assoc()): ?>

								<!-- ------------------------------------product ----------------------------------- -->
								<div class="card text-center" style="max-width: 25%;">
									<?php if ($pro['pro_qty'] == 0): ?>
										<span class="product_status badge badge-pill badge-warning">Bán hết</span>
									<?php endif ?>
									<a href='<?= create_link(base_url("product_detail.php"), ["proid"=> $pro["pro_id"]]); ?>'>
										<img src="<?= $pro['pro_img']; ?>" alt="" class="card-img-top">
									</a>
									<div class="card-body">
										<!-- thông tin sản phẩm -->
										<h5 class="card-title">
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
									<?php
									$catName = fetch_rows("db_category", "cat_id = '{$pro["cat_id"]}'", ["cat_name"]);
									?>
									<p class="text-uppercase card-subtitle">
										<?= $catName['cat_name']; ?>
									</p>
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

								<!-- danh sách yêu thích -->
								<a href='<?= create_link(base_url("wishlist.php"), ["proid"=> $pro["pro_id"]]); ?>' class="btn btn-default btn-danger"
									data-toggle="tooltip" data-placement="top" title="Thêm vào danh sách yêu thích">
									<i class="far fa-heart fa-lg"></i>
								</a>
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
</div>
</main>
<script>
	$(function() {
		
	});
</script>
<!-- phân trang -->
<?php
echo $page['html'];
?>
<?php
require_once RF . '/include/footer.php';
?>