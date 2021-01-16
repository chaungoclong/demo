<?php
require_once '../common.php';
//check is login
if(!is_login()) {
	redirect("login_form.php");
} else {
	$user = getUserById($_SESSION['user_token']['id']);
}
require_once RF . '/user/include/header.php';
require_once RF . '/user/include/navbar.php';
?>
<main>
	<div class="" style="padding: 0px 85px;">
		<!-- row -->
		<div class="row m-0 py-3">
			<!-- column -->
			<?php require_once 'include/sidebar_user.php'; ?>
			<!-- /column -->
			<!-- colum -->
			<div class="col-9 bg-white py-3">
				<div>
					<h5>ĐỔI MẬT KHẨU</h5>
					<p class="mb-4">Đổi mật khẩu thường xuyên giúp tài khoản của bạn an toàn hơn</p>
					<hr>
				</div>
				<div>
					<form action="" id="change_pwd_form">
						<div class="alert-danger" id="backErr"></div>
						<div class="form-group mb-3">
							<div class="input-group">
								<div class="input-group-prepend">
									<label for="oldPwd" class="input-group-text">
										<i class="fas fa-lock fa-lg"></i>
									</label>
								</div>
								<input type="text" id="oldPwd" name="oldPwd" class="form-control" placeholder="mật khẩu cũ">
							</div>
							<div id="oldPwdErr" class="alert-danger"></div>
						</div>

						<input type="hidden" name="userID" value=<?= $user['cus_id']; ?>>
						<!-- retype password -->
						<div class="form-group mb-3">
							<div class="input-group">
								<div class="input-group-prepend">
									<label for="newPwd" class="input-group-text">
										<i class="fas fa-lock fa-lg"></i>
									</label>
								</div>
								<input type="text" id="newPwd" name="newPwd" class="form-control" placeholder="mật khẩu mới">
							</div>
							<div id="newPwdErr" class="alert-danger"></div>
						</div>

						<button class="btn btn-primary">LƯU</button>
					</form>
				</div>
			</div>
			<!-- /column -->
		</div>
		<!-- /row -->
	</div>
</main>
<?php
require_once RF . '/user/include/footer.php';
?>

<script>
	$(function() {
		$(document).on('submit', '#change_pwd_form', function(e) {
			e.preventDefault();
			userPasswordUpdate();
		});
	});
</script>