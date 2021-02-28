<?php
require_once '../common.php';

if(!is_login() || is_admin()) {
	redirect('login_form.php');
}
$user = getUserById($_SESSION['user_token']['id']);

// add file
require_once RF . '/user/include/header.php';
require_once RF . '/user/include/navbar.php';
?>
<main>
	<div class="" style="padding: 0px 85px;">

		<!-- row -->
		<div class="row m-0 py-3">
			<!-- column -->
			<?php require_once 'include/sidebar_user.php'; ?>
			<!-- /column -->

			<!-- colum -->
			<div class="col-9 bg-white py-3">
				<!-- xác thực đơn hàng có tồn tại -->
				<?php 
					$orderID = data_input(input_get('orid'));
					if(!isMyOrder($orderID, $user['cus_id'])) {
						echo "<h5 class='alert alert-danger text-center w-100'>KHÔNG CÓ ĐƠN HÀNG NÀY :(</h5>";
						return false;
					}
					$order   = getOrderByID($orderID);
				?>

				<div class="d-flex justify-content-between align-items-center">
					<a class="" onclick="window.location='<?= base_url('user/purchase.php'); ?>'" style="cursor: pointer;">
						<i class="fas fa-angle-left"></i> TRỞ LẠI
					</a>
					<span>ID ĐƠN HÀNG: <?= $orderID; ?> | 
						<span class="text-warning">
							<?php 
							$showStatus = "";
							switch ($order['or_status']) {
								case '0':
								$showStatus = "ĐANG CHỜ DUYỆT";
								break;
								case '1':
								$showStatus = "ĐÃ DUYỆT";
								break;
								case '2':
								$showStatus = "ĐÃ HỦY";
								break;
								default:
								$showStatus = "KHÔNG XÁC ĐỊNH";
								break;
							}
							echo $showStatus;
							?>
						</span>
					</span>
				</div>
				<hr class="my-1">

				<div class="content_table">
					<div class="card-deck mb-4 mt-3">
						<div class="card">
							<div class="card-header">
								ĐƠN HÀNG
							</div>
							<div class="card-body">
								<p><strong>ID đơn hàng:</strong> <?= $order['or_id']; ?></p>
								<p><strong>Ngày đặt:</strong> <?= strToTimeFormat($order['or_create_at'], "H:i:s d-m-Y"); ?></p>
								<p><strong>Trạng thái:</strong> <?= $showStatus; ?></p>
							</div>
						</div>
						<div class="card">
							<div class="card-header">
								NGƯỜI ĐẶT
							</div>
							<div class="card-body">
								<p><strong>Tên:</strong> <?= $order['cus_name']; ?></p>
								<p><strong>Địa chỉ:</strong> <?= $order['cus_address']; ?></p>
								<p><strong>SĐT:</strong> <?= $order['cus_phone']; ?></p>
							</div>
						</div>
						<div class="card">
							<div class="card-header">
								NGƯỜI NHẬN
							</div>
							<div class="card-body">
								<p><strong>Tên:</strong> <?= $order['receiver_name']; ?></p>
								<p><strong>Địa chỉ:</strong> <?= $order['receiver_add']; ?></p>
								<p><strong>SĐT:</strong> <?= $order['receiver_phone']; ?></p>
							</div>
						</div>
						<div class="card">
							<div class="card-header">
								GHI CHÚ
							</div>
							<div class="card-body">
								<p><?= $order['or_notice']; ?></p>
							</div>
						</div>
					</div>
					<!-- <hr style="border-top: 2px dashed;"> -->
					<!-- CHI TIẾT ĐƠN HÀNG -->
					<?php 
					$orderDetail = getOrderDetailByID($orderID);
					$totalOrder = $orderDetail->num_rows;
					$totalMoney = 0;
					?>
					<div>
						<table class="table table-hover  shadow bg-white" style="font-size: 15px;">
							<thead class="thead-light">
								<tr>
									<th class="align-middle">ID SẢN PHẨM</th>
									<th class="align-middle" colspan="2">TÊN SẢN PHẨM</th>
									<th class="align-middle" width="15%">ĐƠN GIÁ</th>
									<th class="align-middle" width="10%">SỐ LƯỢNG</th>
									<th class="align-middle" width="15%">THÀNH TIỀN</th>
								</tr>
							</thead>

							<tbody>
								<?php foreach ($orderDetail as $key => $product): ?>
									<tr>
										<td class="align-middle"><?= $product['pro_id']; ?></td>

										<td class="align-middle" width="10%">
											<a href="<?= create_link(base_url('product_detail.php'), ["proid"=>$product['pro_id']]); ?>">
												<img src="../image/<?= $product['pro_img']; ?>" alt="" class="img-thumbnail">
											</a>
										</td>

										<td class="align-middle">
											<a href="<?= create_link(base_url('product_detail.php'), ["proid"=>$product['pro_id']]); ?>">
												<span><?= $product['pro_name']; ?></span>
											</a>

										</td>

										<td class="align-middle"><?= number_format($product['price'], 0, ",", "."); ?> &#8363;</td>

										<td class="align-middle"><?= $product['amount']; ?></td>

										<td class="align-middle">
											<?= number_format($product['price'] * $product['amount'], 0, ",", "."); ?> &#8363;
										</td>
									</tr>
									<?php $totalMoney += $product['price'] * $product['amount']; ?>
								<?php endforeach ?>
								<tr>
									<td class="align-middle" colspan="5" align="right">TỔNG:</td>
									<td class="text-danger align-middle"><?= number_format($totalMoney, 0, ",", "."); ?> &#8363;</td>
								</tr>
								<!-- in các đơn hàng -->
							</tbody>

						</table>
					</div>
				</div>

				<!-- CHI TIẾT ĐƠN HÀNG -->

			</div>
			
			<!-- /column -->
		</div>
		<!-- /row -->
	</div>
</main>
<?php
require_once RF . '/user/include/footer.php';
?>

