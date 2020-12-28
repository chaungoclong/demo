<!-- navbar -->
<nav id="navbar" class="navbar-expand-sm bg-light navbar-light sticky-top">
  <ul class="navbar-nav">
    <li class="nav-item active">
      <a href="<?= base_url('index.php'); ?>" class="nav-link">TRANG CHỦ</a>
    </li>
    <li class="nav-item">
      <a href="<?= base_url('product.php'); ?>" class="nav-link">SHOP</a>
    </li>
    
    <!-- in danh sách danh mục -->
    <?php  $listCategory = fetch_list("db_category", "1"); ?>
    <?php foreach ($listCategory as $key => $category): ?>

      <li class="nav-item dropdown">
        <a href="<?= create_link(base_url('product.php'), ['cat'=>$category['cat_id']]); ?>" class="nav-link dropdown-toggle"><?php echo $category["cat_name"]; ?></a>
        <div class="dropdown-menu">
          
          <!-- in danh sách các hãng -->
          <?php 
          $sqlGetBrand = "select * from db_brand where db_brand.bra_id in (select bra_id from db_product where cat_id = {$category['cat_id']})";
          $listBrand = get_list($sqlGetBrand); 
          ?>

          <?php foreach ($listBrand as $key => $brand): ?>
            <a href="<?= create_link(base_url('product.php'), ['cat'=>$category['cat_id'], 'bra'=>$brand['bra_id']]); ?>" class="dropdown-item"><?= $brand["bra_name"]; ?></a>
          <?php endforeach ?>
          <!-- /in danh sách các hãng -->
        </div>
      </li>

    <?php endforeach; ?>
    <!-- /in danh sách danh mục -->

    <li class="nav-item">
      <a href="<?= base_url('about.php'); ?>" class="nav-link">GIỚI THIỆU</a>
    </li>
    <li class="nav-item">
      <a href="<?= base_url('news.php'); ?>" class="nav-link">TIN TỨC</a>
    </li>
    <li class="nav-item">
      <a href="<?= base_url('contact.php'); ?>" class="nav-link">LIÊN HỆ</a>
    </li>
  </ul>
</nav>
<!-- /navbar -->