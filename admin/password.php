<?php
require_once '../common.php';
require_once 'include/header.php';
if(!is_login() || !is_admin()) {
	redirect('admin/form_login.php');
} else {
	$user = getUserById($_SESSION['user_token']['id']);
}
require_once 'include/sidebar.php';
require_once 'include/navbar.php';
?>
<!-- main content -row -->
<div class="main_content bg-white row m-0 pt-4">
	<div class="col-12">
		<div class="mb-2">
			<a class="" onclick="window.location='<?= base_url('admin/index.php'); ?>'" style="cursor: pointer;">
				<i class="fas fa-angle-left"></i> TRỞ LẠI
			</a>
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
						<input type="password" id="oldPwd" name="oldPwd" class="form-control" placeholder="mật khẩu cũ">
					</div>
					<div id="oldPwdErr" class="alert-danger"></div>
				</div>
				<input type="hidden" name="userID" value=<?= $user['ad_id']; ?>>
				<!-- retype password -->
				<div class="form-group mb-3">
					<div class="input-group">
						<div class="input-group-prepend">
							<label for="newPwd" class="input-group-text">
								<i class="fas fa-lock fa-lg"></i>
							</label>
						</div>
						<input type="password" id="newPwd" name="newPwd" class="form-control" placeholder="mật khẩu mới">
					</div>
					<div id="newPwdErr" class="alert-danger"></div>
				</div>
				<button class="btn btn-primary">LƯU</button>
			</form>
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
		$(document).on('submit', '#change_pwd_form', function(e) {
			e.preventDefault();
			userPasswordUpdate();
		});
	});
</script>