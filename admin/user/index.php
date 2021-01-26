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
			<h5>NHÂN VIÊN</h5>
			<p class="mb-4">Nhân viên là nơi bạn kiểm tra và chỉnh sửa thông tin nhân viên</p>
			<hr>
		</div>

		<div class="row m-0 mb-3">
			<div class="col-12 p-0 d-flex justify-content-between align-items-center">
				<a href="
					<?= base_url('admin/user/add.php'); ?>
					" 
					class="btn btn-success" 
					data-toggle="tooltip" 
					data-placement="top" 
					title="Thêm nhân viên"
				>
					<i class="fas fa-user-plus"></i>
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
				SELECT * FROM db_admin WHERE 
				(
					ad_id LIKE(?) OR
					ad_name LIKE(?) OR 
					ad_uname LIKE(?) OR
					ad_phone LIKE(?) OR
					ad_email LIKE(?) OR
					ad_dob LIKE(?) OR
					ad_phone LIKE(?)
				)
				AND ad_role > 1
				";

				$param = [$key, $key, $key, $key, $key, $key, $key];
				$listUser = db_get($searchSQL, 1, $param, "sssssss");
			} else {

				$listUser = getListUser(1);
			}
			

			// chia trang
			$totalUser = $listUser->num_rows;
			$userPerPage = 5;
			$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
			$currentLink = create_link(base_url("admin/user/index.php"), ["page"=>'{page}', 'q'=>$q]);
			$page = paginate($currentLink, $totalUser, $currentPage, $userPerPage);

			// danh sách nhân viên sau khi chia trang
			if($q != "") {
				$searchResultSQL = $searchSQL . " LIMIT ? OFFSET ?";
				$param = [$key, $key, $key, $key, $key, $key, $key, $page['limit'], $page['offset']];

				// danh sách người dùng sau khi tìm kiếm và chia trang chia trang
				$listUserPaginate = db_get($searchResultSQL, 1, $param, "sssssssii");
			} else {

				$listUserPaginate = getListUser(1, $page['limit'], $page['offset']);
			}

			
			$totalUserPaginate = $listUserPaginate->num_rows;

			// số thứ tự
			$stt = 1 + (int)$page['offset'];
		?>
		<div class="content_table">
			<table class="table table-hover table-bordered" style="font-size: 13px;">
				<tr>
					<th>STT</th>
					<th>Mã</th>
					<th>Username</th>
					<th>Tên</th>
					<th>Ngày sinh</th>
					<th>Giới tính</th>
					<th>Email</th>
					<th>Điện thoại</th>
					<th>Ảnh</th>
					<th>Trạng thái</th>
					<th>Sửa</th>
					<th>Xóa</th>
				</tr>
				<!-- in các đơn hàng -->
				<?php if ($totalUserPaginate > 0): ?>
				<?php foreach ($listUserPaginate as $key => $user): ?>
				<tr>
					<!-- mã -->
					<td><?= $stt++; ?></td>

					<!-- mã -->
					<td><?= $user['ad_id']; ?></td>

					<!-- tên người dùng -->
					<td><?= $user['ad_uname']; ?></td>

					<!-- tên  -->
					<td><?= $user['ad_name']; ?></td>

					<!-- ngày sinh -->
					<td><?= $user['ad_dob']; ?></td>

					<!-- giới tính -->
					<td>
						<?= $user['ad_gender'] ? "Nam" : "Nữ"; ?>
					</td>

					<!-- email -->
					<td><?= $user['ad_email']; ?></td>

					<!-- phone -->
					<td><?= $user['ad_phone']; ?></td>

					<!-- avatar -->
					<td>
						<img src="../../image/<?= $user['ad_avatar']; ?>" width="30px" height="30px">
					</td>

					<!-- active -->
					<td>
						<div class="custom-control custom-switch">
							<input 
								type="checkbox" 
								id="switch_active_<?= $user['ad_id']; ?>" 
								data-user-id="<?= $user['ad_id']; ?>"
								class="btn_switch_active custom-control-input" 
								value="<?= $user['ad_active']; ?>"
								<?= $user['ad_active'] ? "checked" : ""; ?>
							>
							<label for="switch_active_<?= $user['ad_id']; ?>" class="custom-control-label"></label>
						</div>
					</td>

					<!-- edit -->
					<td>
						<a
							href="
							<?= 
								create_link(base_url('admin/user/update.php'), [
									"userid"=>$user['ad_id']
								]);
							?>
							"
							class="btn_edit_user btn btn-success"
							data-user-id="<?= $user['ad_id']; ?>">
							<i class="fas fa-edit"></i>
						</a>
					</td>

					<!-- remove -->
					<td>
						<a 
							class="btn_remove_user btn btn-danger"
							data-user-id="<?= $user['ad_id']; ?>">
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
	
		// Thay đổi trạng thái của nhân viên
		$(document).on('change', '.btn_switch_active', function() {

			// id nhân
			let userID = $(this).data("user-id");

			// trạng thái hiện tại
			let prevActive = $(this).val();
			console.log(prevActive);

			// trạng thái muốn thay đổi
			let newActive = $(this).prop('checked');
			newActive = newActive ? 1 : 0;
			console.log(newActive);

			// gửi yêu cầu thay đổi trạng thái
			let sendSwitchActive = sendAJax(
				"process_user.php",
				"post",
				"json",
				{userID: userID, newActive: newActive, action: "switch_active"}
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


		// xóa người dùng
		$(document).on('click', '.btn_remove_user', function() {

			let wantRemove = confirm("BẠN CÓ MUỐN XÓA NHÂN VIÊN NÀY");

			if(wantRemove) {
				// THỰC HIỆN HÀNH ĐỘNG
				let userID = $(this).data('user-id');
				let prevLink = "<?= getCurrentURL() ?>";
				
				let sendRemove = sendAJax(
					"process_user.php",
					"post",
					"text",
					{userID: userID, action: "remove"}
				);

				// LÀM MỚI TRANG
				// trang trước(chuyển hướng đến sau khi cập nhật -dùng cho update)
				let prevPage = "<?= getCurrentURL(); ?>";

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
