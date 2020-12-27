<!--===========================================category ===================================================-->
<section id="category" class="py-5">
  <h2 class="text-center mb-3">Danh mục</h2>
  <?php
  $listCat = fetch_tbl("db_category", 2);
  $numCol = 4;
  $numRow = get_display($listCat, $numCol);
  ?>
  <!-- ?in số hàng? -->
  <?php for ($i = 0; $i < $numRow; $i++): ?>
  <?php $countCol = 0; ?>
  <div class="card-deck">
    <?php while ($cat = $listCat->fetch_assoc()): ?>
    <?php if ($cat['cat_active']): ?>
    <!-- ?in cột? -->
    <div class="category_item card" style="max-width: 25%;">
      <a href="<?= create_link(base_url('product.php'), ['cat'=> $cat["cat_id"]]); ?>" class="card-body">
        <img src="<?= $cat['cat_logo']; ?>" alt="" class="card-img-top">
        <div class="category_info bg-light">
          <h5><?= $cat['cat_name']; ?></h5>
          <!-- ?lấy số lượng sản phẩm? -->
          <?php
          $catID   = $cat['cat_id'];
          $num_pro = fetch_list('db_product', "cat_id = $catID", ['*'], 0);
          ?>
          <p><?= $num_pro; ?> sản phẩm</p>
        </div>
      </a>
    </div>
    <!-- /in cột -->
    <?php
    $countCol++;
    if($countCol == $numCol) {
    break;
    }
    ?>
    <?php endif ?>
    <?php endwhile ?>
  </div>
  <?php endfor ?>
</section>
<!--=======================================/category ===========================================--->
<!--=========================================== product =========================================-->
<?php foreach ($listCat as $key => $cat): ?>
<!-- nếu active thì hiển thị danh mục -->
<?php if ($cat['cat_active']): ?>
<section class="product py-5">
  <h2 class="text-center mb-3"><?= $cat['cat_name']; ?></h2>
  <div class="list_product_body">
    <?php
    $catID   = $cat['cat_id'];
    $getProSQL = "
    SELECT * FROM db_product
    WHERE cat_id = '{$catID}'
    ORDER BY pro_id DESC
    ";
    $listPro = get_list($getProSQL, 2);
    $numCol  = 4; //số cột trên hàng
    $limit   = 2; //giới hạn số sản phẩm được in ra của 1 thể loại trên trang chủ
    $numRow  = get_display($listPro, $numCol, $limit); //số hàng
    ?>
    <!-- list products bar -->
    <div class="product_bar bg-info px-2 py-2 d-flex justify-content-between">
      <span class="badge  bg-faded"><?= $listPro->num_rows; ?> sản phẩm</span>
      <a href="<?= create_link(base_url("product.php"), ["cat"=> $cat["cat_id"]]); ?>" class="badge badge-pill bg-danger">Xem tất cả</a>
    </div>
    <!-- ?hiển thị danh sách sản phẩm của danh mục? -->
    <!--------------------------------------------list product------------------------------------->
    <?php
    $printed = 0;
    ?>
    <?php for ($i = 0; $i < $numRow; $i++): ?>
    <?php $countCol = 0; ?>
    <div class="card-group">
      <?php while ($pro = $listPro->fetch_assoc()): ?>
      <!-- ?nếu sản phẩm active thì in ? -->
      <?php if ($pro['pro_active']): ?>
      <!-- ------------------------------------product ----------------------------------- -->
      <div class="card text-center">
        <?php if ($pro['pro_qty'] == 0): ?>
        <span class="product_status badge badge-pill badge-warning">Sale out</span>
        <?php endif ?>
        <a href='<?= create_link(base_url("product_detail.php"), ["proid"=> $pro["pro_id"]]); ?>'>
          <img src="<?= $pro['pro_img']; ?>" alt="" class="card-img-top">
        </a>
        <div class="card-body">
          <!-- thông tin sản phẩm -->
          <h5 class="card-title"><a href=""><?= $pro['pro_name']; ?></a></h5>
          <p class="text-uppercase"><?= $cat['cat_name']; ?></p>
          <h6 class="text-danger"><?= number_format($pro['pro_price'], 2, ',', '.'); ?> &#8363;</h6>
          <hr>
          <!-- thêm vào giỏ hàng -->
          <?php if ($pro['pro_qty']): ?>
          <a href='<?= create_link(base_url("card.php"), ["proid"=> $pro["pro_id"]]); ?>' class="btn btn-default btn-success">Add to card</a>
          <?php endif ?>
          <!-- xem chi tiết sản phẩm -->
          <a href='<?= create_link(base_url("product_detail.php"), ["proid"=> $pro["pro_id"]]); ?>' class="btn btn-default btn-primary">Detail</a>
          <!-- danh sách yêu thích -->
          <a href='<?= create_link(base_url("wishlist.php"), ["proid"=> $pro["pro_id"]]); ?>' class="btn btn-default btn-danger"><i class="far fa-heart"></i></a>
        </div>
      </div>
      <!-- ------------------------------------/product ----------------------------------- -->
      <?php
      $countCol++;
      if($countCol == $numCol) {
      break;
      }
      ?>
      <?php endif ?>
      <?php endwhile ?>
    </div>
    <?php
    $printed++;
    if($printed == $limit) {
    break;
    }
    ?>
    <?php endfor ?>
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
  $listNews = get_list($getNewsSQL, 2);
  $numNews  = 0;
  $numCol   = 3;
  $numRow   = 0;
  $limit    = 6;
  if($listNews) {
  $numNews = $listNews->num_rows;
  $numRow  = ceil($numNews / $numCol);
  }
  ?>
  <div class="list_news">
    <?php $printed = 0; ?>
    <?php for ($i = 0; $i < $numRow; $i++): ?>
    <?php $countCol = 0; ?>
    <!-- -----------------------------------row --------------------------------------------->
    <div class="card-group">
      <?php while ($news = $listNews->fetch_assoc()): ?>
      <?php if ($news['news_active']): ?>
      <div class="card" style="max-width: calc(100% / 3);">
        <a href='<?= create_link(base_url("news_detail.php"), ["newsid" => $news["news_id"]]); ?>'>
          <img src=" <?= $news['news_img']; ?>" alt="" class="card-img-top">
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
      $countCol++;
      $printed++;
      if($countCol == $numCol) {
      break;
      }
      ?>
      <?php endif ?>
      <?php endwhile ?>
    </div>
    <?php
    if($printed == $limit) {
    break;
    }
    ?>
    <!-- --------------------------------------row --------------------------------------------- -->
    <?php endfor ?>
  </div>
</section>
<!-- ==================================================/news ======================================