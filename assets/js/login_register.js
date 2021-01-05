function isEmail(string){
	var pattern = /^[a-z][a-z0-9_\.]{5,32}@[a-z0-9]{2,}(\.[a-z0-9]{2,4}){1,2}$/;
	return pattern.test(string);
}

function isDate(string) {
	var pattern = /^[12]\d{3}-(0[1-9]|1[12])-(0[1-9]|[12]\d|3[01])$/;
	return pattern.test(string);
}

function formatDate(string) {
	return string.split("-").reverse().join("-");
}

function isName(string) {
	var pattern = /^([a-zA-Z]{3,10}\s?)+$/;
	return pattern.test(string);
}

function isPassword(string) {
	var pattern = /^([\w\d]{8,32})$/;
	return pattern.test(string);
}

function isPhone(string) {
	var pattern = /^(84|0[3|5|7|8|9])+([0-9]{8})$/;
	return pattern.test(string);
}

// function isAddress(string) {
// 	var pattern = /^(84|0[3|5|7|8|9])+([0-9]{8})$/;
// 	return pattern.test(string);
// }

function validateLogin() {
	var test = true;
	//xóa class lỗi 
	$('#user').removeClass("error_field");
	$('#pwdLogin').removeClass("error_field");

	//xóa thông báo lỗi
	$('#userErr').text("");
	$('#pwdLoginErr').text("");

	//lấy dữ liệu
	var user    =  $('#user').val().trim();
	var pwd    =  $('#pwdLogin').val().trim();

	//VALIDATE
	//user
	if(user == "") {
		$('#user').addClass("error_field");
		$('#userErr').text("Email / Phone is required");
		test = false;
	} else if(!isPhone(user) && !isEmail(user)) {
		$('#user').addClass("error_field");
		$('#userErr').text("Email / Phone is wrong");
		test = false;
	}

	//password
	if(pwd == "") {
		$('#pwdLogin').addClass("error_field");
		$('#pwdLoginErr').text("password is required");
		test = false;
	} else if(!isPassword(pwd)) {
		$('#pwdLogin').addClass("error_field");
		$('#pwdLoginErr').text("password is wrong");
		test = false;
	}

	if(!test) {
		$('.error_field').first().focus();
	}

	return test;
}

function validateRegister() {
	var test = true;

	//xóa class lỗi 
	$('#name').removeClass("error_field");
	$('#dob').removeClass("error_field");
	$('#gender').removeClass("error_field");
	$('#email').removeClass("error_field");
	$('#phone').removeClass("error_field");
	$('#address').removeClass("error_field");
	$('#pwdRegister').removeClass("error_field");
	$('#rePwdRegister').removeClass("error_field");
	$('#avatar').removeClass("error_field");

	//xóa thông báo lỗi
	$('#nameErr').text("");
	$('#dobErr').text("");
	$('#genderErr').text("");
	$('#emailErr').text("");
	$('#phoneErr').text("");
	$('#addressErr').text("");
	$('#pwdRegisterErr').text("");
	$('#rePwdRegisterErr').text("");
	$('#avatarErr').text("");

	//lấy dữ liệu
	var name    =  $('#name').val().trim();
	var dob     =  $('#dob').val().trim();
	var gender  =  Object.values($('#registerForm')[0]['gender']);
	var email   =  $('#email').val().trim();
	var phone   =  $('#phone').val().trim();
	var address =  $('#address').val().trim();
	var pwd     =  $('#pwdRegister').val().trim();
	var rePwd   =  $('#rePwdRegister').val().trim();
	var avatar  =  $('#avatar')[0].files[0];
	//console.log(avatar);

	//VALIDATE
	//name
	if(name == "") {
		$('#name').addClass("error_field");
		$('#nameErr').text("name is required");
		test = false;
	} else if(!isName(name)) {
		$('#name').addClass("error_field");
		$('#nameErr').text("name is wrong");
		test = false;
	}

	//dob
	if(dob == "") {
		$('#dob').addClass("error_field");
		$('#dobErr').text("date is required");
		test = false;
	} else if(!isDate(formatDate(dob))) {
		$('#dob').addClass("error_field");
		$('#dobErr').text("date is wrong");
		test = false;
	}

	//gender
	var check = gender.some(val => val.checked);
	if(!check) {
		test = false;
		$('#gender').addClass("error_field");
		$('#genderErr').text("gender is required");
	}

	//email
	if(email == "") {
		$('#email').addClass("error_field");
		$('#emailErr').text("email is required");
		test = false;
	} else if(!isEmail(email)) {
		$('#email').addClass("error_field");
		$('#emailErr').text("email is wrong");
		test = false;
	}

	//phone
	if(phone == "") {
		$('#phone').addClass("error_field");
		$('#phoneErr').text("phone is required");
		test = false;
	} else if(!isPhone(phone)) {
		$('#phone').addClass("error_field");
		$('#phoneErr').text("phone is wrong");
		test = false;
	}

	//addressErr
	if(address == "") {
		$('#address').addClass("error_field");
		$('#addressErr').text("address is required");
		test = false;
	}

	//password
	if(pwd == "") {
		$('#pwdRegister').addClass("error_field");
		$('#pwdRegisterErr').text("password is required");
		test = false;
	} else if(!isPassword(pwd)) {
		$('#pwdRegister').addClass("error_field");
		$('#pwdRegisterErr').text("password is wrong");
		test = false;
	}

	//repassword
	if(rePwd != pwd) {
		$('#rePwdRegister').addClass("error_field");
		$('#rePwdRegisterErr').text("password not match");
		test = false;
	}

	//avatar
	if(avatar != undefined) {
		let imgName = avatar.name;
		let listExt = ["jpg", 'jpeg', 'png', 'gif'];
		let ext = imgName.split('.').pop().toLowerCase();
		let size = avatar.size;

		if(!listExt.some(val => val == ext)) {
			$('#avatar').addClass("error_field");
			$('#avatarErr').text("extention not match");
			test = false;
		} else if(size > 500000){
			$('#avatar').addClass("error_field");
			$('#avatarErr').text("size is very big");
			test = false;
		}
	}

	if(!test) {
		$('.error_field').first().focus();
	}
	return test;
}

function register() {
	console.log(validateRegister());
	//nếu không có lỗi gửi sang file xử lý
	if(validateRegister()) {
		//dữ liệu gửi sang file xử lý
		var data = new FormData();

		//ảnh
		var avatar = $('#avatar')[0].files[0];

		//thông tin khác
		var info = $('#registerForm').serializeArray();
		console.log(avatar);

		//thêm ảnh vào dữ liệu
		data.append('avatar', avatar);
		// $.each(avatar, function(k, v) {
		// 	data.append('file[]', v);
		// });
		
		//thêm thông tin vào dữ liệu
		$.each(info, function(k, obj) {
			data.append(obj.name, obj.value);
		});

		//gửi ajax lấy về phản hòi -> hiển thị thông báo
		$.ajax({
			url: "process_register.php",
			type: "POST",
			data: data,
			dataType:"text",
			cache: false,
			contentType: false,
			processData: false,
			success: function(res) {
				console.log(res);
				switch(res) {
					case "1":
						$('#backErr').text("Thiếu dữ liệu");
						break;
					case "2":
						$('#backErr').text("Dữ liệu sai");
						break;
					case "3":
						$('#backErr').text("Email đã tồn tại");
						break;
					case "4":
						$('#backErr').text("Số điện thoại đã tồn tại");
						break;
					case "5":
						$('#backErr').text("Đăng kí thành công");
						window.location = "login_form.php";
						break;
					case "6":
						$('#backErr').text("Đăng ký thất bại. Hãy thử lại");
						break;
				}
			},
			error: function (a, b, c) {
				console.log(b);
			}
		});
	}

}


function login() {
	if(validateLogin()) {
		var data = $('#loginForm').serialize();
		$.post(
			"process_login.php",
			data,
			function(res) {
				switch(res) {
					case "1":
						$('#backErr').text("Thiếu dữ liệu");
						break;
					case "2":
						$('#backErr').text("Dữ liệu sai");
						break;
					case "5":
						$('#backErr').text("Đăng nhập thành công");
						window.location = "index.php";
						break;
					case "6":
						$('#backErr').text("Đăng nhập thất bại. Hãy thử lại");
						break;
					case "8":
						$('#backErr').text("Tài khoản của bạn bị khóa");
						break;
				}
			},
			"text"
		);
	}
}