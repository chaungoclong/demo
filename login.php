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
        <div class="wrapper_login bg-faded p-5 d-flex justify-content-center">
            <!-- login box -->
            <div class="login_box card m-5 p-4 shadow">
                <h1 class="text-center">ĐĂNG NHẬP</h1>
                <hr class="m-0 bg-success">
                
                <div class="card-body p-5 d-flex flex-column align-items-center">
                    <!-- form login -->
                    <form action="" method="GET"class="form_validate" name="formLogin">
                        <!-- user -->
                        <div class="form-group mb-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <label for="user" class="input-group-text"><i class="fas fa-user fa-lg"></i></label>
                                </div>
                                <input type="text" id="user" name="user" class="form-control" placeholder="Email or Phone number">
                            </div>
                             <div class="alert-danger">hello</div>
                        </div>
                        

                        <!-- password -->
                        <div class="form-group mb-3">
                            <label for="password"><i class="fas fa-lock fa-lg"></i><span class="ml-3 field_name">Mật khẩu:</span></label>
                            <input type="password" id="password" name="password" class="form-control shadow" placeholder="mật khẩu">
                        </div>

                        <!-- remmember -->
                        <div class="custom-control custom-switch mb-3">
                            <input type="checkbox" id="remember" name="remember" class="custom-control-input">
                            <label for="remember" class="custom-control-label field_name">Nhớ mật khẩu</label>
                        </div>

                        <!-- button login -->
                        <button class="btn btn-primary btn-block mb-3 shadow">ĐĂNG NHẬP</button>

                    </form>
                    <!-- /form login -->
                    <p>Chưa có tài khoản?<a href="<?= base_url('sign_up.php'); ?>"> Đăng ký</a></p>
                </div>
            </div>
            <!-- /login box -->
        </div>
        <?php
        require_once RF . '/include/footer.php';
        ?>
    </body>
</html>