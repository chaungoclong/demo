<?php
require_once 'common.php';
require_once RF . '/include/header.php';
require_once RF . '/include/navbar.php';
?>

<main>
	<div class="all_wrapper bg-faded" style="padding: 0px 85px;">
		<div class="row my-4 m-0">

			<!-- hiển thị tin tức -->
			<div class="col-9 p-0 py-3 bg-white shadow">
				<div class="container mb-3 d-flex justify-content-between align-items-center">
					<h5 class="p-0 m-0	">TIN TỨC</h5>

					<div class="d-flex">
						<input type="text" id="q" class="form-control" placeholder="Tên bài viết">
						<!-- sắp xếp -->
						<select id="sort" class="custom-select">
							<option value="1" selected>Mới nhất</option>
							<option value="2">Cũ nhất</option>
							<option value="3">Tiêu đề: A - Z</option>
							<option value="1">Tiêu đề: Z - A </option>
						</select>
					</div>
				</div>

				<div class="container"><hr></div>

				<?php 
					$getNewsSQL = "SELECT * FROM db_news WHERE news_active = 1";
					$listNews = db_get($getNewsSQL, 0);
				?>

				<div class="news_left">
					
				</div>

				<div class="page"></div>
			</div>

			<!-- hiển thị tin tức mói nhất -->
			<div class="col-3 pl-4">
				<div class="bg-white py-3 px-2 shadow">
					<h5 class="text-center mb-3">BÀI VIẾT MỚI</h5>
					<hr class="mb-2" style="border-top: 2px solid #007bff;">

					<?php 
						$getNewsSQL = "SELECT * FROM db_news WHERE news_active = 1 ORDER BY create_at DESC LIMIT 10";
						$listNews = db_get($getNewsSQL, 0);
					?>
					<div class="news_right">
						
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<?php 
require_once RF . '/include/footer.php';
?>
<script>
	$(function() {
		fetchNews(1);

		// lấy danh sách tin tức khi lọc
		$(document).on('change', '#sort', function() {
			fetchNews(1);
		});

		// lấy danh sách tin tức khi tìm kiếm
		$(document).on('input', '#q', function() {
			fetchNews(1);
		});

		// lấy danh sách tin tức khi chuyển trang
		$(document).on('click', '.page-item', function() {
			let currentPage = parseInt($(this).data("page-number"));
			if(isNaN(currentPage)) {
				currentPage = 1;
			}
			fetchNews(currentPage);
			$('html, body').scrollTop(0);
		});
	});

	function fetchNews(currentPage) {
		let action = "fetch";
		let q = "%" + $('#q').val() + "%";
		let sort = $('#sort').val();
		let data = {q: q, sort: sort, currentPage: currentPage, action: action};
		let result = sendAJax("fetch_news.php", "post", "json", data);

		$('.news_left').html(result.news_left);
		$('.news_right').html(result.news_right);
		$('.page').html(result.pagination);
	}

</script>	