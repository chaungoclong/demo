<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title></title>
  <link rel="stylesheet" href="dist/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="assets/font/css/all.css">
  <link rel="stylesheet" href="assets/css/home.css">
  <link rel="stylesheet" href="assets/css/login_register.css">
  <link rel="stylesheet" href="dist/jquery/jquery-ui.min.css">
  <script src="dist/jquery/jquery-3.5.1.js"></script>
  <script src="dist/jquery/jquery-ui.min.js"></script>
  <script src="assets/js/common.js"></script>
  <script src="assets/js/login_register.js"></script>
  <script src="assets/js/cart.js"></script>
  <script src="dist/popper/popper.min.js"></script>
  <script src="dist/bootstrap/js/bootstrap.js"></script>
  <script src="dist/ckeditor/ckeditor.js"></script>
</head>
<body>
  <!-- header -->
  <header id="header" class="">
    <!--header top -->
    <div id="headerTop" class="container-fluid">
      <!-- header title -->
      <p id="headerTitle" class="text-center m-0 p-2">
        trang mua hàng trực tuyến thương hiệu chính hãng
      </p>
    </div>
    <!-- /header top -->
    <hr class="bg-light m-0 p-0">
    <!-- header midle -->
    <div id="headerMidle">
      <!-- header midle content -->
      <div id="headerMidleContent" class="container-fluid">
        <div class="row m-0">
          <!-- logo -->
          <div class="col-3 p-0">
            <a id="headerLogo" href="<?= base_url('index.php'); ?>" class="navbar-brand">LongVietMobile</a>
          </div>
          <!-- search box -->
          <div id="headerSearch" class="col-4 d-flex align-items-center p-0">
            <!-- form search -->
            <form id="formSearch" action="search.php" method="GET" class="form-inline d-flex">
              <input id="boxSearch" type="search" name="q" placeholder="tìm kiếm..." class="form-control"
              value="<?= isset($_GET['q'] ) ? $_GET['q'] : ''; ?>" autocomplete = "off"
              >
              <button id="btnSearch" class="btn btn-primary">
                <i class="fas fa-search"></i>
              </button>
            </form>
            <!-- list of ajax search -->
            <ul id="ajaxSearch" class="list-group" style="max-height: 400px; overflow-y:scroll;">

            </ul>
          </div>
          <!-- header link -->
          <div id="headerLink" class="col-5 d-flex align-items-center pr-0">
            <!-- list link -->
            <ul id="headerListLink" class="nav d-flex justify-content-between pr-0">
              <li id="hotLine" class="nav-item">
                <a href="" class="nav-link">
                  <span><i class="fas fa-phone-alt fa-lg"></i></span>
                  <span>
                    Hotline:
                    <br>
                    <span>039.9898.559</span>
                  </span>
                </a>
              </li>
              <li id="account" class="nav-item dropdown">
                <?php if (is_login()) {
                  $id = $_SESSION['user_token']['id'];
                  $info = getUserById($id);
                 ?>

                  <a href="<?= base_url('account.php'); ?>" class="nav-link dropdown-toggle">
                    <span><img src="image/<?= $info['cus_avatar']; ?>" alt="" style="width: 30px;  height: 30px; border-radius: 50%;"></span>
                    <span>
                      <?= $info['cus_name'];?>
                    </span>
                  </a>
                  <div class="dropdown-menu">
                    <a href="<?= base_url('account.php'); ?>" class="dropdown-item">Tài khoản của tôi</a>
                    <a href="<?= base_url('logout.php'); ?>" class="dropdown-item">Đăng xuất</a>
                  </div>

                <?php } else { ?>

                  <a href="<?= base_url('login.php'); ?>" class="nav-link dropdown-toggle">
                    <span><i class="fas fa-user fa-lg"></i></span>
                    <span>
                      <?= "Tài khoản"; ?>
                    </span>
                  </a>
                  <div class="dropdown-menu">
                    <a href="<?= base_url('login_form.php'); ?>" class="dropdown-item">Đăng nhập</a>
                    <a href="<?= base_url('register_form.php'); ?>" class="dropdown-item">Đăng kí</a>
                  </div>

                <?php } ?>

              </li>
              <li id="shoppingCart" class="nav-item">
                <?php 
                    if(!is_login()) {
                      delete_session("cart");
                    }
                 ?>
                <a href="<?= !is_login() ? base_url('login_form.php') : base_url('view_cart.php'); ?>" class="nav-link">
                  <span class="position-relative">
                    <i class="fas fa-shopping-cart fa-lg"></i>
                    <span id="shoppingCartIndex" class="position-absolute badge badge-primary badge-pill">
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
                  </span>
                  <span>
                    Giỏ hàng
                  </span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <!-- /header midle content -->
    </div>
    <!-- /header midle -->

  </header>
<!-- /header -->