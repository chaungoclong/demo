<!DOCTYPE html>
<html>
<body>
<?php 	
		require_once 'common.php';
		$_POST['email'] = "abc@hotmail.com";
		$email = is_email($_POST['email']);
		var_dump($email);
		$number = int("1");
		var_dump($number);
		$number = float("1");
		var_dump($number);
		$bool = bool("0");
		var_dump($bool);
		var_dump(check_word("Laptop Apple MacBook Air 2017 i5 1.8GHz/8GB/128GB (MQD32SA/A)"));
		$arr = ['a'=>'', 'b'=>''];

		echo getTotalMoneyAnOrder(98);
		
 ?>

</body>
</html>
