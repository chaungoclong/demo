<?php require_once '../common.php'; ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <link rel="stylesheet" href="<?= base_url('dist/bootstrap/css/bootstrap.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/font/css/all.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('dist/jquery/jquery-ui.min.css'); ?>">
    <script src="<?= base_url('dist/jquery/jquery-3.5.1.js'); ?>"></script>
    <script src="<?= base_url('dist/jquery/jquery-ui.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/admin.js'); ?>"></script>
    <script src="<?= base_url('assets/js/valid.js'); ?>"></script>
    <script src="<?= base_url('assets/js/login_register.js'); ?>"></script>
    <script src="<?= base_url('assets/js/user_update.js'); ?>"></script>
    <script src="<?= base_url('assets/js/cart.js'); ?>"></script>
    <script src="<?= base_url('assets/js/rate.js'); ?>"></script>
    <script src="<?= base_url('dist/popper/popper.min.js'); ?>"></script>
    <script src="<?= base_url('dist/bootstrap/js/bootstrap.js'); ?>"></script>
    <script src="<?= base_url('dist/ckeditor/ckeditor.js'); ?>"></script>
  </head>
  <body>
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
              <a href="" class="">
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
                <a href="" class="dropdown-item">
                  <i class="fas fa-user-edit mr-1"></i>
                  Sửa thông tin
                </a>
                <a href="" class="dropdown-item">
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
                <a href="" class="dropdown-item">
                  <i class="fas fa-list-ul mr-1"></i>
                  Danh sách sản phẩm
                </a>
                <a href="" class="dropdown-item">
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
            <!-- nhân viên -->
            <li class="nav-item mb-4 dropdown">
              <a class="dropdown-toggle">
                <i class="fas fa-user-friends mr-1"></i>
                <strong>Nhân viên</strong>
              </a>
              <div class="dropdown_menu border-0">
                <a href="" class="dropdown-item">
                  <i class="fas fa-list-ul mr-1"></i>
                  Danh sách nhân viên
                </a>
                <a href="" class="dropdown-item">
                  <i class="fas fa-user-plus mr-1"></i>
                  Thêm nhân viên
                </a>
              </div>
            </li>
            <!-- người dùng -->
            <li class="nav-item mb-4 dropdown">
              <a href="">
                <i class="fas fa-users mr-1"></i>
                <strong>người dùng</strong>
              </a>
            </li>
            <hr class="w-100">
            <!-- Đăng xuất -->
            <li class="nav-item mb-4">
              <a href="" class="">
                <i class="fas fa-sign-out-alt mr-1"></i>
                <strong>Đăng xuất</strong>
              </a>
            </li>
          </ul>
          <!-- /sidebar -->
        </div>
        <!-- /left-col -->
        <!-- right-col -->
        <div class="right_col bg-faded p-0">
          <!--navbar row -->
          <div class="navtop row m-0 bg-secondary">
            <!-- col -->
            <div class="col-7 d-flex align-items-center">
              <!-- search -->
              <form action="" class="form-inline" id="form_search">
                <input type="text" class="form-control" id="search_bar" name="q" placeholder="search...">
                <button class="btn btn-success">
                <i class="fas fa-search"></i>
                </button>
              </form>
            </div>
            <!-- /col -->
            <!-- col -->
            <div class="col-5">
              <ul id="action_icon" class="nav d-flex justify-content-end align-items-center">
                <!-- notice -->
                <li class="notice_box nav-item dropdown">
                  <a href="" class="nav-link dropdown-toggle d-flex align-items-center" data-toggle="dropdown">
                    <span class="notice_index badge badge-pill badge-danger position-absolute">10</span>
                    <i class="fas fa-bell fa-2x mr-2"></i>
                    Notice
                  </a>
                  <!-- notice dropdown -->
                  <div class="notice_dropdown dropdown-menu shadow">
                    <!-- notice title -->
                    <div class="notice_title dropdown-item">
                      <p class="m-0">bạn có 3 thông báo</p>
                    </div>
                    <!-- notice item -->
                    <div class="notice_item dropdown-item">
                      <a href="" class="d-flex align-items-center">
                        <div class="notice_icon">
                          <i class="fas fa-bell fa-lg"></i>
                        </div>
                        <div class="notice_content">
                          <p class="m-0">bạn có đơn hàng mới</p>
                          <span>24/11/2001</span>
                        </div>
                      </a>
                    </div>
                    <!-- notice item -->
                    <div class="notice_item dropdown-item">
                      <a href="" class="d-flex align-items-center">
                        <div class="notice_icon">
                          <i class="fas fa-bell fa-lg"></i>
                        </div>
                        <div class="notice_content">
                          <p class="m-0">bạn có đơn hàng mới</p>
                          <span>24/11/2001</span>
                        </div>
                      </a>
                    </div>
                  </div>
                </li>
                <!-- /notice -->
                <!-- account -->
                <li class="account_box nav-item dropdown">
                  <a href="" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d3/Microsoft_Account.svg/1024px-Microsoft_Account.svg.png" alt="" width="30px" height="30px">
                    chaungoclong
                  </a>
                  <!-- account dropdown -->
                  <div class="account_dropdown dropdown-menu shadow">
                    <a href="" class="dropdown-item">
                      <i class="fas fa-user"></i>
                      Tài khoản của tôi
                    </a>
                    <a href="" class="dropdown-item">
                      <i class="fas fa-sign-out-alt"></i>
                      Đăng xuất
                    </a>
                  </div>
                </li>
                <!-- /account -->
              </ul>
            </div>
            <!-- /col -->
          </div>
          <!-- /row -->
          <!-- main content -row -->
          <div class="main_content bg-white row m-0 pt-4">
            <!-- xin chào -->
            <!-- row -->
            <div class="row m-0 w-100 mb-4">
              <!-- col -->
              <div class="col-12">
                <div class="card card-body shadow">
                  <h3>Xin chào chaungoclong</h3>
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