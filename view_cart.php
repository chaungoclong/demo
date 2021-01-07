<?php 
require_once 'common.php';
require_once 'include/header.php';
require_once 'include/navbar.php';
?>

<main>
	<div class="" style="padding-left: 85px; padding-right: 85px;" id="shopping-cart">
		<table class="table table-sm">
			<tr>
				<th>ID</th>
				<th>Tên sản phẩm</th>
				<th>Ảnh</th>
				<th>Số lượng</th>
				<th>Giá</th>
				<th>Tổng</th>
				<th>Xóa</th>
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
						<td><?= $product['pro_name']; ?></td>
						<td>
							<img src="<?= $product['pro_img'] ?>" alt="" width="50px">
						</td>
						<td>
						<!-- ô thay đổi số lượng -->
							<input type="number" min="0" name="quantity" value="<?= $qty ?? 0; ?>" class="quantity text-center" data-pro-id="<?= $product['pro_id']; ?>">
						</td>
					<td><?= number_format($product['pro_price'], 0, ",", "."); ?></td>
					<td><?=number_format($product['pro_price'] * $qty, 0, ",", "."); ?></td>
					<td>
						<button class="delete btn btn-danger" id="<?= $product['pro_id']; ?>" data-pro-id="<?= $product['pro_id']; ?>">Xóa</button>
					</td>
				</tr>
				<?php $total += $product['pro_price'] * $qty; ?>
			<?php endforeach ?>
			<tr>
				<td>Total</td>
				<td colspan="6"><?= number_format($total, 0, ",", "."); ?></td>
			</tr>

		<?php endif ?>

	</table>

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
							$('#shopping-cart').html(res.html);
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

					if(proID && action) {
						let del = $.ajax({
							url: "cart.php",
							method: "POST",
							data: data,
							dataType: "json"
						});

						//thành công'
						del.done(function(res) {
							console.log(res.html);
							$('#shopping-cart').html(res.html);
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