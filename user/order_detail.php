<?php
require_once '../common.php';
!is_login() || is_admin() && redirect('login_form.php');
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
				<div>
					<h5>ĐƠN HÀNG CHI TIẾT</h5>
					<p class="mb-4">Đơn hàng chi tiết là nơi bạn xem chi tiết đơn hàng</p>
					<hr>
				</div>
				<!-- lấy đơn hàng -->
				<?php 
					$orderID = data_input(input_get('orid'));
					if(!isMyOrder($orderID, $user['cus_id'])) {
						echo "<h5 class='alert alert-danger text-center'>KHÔNG CÓ ĐƠN HÀNG NÀY</h5>";
						return false;
					}
					$order   = getOrderByID($orderID);
				 ?>

				<div>
					<!-- THÔNG TIN CHUNG -->
					<table class="table table-hover table-sm table-borderless" style="font-size: 13px;">
						<tr>
							<td width="200px"><strong>MÃ ĐƠN HÀNG:</strong></td>
							<td><?= $orderID; ?></td>
						</tr>
						<tr>
							<td><strong>TÌNH TRẠNG ĐƠN HÀNG:</strong></td>
							<td>	
								<?php
									$status = $order['or_status'];
									switch ($status) {
										case '0':
											echo "đang chờ xác nhận";
											break;
										case '1':
											echo "đã xác nhận";
											break;
										case '2':
											echo "đang chờ hủy";
											break;
										case '3':
											echo "đã hủy";
											break;
										
										default:
											echo "đang chờ xác nhận";
											break;
									}
								?>
							</td>
						</tr>
						<tr>
							<td><strong>NGƯỜI ĐẶT:</strong></td>
							<td><?= $order['cus_name']; ?></td>
						</tr>
						<tr>
							<td><strong>NGƯỜI NHẬN:</strong></td>
							<td><?= $order['receiver_name']; ?></td>
						</tr>
						<tr>
							<td><strong>ĐỊA CHỈ NHẬN HÀNG:</strong></td>
							<td><?= $order['receiver_add']; ?></td>
						</tr>
						<tr>
							<td><strong>SĐT NGƯỜI NHẬN:</strong></td>
							<td><?= $order['receiver_phone']; ?></td>
						</tr>
						<tr>
							<td><strong>NGÀY ĐẶT:</strong></td>
							<td><?= strToTimeFormat($order['or_create_at'], "H:i:s d-m-Y"); ?></td>
						</tr>
						<tr>
							<td><strong>GHI CHÚ:</strong></td>
							<td><?= $order['or_notice']; ?></td>
						</tr>
					</table>
				</div>
				<!-- /THÔNG TIN CHUNG -->

				<!-- CHI TIẾT ĐƠN HÀNG -->
				<?php 
					$orderDetail = getOrderDetailByID($orderID);
					$totalOrder = $orderDetail->num_rows;
					$totalMoney = 0;
				?>
				<div>
					<table class="table table-hover table-bordered" style="font-size: 13px;">
						<tr>
							<th>STT</th>
							<th>Mã sản phẩm</th>
							<th colspan="2">Tên sản phẩm</th>
							<th width="15%">Đơn giá</th>
							<th width="10%">Số lượng</th>
							<th width="15%">Thành tiền</th>
						</tr>

						<?php foreach ($orderDetail as $key => $product): ?>
							<tr>
								<td><?= ++$key; ?></td>
								<td><?= $product['pro_id']; ?></td>
								<td width="10%">
									<a href="<?= create_link(base_url('product_detail.php'), ["proid"=>$product['pro_id']]); ?>">
										<img src="../image/<?= $product['pro_img']; ?>" alt="" class="img-thumbnail">
									</a>
								</td>
								<td>
									<a href="<?= create_link(base_url('product_detail.php'), ["proid"=>$product['pro_id']]); ?>">
										<p><?= $product['pro_name']; ?></p>
									</a>
									
								</td>
								<td><?= number_format($product['price'], 0, ",", "."); ?> &#8363;</td>
								<td><?= $product['amount']; ?></td>
								<td>
									<?= number_format($product['price'] * $product['amount'], 0, ",", "."); ?> &#8363;
								</td>
							</tr>
							<?php $totalMoney += $product['price'] * $product['amount']; ?>
						<?php endforeach ?>
						<tr>
							<td colspan="6" align="right">TỔNG:</td>
							<td><?= number_format($totalMoney, 0, ",", "."); ?> &#8363;</td>
						</tr>
						<!-- in các đơn hàng -->
						
					</table>
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

