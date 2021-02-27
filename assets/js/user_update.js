function validateUserUpdate() {
	let test = true;

	// xóa class lỗi 
	$('#name').removeClass("error_field");
	$('#dob').removeClass("error_field");
	$('#gender').removeClass("error_field");
	$('#email').removeClass("error_field");
	$('#phone').removeClass("error_field");
	$('#street').removeClass("error_field");
	$('#avatar').removeClass("error_field");
	$('#tinh').removeClass("error_field");
	$('#huyen').removeClass("error_field");
	$('#xa').removeClass("error_field");

	// xóa thông báo lỗi
	$('#nameErr').text("");
	$('#dobErr').text("");
	$('#genderErr').text("");
	$('#emailErr').text("");
	$('#phoneErr').text("");
	$('#streetErr').text("");
	$('#avatarErr').text("");
	$('#tinhErr').text("");
	$('#huyenErr').text("");
	$('#xaErr').text("");

	//lấy dữ liệu
	let name   =  $('#name').val().trim();
	let dob    =  $('#dob').val().trim();
	let gender =  Object.values($('#user_info_update_form')[0]['gender']);
	let email  =  $('#email').val().trim();
	let phone  =  $('#phone').val().trim();
	let street =  $('#street').val().trim();
	let avatar =  $('#avatar')[0].files[0];
	let tinh   =  $('#tinh').val().trim();
	let huyen  =  $('#huyen').val().trim();
	let xa     =  $('#xa').val().trim();

	//VALIDATE
	//name
	if(name == "") {
		$('#name').addClass("error_field");
		$('#nameErr').text("không được để trống");
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
		$('#tinhErr').text("không được để trống");
		test = false;
	}
	console.log(tinh+huyen+xa);

	// huyện
	if(huyen == "") {
		$('#huyen').addClass("error_field");
		$('#huyenErr').text("không được để trống");
		test = false;
	}

	// xã
	if(xa == "") {
		$('#xa').addClass("error_field");
		$('#xaErr').text("không được để trống");
		test = false;
	}

	//street
	if(street == "") {
		$('#street').addClass("error_field");
		$('#streetErr').text("không được để trống");
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
			$('#avatarErr').text("file không hợp lệ");
			test = false;
		} else if(size > 5000000){
			$('#avatar').addClass("error_field");
			$('#avatarErr').text("file quá lớn");
			test = false;
		}
	}

	if(!test) {
		$('.error_field').first().focus();
	}
	return test;
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
		$('#oldPwdErr').text("không được để trống");
		test = false;
	} else if(!isPassword(oldPwd)) {
		$('#oldPwd').addClass('error_field');
		$('#oldPwdErr').text("không được để trống");
		test = false;
	}

	if(newPwd == "") {
		$('#newPwd').addClass('error_field');
		$('#newPwdErr').text("không được để trống");
		test = false;
	} else if(!isPassword(newPwd)) {
		$('#newPwd').addClass('error_field');
		$('#newPwdErr').text("sai định dạng");
		test = false;
	}

	if(!test) {
		$('.error_field').first().focus();
	}

	return test;
}

function userInfoUpdate() {
	if(validateUserUpdate()) {

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
		if(avatar != undefined) {
			data.append("avatar", avatar);
		}
		
		// biến gửi ajax
		let sendUpdate = $.ajax({
			url: "process_user_update_info.php",
			type: "POST",
			data: data,
			dataType:"json",
			cache: false,
			contentType: false,
			processData: false,
		});

		sendUpdate.done(function(res) {
			let status = res.status;
			let data = res.info;
			console.log(res);
			console.log(status, data);

			switch(status) {
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

function userPasswordUpdate() {
	if(validateUpdatePassword()) {
		let data = $('#change_pwd_form').serialize();
		let updatePwd = sendAJax(
			"process_change_password.php",
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
