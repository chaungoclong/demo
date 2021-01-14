<?php 
require_once 'common.php';
require_once 'include/header.php';
require_once 'include/navbar.php';
?>

<main>
	<div style="padding-left: 85px; padding-right: 85px;" class="mt-5">
		<!--Section: Block Content-->
		<div class="">
			<!-- row -->
			<div class="card-deck d-flex my-5">
				<!-- column -->
				<div class="card bg-white shadow" style="flex: 6;">
					<div class="card-header">
						<h5>THÔNG TIN THANH TOÁN</h5>
					</div>
					<div class="card-body">
						<form action="">
							<div class="form-group">
								<label for="name">
									<span><i class="fas fa-user"></i></span>
									 Tên người nhận
								</label>
								<input type="text" class="form-control" id="name" name="name">
								<div class="alert-danger" id="rcvNameErr"></div>
							</div>

							<div class="form-group">
								<label for="phone"><span><i class="fas fa-phone-alt"></i></span> Số điện thoại người nhận</label>
								<input type="text" class="form-control" id="phone" name="phone">
								<div class="alert-danger" id="rcvPhoneErr"></div>
							</div>

							<div class="form-group">
								<label for="address"><span><i class="fas fa-id-card"></i></span> Địa chỉ người nhận</label>
								<input type="text" class="form-control" id="address" name="address">
								<div class="alert-danger" id="rcvAddErr"></div>
							</div>

							<div class="form-group">
								<label for="notice"><span><i class="fas fa-clipboard"></i></span> Ghi chú</label>
								<textarea class="form-control" id="notice" name="notice"></textarea>
							</div>
						</form>
					</div>
				</div>
				<!-- /column -->

				<!-- column -->
				<div class="card bg-white shadow" style="flex: 4;">
					<div class="card-header d-flex justify-content-between align-items-center">
						<h5 class="m-0">SẢN PHẨM ĐÃ CHỌN</h5>
						<a href="<?= base_url('view_cart.php'); ?>" class="position-relative icon_cart_check">
							<span class="badge badge-pill badge-danger position-absolute">10</span>
							<i class="fas fa-shopping-cart fa-2x"></i>
						</a>
						<script>
							$('.icon_cart_check span').text($('#shoppingCartIndex').text());
						</script>
					</div>
					<div class="card-body table-responsive">
						<table class="table table-borderless table-hover pro_check_out">
							<tr style="font-size: 13px;">
								<th width="65%" colspan="2"><strong>SẢN PHẨM</strong></th>
								<th width="35%"><strong>TỔNG</strong></th>
							</tr>
							<?php if (!empty($_SESSION['cart'])): ?>

								<!-- tổng tiền -->
								<?php $total = 0; ?>

								<?php foreach ($_SESSION['cart'] as $pro_id => $qty): ?>

									<!-- lấy sản phẩm -->
									<?php
									$getOneProSQL = "SELECT * FROM db_product
									WHERE pro_id  = ?
									";
									$product = s_row($getOneProSQL, [$pro_id]);
									?>

									<!-- in sản phẩm -->
									<tr>
										<td width="20%" class="p-0 pb-1">
											<a href="
												<?= create_link(base_url('product_detail.php'), ['proid'=>$pro_id]); ?>
											">
												<img src="<?= $product['pro_img']; ?>" alt="" class="img-thumbnail" width="100%">
											</a>
										</td>

										<td width="45%" class="py-0 m-0">
											<h6 class="pro_check_name">
												<a href="  
													<?= create_link(base_url('product_detail.php'), ['proid'=>$pro_id]); ?>
												">
													<?= $product['pro_name']; ?>
												</a>
											</h6>

											<p class="p-0 m-0 ">giá: 
												<span>
													<?= number_format($product['pro_price'], 0, ",", "."); ?> &#8363;
												</span>
											</p>

											<p>số lượng:
											 <span><?= $qty; ?></span>
											</p>
									</td>

									<td width="35%">
										<span>
											<?= number_format((int)$qty * (int)$product['pro_price'], 0, ",", ".") ; ?>
											&#8363;
										</span>
									</td>
									<?php $total += (int)$qty * (int)$product['pro_price']; ?>
								</tr>
							<?php endforeach ?>

							<tr style="font-size: 14px;">
								<td colspan="3" align="right">
									TỔNG TIỀN: 
									<span><?= number_format($total, 0, ",", "."); ?> &#8363;</span>
								</td>
							</tr>
						<?php endif ?>
					</table>
				</div>
				<div class="card-footer">
					<button class="btn btn-block btn-success" id="btn_order">ĐẶT HÀNG</button>
				</div>
			</div>
			<!-- /column -->
		</div>
		<!--/ row -->
	</div>
</div>
</main>

<?php require_once 'include/footer.php'; ?>

<script>
	$(function() {
		
	});
</script>