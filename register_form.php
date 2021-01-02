<?php require_once 'common.php'; ?>
<?php 
require_once RF . "/include/header.php";
require_once RF . "/include/navbar.php";
?>
<div class="container-fluid bg-dark p-5 d-flex justify-content-center">
	<div class="wrapper_login_register card bg-light shadow" style="max-width: 600px;">
		<h2 class="text-center card-header">ĐĂNG KÍ TÀI KHOẢN</h2>
		<div class="card-body">
			<form action="	" method="POST" id="registerForm" style="width: 400px;">
				<!-- name -->
				<div class="form-group mb-3">
					<div class="input-group">
						<div class="input-group-prepend">
							<label for="name" class="input-group-text">
								<i class="fas fa-user fa-lg"></i>
							</label>
						</div>
						<input type="text" id="name" name="name" class="form-control" placeholder="name">	
					</div>
					<div id="nameErr" class="alert-danger">

					</div>
				</div>

				<!-- date of birth -->
				<div class="form-group mb-3">
					<div class="input-group">
						<div class="input-group-prepend">
							<label for="dob" class="input-group-text">
								<i class="fas fa-calendar fa-lg"></i>
							</label>
						</div>
						<input type="text" id="dob" name="dob" class="form-control" placeholder="dd-mm-yyyy">	

						<script>
							//show date picker
							$(document).ready(function() {
								$('#dob').datepicker({
									dateFormat: "dd-mm-yy",
									changeMonth: true,
									changeYear: true
								});
							});
						</script>
					</div>
					<div id="dobErr" class="alert-danger">
						
					</div>
				</div>
				

				<!-- gender -->
				<div class="custom-control custom-radio custom-control-inline mb-3">
					<input type="radio" id="male" name="gender" class="custom-control-input">
					<label for="male" class="custom-control-label">Nam</label>
				</div>
				<div class="custom-control custom-radio custom-control-inline mb-3">
					<input type="radio" id="female" name="gender" class="custom-control-input">
					<label for="female" class="custom-control-label">Nữ</label>
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
						<input type="text" id="email" name="phone" class="form-control" placeholder="phone">	
					</div>
					<div id="phoneErr" class="alert-danger">
						
					</div>
				</div>

				<!-- password -->
				<div class="form-group mb-3">
					<div class="input-group">
						<div class="input-group-prepend">
							<label for="pwdRegistor" class="input-group-text">
								<i class="fas fa-lock fa-lg"></i>
							</label>
						</div>
						<input type="text" id="pwdRegistor" name="pwdRegistor" class="form-control" placeholder="password">	
					</div>
					<div id="pwdRegistorErr" class="alert-danger"></div>
				</div>

				<!-- retype password -->
				<div class="form-group mb-3">
					<div class="input-group">
						<div class="input-group-prepend">
							<label for="rePwdRegistor" class="input-group-text">
								<i class="fas fa-lock fa-lg"></i>
							</label>
						</div>
						<input type="text" id="rePwdRegistor" name="rePwdRegistor" class="form-control" placeholder="password">	
					</div>
					<div id="rePwdRegistorErr" class="alert-danger"></div>
				</div>

				<!-- avatar -->
				<div class="upload w-100 bg-faded mb-3 text-center">
					<h5 class="p-2">Ảnh đại diện</h5>
					<input type="file" id="avatar" name="avatar" multiple="" class="form-control">

					<div id="imgSelectedBox" class="img_selected_box m-1 d-flex flex-wrap">
					</div>

					<script>
						$(function() {
							//hiển thị ảnh khi chọn
							$('#avatar').on('change', function() {
								$('#imgSelectedBox').html("");
								showImg(this, "#imgSelectedBox", 1);
							});
						});
					</script>
				</div>
				<!-- registor button -->
				<button class="btn btn-primary btn-block mb-3">ĐĂNG KÝ</button>

				<p class="text-center">Đã có tài khoản? <a href="<?= base_url("login_form.php"); ?>">đăng nhập</a></p>
			</form>

			<script>
				$(function() {
					$('#registerForm').on('submit', function(e) {
						e.preventDefault();
						registor();
					})
				})
			</script>
		</div>
	</div>
</div>

<?php require_once RF . '/include/footer.php'; ?>
<script>
	$(document).ready(function() {
		$('#dob').on('change', function() {
			console.log($(this).val());
			console.log(isDate(formatDate($(this).val())));
		});
		var email = "long123@gmail.com";
		console.log(isEmail(email));
	});
</script>