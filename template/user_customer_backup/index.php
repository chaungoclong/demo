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
			<div class="col-9 bg-white">main</div>
			<!-- /column -->
		</div>
		<!-- /row -->
	</div>
</main>
<?php
require_once RF . '/user/include/footer.php';
?>
