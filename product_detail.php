<?php
require_once 'common.php';
require_once 'include/header.php';
require_once 'include/navbar.php';
?>
<main>
	<!-- In thông tin sản phẩm có id = id nhận được -->
	<?php
		//ID nhận được
	$proID = data_input(input_get("proid"));
		//lấy sản phẩm có ID nhận được
	$getOneProSQL = "SELECT * FROM db_product WHERE pro_id = ?";
	$product = s_row($getOneProSQL, [$proID]);
	
	//lấy các ảnh của sản phẩm, thể loại, hãng
	if(!empty($product)) {
		$getImgProSQL = "SELECT img_url FROM db_image WHERE pro_id = ? LIMIT 4";
		$listImg = db_get($getImgProSQL, [$proID]);
		
		$getCatProSQL = "SELECT cat_name FROM db_category WHERE cat_id = ?";
		$category = s_cell($getCatProSQL, [$product['cat_id']]);
		
		$getBraProSQL = "SELECT bra_name FROM db_brand WHERE bra_id = ?";
		$brand = s_cell($getBraProSQL, [$product['bra_id']]);
	}
	?>
	
	<div style="padding-left: 85px; padding-right: 85px;" class="">
		<h1 class="text-center mt-3">PRODUCT DETAIL</h1>
		<section>
			<div class="row p-0 m-0 bg-light">
				<!-- product image wrapper -->
				<div id="product_image" class="col-6 p-3">
					<!-- product image content -->
					<div class="card text-center py-3 h-100">
						<div class="row p-0 m-0">
							<div class="col-12 mb-3">
								<img src="<?= $product['pro_img']; ?>" alt="" class="big_img card-img-top w-75" style="border-radius: 5px;">
							</div>
							<div class="col-12">
								<div class="row">
									<?php if (!empty($listImg)): ?>
										<?php foreach ($listImg as $key => $img): ?>
											<div class="col-3">
												<img src="<?= $img['img_url']; ?>" class="small_img img-fluid" alt="" style="border-radius: 5px;">
											</div>
										<?php endforeach ?>
									<?php endif ?>
								</div>
							</div>
						</div>
					</div>
					<!-- /product image content -->
				</div>
				<!-- /product image wrapper -->
				<div class="col-6 p-3">
					<div class="card h-100">
						<div id="notice" class="card-header text-center"></div>
						<div class="card-body">
							<!-- product name -->
							<h2 style="color:blue;" class="card-title">
								<strong>
									<?= ucwords(strtolower($product['pro_name'])); ?>
								</strong>
							</h2>
							<hr>
							<h3 class="card-text my-4" style="color:red;">
								<strong>
									<?= !empty($product['pro_price']) ?
									number_format($product['pro_price'], 0, ",", ".") : ""; ?>
									&#8363;
								</strong>
							</h3>
							<div class="table-responsive mb-3">
								<table class="table table-sm table-borderless mb-0">
									<tr>
										<th class="pl-0 w-25" scope="row"><strong>Loại sản phẩm:</strong></th>
										<td><?= !empty($category) ? $category : ""; ?></td>
									</tr>
									<tr>
										<th class="pl-0 w-25" scope="row"><strong>Hãng sản xuất:</strong></th>
										<td><?= !empty($brand) ? $brand : ""; ?></td>
									</tr>
									<tr>
										<th class="pl-0 w-25" scope="row"><strong>Màu:</strong></th>
										<td>
											<?= !empty($product['pro_color']) ?
											$product['pro_color'] : ""; ?>
										</td>
									</tr>
									<tr>
										<th class="pl-0 w-25" scope="row"><strong>Tình trạng:</strong></th>
										<td>
											<?= $product['pro_qty'] ?
											"còn hàng(" . $product['pro_qty'] . " sản phẩm)": "hết hàng"; ?>
										</td>
									</tr>
								</table>
							</div>
							<hr>
							<p>
								<?= !empty($product['pro_short_desc']) ?
								$product['pro_short_desc'] : ""; ?>
							</p>
							<hr>
							<div class="action border-1">
								<div class="get_quantity d-flex mb-3">
									<button class="minus btn"><i class="fas fa-minus"></i></button>
									<input type="number" min="0" name="quantity" value="1" class="quantity text-center">
									<button class="plus btn"><i class="fas fa-plus"></i></button>
								</div>
								<button class="btn_add_cart btn btn-primary"><strong>THÊM VÀO GIỎ</strong></button>
								<button class="btn_wishlist btn btn-danger"><strong><i class="fas fa-heart"></i></strong></button>
							</div>
							<script>
								$(function() {
									//send cart
									$('.btn_add_cart').on('click', function() {
										let proID     = <?php echo input_get("proid"); ?>;
										let qtyPro    = parseInt(<?php echo $product['pro_qty']; ?>);
										let qtySelect = parseInt($('.quantity').val());
										//console.log(qtySelected);

										if(isNaN(qtyPro) || qtyPro <= 0) {
											alert("HẾT HÀNG");
										} else if(isNaN(qtySelect) || qtySelect <= 0) {
											alert("VUI LÒNG CHỌN ÍT NHẤT 1 SẢN PHẨM");
										} else if(qtySelect > qtyPro) {
											alert("SỐ LƯỢNG SẢN PHẨM KHÔNG ĐỦ");
										} else {
											let action = "add";
											let data = {proid:proID, quantity:qtySelect, action:action};
											let sendCart = $.ajax({
												url: "cart.php",
												data: data,
												method: "POST",
												dataType: "json"
											});

											//success
											sendCart.done((res) => {
												$('#notice').html(res.notice);
												if(res.totalItem > 0) {
													$('#shoppingCartIndex').text(res.totalItem);
												} else {
													$('#shoppingCartIndex').text(0);
												}
											});

											//error
											sendCart.fail((a, b, c) => {
												console.log(a, b, c);
											});
										}
									});
								});
							</script>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- tab info -->
		<section id="tab_info">
			<!-- tab index -->
			<ul class="nav nav-justified nav-tabs">
				<li class="nav-item">
					<a href="#desc" class="nav-link active" data-toggle="tab">MÔ TẢ</a>
				</li>
				<li class="nav-item">
					<a href="#info" class="nav-link" data-toggle="tab">THÔNG SỐ KỸ THUẬT</a>
				</li>
				<li class="nav-item">
					<a href="#review" class="nav-link" data-toggle="tab">ĐÁNH GIÁ</a>
				</li>
			</ul>
			<!-- tab content -->
			<div class="tab-content">
				<div id="desc" class="active tab-pane bg-light">
					<h1>MÔ TẢ</h1>
					<?= !empty($product['pro_desc']) ? $product['pro_desc'] : "";  ?>
				</div>
				<div id="info" class="tab-pane fade bg-light">
					<h1>THÔNG SỐ KỸ THUẬT</h1>
				</div>
				<div id="review" class="tab-pane fade bg-light">
					<h1>ĐÁNH GIÁ</h1>
				</div>
			</div>
		</section>
		<!-- /tab info -->
		<!-- product -->
		<?php
		$getRelatedProSQL = "
		SELECT * FROM db_product
		WHERE cat_id = ? AND pro_id != ?
		";
		$listRelatedPro = db_get(
			$getRelatedProSQL,
			[$product['cat_id'], $product['pro_id']]
		);
		//vd($listRelatedPro);
		?>
		<section class="product py-5">
			<h2 class="text-center mb-3">SẢN PHẨM LIÊN QUAN</h2>
			<div class="list_product_body">
				<!-- list products bar -->
				<div class="product_bar bg-info px-2 py-2 d-flex justify-content-between">
					<span class="badge  bg-faded">
						<?= !empty($listRelatedPro) ? count($listRelatedPro) : "0"; ?>
						sản phẩm liên quan
					</span>
					<a href="
					<?php
					echo create_link(
					base_url("product.php"),
					['cat' => $product['cat_id']]
					);
					?>
					"
					class="badge badge-pill bg-danger">Xem tất cả</a>
				</div>
				<!-- list products -->
				<div class="card-group">
					<?php if (!empty($listRelatedPro)): ?>
						<?php
						$limit = 4;
						$count = 0;
						?>
						<?php foreach ($listRelatedPro as $key => $relatedPro): ?>
							<div class="card text-center" style="max-width: 25%;">
								<?php if (empty($relatedPro['pro_qty'])): ?>
									<span class="product_status badge badge-pill badge-warning">
										bán hết
									</span>
								<?php endif ?>
								<a href="
								<?php
								echo create_link(
								base_url("product_detail.php"),
								['proid' => $relatedPro['pro_id']]
								);
								?>
								">
								<img src="<?= $relatedPro['pro_img']; ?>" alt="" class="card-img-top">
							</a>
							<div class="card-body">
								<h5 class="card-title">
									<a href="
									<?php
									echo create_link(
									base_url("product_detail.php"),
									["proid" => $relatedPro['pro_id']]
									);
									?>
									">
									<?= $relatedPro['pro_name']; ?>
								</a>
							</h5>
							<p class="text-uppercase">
								<?= $category; ?>
							</p>
							<h6 class="text-danger">
								<?= number_format($relatedPro['pro_price'], 0, ",", "."); ?>
								&#8363;
							</h6>
							<hr>
							<?php if (!empty($relatedPro['pro_qty'])): ?>
								<a href="" class="btn btn-default btn-success">Add to card</a>
							<?php endif ?>
							<a href="
							<?php
							echo create_link(
							base_url("product_detail.php"),
							["proid" => $relatedPro['pro_id']]
							);
							?>
							"
							class="btn btn-default btn-primary">Detail</a>
							<a href="
							<?php
							echo create_link(
							base_url("wishlist.php"),
							["proid" => $relatedPro['pro_id']]
							);
							?>
							" class="btn btn-default btn-danger"><i class="far fa-heart"></i></a>
						</div>
					</div>
					<?php
					++$count;
					if($count === $limit) {
						break;
					}
					?>
				<?php endforeach ?>
			<?php endif ?>
		</div>
	</div>
</section>
<!-- /product -->
</div>
</main>
<?php require_once 'include/footer.php'; ?>