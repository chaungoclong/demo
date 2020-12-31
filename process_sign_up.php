<?php 
	require_once 'common.php';
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		$name = input_post("name");
		$dob = input_post("dob");
		$gender = input_post("gender");
		$email = input_post("email");
		$pass = input_post("pass");
		$re_pass = input_post("repass");
		$avatar = $_FILES[0]["name"];

		$sql = "
		INSERT INTO db_customer(cus_name, cus_dob, cus_gender, cus_email, cus_password, cus_avatar)
		VALUES (?, ?, ?, ?, ?, ?);
		";
		db_run($sql, $name, $dob, $gender, $email, $pass, $avatar);

	}

 ?>