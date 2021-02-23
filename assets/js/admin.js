$(function() {

    // dropdown sidebar
    let dropdownMenu = $('.sidebar .dropdown');
    $(dropdownMenu).on('click', function(e) {
        $(this).find('.dropdown_menu').slideToggle(10);
    });

    $(document).on('click', function(e) {
        if (dropdownMenu != e.target && dropdownMenu.has(e.target).length == 0) {
            $('.dropdown_menu').slideUp(0);
        }
    });
});

// LOGIN ADMIN

// validate đăng nhập admin
function validateLogin() {
    var test = true;
    //xóa class lỗi 
    $('#user').removeClass("error_field");
    $('#pwdLogin').removeClass("error_field");

    //xóa thông báo lỗi
    $('#userErr').text("");
    $('#pwdLoginErr').text("");

    //lấy dữ liệu
    var user = $('#user').val().trim();
    var pwd = $('#pwdLogin').val().trim();

    //VALIDATE
    //user
    if (user == "") {
        $('#user').addClass("error_field");
        $('#userErr').text("Email / Phone is required");
        test = false;
    } else if (!isPhone(user) && !isEmail(user) && !checkWord(user)) {
        $('#user').addClass("error_field");
        $('#userErr').text("Email / Phone is wrong");
        test = false;
    }

    //password
    if (pwd == "") {
        $('#pwdLogin').addClass("error_field");
        $('#pwdLoginErr').text("password is required");
        test = false;
    } else if (!isPassword(pwd)) {
        $('#pwdLogin').addClass("error_field");
        $('#pwdLoginErr').text("password is wrong");
        test = false;
    }

    if (!test) {
        $('.error_field').first().focus();
    }

    return test;
}


// đăng nhập admin
function login() {
    if (validateLogin()) {
        var data = $('#loginForm').serialize();
        var sendLogin = $.ajax({
            url: 'process_login.php',
            method: "POST",
            data: data,
            dataType: "text"
        });

        sendLogin.done(function(res) {
            switch (res) {
                case "1":
                    $('#backErr').text("Thiếu dữ liệu");
                    break;
                case "2":
                    $('#backErr').text("Dữ liệu sai");
                    break;
                case "5":
                    window.location = "index.php";
                    break;
                case "6":
                    $('#backErr').text("Đăng nhập thất bại. Hãy thử lại");
                    break;
                case "8":
                    $('#backErr').text("Tài khoản của bạn bị khóa");
                    break;
            }
        });

        sendLogin.fail(function(a, b, c) {
            console.log(a, b, c);
        })
    }
}

// UPDATE MY ACCOUNT ADMIN

// validate cập nhật thông tin tài khoản của tôi  -admin
function validateUserUpdate() {
    let test = true;

    // xóa class lỗi 
    $('#uname').removeClass("error_field");
    $('#name').removeClass("error_field");
    $('#dob').removeClass("error_field");
    $('#gender').removeClass("error_field");
    $('#email').removeClass("error_field");
    $('#phone').removeClass("error_field");
    $('#avatar').removeClass("error_field");

    // xóa thông báo lỗi
    $('#unameErr').text("");
    $('#nameErr').text("");
    $('#dobErr').text("");
    $('#genderErr').text("");
    $('#emailErr').text("");
    $('#phoneErr').text("");
    $('#avatarErr').text("");

    //lấy dữ liệu
    let uname = $('#uname').val().trim();
    let name = $('#name').val().trim();
    let dob = $('#dob').val().trim();
    let gender = Object.values($('#user_info_update_form')[0]['gender']);
    let email = $('#email').val().trim();
    let phone = $('#phone').val().trim();
    let avatar = $('#avatar')[0].files[0];


    //VALIDATE

    //name
    if (uname == "") {
        $('#uname').addClass("error_field");
        $('#unameErr').text("user name is required");
        test = false;
    } else if (!isName(uname)) {
        $('#uname').addClass("error_field");
        $('#unameErr').text("user name is wrong");
        test = false;
    }

    //name
    if (name == "") {
        $('#name').addClass("error_field");
        $('#nameErr').text("name is required");
        test = false;
    } else if (!isName(name)) {
        $('#name').addClass("error_field");
        $('#nameErr').text("name is wrong");
        test = false;
    }

    //dob
    if (dob == "") {
        $('#dob').addClass("error_field");
        $('#dobErr').text("date is required");
        test = false;
    } else if (!isDate(formatDate(dob))) {
        $('#dob').addClass("error_field");
        $('#dobErr').text("date is wrong");
        test = false;
    }

    //gender
    var check = gender.some(val => val.checked);
    if (!check) {
        test = false;
        $('#gender').addClass("error_field");
        $('#genderErr').text("gender is required");
    }

    //email
    if (email == "") {
        $('#email').addClass("error_field");
        $('#emailErr').text("email is required");
        test = false;
    } else if (!isEmail(email)) {
        $('#email').addClass("error_field");
        $('#emailErr').text("email is wrong");
        test = false;
    }

    //phone
    if (phone == "") {
        $('#phone').addClass("error_field");
        $('#phoneErr').text("phone is required");
        test = false;
    } else if (!isPhone(phone)) {
        $('#phone').addClass("error_field");
        $('#phoneErr').text("phone is wrong");
        test = false;
    }

    //avatar
    if (avatar != undefined) {
        let imgName = avatar.name;
        let listExt = ["jpg", 'jpeg', 'png', 'gif'];
        let ext = imgName.split('.').pop().toLowerCase();
        let size = avatar.size;

        if (!listExt.some(val => val == ext)) {
            $('#avatar').addClass("error_field");
            $('#avatarErr').text("extention not match");
            test = false;
        } else if (size > 5000000) {
            $('#avatar').addClass("error_field");
            $('#avatarErr').text("size is very big");
            test = false;
        }
    }

    if (!test) {
        $('.error_field').first().focus();
    }
    return test;
}

// cập nhật thông tin tài khoản của tôi -admin
function userInfoUpdate() {
    if (validateUserUpdate()) {

        // biến lưu trữ dữ liệu gửi lên server
        let data = new FormData();

        // biến chứa thông tin lấy từ form
        let infoByForm = $('#user_info_update_form').serializeArray();

        // thêm thông tin từ form vào biến chứa dữ liệu
        $.each(infoByForm, function(k, obj) {
            data.append(obj.name, obj.value);
        });

        // biến ảnh
        let avatar = $('#avatar')[0].files[0];

        // kiểm tra ảnh có trống
        if (avatar != undefined) {
            data.append("avatar", avatar);
        }

        // biến gửi ajax
        let sendUpdate = $.ajax({
            url: "process_profile.php",
            type: "POST",
            data: data,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
        });

        sendUpdate.done(function(res) {
            let status = res.status;
            let data = res.info;
            console.log(res);
            console.log(status, data);

            switch (status) {
                case 1:
                    $('#backErr').text('Thiếu dữ liệu');
                    break;
                case 2:
                    $('#backErr').text('Dữ liệu sai');
                    break;
                case 3:
                    $('#backErr').text('Email đã tồn tại');
                    break;
                case 4:
                    $('#backErr').text('Số điện thoại đã tồn tại');
                    break;
                case 5:
                    // $('#backErr').text('Cập nhật thành công');
                    // $('#name').text(data.name);
                    alert('CẬP NHẬT THÀNH CÔNG');
                    $('[class*="acc_img"]').prop('src', "../image/" + data.avatar);
                    $('[class*="acc_name"]').text(data.name);

                    break;
                case 6:
                    $('#backErr').text('Đã xảy ra lỗi. Vui lòng thử lại');
                    break;
            }
        });
    }
}


// UPDATE PASSWORD MY ACCOUNT ADMIN

// validate cập nhật tài khoản của tôi -admin
function validateUpdatePassword() {
    let test = true;

    //xóa các class lỗi
    $('#oldPwd, #newPwd').removeClass('error_field');

    //xóa các thông báo lỗi
    $('#oldPwdErr, #newPwdErr').text("");

    //lấy dữ liệu
    let oldPwd = $('#oldPwd').val().trim();
    let newPwd = $('#newPwd').val().trim();

    //validate
    if (oldPwd == "") {
        $('#oldPwd').addClass('error_field');
        $('#oldPwdErr').text("required");
        test = false;
    } else if (!isPassword(oldPwd)) {
        $('#oldPwd').addClass('error_field');
        $('#oldPwdErr').text("password is wrong");
        test = false;
    }

    if (newPwd == "") {
        $('#newPwd').addClass('error_field');
        $('#newPwdErr').text("required");
        test = false;
    } else if (!isPassword(newPwd)) {
        $('#newPwd').addClass('error_field');
        $('#newPwdErr').text("password is wrong");
        test = false;
    }

    if (!test) {
        $('.error_field').first().focus();
    }

    return test;
}


// cập nhật mật khẩu tài khoản của tôi -admin
function userPasswordUpdate() {
    if (validateUpdatePassword()) {
        let data = $('#change_pwd_form').serialize();
        let updatePwd = sendAJax(
            "process_password.php",
            "post",
            "text",
            data
        );

        switch (updatePwd) {
            case "1":
                $('#backErr').text("Thiếu dữ liệu");
                break;
            case "2":
                $('#backErr').text("Dữ liệu sai");
                break;
            case "3":
                $('#backErr').text("Mật khẩu cũ không đúng");
                break;
            case "5":
                $('#backErr').text("Đổi mật khẩu thành công");
                $('#oldPwd, #newPwd').val("");
                break;
            case "6":
                $('#backErr').text("Có lỗi xảy ra. Vui lòng thử lại");
                break;
        }
    }
}


// VALIDATE EDIT USER 
function validateEditCustomer() {
    let test = true;

    // xóa class lỗi 
    $('#address').removeClass("error_field");
    $('#name').removeClass("error_field");
    $('#dob').removeClass("error_field");
    $('#gender').removeClass("error_field");
    $('#email').removeClass("error_field");
    $('#phone').removeClass("error_field");
    $('#avatar').removeClass("error_field");

    // xóa thông báo lỗi
    $('#addressErr').text("");
    $('#nameErr').text("");
    $('#dobErr').text("");
    $('#genderErr').text("");
    $('#emailErr').text("");
    $('#phoneErr').text("");
    $('#avatarErr').text("");

    //lấy dữ liệu
    let address = $('#address').val().trim();
    let name    = $('#name').val().trim();
    let dob     = $('#dob').val().trim();
    let gender  = Object.values($('#cus_info_edit_form')[0]['gender']);
    let email   = $('#email').val().trim();
    let phone   = $('#phone').val().trim();
    let avatar  = $('#avatar')[0].files[0];


    //VALIDATE

    //address
    if (address == "") {
        $('#address').addClass("error_field");
        $('#addressErr').text("address is required");
        test = false;
    }

    //name
    if (name == "") {
        $('#name').addClass("error_field");
        $('#nameErr').text("name is required");
        test = false;
    } else if (!isName(name)) {
        $('#name').addClass("error_field");
        $('#nameErr').text("name is wrong");
        test = false;
    }

    //dob
    if (dob == "") {
        $('#dob').addClass("error_field");
        $('#dobErr').text("date is required");
        test = false;
    } else if (!isDate(formatDate(dob))) {
        $('#dob').addClass("error_field");
        $('#dobErr').text("date is wrong");
        test = false;
    }

    //gender
    var check = gender.some(val => val.checked);
    if (!check) {
        test = false;
        $('#gender').addClass("error_field");
        $('#genderErr').text("gender is required");
    }

    //email
    if (email == "") {
        $('#email').addClass("error_field");
        $('#emailErr').text("email is required");
        test = false;
    } else if (!isEmail(email)) {
        $('#email').addClass("error_field");
        $('#emailErr').text("email is wrong");
        test = false;
    }

    //phone
    if (phone == "") {
        $('#phone').addClass("error_field");
        $('#phoneErr').text("phone is required");
        test = false;
    } else if (!isPhone(phone)) {
        $('#phone').addClass("error_field");
        $('#phoneErr').text("phone is wrong");
        test = false;
    }

    //avatar
    if (avatar != undefined) {
        let imgName = avatar.name;
        let listExt = ["jpg", 'jpeg', 'png', 'gif'];
        let ext = imgName.split('.').pop().toLowerCase();
        let size = avatar.size;

        if (!listExt.some(val => val == ext)) {
            $('#avatar').addClass("error_field");
            $('#avatarErr').text("extention not match");
            test = false;
        } else if (size > 5000000) {
            $('#avatar').addClass("error_field");
            $('#avatarErr').text("size is very big");
            test = false;
        }
    }

    if (!test) {
        $('.error_field').first().focus();
    }
    return test;
}

// hàm chỉnh sửa thông tin người dùng
function editCustomerInfo() {
    if (validateEditCustomer()) {

        // biến lưu trữ dữ liệu gửi lên server
        let data = new FormData();

        // biến chứa thông tin lấy từ form
        let infoByForm = $('#cus_info_edit_form').serializeArray();

        // thêm thông tin từ form vào biến chứa dữ liệu
        $.each(infoByForm, function(k, obj) {
            data.append(obj.name, obj.value);
        });

        // biến ảnh
        let avatar = $('#avatar')[0].files[0];

        // kiểm tra ảnh có trống
        if (avatar != undefined) {
            data.append("avatar", avatar);
        }

        data.append('action', "edit");

        let result = sendAJax("process_customer.php", "post", "json", data, 1);
        let status = result.status;
        let error = result.error;
        let msg = "";

        if(status == "success") {
            msg += "CẬP NHẬT THÀNH CÔNG";

            if(error.length) {
                msg += "\n" + error.join("\n");
            }
            alert(msg);
            window.location = "index.php";
        } else {
            msg += "CẬP NHẬT THẤT BẠI";

            if(error.length) {
                msg += "\n" + error.join("\n");
            }
            alert(msg);
        }
    }
}

// VALIDATE USER MODULE
// validate chỉnh sửa thông tin nhân viên  -user
function validateUserEdit() {
    let test = true;

    // xóa class lỗi 
    $('#uname').removeClass("error_field");
    $('#name').removeClass("error_field");
    $('#dob').removeClass("error_field");
    $('#gender').removeClass("error_field");
    $('#email').removeClass("error_field");
    $('#phone').removeClass("error_field");
    $('#avatar').removeClass("error_field");

    // xóa thông báo lỗi
    $('#unameErr').text("");
    $('#nameErr').text("");
    $('#dobErr').text("");
    $('#genderErr').text("");
    $('#emailErr').text("");
    $('#phoneErr').text("");
    $('#avatarErr').text("");

    //lấy dữ liệu
    let uname  = $('#uname').val().trim();
    let name   = $('#name').val().trim();
    let dob    = $('#dob').val().trim();
    let gender = Object.values($('#user_info_edit_form')[0]['gender']);
    let email  = $('#email').val().trim();
    let phone  = $('#phone').val().trim();
    let avatar = $('#avatar')[0].files[0];

    //VALIDATE

    //name
    if (uname == "") {
        $('#uname').addClass("error_field");
        $('#unameErr').text("user name is required");
        test = false;
    } else if (!isUname(uname)) {
        $('#uname').addClass("error_field");
        $('#unameErr').text("user name is wrong");
        test = false;
    }

    //name
    if (name == "") {
        $('#name').addClass("error_field");
        $('#nameErr').text("name is required");
        test = false;
    } else if (!isName(name)) {
        $('#name').addClass("error_field");
        $('#nameErr').text("name is wrong");
        test = false;
    }

    //dob
    if (dob == "") {
        $('#dob').addClass("error_field");
        $('#dobErr').text("date is required");
        test = false;
    } else if (!isDate(formatDate(dob))) {
        $('#dob').addClass("error_field");
        $('#dobErr').text("date is wrong");
        test = false;
    }

    //gender
    var check = gender.some(val => val.checked);
    if (!check) {
        test = false;
        $('#gender').addClass("error_field");
        $('#genderErr').text("gender is required");
    }

    //email
    if (email == "") {
        $('#email').addClass("error_field");
        $('#emailErr').text("email is required");
        test = false;
    } else if (!isEmail(email)) {
        $('#email').addClass("error_field");
        $('#emailErr').text("email is wrong");
        test = false;
    }

    //phone
    if (phone == "") {
        $('#phone').addClass("error_field");
        $('#phoneErr').text("phone is required");
        test = false;
    } else if (!isPhone(phone)) {
        $('#phone').addClass("error_field");
        $('#phoneErr').text("phone is wrong");
        test = false;
    }

    //avatar
    if (avatar != undefined) {
        let imgName = avatar.name;
        let listExt = ["jpg", 'jpeg', 'png', 'gif'];
        let ext = imgName.split('.').pop().toLowerCase();
        let size = avatar.size;

        if (!listExt.some(val => val == ext)) {
            $('#avatar').addClass("error_field");
            $('#avatarErr').text("extention not match");
            test = false;
        } else if (size > 5000000) {
            $('#avatar').addClass("error_field");
            $('#avatarErr').text("size is very big");
            test = false;
        }
    }

    if (!test) {
        $('.error_field').first().focus();
    }
    return test;
}

function editUserInfo() {
    if (validateUserEdit()) {

        // biến lưu trữ dữ liệu gửi lên server
        let data = new FormData();

        // biến chứa thông tin lấy từ form
        let infoByForm = $('#user_info_edit_form').serializeArray();

        // thêm thông tin từ form vào biến chứa dữ liệu
        $.each(infoByForm, function(k, obj) {
            data.append(obj.name, obj.value);
        });

        // biến ảnh
        let avatar = $('#avatar')[0].files[0];

        // kiểm tra ảnh có trống
        if (avatar != undefined) {
            data.append("avatar", avatar);
        }

        data.append('action', "edit");
        // biến gửi ajax
        let result= sendAJax('process_user.php', 'post', 'json', data, 1);

        let status = result.status;
        let error = result.error;
        let msg = "";

        if(status == "success") {
            msg = "CẬP NHẬT THÀNH CÔNG";
            alert(msg);
            window.location = "index.php";
        } else {
            msg = "CẬP NHẬT THẤT BẠI";
            if(error.length) {
                msg += "\n" + error.join("\n");
            }
            alert(msg);
        }
    }
}


// validate thêm người dùng -user
function validateUserAdd() {
    let test = true;

    // xóa class lỗi 
    $('#uname').removeClass("error_field");
    $('#name').removeClass("error_field");
    $('#dob').removeClass("error_field");
    $('#gender').removeClass("error_field");
    $('#email').removeClass("error_field");
    $('#phone').removeClass("error_field");
    $('#avatar').removeClass("error_field");
    $('#pwdRegister').removeClass("error_field");
    $('#rePwdRegister').removeClass("error_field");


    // xóa thông báo lỗi
    $('#unameErr').text("");
    $('#nameErr').text("");
    $('#dobErr').text("");
    $('#genderErr').text("");
    $('#emailErr').text("");
    $('#phoneErr').text("");
    $('#avatarErr').text("");
    $('#pwdRegisterErr').text("");
    $('#rePwdRegisterErr').text("");

    //lấy dữ liệu
    let uname = $('#uname').val().trim();
    let name = $('#name').val().trim();
    let dob = $('#dob').val().trim();
    let gender = Object.values($('#user_add_form')[0]['gender']);
    let email = $('#email').val().trim();
    let phone = $('#phone').val().trim();
    let avatar = $('#avatar')[0].files[0];
    let pwd = $('#pwdRegister').val().trim();
    let rePwd = $('#rePwdRegister').val().trim();

    //VALIDATE

    //name
    if (uname == "") {
        $('#uname').addClass("error_field");
        $('#unameErr').text("user name is required");
        test = false;
    } else if (!isUname(uname)) {
        $('#uname').addClass("error_field");
        $('#unameErr').text("user name is wrong");
        test = false;
    }

    //name
    if (name == "") {
        $('#name').addClass("error_field");
        $('#nameErr').text("name is required");
        test = false;
    } else if (!isName(name)) {
        $('#name').addClass("error_field");
        $('#nameErr').text("name is wrong");
        test = false;
    }

    //dob
    if (dob == "") {
        $('#dob').addClass("error_field");
        $('#dobErr').text("date is required");
        test = false;
    } else if (!isDate(formatDate(dob))) {
        $('#dob').addClass("error_field");
        $('#dobErr').text("date is wrong");
        test = false;
    }

    //gender
    var check = gender.some(val => val.checked);
    if (!check) {
        test = false;
        $('#gender').addClass("error_field");
        $('#genderErr').text("gender is required");
    }

    //email
    if (email == "") {
        $('#email').addClass("error_field");
        $('#emailErr').text("email is required");
        test = false;
    } else if (!isEmail(email)) {
        $('#email').addClass("error_field");
        $('#emailErr').text("email is wrong");
        test = false;
    }

    //phone
    if (phone == "") {
        $('#phone').addClass("error_field");
        $('#phoneErr').text("phone is required");
        test = false;
    } else if (!isPhone(phone)) {
        $('#phone').addClass("error_field");
        $('#phoneErr').text("phone is wrong");
        test = false;
    }

    //password
    if (pwd == "") {
        $('#pwdRegister').addClass("error_field");
        $('#pwdRegisterErr').text("password is required");
        test = false;
    } else if (!isPassword(pwd)) {
        $('#pwdRegister').addClass("error_field");
        $('#pwdRegisterErr').text("password is wrong");
        test = false;
    }

    //repassword
    if (rePwd != pwd) {
        $('#rePwdRegister').addClass("error_field");
        $('#rePwdRegisterErr').text("password not match");
        test = false;
    }

    //avatar
    if (avatar != undefined) {
        let imgName = avatar.name;
        let listExt = ["jpg", 'jpeg', 'png', 'gif'];
        let ext = imgName.split('.').pop().toLowerCase();
        let size = avatar.size;

        if (!listExt.some(val => val == ext)) {
            $('#avatar').addClass("error_field");
            $('#avatarErr').text("extention not match");
            test = false;
        } else if (size > 5000000) {
            $('#avatar').addClass("error_field");
            $('#avatarErr').text("size is very big");
            test = false;
        }
    }

    if (!test) {
        $('.error_field').first().focus();
    }
    return test;
}

function addUser() {
    if (validateUserAdd()) {

        // biến lưu trữ dữ liệu gửi lên server
        let data = new FormData();

        // biến chứa thông tin lấy từ form
        let infoByForm = $('#user_add_form').serializeArray();

        // thêm thông tin từ form vào biến chứa dữ liệu
        $.each(infoByForm, function(k, obj) {
            data.append(obj.name, obj.value);
        });

        // biến ảnh
        let avatar = $('#avatar')[0].files[0];

        // kiểm tra ảnh có trống
        if (avatar != undefined) {
            data.append("avatar", avatar);
        }

        data.append('action', "add");
        // biến gửi ajax
        let result = sendAJax('process_user.php', 'post', 'json', data, 1);

        let status = result.status;
        let error = result.error;
        let msg = "";

        if(status == "success") {
            msg = "THÊM THÀNH CÔNG";
            if(error.length) {
                msg += "\n" + error.join("\n");
            }
            alert(msg);
            window.location = "index.php";
        } else {
            msg = "THÊM THẤT BẠI";
            if(error.length) {
                msg += "\n" + error.join("\n");
            }
            alert(msg);
        }
    }
}

// PRODUCT MODULE

function validateProductAdd() {

    let test = true;
    let limitImg = 10;

    // xóa class lỗi
    $('#name').removeClass("error_field");
    $('#category').removeClass("error_field");
    $('#brand').removeClass("error_field");
    $('#price').removeClass("error_field");
    $('#quantity').removeClass("error_field");
    $('#color').removeClass("error_field");
    $('#short_desc').removeClass("error_field");
    $('#desc').removeClass("error_field");
    $('#detail').removeClass("error_field");

    // xóa thông báo lỗi
    $('#nameErr').text("");
    $('#categoryErr').text("");
    $('#brandErr').text("");
    $('#priceErr').text("");
    $('#quantityErr').text("");
    $('#colorErr').text("");
    $('#descErr').text("");
    $('#detailErr').text("");
    $('#imageErr').text("");
    $('#libraryErr').text("");

    // lấy giá trị
    let name = $('#name').val().trim();
    let category = $('#category').val();
    let brand = $('#brand').val();
    let price = $('#price').val().trim();
    let quantity = $('#quantity').val().trim();
    let color = $('#color').val().trim();
    let desc = $('#desc').val().trim();
    let detail = $('#detail').val().trim();
    let image = $('#image')[0].files[0];
    let library = $('#library')[0].files;

    // validate
    // name
    if (name == "") {
        $('#name').addClass("error_field");
        $('#nameErr').text('không được để trống');
        test = false;
    } else if(!checkWord(name)) {
        $('#name').addClass("error_field");
        $('#nameErr').text('Tên sai định dạng');
        test = false;
    }

    // category
    if (!category) {
        $('#category').addClass("error_field");
        $('#categoryErr').text('không được để trống');
        test = false;
    }

    // brand
    if (!brand) {
        $('#brand').addClass("error_field");
        $('#brandErr').text('không được để trống');
        test = false;
    }

    // price
    if (!price) {
        $('#price').addClass("error_field");
        $('#priceErr').text('không được để trống');
        test = false;
    } else if (parseInt(price) <= 0 || isNaN(parseInt(price))) {
        $('#price').addClass("error_field");
        $('#priceErr').text('giá không hợp lệ');
        test = false;
    }

    // quantity
    if (!quantity) {
        $('#quantity').addClass("error_field");
        $('#quantityErr').text('không được để trống');
        test = false;
    } else if (parseInt(quantity) <= 0 || isNaN(parseInt(quantity))) {
        $('#quantity').addClass("error_field");
        $('#quantityErr').text('số lượng không hợp lệ');
        test = false;
    }

    // color
    if (color == "") {
        $('#color').addClass("error_field");
        $('#colorErr').text('không được để trống');
        test = false;
    } else if(!checkName(color)) {
        $('#color').addClass("error_field");
        $('#colorErr').text('Màu sắc không hợp lệ');
        test = false;
    }

    // desc
    if (desc == "") {
        $('#desc').addClass("error_field");
        $('#descErr').text('không được để trống');
        test = false;
    }

    // detail
    if (detail == "") {
        $('#detail').addClass("error_field");
        $('#detailErr').text('không được để trống');
        test = false;
    }

    // image
    if (image == undefined) {
        $('#imageErr').text('không được để trống');
        test = false;
    } else {
        let imgName = image.name;
        let listExt = ["jpg", 'jpeg', 'png'];
        let ext = imgName.split('.').pop().toLowerCase();
        let size = imgName.size;

        if (!listExt.some(val => val == ext)) {
            $('#imageErr').text("file không hợp lệ");
            test = false;
        } else if (size > 5000000) {
            $('#imageErr').text("kích cỡ file quá lớn");
            test = false;
        }
    }

    if (library != undefined) {
        let errorLibrary = "";
        let qtyFile = library.length;

        if (qtyFile > limitImg) {
            errorLibrary += "số lượng file nhiều hơn giới hạn cho phép";
        } else {
            $.each(library, function(k, v) {
                let imgName = v.name;
                let listExt = ["jpg", 'jpeg', 'png'];
                let ext = imgName.split('.').pop().toLowerCase();
                let size = v.size;

                if (!listExt.some(val => val == ext)) {
                    errorLibrary += imgName + ": file không hợp lệ| ";
                } else if (size > 5000000) {
                    errorLibrary += imgName + ": file quá lớn| ";
                }
            });
        }

        if (errorLibrary.length > 0) {
            test = false;
            $('#libraryErr').html(errorLibrary);
        }
    }

    if (!test) {
        $('.error_field').first().focus();
    }

    return test;
}


function addProduct() {
    if (validateProductAdd()) {
        // biến chứa dữ liệu gửi lên server
        let form_data = new FormData();

        // thông tin lấy từ form 
        let infoByForm = $('#product_add_form').serializeArray();
        $.each(infoByForm, function(k, obj) {
            form_data.append(obj.name, obj.value);
        });

        // biến chứa ảnh đại diện
        let image = $('#image')[0].files[0];
        if (image != undefined) {
            form_data.append("image", image);
        }

        // biến chứa các ảnh chi tiết
        let library = $('#library')[0].files;
        if (library != undefined) {
            $.each(library, function(k, v) {
                form_data.append("library[]", v);
            });
        }

        form_data.append("action", "add");

        let result = sendAJax("process_product.php", "post", "json", form_data, 1);

        // lấy kết quả trả về
       let status = result.status;
       let error = result.error;
       let msg = "";

        if(status == "success") {
            msg = "THÊM SẢN PHẨM THÀNH CÔNG";
            if(error['library'].length) {
                msg += "\nCHÚ Ý:\n" + error['library'].join(", ");
            }
            alert(msg);
            window.location = "index.php";
        } else {
            alert("THÊM SẢN PHẨM KHÔNG THÀNH CÔNG");

            if(error['name']) {
                $('#name').addClass("error_field");
            }
            $('#nameErr').text(error['name']);
        }
    }
}


function validateProductEdit() {

    let test = true;
    let limitImg = 10;

    // xóa class lỗi
    $('#name').removeClass("error_field");
    $('#category').removeClass("error_field");
    $('#brand').removeClass("error_field");
    $('#price').removeClass("error_field");
    $('#quantity').removeClass("error_field");
    $('#color').removeClass("error_field");
    $('#desc').removeClass("error_field");
    $('#detail').removeClass("error_field");

    // xóa thông báo lỗi
    $('#nameErr').text("");
    $('#categoryErr').text("");
    $('#brandErr').text("");
    $('#priceErr').text("");
    $('#quantityErr').text("");
    $('#colorErr').text("");
    $('#shortDescErr').text("");
    $('#descErr').text("");
    $('#detailErr').text("");
    $('#imageErr').text("");
    $('#libraryErr').text("");

    // lấy giá trị
    let name = $('#name').val().trim();
    let category = $('#category').val();
    let brand = $('#brand').val();
    let price = $('#price').val().trim();
    let quantity = $('#quantity').val().trim();
    let color = $('#color').val().trim();
    let desc = $('#desc').val().trim();
    let detail = $('#detail').val().trim();
    let image = $('#image')[0].files[0];
    let library = $('#library')[0].files;

    // validate
    // name
    if (name == "") {
        $('#name').addClass("error_field");
        $('#nameErr').text('không được để trống');
        test = false;
    } else if(!checkWord(name)) {
        $('#name').addClass("error_field");
        $('#nameErr').text('Sai định dạng');
        test = false;
    }

    // category
    if (!category) {
        $('#category').addClass("error_field");
        $('#categoryErr').text('không được để trống');
        test = false;
    }

    // brand
    if (!brand) {
        $('#brand').addClass("error_field");
        $('#brandErr').text('không được để trống');
        test = false;
    }

    // price
    if (!price) {
        $('#price').addClass("error_field");
        $('#priceErr').text('không được để trống');
        test = false;
    } else if (parseInt(price) <= 0 || isNaN(parseInt(price))) {
        $('#price').addClass("error_field");
        $('#priceErr').text('giá không hợp lệ');
        test = false;
    }

    // quantity
    if (!quantity) {
        $('#quantity').addClass("error_field");
        $('#quantityErr').text('không được để trống');
        test = false;
    } else if (parseInt(quantity) <= 0 || isNaN(parseInt(quantity))) {
        $('#quantity').addClass("error_field");
        $('#quantityErr').text('số lượng không hợp lệ');
        test = false;
    }

    // color
    if (color == "") {
        $('#color').addClass("error_field");
        $('#colorErr').text('không được để trống');
        test = false;
    } else if(!checkName(color)) {
        $('#color').addClass("error_field");
        $('#colorErr').text('Sai định dạng');
        test = false;
    }

    // desc
    if (desc == "") {
        $('#desc').addClass("error_field");
        $('#descErr').text('không được để trống');
        test = false;
    }

    // detail
    if (detail == "") {
        $('#detail').addClass("error_field");
        $('#detailErr').text('không được để trống');
        test = false;
    }

    // image
    if (image != undefined) {
        let imgName = image.name;
        let listExt = ["jpg", 'jpeg', 'png'];
        let ext = imgName.split('.').pop().toLowerCase();
        let size = imgName.size;

        if (!listExt.some(val => val == ext)) {
            $('#imageErr').text("file không hợp lệ");
            test = false;
        } else if (size > 5000000) {
            $('#imageErr').text("kích cỡ file quá lớn");
            test = false;
        }
    }

    if (library != undefined) {
        let errorLibrary = "";
        let qtyFile = library.length;

        if (qtyFile > limitImg) {
            errorLibrary += "số lượng file nhiều hơn giới hạn cho phép";
        } else {
            $.each(library, function(k, v) {
                let imgName = v.name;
                let listExt = ["jpg", 'jpeg', 'png'];
                let ext = imgName.split('.').pop().toLowerCase();
                let size = v.size;

                if (!listExt.some(val => val == ext)) {
                    errorLibrary += imgName + ": file không hợp lệ| ";
                } else if (size > 5000000) {
                    errorLibrary += imgName + ": file quá lớn| ";
                }
            });
        }

        if (errorLibrary.length > 0) {
            test = false;
            $('#libraryErr').html(errorLibrary);
        }
    }

    if (!test) {
        $('.error_field').first().focus();
    }

    return test;
}


function editProduct() {
    if (validateProductEdit()) {
        // biến chứa dữ liệu gửi lên server
        let form_data = new FormData();

        // thông tin lấy từ form 
        let infoByForm = $('#product_edit_form').serializeArray();
        $.each(infoByForm, function(k, obj) {
            form_data.append(obj.name, obj.value);
        });

        // biến chứa ảnh đại diện
        let image = $('#image')[0].files[0];
        if (image != undefined) {
            form_data.append("image", image);
        }

        // biến chứa các ảnh chi tiết
        let library = $('#library')[0].files;
        if (library != undefined) {
            $.each(library, function(k, v) {
                form_data.append("library[]", v);
            });
        }

        form_data.append("action", "edit");

        let result = sendAJax("process_product.php", "post", "json", form_data, 1);

        // lấy kết quả trả về
        let error = result.error;
        let status = result.status;
        let msg = "";

        if(status == "success") {
            msg = "CẬP NHẬT SẢN PHẨM THÀNH CÔNG";

            if(error['library'].length) {
                msg += "\n CHÚ Ý:n" + error['library'].join(", ");
            }
            alert(msg);
            window.location = "index.php";
        } else {
            alert("CẬP NHẬT SẢN PHẨM THẤT BẠI");
            
            if(error['name']) {
                $('#name').addClass('error_field');
            }
            $('#nameErr').text(error['name']);
        }
    }
}

function validateCategoryAdd() {

    let test = true;

    // xóa class lỗi
    $('#name').removeClass("error_field");

    // xóa thông báo lỗi
    $('#nameErr').text("");

    // lấy giá trị
    let name = $('#name').val().trim();
    let image = $('#image')[0].files[0];

    // validate
    // name
    if (name == "") {
        $('#name').addClass("error_field");
        $('#nameErr').text('không được để trống');
        test = false;
    }

    // image
    if (image == undefined) {
        $('#imageErr').text('không được để trống');
        test = false;
    } else {
        let imgName = image.name;
        let listExt = ["jpg", 'jpeg', 'png'];
        let ext = imgName.split('.').pop().toLowerCase();
        let size = image.size;

        if (!listExt.some(val => val == ext)) {
            $('#imageErr').text("file không hợp lệ");
            test = false;
        } else if (size > 5000000) {
            $('#imageErr').text("kích cỡ file quá lớn");
            test = false;
        }
    }

    if (!test) {
        $('.error_field').first().focus();
    }

    return test;
}

function addCategory() {
    if (validateCategoryAdd()) {

        let form_data = new FormData();

        let infoByForm = $("#category_add_form").serializeArray();
        $.each(infoByForm, function(k, obj) {
            form_data.append(obj.name, obj.value);
        });

        let image = $('#image')[0].files[0];
        if (image != undefined) {
            form_data.append("image", image);
        }

        form_data.append("action", "add");

        let result = sendAJax(
            "process_category.php",
            "post",
            "json",
            form_data,
            1
        );

        let status = result.status;
        let error = result.error;

        if(status == "success") {
            alert("THÊM DANH MỤC THÀNH CÔNG");
            window.location = "index.php";
        } else {
            alert("THÊM DANH MỤC THẤT BẠI");

            if(error['name'] != "") {
               $('#name').addClass("error_field");
            }
            $('#nameErr').text(error['name']);

            $('#imageErr').text(error['file']);
        }
    }
}

function validateCategoryEdit() {

    let test = true;

    // xóa class lỗi
    $('#name').removeClass("error_field");

    // xóa thông báo lỗi
    $('#nameErr').text("");

    // lấy giá trị
    let name = $('#name').val().trim();
    let image = $('#image')[0].files[0];

    // validate
    // name
    if (name == "") {
        $('#name').addClass("error_field");
        $('#nameErr').text('không được để trống');
        test = false;
    }

    // image
    if (image != undefined) {
        let imgName = image.name;
        let listExt = ["jpg", 'jpeg', 'png'];
        let ext = imgName.split('.').pop().toLowerCase();
        let size = image.size;

        if (!listExt.some(val => val == ext)) {
            $('#imageErr').text("file không hợp lệ");
            test = false;
        } else if (size > 5000000) {
            $('#imageErr').text("kích cỡ file quá lớn");
            test = false;
        }
    }

    if (!test) {
        $('.error_field').first().focus();
    }

    return test;
}

function editCategory() {
    if (validateCategoryEdit()) {

        let form_data = new FormData();

        let infoByForm = $("#category_edit_form").serializeArray();
        $.each(infoByForm, function(k, obj) {
            form_data.append(obj.name, obj.value);
        });

        let image = $('#image')[0].files[0];
        if (image != undefined) {
            form_data.append("image", image);
        }

        form_data.append("action", "edit");

        let result = sendAJax(
            "process_category.php",
            "post",
            "json",
            form_data,
            1
        );

        let status = result.status;
        let error = result.error;

        if(status == 'success') {
            alert("CẬP NHẬT THÀNH CÔNG");
            window.location = "index.php";
          
        } else {
            alert("CẬP NHẬT THẤT BẠI");

            if(error['name'] != "") {
                $('#name').addClass('error_field');
            }
            $('#nameErr').text(error['name']);

            $('#imageErr').text(error['file']);
        }
    }
}

// ======== BRAND MODULE ========================================
function validateBrandAdd() {

    let test = true;

    // xóa class lỗi
    $('#name').removeClass("error_field");

    // xóa thông báo lỗi
    $('#nameErr').text("");

    // lấy giá trị
    let name = $('#name').val().trim();
    let image = $('#image')[0].files[0];

    // validate
    // name
    if (name == "") {
        $('#name').addClass("error_field");
        $('#nameErr').text('không được để trống');
        test = false;
    }

    // image
    if (image == undefined) {
        $('#imageErr').text('không được để trống');
        test = false;
    } else {
        let imgName = image.name;
        let listExt = ["jpg", 'jpeg', 'png'];
        let ext = imgName.split('.').pop().toLowerCase();
        let size = image.size;

        if (!listExt.some(val => val == ext)) {
            $('#imageErr').text("file không hợp lệ");
            test = false;
        } else if (size > 5000000) {
            $('#imageErr').text("kích cỡ file quá lớn");
            test = false;
        }
    }

    if (!test) {
        $('.error_field').first().focus();
    }

    return test;
}

function addBrand() {
    if (validateBrandAdd()) {

        let form_data = new FormData();

        let infoByForm = $("#brand_add_form").serializeArray();
        $.each(infoByForm, function(k, obj) {
            form_data.append(obj.name, obj.value);
        });

        let image = $('#image')[0].files[0];
        if (image != undefined) {
            form_data.append("image", image);
        }

        form_data.append("action", "add");

        let result = sendAJax(
            "process_brand.php",
            "post",
            "json",
            form_data,
            1
        );

        let status = result.status;
        let error = result.error;

        if(status == "success") {
            alert("THÊM HÃNG THÀNH CÔNG");
            window.location = "index.php";
        } else {
             alert("THÊM HÃNG THẤT BẠI");

             if(error['name'] != "") {
                $('#name').addClass('error_field');
             }

             $('#nameErr').text(error['name']);
             $('#imageErr').text(error['file']);
        }
    }
}

function validateBrandEdit() {

    let test = true;

    // xóa class lỗi
    $('#name').removeClass("error_field");

    // xóa thông báo lỗi
    $('#nameErr').text("");

    // lấy giá trị
    let name = $('#name').val().trim();
    let image = $('#image')[0].files[0];

    // validate
    // name
    if (name == "") {
        $('#name').addClass("error_field");
        $('#nameErr').text('không được để trống');
        test = false;
    }

    // image
    if (image != undefined) {
        let imgName = image.name;
        let listExt = ["jpg", 'jpeg', 'png'];
        let ext = imgName.split('.').pop().toLowerCase();
        let size = image.size;

        if (!listExt.some(val => val == ext)) {
            $('#imageErr').text("file không hợp lệ");
            test = false;
        } else if (size > 5000000) {
            $('#imageErr').text("kích cỡ file quá lớn");
            test = false;
        }
    }

    if (!test) {
        $('.error_field').first().focus();
    }

    return test;
}

function editBrand() {
    if (validateBrandEdit()) {

        let form_data = new FormData();

        let infoByForm = $("#brand_edit_form").serializeArray();
        $.each(infoByForm, function(k, obj) {
            form_data.append(obj.name, obj.value);
        });

        let image = $('#image')[0].files[0];
        if (image != undefined) {
            form_data.append("image", image);
        }

        form_data.append("action", "edit");

        let result = sendAJax(
            "process_brand.php",
            "post",
            "json",
            form_data,
            1
        );

        let status = result.status;
        let error = result.error;

        if(status == 'success') {
            alert('CẬP NHẬT HÃNG THÀNH CÔNG');
            window.location = "index.php";
        } else {
            alert("CẬP NHẬT HÃNG KHÔNG THÀNH CÔNG");

            if(error['name'] != "") {
                $('#name').addClass("error_field");
            }
            $('#nameErr').text(error['name']);
            $('#imageErr').text(error['file']);
        }
    }
}

// =========================SLIDER MODULE===========================
function validateSlideAdd() {
    let test = true;
    let limitSlide = 20;

    // xóa class lỗi
    $('#cat').removeClass('error_field');
    $('#slide').removeClass('error_field');
    $('#link').removeClass('error_field');

    // xóa thông báo lỗi
    $('#catErr').text('');
    $('#slideErr').text('');
    $('#linkErr').text('');

    // lấy giá trị
    let category = $('#cat').val();
    let slide = $('#slide')[0].files;
    let link = $('#link').val().trim();

    // category
    if (category == null) {
        $('#cat').addClass('error_field');
        $('#catErr').text('Không được để trống');
        test = false;
    }

    // slide
    let errorSlide = "";
    if (slide == undefined || slide.length == 0) {
        $('#slide').addClass('error_field');
        $('#slideErr').text('Không được để trống');
        test = false;
    } else {
        let qtySlide = slide.length;
        if (qtySlide > limitSlide) {
            errorSlide += "SỐ LƯỢNG FILE VƯỢT QUÁ GIỚI HẠN";
        } else {
            $.each(slide, function(k, v) {
                let fileName = v.name;
                let listExt = ['jpg', 'jpeg', 'png'];
                let ext = fileName.split('.').pop().toLowerCase();
                let size = v.size;

                if (!listExt.some(val => val == ext)) {
                    errorSlide += fileName + ":File không hợp lệ|";
                } else if (size > 5000000) {
                    errorSlide += fileName + ":File quá lớn|";
                }
            });
        }

        if (errorSlide.length > 0) {
            test = false;
            $('#slideErr').text(errorSlide);
        }
    }

    // link
    if(link == "") {
        test = false;
        $('#link').addClass('error_field');
        $('#linkErr').text("không được bỏ trống");
    } else if(!isURL(link)) {
        test = false;
        $('#link').addClass('error_field');
        $('#linkErr').text("sai định dạng");
    }

    if (!test) {
        $('.error_field').first().focus();
    }

    return test;
}

// hàm thêm slide
function addSlide() {
    if (validateSlideAdd()) {
        let form_data = new FormData();

        let infoByForm = $('#slide_add_form').serializeArray();

        $.each(infoByForm, function(k, obj) {
            form_data.append(obj.name, obj.value);
        });

        let listSlide = $('#slide')[0].files;
        if (listSlide != undefined) {
            $.each(listSlide, function(k, v) {
                form_data.append('slide[]', v);
            });
        }

        // thêm hành động
        form_data.append('action', "add");

        let result = sendAJax( "process_slider.php", "post", "json", form_data, 1);

        let status = result.status;
        let error = result.error;
        let msg = "";

        if(status == "success") {
            msg = "THÊM SLIDE THÀNH CÔNG";
            if(error['file'].length) {
                msg += "\nLƯU Ý:\n" + error['file'].join(", ");
            }
            alert(msg);
            window.location = "index.php";
        } else {
            msg = "THÊM SLIDE THẤT BẠI";

            // lỗi danh mục
            if(error['category'] != "") {
                msg += "\n" + error['category'];
                $('#cat').addClass('error_field');
            }
            $('#catErr').text(error['category']);

            // lỗi file ảnh slide
            if(error['file'].length) {
                msg += "\n" + error['file'].join(", ");
            }
            $('#slideErr').text(error['file'].join(", "));
           
            alert(msg);
        }
    }
}

// UPDATE SLIDE

function validateSlideEdit() {
    let test = true;

    // xóa class lỗi
    $('#cat').removeClass('error_field');
    $('#slide').removeClass('error_field');
    $('#link').removeClass('error_field');

    // xóa thông báo lỗi
    $('#catErr').text('');
    $('#slideErr').text('');
    $('#linkErr').text('');

    // lấy giá trị
    let category = $('#cat').val();
    let slide = $('#slide')[0].files;
    let link = $('#link').val().trim();

    // validate

    // category
    if (category == null) {
        $('#cat').addClass('error_field');
        $('#catErr').text('Không được để trống');
        test = false;
    }


    // slide
    let errorSlide = "";
    if (slide != undefined) {

        $.each(slide, function(k, v) {
            let fileName = v.name;
            let listExt = ['jpg', 'jpeg', 'png'];
            let ext = fileName.split('.').pop().toLowerCase();
            let size = v.size;

            if (!listExt.some(val => val == ext)) {
                errorSlide += fileName + ":File không hợp lệ|";
            } else if (size > 5000000) {
                errorSlide += fileName + ":File quá lớn|";
            }
        });

        if (errorSlide.length > 0) {
            test = false;
            $('#slideErr').text(errorSlide);
        }
    }

    if(link == "") {
        test = false;
        $('#link').addClass('error_field');
        $('#linkErr').text("không được để trống");
    } else if(!isURL(link)) {
        test = false;
        $('#link').addClass('error_field');
        $('#linkErr').text("sai định dạng");
    }

    if (!test) {
        $('.error_field').first().focus();
    }

    return test;
}

// hàm update slide
function editSlide() {
    if (validateSlideEdit()) {

        // form gửi dữ liệu lên server
        let form_data = new FormData();

        // dữ liệu từ form
        let infoByForm = $('#slide_edit_form').serializeArray();

        // thêm dữ liệu vào form gửi lên server
        $.each(infoByForm, function(k, obj) {
            form_data.append(obj.name, obj.value);
        });

        // file ảnh
        let slide = $('#slide')[0].files[0];

        // thêm ảnh vào form data gửi lên server
        form_data.append("newSlide", slide);

        // thêm hành động
        form_data.append('action', "edit");

        let result = sendAJax('process_slider.php', 'post', 'json', form_data, 1);

        let status = result.status;
        let error = result.error;
        let msg = "";
        
        if(status == "success") {
            msg = "CẬP NHẬT SLIDE THÀNH CÔNG";
            if(error.length) {
                msg += error.join("\n");
            }
            alert(msg);
            window.location = "index.php";
        } else {
            msg = "CẬP NHẬT SLIDE THẤT BẠI";
            if(error.length) {
                msg += error.join("\n");
            }
            alert(msg);
        }
    }

}

// ================ NEWS MODULE =============================/
function validateNewsAdd() {

    let test = true;

    // xóa class lỗi
    $('#title').removeClass('error_field');
    $('#desc').removeClass('error_field');
    $('#content').removeClass('error_field');
    $('#auth').removeClass('error_field');

    // xóa thông báo lỗi
    $('#titleErr').text('');
    $('#descErr').text('');
    $('#contentErr').text('');
    $('#authErr').text('');

    // lấy giá trị
    let title   = $('#title').val().trim();
    let desc    = $('#desc').val().trim();
    let content = $('#content').val().trim();
    let auth    = $('#auth').val().trim();
    let image   = $('#image')[0].files[0];

    // validate

    // title
    if (title == "") {
        $('#title').addClass('error_field');
        $('#titleErr').text('Không được để trống');
        test = false;
    }

    // desc
    if (desc == "") {
        $('#desc').addClass('error_field');
        $('#descErr').text('Không được để trống');
        test = false;
    }

    // content
    if (content == "") {
        $('#content').addClass('error_field');
        $('#contentErr').text('Không được để trống');
        test = false;
    }

    // auth
    if (auth == "") {
        $('#auth').addClass('error_field');
        $('#authErr').text('Không được để trống');
        test = false;
    }

    // image
    if (image == undefined) {
        $('#image').addClass('error_field');
        $('#imageErr').text('Không được để trống');
        test = false;
    } else {
        let imgName = image.name;
        let listExt = ["jpg", 'jpeg', 'png'];
        let ext = imgName.split('.').pop().toLowerCase();
        let size = image.size;

        if (!listExt.some(val => val == ext)) {
            $('#image').addClass("error_field");
            $('#imageErr').text("file không hợp lệ");
            test = false;
        } else if (size > 5000000) {
            $('#image').addClass("error_field");
            $('#imageErr').text("file quá lớn");
            test = false;
        }
    }

    if (!test) {
        $('.error_field').first().focus();
    }

    return test;
}

// hàm thêm slide
function addNews() {
    if (validateNewsAdd()) {
        let form_data = new FormData();

        let infoByForm = $('#news_add_form').serializeArray();

        $.each(infoByForm, function(k, obj) {
            form_data.append(obj.name, obj.value);
        });

        let image = $('#image')[0].files[0];
        form_data.append('image', image);

        // thêm hành động
        form_data.append('action', "add");

        let result = sendAJax( "process_news.php", "post", "json", form_data, 1 );

        let status = result.status;
        let error = result.error;
        let msg = "";

        if(status == "success") {
            msg = "THÊM THÀNH CÔNG";
            if(error.length) {
                msg += "\n" + error.join("\n");
            }
            alert(msg);
            window.location = "index.php";
        } else {
            msg = "THÊM THẤT BẠI";
            if(error.length) {
                msg += "\n" + error.join("\n");
            }
            alert(msg);
        }
    }
}

// UPDATE SLIDE

function validateNewsEdit() {

    let test = true;

    // xóa class lỗi
    $('#title').removeClass('error_field');
    $('#desc').removeClass('error_field');
    $('#content').removeClass('error_field');
    $('#auth').removeClass('error_field');

    // xóa thông báo lỗi
    $('#titleErr').text('');
    $('#descErr').text('');
    $('#contentErr').text('');
    $('#authErr').text('');

    // lấy giá trị
    let title = $('#title').val().trim();
    let desc = $('#desc').val().trim();
    let content = $('#content').val().trim();
    let auth = $('#auth').val().trim();
    let image = $('#image')[0].files[0];

    // validate

    // title
    if (title == "") {
        $('#title').addClass('error_field');
        $('#titleErr').text('Không được để trống');
        test = false;
    }

    // desc
    if (desc == "") {
        $('#desc').addClass('error_field');
        $('#descErr').text('Không được để trống');
        test = false;
    }

    // content
    if (content == "") {
        $('#content').addClass('error_field');
        $('#contentErr').text('Không được để trống');
        test = false;
    }

    // auth
    if (auth == "") {
        $('#auth').addClass('error_field');
        $('#authErr').text('Không được để trống');
        test = false;
    }

    // image
    if (image != undefined) {
        let imgName = image.name;
        let listExt = ["jpg", 'jpeg', 'png'];
        let ext = imgName.split('.').pop().toLowerCase();
        let size = image.size;

        if (!listExt.some(val => val == ext)) {
            $('#image').addClass("error_field");
            $('#imageErr').text("file không hợp lệ");
            test = false;
        } else if (size > 5000000) {
            $('#image').addClass("error_field");
            $('#imageErr').text("file quá lớn");
            test = false;
        }
    }

    if (!test) {
        $('.error_field').first().focus();
    }

    return test;
}

// hàm update slide
function editNews() {

    if (validateNewsEdit()) {
        // form gửi dữ liệu lên server
        let form_data = new FormData();

        // dữ liệu từ form
        let infoByForm = $('#news_edit_form').serializeArray();
        console.log(infoByForm);

        // thêm dữ liệu vào form gửi lên server
        $.each(infoByForm, function(k, obj) {
            form_data.append(obj.name, obj.value);
        });

        // file ảnh
        let image = $('#image')[0].files[0];

        // thêm ảnh vào form data gửi lên server
        form_data.append("image", image);

        // thêm hành động
        form_data.append('action', "edit");

        let result = sendAJax( "process_news.php", "post", "json", form_data, 1 );

        let status = result.status;
        let error = result.error;
        let msg = "";

        if(status == "success") {
            msg = "CẬP NHẬT THÀNH CÔNG";
            if(error.length) {
                msg += "\n" + error.join("\n");
            }
            alert(msg);
            window.location = "index.php";
        } else {
            msg = "CẬP NHẬT THẤT BẠI";
            if(error.length) {
                msg += "\n" + error.join("\n");
            }
            alert(msg);
        }
    }
}