<?php 
require_once 'common.php';
if(!is_login() || is_admin()) {
	redirect("login_form.php");
} elseif(empty($_SESSION['cart'])) {
	redirect("index.php");
}
require_once 'include/header.php';
require_once 'include/navbar.php';

?>

<main>
	<div style="padding-left: 85px; padding-right: 85px;" class="mt-5">
		<!--Section: Block Content-->
		<div class="">
			<!-- row -->
			<div class="card-deck d-flex my-5">
				<!-- column -->
				<div class="card bg-white shadow" style="flex: 6;">
					<div class="card-header">
						<h5>THÔNG TIN THANH TOÁN</h5>
					</div>
					<div class="card-body">
						<?php 
							$customer = getUserById($_SESSION['user_token']['id']);
						 ?>
						<form action="" id="form_check_out">
							<div class="form-group">
								<label for="name">
									<span><i class="fas fa-user"></i></span>
									 Tên người nhận
								</label>
								<input type="text" class="form-control" id="name" name="name" value="<?= $customer['cus_name']; ?>">
								<div class="alert-danger" id="nameErr"></div>
							</div>

							<div class="form-group">
								<label for="phone"><span><i class="fas fa-phone-alt"></i></span> Số điện thoại người nhận</label>
								<input type="text" class="form-control" id="phone" name="phone" value="<?= $customer['cus_phone']; ?>">
								<div class="alert-danger" id="phoneErr"></div>
							</div>

							<div class="form-group">
								<label for="address"><span><i class="fas fa-id-card"></i></span> Địa chỉ người nhận</label>


								<?php 
									$address = $customer['cus_address'];
									$address = explode('-', $address);

									$ten_tinh = $address[3];
									$id_tinh = s_cell("select matp from db_tinh where name=?", [$ten_tinh], "s");

									$ten_huyen = $address[2];
									$id_huyen = s_cell("select maqh from db_huyen where name=?", [$ten_huyen], "s");
									// echo $id_tinh. "-" . $id_huyen;

									$ten_xa = $address[1];

									$street = $address[0];
								?>
								<div class="form-row mb-3">
									<div class="col-4">
										<select name="tinh" id="tinh" class="custom-select" value="<?= $ten_tinh; ?>">
											<option value="" hidden>Tỉnh/Thành phố</option>
											<?php 
											$ds_tinh = db_fetch_table('db_tinh', 0);
											foreach ($ds_tinh as $key => $tinh) {
												if($tinh['name'] == $ten_tinh) {
													echo '
													<option value="'.$tinh['name'].'" data-id-tinh="'.$tinh['matp'].'" selected>'.$tinh['name'].'</option>
													';
												} else {
													echo '
													<option value="'.$tinh['name'].'" data-id-tinh="'.$tinh['matp'].'">'.$tinh['name'].'</option>
													';
												}
											}
											?>
										</select>
										<div id="tinhErr" class="alert-danger"></div>
									</div>

									<div class="col-4">
										<select name="huyen" id="huyen" class="custom-select" value="<?= $ten_huyen; ?>">
											<option value="" hidden>Quận/Huyện</option>
											<?php 
											$ds_huyen = db_get("select * from db_huyen where matp = ?", 0, [$id_tinh], "i"); 
											foreach ($ds_huyen as $key => $huyen) {
												if($huyen['name'] == $ten_huyen) {
													echo '  
													<option value="'.$huyen['name'].'" data-id-huyen="'.$huyen['maqh'].'" selected>'.$huyen['name'].'</option>
													';
												} else {
													echo '  
													<option value="'.$huyen['name'].'" data-id-huyen="'.$huyen['maqh'].'">'.$huyen['name'].'</option>
													';
												}
											}
											?>
										</select>
										<div id="huyenErr" class="alert-danger"></div>
									</div>

									<div class="col-4">
										<select name="xa" id="xa" class="custom-select" value="<?= $ten_xa; ?>">
											<option value="" hidden>Phường/Xã</option>
											<?php 
											$ds_xa = db_get("select * from db_xa where maqh = ?", 0, [$id_huyen], "i"); 
											foreach ($ds_xa as $key => $xa) {
												if($xa['name'] == $ten_xa) {
													echo '  
													<option value="'.$xa['name'].'" selected>'.$xa['name'].'</option>
													';
												} else {
													echo '  
													<option value="'.$xa['name'].'">'.$xa['name'].'</option>
													';
												}
											}
											?>
										</select>
										<div id="xaErr" class="alert-danger"></div>
									</div>
								</div>

								<input type="text" name="street" id="street" class="form-control" placeholder="Thôn, xóm, đường..." value="<?= $street; ?>">
								<div class="alert-danger" id="streetErr"></div>

								<!-- địa chỉ đầy đủ -->
								<input type="hidden" name="address" id="address">
							</div>

							<div class="form-group">
								<label for="notice"><span><i class="fas fa-clipboard"></i></span> Ghi chú</label>
								<textarea class="form-control" id="notice" name="notice"></textarea>
							</div>
						</form>
					</div>
				</div>
				<!-- /column -->

				<!-- column -->
				<div class="card bg-white shadow" style="flex: 4;">
					<div class="card-header d-flex justify-content-between align-items-center">
						<h5 class="m-0">SẢN PHẨM ĐÃ CHỌN</h5>
						<a href="<?= base_url('view_cart.php'); ?>" class="position-relative icon_cart_check">
							<span class="badge badge-pill badge-danger position-absolute">10</span>
							<i class="fas fa-shopping-cart fa-2x"></i>
						</a>
						<script>
							$('.icon_cart_check span').text($('#shoppingCartIndex').text());
						</script>
					</div>
					<div class="card-body table-responsive">
						<table class="table table-borderless table-hover pro_check_out">
							<tr style="font-size: 13px;">
								<th width="65%" colspan="2"><strong>SẢN PHẨM</strong></th>
								<th width="35%"><strong>TỔNG</strong></th>
							</tr>
							<?php if (!empty($_SESSION['cart'])): ?>

								<!-- tổng tiền -->
								<?php $total = 0; ?>

								<?php foreach ($_SESSION['cart'] as $pro_id => $qty): ?>

									<!-- lấy sản phẩm -->
									<?php
										$product = getProductById($pro_id);

										// nếu số lượng sản phẩm hiện tại = 0 || < só lượng sản phẩm trong giỏ
										// xóa sản phẩm đó khỏi giỏ hàng -> lần lặp mới
										if($product['pro_qty'] == 0 || $product['pro_qty'] < $qty) {
											unset($_SESSION['cart'][$pro_id]);
											continue;
										}
									?>

									<!-- in sản phẩm -->
									<tr>
										<td width="20%" class="p-0 pb-1">
											<a href="
												<?= create_link(base_url('product_detail.php'), ['proid'=>$pro_id]); ?>
											">
												<img src="image/<?= $product['pro_img']; ?>" alt="" class="img-thumbnail" width="100%">
											</a>
										</td>

										<td width="45%" class="py-0 m-0">
											<h6 class="pro_check_name">
												<a href="  
													<?= create_link(base_url('product_detail.php'), ['proid'=>$pro_id]); ?>
												">
													<?= $product['pro_name']; ?>
												</a>
											</h6>

											<p class="p-0 m-0 ">giá: 
												<span>
													<?= number_format($product['pro_price'], 0, ",", "."); ?> &#8363;
												</span>
											</p>

											<p>số lượng:
											 <span><?= $qty; ?></span>
											</p>
									</td>

									<td width="35%">
										<span>
											<?= number_format((int)$qty * (int)$product['pro_price'], 0, ",", ".") ; ?>
											&#8363;
										</span>
									</td>
									<?php $total += (int)$qty * (int)$product['pro_price']; ?>
								</tr>
							<?php endforeach ?>

							<tr style="font-size: 14px;">
								<td colspan="3" align="right">
									TỔNG TIỀN: 
									<span><?= number_format($total, 0, ",", "."); ?> &#8363;</span>
								</td>
							</tr>
						<?php endif ?>
					</table>
				</div>
				<div class="card-footer">
					<button class="btn btn-block btn-success" id="btn_order" 
					<?= empty($_SESSION['cart']) ? "disabled" : ""; ?>>
						ĐẶT HÀNG
					</button>
				</div>
			</div>
			<!-- /column -->
		</div>
		<!--/ row -->
	</div>
</div>
</main>

<?php require_once 'include/footer.php'; ?>

<script>
	$(function() {
		// lấy quận, huyện
		$(document).on('change', '#tinh', function() {
			let id_tinh = $(this).find("option:selected").data('id-tinh');
			$('#huyen').load("fetch_unit.php", {id_tinh: id_tinh}, function() {
				$('#xa').html('<option value="">Phường/Xã</option>');
			});
		});

		// phường, xã
		$(document).on('change', '#huyen', function() {
			let id_huyen = $(this).find("option:selected").data('id-huyen');
			$('#xa').load("fetch_unit.php", {id_huyen: id_huyen});
		});

		// validate -> đặt hàng
		$(document).on('click', '#btn_order', function() {
			joinAddress();
			//console.log($)
			let test = true;

			// xóa các class lỗi khỏi thẻ input
			$('#name').removeClass('error_field');
			$('#phone').removeClass('error_field');
			$('#address').removeClass('error_field');
			$('#tinh').removeClass('error_field');
			$('#huyen').removeClass('error_field');
			$('#xa').removeClass('error_field');

			// đặt giá trị trong ô thông báo lỗi về ''
			$('#nameErr').text('');
			$('#phoneErr').text('');
			$('#rcvAddErr').text('');
			$('#tinhErr').text('');
			$('#huyenErr').text('');
			$('#xaErr').text('');

			// lấy giá trị
			let name   = $('#name').val().trim();
			let phone  = $('#phone').val().trim();
			let street = $('#street').val().trim();
			let tinh   = $('#tinh').val().trim();
			let huyen  = $('#huyen').val().trim();
			let xa     = $('#xa').val().trim();

			// validate name
			if(name == '') {
				$('#nameErr').text('không được để trống');
				$('#name').addClass('error_field');
				test = false;
			} else if(!isName(name)) {
				$('#nameErr').text('sai định dạng');
				$('#name').addClass('error_field');
				test = false;
			}

			// validate phone
			if(phone == '') {
				$('#phoneErr').text('không được để trống');
				$('#phone').addClass('error_field');
				test = false;
			} else if(!isPhone(phone)) {
				$('#phoneErr').text('sai định dạng');
				$('#phone').addClass('error_field');
				test = false;
			}

			// tỉnh
			if(tinh == '') {
				$('#tinhErr').text('không được để trống');
				$('#tinh').addClass('error_field');
				test = false;
			}

			// huyện
			if(huyen == '') {
				$('#huyenErr').text('không được để trống');
				$('#huyen').addClass('error_field');
				test = false;
			}

			// xã
			if(xa == '') {
				$('#xaErr').text('không được để trống');
				$('#xa').addClass('error_field');
				test = false;
			}

			// street
			if(street == '') {
				$('#streetErr').text('không được để trống');
				$('#street').addClass('error_field');
				test = false;
			}

			if(!test) {
				$('.error_field').first().focus();
			} else {

				// nếu thông tin không sai kiểm tra có được đặt hàng(số lượng sản phẩm có đủ)
				let checkOutOK = sendAJax(
					'get_cart.php',
					'post',
					'text',
					{action:"check_out"}
				);
				console.log(checkOutOK);

				// ok -> đặt hàng + bật nút đặt hàng
				if(checkOutOK == '1') {
					$('#btn_order').prop('disabled', false);

					let data = $('#form_check_out').serialize();
					let sendAjax = sendAJax(
						'process_check_out.php',
						'post',
						'json',
						data
					);

					switch(sendAjax.status) {
						case 1:
							alert("THIẾU THÔNG TIN");
							break;
						case 2:
							alert("THÔNG TIN SAI");
							break;
						case 5:
							// alert("ĐẶT HÀNG THÀNH CÔNG");
							let link = "order_success.php?orid=" + sendAjax.orID;
							window.location = link;
							break;
						case 6:
							alert("ĐẶT HÀNG THẤT BẠI. VUI LÒNG THỬ LẠI");
							break;
					}
				} else {

					// no -> hiển thị thông báo lỗi
					alert("CÓ SẢN PHẨM TRONG GIỎ ĐÃ HẾT HÀNG HOẶC SỐ LƯỢNG TỒN KHO KHÔNG ĐỦ");
					$('#btn_order').prop('disabled', true);
					window.location = "view_cart.php";
				}
				
			}

		});
	});

	function joinAddress() {
		let tinh = $('#tinh').val();
		let huyen = $('#huyen').val();
		let xa = $('#xa').val();
		let street = $('#street').val().trim();
		let address = [street, xa, huyen, tinh];
		$('#address').val(address.join("-"));
	}
</script>