<?php
require_once '../common.php';
// nếu đã đăng nhập thì về index.php
if(is_login() && is_admin()) {
	redirect('admin/index.php');
}
require_once 'include/header.php';
?>
<style>
	body {
		display: flex;
		justify-content: center;
		align-items: center;
		height: 100Vh;
		background: url('https://i.pinimg.com/originals/76/a8/ea/76a8ea33d8fbc7ed64732b66528b027b.png');
		background-size: cover;
	}
</style>
<div class="wrapper_login_register card bg-light shadow" style="max-width: 600px;">
	<h1 class="text-center card-header">ADMIN</h1>
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
					value="<?= $_COOKIE['ad_user'] ?? ""; ?>"
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
					<input type="password" id="pwdLogin" name="pwdLogin" class="form-control" placeholder="password"
					value="<?= $_COOKIE['ad_pwd'] ?? ""; ?>" >
				</div>
				<div id="pwdLoginErr" class="alert-danger"></div>
			</div>
			<!-- remember password -->
			<div class="custom-control custom-switch mb-3">
				<input type="checkbox" id="remember" name="remember" class="custom-control-input"
				<?= isset($_COOKIE['ad_user']) ? "checked" : ""; ?>
				>
				<label for="remember" class="custom-control-label">Remember me</label>
			</div>
			<!-- login button -->
			<button class="btn btn-primary btn-block mb-3" id="loginBtn" name="loginBtn">ĐĂNG NHẬP</button>
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
</body>
</html>