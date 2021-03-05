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
		<!-- tiêu đề -->
		<div class="d-flex justify-content-between align-items-center mb-2">
			<a class="" onclick="window.location='<?= base_url('admin/'); ?>'" style="cursor: pointer;">
				<i class="fas fa-angle-left"></i> TRỞ LẠI
			</a>
		</div>

		<!-- thanh tìm kiếm -->
		<div class="row m-0 mb-3">
			<div class="col-12 p-2 d-flex justify-content-between align-items-center bg-light border">
				<!-- lọc-->
				<div class="filter d-flex">
					<!-- sắp xếp -->
					<select id="sort" class="custom-select mr-3">
						<option value="1" selected>Ngày tạo: Mới nhất</option>
						<option value="2">Ngày tạo: Cũ nhất</option>
					</select>

					<!-- tìm kiếm tên , id đơn hàng -->
					<input type="text" class="form-control" id="search" placeholder="Search...">
				</div>

				<!-- số hàng hiển thị -->
				<div class="d-flex align-items-center">
					<i class="far fa-file-excel fa-2x text-success mr-3" style="" onclick="window.location='export_file.php'" data-toggle="tooltip" title="xuất file excel"></i>

					<?php $option = [5, 10, 25, 50, 100]; ?>
					<select class="custom-select" id="number_of_rows" data-toggle="tooltip" title="số hàng hiển thị">
						<?php foreach ($option as $key => $each): ?>
							<option value="<?= $each; ?>"> <?= $each; ?> </option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
		</div>

		<ul class="nav nav-tabs px-2" role="tablist" id="list_name_tab">
			<li class="nav-item">
				<a class="nav-link active" data-toggle="tab" href="#all" data-status="all">TẤT CẢ <span class="badge badge-secondary" id="count_all">10</span></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#pending" data-status="pending">ĐANG CHỜ <span class="badge badge-primary" id="count_pending">10</span></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#success" data-status="success">ĐÃ XỬ LÝ <span class="badge badge-success" id="count_success">10</span></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#fail" data-status="fail">ĐÃ HỦY <span class="badge badge-danger" id="count_fail">10</span></a>
			</li>
		</ul>

		<!-- Tab panes -->
		<div class="tab-content">
			<div class="tab-pane p-2 active" id="all">
				<table class="table table-hover table-bordered" style="font-size: 15px;">
					<thead class="thead-light">
						<tr>
							<th class="align-middle">ID</th>
							<th class="align-middle">NGÀY ĐẶT</th>
							<th class="align-middle">TỔNG TIỀN</th>
							<th class="align-middle">TRẠNG THÁI</th>
							<th class="align-middle">NGƯỜI ĐẶT</th>
							<th class="align-middle">NGƯỜI NHẬN</th>
							<th class="align-middle">XEM</th>
							<th class="align-middle">TÙY CHỌN</th>
						</tr>
					</thead>

					<tbody class="list_order">
					</tbody>
				</table>
				<div class="page"></div>
			</div>

			<!-- ĐANG CHỜ -->
			<div class="tab-pane p-2" id="pending">
				<table class="table table-hover table-bordered" style="font-size: 15px;">
					<thead class="thead-light">
						<tr>
							<th class="align-middle">ID</th>
							<th class="align-middle">NGÀY ĐẶT</th>
							<th class="align-middle">TỔNG TIỀN</th>
							<th class="align-middle">TRẠNG THÁI</th>
							<th class="align-middle">NGƯỜI ĐẶT</th>
							<th class="align-middle">NGƯỜI NHẬN</th>
							<th class="align-middle">XEM</th>
							<th class="align-middle" width="115px">HÀNH ĐỘNG</th>
						</tr>
					</thead>

					<tbody class="list_order">
					</tbody>
				</table>
				<div class="page"></div>
			</div>

			<!-- ĐÃ XỬ LÝ -->
			<div class="tab-pane p-2" id="success">
				<table class="table table-hover table-bordered" style="font-size: 15px;">
					<thead class="thead-light">
						<tr>
							<th class="align-middle">ID</th>
							<th class="align-middle">NGÀY ĐẶT</th>
							<th class="align-middle">TỔNG TIỀN</th>
							<th class="align-middle">TRẠNG THÁI</th>
							<th class="align-middle">NGƯỜI ĐẶT</th>
							<th class="align-middle">NGƯỜI NHẬN</th>
							<th class="align-middle">XEM</th>
							<th class="align-middle">TÙY CHỌN</th>
						</tr>
					</thead>

					<tbody class="list_order">
					</tbody>
				</table>
				<div class="page"></div>
			</div>

			<!-- ĐÃ HỦY -->
			<div class="tab-pane p-2" id="fail">
				<table class="table table-hover table-bordered" style="font-size: 15px;">
					<thead class="thead-light">
						<tr>
							<th class="align-middle">ID</th>
							<th class="align-middle">NGÀY ĐẶT</th>
							<th class="align-middle">TỔNG TIỀN</th>
							<th class="align-middle">TRẠNG THÁI</th>
							<th class="align-middle">NGƯỜI ĐẶT</th>
							<th class="align-middle">NGƯỜI NHẬN</th>
							<th class="align-middle">XEM</th>
							<th class="align-middle">TÙY CHỌN</th>
						</tr>
					</thead>

					<tbody class="list_order">
					</tbody>
				</table>
				<div class="page"></div>
			</div>
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
		channel.bind('check_out', function(data) {
			fetchPage(1);
		});

		channel.bind('cancel_order', function(data) {
			fetchPage(1);
		});

		fetchPage(1);

		// lấy danh sách đơn hàng khi nhập tìm kiếm
		$(document).on('input', '#search', function() {
			fetchPage(1);
		});

		// lấy danh sách đơn hàng khi sắp xếp
		$(document).on('change', '#sort', function() {
			fetchPage(1);
		});

		// lấy danh sách đơn hàng khi thay đổi số hàng hiển thị
		$(document).on('change', '#number_of_rows', function() {
			fetchPage(1);
		});

		// lấy danh sách đơn hàng khi chuyển tab
		$(document).on('click', '#list_name_tab .nav-item', function() {
			fetchPage(1);
		});

		// lấy danh sách đơn hàng khi chuyển trang
		$(document).on('click', '.page-item', function() {
			let currentPage = parseInt($(this).data("page-number"));
			if(isNaN(currentPage)) {
				currentPage = 1;
			}
			fetchPage(currentPage);
			$('html, body').scrollTop(0);
		});

		// duyệt 1 đơn hàng
		$(document).on('click', '.btn_confirm', function() {
			changeStatus(this.id, "confirm");
			sendEmailWhenChangeStatusOrder($(this).data('order-id'), 'email_ad_confirm_order');
		});

		// hủy 1 đơn hàng
		$(document).on('click', '.btn_cancel', function() {
			changeStatus(this.id, "cancel");
			sendEmailWhenChangeStatusOrder($(this).data('order-id'), 'email_ad_cancel_order');
		});
	});

	// hàm lấy danh sách đơn hàng
	function fetchPage(currentPage = 1) {
		// console.log($('#number_of_rows').val());
		let q = "%" + $('#search').val().trim() + "%";
		let sort = $('#sort').val();
		let status = $('#list_name_tab .nav-link.active').data('status');
		let numRows = $('#number_of_rows').val();
		let action = "fetch";
		let data = {q : q, status: status, numRows: numRows, sort: sort, currentPage: currentPage, action: action};
		let result = sendAJax("fetch_page.php", "post", "json", data);
		$('.list_order').html(result.orders);
		$('.page').html(result.pagination);

		let num_pending = parseInt(result.count['pending']);
		num_pending = !isNaN(num_pending) ? num_pending : 0;

		let num_success = parseInt(result.count['success']);
		num_success = !isNaN(num_success) ? num_success : 0;

		let num_fail = parseInt(result.count['fail']);
		num_fail = !isNaN(num_fail) ? num_fail : 0;

		$('#count_all').text(num_pending + num_success + num_fail);
		$('#count_pending').text(num_pending);
		$('#count_success').text(num_success);
		$('#count_fail').text(num_fail);
	}

	// hàm thay đổi trạng thái của đơn hàng
	function changeStatus(btnID, action) {
		let orID = $(`#${btnID}`).data('order-id');
		let data = {orID: orID, action: action};
		let result = sendAJax("process_order.php", "post", "json", data);
		if(!result.ok) {
			alert("KHÔNG THỂ CẬP NHẬT TRẠNG THÁI ĐƠN HÀNG");
		}
		// cập nhật lại sau khi thay đổi
		let currentPage = parseInt($('li.page-item.active').data('page-number'));
		if(isNaN(currentPage)) {
			currentPage = 1;
		};
		fetchPage(currentPage);
	}

	function sendEmailWhenChangeStatusOrder(orderID, action) {
		let data = {orderID: orderID, action: action};
		let url = '<?= base_url("admin/email/process_email.php"); ?>';
		$.post(url, data);
	}
</script>	