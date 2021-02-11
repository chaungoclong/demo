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
			<h5>DANH SÁCH DANH MỤC</h5>
			<a class="btn_back btn btn-warning py-0 px-3" onclick="javascript:history.go(-1)">
				<i class="fas fa-arrow-alt-circle-left"></i>
			</a>
		</div>

		<!-- nút thêm danh mục và thanh tìm kiếm -->
		<div class="row m-0 mb-3">
			<!-- nút thêm danh mục -->
			<div class="col-12 p-0 d-flex justify-content-between align-items-center">
				<a href="
					<?= base_url('admin/category/add.php'); ?>
					" 
					class="btn btn-success" 
					data-toggle="tooltip" 
					data-placement="top" 
					title="Thêm danh mục mới"
				>
					<i class="fas fa-plus"></i>
				</a>

				<!-- tìm kiếm -->
				<div class="filter d-flex">
					<!-- sắp xếp -->
					<select id="sort" class="custom-select">
						<option value="1" selected>Tên: A - Z</option>
						<option value="2">Tên: Z - A</option>
					</select>

					<!-- lọc trạng thái danh mục -->
					<select id="filter_status" class='custom-select'>
						<option value="all" selected>Tất cả</option>
						<option value="on">Bật</option>
						<option value="off">Tắt</option>
					</select>

					<!-- tìm kiếm tên , id danh mục -->
					<input type="text" class="form-control" id="search" placeholder="search">
				</div>
			</div>
		</div>

		<!-- danh sách danh mục -->
		<div>
			<table class="table table-hover table-bordered" style="font-size: 13px;">
				<thead>
					<tr>
						<th>STT</th>
						<th>Mã</th>
						<th>Tên</th>
						<th>Ảnh</th>
						<th>Trạng thái</th>
						<th>Sửa</th>
						<th>Xóa</th>
					</tr>
				</thead>

				<tbody class="list_category">
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
		fetchPage();

		// lấy danh sách danh mục khi nhập tìm kiếm
		$(document).on('input', '#search', function() {
			fetchPage();
		});
		$(document).on('change', '#filter_status', function() {
			fetchPage();
		});

		// lấy danh sách danh mục khi nhập tìm kiếm
		$(document).on('change', '#sort', function() {
			fetchPage();
		});

		// lấy danh sách danh mục khi chuyển trang
		$(document).on('click', '.page-item', function() {
			let currentPage = parseInt($(this).data("page-number"));
			if(isNaN(currentPage)) {
				currentPage = 1;
			}
			fetchPage(currentPage);
		});

		// thay đổi trạng thái của 1 danh mục
		$(document).on('change', '.btn_switch_active', function() {
			changeStatus(this.id);
		});

		// xóa 1 danh mục
		$(document).on('click', '.btn_delete_cat', function() {
			deleteRow(this.id);
		});
	});

	// hàm lấy danh sách các mục
	function fetchPage(currentPage = 1) {
		let q = "%" + $('#search').val() + "%";
		let sort = $('#sort').val();
		let status = $('#filter_status').val();
		let action = "fetch";
		let data = {q : q, status: status, sort: sort, currentPage: currentPage, action: action};
		let result = sendAJax("fetch_page.php", "post", "json", data);
		$('.list_category').html(result.categories);
		$('.page').html(result.pagination);
	}

	// hàm thay đổi trạng thái của danh mục
	function changeStatus(btnID) {
		let catID = $(`#${btnID}`).data('cat-id');
		let status = $(`#${btnID}`).prop('checked');
		let active = status ? 1 : 0;
		let action = "switch_active";
		let data = {catID: catID, active: active, action: action};
		let result = sendAJax("process_category.php", "post", "json", data);
		if(!result.ok) {
			alert("có lỗi khi thay đổi trạng thái");
		}
		// cập nhật lại sau khi thay đổi
		let currentPage = parseInt($('li.page-item.active').data('page-number'));
		if(isNaN(currentPage)) {
			currentPage = 1;
		};
		fetchPage(currentPage);
	}

	function deleteRow(btnID) {
		let catID = $(`#${btnID}`).data('cat-id'); 
		let action = "delete";
		let data = {catID : catID, action: action};
		let result = sendAJax("process_category.php", "post", "json", data);
		console.log(result);
		let status = result.status;
		switch (status) {
			case "success":
				alert("XÓA THÀNH CÔNG");
				break;
			case "has_product":
				alert("KHÔNG THỂ XÓA DANH MỤC ĐÃ CÓ SẢN PHẨM");
				break;
			case "error":
				alert("ĐÃ CÓ LỖI XẢY RA, VUI LÒNG THỬ LẠI");
				break;
			default:
				alert("ĐÃ CÓ LỖI XẢY RA, VUI LÒNG THỬ LẠI");
				break;
		}

		// cập nhật danh sách sau khi xóa
		let currentPage = parseInt($('li.page-item.active').data('page-number'));
		if(isNaN(currentPage)) {
			currentPage = 1;
		};
		fetchPage(currentPage);
	}
</script>	
