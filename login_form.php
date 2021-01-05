<?php
require_once 'common.php';

//nếu đã đăng nhập thì về index.php
if(is_login()) {
	redirect('index.php');
}
require_once RF . "/include/header.php";
require_once RF . "/include/navbar.php";

?>
<div class="container-fluid bg-faded p-5 d-flex justify-content-center">
	<div class="wrapper_login_register card bg-light shadow" style="max-width: 600px;">
		<h1 class="text-center card-header">ĐĂNG NHẬP</h1>
		<div class="card-body">
			<form action="	" method="POST" id="loginForm" style="width: 400px;">
				<div id="backErr" class="alert-danger"></div>
				<!-- email/phone number -->
				<div class="form-group mb-3">
					<div class="input-group">
						<div class="input-group-prepend">
							<label for="user" class="input-group-text">
								<i class="fas fa-user fa-lg"></i>
							</label>
						</div>
						<input type="text" id="user" name="user" class="form-control" placeholder="Email / Phone number"
						value="<?= $_COOKIE['cus_user'] ?? ""; ?>" 
						>
					</div>
					<div id="userErr" class="alert-danger">
						
					</div>
				</div>
				<!-- password -->
				<div class="form-group mb-3">
					<div class="input-group">
						<div class="input-group-prepend">
							<label for="pwdLogin" class="input-group-text">
								<i class="fas fa-lock fa-lg"></i>
							</label>
						</div>
						<input type="text" id="pwdLogin" name="pwdLogin" class="form-control" placeholder="password"
						value="<?= $_COOKIE['cus_pwd'] ?? ""; ?>" >
					</div>
					<div id="pwdLoginErr" class="alert-danger"></div>
				</div>
				<!-- remember password -->
				<div class="custom-control custom-switch mb-3">
					<input type="checkbox" id="remember" name="remember" class="custom-control-input"
					<?= isset($_COOKIE['cus_user']) ? "checked" : ""; ?>
					>
					<label for="remember" class="custom-control-label">Remember me</label>
				</div>
				<!-- login button -->
				<button class="btn btn-primary btn-block mb-3" id="loginBtn" name="loginBtn">ĐĂNG NHẬP</button>
				<p class="text-center">Chưa có tài khoản? <a href="<?= base_url("register_form.php"); ?>">đăng ký</a></p>
			</form>
			<script>
				$(function() {
					$('#loginForm').on('submit', function(e) {
						e.preventDefault();
						login();
					})
				})
			</script>
		</div>
	</div>
</div>
<?php require_once RF . '/include/footer.php'; ?>