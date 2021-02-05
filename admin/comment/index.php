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
			<h5>DANH SÁCH ĐÁNH GIÁ</h5>
			<p class="mb-4">Dánh sách đánh giá là nơi bạn xem đánh giá của khách hàng</p>
			<hr>
		</div>

		<div class="row m-0 mb-3">
			<div class="col-12 p-0 d-flex justify-content-between align-items-center">

				<div class="form-group m-0 p-0 d-flex align-items-center">
					<form action="" class="form-inline" id="search_box">

						<select name="star" id="star" class="custom-select">
							<option value="0" selected>TẤT CẢ</option>
							<?php for ($i=1; $i <= 5 ; $i++): ?>
								<option value="<?= $i; ?>">
									<?= $i; ?> Sao
								</option>
							<?php endfor ?>
						</select>
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
			SELECT db_rate.*, db_customer.cus_name, db_product.pro_name
			FROM db_rate 
			JOIN db_customer ON db_rate.cus_id = db_customer.cus_id
			JOIN db_product ON db_rate.pro_id = db_product.pro_id
			WHERE db_customer.cus_name LIKE(?)
			ORDER BY db_rate.r_create_at DESC
			";

			$param = [$key];
			$listRate = db_get($searchSQL, 1, $param, "s");
		} else {

			$listRate = getListRate();
		}


			// chia trang
		$totalRate = $listRate->num_rows;
		$ratePerPage = 5;
		$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
		$currentLink = create_link(base_url("admin/comment/index.php"), ["page"=>'{page}', 'q'=>$q]);
		$page = paginate($currentLink, $totalRate, $currentPage, $ratePerPage);

			// danh sách nhân viên sau khi chia trang
		if($q != "") {
			$searchResultSQL = $searchSQL . " LIMIT ? OFFSET ?";
			$param = [$key, $page['limit'], $page['offset']];

				// danh sách người dùng sau khi tìm kiếm và chia trang chia trang
			$listRatePaginate = db_get($searchResultSQL, 1, $param, "sii");
		} else {

			$listRatePaginate = getListRate($page['limit'], $page['offset']);
		}


		$totalRatePaginate = $listRatePaginate->num_rows;

			// số thứ tự
		$stt = 1 + (int)$page['offset'];
		?>
		<div class="content_table">
			<table class="table table-hover table-bordered" style="font-size: 13px;" id="mytable">
				<thead>
					<tr>
						<th>STT</th>
						<th>Khách hàng</th>
						<th>Sản phẩm</th>
						<th>Nội dung</th>
						<th>Sao</th>
						<th>Ngày tạo</th>
						<th>Ngày sửa</th>
					</tr>
				</thead>
				<!-- in các đơn hàng -->
				<tbody>
					<?php if ($totalRatePaginate > 0): ?>
						<?php foreach ($listRatePaginate as $key => $rate): ?>
							<tr>
								<!-- mã -->
								<td><?= $stt++; ?></td>

								<!-- Khách hàng-->
								<td><?= $rate['cus_name']; ?></td>

								<!-- sản phẩm -->
								<td><?= $rate['pro_name']; ?></td>

								<!-- Nội dung -->
								<td><?= $rate['r_content']; ?></td>

								<!-- sao -->
								<td>
									<?php for ($i=0; $i < (int)$rate['r_star'] ; $i++): ?>
										<i class="fas fa-star" style="color: yellow;"></i>
									<?php endfor ?>
								</td>

								<!-- ngày tạo -->
								<td><?= strToTimeFormat($rate['r_create_at'], 'H:i:s d-m-Y'); ?></td>

								<!-- ngày cập nhật -->
								<td>
									<?php 
									echo (strtotime($rate['r_update_at']) > strtotime($rate['r_create_at'])) ? 
									strToTimeFormat($rate['r_update_at'], "H:i:s d-m-Y") : "";
									?>
								</td>

							</tr>

						<?php endforeach ?>
					<?php endif ?>
				</tbody>
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
		$('#mytable').DataTable();
	});
</script>

