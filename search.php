<?php
require_once 'common.php';
require_once RF . '/include/header.php';
require_once RF . '/include/navbar.php';

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

//danh sách ản phẩm sau khi phân trang
$listResult = fetch_list(
    "db_product",
    "pro_active = 1 AND pro_name LIKE('%$keyWord%') LIMIT {$page['limit']} OFFSET {$page['offset']}",
    ["*"],
    2
);

//chia hàng để hiển thị
$numResult = $listResult->num_rows;
$numCol = 4;
$numRow = row_qty($numResult, $numCol);
?>

<main>
    <div style="padding-left: 85px; padding-right: 85px;">
        <section class="product py-5">
            <h2 class="text-center mb-3">found <?= $numRecord; ?> results match</h2>
            <div class="list_product_body">
                <!-- list products bar -->
                <div class="product_bar bg-info px-2 py-2 d-flex justify-content-between">
                    <span class="badge  bg-faded">
                        <span><?= $numRecord; ?> sản phẩm</span>
                        <span>
                            (
                                <?php
                                $start = (int)$page["offset"] + 1;
                                $end   = (int)$page["offset"] + (int)$page["limit"];
                                $end   = $end <= $numRecord ? $end : $numRecord;
                                echo $start . " - " . $end;
                                ?>
                                )
                            </span>
                        </span>
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
                                            <button class="btn_add_cart_out btn btn-success" data-pro-id="<?= $result['pro_id']; ?>">Thêm vào giỏ</button>
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
        </div>
    </main>

    <?php
//phân trang
    echo $page['html'];

    require_once RF . '/include/footer.php';
    ?>
