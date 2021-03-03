<?php 
	require_once 'common.php';
	var_dump(is_login());
	$period = new DatePeriod(
     new DateTime('2010-10-01'),
     new DateInterval('P1D'),
     new DateTime('2021-03-04')
);
foreach ($period as $key => $value) {
    echo($value->format('Y-m-d'));      
}
 ?>