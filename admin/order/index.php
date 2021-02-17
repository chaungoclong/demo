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
			<div class="col-12 p-0 d-flex justify-content-between align-items-center">
				<!-- lọc-->
				<div class="filter d-flex">
					<!-- sắp xếp -->
					<select id="sort" class="custom-select">
						<option value="1" selected>Mới nhất</option>
						<option value="2">Cũ nhất</option>
					</select>

					<!-- lọc trạng thái đơn hàng -->
					<select id="filter_status" class='custom-select'>
						<option value="all" selected>Tất cả</option>
						<option value="pending">Đang chờ xử lý</option>
						<option value="success">Đã duyệt</option>
						<option value="fail">Đã hủy</option>
					</select>

					<!-- tìm kiếm tên , id đơn hàng -->
					<input type="text" class="form-control" id="search" placeholder="search">
				</div>
			</div>
		</div>

		<!-- danh sách đơn hàng-->
		<div>
			<table class="table table-hover table-bordered shadow" style="font-size: 13px;">
				<thead class="thead-dark">
					<tr>
						<th>ID</th>
						<th>NGÀY ĐẶT</th>
						<th>TRẠNG THÁI</th>
						<th>NGƯỜI ĐẶT</th>
						<th>NGƯỜI NHẬN</th>
						<th>XEM</th>
						<th>TÙY CHỌN</th>
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
		$(document).on('change', '#filter_status', function() {
			fetchPage(1);
		});

		// lấy danh sách đơn hàng khi nhập tìm kiếm
		$(document).on('change', '#sort', function() {
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
		let q = "%" + $('#search').val().trim() + "%";
		let sort = $('#sort').val();
		let status = $('#filter_status').val();
		let action = "fetch";
		let data = {q : q, status: status, sort: sort, currentPage: currentPage, action: action};
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