<?php 
require_once 'common.php';
require_once RF . "/include/header.php";
require_once RF . "/include/navbar.php";
?>
<style>
	.form-control, .custom-select {
		height: 34px !important;
	}

	.img_selected_box img {
		width: 100% !important	;	
	}
</style>

<div class="container-fluid bg-faded p-5 pt-0 d-flex justify-content-center">
	<div class="wrapper_login_register card bg-white shadow w-50">
		<h2 class="text-center card-header">ĐĂNG KÍ TÀI KHOẢN</h2>
		<div class="card-body">
			<form action="	" method="POST" id="registerForm" class="w-100">
				<div  id="backErr" class="alert-danger"></div>

				<div class="form-row">	
					<div class="col-9">	

						<!-- TÊN -->
						<div class="form-group mb-3">
							<div class="input-group">
								<div class="input-group-prepend">
									<label for="name" class="input-group-text">
										<i class="fas fa-user fa-lg"></i>
									</label>
								</div>
								<input type="text" id="name" name="name" class="form-control" placeholder="name">	
							</div>
							<div id="nameErr" class="alert-danger"></div>
						</div>

						<!-- Ngày sinh -->
						<div class="form-group mb-3">
							<div class="input-group">
								<div class="input-group-prepend">
									<label for="dob" class="input-group-text">
										<i class="fas fa-calendar fa-lg"></i>
									</label>
								</div>

								<input type="text" id="dob" name="dob" class="form-control" placeholder="dd-mm-yyyy" autocomplete="off">	
							</div>
							<div id="dobErr" class="alert-danger">

							</div>
						</div>


						<!-- Giới tính -->
						<div class="form-group mb-3">
							<div class="custom-control custom-radio custom-control-inline">
								<input type="radio" id="male" name="gender" class="custom-control-input" value="1">
								<label for="male" class="custom-control-label">NAM</label>
							</div>
							<div class="custom-control custom-radio custom-control-inline">
								<input type="radio" id="female" name="gender" class="custom-control-input" value="0">
								<label for="female" class="custom-control-label">NỮ</label>
							</div>
							<div id="genderErr" class="alert-danger"></div>
						</div>

						<!-- email -->
						<div class="form-group mb-3">
							<div class="input-group">
								<div class="input-group-prepend">
									<label for="email" class="input-group-text">
										<i class="fas fa-envelope fa-lg"></i>
									</label>
								</div>
								<input type="text" id="email" name="email" class="form-control" placeholder="email">	
							</div>
							<div id="emailErr" class="alert-danger">

							</div>
						</div>

						<!-- phone -->
						<div class="form-group mb-3">
							<div class="input-group">
								<div class="input-group-prepend">
									<label for="phone" class="input-group-text">
										<i class="fas fa-phone-alt fa-lg"></i>
									</label>
								</div>
								<input type="text" id="phone" name="phone" class="form-control" placeholder="phone">	
							</div>
							<div id="phoneErr" class="alert-danger">

							</div>
						</div>

						<!-- Địa chỉ -->
						<div class="form-group">
							<select name="tinh" id="tinh" class="custom-select">
								<option value="" hidden>Tỉnh/Thành phố</option>
								<?php 
									$ds_tinh = db_fetch_table('db_tinh', 0);
									foreach ($ds_tinh as $key => $tinh) {
										echo '
										<option value="'.$tinh['name'].'" data-id-tinh="'.$tinh['matp'].'">'.$tinh['name'].'</option>
										';
									}
								 ?>
							</select>
							<div id="tinhErr" class="alert-danger"></div>
						</div>

						<div class="form-group">
							<select name="huyen" id="huyen" class="custom-select">
								<option value="" hidden>Quận/Huyện</option>
							</select>
							<div id="huyenErr" class="alert-danger"></div>
						</div>

						<div class="form-group">
							<select name="xa" id="xa" class="custom-select">
								<option value="" hidden>Phường/Xã</option>
							</select>
							<div id="xaErr" class="alert-danger"></div>
						</div>

						<div class="form-group mb-3">
							<div class="input-group">
								<div class="input-group-prepend">
									<label for="street" class="input-group-text">
										<i class="fas fa-lock fa-lg"></i>
									</label>
								</div>

								<input type="text" name="street" id="street" class="form-control" placeholder="Thôn, xóm, đường...">
							</div>	
							<div id="streetErr" class="alert-danger"></div>
						</div>

						<!-- địa chỉ đầy đủ -->
						<input type="hidden" name="address" id="address">

						<!-- password -->
						<div class="form-group mb-3">
							<div class="input-group">
								<div class="input-group-prepend">
									<label for="pwdRegister" class="input-group-text">
										<i class="fas fa-lock fa-lg"></i>
									</label>
								</div>
								<input type="password" id="pwdRegister" name="pwdRegister" class="form-control" placeholder="Nhập mật khẩu">	
							</div>
							<div id="pwdRegisterErr" class="alert-danger"></div>
						</div>

						<!-- retype password -->
						<div class="form-group mb-3">
							<div class="input-group">
								<div class="input-group-prepend">
									<label for="rePwdRegister" class="input-group-text">
										<i class="fas fa-lock fa-lg"></i>
									</label>
								</div>

								<input type="password" id="rePwdRegister" name="rePwdRegister" class="form-control" placeholder="Nhập lại mật khẩu">	
							</div>

							<div id="rePwdRegisterErr" class="alert-danger"></div>
						</div>
					</div>

					<div class="col-3">
						<!-- avatar -->
						<div class="upload w-100 bg-faded mb-3 text-center">
							<h5 class="mb-4">Ảnh đại diện</h5>
							<input type="file" id="avatar" name="avatar" class="form-control">

							<div id="imgSelectedBox" class="img_selected_box m-1 d-flex flex-wrap">
							</div>

							<div id="avatarErr" class="alert-danger"></div>
						</div>
					</div>
				</div>

				<!-- nút đăng ký -->
				<button class="btn btn-primary btn-block mb-3">ĐĂNG KÝ</button>

				<p class="text-center">Đã có tài khoản? <a href="<?= base_url("login_form.php"); ?>">đăng nhập</a></p>
			</form>
			<div class="container" id="result"></div>

			<script>
				//submit form
				$(function() {
					//hiển thị ảnh khi chọn
					$('#avatar').on('change', function() {
						$('#imgSelectedBox').html("");
						showImg(this, "#imgSelectedBox");
					});


					// datepicker
					/**
					*input: return input tag
					*inst: an object that including datepicker
					*dpDiv : a attribute of inst , it is datepicker
					*/
					$('#dob').datepicker({
						dateFormat: 'dd-mm-yy',
						yearRange: "-100:+0",
						prevText: "Tháng trước",
						nextText: "Tháng sau",
						dayNamesMin: ['T2', 'T3', 'T4', "T5", 'T6', 'T7', "CN"],
						monthNamesShort: ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6", "Tháng 7", "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"],
						changeMonth: true,
						changeYear: true,
						beforeShow: function (input, inst) {
							$(".ui-datepicker-month").insertAfter(".ui-datepicker-year");
							console.log(input, inst);
							setTimeout(function () {
								inst.dpDiv.css({
									top: 386,
									left: 382
								});
							},0);
						}
					});

					$('#dob').change(function() {
						console.log($(this).val());
					});

					// lấy quận, huyện
					$(document).on('change', '#tinh', function() {
						let id_tinh = $(this).find("option:selected").data('id-tinh');
						$('#huyen').load("fetch_unit.php", {id_tinh: id_tinh}, function() {
							$('#xa').html('<option value="">Phường/Xã</option>');
						});
					});

					// phường, xã
					$(document).on('change', '#huyen', function() {
						let id_huyen = $(this).find("option:selected").data('id-huyen');
						$('#xa').load("fetch_unit.php", {id_huyen: id_huyen});
					});


					// submit
					$('#registerForm').on('submit', function(e) {
						e.preventDefault();
						joinAddress();
						// console.log($(this).serializeArray());
						// console.log($('#tinh').val());
						register();
						
					});
				});

				function joinAddress() {
					let tinh = $('#tinh').val();
					let huyen = $('#huyen').val();
					let xa = $('#xa').val();
					let street = $('#street').val().trim();
					let address = [street, xa, huyen, tinh];
					$('#address').val(address.join("-"));
				}
			</script>
		</div>
	</div>
</div>

<?php require_once RF . '/include/footer.php'; ?>