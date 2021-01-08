<?php
require_once 'common.php';
require_once 'include/header.php';
require_once 'include/navbar.php';
?>
<main>
	<div class="" style="padding-left: 85px; padding-right: 85px;">
		<div class="card my-5 shadow" id="shopping-cart">
			<div class="card-header">
				<h3>Giỏ hàng</h3>
			</div>
			<div class="card-body">
				<table class="table table-hover table-borderless">
					<tr>
						<th>ID</th>
						<th class="text-center" colspan="2">SẢN PHẨM</th>
						<th class="text-center">GIÁ</th>
						<th class="text-center">SỐ LƯỢNG</th>
						<th class="text-center">TỔNG</th>
						<th class="text-center">TÙY CHỌN</th>
					</tr>
					
					<?php if (get_session("cart")): ?>
						<?php $total = 0; ?>
						<?php foreach ($_SESSION['cart'] as $pro_id => $qty): ?>
							<?php
							$getOneProSQL = "SELECT * FROM db_product
							WHERE pro_id  = ?
							";
							$product = s_row($getOneProSQL, [$pro_id]);
							?>
							<tr>
								<td><?= $product['pro_id']; ?></td>
								<td width="80px">
									<a href="">
										<img src="<?= $product['pro_img'] ?>" alt="" width="50px" class="img-thumbnail">
									</a>
								</td>
								<td>
									<h5><?= $product['pro_name']; ?></h5>
									<h6><?= $product['pro_color']; ?></h6>
								</td>
								<td class="text-center">
									<?= number_format($product['pro_price'], 0, ",", "."); ?>
								</td>
								<td class="text-center">
									<!-- ô thay đổi số lượng -->
									<input type="number" min="0" name="quantity" value="<?= $qty; ?>" class="quantity text-center" data-pro-id="<?= $product['pro_id']; ?>">
								</td>

								<td class="text-center">
									<?=number_format($product['pro_price'] * $qty, 0, ",", "."); ?>
								</td>
								<td class="text-center">
									<button class="delete btn btn-danger" id="<?= $product['pro_id']; ?>" data-pro-id="<?= $product['pro_id']; ?>">
										<i class="far fa-trash-alt"></i>
									</button>
								</td>
							</tr>
							<?php $total += $product['pro_price'] * $qty; ?>
						<?php endforeach ?>
						<tr>
							<td colspan="5" class="text-right"><strong>TOTAL:</strong></td>
							<td class="text-center">
								<?= number_format($total, 0, ",", "."); ?>
							</td>
							<td></td>
						</tr>
						<?php else: ?>
							<tr>
								<td class='text-center' colspan='7'>
									<h5>GIỎ HÀNG TRỐNG</h5>
								</td>
							</tr>
						<?php endif ?>
					</table>
				</div>

				<div class="card-footer d-flex justify-content-between">
					<button class="btn btn-danger shadow">
						<a href="<?= base_url('product.php') ?>">Mua thêm</a>
					</button>
					<button class="btn btn-warning shadow">
						<a href="<?= base_url('checkout.php') ?>">Checkout</a>
					</button>
				</div>

			</div>
			<script>
				$(function() {
				//thay đổi số lượng sản phẩm
				$(document).on('input', '.quantity', function(e) {
					let proID = $(this).data('pro-id');
					let quantity = $(this).val();
					let action = "change";
					let data = {proid:proID, quantity:quantity, action:action};
					if(proID && quantity) {
						let change_qty = $.ajax({
							url: "cart.php",
							data: data,
							method: "POST",
							dataType: "json"
						});
						//thành công
						change_qty.done(function(res) {
							console.log(res.html);
							$('#shopping-cart .card-body').html(res.html);
							if(res.totalItem > 0) {
								$('#shoppingCartIndex').text(res.totalItem);
							} else {
								$('#shoppingCartIndex').text(0);
							}
						});
						//thất bại
						change_qty.fail(function(a, b, c) {
							console.log(a, b, c);
						});
					}
				});
				//xóa sản phẩm
				$(document).on('click', '.delete', function() {
					let proID = $(this).data('pro-id');
					console.log(proID);
					let action = "delete";
					let data = {proid:proID, action:action};
					if(proID && action && confirm("bạn có muốn xóa")) {
						let del = $.ajax({
							url: "cart.php",
							method: "POST",
							data: data,
							dataType: "json"
						});
						//thành công'
						del.done(function(res) {
							console.log(res.html);
							$('#shopping-cart .card-body').html(res.html);
							if(res.totalItem > 0) {
								$('#shoppingCartIndex').text(res.totalItem);
							} else {
								$('#shoppingCartIndex').text(0);
							}
						});
						//thất bại
						del.fail(function(a, b, c) {
							console.log(a, b, c);
						});
					}
				})
			});
		</script>
	</div>
</main>
<?php require_once 'include/footer.php'; ?>