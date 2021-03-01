<main>
  <!-- wrapper -row -->
  <div class="wrapper_all bg-white">
    <!-- left-col -->
    <div class="left_col shadow position-fixed bg-dark">
      <h2 class="logo_sidebar mt-1 mb-4 bg-dark">
      <a href="<?= base_url('admin/'); ?>" class=" btn btn-primary w-50 mr-2">ADMIN</a>
      <a href="<?= base_url(''); ?>" class=" btn btn-danger w-50">NHÀ</a>
      </h2>
      <!-- sidebar -->
      <ul class="sidebar nav flex-column">
        <!-- trang chủ -->
        <li class="nav-item mb-2">
          <a href="<?= base_url('admin/'); ?>" class="">
            <i class="fas fa-tachometer-alt mr-1"></i>
            <strong>Trang chủ quản trị</strong>
          </a>
          <hr class="m-0 bg-white">
        </li>

        <!-- tài khoản -->
        <li class="nav-item mb-2 dropdown">
          <a class="dropdown-toggle">
            <i class="fas fa-user mr-1"></i>
            <strong>Tài khoản của tôi</strong>
          </a>
          <div class="dropdown_menu border-0">
            <a href="<?= base_url('admin/profile.php'); ?>" class="dropdown-item">
              <i class="fas fa-user-edit mr-1"></i>
              Sửa thông tin
            </a>
            <a href="<?= base_url('admin/password.php'); ?>" class="dropdown-item">
              <i class="fas fa-lock mr-1"></i>
              Đổi mật khẩu
            </a>
          </div>
           <hr class="m-0 bg-white">
        </li>

        <!-- danh mục -->
        <li class="nav-item mb-2 dropdown">
          <a class="dropdown-toggle">
            <i class="fas fa-th-list mr-1"></i>
            <strong>Danh mục sản phẩm</strong>
          </a>
          <div class="dropdown_menu border-0">
            <a href="<?= base_url('admin/category/'); ?>" class="dropdown-item">
              <i class="fas fa-list-ul mr-1"></i>
              Danh sách danh mục
            </a>
            <a href="<?= base_url('admin/category/add.php'); ?>" class="dropdown-item">
              <i class="fas fa-plus-circle mr-1"></i>
              Thêm danh mục
            </a>
          </div>
           <hr class="m-0 bg-white">
        </li>

        <!-- sản phẩm -->
        <li class="nav-item mb-2 dropdown">
          <a class="dropdown-toggle">
            <i class="fas fa-rocket mr-1"></i>
            <strong>Sản phẩm</strong>
          </a>
          <div class="dropdown_menu border-0">
            <a href="<?= base_url('admin/product/'); ?>" class="dropdown-item">
              <i class="fas fa-list-ul mr-1"></i>
              Danh sách sản phẩm
            </a>
            <a href="<?= base_url('admin/product/add.php'); ?>" class="dropdown-item">
              <i class="fas fa-plus-circle mr-1"></i>
              Thêm sản phẩm
            </a>
          </div>
           <hr class="m-0 bg-white">
        </li>

        <!-- hãng -->
        <li class="nav-item mb-2 dropdown">
          <a class="dropdown-toggle">
           <i class="fab fa-android"></i>
            <strong>Hãng</strong>
          </a>
          <div class="dropdown_menu border-0">
            <a href="<?= base_url('admin/brand/'); ?>" class="dropdown-item">
              <i class="far fa-images mr-1"></i>
              Danh sách hãng
            </a>
            <a href="<?= base_url('admin/brand/add.php'); ?>" class="dropdown-item">
              <i class="fas fa-plus-circle mr-1"></i>
              Thêm hãng
            </a>
          </div>
           <hr class="m-0 bg-white">
        </li>

        <!-- slide thể loại -->
        <li class="nav-item mb-2 dropdown">
          <a class="dropdown-toggle">
            <i class="fab fa-slideshare mr-1"></i>
            <strong>slide</strong>
          </a>
          <div class="dropdown_menu border-0">
            <a href="<?= base_url('admin/slider/'); ?>" class="dropdown-item">
              <i class="fas fa-sliders-h mr-1"></i>
              Danh sách slide
            </a>
            <a href="<?= base_url('admin/slider/add.php'); ?>" class="dropdown-item">
              <i class="fas fa-plus-circle mr-1"></i>
              Thêm slide
            </a>
          </div>
           <hr class="m-0 bg-white">
        </li>

        <!-- tin tức -->
        <li class="nav-item mb-2 dropdown">
          <a class="dropdown-toggle">
            <i class="fas fa-newspaper mr-1"></i>
            <strong>Tin tức</strong>
          </a>
          <div class="dropdown_menu border-0">
            <a href="<?= base_url('admin/news/'); ?>" class="dropdown-item">
              <i class="fas fa-sliders-h mr-1"></i>
              Danh sách tin tức
            </a>
            <a href="<?= base_url('admin/news/add.php'); ?>" class="dropdown-item">
              <i class="fas fa-plus-circle mr-1"></i>
              Thêm tin tức
            </a>
          </div>
           <hr class="m-0 bg-white">
        </li>

        <!-- hóa đơn -->
        <li class="nav-item mb-2 dropdown">
          <a href="<?= base_url('admin/order/'); ?>">
            <i class="fas fa-book mr-1"></i>
            <strong>Hóa đơn</strong>
          </a>
           <hr class="m-0 bg-white">
        </li>
        
        <?php if ($_SESSION['user_token']['role'] == 1): ?>
        <!-- nhân viên -->
        <li class="nav-item mb-2 dropdown">
          <a class="dropdown-toggle">
            <i class="fas fa-user-friends mr-1"></i>
            <strong>Nhân viên</strong>
          </a>
          <div class="dropdown_menu border-0">
            <a href="<?= base_url('admin/user/'); ?>" class="dropdown-item">
              <i class="fas fa-list-ul mr-1"></i>
              Danh sách nhân viên
            </a>
            <a href="<?= base_url('admin/user/add.php'); ?>" class="dropdown-item">
              <i class="fas fa-user-plus mr-1"></i>
              Thêm nhân viên
            </a>
          </div>
           <hr class="m-0 bg-white">
        </li>
        <?php endif ?>

        <!-- người dùng -->
        <li class="nav-item mb-2 dropdown">
          <a href="<?= base_url('admin/customer/'); ?>">
            <i class="fas fa-users mr-1"></i>
            <strong>Khách hàng</strong>
          </a>
           <hr class="m-0 bg-white">
        </li>
        
        <!-- đánh giá -->
        <li class="nav-item mb-2">
          <a href="<?= base_url('admin/rate/'); ?>" class="">
            <i class="far fa-comment-dots mr-1"></i>
            <strong>Đánh giá</strong>
          </a>
           <hr class="m-0 bg-white">
        </li>

        <!-- <hr class="w-100"> -->
        <!-- Đăng xuất -->
        <li class="nav-item mb-2">
          <a href="<?= base_url('admin/log_out.php'); ?>" class="">
            <i class="fas fa-sign-out-alt mr-1"></i>
            <strong>Đăng xuất</strong>
          </a>
           <hr class="m-0 bg-white">
        </li>
      </ul>
      <!-- /sidebar -->
    </div>
    <!-- /left-col -->
    <script>
      let url = window.location.pathname;
      let newUrl = url.replace(/\//g, '');
      let regex = new RegExp(`${newUrl}$`, 'g');

      let link = $('.sidebar li.nav-item a');
      link.each(function(){
        if(regex.test(this.href.replace(/\//g, ''))) {
          $(this).closest('li').addClass('active');
          $(this).css('color', 'red');
        }
      }) ;
    </script>