<?php
require_once 'common.php';
require_once RF . '/include/header.php';
require_once RF . '/include/navbar.php';

//từ khóa tìm kiếm
$q = input_get('q');
$keyWord = "%" . $q . "%";

?>

<main>
    <div style="padding-left: 85px; padding-right: 85px;">

        <!-- sort -->
        <div class="d-flex justify-content-between align-items-center my-3">
            <!-- option -->
            <div class="">
                <div class="form-check-inline">
                    <label class="form-check-label" style="font-size: 18px;">
                        <input type="radio" class="form-check-input type_option" name="type" value="product" checked>Sản phẩm
                    </label>
                </div>
                <div class="form-check-inline">
                    <label class="form-check-label" style="font-size: 18px;">
                        <input type="radio" class="form-check-input type_option" name="type" value="news">Tin tức
                    </label>
                </div>
            </div>
            
            <div>
                <!-- change display -->
                <span class="btn mr-3" id="change-show">
                    <i class="fas fa-list fa-2x"></i>
                </span>
                <!-- sort -->
                <select id="sort" class="custom-select" style="width: 200px;">
                    <option value="1" selected>Tên: A-Z</option>
                    <option value="2">Tên: Z-A</option>
                    <option class="only_product" value="3">Giá: Tăng dần</option>
                    <option class="only_product" value="4">Giá: Giảm dần</option>
                    <option value="5">Cũ nhất</option>
                    <option value="6">Mới nhất</option>
                </select>
            </div>
        </div>

        <!-- show result -->
        <div class="" id="show-result">
            
        </div>
    </div>
</main>

<?php
require_once RF . '/include/footer.php';
?>

<script>
    function getResult(currentPage) {
        let type = $('.type_option:checked').val();
        let keyWord = '<?= $keyWord ?? "%%"; ?>';
        let sort = $('#sort').val();
        let action = "search";
        let data = {type: type, keyWord: keyWord, currentPage: currentPage, sort: sort, action: action};
        let search = sendAJax("fetch_search.php", "post", "html", data);
        $('#show-result').html(search);
        $('body').tooltip({selector: '[data-toggle="tooltip"]'});

         // ẩn các tùy chọn chỉ có trên sản phẩm nếu thể loại là tin tức
        if(type == "news") {
            $('.only_product').prop('hidden', true);
        } else {
             $('.only_product').prop('hidden', false);
        }
    }

    $(function() {
        getResult(1);

        // lấy kết quả tìm kiếm khi thay đổi thể loại tìm kiếm
        $(document).on('click', '.type_option', function() {
            getResult(1);
        });

        // Lấy kết quả tìm kiếm khi sắp xếp
        $(document).on('change', '#sort', function() {
            getResult(1);
        });

        // Lấy kết quả tìm kiếm khi chuyển trang
        $(document).on('click', '.page-item', function() {
            let currentPage = parseInt($(this).data('page-number'));
            if(isNaN(currentPage)) {
                currentPage = 1;
            }
            getResult(currentPage);
            $('html, body').scrollTop(150);
        });

        // thay đổi cách hiển thị danh sách sản phẩm
        $(document).on('click', '#change-show', function() {
            $('.card').toggleClass('col-12 col-3');
            $(this).find('i').toggleClass('fa-list fa-th');
        });
    });
</script>
