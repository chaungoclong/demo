<?php
require_once '../common.php';

//check is login
if(!is_login()) {
	redirect("login_form.php");
} else {
	$user = getUserById($_SESSION['user_token']['id']);
}

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
					<h5>ĐƠN HÀNG</h5>
					<p class="mb-4">Đơn hàng là nơi bạn kiểm tra đơn hàng</p>
					<hr>
				</div>
				<!-- lấy đơn hàng -->
				<?php 
					$listOrder = getOrderByUser($user['cus_id']);
					$totalOder = $listOrder->num_rows;
					$stt = 1;
				 ?>
				<div>
					<table class="table table-hover table-bordered" style="font-size: 13px;">
						<tr>
							<th>STT</th>
							<th>Mã đơn hàng</th>
							<th>Tên khách hàng</th>
							<th>Địa chỉ</th>
							<th>Ngày đặt</th>
							<th>Trạng thái</th>
							<th>Chi tiết</th>
							<th>Hủy đơn</th>
						</tr>

						<!-- in các đơn hàng -->
						<?php if ($totalOder > 0): ?>
							<?php foreach ($listOrder as $key => $order): ?>
								<tr>
									<td><?= ++$key; ?></td>
									<td><?= $order['or_id']; ?></td>
									<td><?= $order['cus_name']; ?></td>
									<td><?= $order['cus_address']; ?></td>
									<td><?= strToTimeFormat($order['or_create_at'], "H:i:s d-m-Y"); ?></td>
									<td>	
										<?php
											$status = $order['or_status'];
											switch ($status) {
												case '0':
													echo "đang chờ hủy";
													break;
												case '1':
													echo "đang chờ xác nhận";
													break;
												case '2':
													echo "đã xác nhận";
													break;
												case '3':
													echo "đang giao hàng";
													break;
												case '4':
													echo "đã giao hàng";
													break;
												case '5':
													echo "đã hủy";
													break;
												
												default:
													echo "đang chờ xác nhận";
													break;
											}
										?>
									</td>
									<td>
										<a href="<?= create_link(base_url("user/order_detail.php"), ["orid"=>$order['or_id']]); ?>"
											class="btn btn-success">
											XEM
										</a>
									</td>
									<td>
										<?php if ($order['or_status'] == 0 || $order['or_status'] == 4 || $order['or_status'] == 5): ?>
											<button class="btn btn-danger" disabled="">HỦY</button>
										<?php else: ?>
											<button class="btn btn-danger" data-order-id="<?= $order['or_id']; ?>">HỦY</button>
										<?php endif ?>
									</td>	
								</tr>
								
							<?php endforeach ?>
						<?php endif ?>
					</table>
				</div>
			</div>
			
			<!-- /column -->
		</div>
		<!-- /row -->
	</div>
</main>
<?php
require_once RF . '/user/include/footer.php';
?>
