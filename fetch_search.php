<?php 
	require_once 'common.php';
	if(!empty($_POST['action']) && $_POST['action'] == "search") {
		$type = !empty($_POST['type']) ? $_POST['type'] : "product";
		// search
		$keyWord = input_post('keyWord');
		$param = [];
		$format = "";
		if($type == "product") {
			$getResultSQL = "
			SELECT * FROM db_product 
			WHERE pro_active = 1 
			AND CONCAT(pro_name, pro_price, pro_color) LIKE(?)
			";
		} else {
			$getResultSQL = "
			SELECT * FROM db_news 
			WHERE news_active = 1 
			AND CONCAT(news_title, news_desc, news_content) LIKE(?)
			";
		}
		$param[] = $keyWord;
		$format .= "s";

		// sort
		$sort = !empty($_POST['sort']) ? (int)$_POST['sort'] : 1;
		switch ($sort) {
			case 1:
				if($type == "product") {
					$getResultSQL .= " ORDER BY pro_name ASC";
				} else {
					$getResultSQL .= " ORDER BY news_title ASC";
				}
			break;

			case 2:
				if($type == "product") {
					$getResultSQL .= " ORDER BY pro_name DESC";
				} else {
					$getResultSQL .= " ORDER BY news_title DESC";
				}
			break;

			case 3:
			$getResultSQL .= " ORDER BY pro_price ASC";
			break;

			case 4:
			$getResultSQL .= " ORDER BY pro_price DESC";
			break;

			case 5:
				if($type == "product") {
					$getResultSQL .= " ORDER BY pro_create_at ASC";
				} else {
					$getResultSQL .= " ORDER BY create_at ASC";
				}
			break;

			case 6:
				if($type == "product") {
					$getResultSQL .= " ORDER BY pro_create_at DESC";
				} else {
					$getResultSQL .= " ORDER BY create_at DESC";
				}
			break;

			default:
				if($type == "product") {
					$getResultSQL .= " ORDER BY pro_name ASC";
				} else {
					$getResultSQL .= " ORDER BY news_title ASC";
				}
			break;
		}

		// pagination
		$listResult = db_get($getResultSQL, 0, $param, $format);
		$totalResult = count($listResult);
		$resultPerPage = 12;
		$totalPage = ceil($totalResult / $resultPerPage);
		$currentPage = !empty($_POST['currentPage']) ? (int)$_POST['currentPage'] : 1;
		$offset = ($currentPage - 1) * $resultPerPage;

		$getResultSQL .= " LIMIT ? OFFSET ?";
		$param = [...$param, $resultPerPage, $offset];
		$format .= "ii";

		$listResult = db_get($getResultSQL, 0, $param, $format);
		// vd($listResult);
		// echo $getResultSQL;
		// vd($param);
		// echo $format;
	}
?>

<?php if ($type == "product"): ?>
	<section class="product">
	<h5 class="text-center mb-3">
		<?php 	
			$key = str_replace("%", "", $keyWord);
			if($totalResult) {
				echo "Tìm thấy $totalResult kết quả với từ khóa \"$key\"";
			} else {
				echo "Không tìm thấy kết quả với từ khóa \"$key\"";
			}
		?>
	</h5>
	<div class="row m-0 bg-white">
		<?php foreach($listResult as $key => $result): ?>
			<?php if ($result['pro_active']): ?>
			<!-- ------------------------------------product ----------------------------------- -->
			<div class="card text-center col-3">
				<?php if ($result['pro_qty'] == 0): ?>
				<span class="product_status badge badge-pill badge-warning">Bán hết</span>
				<?php endif ?>
				<a href='<?= create_link(base_url("product_detail.php"), ["proid"=> $result["pro_id"]]); ?>'>
					<img src="image/<?= $result['pro_img']; ?>" alt="" class="card-img-top">
				</a>
				<div class="card-body">

					<!-- thông tin sản phẩm -->
					<h5 class="card-title">
						<a href="
							<?php
								echo create_link(
									base_url("product_detail.php"),
									['proid' => $result['pro_id']]
								);
							?>
						">
							<?= $result['pro_name']; ?>
						</a>
					</h5>
					<!-- giá -->
                    <h5 class="badge badge-danger py-1" style="font-size: 15px;">
                      <?= number_format($result['pro_price'], 0, ',', '.'); ?> &#8363;
                    </h5>

                    <!-- sao đánh giá -->
                    <div>
                      <?php $star = getStar($result['pro_id']);?>
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
					<?php if ($result['pro_qty']): ?>
					<a class="btn_add_cart_out btn btn-success text-light" data-pro-id="<?= $result['pro_id']; ?>"
						data-toggle="tooltip" data-placement="top" title="Thêm vào giỏ hàng"
						>
						<i class="fas fa-cart-plus fa-lg"></i>
					</a>
					<?php endif ?>

					<!-- xem chi tiết sản phẩm -->
					<a href='<?= create_link(base_url("product_detail.php"), ["proid"=> $result["pro_id"]]); ?>' class="btn btn-default btn-primary" data-toggle="tooltip" data-placement="top" title="chi tiết sản phẩm">
						<i class="far fa-eye fa-lg"></i>
					</a>
				</div>
			</div>
			<!-- ------------------------------------/product ----------------------------------- -->
			<?php endif ?>
		<?php endforeach; ?>
	</div>
</section>
<?php else: ?>
	<section  id="news">
    <h5 class="text-center mb-3">
		<?php 
			$key = str_replace("%", "", $keyWord);
			if($totalResult) {
				echo "Tìm thấy $totalResult kết quả với từ khóa \"$key\"";
			} else {
				echo "Không tìm thấy kết quả với từ khóa \"$key\"";
			}
		?>
	</h5>
    <div class="list_news">
      <div class="row m-0">
        <?php foreach ($listResult as $key => $result): ?>
        <?php if ($result['news_active']): ?>
          <div class="card col-4">
            <a href='<?= create_link(base_url("news_detail.php"), ["newsid" => $result["news_id"]]); ?>'>
              <img src="image/<?= $result['news_img']; ?>" alt="" class="card-img-top">
            </a>
            <div class="card-body">
              <ul class="card-title nav">
                <li class="nav-item mr-2">
                  <i class="fas fa-calendar-alt"></i>
                  <?php
                  $time = strtotime($result['create_at']);
                  ?>
                  <span><?= read_date($time); ?></span>
                </li>
                <li class="nav-item">
                  <i class="fas fa-user-edit"></i>
                  <span><?= $result['create_by']; ?></span>
                </li>
              </ul>
              <h5 class="card-title text-uppercase">
                <a href='<?= create_link(base_url("news_detail.php"), ["newsid" => $result["news_id"]]); ?>'>
                  <?= $result['news_title']; ?>
                </a>
              </h5>
              <p class="card-text">
                <?= $result['news_desc']; ?>
              </p>
              <a href='<?= create_link(base_url("news_detail.php"), ["newsid" => $result["news_id"]]); ?>' class="btn btn-default btn-primary">Xem thêm<i class="fas fa-angle-double-right"></i></a>
            </div>
          </div>
        <?php endif ?>
      <?php endforeach ?>
      </div>
    </div>
  </section>
<?php endif ?>
<!-- phân trang -->
	<div class="mt-3">
		<?php if ($totalPage): ?>
		<nav aria-label="...">
			<ul class="pagination justify-content-center">

				<!-- previous page link -->
				<?php if ($totalPage > 1 && $currentPage > 1): ?>
				<li class="page-item page-link" data-page-number="<?= $currentPage - 1; ?>">Trước</li>
				<?php endif ?>

				<!-- page link -->
				<?php for($i = 1; $i <= $totalPage; ++$i): ?>
					<?php if ($i != $currentPage): ?>
					<li class="page-item page-link" data-page-number="<?= $i; ?>"><?= $i; ?></li>
					<?php else: ?>
					<li class="page-item active" data-page-number="<?= $i; ?>">
						<span class="page-link">
							<?= $i; ?>
							<span class="sr-only">(current)</span>
						</span>
					</li>
					<?php endif ?>
				<?php endfor; ?>

				<!-- next page link -->
				<?php if ($totalPage > 1 && $currentPage < $totalPage): ?>
				<li class="page-item page-link" data-page-number="<?= $currentPage + 1; ?>">Sau</li>
				<?php endif ?>
			</ul>
		</nav>
		<?php endif ?>
	</div>