<main>
  <div style="padding-left: 85px; padding-right: 85px;">
    <!--===========================================category ===================================================-->
    <section id="category" class="py-5">
      <h2 class="text-center mb-3">DANH MỤC</h2>
      <?php
      $getListCatSQL = "SELECT * FROM db_category WHERE cat_id IN(
        SELECT cat_id FROM db_product
      )
      AND cat_active = 1";
      $listCategory = db_get($getListCatSQL);
      ?>
      <!-- ?in các hãng? -->
      <div class="row">
        <?php foreach ($listCategory as $key => $category): ?>
         <?php if ($category['cat_active']): ?>
          <!-- ?in cột? -->
          <div class="category_item card col-3" style="max-width: 25%;">
            <a href="<?= create_link(base_url('product.php'), ['cat'=> $category["cat_id"]]); ?>" class="card-body">
              <img src="image/<?= $category['cat_logo']; ?>" alt="" class="card-img-top">
              <div class="category_info bg-light">
                <h5><?= $category['cat_name']; ?></h5>
                <!-- ?lấy số lượng sản phẩm? -->
                <?php
                $catID   = $category['cat_id'];
                $proQty = s_cell("SELECT COUNT(*) FROM db_product WHERE cat_id = ?", [$catID], "i");
                ?>
                <p><?= $proQty; ?> sản phẩm</p>
              </div>
            </a>
          </div>
          <!-- /in cột -->
        <?php endif ?>
      <?php endforeach ?>
      </div>
    </section>
    <!--=======================================/category ===========================================--->
    <!--=========================================== product =========================================-->
    <?php foreach ($listCategory as $key => $category): ?>
      <!-- nếu active thì hiển thị danh mục -->
      <?php if ($category['cat_active']): ?>
        <section class="product py-5">
          <h2 class="text-center mb-3"><?= $category['cat_name']; ?></h2>
          <div class="list_product_body">
            <?php
              $catID   = $category['cat_id'];
              $getProSQL = "
              SELECT * FROM db_product
              WHERE cat_id = ?
              AND pro_active = 1
              ORDER BY pro_id DESC
              ";
              $listPro = db_get($getProSQL, 0, [$catID], "i");
              $proQty = count($listPro);
            ?>
            <!-- list products bar -->
            <div class="product_bar bg-info px-2 py-2 d-flex justify-content-between">
              <span class="badge  bg-faded"><?= $proQty; ?> sản phẩm</span>
              <a href="<?= create_link(base_url("product.php"), ["cat"=> $catID]); ?>" class="badge badge-pill bg-danger">Xem tất cả</a>
            </div>
            <!-- ?hiển thị danh sách sản phẩm của danh mục? -->
            <!--------------------------------------------list product------------------------------------->
           <div class="owl-carousel owl-theme">
            <?php foreach($listPro as $key => $pro): ?>
               <?php if ($pro['pro_active']): ?>
                <!-- ------------------------------------product ----------------------------------- -->
                <div class="card text-center" style="padding: 0px 15px;">
                  <?php if ($pro['pro_qty'] == 0): ?>
                    <span class="product_status badge badge-warning"><strong>Bán hết</strong></span>
                  <?php endif ?>
                  <a href='<?= create_link(base_url("product_detail.php"), ["proid"=> $pro["pro_id"]]); ?>' class="d-flex justify-content-center">
                    <img src="image/<?= $pro['pro_img']; ?>" alt="" class="card-img-top">
                  </a>
                  <div class="card-body">

                    <!-- tên -->
                    <h5 class="card-title">
                      <a href="<?= create_link(base_url("product_detail.php"), ['proid' => $pro['pro_id']]); ?>">
                        <?= $pro['pro_name']; ?>
                      </a>
                    </h5>

                    <!-- giá -->
                    <h5 class="badge badge-danger py-1" style="font-size: 15px;">
                      <?= number_format($pro['pro_price'], 0, ',', '.'); ?> &#8363;
                    </h5>

                    <!-- sao đánh giá -->
                    <div>
                      <?php $star = getStar($pro['pro_id']);?>
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
                    <?php if ($pro['pro_qty']): ?>
                      <a class="btn_add_cart_out btn btn-success text-light" data-pro-id="<?= $pro['pro_id']; ?>"
                        data-toggle="tooltip" data-placement="top" title="Thêm vào giỏ hàng"
                        >
                        <i class="fas fa-cart-plus fa-lg"></i>
                      </a>
                    <?php endif ?>

                    <!-- xem chi tiết sản phẩm -->
                    <a href='<?= create_link(base_url("product_detail.php"), ["proid"=> $pro["pro_id"]]); ?>' class="btn btn-default btn-primary" data-toggle="tooltip" data-placement="top" title="chi tiết sản phẩm">
                      <i class="far fa-eye fa-lg"></i>
                    </a>
                  </div>
                </div>
                  <!-- ------------------------------------/product ----------------------------------- -->
                <?php endif ?>
            <?php endforeach; ?>
           </div>
          <!---------------------------------------/list product ------------------------------------------>
        </div>
      </section>
    <?php endif ?>
  <?php endforeach ?>
  <!--===================================== /product ===================================-->
  <!-- =====================================news =================================================== -->
  <section  id="news" class="py-5">
    <h2 class="text-center mb-3 text-primary">TIN TỨC</h2>
    <?php
      $getNewsSQL = "
      SELECT * FROM db_news
      ORDER BY create_at DESC
      LIMIT 10
      ";
      $listNews = db_get($getNewsSQL);
    ?>
    <div class="list_news">
      <div class="owl-carousel owl-theme">
        <?php foreach ($listNews as $key => $news): ?>
        <?php if ($news['news_active']): ?>
          <div class="card one_news">
            <a href="news_detail.php?newsid=<?= $news['news_id']; ?>">
              <img src="image/<?= $news['news_img']; ?>" alt="" class="card-img-top">
            </a>

            <div class="card-body">
              <ul class="card-title nav">
                <li class="nav-item mr-2">
                  <i class="fas fa-calendar-alt"></i>
                  <?php $time = strtotime($news['create_at']); ?>
                  <span><?= read_date($time); ?></span>
                </li>

                <li class="nav-item">
                  <i class="fas fa-user-edit"></i>
                  <span><?= $news['create_by']; ?></span>
                </li>
              </ul>

              <!-- title -->
              <div class="card-title news_title mb-1">
                <a href='news_detail.php?newsid=<?= $news['news_id']; ?>'>
                  <?= $news['news_title']; ?>
                </a>
              </div>

              <!-- desc -->
              <div class="card-text news_desc mb-1">
                <?= $news['news_desc']; ?>
              </div>

              <a href='news_detail.php?newsid=<?= $news['news_id']; ?>' class="badge badge-primary">Xem thêm<i class="fas fa-angle-double-right"></i></a>
            </div>
          </div>
        <?php endif ?>
      <?php endforeach ?>
      </div>
    </div>
  </section>
<!-- ==================================================/news ======================================-->
  </div>
</main>
<script>
  $(function() {
    $('.owl-carousel').owlCarousel({
      items: 4,
      margin: 5,
      autoHeight: true,
      nav    : true,
      smartSpeed :900,
      navText : ["<i class=' btn fa fa-chevron-left'></i>","<i class='btn fa fa-chevron-right'></i>"]
    });
  });
</script>