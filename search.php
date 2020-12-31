<?php
require_once 'common.php';
//từ khóa tìm kiếm
$keyWord = "%" . input_get("q") . "%";
//câu sql lấy kêt quả tìm kiếm
$getResultSQL = "SELECT * FROM db_product WHERE pro_active = 1  AND pro_name LIKE(?)";
//số lượng bản ghi
$listRecord = db_get($getResultSQL, [$keyWord], 1);
$numRecord = $listRecord->num_rows;
//phân trang
$currentLink = create_link(base_url("search.php"), ["q"=>$keyWord, "page"=>"{page}"]);
$resultPerPage = 8;
$currentPage = input_get("page") ? input_get("page") : 1;
$page = paginate($currentLink, $numRecord, $currentPage, $resultPerPage);
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
        // require_once RF . '/include/slider.php';
    ?>
    <!-- hiển thị sản phẩm sau khi search -> phân trang -->
    <?php
    $listResult = fetch_list(
        "db_product",
        "pro_active = 1 AND pro_name LIKE('%$keyWord%') LIMIT {$page['limit']} OFFSET {$page['offset']}",
        ["*"],
        2
    );
    $numResult = $listResult->num_rows;
    $numCol = 4;
    $numRow = row_qty($numResult, $numCol);
    ?>
    <section class="product py-5">
        <h2 class="text-center mb-3">found <?= $numResult; ?> results match</h2>
        <div class="list_product_body">
            <!-- list products bar -->
            <div class="product_bar bg-info px-2 py-2 d-flex justify-content-between">
                <span class="badge  bg-faded"><?= $numResult; ?> products</span>
            </div>
            <!-- list products -->
            <?php for ($i = 0; $i < $numRow ; $i++): ?>
                <?php $countCol = 0; ?>
                <div class="card-group">
                    <?php while ($result = $listResult->fetch_assoc()): ?>
                        <!-- ------------------------------------product ----------------------------------- -->
                        <div class="card text-center" style="max-width: 25%;">
                            <?php if ($result['pro_qty'] == 0): ?>
                                <span class="product_status badge badge-pill badge-warning">Sale out</span>
                            <?php endif ?>
                            <a href='<?= create_link(base_url("product_detail.php"), ["proid"=> $result["pro_id"]]); ?>'>
                                <img src="<?= $result['pro_img']; ?>" alt="" class="card-img-top">
                            </a>
                            <div class="card-body">
                                <!-- thông tin sản phẩm -->
                                <h5 class="card-title"><a href=""><?= $result['pro_name']; ?></a></h5>
                                <?php
                                $cat = fetch_rows("db_category", "cat_id = '{$result["cat_id"]}'", ["cat_name"]);
                                ?>
                                <p class="text-uppercase"><?= $cat['cat_name']; ?></p>
                                <h6 class="text-danger"><?= number_format($result['pro_price'], 2, ',', '.'); ?> &#8363;</h6>
                                <hr>
                                <!-- thêm vào giỏ hàng -->
                                <?php if ($result['pro_qty']): ?>
                                    <a href='<?= create_link(base_url("card.php"), ["proid"=> $result["pro_id"]]); ?>' class="btn btn-default btn-success">Add to card</a>
                                <?php endif ?>
                                <!-- xem chi tiết sản phẩm -->
                                <a href='<?= create_link(base_url("product_detail.php"), ["proid"=> $result["pro_id"]]); ?>' class="btn btn-default btn-primary">Detail</a>
                                <!-- danh sách yêu thích -->
                                <a href='<?= create_link(base_url("wishlist.php"), ["proid"=> $result["pro_id"]]); ?>' class="btn btn-default btn-danger"><i class="far fa-heart"></i></a>
                            </div>
                        </div>
                        <!-- ------------------------------------/product ----------------------------------- -->
                        <?php
                        $countCol++ ;
                        if($countCol == $numCol) {
                            break;
                        }
                        ?>
                    <?php endwhile ?>
                </div>
            <?php endfor ?>
        </div>
    </section>
    
    <!-- phân trang -->
    <?php 
        echo $page['html'];
     ?>
    <!-- /product -->
    <?php
    require_once RF . '/include/footer.php';
    ?>
</body>
</html>