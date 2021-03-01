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

		<!-- nút thêm hãng và thanh tìm kiếm -->
		<div class="row m-0 mb-3">
			<!-- nút thêm hãng -->
			<div class="col-12 p-2 d-flex justify-content-between align-items-center bg-light border">
				<!-- thêm , tìm kiếm -->
				<div class="filter d-flex">
					<!-- thêm -->
					<a href="
						<?= base_url('admin/brand/add.php'); ?>
						"
						class="btn btn-success mr-3"
						data-toggle="tooltip"
						data-placement="top"
						title="Thêm hãng mới"
						style="border-radius: 50%;"
						>
						<i class="fas fa-plus"></i>
					</a>

					<!-- sắp xếp -->
					<select id="sort" class="custom-select mr-2">
						<option value="1">Tên: A - Z</option>
						<option value="2">Tên: Z - A</option>
						<option value="3" selected>Ngày tạo: Mới nhất</option>
						<option value="4">Ngày tạo: Cũ nhất</option>
					</select>

					<!-- lọc trạng thái hãng -->
					<select id="filter_status" class='custom-select mr-2'>
						<option value="all" selected>Trạng thái: Tất cả</option>
						<option value="on">Trạng thái: Bật</option>
						<option value="off">Trạng thái: Tắt</option>
					</select>

					<!-- tìm kiếm tên , id hãng -->
					<input type="text" class="form-control" id="search" placeholder="search">
				</div>

				<!-- số hàng hiển thị, xuất file -->
				<div class="d-flex align-items-center">
					<!-- xuất file -->
					<i class="far fa-file-excel fa-2x text-success mr-3" onclick="window.location='export_file.php'" data-toggle="tooltip" title="xuất file excel"></i>

					<!-- số hàng -->
					<?php $option = [5, 10, 25, 50, 100]; ?>
					<select class="custom-select" id="number_of_rows" data-toggle="tooltip" title="số hàng hiển thị">
						<?php foreach ($option as $key => $each): ?>
							<option value="<?= $each; ?>"> <?= $each; ?> </option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
		</div>

		<!-- danh sách hãng -->
		<div>
			<table class="table table-hover bg-white table-bordered shadow" style="font-size: 15px;">
				<thead class="thead-light">
					<tr>
						<th class="align-middle">Mã</th>
						<th class="align-middle">Tên</th>
						<th class="align-middle">Logo</th>
						<th class="align-middle">Trạng thái</th>
						<th class="align-middle" width="115px">Hành động</th>
					</tr>
				</thead>

				<tbody class="list_brand thead-light">
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
		// khôi phục trang trước(nếu quay lại từ trang update sau khi update)
		// hoặc làm mới trang(lấy danh sách hãng tại trang đầu tiên vơi các tùy chọn tìm kiếm mặc định)
		fetchPageFirstTime();

		// lấy danh sách hãng khi nhập tìm kiếm
		$(document).on('input', '#search', function() {
			fetchPage(1);
		});
		$(document).on('change', '#filter_status', function() {
			fetchPage(1);
		});

		// lấy danh sách hãng khi nhập tìm kiếm
		$(document).on('change', '#sort', function() {
			fetchPage(1);
		});

		// lấy danh sách hãng khi thay đổi số hàng hiển thị
		$(document).on('change', '#number_of_rows', function() {
			fetchPage(1);
		});

		// lấy danh sách hãng khi chuyển trang
		$(document).on('click', '.page-item', function() {
			let currentPage = parseInt($(this).data("page-number"));
			if(isNaN(currentPage)) {
				currentPage = 1;
			}
			fetchPage(currentPage);
		});

		// thay đổi trạng thái của 1 hãng
		$(document).on('change', '.btn_switch_active', function() {
			changeStatus(this.id);
		});

		// xóa 1 hãng
		$(document).on('click', '.btn_delete_bra', function() {
			deleteRow(this.id);
		});

		// lưu dữ liệu của trang index trước khi chuyển sang trang update(để quay lại đúng trang sau khi update)
		$(document).on('click', '.btn_edit_bra', function() {
			setPrevPageData();
		});
	});

	// hàm lấy danh sách các mục
	function fetchPage(currentPage = 1) {
		let q = "%" + $('#search').val().trim() + "%";
		let sort = $('#sort').val();
		let status = $('#filter_status').val();
		let action = "fetch";
		let numRows = $('#number_of_rows').val();
		let data = {q : q, status: status, sort: sort, numRows: numRows, currentPage: currentPage, action: action};
		let result = sendAJax("fetch_page.php", "post", "json", data);
		$('.list_brand').html(result.brands);
		$('.page').html(result.pagination);
	}

	// hàm thay đổi trạng thái của hãng
	function changeStatus(btnID) {
		let braID = $(`#${btnID}`).data('bra-id');
		let status = $(`#${btnID}`).prop('checked');
		let active = status ? 1 : 0;
		let action = "switch_active";
		let data = {braID: braID, active: active, action: action};
		let result = sendAJax("process_brand.php", "post", "json", data);
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
		let braID = $(`#${btnID}`).data('bra-id'); 
		let action = "delete";
		let data = {braID : braID, action: action};
		let result = sendAJax("process_brand.php", "post", "json", data);
		console.log(result);
		let status = result.status;
		switch (status) {
			case "success":
				alert("XÓA THÀNH CÔNG");
				break;
			case "has_product":
				alert("KHÔNG THỂ XÓA HÃNG ĐÃ CÓ SẢN PHẨM");
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

	/**
	 * hàm tạo dữ liệu của trang trước (để khi quay lại trang đó thì khôi phục lại)
	 */
	function setPrevPageData() {
		localStorage.setItem("search", $('#search').val());
		localStorage.setItem("sort", $('#sort').val());
		localStorage.setItem("numRows", $('#number_of_rows').val());
		localStorage.setItem("status", $('#filter_status').val());
		localStorage.setItem("oldPage", parseInt($('li.page-item.active').data('page-number')));
	}

	// hàm lấy trang lần đầu tiên (nếu quay về từ trang update thì khôi phục các thông tin về tùy chọn tìm kiếm, vị trí trang hiện tại)
	// nếu lần đầu vào trang hoặc quay về từ trang khác khác trang update thì làm mới trang(lấy dữ liệu trang đầu tiên, các tùy chọn tìm kiếm mặc định)
	function fetchPageFirstTime() {
		let search  = localStorage.getItem("search");
		if(search != null) {
			$('#search').val(search);
			localStorage.removeItem("search");
		}

		let sort    = localStorage.getItem("sort");
		if(sort != null) {
			$('#sort').val(sort);
			localStorage.removeItem("sort");
		}

		let numRows    = localStorage.getItem("numRows");
		if(numRows != null) {
			$('#number_of_rows').val(numRows);
			localStorage.removeItem("numRows");
		}

		let status  = localStorage.getItem("status");
		if(status != null) {
			$('#filter_status').val(status);
			localStorage.removeItem("status");
		}

		let oldPage = localStorage.getItem("oldPage");
		if(oldPage != null) {
			fetchPage(oldPage);
			localStorage.removeItem("oldPage");
		} else {
			fetchPage(1);
		}
	}
</script>	
