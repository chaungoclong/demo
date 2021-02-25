<!-- footer -->
<!-- button top -->
<!-- <button id="btn_top" class="btn btn-warning position-fixed p-2" style="bottom: 90px; right: 15px; border-radius: 50%; z-index: 1000;display:none;">
  <i class="fas fa-arrow-up fa-2x"></i>
</button> -->

<i class="far fa-caret-square-up fa-3x position-fixed text-warning" style="bottom: 60px; right: 10px; z-index: 1000; display: none; cursor: pointer;" id="btn_top"></i>
<script>
  $(function() {
    $(window).scroll(function() {
      if($(this).scrollTop() >= 50) {
        $('#btn_top').show();
      } else {
        $('#btn_top').hide();
      }
    });

    $('#btn_top').on('click', function() {
      scrollToTop();
    });
    function scrollToTop() {
      var scroll = $('html, body');
      scroll.animate({ scrollTop: 0 }, 600, "swing");
    }
  });
</script>
<!-- button top -->

<!-- modal shopping cart -->
<?php if (is_login() && !empty($_SESSION['cart'])): ?>
<div id="modal_cart" class="position-fixed border border-1 btn btn-info p-0" style="bottom: 10px; right: 10px; z-index: 1000; display:block;">
  <a href="<?= base_url('view_cart.php'); ?>" class="d-block p-2 bg-info shadow-lg">
    <span class="position-absolute badge badge-pill bg-danger text-light" style="right: -5px;  top: -13px; font-size: 14px; ">
      <!-- in số lượng sản phẩm trong giỏ hàng -->
      <?php 
      if(!empty($_SESSION['cart'])) {
        $total = 0;
        foreach ($_SESSION['cart'] as $key => $value) {
          $total += $value;
        }
        echo $total;
      } else {
        echo 0;
      }
      ?>
    </span>
    <span style="color:#EBFF41;"><i class="fas fa-shopping-cart fa-2x"></i></span>
  </a>
</div>
<?php else: ?>
 <div id="modal_cart" class="position-fixed border border-1 btn btn-info p-0" style="bottom: 10px; right: 10px; z-index: 1000; display:none;">
  <a href="<?= base_url('view_cart.php'); ?>" class="d-block p-2 bg-info shadow-lg">
    <span class="position-absolute badge badge-pill bg-danger text-light" style="right: -5px;  top: -13px; font-size: 14px; ">

    </span>
    <span style="color:#EBFF41;"><i class="fas fa-shopping-cart fa-2x"></i></span>
  </a>
</div>
</div>
<?php endif ?>
<!-- /modal shopping cart -->

<footer id="footer">

  <!-- footer top -->
  <div class="row py-4 d-flex align-items-start m-0">
    <!-- about -->
    <div class="col-4 p-0">
      <h5>GIỚI THIỆU</h5>
      <p>
        trang mua sắm trực tuyến của hệ Mặt Trời. Giúp bạn tiếp cận xu
        hướng công nghệ của vũ trụ
      </p>
      <img src="https://theme.hstatic.net/1000406564/1000647212/14/logo_bct.png?v=414" alt="" width="200px">
    </div>
    <!-- /about -->
    <!-- product -->
    <div class="col-2">
      <h5>SẢN PHẨM</h5>
      <ul class="list-unstyled">
        <li class="mb-2"><a href="">Điện thoại</a></li>
        <li class="mb-2"><a href="">Laptop</a></li>
        <li class="mb-2"><a href="">Tablet</a></li>
      </ul>
    </div>
    <!-- /product -->
    <!-- link -->
    <div class="col-2">
      <h5>HÃNG</h5>
      <ul class="list-unstyled">
        <li class="mb-2"><a href="">Google</a></li>
        <li class="mb-2"><a href="">Apple</a></li>
        <li class="mb-2"><a href="">Samsung</a></li>
      </ul>
    </div>
    <!-- /link -->
    <!-- contact -->
    <div class="col-4">
      <h5>LIÊN HỆ</h5>
      <ul class="fa-ul ml-4">
        <li class="mb-2">
          <span class="fa-li"><i class="fas fa-map-marker-alt"></i></span>
          Quan Lạn - Vân Đồn - Quảng Ninh
        </li>
        <li class="mb-2">
          <span class="fa-li"><i class="fas fa-phone-alt"></i></span>
          039.9898.559
        </li>
        <li class="mb-2">
          <span class="fa-li"><i class="fas fa-envelope"></i></span>
          chaungoclong2411@gmail.com
        </li>
      </ul>
    </div>
    <!-- /contact -->
  </div>
  <!-- /footer top -->
  <hr class="m-0 p-0">
  <!-- footer bottom -->
  <div class="text-center py-2">
    Copyright © 2020 LongVietMobile. Powered by Chau Ngoc Long & Nguyen Van Viet
  </div>
  <!-- /footer bottom -->
</footer>
<!-- /footer -->
</body>
</html>