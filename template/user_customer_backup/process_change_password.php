<?php 
	require_once '../common.php';
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		$userID = data_input(input_post("userID"));
		$oldPwd = data_input(input_post('oldPwd'));
		$newPwd = data_input(input_post('newPwd'));

		if($oldPwd === false || $newPwd === false) {
			echo 1;
		} else if(!check_password($oldPwd) || !check_password($newPwd)) {
			echo 2;
		} else {
			$checkOldPwd = "
			SELECT cus_password FROM db_customer
			WHERE cus_password = ? AND cus_id = ?
			";

			$runCheckOldPwd = s_cell($checkOldPwd, [$oldPwd, $userID], "si");

			if($runCheckOldPwd) {
				$changePwdSQL = "
				UPDATE db_customer
				SET cus_password = ? 
				WHERE cus_id = ?
				";

				$runUpdatePwd = db_run($changePwdSQL, [$newPwd, $userID], "si");
				if($runUpdatePwd) {
					echo 5;
				} else {
					echo 6;
				}
			} else {
				echo 3;
			}
			
		}
	}
 ?>