<main>
  <div style="padding-left: 85px; padding-right: 85px;">
    <!--===========================================category ===================================================-->
    <section id="category" class="py-5">
      <h2 class="text-center mb-3">Danh mục</h2>
      <?php
      $listCategory = db_fetch_table("db_category", 0);
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
              $limit = 8;
              $count = 0;
            ?>
            <!-- list products bar -->
            <div class="product_bar bg-info px-2 py-2 d-flex justify-content-between">
              <span class="badge  bg-faded"><?= $proQty; ?> sản phẩm</span>
              <a href="<?= create_link(base_url("product.php"), ["cat"=> $catID]); ?>" class="badge badge-pill bg-danger">Xem tất cả</a>
            </div>
            <!-- ?hiển thị danh sách sản phẩm của danh mục? -->
            <!--------------------------------------------list product------------------------------------->
           <div class="row m-0 bg-white">
            <?php foreach($listPro as $key => $pro): ?>
               <?php if ($pro['pro_active']): ?>
                    <!-- ------------------------------------product ----------------------------------- -->
                    <div class="card text-center col-3" style="max-width: 25%;">
                      <?php if ($pro['pro_qty'] == 0): ?>
                        <span class="product_status badge badge-pill badge-warning">Bán hết</span>
                      <?php endif ?>
                      <a href='<?= create_link(base_url("product_detail.php"), ["proid"=> $pro["pro_id"]]); ?>'>
                        <img src="image/<?= $pro['pro_img']; ?>" alt="" class="card-img-top">
                      </a>
                      <div class="card-body">

                        <!-- thông tin sản phẩm -->
                        <h5 class="card-title text-uppercase">
                          <a href="
                            <?php
                              echo create_link(
                                base_url("product_detail.php"),
                                ['proid' => $pro['pro_id']]
                              );
                            ?>
                          ">
                          <?= $pro['pro_name']; ?>
                        </a>
                      </h5>
                      <p class="text-uppercase card-subtitle"><?= $category['cat_name']; ?></p>
                      <h6 class="text-danger">
                        <strong><?= number_format($pro['pro_price'], 0, ',', '.'); ?> &#8363;</strong>
                      </h6>
                      <hr>

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
                  <?php 
                      $count++;
                      if($count == $limit) break;
                  ?>
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
    <h2 class="text-center mb-3">Tin tức</h2>
    <?php
      $getNewsSQL = "
      SELECT * FROM db_news
      ORDER BY create_at DESC
      ";
      $listNews = db_get($getNewsSQL);
      $limit = 6;
      $count = 0;
    ?>
    <div class="list_news">
      <div class="row m-0">
        <?php foreach ($listNews as $key => $news): ?>
        <?php if ($news['news_active']): ?>
          <div class="card col-4 p-0" style="max-width: calc(100% / 3);">
            <a href='<?= create_link(base_url("news_detail.php"), ["newsid" => $news["news_id"]]); ?>'>
              <img src="image/<?= $news['news_img']; ?>" alt="" class="card-img-top">
            </a>
            <div class="card-body">
              <ul class="card-title nav">
                <li class="nav-item mr-2">
                  <i class="fas fa-calendar-alt"></i>
                  <?php
                  $time = strtotime($news['create_at']);
                  ?>
                  <span><?= read_date($time); ?></span>
                </li>
                <li class="nav-item">
                  <i class="fas fa-user-edit"></i>
                  <span><?= $news['create_by']; ?></span>
                </li>
              </ul>
              <h5 class="card-title text-uppercase">
                <a href='<?= create_link(base_url("news_detail.php"), ["newsid" => $news["news_id"]]); ?>'>
                  <?= $news['news_title']; ?>
                </a>
              </h5>
              <p class="card-text">
                <?= $news['news_desc']; ?>
              </p>
              <a href='<?= create_link(base_url("news_detail.php"), ["newsid" => $news["news_id"]]); ?>' class="btn btn-default btn-primary">Xem thêm<i class="fas fa-angle-double-right"></i></a>
            </div>
          </div>
          <?php
          $count++;
          if($count == $limit) {
            break;
          }
          ?>
        <?php endif ?>
      <?php endforeach ?>
      </div>
    </div>
  </section>
<!-- ==================================================/news ======================================-->
  </div>
</main>