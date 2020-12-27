<?php
require_once 'common.php';
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
    ?>
    <div class="wrapper_register bg-faded p-5 d-flex justify-content-center">
        <!-- register box -->
        <div class="register_box card m-5 p-4 shadow">
            <h1 class="text-center">ĐĂNG KÝ TÀI KHOẢN</h1>
            <hr class="m-0 bg-success">
            <div class="card-body p-5 d-flex flex-column align-items-center">
                <!-- form register -->
                <form action="" method="POST" id="formSignUp">
                    <!-- name -->
                    <div class="form-group mb-3">
                        <label for="name"><i class="fas fa-user fa-lg"></i><span class="ml-3 field_name">Tên của bạn:</span></label>
                        <input type="text" id="name" name="name" class="form-control shadow" placeholder="tên">
                    </div>
                    <!-- dob -->
                    <div class="form-group mb-3">
                        <label for="dob"><i class="far fa-calendar-alt fa-lg"></i><span class="ml-3 field_name">Ngày sinh:</span></label>
                        <input type="date" id="dob" name="dob" class="form-control shadow" placeholder="Ngày sinh">
                    </div>
                    <!-- gender -->
                    <p class="my-3 d-inline-block"><i class="fas fa-mars-double fa-lg"></i></i><span class="ml-3 mr-5 field_name">Giới tính:</span></p>
                    <div class="custom-control custom-radio custom-control-inline mb-3">
                        <input type="radio" id="gender1" name="gender" class="custom-control-input" value="1">
                        <label for="gender1" class="custom-control-label">Nam</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline mb-3">
                        <input type="radio" id="gender2" name="gender" class="custom-control-input" value="0">
                        <label for="gender2" class="custom-control-label">Nữ</label>
                    </div>
                    <!-- email -->
                    <div class="form-group mb-3">
                        <label for="email"><i class="fas fa-envelope fa-lg"></i><span class="ml-3 field_name">Email:</span></label>
                        <input type="email" id="email" name="email" class="form-control shadow" placeholder="Email">
                    </div>
                    <!-- phone -->
                    <div class="form-group mb-3">
                        <label for="phone"><i class="fas fa-phone-square-alt fa-lg"></i><span class="ml-3 field_name">Điện thoại:</span></label>
                        <input type="text" id="phone" name="phone" class="form-control shadow" placeholder="số điện thoại">
                    </div>
                    <!-- password -->
                    <div class="form-group mb-3">
                        <label for="password"><i class="fas fa-lock fa-lg"></i><span class="ml-3 field_name">Mật khẩu:</span></label>
                        <input type="password" id="password" name="password" class="form-control shadow" placeholder="mật khẩu">
                    </div>
                    <!-- re-password -->
                    <div class="form-group mb-3">
                        <label for="re_pass"><i class="fas fa-lock fa-lg"></i><span class="ml-3 field_name">Xác nhận mật khẩu:</span></label>
                        <input type="password" id="re_pass" name="re_pass" class="form-control shadow" placeholder="xác nhận mật khẩu">
                    </div>
                    <!-- avatar -->
                    <div class="custom-file mb-3 mt-2">
                        <input type="file" id="avatar" name="avatar" class="custom-file-input shadow" multiple="">
                        <label for="avatar" class="custom-file-label field_name">Ảnh đại diện:</label>
                    </div>
                    <!-- show image selected -->
                    <div class="w-100" id="showImg">

                    </div>
                    <button class="btn btn-primary btn-block mb-3 shadow" id="btnSignUp" name="btnSignUp">ĐĂNG KÝ</button>

                    <script>
                        $(document).ready(function() {
                            $('#formSignUp').on('submit', function(e) {
                                e.preventDefault();
                                var data = $(this).serialize();
                                $.post(
                                    "process_sign_up.php",
                                    data,
                                    function(res) {
                                        console.log(res);
                                    }
                                );
                            });
                        });

                    </script>
                </form>
                <!-- /form register -->
                <p>Đã có tài khoản?<a href="<?= base_url('login.php'); ?>"> Đăng nhập</a></p>
            </div>
        </div>
        <!-- /register box -->
    </div>
    <?php
    require_once RF . '/include/footer.php';
    ?>
</body>
</html>