<div class="col-3 bg-faded py-3" id="side_bar">
	<div class="acc_img_name d-flex">
		<div class="acc_img d-flex align-items-center" style="width: 30%;">
			<img src="../image/<?= $user['cus_avatar']; ?>" class="img-thumbnail acc_img_sidebar" style="width: 60px; height: 60px; border-radius: 50%;">
		</div>
		<div class="acc_img pl-3 d-flex flex-column justify-content-center" style="width: 70%;">
			<h5 class="acc_name_sidebar"><?= $user['cus_name']; ?></h5>
			<a href="<?= base_url('user/profile.php'); ?>"><i class="fas fa-edit fa-lg"></i> <span>Sửa hồ sơ</span></a>
		</div>
	</div>
	<hr>
	<ul class="nav flex-column">
		<li class="nav-item mb-2">
			<a href="<?= base_url('user/profile.php'); ?>" class="nav-link">
				<i class="fas fa-user fa-lg"></i>
				TÀI KHOẢN CỦA TÔI
			</a>
		</li>
		<li class="nav-item mb-2">
			<a href="<?= base_url('user/password.php'); ?>" class="nav-link">
				<i class="fas fa-key fa-lg"></i>
				ĐỔI MẬT KHẨU
			</a>
		</li>
		<li class="nav-item mb-2">
			<a href="<?= base_url('user/purchase.php'); ?>" class="nav-link">
				<i class="fas fa-book fa-lg"></i>
				ĐƠN HÀNG CỦA TÔI
			</a>
		</li>
		<li class="nav-item mb-2">
			<a href="<?= base_url('logout.php'); ?>" class="nav-link">
				<i class="fas fa-sign-out-alt fa-lg"></i>
				ĐĂNG XUẤT
			</a>
		</li>
	</ul>
</div>

<script>
	let url_sidebar = window.location.href;
	let link_sidebar = $('#side_bar li.nav-item a');
	
	link_sidebar.each(function(){
		if(this.href == url_sidebar) {
			$(this).css('color', 'red');
		}
	}) ;
</script>