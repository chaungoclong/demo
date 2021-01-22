<main>
  <!-- wrapper -row -->
  <div class="wrapper_all bg-light">
    <!-- left-col -->
    <div class="left_col shadow bg-light position-fixed">
      <h2 class="logo_sidebar bg-secondary mb-4">
      <a href="" class="navbar-brand">ADMIN</a>
      </h2>
      <!-- sidebar -->
      <ul class="sidebar nav flex-column">
        <!-- trang chủ -->
        <li class="nav-item mb-4">
          <a href="<?= base_url('admin/'); ?>" class="">
            <i class="fas fa-tachometer-alt mr-1"></i>
            <strong>Trang chủ quản trị</strong>
          </a>
        </li>
        <!-- tài khoản -->
        <li class="nav-item mb-4 dropdown">
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
        </li>
        <!-- danh mục -->
        <li class="nav-item mb-4 dropdown">
          <a class="dropdown-toggle">
            <i class="fas fa-th-list mr-1"></i>
            <strong>Danh mục sản phẩm</strong>
          </a>
          <div class="dropdown_menu border-0">
            <a href="" class="dropdown-item">
              <i class="fas fa-list-ul mr-1"></i>
              Danh sách danh mục
            </a>
            <a href="" class="dropdown-item">
              <i class="fas fa-plus-circle mr-1"></i>
              Thêm danh mục
            </a>
          </div>
        </li>
        <!-- sản phẩm -->
        <li class="nav-item mb-4 dropdown">
          <a class="dropdown-toggle">
            <i class="fas fa-rocket mr-1"></i>
            <strong>Sản phẩm</strong>
          </a>
          <div class="dropdown_menu border-0">
            <a href="<?= base_url('admin/product/'); ?>" class="dropdown-item">
              <i class="fas fa-list-ul mr-1"></i>
              Danh sách sản phẩm
            </a>
            <a href="<?= create_link( base_url('admin/product/add.php'), ['from'=>getCurrentURL()]); ?>" class="dropdown-item">
              <i class="fas fa-plus-circle mr-1"></i>
              Thêm sản phẩm
            </a>
          </div>
        </li>
        <!-- ảnh sản phẩm -->
        <li class="nav-item mb-4 dropdown">
          <a class="dropdown-toggle">
            <i class="far fa-image mr-1"></i>
            <strong>Ảnh sản phẩm</strong>
          </a>
          <div class="dropdown_menu border-0">
            <a href="" class="dropdown-item">
              <i class="far fa-images mr-1"></i>
              Danh sách ảnh
            </a>
            <a href="" class="dropdown-item">
              <i class="fas fa-plus-circle mr-1"></i>
              Thêm ảnh
            </a>
          </div>
        </li>
        <!-- slide thể loại -->
        <li class="nav-item mb-4 dropdown">
          <a class="dropdown-toggle">
            <i class="fab fa-slideshare mr-1"></i>
            <strong>slide</strong>
          </a>
          <div class="dropdown_menu border-0">
            <a href="" class="dropdown-item">
              <i class="fas fa-sliders-h mr-1"></i>
              Danh sách slide
            </a>
            <a href="" class="dropdown-item">
              <i class="fas fa-plus-circle mr-1"></i>
              Thêm slide
            </a>
          </div>
        </li>
        <!-- hóa đơn -->
        <li class="nav-item mb-4 dropdown">
          <a href="<?= base_url('admin/order/'); ?>">
            <i class="fas fa-book mr-1"></i>
            <strong>Hóa đơn</strong>
          </a>
        </li>
        
        <?php if ($_SESSION['user_token']['role'] == 1): ?>
        <!-- nhân viên -->
        <li class="nav-item mb-4 dropdown">
          <a class="dropdown-toggle">
            <i class="fas fa-user-friends mr-1"></i>
            <strong>Nhân viên</strong>
          </a>
          <div class="dropdown_menu border-0">
            <a href="<?= base_url('admin/user/'); ?>" class="dropdown-item">
              <i class="fas fa-list-ul mr-1"></i>
              Danh sách nhân viên
            </a>
            <a href="<?= create_link( base_url('admin/user/add.php'), ['from'=>getCurrentURL()]); ?>" class="dropdown-item">
              <i class="fas fa-user-plus mr-1"></i>
              Thêm nhân viên
            </a>
          </div>
        </li>
        <!-- người dùng -->
        <li class="nav-item mb-4 dropdown">
          <a href="<?= base_url('admin/customer/'); ?>">
            <i class="fas fa-users mr-1"></i>
            <strong>người dùng</strong>
          </a>
        </li>
        <?php endif ?>
        
        <hr class="w-100">
        <!-- Đăng xuất -->
        <li class="nav-item mb-4">
          <a href="<?= base_url('admin/log_out.php'); ?>" class="">
            <i class="fas fa-sign-out-alt mr-1"></i>
            <strong>Đăng xuất</strong>
          </a>
        </li>
      </ul>
      <!-- /sidebar -->
    </div>
    <!-- /left-col -->