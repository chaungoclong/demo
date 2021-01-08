<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title></title>
		<link rel="stylesheet" href="../dist/bootstrap/css/bootstrap.css">
		<link rel="stylesheet" href="../assets/font/css/all.css">
		<script src="../dist/jquery/jquery-3.5.1.js"></script>
		<script src="../dist/bootstrap/js/bootstrap.js"></script>
	</head>
	<body>
		<div class="card">
			<div class="card-header"><strong>SHOPPING CART</strong></div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-hover table-borderless">
						<tr>
							<th width="">ID</th>
							<th colspan="2" width="">Product</th>
							<th width="">Price</th>
							<th width="">Quantity</th>
							<th width="">Total</th>
							<th width="">Action</th>
						</tr>
						<tr>
							<td>1</td>
							<td width="80px">
								<a href="">
									<img src="https://tintaynguyen.com/wp-content/uploads/2020/03/1583688591-8584-to-1-15792449059781593332846.jpg" class="img-thumbnail" width="50px">
								</a>
							</td>
							<td>
								<a href="">
									<h5>Google Pixel 5</h5>
									<h6>smartphone</h6>
									<h6>google</h6>
								</a>
							</td>
							<td class="text-center">1.000.000.000.000</td>
							<td class="text-center">
								<input type="number" class="quantity" value="10" data-pro-id="">
							</td>
							<td class="text-center">10.000.000.000.000</td>
							<td class="text-center">
								<button class="delete btn btn-warning mr-3" data-pro-id=""><i class="far fa-trash-alt"></i></button>
								<button class="delete btn btn-danger" data-pro-id=""><i class="far fa-heart"></i></button>
							</td>
						</tr>
						<hr>
						<tr>
							<td>1</td>
							<td width="80px">
								<a href="">
									<img src="https://tintaynguyen.com/wp-content/uploads/2020/03/1583688591-8584-to-1-15792449059781593332846.jpg" class="img-thumbnail" width="50px">
								</a>
							</td>
							<td>
								<a href="">
									<h5>Google Pixel 5</h5>
									<h6>smartphone</h6>
									<h6>google</h6>
								</a>
							</td>
							<td class="text-center">1.000.000.000.000</td>
							<td class="text-center">
								<input type="number" class="quantity" value="10" data-pro-id="">
							</td>
							<td class="text-center">10.000.000.000.000</td>
							<td class="text-center">
								<button class="delete btn btn-danger mr-3" data-pro-id=""><i class="far fa-trash-alt"></i></button>
								<button class="delete btn btn-danger" data-pro-id=""><i class="far fa-heart"></i></button>
							</td>
						</tr>
						<tr>
							<td colspan="5" class="text-right">TOTAL</td>
							<td class="text-center">10.000.000.000.000</td>
							<td></td>
						</tr>
					</table>
				</div>
			</div>
			<div class="card-footer d-flex justify-content-between">
				<button class="btn btn-primary">Mua tiếp</button>
				<button class="btn btn-warning">Checkout</button>
			</div>
		</div>
		
	</body>
</html>