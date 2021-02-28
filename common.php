<?php
//thư mục gốc
define('RF', realpath(__DIR__));

//thêm các file dùng chung
require_once RF . "/libs/session.php";
require_once RF . "/libs/database.php";
require_once RF . "/libs/function.php";
require_once RF . "/pusher/vendor/autoload.php";

//đặt múi giờ
date_default_timezone_set('Asia/Ho_Chi_Minh');

//khởi tạo session
start_session();
?>
