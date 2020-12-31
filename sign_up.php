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
                <form action="" method="POST" name="formSignUp">
                    <!-- NAME -->
                    <div class="form-group">
                        <div class="input-group flex-nowrap">
                            <div class="input-group-prepend">
                                <label for="name" class="input-group-text"><i class="fas fa-user fa-lg"></i></label>
                            </div>
                            <input type="text" id="name" name="name" class="form-control border-danger" placeholder="name">
                        </div>

                        <div id="nameError" class="alert-danger"></div>
                    </div>
                    <!-- DOB -->
                    <div class="form-group">
                        <div class="input-group flex-nowrap mb-3">
                            <div class="input-group-prepend">
                                <label for="dob" class="input-group-text"><i class="far fa-calendar-alt fa-lg"></i></label>
                            </div>
                            <input type="date" id="dob" name="dob" class="form-control" placeholder="date of birth">
                        </div>
                        <div id="dobError" class="alert-danger">hello</div>
                    </div>
                    <!-- GENDER -->
                    <div class="form-group">
                        <div class="custom-control custom-control-inline custom-radio mb-3 mr-5">
                            <input type="radio" id="nam" name="gender" class="custom-control-input">
                            <label for="nam" class="custom-control-label">Nam</label>
                        </div>
                        <div class="custom-control custom-control-inline custom-radio mb-3">
                            <input type="radio" id="nu" name="gender" class="custom-control-input">
                            <label for="nu" class="custom-control-label">Nữ</label>
                        </div>
                        <div id="genderError" class="alert-danger"></div>
                    </div>
                    <!-- EMAIL -->
                    <div class="form-group">
                        <div class="input-group flex-nowrap mb-3">
                            <div class="input-group-prepend">
                                <label for="email" class="input-group-text"><i class="fas fa-envelope fa-lg"></i></label>
                            </div>
                            <input type="email" id="email" name="email" class="form-control" placeholder="email">
                        </div>
                        <div id="emailError" class="alert-danger"></div>
                    </div>
                    <!-- PHONE -->
                    <div class="form-group">
                        <div class="input-group flex-nowrap mb-3">
                            <div class="input-group-prepend">
                                <label for="phone" class="input-group-text"><i class="fas fa-phone-alt fa-lg"></i></label>
                            </div>
                            <input type="text" id="phone" name="phone" class="form-control" placeholder="phone">
                        </div>
                        <div id="phoneError" class="alert-danger"></div>
                    </div>
                    <!-- PASSWORD-->
                    <div class="form-group">
                        <div class="input-group flex-nowrap mb-3">
                            <div class="input-group-prepend">
                                <label for="pass" class="input-group-text"><i class="fas fa-lock fa-lg"></i></label>
                            </div>
                            <input type="password" id="pass" name="pass" class="form-control" placeholder="password">
                        </div>
                        <div id="passError" class="alert-danger"></div>
                    </div>
                    <!-- CONFIRM PASSWORD -->
                    <div class="form-group">
                        <div class="input-group flex-nowrap mb-3">
                            <div class="input-group-prepend">
                                <label for="repass" class="input-group-text"><i class="fas fa-lock fa-lg"></i></label>
                            </div>
                            <input type="password" id="repass" name="repass" class="form-control" placeholder="confirm password">
                        </div>
                        <div id="rePassError" class="alert-danger"></div>
                    </div>

                    <!-- AVATAR -->
                    <div class="custom-file mb-3 mt-2">
                        <input type="file" id="avatar" name="avatar[]" class="custom-file-input shadow" multiple="">
                        <label for="avatar" class="custom-file-label field_name">Ảnh đại diện:</label>
                    </div>
                    <!-- show image selected -->
                    <div class="w-100" id="showImg"></div>

                    <button class="btn btn-primary btn-block mb-3 shadow" id="btnSignUp" name="btnSignUp">ĐĂNG KÝ</button>
                    <script>
                        $(document).ready(function() {
                            $('#formSignUp').on('submit', function(e) {
                                e.preventDefault();
                                // var file = $('#avatar')[0].files;
                                var data = new FormData();
                                $.each($('#avatar')[0].files, function(k, v) {
                                    data.append(k, v);
                                });

                                $.each($('#formSignUp').serializeArray(), function(k, obj) {
                                    data.append(obj.name, obj.value);
                                });
                                
                                $.ajax({
                                    url: "process_sign_up.php",
                                    type: "POST",
                                    data: data,
                                    dataType: "text",
                                    processData: false,
                                    contentType: false,
                                    cache: false,
                                    success: function(res) {
                                        alert(res);
                                    }
                                });
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