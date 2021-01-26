<?php
require_once 'common.php';
require_once RF . '/include/header.php';
require_once RF . '/include/navbar.php';
?>

<main>
	<div class="all_wrapper bg-success" style="padding: 0px 85px;">
		<div class="row m-0">

			<!-- hiển thị tin tức -->
			<div class="col-9">

				<?php 
					$listNews = getListNews();
				 ?>
				<div class="row m-0">
					
				</div>
			</div>

			<!-- hiển thị tin tức mói nhất -->
			<div class="col-3"></div>
		</div>
	</div>
</main>

<?php 
require_once RF . '/include/footer.php';
?>
