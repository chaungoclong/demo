<?php 
require_once 'common.php';
require_once 'include/header.php';
require_once 'include/navbar.php';

$newsID = data_input($_GET['newsid']);
if(int($newsID) === false) {
	die('<h1 class="text-center text-danger m-5">Không tìm thấy trang</h1>');
}

$news = getNewsByID($newsID);
if(!$news) {
	die('<h1 class="text-center text-danger m-5">Không tìm thấy bài viết</h1>');
}
?>
<main>
	<div style="padding: 0px 85px;" class="bg-faded d-flex justify-content-center">
		<div class="news_content container my-3 py-3 bg-white shadow" style="width: 830px; font-size: 17px;">
			<h1 class=""><strong><?= $news['news_title']; ?></strong></h1>

			<div class="auth_detail mb-2">
				<span class="text-primary" style="font-size: 18px;"><?= $news['create_by']; ?></span>

				<?php $time = strtotime($news['create_at']); ?>
				<span class="text-secondary" style="font-size: 16px;"> / <?= read_date($time); ?></span>
			</div>
			<hr>
			<div class="news_content w-100">
				<h5><?= $news['news_desc']; ?></h5>
			</div>

			<div class="news_img mb-3">
				<img src="image/<?= $news['news_img']; ?>" alt="">
			</div>

			<div class="news_content w-100">
				<?= $news['news_content']; ?>
			</div>
		</div>
	</div>
</main>
<?php require_once 'include/footer.php'; ?>