<?php
require_once 'common.php';
$keyWord = input_get("q");
$result = fetch_list("db_product", "pro_name LIKE('%$keyWord%')", ["*"], 1);
echo "ok";
echo "okk";
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <link rel="stylesheet" href="dist/bootstrap/css/bootstrap.css">
        <link rel="stylesheet" href="assets/font/css/all.css">
        <link rel="stylesheet" href="assets/css/home.css">
        <script src="dist/jquery/jquery-3.5.1.js"></script>
        <script src="assets/js/home.js"></script>
        <script src="dist/popper/popper.min.js"></script>
        <script src="dist/bootstrap/js/bootstrap.js"></script>
    </head>
    <body>
        <?php
        require_once RF . '/include/header.php';
        require_once RF . '/include/navbar.php';
        require_once RF . '/include/slider.php';
        ?>
        <!-- in ket qua -->
        <?php  
            
        ?>
        <section class="product py-5">
            <h2 class="text-center mb-3">found 100 results match</h2>
            <div class="list_product_body">
                <!-- list products bar -->
                <div class="product_bar bg-info px-2 py-2 d-flex justify-content-between">
                    <span class="badge  bg-faded">100 products</span>
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
                </div>
            </div>
        </section>
        <!-- /product -->
        <?php
        require_once RF . '/include/footer.php';
        ?>
    </body>
</html>