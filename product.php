<?php 
require_once 'common.php';
// $cat = $_GET['cat'] ?? "";
// $bra = $_GET['bra'] ?? "";
// $sql = "SELECT * FROM db_product WHERE pro_active = 1 ";

// if($cat) {
// 	$sql .= "AND cat_id = '{$cat}' ";
// }
// if($bra) {
// 	$sql .= "AND bra_id = '{$bra}' ";
// }

// $result = get_list($sql);
// print_r($result);

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
	require_once RF . '/include/home.php';
	require_once RF . '/include/footer.php';
	?>
</body>
</html>