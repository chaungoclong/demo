// function isEmail(string){
// 	var pattern = /^[a-z][a-z0-9_\.]{5,32}@[a-z0-9]{2,}(\.[a-z0-9]{2,4}){1,2}$/;
// 	return pattern.test(string);
// }

// function isDate(string) {
// 	var pattern = /^[12]\d{3}-(0[1-9]|1[12])-(0[1-9]|[12]\d|3[01])$/;
// 	return pattern.test(string);
// }

// function formatDate(string) {
// 	return string.split("-").reverse().join("-");
// }

// function isName(string) {
// 	var pattern = /^([a-zA-Z]{1,10}\s?)+$/;
// 	return pattern.test(string);
// }

// function isPassword(string) {
// 	var pattern = /^([\w\d]{8,32})$/;
// 	return pattern.test(string);
// }

// function isPhone(string) {
// 	var pattern = /^(84|0[3|5|7|8|9])+([0-9]{8})$/;
// 	return pattern.test(string);
// }

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
		$('#userErr').text("không được để trống");
		test = false;
	} else if(!isPhone(user) && !isEmail(user)) {
		$('#user').addClass("error_field");
		$('#userErr').text("sai định dạng");
		test = false;
	}

	//password
	if(pwd == "") {
		$('#pwdLogin').addClass("error_field");
		$('#pwdLoginErr').text("không được để trống");
		test = false;
	} else if(!isPassword(pwd)) {
		$('#pwdLogin').addClass("error_field");
		$('#pwdLoginErr').text("sai định dạng");
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
	$('#tinh').removeClass("error_field");
	$('#huyen').removeClass("error_field");
	$('#xa').removeClass("error_field");
	$('#street').removeClass("error_field");
	$('#pwdRegister').removeClass("error_field");
	$('#rePwdRegister').removeClass("error_field");
	$('#avatar').removeClass("error_field");

	//xóa thông báo lỗi
	$('#nameErr').text("");
	$('#dobErr').text("");
	$('#genderErr').text("");
	$('#emailErr').text("");
	$('#phoneErr').text("");
	$('#tinhErr').text("");
	$('#huyenErr').text("");
	$('#xaErr').text("");
	$('#streetErr').text("");
	$('#pwdRegisterErr').text("");
	$('#rePwdRegisterErr').text("");
	$('#avatarErr').text("");

	//lấy dữ liệu
	let name    =  $('#name').val().trim();
	let dob     =  $('#dob').val().trim();
	let gender  =  Object.values($('#registerForm')[0]['gender']);
	let email   =  $('#email').val().trim();
	let phone   =  $('#phone').val().trim();
	let tinh    =  $('#tinh').val();
	let huyen   =  $('#huyen').val();
	let xa      =  $('#xa').val();
	let street =  $('#street').val().trim();
	let pwd     =  $('#pwdRegister').val().trim();
	let rePwd   =  $('#rePwdRegister').val().trim();
	let avatar  =  $('#avatar')[0].files[0];
	//console.log(avatar);

	//VALIDATE
	//name
	if(name == "") {
		$('#name').addClass("error_field");
		$('#nameErr').text("không được dể trống");
		test = false;
	} else if(!isName(name)) {
		$('#name').addClass("error_field");
		$('#nameErr').text("sai định dạng");
		test = false;
	}

	//dob
	if(dob == "") {
		$('#dob').addClass("error_field");
		$('#dobErr').text("không được để trống");
		test = false;
	} else if(!isDate(formatDate(dob))) {
		$('#dob').addClass("error_field");
		$('#dobErr').text("sai định dạng");
		test = false;
	}

	//gender
	var check = gender.some(val => val.checked);
	if(!check) {
		test = false;
		$('#gender').addClass("error_field");
		$('#genderErr').text("không được để trống");
	}

	//email
	if(email == "") {
		$('#email').addClass("error_field");
		$('#emailErr').text("không được để trống");
		test = false;
	} else if(!isEmail(email)) {
		$('#email').addClass("error_field");
		$('#emailErr').text("sai định dạng");
		test = false;
	}

	//phone
	if(phone == "") {
		$('#phone').addClass("error_field");
		$('#phoneErr').text("không được để trống");
		test = false;
	} else if(!isPhone(phone)) {
		$('#phone').addClass("error_field");
		$('#phoneErr').text("sai định dạng");
		test = false;
	}

	// tỉnh
	if(tinh == "") {
		$('#tinh').addClass("error_field");
		$('#tinhErr').text("không được dể trống");
		test = false;
	}

	// huyện
	if(huyen == "") {
		$('#huyen').addClass("error_field");
		$('#huyenErr').text("không được dể trống");
		test = false;
	}

	// xã
	if(xa == "") {
		$('#xa').addClass("error_field");
		$('#xaErr').text("không được dể trống");
		test = false;
	}

	//addressErr
	if(street == "") {
		$('#street').addClass("error_field");
		$('#streetErr').text("không được dể trống");
		test = false;
	}

	//password
	if(pwd == "") {
		$('#pwdRegister').addClass("error_field");
		$('#pwdRegisterErr').text("không được dể trống");
		test = false;
	} else if(!isPassword(pwd)) {
		$('#pwdRegister').addClass("error_field");
		$('#pwdRegisterErr').text("sai định dạng");
		test = false;
	}

	//repassword
	if(rePwd != pwd) {
		$('#rePwdRegister').addClass("error_field");
		$('#rePwdRegisterErr').text("mật khẩu không khớp");
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
		} else if(size > 5000000){
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

		data.append("action", "register");

		//gửi ajax lấy về phản hòi -> hiển thị thông báo
		let result = sendAJax('process_register.php', 'post', 'json', data, 1);
		let status = result.status;
		let error = result.error;
		let msg = "";

		if(status == "success") {
			msg = "ĐĂNG KÍ THÀNH CÔNG";
			alert(msg);
			window.location = "login_form.php";
		} else {
			msg = "ĐĂNG KÝ THẤT BẠI";
			if(error.length) {
				msg += "\n" + error.join("\n");
			}
			alert(msg);
		}
	}

}


function login() {
	if(validateLogin()) {
		let data = $('#loginForm').serialize();
		
		let result = sendAJax('process_login.php', 'post', 'json', data);
		let status = result.status;
		let error = result.error;
		let msg = "";

		if(status == "success") {
			msg = "ĐĂNG NHẬP THÀNH CÔNG";
			alert(msg);
			window.location = "index.php";
		} else {
			msg = "ĐĂNG NHẬP THẤT BẠI";
			if(error.length) {
				msg += "\n" + error.join('\n');
			}
			alert(msg);
		}
	}
}