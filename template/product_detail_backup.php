<?php
require_once 'common.php';

//ID nhận được
$proID = data_input(input_get("proid"));

//lấy sản phẩm có ID nhận được
$product   = getProductById($proID);

$catID     = $product['cat_id'];
$catStatus = getCategoryByID($catID);

$braID     = $product['bra_id'];
$braStatus = getBrandByID($braID);

if(!$catStatus['cat_active'] || !$braStatus['bra_active']) {
	redirect('product.php');
}

require_once 'include/header.php';
require_once 'include/navbar.php';
?>

<main>
	<!-- In thông tin sản phẩm có id = id nhận được -->
	<?php

	
	//lấy các ảnh của sản phẩm, thể loại, hãng
	if(!empty($product)) {
		$getImgProSQL = "SELECT img_url FROM db_image WHERE pro_id = ?";
		$listImg = db_get($getImgProSQL, 0, [$proID], "i");
		
		$getCatProSQL = "SELECT cat_name FROM db_category WHERE cat_id = ?";
		$category = s_cell($getCatProSQL, [$product['cat_id']], "i");
		
		$getBraProSQL = "SELECT bra_name FROM db_brand WHERE bra_id = ?";
		$brand = s_cell($getBraProSQL, [$product['bra_id']], "i");
	}
	?>
	
	<div style="padding-left: 85px; padding-right: 85px;" class="">
		<section>
			<div class="row p-0 m-0 bg-light">
				<!-- product image wrapper -->
				<div id="product_image" class="col-6 p-3">
					<!-- product image content -->
					<div class="card text-center py-3 h-100">
						<div class="p-0 m-0">
							<div id="carousel-list-img" class="card card-body border-0 carousel slide" data-ride="carousel" data-interval="false">
								<!-- nội dung -->
								<div class="carousel-inner">
									<?php if (!empty($listImg)): ?>
										<?php foreach ($listImg as $key => $img): ?>
											<div class="<?= $key == 0 ? 'carousel-item active' : 'carousel-item' ?>">
												<img src="image/<?= $img['img_url']; ?>" class="card-img-top">
											</div>
										<?php endforeach ?>
									<?php endif ?>
								</div>

								<!-- điều khiển -->
								<div href="#carousel-list-img" class="carousel-control-prev" data-slide="prev">
									<div class="carousel-control-prev-icon"></div>
								</div>
								<div href="#carousel-list-img" class="carousel-control-next" data-slide="next">
									<div class="carousel-control-next-icon"></div>
								</div>

								<!-- chỉ mục -->
								<div class="bg-white p-2"><strong class="img-current-id"></strong></div>
								<div class="carousel-indicators" style="bottom: -20px; display: none;">
									<?php for($i = 0; $i < count($listImg); $i++): ?>
										<li data-target="#carousel-list-img" data-slide-to="<?= $i ?>" <?= !$i ? "class='active'" : ""; ?>>
										</li>
									<?php endfor ?>
								</div>
							</div>

						</div>
						<script>
							

						</script>
					</div>
					<!-- /product image content -->
				</div>
				<!-- /product image wrapper -->
				<div class="col-6 p-3">
					<div class="card h-100">
						<div id="notice" class="card-header text-center"></div>
						<div class="card-body">
							<!-- product name -->
							<h2 style="color:blue;" class="card-title">
								<strong>
									<?= ucwords(strtolower($product['pro_name'])); ?>
								</strong>
							</h2>
							<div class="rateAvg"></div>
							<hr>
							<h3 class="card-text my-4" style="color:red;">
								<strong>
									<?= !empty($product['pro_price']) ?
									number_format($product['pro_price'], 0, ",", ".") : ""; ?>
									&#8363;
								</strong>
							</h3>
							<div class="table-responsive mb-3">
								<table class="table table-sm table-borderless mb-0">
									<tr>
										<th class="pl-0 w-50" scope="row"><strong>Loại sản phẩm:</strong></th>
										<td><?= !empty($category) ? $category : ""; ?></td>
									</tr>
									<tr>
										<th class="pl-0 w-50" scope="row"><strong>Hãng sản xuất:</strong></th>
										<td><?= !empty($brand) ? $brand : ""; ?></td>
									</tr>
									<tr>
										<th class="pl-0 w-50" scope="row"><strong>Màu:</strong></th>
										<td>
											<?= !empty($product['pro_color']) ?
											$product['pro_color'] : ""; ?>
										</td>
									</tr>
									<tr>
										<th class="pl-0 w-25" scope="row"><strong>Tình trạng:</strong></th>
										<td>
											<?= $product['pro_qty'] ?
											"còn hàng(" . $product['pro_qty'] . " sản phẩm)": "hết hàng"; ?>
										</td>
									</tr>
								</table>
							</div>
							<hr>
							<div class="action border-1">
								<div class="get_quantity d-flex mb-3">
									<button class="minus btn"><i class="fas fa-minus"></i></button>
									<input type="number" min="0" name="quantity" value="1" class="quantity text-center">
									<button class="plus btn"><i class="fas fa-plus"></i></button>
								</div>
								<button class="btn_add_cart btn btn-primary"><strong>THÊM VÀO GIỎ</strong></button>
								<button class="btn_wishlist btn btn-danger"><strong><i class="fas fa-heart"></i></strong></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		
		<!-- tab info -->
		<section id="product_tab" class="border p-3 my-5">
			<!-- tab index -->
			<ul class="nav nav-justified nav-tabs">
				<li class="nav-item">
					<a href="#desc" class="nav-link active" data-toggle="tab">MÔ TẢ</a>
				</li>
				<li class="nav-item">
					<a href="#info" class="nav-link" data-toggle="tab">THÔNG SỐ KỸ THUẬT</a>
				</li>
				<li class="nav-item">
					<a href="#rate" class="nav-link" data-toggle="tab">ĐÁNH GIÁ (<span class="qtyRate"></span>)</a>
				</li>
			</ul>
			<!-- tab content -->
			<div class="tab-content">
				<div id="desc" class="active tab-pane p-3">
					<h6><strong>MÔ TẢ</strong></h6>

					<div id="moreText" style="height: 300px; overflow: hidden;">
						<?= !empty($product['pro_desc']) ? $product['pro_desc'] : "";  ?>
					</div>

					<div class="d-flex justify-content-center">
						<button class="btn btn-primary dropdown-toggle" id="toggleShow">Xem thêm</button>
					</div>

					<script>
						$(function() {
							$(document).on('click', '#toggleShow', function() {
								let textValue = $(this).text();
								if(textValue == "Xem thêm") {
									$(this).text("Ẩn bớt");
									$('#moreText').css('height', '100%');
								} else {
									$(this).text("Xem thêm");
									$('#moreText').css('height', '300px');

								}
							});
						});
					</script>
				</div>
				<div id="info" class="tab-pane fadt p-3">
					<h6><strong>THÔNG SỐ KĨ THUẬT</strong></h6>
					<?= !empty($product['pro_detail']) ? $product['pro_detail'] : "";  ?>
				</div>
				<div id="rate" class="tab-pane fade py-3">
					<!-- đánh giá trung bình-->
					<div class="rate_title row m-0 mb-5">
						<div class="col-12 p-0 mb-3">
							<h5 class="text-uppercase">có <span class="qtyRate"></span> đánh giá cho <?= $product['pro_name']; ?></h5>
						</div>
						<div class="col-12 p-0 rateAvg">
						</div>

					</div>

					<!-- tạo đánh giá -->
					<div class= "send_rate row m-0 mb-5 bg-white shadow">

						<div class="col-4 text-center py-3">
							<p class="p-0">
								<strong>Bạn đánh giá sản phẩm này bao nhiêu sao?</strong>
							</p>
							<!-- chọn số sao -->
							<div class="choose_star">
								<button class="btn starr m-1" data-rate="1" id="star_rate_1">
									<i class="fas fa-star"></i>
								</button>
								<button class="btn starr m-1" data-rate="2" id="star_rate_2">
									<i class="fas fa-star"></i>
								</button>
								<button class="btn  starr m-1" data-rate="3" id="star_rate_3">
									<i class="fas fa-star"></i>
								</button>
								<button class="btn  starr m-1" data-rate="4" id="star_rate_4">
									<i class="fas fa-star"></i>
								</button>
								<button class="btn  starr m-1" data-rate="5" id="star_rate_5">
									<i class="fas fa-star"></i>
								</button>
							</div>
						</div>

						<!-- viết bình luận -->
						<div class="create_rate col-8 py-3">
							<form action="" id="formRate" class="w-100 position-relative">
								<div class="form-group">
									<textarea class="form-control" rows="3" cols="55" id="rateContent" name="rateContent"></textarea>
									<input type="hidden" id="rateValue" name="rateValue">
								</div>

								<button class="btn btn-primary position-absolute" id="sendRate" type="button"
								style="top: 20%; right: 10px;">SEND</button>
							</form>
						</div>
					</div>
					<!-- /tạo đánh giá -->
					
					<!-- hiển thị các đánh giá -->
					<div class="show_rate">
						<!-- display rate -->
					</div>
					<!-- /hiển thị các đánh giá -->
				</div>
			</div>
		</section>
		<!-- /tab info -->

		<!-- product -->
		<?php
		$getRelatedProSQL = "
		SELECT * FROM db_product
		WHERE 
			cat_id = ? 
			AND pro_id != ? 
			AND pro_price BETWEEN ? - 2000000 AND ? + 2000000
			AND pro_active = 1
		";
		$listRelatedPro = db_get(
			$getRelatedProSQL,
			0,
			[$product['cat_id'], $product['pro_id'], $product['pro_price'], $product['pro_price']],
			"iiii"
		);
		?>

		<section class="product py-5">
			<h2 class="text-center mb-3">SẢN PHẨM LIÊN QUAN</h2>
			<div class="list_product_body">
				<div class="owl-carousel owl-theme">
					<?php if (!empty($listRelatedPro)): ?>
						<?php foreach ($listRelatedPro as $key => $relatedPro): ?>
							<div class="card text-center">
								<?php if ($relatedPro['pro_qty'] == 0): ?>
									<span class="product_status badge badge-warning"><strong>Bán hết</strong></span>
								<?php endif ?>
								<a href='<?= create_link(base_url("product_detail.php"), ["proid"=> $relatedPro["pro_id"]]); ?>' class="d-flex justify-content-center">
									<img src="image/<?= $relatedPro['pro_img']; ?>" alt="" class="card-img-top">
								</a>
								<div class="card-body">

									<!-- tên -->
									<h5 class="card-title">
										<a href="<?= create_link(base_url("product_detail.php"), ['proid' => $relatedPro['pro_id']]); ?>">
											<?= $relatedPro['pro_name']; ?>
										</a>
									</h5>

									<!-- giá -->
									<h5 class="badge badge-danger py-1" style="font-size: 15px;">
										<?= number_format($relatedPro['pro_price'], 0, ',', '.'); ?> &#8363;
									</h5>

									<!-- sao đánh giá -->
									<div class="">
										<?php $star = getStar($relatedPro['pro_id']);?>
										<?php if ($star['timeRate']): ?>
											<span class="" style="color: yellow;">
												<?php showStar($star['star']); ?>
											</span>
											<span>
												<?php echo "(" . $star['timeRate'] . " đánh giá)"; ?>
											</span>
										<?php endif ?>
									</div>

									<hr class="my-2">

									<!-- thêm vào giỏ hàng -->
									<?php if ($relatedPro['pro_qty']): ?>
										<a class="btn_add_cart_out btn btn-success text-light" data-pro-id="<?= $relatedPro['pro_id']; ?>"
											data-toggle="tooltip" data-placement="top" title="Thêm vào giỏ hàng"
											>
											<i class="fas fa-cart-plus fa-lg"></i>
										</a>
									<?php endif ?>

									<!-- xem chi tiết sản phẩm -->
									<a href='<?= create_link(base_url("product_detail.php"), ["proid"=> $relatedPro["pro_id"]]); ?>' class="btn btn-default btn-primary" data-toggle="tooltip" data-placement="top" title="chi tiết sản phẩm">
										<i class="far fa-eye fa-lg"></i>
									</a>
								</div>
							</div>
						<?php endforeach ?>
					<?php endif ?>
				</div>
			</div>
		</section>
<!-- /product -->
</div>
</main>
<?php require_once 'include/footer.php'; ?>
<script>
	$(function() {

		// băng chuyền sản phẩm liên quan
		$(".owl-carousel").owlCarousel({
			autoHeight: true,
			items:4,
			nav: true,
			smartSpeed: 900,
			navText: ["<i class=' btn fa fa-chevron-left'></i>","<i class='btn fa fa-chevron-right'></i>"]
		});

		// slide ảnh sản phẩm
		showCurrentImgID();
		$(document).on('click', '.carousel-control-prev, .carousel-control-next', function() {
			showCurrentImgID();
		});

		// ==================XỬ LÝ GIỎ HÀNG==========================//
		//send cart
		$('.btn_add_cart').on('click', function() {
			//mã sản phẩm
			let proID     = <?php echo $proID; ?>;
			//số lượng sản phẩm
			let qtyPro    = parseInt(<?php echo $product['pro_qty']; ?>);
			//số lượng sản phẩm được chọn
			let qtySelect = parseInt($('.quantity').val());
			//số lượng sản phẩm này trong giỏ hàng
			let qtyProInCart;

			qtyProInCart = parseInt(
				sendAJax(
					'get_cart.php',
					'post',
					'text',
					{proid:proID, action:"pro_cart_qty"}
					)
				);

			if(isNaN(qtyPro) || qtyPro <= 0) {
				//nếu số lượng sản phẩm không hợp lệ || <= 0
				alert("HẾT HÀNG");
			} else if(isNaN(qtySelect) || qtySelect <= 0) {
				//nếu số lượng sản phẩm được chọn không hợp lệ
				//|| == 0
				alert("VUI LÒNG CHỌN ÍT NHẤT 1 SẢN PHẨM");
			} else if(qtySelect + qtyProInCart > qtyPro) {
				//nếu sản phẩm đã có trong giỏ hàng: số lượng sản phẩm 
				//được chọn + số sản phẩm trong giỏ hàng > số sản phẩm
				//hiện có của sản phẩm
				alert("SỐ LƯỢNG SẢN PHẨM KHÔNG ĐỦ");
			} else {
				let action = "add";
				let data = {proid:proID, quantity:qtySelect, action:action};
				let sendCart = $.ajax({
					url: "cart.php",
					data: data,
					method: "POST",
					dataType: "json"
				});

				//success
				sendCart.done((res) => {
					if(res.totalItem > 0) {
						$('#shoppingCartIndex').text(res.totalItem);
						$('#modal_cart').show().find('.badge').text(res.totalItem);
					} else {
						$('#shoppingCartIndex').text(0);
						$('#modal_cart').hide();
					}
					alert(res.notice);
				});

				//error
				sendCart.fail((a, b, c) => {
					console.log(a, b, c);
				});
			}
		});



		// ==================XỬ LÝ ĐÁNH GIÁ==========================//
		var proID = <?= $product['pro_id']; ?>;

		//lấy bình luận ngay khi vào trang
		fetchRate(proID);

		//xử lý chọn sao
		$('.starr').on('click', function() {

			//danh sách sao
			let listStar = $('.starr');

			//giá trị của lần chọn này
			let value = $(this).data('rate');

			//thay đổi giá trị trong thẻ input
			$('#rateValue').val(value);
			
			//bỏ trạng thái được chọn của tất cả các sao
			for(let i = 0; i < 5; ++i) {
				$(listStar[i]).removeClass('star_selected');
			}

			//đặt trạng thái được chọn cho các sao từ 0 -> value
			for(let i = 0; i < value; ++i) {
				$(listStar[i]).addClass('star_selected');
			}
		});

		//XỬ LÝ THÊM , CẬP NHẬT ĐÁNH GIÁ
		$('#sendRate').on('click', function() {
			//e.prenventDefault();
			let checkLogin = <?= is_login() ? 1 : 0; ?>;
			if(checkLogin == 0) {
				alert("BẠN CHƯA ĐĂNG NHẬP");
			} else {
				let rateContent = $('#rateContent').val();
				let rateValue = $('#rateValue').val();
				let cusID = <?= 
				isset($_SESSION['user_token']['id']) ?
				$_SESSION['user_token']['id'] : 0; 
				?>;
				let proID = <?= $product['pro_id']; ?>;

				if(rateContent == '') {
					alert('VUI LÒNG NHẬP BÌNH LUẬN');
				} else if(rateValue == '') {
					alert('VUI LÒNG CHỌN SAO ĐÁNH GIÁ');
				} else {

					//kiểm tra đánh giá của người cusID về sản phẩm proID đã tồn tại chưa
					let checkRateExist = rateExist(cusID, proID, "rate_exist");
					if(checkRateExist) {
						//cập nhât lại đánh giá nếu đánh giá đã tồn tại và người dùng muốn cập nhật
						if(confirm("BẠN CÓ MUỐN CẬP NHẬT LẠI BÌNH LUẬN CỦA BẠN VỀ SẢN PHẨM NÀY")) {
							let resultUpdate = setRate(
								cusID, proID, rateValue, rateContent, "update_rate"
								);
							alert(resultUpdate.msg);
							fetchRate(proID);
						}
					} else {
						//nếu đánh giá chưa tồn tại thì thêm mới đánh giá
						let resultAdd = setRate(
							cusID, proID, rateValue, rateContent, "add_rate"
							);
						alert(resultAdd.msg);
						fetchRate(proID);
					}
				}
			}
		});
	});

	function showCurrentImgID() {
		let currentImg = $('.carousel-indicators li.active').data('slide-to') + 1;
		let totalImg = <?= count($listImg); ?>;
		if(totalImg) {
			$('.img-current-id').text("Ảnh " + currentImg + " / " + totalImg);
		} else {
			$('.img-current-id').html("KHÔNG CÓ ẢNH"+"<br>"+"đang cập nhật...");
		}
	}

	function rating() {
		
	}
		
</script>