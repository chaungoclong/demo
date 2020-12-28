<?php require_once '../common.php'; ?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title></title>
  <link rel="stylesheet" href="../dist/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../assets/font/css/all.css">
  <link rel="stylesheet" href="../assets/css/home.css">
  <script src="../dist/jquery/jquery-3.5.1.js"></script>
  <script src="../assets/js/home.js"></script>
  <script src="../dist/popper/popper.min.js"></script>
  <script src="../dist/bootstrap/js/bootstrap.js"></script>
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
            <a id="headerLogo" href="" class="navbar-brand">LongVietMobile</a>
          </div>
          <!-- search box -->
          <div id="headerSearch" class="col-4 d-flex align-items-center p-0">
            <!-- form search -->
            <form id="formSearch" action="template/index.php" method="GET" class="form-inline d-flex">
              <input id="boxSearch" type="search" name="q" placeholder="tìm kiếm..." class="form-control">
              <button id="btnSearch" name="btnSearch" class="btn btn-primary">
                <i class="fas fa-search"></i>
              </button>
            </form>
            <!-- list of ajax search -->
            <ul id="ajaxSearch" class="list-group">
                <!-- <li class="ajax_search_item list-group-item p-0">
                  <a href="" class="d-flex align-items-center">
                    <span class="item_result_img">
                      <img src="https://www.dungplus.com/wp-content/uploads/2019/12/girl-xinh-1-480x600.jpg" alt="" height="60px  " class="float-left mr-3">
                    </span>
                    <span class="item_result_text">
                      <h3>name</h3>
                      <span>1.000.000 $</span>
                    </span>
                  </a>
                </li>
                <li class="ajax_search_item list-group-item p-0">
                  <a href="" class="d-flex align-items-center">
                    <span class="item_result_img">
                      <img src="https://www.dungplus.com/wp-content/uploads/2019/12/girl-xinh-1-480x600.jpg" alt="" height="60px  " class="float-left mr-3">
                    </span>
                    <span class="item_result_text">
                      <h3>name</h3>
                      <span>1.000.000 $</span>
                    </span>
                  </a>
                </li> -->
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
                  <a href="" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <span><i class="fas fa-user fa-lg"></i></span>
                    <span>
                      Tài khoản
                    </span>
                  </a>
                  <div class="dropdown-menu">
                    <a href="" class="dropdown-item">Login</a>
                    <a href="" class="dropdown-item">Sign Up</a>
                  </div>
                </li>
                <li id="shoppingCart" class="nav-item">
                  <a href="" class="nav-link">
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
    <!-- navbar -->
    <nav id="navbar" class="navbar-expand-sm bg-light navbar-light sticky-top">
      <ul class="navbar-nav">
        <li class="nav-item active">
          <a href="" class="nav-link">TRANG CHỦ</a>
        </li>
        <li class="nav-item">
          <a href="" class="nav-link">SHOP</a>
        </li>
        <li class="nav-item dropdown">
          <a href="" class="nav-link dropdown-toggle" data-toggle="dropdown">ĐIỆN THOẠI</a>
          <div class="dropdown-menu">
            <a href="" class="dropdown-item">Google Pixel</a>
            <a href="" class="dropdown-item">iPhone</a>
            <a href="" class="dropdown-item">Nokia</a>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a href="" class="nav-link dropdown-toggle" data-toggle="dropdown">LAPTOP</a>
          <div class="dropdown-menu">
            <a href="" class="dropdown-item">Dell</a>
            <a href="" class="dropdown-item">HP</a>
            <a href="" class="dropdown-item">Mac</a>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a href="" class="nav-link dropdown-toggle" data-toggle="dropdown">TABLET</a>
          <div class="dropdown-menu">
            <a href="" class="dropdown-item">Ipad</a>
            <a href="" class="dropdown-item">Samsung</a>
            <a href="" class="dropdown-item">Huawei</a>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a href="" class="nav-link dropdown-toggle" data-toggle="dropdown">SMARTWATCH</a>
          <div class="dropdown-menu">
            <a href="" class="dropdown-item">Google Pixel</a>
            <a href="" class="dropdown-item">iPhone</a>
            <a href="" class="dropdown-item">Nokia</a>
          </div>
        </li>
        <li class="nav-item">
          <a href="" class="nav-link">GIỚI THIỆU</a>
        </li>
        <li class="nav-item">
          <a href="" class="nav-link">TIN TỨC</a>
        </li>
        <li class="nav-item">
          <a href="" class="nav-link">LIÊN HỆ</a>
        </li>
      </ul>
    </nav>
    <!-- /navbar -->
    <!-- slide -->
    <div id="slide" class="carousel slide" data-ride="carousel">
      <!-- indicators -->
      <div class="carousel-indicators">
        <li data-target="#slide" data-slide-to="0" class="active"></li>
        <li data-target="#slide" data-slide-to="1"></li>
        <li data-target="#slide" data-slide-to="2"></li>
      </div>
      <!-- slide content -->
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="https://theme.hstatic.net/1000406564/1000647212/14/slideshow_1.jpg?v=414" alt="">
        </div>
        <div class="carousel-item">
          <img src="https://theme.hstatic.net/1000406564/1000647212/14/slideshow_2.jpg?v=414" alt="">
        </div>
        <div class="carousel-item">
          <img src="https://theme.hstatic.net/1000406564/1000647212/14/slideshow_3.jpg?v=414" alt="">
        </div>
      </div>
      <!-- slide control -->
      <a href="#slide" class="carousel-control-prev" data-slide="prev">
        <span class="carousel-control-prev-icon"></span>
      </a>
      <a href="#slide" class="carousel-control-next" data-slide="next">
        <span class="carousel-control-next-icon"></span>
      </a>
    </div>
    <!-- /slide -->
    <!-- list brand -->
    <div id="listBrand" class="py-3" style="">
      <div class="d-flex justify-content-start flex-wrap">
        <a class="card" href="">
          <?= base_url('image/pixel_logo.png'); ?>
          <img src="<?= base_url('image/pixel_logo.png'); ?>" alt="">
        </a>
         <a class="card" href="">
          <img src="https://cdn.tgdd.vn/Brand/1/Nokia42-b_21.jpg" alt="">
        </a>
         <a class="card" href="">
          <img src="https://cdn.tgdd.vn/Brand/1/Nokia42-b_21.jpg" alt="">
        </a>
         <a class="card" href="">
          <img src="https://cdn.tgdd.vn/Brand/1/Nokia42-b_21.jpg" alt="">
        </a>
         <a class="card" href="">
          <img src="https://cdn.tgdd.vn/Brand/1/Nokia42-b_21.jpg" alt="">
        </a>
         <a class="card" href="">
          <img src="https://cdn.tgdd.vn/Brand/1/Nokia42-b_21.jpg" alt="">
        </a>
         <a class="card" href="">
          <img src="https://cdn.tgdd.vn/Brand/1/Nokia42-b_21.jpg" alt="">
        </a>
         <a class="card" href="">
          <img src="https://cdn.tgdd.vn/Brand/1/Nokia42-b_21.jpg" alt="">
        </a>
         <a class="card" href="">
          <img src="https://cdn.tgdd.vn/Brand/1/Nokia42-b_21.jpg" alt="">
        </a>
         <a class="card" href="">
          <img src="https://cdn.tgdd.vn/Brand/1/Nokia42-b_21.jpg" alt="">
        </a>
         <a class="card" href="">
          <img src="https://cdn.tgdd.vn/Brand/1/Nokia42-b_21.jpg" alt="">
        </a>
         <a class="card" href="">
          <img src="https://cdn.tgdd.vn/Brand/1/Nokia42-b_21.jpg" alt="">
        </a>
         <a class="card" href="">
          <img src="https://cdn.tgdd.vn/Brand/1/Nokia42-b_21.jpg" alt="">
        </a>
       
      </div>
    </div>
    <!-- /list brand -->
    <!-- category -->
    <section id="category" class="py-5">
      <h2 class="text-center mb-3">Danh mục</h2>
      <!-- category list -->
      <div class="card-deck">
        <div class="category_item card">
          <a href="" class="card-body">
            <img src="https://cnet4.cbsistatic.com/img/EoD3Hm38dwjlazKFJmFeQ6V6Q1w=/940x0/2020/09/22/7ad7932e-6b54-4b59-afd4-a2b19a7210e4/screen-shot-2020-09-22-at-12-38-22-pm.png" alt="" class="card-img-top">
            <div class="category_info bg-light">
              <h5>SmartPhone</h5>
              <p>100 products</p>
            </div>
          </a>
        </div>
        <div class="category_item card">
          <a href="" class="card-body">
            <img src="https://fptshop.com.vn/Uploads/images/2015/Tin-Tuc/QuanLNH2/macbook-pro-16-1.JPG" alt="" class="card-img-top">
            <div class="category_info bg-light">
              <h5>Laptop</h5>
              <p>100 products</p>
            </div>
          </a>
        </div>
        <div class="category_item card">
          <a href="" class="card-body">
            <img src="https://cdn.tgdd.vn/Products/Images/522/221776/ipad-pro-12-9-inch-wifi-cellular-128gb-2020-xam-600x600-1-600x600.jpg" alt="" class="card-img-top">
            <div class="category_info bg-light">
              <h5>Tablet</h5>
              <p>100 products</p>
            </div>
          </a>
        </div>
        <div class="category_item card">
          <a href="" class="card-body">
            <img src="https://cdn.tgdd.vn/Products/Images/7077/229033/apple-watch-s6-lte-40mm-vien-nhom-day-cao-su-ava-600x600.jpg" alt="" class="card-img-top">
            <div class="category_info bg-light">
              <h5>SmartWatch</h5>
              <p>100 products</p>
            </div>
          </a>
        </div>
      </div>
    </section>
    <!-- /category -->
    <!-- product -->
    <section class="product py-5">
      <h2 class="text-center mb-3">Điện Thoại</h2>
      <div class="list_product_body">
        <!-- list products bar -->
        <div class="product_bar bg-info px-2 py-2 d-flex justify-content-between">
          <span class="badge  bg-faded">100 products</span>
          <a href="" class="badge badge-pill bg-danger">Show all</a>
        </div>
        <!-- list products -->
        <div class="card-group">
          <div class="card text-center">
            <span class="product_status badge badge-pill badge-warning">Sale out</span>
            <a href="">
              <img src="https://cdn.tgdd.vn/Products/Images/42/198422/google-pixel-5-600jpg-600x600.jpg" alt="" class="card-img-top">
            </a>
            <div class="card-body">
              <h5 class="card-title"><a href="">Google Pixel 5</a></h5>
              <p class="text-uppercase">smartphone</p>
              <h6 class="text-danger">10.000.000 &#8363;</h6>
              <hr>
              <a href="" class="btn btn-default btn-success">Add to card</a>
              <a href="" class="btn btn-default btn-primary">Detail</a>
              <a href="" class="btn btn-default btn-danger"><i class="far fa-heart"></i></a>
            </div>
          </div>
          <div class="card text-center">
            <span class="product_status badge badge-pill badge-warning">Sale out</span>
            <a href="">
              <img src="https://cdn.tgdd.vn/Products/Images/42/198422/google-pixel-5-600jpg-600x600.jpg" alt="" class="card-img-top">
            </a>
            <div class="card-body">
              <h5 class="card-title"><a href="">Google Pixel 5</a></h5>
              <p class="text-uppercase">smartphone</p>
              <h6 class="text-danger">10.000.000 &#8363;</h6>
              <hr>
              <a href="" class="btn btn-default btn-success">Add to card</a>
              <a href="" class="btn btn-default btn-primary">Detail</a>
              <a href="" class="btn btn-default btn-danger"><i class="far fa-heart"></i></a>
            </div>
          </div>
          <div class="card text-center">
            <span class="product_status badge badge-pill badge-warning">Sale out</span>
            <a href="">
              <img src="https://cdn.tgdd.vn/Products/Images/42/198422/google-pixel-5-600jpg-600x600.jpg" alt="" class="card-img-top">
            </a>
            <div class="card-body">
              <h5 class="card-title"><a href="">Google Pixel 5</a></h5>
              <p class="text-uppercase">smartphone</p>
              <h6 class="text-danger">10.000.000 &#8363;</h6>
              <hr>
              <a href="" class="btn btn-default btn-success">Add to card</a>
              <a href="" class="btn btn-default btn-primary">Detail</a>
              <a href="" class="btn btn-default btn-danger"><i class="far fa-heart"></i></a>
            </div>
          </div>
          <div class="card text-center">
            <span class="product_status badge badge-pill badge-warning">Sale out</span>
            <a href="">
              <img src="https://cdn.tgdd.vn/Products/Images/42/198422/google-pixel-5-600jpg-600x600.jpg" alt="" class="card-img-top">
            </a>
            <div class="card-body">
              <h5 class="card-title"><a href="">Google Pixel 5</a></h5>
              <p class="text-uppercase">smartphone</p>
              <h6 class="text-danger">10.000.000 &#8363;</h6>
              <hr>
              <a href="" class="btn btn-default btn-success">Add to card</a>
              <a href="" class="btn btn-default btn-primary">Detail</a>
              <a href="" class="btn btn-default btn-danger"><i class="far fa-heart"></i></a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /product -->
    <!-- product -->
    <section class="product py-5">
      <h2 class="text-center mb-3">Laptop</h2>
      <div class="list_product_body">
        <!-- list products bar -->
        <div class="product_bar bg-info px-2 py-2 d-flex justify-content-between">
          <span class="badge  bg-faded">100 products</span>
          <a href="" class="badge badge-pill bg-danger">Show all</a>
        </div>
        <!-- list products -->
        <div class="card-group">
          <div class="card text-center">
            <span class="product_status badge badge-pill badge-warning">Sale out</span>
            <a href="">
              <img src="https://cdn.tgdd.vn/Products/Images/44/226169/apple-macbook-air-2020-i3-11ghz-8gb-256gb-mwtj2sa-600x600.jpg" alt="" class="card-img-top">
            </a>
            <div class="card-body">
              <h5 class="card-title"><a href="">MacBook Air 2020</a></h5>
              <p class="text-uppercase">laptop</p>
              <h6 class="text-danger">28.990.000 &#8363;</h6>
              <hr>
              <a href="" class="btn btn-default btn-success">Add to card</a>
              <a href="" class="btn btn-default btn-primary">Detail</a>
              <a href="" class="btn btn-default btn-danger"><i class="far fa-heart"></i></a>
            </div>
          </div>
          <div class="card text-center">
            <span class="product_status badge badge-pill badge-warning">Sale out</span>
            <a href="">
              <img src="https://cdn.tgdd.vn/Products/Images/44/226169/apple-macbook-air-2020-i3-11ghz-8gb-256gb-mwtj2sa-600x600.jpg" alt="" class="card-img-top">
            </a>
            <div class="card-body">
              <h5 class="card-title"><a href="">MacBook Air 2020</a></h5>
              <p class="text-uppercase">laptop</p>
              <h6 class="text-danger">28.990.000 &#8363;</h6>
              <hr>
              <a href="" class="btn btn-default btn-success">Add to card</a>
              <a href="" class="btn btn-default btn-primary">Detail</a>
              <a href="" class="btn btn-default btn-danger"><i class="far fa-heart"></i></a>
            </div>
          </div>
          <div class="card text-center">
            <span class="product_status badge badge-pill badge-warning">Sale out</span>
            <a href="">
              <img src="https://cdn.tgdd.vn/Products/Images/44/226169/apple-macbook-air-2020-i3-11ghz-8gb-256gb-mwtj2sa-600x600.jpg" alt="" class="card-img-top">
            </a>
            <div class="card-body">
              <h5 class="card-title"><a href="">MacBook Air 2020</a></h5>
              <p class="text-uppercase">laptop</p>
              <h6 class="text-danger">28.990.000 &#8363;</h6>
              <hr>
              <a href="" class="btn btn-default btn-success">Add to card</a>
              <a href="" class="btn btn-default btn-primary">Detail</a>
              <a href="" class="btn btn-default btn-danger"><i class="far fa-heart"></i></a>
            </div>
          </div>
          <div class="card text-center">
            <span class="product_status badge badge-pill badge-warning">Sale out</span>
            <a href="">
              <img src="https://cdn.tgdd.vn/Products/Images/44/226169/apple-macbook-air-2020-i3-11ghz-8gb-256gb-mwtj2sa-600x600.jpg" alt="" class="card-img-top">
            </a>
            <div class="card-body">
              <h5 class="card-title"><a href="">MacBook Air 2020</a></h5>
              <p class="text-uppercase">laptop</p>
              <h6 class="text-danger">28.990.000 &#8363;</h6>
              <hr>
              <a href="" class="btn btn-default btn-success">Add to card</a>
              <a href="" class="btn btn-default btn-primary">Detail</a>
              <a href="" class="btn btn-default btn-danger"><i class="far fa-heart"></i></a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /product -->
    <!-- product -->
    <section class="product py-5">
      <h2 class="text-center mb-3">Tablet</h2>
      <div class="list_product_body">
        <!-- list products bar -->
        <div class="product_bar bg-info px-2 py-2 d-flex justify-content-between">
          <span class="badge  bg-faded">100 products</span>
          <a href="" class="badge badge-pill bg-danger">Show all</a>
        </div>
        <!-- list products -->
        <div class="card-group">
          <div class="card text-center">
            <span class="product_status badge badge-pill badge-warning">Sale out</span>
            <a href="">
              <img src="https://cdn.tgdd.vn/Products/Images/522/221776/ipad-pro-12-9-inch-wifi-cellular-128gb-2020-xam-600x600-1-600x600.jpg" alt="" class="card-img-top">
            </a>
            <div class="card-body">
              <h5 class="card-title"><a href="">iPad Pro 12.9 inch Wifi Cellular 128GB (2020)</a>
              </h5>
              <p class="text-uppercase">tablet</p>
              <h6 class="text-danger">10.000.000 &#8363;</h6>
              <hr>
              <a href="" class="btn btn-default btn-success">Add to card</a>
              <a href="" class="btn btn-default btn-primary">Detail</a>
              <a href="" class="btn btn-default btn-danger"><i class="far fa-heart"></i></a>
            </div>
          </div>
          <div class="card text-center">
            <span class="product_status badge badge-pill badge-warning">Sale out</span>
            <a href="">
              <img src="https://cdn.tgdd.vn/Products/Images/522/221776/ipad-pro-12-9-inch-wifi-cellular-128gb-2020-xam-600x600-1-600x600.jpg" alt="" class="card-img-top">
            </a>
            <div class="card-body">
              <h5 class="card-title"><a href="">iPad Pro 12.9 inch Wifi Cellular 128GB (2020)</a>
              </h5>
              <p class="text-uppercase">tablet</p>
              <h6 class="text-danger">10.000.000 &#8363;</h6>
              <hr>
              <a href="" class="btn btn-default btn-success">Add to card</a>
              <a href="" class="btn btn-default btn-primary">Detail</a>
              <a href="" class="btn btn-default btn-danger"><i class="far fa-heart"></i></a>
            </div>
          </div>
          <div class="card text-center">
            <span class="product_status badge badge-pill badge-warning">Sale out</span>
            <a href="">
              <img src="https://cdn.tgdd.vn/Products/Images/522/221776/ipad-pro-12-9-inch-wifi-cellular-128gb-2020-xam-600x600-1-600x600.jpg" alt="" class="card-img-top">
            </a>
            <div class="card-body">
              <h5 class="card-title"><a href="">iPad Pro 12.9 inch Wifi Cellular 128GB (2020)</a>
              </h5>
              <p class="text-uppercase">tablet</p>
              <h6 class="text-danger">10.000.000 &#8363;</h6>
              <hr>
              <a href="" class="btn btn-default btn-success">Add to card</a>
              <a href="" class="btn btn-default btn-primary">Detail</a>
              <a href="" class="btn btn-default btn-danger"><i class="far fa-heart"></i></a>
            </div>
          </div>
          <div class="card text-center">
            <span class="product_status badge badge-pill badge-warning">Sale out</span>
            <a href="">
              <img src="https://cdn.tgdd.vn/Products/Images/522/221776/ipad-pro-12-9-inch-wifi-cellular-128gb-2020-xam-600x600-1-600x600.jpg" alt="" class="card-img-top">
            </a>
            <div class="card-body">
              <h5 class="card-title"><a href="">iPad Pro 12.9 inch Wifi Cellular 128GB (2020)</a>
              </h5>
              <p class="text-uppercase">tablet</p>
              <h6 class="text-danger">10.000.000 &#8363;</h6>
              <hr>
              <a href="" class="btn btn-default btn-success">Add to card</a>
              <a href="" class="btn btn-default btn-primary">Detail</a>
              <a href="" class="btn btn-default btn-danger"><i class="far fa-heart"></i></a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /product -->
    <!-- product -->
    <section class="product py-5">
      <h2 class="text-center mb-3">SmartWatch</h2>
      <div class="list_product_body">
        <!-- list products bar -->
        <div class="product_bar bg-info px-2 py-2 d-flex justify-content-between">
          <span class="badge  bg-faded">100 products</span>
          <a href="" class="badge badge-pill bg-danger">Show all</a>
        </div>
        <!-- list products -->
        <div class="card-group">
          <div class="card text-center">
            <span class="product_status badge badge-pill badge-warning">Sale out</span>
            <a href="">
              <img src="https://cdn.tgdd.vn/Products/Images/7077/229033/apple-watch-s6-lte-40mm-vien-nhom-day-cao-su-ava-600x600.jpg" alt="" class="card-img-top">
            </a>
            <div class="card-body">
              <h5 class="card-title"><a href="">Apple Watch S6 LTE 40mm</a></h5>
              <p class="text-uppercase">smartwatch</p>
              <h6 class="text-danger">14.391.000 &#8363;</h6>
              <hr>
              <a href="" class="btn btn-default btn-success">Add to card</a>
              <a href="" class="btn btn-default btn-primary">Detail</a>
              <a href="" class="btn btn-default btn-danger"><i class="far fa-heart"></i></a>
            </div>
          </div>
          <div class="card text-center">
            <span class="product_status badge badge-pill badge-warning">Sale out</span>
            <a href="">
              <img src="https://cdn.tgdd.vn/Products/Images/7077/229033/apple-watch-s6-lte-40mm-vien-nhom-day-cao-su-ava-600x600.jpg" alt="" class="card-img-top">
            </a>
            <div class="card-body">
              <h5 class="card-title"><a href="">Apple Watch S6 LTE 40mm</a></h5>
              <p class="text-uppercase">smartwatch</p>
              <h6 class="text-danger">14.391.000 &#8363;</h6>
              <hr>
              <a href="" class="btn btn-default btn-success">Add to card</a>
              <a href="" class="btn btn-default btn-primary">Detail</a>
              <a href="" class="btn btn-default btn-danger"><i class="far fa-heart"></i></a>
            </div>
          </div>
          <div class="card text-center">
            <span class="product_status badge badge-pill badge-warning">Sale out</span>
            <a href="">
              <img src="https://cdn.tgdd.vn/Products/Images/7077/229033/apple-watch-s6-lte-40mm-vien-nhom-day-cao-su-ava-600x600.jpg" alt="" class="card-img-top">
            </a>
            <div class="card-body">
              <h5 class="card-title"><a href="">Apple Watch S6 LTE 40mm</a></h5>
              <p class="text-uppercase">smartwatch</p>
              <h6 class="text-danger">14.391.000 &#8363;</h6>
              <hr>
              <a href="" class="btn btn-default btn-success">Add to card</a>
              <a href="" class="btn btn-default btn-primary">Detail</a>
              <a href="" class="btn btn-default btn-danger"><i class="far fa-heart"></i></a>
            </div>
          </div>
          <div class="card text-center">
            <span class="product_status badge badge-pill badge-warning">Sale out</span>
            <a href="">
              <img src="https://cdn.tgdd.vn/Products/Images/7077/229033/apple-watch-s6-lte-40mm-vien-nhom-day-cao-su-ava-600x600.jpg" alt="" class="card-img-top">
            </a>
            <div class="card-body">
              <h5 class="card-title"><a href="">Apple Watch S6 LTE 40mm</a></h5>
              <p class="text-uppercase">smartwatch</p>
              <h6 class="text-danger">14.391.000 &#8363;</h6>
              <hr>
              <a href="" class="btn btn-default btn-success">Add to card</a>
              <a href="" class="btn btn-default btn-primary">Detail</a>
              <a href="" class="btn btn-default btn-danger"><i class="far fa-heart"></i></a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /product -->
    <!-- news -->
    <section  id="news" class="py-5">
      <h2 class="text-center mb-3">Tin tức</h2>
      <div class="list_news">
        <div class="card-deck">
          <div class="card">
            <a href="">
              <img src="https://cdn.tgdd.vn/Files/2020/12/22/1315451/note20ultra5g_800x450-300x200.jpg" alt="" class="card-img-top">
            </a>
            <div class="card-body">
              <ul class="card-title nav">
                <li class="nav-item mr-2">
                  <i class="fas fa-calendar-alt"></i>
                  <span>10 giờ trước</span>
                </li>
                <li class="nav-item">
                  <i class="fas fa-user-edit"></i>
                  <span>TGDĐ</span>
                </li>
              </ul>
              <h5 class="card-title text-uppercase">
                <a href="">
                  Trên tay OPPO A15: Giá chỉ 3.49 triệu đã có thiết kế trẻ trung, màn hình lớn 6.52 inch và 3 camera đa chức năng
                </a>
              </h5>
              <p class="card-text">
                đại chiến ở phân khúc giá rẻ bao giờ mới có thể ngừng lại, khi rất nhiều hãng vẫn cứ liên tục cho ra mắt những chiếc điện thoại giá rẻ? OPPO như thường lệ lại tiếp tục ra mắt OPPO A15 với thiết kế trẻ trung.
              </p>
              <a href="" class="btn btn-default btn-primary">Xem thêm<i class="fas fa-angle-double-right"></i></a>
            </div>
          </div>
          <div class="card">
            <a href="">
              <img src="https://cdn.tgdd.vn/Files/2020/12/22/1315505/dothivungtau3_800x450.jpg" alt="" class="card-img-top">
            </a>
            <div class="card-body">
              <ul class="card-title nav">
                <li class="nav-item mr-2">
                  <i class="fas fa-calendar-alt"></i>
                  <span>10 giờ trước</span>
                </li>
                <li class="nav-item">
                  <i class="fas fa-user-edit"></i>
                  <span>TGDĐ</span>
                </li>
              </ul>
              <h5 class="card-title text-uppercase">
                <a href="">
                  Vũng Tàu hợp tác cùng Viettel điều hành đô thị thông minh với 1.100 camera, kịp thời xử lý vi phạm và phản ánh của người dân
                </a>
              </h5>
              <p class="card-text">
                Chiều ngày 21/12, theo thông tin từ báo Tuổi Trẻ chia sẻ thì TP Vũng Tàu đã đưa vào sử dụng trung tâm điều hành đô thị thông minh.
              </p>
              <a href="" class="btn btn-default btn-primary">Xem thêm<i class="fas fa-angle-double-right"></i></a>
            </div>
          </div>
          <div class="card">
            <a href="">
              <img src="https://cdn.tgdd.vn/Files/2020/12/10/1312707/csm_google_pixel_5_pro-0_800x450.jpg" alt="" class="card-img-top">
            </a>
            <div class="card-body">
              <ul class="card-title nav">
                <li class="nav-item mr-2">
                  <i class="fas fa-calendar-alt"></i>
                  <span>10 giờ trước</span>
                </li>
                <li class="nav-item">
                  <i class="fas fa-user-edit"></i>
                  <span>TGDĐ</span>
                </li>
              </ul>
              <h5 class="card-title text-uppercase">
                <a href="">
                  Google Pixel 5 Pro lộ ảnh thực tế với camera selfie ẩn dưới màn hình, có thể dùng chip Snapdragon 865 cùng bộ nhớ RAM 8GB
                </a>
              </h5>
              <p class="card-text">
                Hình ảnh thực tế Google Pixel 5 Pro mới đây đã xuất hiện trên các trang mạng, điều thú vị là màn hình của smartphone này không có lỗ khoét hay notch nào để chứa camera.
              </p>
              <a href="" class="btn btn-default btn-primary">Xem thêm<i class="fas fa-angle-double-right"></i></a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /news -->
    <!-- footer -->
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