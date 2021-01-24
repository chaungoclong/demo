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
			<h5>DANH SÁCH SLIDE CỦA CÁC THỂ LOẠI</h5>
			<p class="mb-4">Dánh sách slide là nơi bạn quản lý slide hiển thị riêng cho từng thể loại</p>
			<hr>
		</div>

		<div class="row m-0 mb-3">
			<div class="col-12 p-0 d-flex justify-content-between align-items-center">
				<a href="
					<?= create_link( base_url('admin/slider/add.php'), ['from'=>getCurrentURL()]); ?>
					" 
					class="btn btn-success" 
					data-toggle="tooltip" 
					data-placement="top" 
					title="Thêm slide mới"
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
		<!-- lấy danh sách slide-->
		<?php

			$q = data_input(input_get('q'));
			$key = "%" . $q . "%";

			if($q != "") {
				$searchSQL = "
				SELECT db_slider.*, db_category.cat_name FROM `db_slider` 
				JOIN db_category ON db_slider.cat_id = db_category.cat_id
				WHERE 
					db_category.cat_name LIKE(?) 
				";

				$param = [$key];
				$listSlide = db_get($searchSQL, 1, $param, "s");
			} else {

				$listSlide = getListSlide();
			}
			

			// chia trang
			$totalSlide = $listSlide->num_rows;
			$slidePerPage = 5;
			$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
			$currentLink = create_link(base_url("admin/slider/index.php"), ["page"=>'{page}', 'q'=>$q]);
			$page = paginate($currentLink, $totalSlide, $currentPage, $slidePerPage);

			// danh sách slide sau khi chia trang
			if($q != "") {
				$searchResultSQL = $searchSQL . " LIMIT ? OFFSET ?";
				$param = [$key, $page['limit'], $page['offset']];

				// danh sách người dùng sau khi tìm kiếm và chia trang chia trang
				$listSlidePaginate = db_get($searchResultSQL, 1, $param, "sii");
			} else {

				$listSlidePaginate = getListSlide($page['limit'], $page['offset']);
			}

			
			$totalSlidePaginate = $listSlidePaginate->num_rows;

			// số thứ tự
			$stt = 1 + (int)$page['offset'];
		?>
		<div class="content_table">
			<table class="table table-hover table-bordered" style="font-size: 13px;">
				<tr>
					<th>STT</th>
					<th>Mã</th>
					<th>Thể loại</th>
					<th width="45%">Preview</th>
					<th>vị trí</th>
					<th>Sửa</th>
					<th>Xóa</th>
				</tr>
				<!-- in các đơn hàng -->
				<?php if ($totalSlidePaginate > 0): ?>
				<?php foreach ($listSlidePaginate as $key => $slide): ?>
				<tr>
					<!-- mã -->
					<td><?= $stt++; ?></td>

					<!-- mã -->
					<td><?= $slide['sld_id']; ?></td>

					<!-- thể loại-->
					<td><?= $slide['cat_name']; ?></td>

					<!-- ảnh  -->
					<td>
						<img src="<?= $slide['sld_image']; ?>" width="100%">
					</td>

					<!-- vị trí -->
					<td>
						<?php $lastPos = lastPostion(); ?>

						<div class="text-center d-flex flex-column align-items-center" style="width: 100%; height: 100%;">

							<!-- di chuyển slide lên đầu(giảm giá trị vị trí) -->
							<?php if ($slide['sld_pos'] != 1): ?>
								<button 
									class="btn_up_pos btn btn-primary" 
									style="width: 40px;"
									data-sld-id="<?= $slide['sld_id']; ?>"
								>
									<i class="fas fa-caret-up"></i>
								</button>
							<?php endif ?>
							
							<!-- di chuyển slide xuống cuối(tăng giá trị vị trí) -->
							<?php if ($slide['sld_pos'] != $lastPos): ?>
								<button 
									class="btn_down_pos btn btn-danger" 
									style="width: 40px;"
									data-sld-id="<?= $slide['sld_id']; ?>"
								>
									<i class="fas fa-caret-down"></i>
								</button>
							<?php endif ?>
						</div>
					</td>

					<!-- edit -->
					<td class="text-center">
						<a
							href="
							<?= 
								create_link(base_url('admin/slider/update.php'), [
									"sldid"=>$slide['sld_id'],
									"from"=>getCurrentURL()
								]);
							?>
							"
							class="btn_edit_sld btn btn-success"
							data-sld-id="<?= $slide['sld_id']; ?>">
							<i class="fas fa-edit"></i>
						</a>
					</td>

					<!-- remove -->
					<td class="text-center">
						<a 
							class="btn_remove_sld btn btn-danger"
							data-sld-id="<?= $slide['sld_id']; ?>">
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
	
		// =================================THAY ĐỔI VỊ TRÍ CỦA SLIDE===================== //
		// di chuyển lên đầu
		$(document).on('click', '.btn_up_pos', function() {

			// mã slide
			let sldID  = $(this).data('sld-id');

			// hành động
			let action = "change_pos";

			// tùy chọn
			let opt    = "up";

			// dữ liệu gửi đi
			let data = {sldID: sldID, action: action, opt: opt};

			// gủi yêu cầu thay đổi
			let sendChangePos = sendAJax(
				"process_slider.php",
				"post",
				"text",
				data
			);

			//alert(sendChangePos);
			
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

		});

		// di chuyển xuống cuối
		$(document).on('click', '.btn_down_pos', function() {
			// mã slide
			let sldID  = $(this).data('sld-id');

			// hành động
			let action = "change_pos";

			// tùy chọn
			let opt    = "down";

			// dữ liệu gửi đi
			let data = {sldID: sldID, action: action, opt: opt};

			// gủi yêu cầu thay đổi
			let sendChangePos = sendAJax(
				"process_slider.php",
				"post",
				"text",
				data
			);

			//alert(sendChangePos);

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
		});
		
		// xóa slide
		$(document).on('click', '.btn_remove_sld', function() {

			let wantRemove = confirm("BẠN CÓ MUỐN XÓA SLIDE NÀY");

			if(wantRemove) {

				// THỰC HIỆN HÀNH ĐỘNG
				let sldID = $(this).data('sld-id');
				let prevLink = "<?= getCurrentURL() ?>";
				
				let sendRemove = sendAJax(
					"process_slider.php",
					"post",
					"text",
					{sldID: sldID, action: "remove"}
				);


				switch(sendRemove) {
					case "1":
						alert("THIẾU DỮ LIỆU");
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
