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

					// chia trang
					$totalOrder = $listOrder->num_rows;
					$orderPerPage = 5;
					$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
					$currentLink = create_link(base_url("user/purchase.php"), ["page"=>'{page}']);
					$page = paginate($currentLink, $totalOrder, $currentPage, $orderPerPage);

					//đơn hàng sau khi chia trang
					$listOrderPaginate = getOrderByUser($user['cus_id'], $page['limit'], $page['offset']);
					$totalOrderPaginate = $listOrderPaginate->num_rows;

					// số thứ tự
					$stt = 1 + (int)$page['offset'];
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
						<?php if ($totalOrderPaginate > 0): ?>
							<?php foreach ($listOrderPaginate as $key => $order): ?>
								<tr>
									<td><?= $stt++; ?></td>
									<td><?= $order['or_id']; ?></td>
									<td><?= $order['cus_name']; ?></td>
									<td><?= $order['cus_address']; ?></td>
									<td><?= strToTimeFormat($order['or_create_at'], "H:i:s d-m-Y"); ?></td>
									<td id="status_order_<?= $order['or_id']; ?>">	
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
											<button class="btn_cancel btn btn-danger" data-order-id="<?= $order['or_id']; ?>" 
												id="btn_cancel_<?= $order['or_id']; ?>">
												HỦY
											</button>
										<?php endif ?>
									</td>	
								</tr>
								
							<?php endforeach ?>
						<?php endif ?>
					</table>
					<?php echo $page['html']; ?>
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
<script>
	$(function() {
		$(document).on('click', '.btn_cancel', function() {
			let orderID = $(this).data("order-id");
			console.log(orderID);
			let sendCancel = sendAJax(
				"process_cancel_order.php",
				"post",
				"json",
				{orderID: orderID}
			)

			if(sendCancel.status == 1) {
				alert("THIẾU DỮ LIỆU");
				$("#btn_cancel_" + $orderID).prop("disabled", false);
			}

			if(sendCancel.status == 5) {
				$orderID = sendCancel.orID;
				$("#btn_cancel_" + $orderID).prop("disabled", true);
				$("#status_order_" + $orderID).text("đang chờ hủy");
			}
		});
	});
</script>	
