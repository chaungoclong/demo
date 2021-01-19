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
			<h5>KHÁCH HÀNG</h5>
			<p class="mb-4">Khách hàng là nơi bạn kiểm tra và chỉnh sửa thông tin khách hàng</p>
			<hr>
		</div>
		<!-- lấy đơn hàng -->
		<?php
			$listCustomer = getListUser(0);

			// chia trang
			$totalCustomer = $listCustomer->num_rows;
			$customerPerPage = 5;
			$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
			$currentLink = create_link(base_url("admin/customer/index.php"), ["page"=>'{page}']);
			$page = paginate($currentLink, $totalCustomer, $currentPage, $customerPerPage);

			//đơn hàng sau khi chia trang
			$listCustomerPaginate = getListUser(0, $page['limit'], $page['offset']);
			$totalCustomerPaginate = $listCustomerPaginate->num_rows;

			// số thứ tự
			$stt = 1 + (int)$page['offset'];
		?>
		<div>
			<table class="table table-hover table-bordered" style="font-size: 13px;">
				<tr>
					<th>STT</th>
					<th>Mã</th>
					<th>Tên</th>
					<th>Ngày sinh</th>
					<th>Giới tính</th>
					<th>Email</th>
					<th>Điện thoại</th>
					<th>Ảnh</th>
					<th>Địa chỉ</th>
					<th>Trạng thái</th>
					<th>Sửa</th>
					<th>Xóa</th>
				</tr>
				<!-- in các đơn hàng -->
				<?php if ($totalCustomerPaginate > 0): ?>
				<?php foreach ($listCustomerPaginate as $key => $customer): ?>
				<tr>
					<td><?= $stt++; ?></td>
					<td><?= $customer['cus_id']; ?></td>
					<td><?= $customer['cus_name']; ?></td>
					<td><?= $customer['cus_dob']; ?></td>
					<td>
						<?= $customer['cus_gender'] ? "Nam" : "Nữ"; ?>
					</td>
					<td><?= $customer['cus_email']; ?></td>
					<td><?= $customer['cus_phone']; ?></td>
					<td>
						<img src="../../image/<?= $customer['cus_avatar']; ?>" width="30px" height="30px">
					</td>
					<td><?= $customer['cus_address']; ?></td>
					<td>
						<div class="custom-control custom-switch">
							<input 
								type="checkbox" 
								id="switch_active_<?= $customer['cus_id']; ?>" 
								data-customer-id="<?= $customer['cus_id']; ?>"
								class="btn_switch_active custom-control-input" 
								value="<?= $customer['cus_active']; ?>"
								<?= $customer['cus_active'] ? "checked" : ""; ?>
							>
							<label for="switch_active_<?= $customer['cus_id']; ?>" class="custom-control-label"></label>
						</div>
					</td>
					<td>
						<a
							href="
							<?= 
								create_link(base_url('admin/customer/update.php'), [
									"cusid"=>$customer['cus_id'],
									"from"=>getCurrentURL()
								]);
							?>
							"
							class="btn_edit_customer btn btn-success"
							data-customer-id="<?= $customer['cus_id']; ?>">
							<i class="fas fa-edit"></i>
						</a>
					</td>
					<td>
						<a 
							class="btn_remove_customer btn btn-danger"
							data-customer-id="<?= $customer['cus_id']; ?>">
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

		// Thay đổi trạng thái của khách hàng
		$(document).on('change', '.btn_switch_active', function() {

			// id khách hàng
			let customerID = $(this).data("customer-id");

			// trạng thái hiện tại
			let prevActive = $(this).val();
			console.log(prevActive);

			// trạng thái muốn thay đổi
			let newActive = $(this).prop('checked');
			newActive = newActive ? 1 : 0;
			console.log(newActive);

			let data = {customerID: customerID, newActive: newActive, action: "switch_active"};
			console.log(data);

			// gửi yêu cầu thay đổi trạng thái
			let sendSwitchActive = sendAJax(
				"process_customer.php",
				"post",
				"json",
				{customerID: customerID, newActive: newActive, action: "switch_active"}
			)
			// alert(sendSwitchActive.status);

			// nếu không thành công khôi phục về trạng thái trước đó
			if(sendSwitchActive.status == 1) {
				alert("THIẾU DỮ LIỆU");
				if(prevActive == 1) {
					$("#switch_active_" + customerID).prop("checked", true);
				} else {
					$("#switch_active_" + customerID).prop("checked", false);
				}
			}

			// nếu thành công thay đổi trang thái của nút trạng thái theo trạng thái được trả về
			if(sendSwitchActive.status == 5) {

				// mã khách hàng trả về
				let customerID = sendSwitchActive.customerID;

				// trạng thái trả về
				let resActive = sendSwitchActive.active;
				// alert(resActive);

				// thay đổi trạng thái
				if(resActive == 1) {
					$("#switch_active_" + customerID).prop("checked", true);
				} else {
					$("#switch_active_" + customerID).prop("checked", false);
				}
			}
		});


		$(document).on('click', '.btn_remove_customer', function() {
			let customerID = $(this).data('customer-id');
			let preLink = "<?= getCurrentURL() ?>";
			
			let sendRemove = sendAJax(
				"process_customer.php",
				"post",
				"text",
				{customerID: customerID, action: "remove"}
			);

			alert(sendRemove);
		})
	});
</script>	
