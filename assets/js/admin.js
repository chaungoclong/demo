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
    } else if (!isPhone(user) && !isEmail(user)) {
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
        } else if (size > 500000) {
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
	if(oldPwd == "") {
		$('#oldPwd').addClass('error_field');
		$('#oldPwdErr').text("required");
		test = false;
	} else if(!isPassword(oldPwd)) {
		$('#oldPwd').addClass('error_field');
		$('#oldPwdErr').text("password is wrong");
		test = false;
	}

	if(newPwd == "") {
		$('#newPwd').addClass('error_field');
		$('#newPwdErr').text("required");
		test = false;
	} else if(!isPassword(newPwd)) {
		$('#newPwd').addClass('error_field');
		$('#newPwdErr').text("password is wrong");
		test = false;
	}

	if(!test) {
		$('.error_field').first().focus();
	}

	return test;
}

function userPasswordUpdate() {
	if(validateUpdatePassword()) {
		let data = $('#change_pwd_form').serialize();
		let updatePwd = sendAJax(
			"process_password.php",
			"post",
			"text",
			data
		);

		switch(updatePwd) {
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
