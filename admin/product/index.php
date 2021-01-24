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
			<h5>DANH SÁCH SẢN PHẨM</h5>
			<p class="mb-4">Dánh sách sản phẩm là nơi bạn kiểm tra và chỉnh sửa thông  tin sản phẩm</p>
			<hr>
		</div>

		<div class="row m-0 mb-3">
			<div class="col-12 p-0 d-flex justify-content-between align-items-center">
				<a href="
					<?= create_link( base_url('admin/product/add.php'), ['from'=>getCurrentURL()]); ?>
					" 
					class="btn btn-success" 
					data-toggle="tooltip" 
					data-placement="top" 
					title="Thêm sản phẩm mới"
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
				SELECT db_product.*, db_brand.bra_name, db_category.cat_name FROM `db_product` 
				JOIN db_category ON db_product.cat_id = db_category.cat_id
				JOIN db_brand 	 ON db_product.bra_id = db_brand.bra_id
				WHERE 
					pro_name LIKE(?) 
				";

				$param = [$key];
				$listProduct = db_get($searchSQL, 1, $param, "s");
			} else {

				$listProduct = getListProduct();
			}
			

			// chia trang
			$totalProduct = $listProduct->num_rows;
			$productPerPage = 5;
			$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
			$currentLink = create_link(base_url("admin/product/index.php"), ["page"=>'{page}', 'q'=>$q]);
			$page = paginate($currentLink, $totalProduct, $currentPage, $productPerPage);

			// danh sách nhân viên sau khi chia trang
			if($q != "") {
				$searchResultSQL = $searchSQL . " LIMIT ? OFFSET ?";
				$param = [$key, $page['limit'], $page['offset']];

				// danh sách người dùng sau khi tìm kiếm và chia trang chia trang
				$listProductPaginate = db_get($searchResultSQL, 1, $param, "sii");
			} else {

				$listProductPaginate = getListProduct($page['limit'], $page['offset']);
			}

			
			$totalProductPaginate = $listProductPaginate->num_rows;

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
					<th>Hãng</th>
					<th>Thể loại</th>
					<th>Giá</th>
					<th>Số lượng</th>
					<th>Trạng thái</th>
					<th>Sửa</th>
					<th>Xóa</th>
				</tr>
				<!-- in các đơn hàng -->
				<?php if ($totalProductPaginate > 0): ?>
				<?php foreach ($listProductPaginate as $key => $product): ?>
				<tr>
					<!-- mã -->
					<td><?= $stt++; ?></td>

					<!-- mã -->
					<td><?= $product['pro_id']; ?></td>

					<!-- tên sản phẩm -->
					<td><?= $product['pro_name']; ?></td>

					<!-- ảnh  -->
					<td>
						<img src="../../image/<?= $product['pro_img']; ?>" width="30px" height="30px">
					</td>

					<!-- hãng -->
					<td><?= $product['bra_name']; ?></td>

					<!-- thể loại -->
					<td>
						<?= $product['cat_name'] ?>
					</td>

					<!-- giá -->
					<td><?= $product['pro_price']; ?></td>

					<!-- số lượng -->
					<td><?= $product['pro_qty']; ?></td>
					

					<!-- active -->
					<td>
						<div class="custom-control custom-switch">
							<input 
								type="checkbox" 
								id="switch_active_<?= $product['pro_id']; ?>" 
								data-pro-id="<?= $product['pro_id']; ?>"
								class="btn_switch_active custom-control-input" 
								value="<?= $product['pro_active']; ?>"
								<?= $product['pro_active'] ? "checked" : ""; ?>
							>
							<label for="switch_active_<?= $product['pro_id']; ?>" class="custom-control-label"></label>
						</div>
					</td>

					<!-- edit -->
					<td>
						<a
							href="
							<?= 
								create_link(base_url('admin/product/update.php'), [
									"proid"=>$product['pro_id'],
									"from"=>getCurrentURL()
								]);
							?>
							"
							class="btn_edit_pro btn btn-success"
							data-pro-id="<?= $product['pro_id']; ?>">
							<i class="fas fa-edit"></i>
						</a>
					</td>

					<!-- remove -->
					<td>
						<a 
							class="btn_remove_pro btn btn-danger"
							data-pro-id="<?= $product['pro_id']; ?>">
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
	
		// Thay đổi trạng thái của khách hàng
		$(document).on('change', '.btn_switch_active', function() {

			// id khách hàng
			let proID = $(this).data("pro-id");

			// trạng thái hiện tại
			let prevActive = $(this).val();
			console.log(prevActive);

			// trạng thái muốn thay đổi
			let newActive = $(this).prop('checked');
			newActive = newActive ? 1 : 0;
			console.log(newActive);

			// gửi yêu cầu thay đổi trạng thái
			let sendSwitchActive = sendAJax(
				"process_product.php",
				"post",
				"json",
				{proID: proID, newActive: newActive, action: "switch_active"}
			)
			// alert(sendSwitchActive.status);

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


		// xóa sản phẩm
		$(document).on('click', '.btn_remove_pro', function() {

			let wantRemove = confirm("BẠN CÓ MUỐN XÓA SẢN PHẨM NÀY");

			if(wantRemove) {

				// THỰC HIỆN HÀNH ĐỘNG
				let proID = $(this).data('pro-id');
				let prevLink = "<?= getCurrentURL() ?>";
				
				let sendRemove = sendAJax(
					"process_product.php",
					"post",
					"text",
					{proID: proID, action: "remove"}
				);

				switch(sendRemove) {
					case "1":
						alert("THIẾU DỮ LIỆU");
						break;

					case "2":
						alert("KHÔNG THỂ XÓA SẢN PHẨM ĐANG CÓ ĐƠN HÀNG");
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
