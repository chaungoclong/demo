<!-- header -->
<header id="header" class="">
  <h1 id="test"></h1>
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
        <div id="headerLink" class="col-5">
          <!-- list link -->
          <ul id="headerListLink" class="nav d-flex justify-content-around">
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
              <?php if (is_login()) { ?>

                <a href="<?= base_url('account.php'); ?>" class="nav-link dropdown-toggle">
                  <span><i class="fas fa-user fa-lg"></i></span>
                  <span>
                    <?= $_SESSION['user_token']['username']; ?>
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
                  <a href="<?= base_url('login.php'); ?>" class="dropdown-item">Đăng nhập</a>
                  <a href="<?= base_url('sign_up.php'); ?>" class="dropdown-item">Đăng kí</a>
                </div>

              <?php } ?>
             
            </li>
            <li id="shoppingCart" class="nav-item">
              <a href="<?= !is_login() ? base_url('login.php') : base_url('card.php'); ?>" class="nav-link">
                <span class="position-relative">
                  <i class="fas fa-shopping-cart fa-lg"></i>
                  <span id="shoppingCartIndex" class="position-absolute badge badge-primary badge-pill">1</span>
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