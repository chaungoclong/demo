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

					<!-- số sao -->
					<select id="star_number" class="custom-select mr-3">
						<option value="all" selected>Sao: Tất cả</option>
						<?php for($i = 1; $i <= 5; ++$i): ?>
							<option value="<?= $i; ?>">Sao: <?= $i . " sao"; ?></option>
						<?php endfor ?>
					</select>

					<!-- tìm kiếm tên , id đơn hàng -->
					<input type="text" class="form-control" id="search" placeholder="Search..." style="width: 180px;	">
				</div>

				<!-- số hàng hiển thị -->
				<div class="d-flex justify-content-between align-items-center">
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

			<!-- danh sách danh mục -->
		<div>
			<table class="table table-hover table-bordered" style="font-size: 15px;">
				<thead class="thead-light">
					<tr>
						<th class="align-middle">Khách hàng</th>
						<th class="align-middle">Sản phẩm</th>
						<th class="align-middle">Nội dung</th>
						<th class="align-middle">Số sao</th>
						<th class="align-middle">Ngày tạo</th>
					</tr>
				</thead>

				<tbody class="list_rate">
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

		// lấy danh sách đơn hàng khi sắp xếp
		$(document).on('change', '#sort', function() {
			fetchPage(1);
		});

		// lấy danh sách đơn hàng khi thay đổi số hàng hiển thị
		$(document).on('change', '#number_of_rows', function() {
			fetchPage(1);
		});

		// lấy danh sách đơn hàng khi lọc theo số sao đánh giá
		$(document).on('change', '#star_number', function() {
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
	});

	// hàm lấy danh sách đơn hàng
	function fetchPage(currentPage = 1) {
		// console.log($('#number_of_rows').val());
		let q = "%" + $('#search').val().trim() + "%";
		let sort = $('#sort').val();
		let numStars = $('#star_number').val();
		let numRows = $('#number_of_rows').val();
		let action = "fetch";
		let data = {q : q, numStars: numStars, numRows: numRows, sort: sort, currentPage: currentPage, action: action};
		let result = sendAJax("fetch_page.php", "post", "json", data);
		$('.list_rate').html(result.rates);
		$('.page').html(result.pagination);
	}
</script>	