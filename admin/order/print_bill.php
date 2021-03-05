<?php
require_once '../../common.php';
$orderID = int(input_get('orid'));
	if($orderID === false) {
		header('Location:'.$_SERVER['HTTP_REFERER']);
	}
	$customerInfo = getOrderByID($orderID);
	$orderInfo = getOrderDetailByID($orderID);
	if(!$customerInfo || !$orderInfo) {
		header('Location:'.$_SERVER['HTTP_REFERER']);
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>	</title>
		<link rel="stylesheet" href="../../dist/bootstrap/css/bootstrap.css">
		<script src="../../dist/jquery/jquery-3.5.1.js"></script>
		<script src="../../dist/bootstrap/js/bootstrap.js"></script>
	</head>
	<body onload="window.print()">
		<main style="padding: 50px 100px;">
			
			<div class="card bg-white">
				<div class="row m-0">
					<div class="col-3">
						<img src="https://2.bp.blogspot.com/-8kYxod2Bs9s/VZoSbT1joAI/AAAAAAAAEdo/89Gp_Q257lY/s1600/Hello_Kitty_logo.svg.png" alt="" class="img-fluid">
					</div>
					<div class="col-9">
						<h3 class="pl-3 py-1 text-primary">HÓA ĐƠN BÁN HÀNG</h3>

						<div class="row m-0 mb-1">
							<div class="col-6">
								<div class="bg-white p-2" style="border-radius: 10px">
									<span>Người đặt:</span>
									<span><?= $customerInfo['cus_name']; ?></span>
								</div>
							</div>
							<div class="col-6">
								<div class="bg-white p-2" style="border-radius: 10px">
									<span>Người nhận:</span>
									<span><?= $customerInfo['receiver_name']; ?></span>
								</div>
							</div>
						</div>

						<div class="row m-0 mb-1">
							<div class="col-6">
								<div class="bg-white p-2" style="border-radius: 10px">
									<span>SĐT người đặt:</span>
									<span><?= $customerInfo['cus_phone']; ?></span>
								</div>
							</div>
							<div class="col-6">
								<div class="bg-white p-2" style="border-radius: 10px">
									<span>SĐT người nhận:</span>
									<span><?= $customerInfo['receiver_phone']; ?></span>
								</div>
							</div>
						</div>

						<div class="row m-0">
							<div class="col-12">
								<div class="bg-white p-2 mb-1" style="border-radius: 10px">
									<span>Địa chỉ:</span>
									<span><?= $customerInfo['receiver_add']; ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row px-4 m-0">
					<table class="table table-bordered">
						<thead class="thead-light">
							<th>SẢN PHẨM</th>
							<th>GIÁ</th>
							<th>SỐ LƯỢNG</th>
							<th>THÀNH TIỀN</th>
						</thead>
						<tbody>
							<?php $totalMoney = 0; ?>
							<?php foreach ($orderInfo as $key => $oneOrder): ?>
								<tr>
									<td class="text-primary"><?= $oneOrder['pro_name']; ?></td>
									<td><?= number_format($oneOrder['price']); ?> &#8363</td>
									<td><?= $oneOrder['amount']; ?></td>
									<td><?= number_format($oneOrder['price'] * $oneOrder['amount']); ?> &#8363</td>
								</tr>
								<?php $totalMoney += ($oneOrder['price'] * $oneOrder['amount']); ?>
							<?php endforeach ?>
						</tbody>
					</table>
				</div>
				<div class="row m-0 px-4 d-flex justify-content-end">
					<div class="mb-2">
						<strong>TỔNG CỘNG:</strong>
						<span class="bg-white p-2" style="border-radius: 10px;">
							<?= number_format($totalMoney); ?>&#8363;
						</span>
					</div>
				</div>
			</div>
		</main>
	</body>
</html>