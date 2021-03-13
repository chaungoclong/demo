<?php
if(!empty($_POST['action']) && $_POST['action'] == "fetch") {
	require_once 'common.php';
	$news_left = $news_right = "";
	$param = [];
	$format = "";
	$getNewsSQL = "SELECT * FROM db_news WHERE news_active = 1";

	// tìm kiếm
	$q = !empty($_POST['q']) ? $_POST['q'] : "%%";
	$getNewsSQL .= " AND CONCAT(news_title, create_by) LIKE ?";
	$param[] = $q;
	$format .= "s";

	// sắp xếp
	$sort = !empty($_POST['sort']) ? (int)$_POST['sort'] : 1;
	switch ($sort) {
		case 1:
			$getNewsSQL .= " ORDER BY create_at DESC";
			break;
		case 2:
			$getNewsSQL .= " ORDER BY create_at ASC";
			break;
		case 3:
			$getNewsSQL .= " ORDER BY news_title ASC";
			break;
		case 4:
			$getNewsSQL .= " ORDER BY news_title DESC";
			break;
		default:
			$getNewsSQL .= " ORDER BY create_at DESC";
			break;
	}

	$listNews = db_get($getNewsSQL, 0, $param, $format);
	$totalNews = count($listNews);

	// chia trang
	$newsPerPage = 6;
	$totalPage = ceil($totalNews / $newsPerPage);
	$currentPage = !empty($_POST['currentPage']) ? int($_POST['currentPage']) : 1;
	$currentPage = $currentPage > $totalPage ? $totalPage : $currentPage;
	$offset = ($currentPage - 1) * $newsPerPage;

	$getNewsSQL .= " LIMIT ? OFFSET ?";
	$param = [...$param, $newsPerPage, $offset];
	$format .= "ii";
	$listNews = db_get($getNewsSQL, 0, $param, $format);

	// tin tức cột trái
	foreach ($listNews as $key => $news) {
		$time = strtotime($news['create_at']);
		$news_left .= '
		<div class="row m-0 mb-4 news_vertical" >
			<div class="col-4">
				<a href="news_detail.php?newsid=' . $news['news_id'] . '">
					<img src="image/' . $news['news_img'] . '" class="w-100">
				</a>
			</div>
			<div class="col-8">
				<h5>
				<a href="news_detail.php?newsid=' . $news['news_id'] . '" class="news_title">
					' . $news['news_title'] . '
				</a>
				</h5>
				<div class="news_desc mb-1">
					' . $news['news_desc'] . '
				</div>
				<h6 class="text-secondary">
				<span class="mr-2">' . $news['create_by'] . ' </span>
				<span>' . read_date($time) . '</span>
				</h6>
			</div>
		</div>';
	}

	// tin tức cột bên phải
	$getNewsSQL = "SELECT * FROM db_news WHERE news_active = 1 ORDER BY create_at DESC LIMIT 10";
	$listNews = db_get($getNewsSQL, 0);

	foreach ($listNews as $key => $news) {
		$time = strtotime($news['create_at']);

		$news_right .= '
		<div class="row" style="max-height: 80px;">
			<div class="row m-0 mb-4 news_vertical" >
				<div class="col-4">
					<a href="news_detail.php?newsid=' . $news['news_id'] . '">
						<img src="image/' . $news['news_img'] . '" class="w-100">
					</a>
				</div>
				<div class="col-8">
					<div>
						<a href="news_detail.php?newsid=' . $news['news_id'] . '" class="news_title" style="font-size: 17px;">
							' . $news['news_title'] . '
						</a>
					</div>
					<h6 class="text-secondary">
					<span class="mr-2">' . $news['create_by'] . ' </span>
					<span>' . read_date($time) . '</span>
					</h6>
				</div>
			</div>
		</div>
		<hr class="m-0 mb-3 p-0">';
	}

	$pagination = paginateAjax($totalPage, $currentPage);

	$output = ['news_left'=>$news_left, 'news_right'=>$news_right, 'pagination'=>$pagination];
	echo json_encode($output);
	
}