<?php
require_once '../../common.php';
require_once '../include/header.php';
if(!is_login() || !is_admin()) {
	redirect('admin/form_login.php');
}
require_once '../include/sidebar.php';
require_once '../include/navbar.php';
// lấy thông tin khách hàng
$customerID = data_input(input_get("cusid"));
if(!int($customerID)) {
	die('<h1 class="text-center text-danger m-5">KHÔNG TÌM THẤY TRANG</h1>');
}

$customer = get_user_by_id($customerID);
if(!$customer) {
	die('<h1 class="text-center text-danger m-5">KHÔNG TÌM KHÁCH HÀNG</h1>');
}

//vd($customer);
?>
<!-- main content -row -->
<div class="main_content bg-white row m-0 pt-4">
	<div class="col-12">
		<form action="	" method="POST" id="cus_info_edit_form">
			
			<div class="row m-0">
				<div class="col-12 mb-3">
					<a class="" onclick="javascript:history.go(-1)" style="cursor: pointer;">
						<i class="fas fa-angle-left"></i> TRỞ LẠI
					</a>
				</div>
			</div>
			<div class="row m-0">
				<div class="col-9">
					<div  id="backErr" class="alert-danger"></div>
					<!-- name -->
					<div class="form-group mb-3">
						<div class="input-group">
							<div class="input-group-prepend">
								<label for="name" class="input-group-text" style="min-width: 120px;">
									<i class="fas fa-user fa-lg mr-2"></i>Họ tên
								</label>
							</div>
							<input type="text" id="name" name="name" class="form-control" placeholder="name" value="<?= $customer['cus_name']; ?>">
						</div>
						<div id="nameErr" class="alert-danger">
						</div>
					</div>
					<!-- id -->
					<input type="hidden" name="cusID" value="<?= $customer['cus_id']; ?>">
					<!-- date of birth -->
					<div class="form-group mb-3">
						<div class="input-group">
							<div class="input-group-prepend">
								<label for="dob" class="input-group-text" style="min-width: 120px;">
									<i class="fas fa-calendar fa-lg mr-2"></i>Ngày sinh
								</label>
							</div>
							<input type="text" id="dob" name="dob" class="form-control" placeholder="dd-mm-yyyy" autocomplete="off"
							value="<?= formatDate($customer['cus_dob']); ?>">
							<script>
							/**
							*input: return input tag
							*inst: an object that including datepicker
							*dpDiv : a attribute of inst , it is datepicker
							*/
							
							$(document).ready(function() {
							$('#dob').datepicker({
								dateFormat: 'dd-mm-yy',
								yearRange: "-100:2100",
								changeMonth: true,
								changeYear: true,
								beforeShow: function (input, inst) {
									$(".ui-datepicker-month").insertAfter(".ui-datepicker-year");
									console.log(input, inst);
									setTimeout(function () {
										inst.dpDiv.css({
											top: 256,
											left: 280
										});
									},0);
								}
							});
							$('#dob').change(function() {
								console.log($(this).val());
							})
							});
							</script>
						</div>
						<div id="dobErr" class="alert-danger"></div>
					</div>
					<!-- gender -->
					<div class="form-group mb-3">
						<div class="custom-control custom-radio custom-control-inline">
							<input type="radio" id="male" name="gender" class="custom-control-input" value="1"
							<?= $customer['cus_gender'] == 1 ? "checked" : ""; ?>
							>
							<label for="male" class="custom-control-label">Nam</label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input type="radio" id="female" name="gender" class="custom-control-input" value="0"
							<?= $customer['cus_gender'] == 0 ? "checked" : ""; ?>
							>
							<label for="female" class="custom-control-label">Nữ</label>
						</div>
						<div id="genderErr" class="alert-danger"></div>
					</div>
					<!-- email -->
					<div class="form-group mb-3">
						<div class="input-group">
							<div class="input-group-prepend">
								<label for="email" class="input-group-text" style="min-width: 120px;">
									<i class="fas fa-envelope fa-lg mr-2"></i>Email
								</label>
							</div>
							<input type="text" id="email" name="email" class="form-control" placeholder="email"
							value="<?= $customer['cus_email']; ?>"
							>
						</div>
						<div id="emailErr" class="alert-danger">
						</div>
					</div>
					<!-- phone -->
					<div class="form-group mb-3">
						<div class="input-group">
							<div class="input-group-prepend">
								<label for="phone" class="input-group-text" style="min-width: 120px;">
									<i class="fas fa-phone-alt fa-lg mr-2"></i>Điện thoại
								</label>
							</div>
							<input type="text" id="phone" name="phone" class="form-control" placeholder="phone"
							value="<?= $customer['cus_phone']; ?>"
							>
						</div>
						<div id="phoneErr" class="alert-danger">
						</div>
					</div>
					<!-- adddress -->
					<div class="form-group mb-3">
						<label for="address" class="badge badge-success" style="font-size: 15px;">
							<strong>Địa chỉ:</strong>
						</label>

						<textarea name="address" id="address" class="form-control" placeholder="Địa chỉ"
						value="<?= $customer['cus_address']; ?>"><?= $customer['cus_address']; ?></textarea>
						<div id="addressErr" class="alert-danger"></div>
					</div>
					<!-- active -->
					<div class="custom-control custom-switch mb-3">
						<input
						type="checkbox"
						id="active"
						name="active"
						class="custom-control-input"
						<?= $customer['cus_active'] ? "checked" : ""; ?>
						>
						<label for="active" class="custom-control-label badge badge-danger">Trạng thái</label>
					</div>
					
					<!-- update button -->
					<button class="btn_user_edit btn btn-success btn-block mb-3">LƯU</button>
				</div>
				<div class="col-3">
					<div class="upload w-100 bg-faded mb-3 text-center">
						<!-- avatar edit -->
						<div id="user_avatar_edit" class="mb-2">
							<img src="../../image/<?= $customer['cus_avatar']; ?>" width="100%" class="img-thumbnail">
						</div>
						<h5>CHỌN ẢNH</h5>
						<!-- input file avatar -->
						<input type="file" id="avatar" name="avatar" class="form-control" style="overflow: hidden;">
						<input type="hidden" name="oldAvatar" value="<?= $customer['cus_avatar']; ?>">
					</div>
					<script>
						$(function() {
								//hiển thị ảnh khi chọn
								$('#avatar').on('change', function() {
									showImg(this, "#user_avatar_edit", 0);
								});
							});
					</script>
					<div id="avatarErr" class="alert-danger"></div>
				</div>
			</div>
		</div>
	</form>
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
		$(document).on('submit', "#cus_info_edit_form", function(e) {
			e.preventDefault();
			editCustomerInfo();
		});
	});
</script>