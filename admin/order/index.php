<?php
require_once '../../common.php';
require_once '../include/header.php';

if(!is_login() || !is_admin()) {
	redirect('admin/form_login.php');
} 

require_once '../include/sidebar.php';
require_once '../include/navbar.php';

?>
<!-- main content -row -->
<div class="main_content bg-white row m-0 pt-4">
	<div class="col-12">
		<div>
			<h5>DANH SÁCH ĐƠN HÀNG</h5>
			<p class="mb-4">Dánh sách đơn hàng là nơi bạn quản lý đơn hàng</p>
			<hr>
		</div>

		<div class="row m-0 mb-3">
			<div class="col-12 p-0 d-flex justify-content-between align-items-center">

				<button class="btn btn-warning" onclick="javascript:history.go(-1)">QUAY LẠI</button>

				<div class="form-group m-0 p-0 d-flex align-items-center">
					<form action="" class="form-inline" id="search_box">
						<input 
							type        ="text" 
							name        ="q" 
							id          ="search" 
							class       ="form-control"
							placeholder ="Search..." 
							value       ="<?= $_GET['q'] ?? ""; ?>"
							>
						<button class="btn btn-outline-success">
							<i class="fas fa-search"></i>
						</button>
					</form>
				</div>
			</div>
		</div>
		<!-- lấy danh sách đơn hàng-->
		<?php

			$q = data_input(input_get('q'));
			$key = "%" . $q . "%";

			if($q != "") {
				$searchSQL = "
				SELECT db_order.*, db_customer.cus_name, db_customer.cus_address, db_customer.cus_phone
				FROM db_order JOIN db_customer
				ON db_order.cus_id = db_customer.cus_id
				WHERE 
			 		db_order.receiver_name LIKE(?)
				";

				$param = [$key];
				$listOrder = db_get($searchSQL, 1, $param, "s");
			} else {

				$listOrder = getListOrder();
			}
			

			// chia trang
			$totalOrder = $listOrder->num_rows;
			$orderPerPage = 5;
			$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
			$currentLink = create_link(base_url("admin/order/index.php"), ["page"=>'{page}', 'q'=>$q]);
			$page = paginate($currentLink, $totalOrder, $currentPage, $orderPerPage);

			// danh sách đơn hàng sau khi chia trang
			if($q != "") {
				$searchResultSQL = $searchSQL . " LIMIT ? OFFSET ?";
				$param = [$key, $page['limit'], $page['offset']];

				// danh sách  khi tìm kiếm đơn hàng sau chia chia trang
				$listOrderPaginate = db_get($searchResultSQL, 1, $param, "sii");
			} else {

				$listOrderPaginate = getListOrder($page['limit'], $page['offset']);
			}

			
			$totalOrderPaginate = $listOrderPaginate->num_rows;

			// số thứ tự
			$stt = 1 + (int)$page['offset'];
		?>
		<div class="content_table">
			<table class="table table-hover table-bordered" style="font-size: 13px;">
				<tr>
					<th>STT</th>
					<th>Mã</th>
					<th>Ngày đặt</th>
					<th>Trạng thái</th>
					<th>Người đặt</th>
					<th>Người nhận</th>
					<th>Địa chỉ giao hàng</th>
					<th>Xem</th>
					<th>Hành động</th>
				</tr>
				<!-- in các đơn hàng -->
				<?php if ($totalOrderPaginate > 0): ?>
				<?php foreach ($listOrderPaginate as $key => $order): ?>
				<tr>
					<!-- stt -->
					<td><?= $stt++; ?></td>

					<!-- mã -->
					<td><?= $order['or_id']; ?></td>

					<!-- ngày đặt -->
					<td><?= strToTimeFormat($order['or_create_at'], "H:i:s d-m-Y"); ?></td>

					<!-- trạng thái  -->
					<td id="status_order_<?= $order['or_id']; ?>">	
						<?php
							$status = $order['or_status'];
							switch ($status) {
								case 0:
									echo "đang chờ xác nhận";
									break;
								case 1:
									echo "đã xác nhận";
									break;
								case 2:
									echo "đang chờ hủy";
									break;
								case 3:
									echo "đã hủy";
									break;
								
								default:
									echo "đang chờ xác nhận";
									break;
							}
						?>
					</td>

					<!-- người đặt -->
					<td>
						<?= $order['cus_name']; ?>
					</td>

					<!-- người nhận -->
					<td>
						<?= $order['receiver_name'] ?>
					</td>

					<!-- địa chỉ giao hàng -->
					<td>
						<?= $order['receiver_add']; ?>
					</td>

					<!-- xem -->
					<td>
						<a 
						href="
							<?= 
							create_link(base_url('admin/order/order_detail.php'), ['orid'=>$order['or_id']]); 
							?>
						" 
						class="btn btn-success"
						>
							XEM
						</a>
					</td>

					<!-- hành động -->
					<td>
						<?php if ($order['or_status'] == 0): ?>
							<button 
							class="btn_confirm btn btn-primary"
							id="btn_confirm_<?= $order['or_id']; ?>"
							data-order-id="<?= $order['or_id']; ?>"
							>
							Xác nhận
							</button>
						<?php endif ?>

						<?php if ($order['or_status'] == 2): ?>
							<button 
							class="btn_cancel btn btn-primary"
							id="btn_cancel_<?= $order['or_id']; ?>"
							data-order-id="<?= $order['or_id']; ?>"
							>
							Hủy
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
</div>
</div>
<!-- /right-col -->
</div>
<!-- /wrapper -row -->
</main>
</body>
</html>

<script>
	$(function() {

		// cập nhật nội dung thẻ search
		let q = "<?= $_GET['q'] ?? ""; ?>";
		$('#search').val(q);
	
		// Thay đổi trạng thái của khách hàng
		$(document).on('click', '.btn_confirm', function() {

			// id khách hàng
			let orderID = $(this).data("order-id");

			// gửi yêu cầu thay đổi trạng thái
			let sendSwitchActive = sendAJax(
				"process_order.php",
				"post",
				"json",
				{orderID: orderID, action: "confirm"}
			);


			// làm mới trang
			let q           = "<?= $_GET['q'] ?? ""; ?>";

			let prevPage    = "<?= getCurrentURL(); ?>";
			let currentPage = <?= $currentPage ?>;
			let fetchPage = sendAJax(
				"fetch_page.php",
				"post",
				"html",
				{action: "fetch", prevPage: prevPage, q: q, currentPage: currentPage }
			);

			$('.content_table').html(fetchPage);
		});

		// Thay đổi trạng thái của khách hàng
		$(document).on('click', '.btn_cancel', function() {

			// id khách hàng
			let orderID = $(this).data("order-id");

			// gửi yêu cầu thay đổi trạng thái
			let sendSwitchActive = sendAJax(
				"process_order.php",
				"post",
				"json",
				{orderID: orderID, action: "cancel"}
			);


			// làm mới trang
			let q           = "<?= $_GET['q'] ?? ""; ?>";

			let prevPage    = "<?= getCurrentURL(); ?>";
			let currentPage = <?= $currentPage ?>;
			let fetchPage = sendAJax(
				"fetch_page.php",
				"post",
				"html",
				{action: "fetch", prevPage: prevPage, q: q, currentPage: currentPage }
			);

			$('.content_table').html(fetchPage);
		});
	});
</script>	
