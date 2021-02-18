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
			<h5>DANH SÁCH ĐƠN HÀNG</h5>
			<a class="btn_back btn btn-warning py-1 px-2" onclick="javascript:history.go(-1)">
				<i class="fas fa-chevron-circle-left"></i>
			</a>
		</div>

		<!-- nút thêm đơn hàng và thanh tìm kiếm -->
		<div class="row m-0 mb-3">
			<div class="col-12 p-2 d-flex justify-content-between align-items-center bg-light">
				<!-- lọc-->
				<div class="filter d-flex">
					<!-- sắp xếp -->
					<select id="sort" class="custom-select">
						<option value="1" selected>Mới nhất</option>
						<option value="2">Cũ nhất</option>
					</select>

					<!-- tìm kiếm tên , id đơn hàng -->
					<input type="text" class="form-control" id="search" placeholder="Search...">
				</div>

				<!-- số hàng hiển thị -->
				<div>
					<?php $option = [5, 10, 25, 50, 100]; ?>
					<select class="custom-select" id="number_of_rows">
						<?php foreach ($option as $key => $each): ?>
							<option value="<?= $each; ?>"> <?= $each; ?> </option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
		</div>

		<ul class="nav nav-tabs px-2" role="tablist" id="list_name_tab">
			<li class="nav-item">
				<a class="nav-link active" data-toggle="tab" href="#all" data-status="all">TẤT CẢ</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#pending" data-status="pending">ĐANG CHỜ</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#success" data-status="success">ĐÃ XỬ LÝ</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#fail" data-status="fail">ĐÃ HỦY</a>
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
							<th class="align-middle">LỢI NHUẬN</th>
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
							<th class="align-middle">LỢI NHUẬN</th>
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

			<!-- ĐÃ XỬ LÝ -->
			<div class="tab-pane p-2" id="success">
				<table class="table table-hover table-bordered" style="font-size: 15px;">
					<thead class="thead-light">
						<tr>
							<th class="align-middle">ID</th>
							<th class="align-middle">NGÀY ĐẶT</th>
							<th class="align-middle">LỢI NHUẬN</th>
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
							<th class="align-middle">LỢI NHUẬN</th>
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
		});

		// hủy 1 đơn hàng
		$(document).on('click', '.btn_cancel', function() {
			changeStatus(this.id, "cancel");
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
</script>	