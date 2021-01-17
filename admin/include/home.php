<!-- main content -row -->
<div class="main_content bg-white row m-0 pt-4">
  <!-- xin chào -->
  <!-- row -->
  <div class="row m-0 w-100 mb-4">
    <!-- col -->
    <div class="col-12">
      <div class="card card-body shadow">
        <?php 
          $user = getUserById($_SESSION['user_token']['id']);
         ?>
        <h3>Xin chào <?= $user['ad_name']; ?></h3>
        <h5>chào mừng bạn đến với trang quản trị</h5>
      </div>
    </div>
    <!-- /col -->
  </div>
  <!-- /row -->
  <div class="row m-0 w-100">
    <div class="col-12">
      <div class="card-deck">
        <div class="card card-body shadow">
          <h5>Sản phẩm</h5>
          <p>100 sản phẩm</p>
          <a href=""><i>chi tiết...</i></a>
        </div>
        <div class="card card-body shadow">
          <h5>Sản phẩm</h5>
          <p>100 sản phẩm</p>
          <a href=""><i>chi tiết...</i></a>
        </div>
        <div class="card card-body shadow">
          <h5>Sản phẩm</h5>
          <p>100 sản phẩm</p>
          <a href=""><i>chi tiết...</i></a>
        </div>
        <div class="card card-body shadow">
          <h5>Sản phẩm</h5>
          <p>100 sản phẩm</p>
          <a href=""><i>chi tiết...</i></a>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<!-- /right-col -->
</div>
<!-- /wrapper -row -->
</main>
</body>
</html>