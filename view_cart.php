<?php
require_once 'common.php';
require_once 'include/header.php';
require_once 'include/navbar.php';
?>
<main>
	<div class="" style="padding-left: 85px; padding-right: 85px;">
		<div class="card my-5 shadow" id="shopping-cart">
			<div class="card-header text-center">
				<h3><strong>GIỎ HÀNG</strong></h3>
			</div>
			<div class="card-body">
				<table class="cart_table table table-hover table-borderless">
					<tr class="cart_table_title bg-info">
						<th width="5%"><strong>ID</strong></th>
						<th width="50%"  colspan="2"><strong>SẢN PHẨM</strong></th>
						<th width="10%" ><strong>GIÁ</strong></th>
						<th width="10%" ><strong>SỐ LƯỢNG</strong></th>
						<th width="15%" ><strong>TỔNG</strong></th>
						<th width="10%" class="text-center" ><strong>TÙY CHỌN</strong></th>
					</tr>
					<!-- 
						/**
						 * #nếu tồn tại giỏ hàng: lặp in ra các sản phẩm
						 * $_SESSION['cart'] là 1 mảng 1 chiều với key = pro_id, value = số lượng sản phẩm
						 * có id = pro_id
						 */
					 -->
					<?php if (!empty($_SESSION['cart'])): ?>
						<?php $total = 0; ?>
						<?php foreach ($_SESSION['cart'] as $pro_id => $qty): ?>
							<?php
							$getOneProSQL = "SELECT * FROM db_product
							WHERE pro_id  = ?
							";
							$product = s_row($getOneProSQL, [$pro_id]);
							?>
							<tr class="cart_table_body">
								<td><?= $product['pro_id']; ?></td>
								<td width="8%">
									<a href="
									<?=
									create_link(
									base_url('product_detail.php'), 
									['proid' => $product['pro_id']]
									); 
									?>
									">
									<img src="<?= $product['pro_img'] ?>" alt="" width="100%" class="img-thumbnail">
								</a>
							</td>
							<td>
								<h5>
									<a href=" 
									<?=
									create_link(
									base_url('product_detail.php'), 
									['proid' => $product['pro_id']]
									); 
									?> 
									">
									<?= $product['pro_name']; ?>
								</a>
							</h5>
							<h6><?= $product['pro_color']; ?></h6>
						</td>
						<td>
							<?= number_format($product['pro_price'], 0, ",", "."); ?>
							<span class="unit">&#8363;</span>
						</td>
						<td>
							<!-- ô thay đổi số lượng -->
							<select name="quantity" value="<?= $qty; ?>" class="quantity text-center" data-pro-id="<?= $product['pro_id']; ?>">
								<?php 
									/**
									 * #Giới hạn số lượng sản phẩm được
									 * @var $limit: giới hạn
									 * #Giới hạn sản phẩm được chọn > số lượng sản phẩm hiện tại
									 * => giới hạn = số lượng sản phẩm hiện tại , ngược lại
									 * #In lần lượt các option từ 1 -> $limit
									 * #nếu option có giá trị = số lượng của sản phẩm trong giỏ hàng
									 * =>selected option đó
									 * 
									 */
									$limit = 10;
									$limit = ($limit > $product['pro_qty']) ? $product['pro_qty'] : $limit;
									for ($i = 1; $i <= $limit ; $i++) { 
										if($i == $qty) {
											echo "	 
												<option value='$i' selected>$i</option>
											";
										} else {
											echo "	 
												<option value='$i'>$i</option>
											";
										}
									}
								 ?>
							</select>
						</td>

						<td>
							<?=number_format($product['pro_price'] * $qty, 0, ",", "."); ?>
							<span class="unit">&#8363;</span>
						</td>
						<td class="text-center">
							<button class="delete btn btn-danger" id="<?= $product['pro_id']; ?>" data-pro-id="<?= $product['pro_id']; ?>">
								<i class="far fa-trash-alt"></i>
							</button>
						</td>
					</tr>
					<?php $total += $product['pro_price'] * $qty; ?>
				<?php endforeach ?>

				<tr class="all_total">
					<td colspan="5" class="text-right"><strong>TỔNG SỐ LƯỢNG:</strong></td>
					<td id="totalItem"></td>
					<td></td>
				</tr>
				<tr class="all_total">
					<td colspan="5" class="text-right"><strong>TỔNG TIỀN:</strong></td>
					<td>
						<?= number_format($total, 0, ",", "."); ?>
						<span class="unit">&#8363;</span>
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
			<a class="btn_buy_more btn btn-danger" href="<?= base_url('product.php') ?>">
				<strong>MUA THÊM</strong>
			</a>
			<?php if (!empty($_SESSION['cart'])): ?>
				<a class="btn_check_out btn btn-warning" href="<?= base_url('checkout.php') ?>">
					<strong>CHECK OUT</strong>
				</a>
			<?php endif ?>
		</div>

	</div>
	<script>
		$(function() {
			$('#totalItem').text($('#shoppingCartIndex').text() + "sản phẩm");
			//thay đổi số lượng sản phẩm
			$(document).on('change', '.quantity', function(e) {
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
								$('.btn_check_out').show();
							} else {
								$('#shoppingCartIndex').text(0);
								$('.btn_check_out').hide();
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