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
			<h5>DANH SÁCH SLIDE</h5>
			<a class="btn_back btn btn-warning py-1 px-2" onclick="javascript:history.go(-1)">
				<i class="fas fa-chevron-circle-left"></i>
			</a>
		</div>

		<!-- nút thêm slide và thanh tìm kiếm -->
		<div class="row m-0 mb-3">
			<div class="col-12 p-2 d-flex justify-content-between align-items-center bg-light">
				<!-- lọc-->
				<div class="filter d-flex">
					<!-- sắp xếp -->
					<select id="sort" class="custom-select">
						<option value="1">Mới nhất</option>
						<option value="2">Cũ nhất</option>
						<option value="3" selected>Vị trí: cao - thấp</option>
						<option value="4">Vị trí: thấp - cao</option>
					</select>

					<!-- danh mục -->
					<select id="category_opt" class="custom-select">
						<?php $listCategory = db_fetch_table("db_category", 0); ?>

						<option value="all">Tất cả</option>
						<?php foreach ($listCategory as $key => $category): ?>
							<option value="<?= $category['cat_id'] ?>"> <?= $category['cat_name']; ?> </option>
						<?php endforeach ?>
					</select>

					<!-- tìm kiếm tên , id slide -->
					<input type="text" class="form-control" id="search" placeholder="Search...">
				</div>

				<!-- số hàng hiển thị -->
				<div class="d-flex justify-content-between">
					<a class="btn btn-success mr-3 text-white" href="add.php" data-toggle="tooltip" title="Thêm slide mới">
						<i class="fas fa-plus"></i>
					</a>
					<?php $option = [5, 10, 25, 50, 100]; ?>
					<select class="custom-select" id="number_of_rows">
						<?php foreach ($option as $key => $each): ?>
							<option value="<?= $each; ?>"> <?= $each; ?> </option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
		</div>

		<!-- danh sách slide -->
		<div>
			<table class="table table-hover table-bordered" style="font-size: 15px;">
				<thead class="thead-light">
					<tr>
						<th class="align-middle">ID</th>
						<th class="align-middle">DANH MỤC</th>
						<th class="align-middle">VỊ TRÍ</th>
						<th class="align-middle">LINK</th>
						<th class="align-middle" width="25%">XEM TRƯỚC</th>
						<th class="align-middle">DI CHUYỂN</th>
						<th class="align-middle">SỬA</th>
						<th class="align-middle">XÓA</th>
					</tr>
				</thead>

				<tbody class="list_slide">
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
		// hoặc làm mới trang(lấy danh sách slide tại trang đầu tiên vơi các tùy chọn tìm kiếm mặc định)
		fetchPageFirstTime();

		// lấy danh sách slide khi nhập tìm kiếm
		$(document).on('input', '#search', function() {
			fetchPage(1);
		});

		// lấy danh sách slide khi lọc theo danh mục
		$(document).on('change', '#category_opt', function() {
			fetchPage(1);
		});

		// lấy danh sách slide khi sắp xếp
		$(document).on('change', '#sort', function() {
			fetchPage(1);
		});

		// lấy danh sách slide khi thay đổi số hàng hiển thị
		$(document).on('change', '#number_of_rows', function() {
			fetchPage(1);
		});

		// lấy danh sách slide khi chuyển trang
		$(document).on('click', '.page-item', function() {
			let currentPage = parseInt($(this).data("page-number"));
			if(isNaN(currentPage)) {
				currentPage = 1;
			}
			fetchPage(currentPage);
		});

		// xóa 1 slide
		$(document).on('click', '.btn_delete_sld', function() {
			deleteRow(this.id);
		});

		// lưu dữ liệu của trang index trước khi chuyển sang trang update(để quay lại đúng trang sau khi update)
		$(document).on('click', '.btn_edit_sld', function() {
			setPrevPageData();
		});

		// di chuyển 1 slide lên
		$(document).on('click', '.btn_up', function() {
			movePosition(this.id, "up");
		});

		// di chuyển 1 slide xuống
		$(document).on('click', '.btn_down', function() {
			movePosition(this.id, "down");
		});
	});

	// hàm lấy danh sách các mục
	function fetchPage(currentPage = 1) {
		let q = "%" + $('#search').val().trim() + "%";
		let sort = $('#sort').val();
		let category = $('#category_opt').val();
		let numRows = $('#number_of_rows').val();
		let action = "fetch";
		let data = {q : q, category: category, sort: sort, numRows: numRows, currentPage: currentPage, action: action};
		let result = sendAJax("fetch_page.php", "post", "json", data);
		$('.list_slide').html(result.slides);
		$('.page').html(result.pagination);
	}

	// hàm thay đổi trạng thái của slide
	function movePosition(btnID, option) {
		let sldID = $(`#${btnID}`).data('sld-id');
		let action = "move";
		let data = {sldID: sldID, option: option, action: action};
		let result = sendAJax("process_slider.php", "post", "json", data);
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
		let sldID = $(`#${btnID}`).data('sld-id'); 
		let action = "delete";
		let data = {sldID : sldID, action: action};
		let result = sendAJax("process_slider.php", "post", "json", data);
		console.log(result);
		let status = result.status;
		switch (status) {
			case "success":
				alert("XÓA THÀNH CÔNG");
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
		localStorage.setItem("category", $('#category_opt').val());
		localStorage.setItem("oldPage", parseInt($('li.page-item.active').data('page-number')));
		localStorage.setItem("numRows", $('#number_of_rows').val());
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

		let category  = localStorage.getItem("category");
		if(category != null) {
			$('#category_opt').val(category);
			localStorage.removeItem("category");
		}

		let numRows  = localStorage.getItem("numRows");
		if(numRows != null) {
			$('#number_of_rows').val(numRows);
			localStorage.removeItem("numRows");
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
