<?php
require_once 'common.php';
require_once RF . '/include/header.php';
require_once RF . '/include/navbar.php';
require_once RF . '/include/slider.php';
?>
<?php
/**
* [tạo câu sql lấy các hãng tương ứng với danh mục. nếu không có danh mục hiện hết]
*/
$cat = input_get("cat"); //danh mục
$bra = input_get("bra"); //hãng
$getBrandSQL =
"
SELECT * FROM db_brand
WHERE bra_id IN
(
	SELECT bra_id FROM db_product
	WHERE cat_id = '{$cat}'
)
";
if($cat) {
	$listBrands = get_list($getBrandSQL, 1);
} else {
	$listBrands = fetch_tbl("db_brand", 1);
}
?>
<main>
	<div style="padding-left: 85px; padding-right: 85px;">
		<div class="row m-0 mt-3">
			<!-- filter -->
			<div class="col-3 pl-0	" id="pro-filter">
				<h4></h4>

				<!-- price -->
				<div class="list-group mb-3">
					<h5>Giá</h5>
					<p id="show_price">Mức giá</p>
					<input type="hidden" id="min_price" class="price">
					<input type="hidden" id="max_price" class="price">
					<div id="price_range" class="ml-2 bg-warning"></div>
				</div>

				<!-- category -->
				<div class="list-group mb-3" id="cat_filter">
					<h5>Danh mục</h5>
					<!-- danh sách danh mục -->
					<?php 
						$catID = input_get('cat');
						$param = [];
						$format = "";
						if($catID) {
							$getListCategorySQL = "SELECT * FROM db_category 
							WHERE cat_id = ?";
							$param[] = $catID;
							$format .= "i";
						} else {
							$getListCategorySQL = "SELECT * FROM db_category 
							WHERE cat_id IN(
							SELECT cat_id FROM db_product
							)
							AND cat_active = 1
							";
						}
						$listCategory = db_get($getListCategorySQL, 0, $param, $format);
					 ?>

					 <!-- in danh sách danh mục -->
					 <?php foreach ($listCategory as $key => $category): ?>
					 	<div class="list-group-item pl-4">
					 		<label class="form-check-label">
					 			<input  class="form-check-input filter_item category" type="checkbox" value="<?= $category['cat_id']; ?>" <?= $catID ? "checked disabled" : ""; ?>>
					 			<?= $category['cat_name']; ?>
					 		</label>
					 	</div>
					 <?php endforeach ?>
				</div>

				<!-- brand -->
				<div class="list-group">
					<h5>Hãng</h5>
					<!-- danh sách hãng -->
					<?php 
						$braID = input_get('bra');
						$param = [];
						$format = "";
						if(!$catID && !$braID) {
							$getListBrandSQL = "SELECT * FROM db_brand 
							WHERE bra_id IN(
							SELECT bra_id FROM db_product
							)
							AND bra_active = 1
							";
						} elseif($braID) {
							$getListBrandSQL = "SELECT * FROM db_brand WHERE bra_id = ?";
							$param[] = $braID;
							$format .= "i";
						} elseif($catID) {
							$getListBrandSQL = "SELECT * FROM db_brand WHERE bra_id IN(
								SELECT bra_id FROM db_product WHERE cat_id = ?
							)";
							$param[] = $catID;
							$format .= "i";
						} 
						$listBrand = db_get($getListBrandSQL, 0, $param, $format);
					 ?>

					 <!-- in danh sách hãng -->
					 <?php foreach ($listBrand as $key => $brand): ?>
					 	<div class="list-group-item pl-4">
					 		<label class="form-check-label">
					 			<input  class="form-check-input filter_item brand" type="checkbox" value="<?= $brand['bra_id']; ?>" <?= $braID ? "checked disabled" : ""; ?>>
					 			<?= $brand['bra_name']; ?>
					 		</label>
					 	</div>
					 <?php endforeach ?>
				</div>
			</div>

			<?php 
				$getRangePriceSQL = "
				SELECT MIN(pro_price) AS min_price, MAX(pro_price) AS max_price FROM db_product WHERE 1
				";
				$param = [];
				$format = "";
				if($catID) {
					$getRangePriceSQL .= " AND cat_id = ?";
					$param[] = $catID;
					$format .= "i";
				}
				if($braID) {
					$getRangePriceSQL .= " AND bra_id = ?";
					$param[] = $braID;
					$format .= "i";
				}
				$rangePrice = s_row($getRangePriceSQL, $param, $format);
				$minPrice = $rangePrice['min_price'];
				$maxPrice = $rangePrice['max_price'];
			 ?>

			<!-- product -->
			<div class="col-9 p-0">
				<div class="d-flex justify-content-end mb-3">
					<select id="sort" class="custom-select w-25">
						<option value="1" selected>Tên: A-Z</option>
						<option value="2">Tên: Z-A</option>
						<option value="3">Giá: Tăng dần</option>
						<option value="4">Giá: Giảm dần</option>
						<option value="5">Mới nhất</option>
						<option value="6">Cũ nhất</option>
					</select>
				</div>
				<div id="product_box"></div>
				
			</div>
		</div>
	</div>
</main>

<?php
require_once RF . '/include/footer.php';
?>
<script>
	// hàm thực hiện lọc và trả về html sau khi tìm kiếm sắp xếp và phân trang
	function filtering(currentPage) {
		let action = "fetch";
		let sort = $('#sort').val();
		let min_price = $('#min_price').val();
		let max_price = $('#max_price').val();
		let brand = getOption("brand");
		let category = getOption("category");
		let data = {
			action: action,
			sort: sort,
			min_price: min_price,
			max_price: max_price,
			brand: brand, 
			category: category, 
			currentPage: currentPage
		};
		let filter = $.ajax({
			url: "fetch_product.php",
			type: "POST",
			dataType: "text",
			data: data
		});

		filter.done(function(res) {
			$('#product_box').html(res);
			$('body').tooltip({selector: '[data-toggle="tooltip"]'});
		});
		console.log(data);
	}

	// hàm lấy các lựa chọn của một mục tìm kiếm
	function getOption(className) {
		let listOption = [];
		$('.' + className + ':checked').each(function() {
			listOption.push($(this).val());
		});
		return listOption;
	}

	$(function() {
		// thực hiện lock khi vừa vào trang
		filtering(1);

		$(document).on('change', '#sort', function() {
			// console.log($('li.active'));
			// let currentPage = parseInt($('li.page-item.active').data('page-number'));
			// if(isNaN(currentPage)) {
			// 	currentPage = 1;
			// };
			filtering(1);
		});

		// thực hiện lọc khi một lựa chọn được click vào
		$(document).on('click', '.filter_item', function() {
			filtering(1);
		});

		// thực hiện lọc khi nhấn nút phân trang
		$(document).on('click', '.page-item', function() {
			let currentPage = parseInt($(this).data('page-number'));
			if(isNaN(currentPage)) {
				currentPage = 1;
			};
			filtering(currentPage);
		})

		$('#price_range').slider({
			range: true,
			min: <?= (int)$minPrice; ?>,
			max: <?= (int)$maxPrice; ?>,
			value: [1, 60000000],
			stop: function( event, ui ) {
				let format = new Intl.NumberFormat('vi-VN', {style: "currency", currency: "VND"});
				$('#show_price').html(format.format(ui.values[0]) + " - " + format.format(ui.values[1]));
				$('#min_price').val(ui.values[0]);
				$('#max_price').val(ui.values[1]);

				// thực hiện lọc sau khi ngừng di chuyển thanh trượt trên thanh giá sản phẩm
				filtering(1);
			}
		});
	});
</script>