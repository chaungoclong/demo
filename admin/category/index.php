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
			<h5>DANH SÁCH SẢN DANH MỤC</h5>
			<p class="mb-4">Dánh sách danh mục là nơi bạn kiểm tra và chỉnh sửa thông tin danh mục</p>
			<a class="btn_back btn btn-warning py-0 px-3" onclick="javascript:history.go(-1)">
				<i class="fas fa-arrow-alt-circle-left"></i>
			</a>
			<hr>
		</div>

		<div class="row m-0 mb-3">
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
		<!-- lấy danh sách nhân viên-->
		<?php

			$q = data_input(input_get('q'));
			$key = "%" . $q . "%";

			if($q != "") {
				$searchSQL = "
				SELECT * FROM db_category 
				WHERE 
					cat_name LIKE(?) 
				";

				$param = [$key];
				$listCategory = db_get($searchSQL, 1, $param, "s");
			} else {

				$listCategory = db_fetch_table("db_category", 1);
			}
			

			// chia trang
			$totalCategory   = $listCategory->num_rows;
			$categoryPerPage = 5;
			$currentPage     = isset($_GET['page']) ? $_GET['page'] : 1;
			$currentLink     = create_link(base_url("admin/category/index.php"), ["page"=>'{page}', 'q'=>$q]);
			$page            = paginate($currentLink, $totalCategory, $currentPage, $categoryPerPage);

			// danh sách nhân viên sau khi chia trang
			if($q != "") {
				$searchResultSQL = $searchSQL . " LIMIT ? OFFSET ?";
				$param = [$key, $page['limit'], $page['offset']];

				// danh sách người dùng sau khi tìm kiếm và chia trang chia trang
				$listCategoryPaginate = db_get($searchResultSQL, 1, $param, "sii");
			} else {

				$listCategoryPaginate = db_fetch_table("db_category", 1, $page['limit'], $page['offset']);
			}

			
			$totalCategoryPaginate = $listCategoryPaginate->num_rows;

			// số thứ tự
			$stt = 1 + (int)$page['offset'];
		?>
		<div class="content_table">
			<table class="table table-hover table-bordered" style="font-size: 13px;">
				<tr>
					<th>STT</th>
					<th>Mã</th>
					<th>Tên</th>
					<th>Ảnh</th>
					<th>Trạng thái</th>
					<th>Sửa</th>
					<th>Xóa</th>
				</tr>
				<!-- in các đơn hàng -->
				<?php if ($totalCategoryPaginate > 0): ?>
				<?php foreach ($listCategoryPaginate as $key => $category): ?>
				<tr>
					<!--stt -->
					<td><?= $stt++; ?></td>

					<!-- mã -->
					<td><?= $category['cat_id']; ?></td>

					<!-- tên danh mục -->
					<td><?= $category['cat_name']; ?></td>

					<!-- ảnh  -->
					<td>
						<img src="../../image/<?= $category['cat_logo']; ?>" width="30px" height="30px">
					</td>

					<!-- active -->
					<td>
						<div class="custom-control custom-switch">
							<input 
								type="checkbox" 
								id="switch_active_<?= $category['cat_id']; ?>" 
								data-cat-id="<?= $category['cat_id']; ?>"
								class="btn_switch_active custom-control-input" 
								value="<?= $category['cat_active']; ?>"
								<?= $category['cat_active'] ? "checked" : ""; ?>
							>
							<label for="switch_active_<?= $category['cat_id']; ?>" class="custom-control-label"></label>
						</div>
					</td>

					<!-- edit -->
					<td>
						<a
							href="
							<?= 
								create_link(base_url('admin/category/update.php'), [
									"catid"=>$category['cat_id']
								]);
							?>
							"
							class="btn_edit_cat btn btn-success"
							data-cat-id="<?= $category['cat_id']; ?>">
							<i class="fas fa-edit"></i>
						</a>
					</td>

					<!-- remove -->
					<td>
						<a 
							class="btn_remove_cat btn btn-danger"
							data-cat-id="<?= $category['cat_id']; ?>">
							<i class="fas fa-trash-alt"></i>
						</a>
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
	
		// Thay đổi trạng thái của danh mục
		$(document).on('change', '.btn_switch_active', function() {

			// id danh mục
			let catID = $(this).data("cat-id");

			// trạng thái hiện tại
			let prevActive = $(this).val();
			console.log(prevActive);

			// trạng thái muốn thay đổi
			let newActive = $(this).prop('checked');
			newActive = newActive ? 1 : 0;
			console.log(newActive);

			// gửi yêu cầu thay đổi trạng thái
			let sendSwitchActive = sendAJax(
				"process_category.php",
				"post",
				"json",
				{catID: catID, newActive: newActive, action: "switch_active"}
			)
			//alert(sendSwitchActive.status);

			// nếu không thành công khôi phục về trạng thái trước đó
			// if(sendSwitchActive.status == 1) {
			// 	alert("THIẾU DỮ LIỆU");
			// 	if(prevActive == 1) {
			// 		$("#switch_active_" + customerID).prop("checked", true);
			// 	} else {
			// 		$("#switch_active_" + customerID).prop("checked", false);
			// 	}
			// }

			// // nếu thành công thay đổi trang thái của nút trạng thái theo trạng thái được trả về
			// if(sendSwitchActive.status == 5) {

			// 	// mã khách hàng trả về
			// 	let customerID = sendSwitchActive.customerID;

			// 	// trạng thái trả về
			// 	let resActive = sendSwitchActive.active;
			// 	// alert(resActive);

			// 	// thay đổi trạng thái
			// 	if(resActive == 1) {
			// 		$("#switch_active_" + customerID).prop("checked", true);
			// 	} else {
			// 		$("#switch_active_" + customerID).prop("checked", false);
			// 	}
			// }
			

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


		// xóa danh mục
		$(document).on('click', '.btn_remove_cat', function() {

			let wantRemove = confirm("BẠN CÓ MUỐN XÓA DANH MỤC NÀY");

			if(wantRemove) {

				// THỰC HIỆN HÀNH ĐỘNG
				let catID = $(this).data('cat-id');
				let prevLink = "<?= getCurrentURL() ?>";
				
				let sendRemove = sendAJax(
					"process_category.php",
					"post",
					"text",
					{catID: catID, action: "remove"}
				);

				switch(sendRemove) {
					case "1":
						alert("THIẾU DỮ LIỆU");
						break;

					case "2":
						alert("KHÔNG THỂ XÓA DANH MỤC ĐANG CÓ SẢN PHẨM");
						break;

					case "5":
						alert("XÓA THÀNH CÔNG");
						break;

					case "6":
						alert("ĐÃ XẢY RA LỖI");
						break;
				}

				// LÀM MỚI TRANG
				// trang trước(chuyển hướng đến sau khi cập nhật -dùng cho update)
				let prevPage    = "<?= getCurrentURL(); ?>";
				
				// trang hiện tại(phân trang)
				let currentPage = <?= $currentPage ?>;
				
				let q           = "<?= $_GET['q'] ?? ""; ?>";

				// làm mới trang
				let fetchPage = sendAJax(
					"fetch_page.php",
					"post",
					"html",
					{action: "fetch", prevPage: prevPage, q: q, currentPage: currentPage }
				);

				$('.content_table').html(fetchPage);
			}

		});
	});
</script>	
