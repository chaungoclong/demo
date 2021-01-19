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
        <?php 
          $user = getUserById($_SESSION['user_token']['id']);
         ?>
        <li class="account_box nav-item dropdown">
          <a href="" class="nav-link dropdown-toggle" data-toggle="dropdown">
            <img src="<?= base_url('image/' . $user['ad_avatar']); ?>" alt="" width="30px" height="30px" class="acc_img_sidebar">
            <span class="acc_name_sidebar"><?= $user['ad_name']; ?></span>
          </a>
          <!-- account dropdown -->
          <div class="account_dropdown dropdown-menu shadow">
            <a href="" class="dropdown-item">
              <i class="fas fa-user"></i>
              Tài khoản của tôi
            </a>
            <a href="<?= base_url('admin/log_out.php'); ?>" class="dropdown-item">
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