<!-- navbar -->
<nav id="navbar" class="navbar-expand-sm navbar-light sticky-top py-2 mb-3">
  <?php
  $currentPage = basename($_SERVER["PHP_SELF"], ".php");
  ?>
  <ul class="navbar-nav">
    <li class="nav-item">
      <a href="<?= base_url('index.php'); ?>" class="nav-link">TRANG CHỦ</a>
    </li>
    <li  class="nav-item">
      <a href="<?= base_url('product.php'); ?>" class="nav-link">SHOP</a>
    </li>

    <!-- in danh sách danh mục -->
    <?php
    $getCatSQL = "SELECT * FROM db_category WHERE cat_id IN (SELECT cat_id FROM db_product) AND cat_active = 1";
    $listCategory = db_get($getCatSQL);
    ?>
    <?php foreach ($listCategory as $key => $category): ?>

      <?php if ($category['cat_active']): ?>

        <li class="nav-item dropdown">

        <a href="<?= create_link(base_url('product.php'), ['cat'=>$category['cat_id']]); ?>" class="nav-link dropdown-toggle">
          <?php echo $category["cat_name"]; ?>
        </a>
        
        <div class="dropdown-menu">
          <!-- in danh sách các hãng -->

          <?php
          $sqlGetBrand = "select * from db_brand where db_brand.bra_id in (select bra_id from db_product where cat_id = {$category['cat_id']})";
          $listBrand = get_list($sqlGetBrand);
          ?>
          
          <?php foreach ($listBrand as $key => $brand): ?>
            <?php if ($brand['bra_active']): ?>
              <a href="<?= create_link(base_url('product.php'), ['cat'=>$category['cat_id'], 'bra'=>$brand['bra_id']]); ?>" class="dropdown-item">
              <?= $brand["bra_name"]; ?>
              </a>
            <?php endif ?>
          <?php endforeach ?>

          <!-- /in danh sách các hãng -->
        </div>
      </li>
      
      <?php endif ?>

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
<script>
  let url = window.location.href;

  let link = $('#navbar li.nav-item a');
  link.each(function(){
    if(this.href == url) {
      $(this).closest('li').addClass('active');
      $(this).css('color', 'red');
    }
  }) ;
</script>