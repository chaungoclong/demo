<?php
require_once 'common.php';
require_once RF . '/include/header.php';
require_once RF . '/include/navbar.php';
require_once RF . '/include/slider.php';

// mã danh mục
$catID = input_get('cat');

// mã hãng
$braID = input_get('bra');

?>

<main>
	<div class="" style="padding-left: 85px; padding-right: 85px;">
		
		<!-- in các hãng -->
		<?php 
			if($catID) {

				$getBrandByCatSQL = "
				SELECT * FROM db_brand
				WHERE 
					bra_id IN(
						SELECT bra_id FROM db_product
						WHERE cat_id = ?
					)
				";

				$listBrandByCat = db_get($getBrandByCatSQL, 1, [$catID], "i");
			} else {

				$listBrandByCat = db_fetch_table("db_brand", 1);
			}
		 ?>

		 <!-- danh sách các hãng -->
		<div id="listBrand" class="py-3" style="">
			<div class="d-flex justify-content-start flex-wrap">
				<?php foreach ($listBrandByCat as $key => $brand): ?>
					<?php if ($brand['bra_active']): ?>
						<a class="card" href='
						<?php
							/**
							* Nếu có danh mục sản phẩm: in các sản phẩm có danh mục = danh mục && hãng = hãng
							* Nếu chỉ có hãng: in các sản phẩm có hãng bằng hãng
							*/
							if($catID) {
								echo create_link(base_url("product.php"), ["cat"=>$catID, "bra"=>$brand["bra_id"]]);
							} else {
								echo create_link(base_url("product.php"), ["bra"=>$brand["bra_id"]]);
							}
						?>'
						>
							<img src="image/<?= $brand['bra_logo']; ?>" alt="">
						</a>
					<?php endif ?>
				<?php endforeach ?>
			</div>
		</div>

		<!-- filter box -->
		<div class="filter-box">
			<ul class="nav">
				<li class="dropdown">
					<a data-toggle="dropdown" class="dropdown-toggle"><strong>Sắp xếp</strong></a>
					<div class="dropdown-menu filter_menu">
						<a class="dropdown-item choose_item" data-choose-id="1">A-Z</a>
						<a class="dropdown-item choose_item" data-choose-id="2">Z-A</a>
						<a class="dropdown-item choose_item" data-choose-id="3">Giá cao đến thấp</a>
						<a class="dropdown-item choose_item" data-choose-id="4">Giá thấp đến cao</a>
					</div>
				</li>
			</ul>
		</div>

		<!-- lấy danh sách sản phẩm -->
		<?php 
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
			$listProAfterBefore = db_get($getProSQL, 1, $param, $format);
			//vd($listProAfterBefore);
			
			// ====================CHIA TRANG===========================
			
			// tổng số sản phẩm
			$totalPro = $listProAfterBefore->num_rows;

			// số hàng số cột
			$numCol  = 4;
			$numRow  = row_qty($totalPro, $numCol);

			// số sản phẩm trên 1 trang
			$proPerPage = 8;

			// trang hiện tại
			$currentPage = input_get('page') ? input_get('page') : 1;

			// link trang hiện tại
			$currentLink = create_link(base_url('product.php'), 
				['cat'=>$catID, 'bra'=>$braID, 'page'=>'{page}']
			);

			$page = paginate($currentLink, $totalPro, $currentPage, $proPerPage);

			// danh sách sản phẩm sau khi chia trang
			$getProSQL .= " LIMIT ? OFFSET ?";
			$param     = [...$param, $page['limit'], $page['offset']];
			$format    .= "ii";
			
			$listProAfter = db_get($getProSQL, 1, $param, $format);

		?>
		<!-- // ===========================DANH SÁCH SẢN PHẨM==================== // -->
		<section class="product my-5 shadow">
			<h2 class="text-center mb-3"><?= isset($catInfo["cat_name"]) ? $catInfo["cat_name"] : ""; ?></h2>
			<div class="list_product_body">
				<!-- list products bar -->
				<div class="product_bar bg-info px-2 py-2 d-flex justify-content-between">
					<span class="badge  bg-faded">
						<span><?= $totalPro; ?> sản phẩm</span>
						<span>
							(
								<?php
								$start = $totalPro > 0 ? (int)$page["offset"] + 1 : 0;
								$end   = (int)$page["offset"] + (int)$page["limit"];
								$end   = $end <= $totalPro ? $end : $totalPro;
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
							<?php while ($pro = $listProAfter->fetch_assoc()): ?>

								<!-- ------------------------------------product ----------------------------------- -->
								<div class="card text-center" style="max-width: 25%;">
									<?php if ($pro['pro_qty'] == 0): ?>
										<span class="product_status badge badge-pill badge-warning">Bán hết</span>
									<?php endif ?>
									<a href='<?= create_link(base_url("product_detail.php"), ["proid"=> $pro["pro_id"]]); ?>'>
										<img src="image/<?= $pro['pro_img']; ?>" alt="" class="card-img-top">
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
										$catName = s_cell(
											"SELECT cat_name FROM db_category WHERE cat_id = ?",
											[$pro['cat_id']],
											"i"
										);
									?>
									<p class="text-uppercase card-subtitle">
										<?= $catName; ?>
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

			<!-- phân trang -->
			<div class="pt-5 pb-2">
				<?= $page['html']; ?>
			</div>
		</section>
		<!-- /product -->
	</div>
	
</main>

<?php
require_once RF . '/include/footer.php';
?>