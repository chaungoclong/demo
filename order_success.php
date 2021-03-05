<?php
require_once 'common.php';
require_once RF . '/include/header.php';
require_once RF . '/include/navbar.php';
?>
<main>
	<div class="" style="padding: 85px 85px; background: url('image/order_success_bg.png') no-repeat; background-size: cover;">
		<div class="container d-flex justify-content-center">
			<div class="card text-center shadow p-0" style="width: 60%;">
				<div class="card-body text-center d-flex flex-column align-items-center">
					<div class="jumbotron bg-white pb-1">
						<h2 class="text-primary">ĐẶT HÀNG THÀNH CÔNG!!!</h2>
						<h5 class="text-secondary">Chúng tôi sẽ giao hàng với thời gian ngắn nhất cho quý khách</h5>
					</div>
                    	<img src="image/or.png" width="100px" class="mb-5">
					<div class="btn-group w-75">
						<?php 
							$orID = isset($_GET['orid']) ? $_GET['orid'] : 1;
						 ?>
						<a class="btn btn-primary w-50" href="<?= base_url('product.php'); ?>"><strong>MUA THÊM</strong></a>
						<a class="btn btn-warning w-50" href="<?= create_link(base_url('user/order_detail.php'), ["orid"=>$orID]); ?>"><strong>XEM ĐƠN HÀNG CỦA BẠN</strong></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?php
require_once RF . '/include/footer.php';
?>