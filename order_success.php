<?php
require_once 'common.php';
require_once RF . '/include/header.php';
require_once RF . '/include/navbar.php';
?>
<main>
	<div class="" style="padding: 85px 85px; background: url('https://i.pinimg.com/originals/54/21/e8/5421e85cea71552f18839d83d3e22c25.png') no-repeat; background-size: cover;">
		<div class="container d-flex justify-content-center">
			<div class="card text-center w-75">
				<div class="card-body text-center d-flex flex-column align-items-center">
					<div class="jumbotron bg-white pb-1">
						<h1>ĐẶT HÀNG THÀNH CÔNG!!!</h1>
						<p>Chúng tôi sẽ giao hàng với thời gian ngắn nhất cho quý khách</p>
					</div>
                    	<img src="https://www.freeiconspng.com/thumbs/success-icon/success-icon-10.png" width="100px" class="mb-5">
					<div class="btn-group w-75">
						<?php 
							$orID = isset($_GET['orid']) ? $_GET['orid'] : 1;
						 ?>
						<a class="btn btn-success w-50" href="<?= base_url('product.php'); ?>">xem thêm</a>
						<a class="btn btn-primary w-50" href="<?= create_link(base_url('user/order_detail.php'), ["orid"=>$orID]); ?>">xem đơn hàng của bạn</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?php
require_once RF . '/include/footer.php';
?>