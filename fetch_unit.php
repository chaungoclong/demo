<?php 
	require_once 'common.php';

	if(!empty($_POST['id_tinh'])) {
		$id_tinh = $_POST['id_tinh'];
		$ds_huyen = db_get("select * from db_huyen where matp = ?", 0, [$id_tinh], "i");

		echo "<option value='' hidden>Quận/Huyện</option>";
		foreach ($ds_huyen as $key => $huyen) {
			echo '<option value="'.$huyen['name'].'" data-id-huyen="'.$huyen['maqh'].'">'.$huyen['name'].'</option>';
		}
	}

	if(!empty($_POST['id_huyen'])) {
		$id_huyen = $_POST['id_huyen'];
		$ds_xa = db_get("select * from db_xa where maqh = ?", 0, [$id_huyen], "i");

		echo "<option value='' hidden>Phường/Xã</option>";
		foreach ($ds_xa as $key => $xa) {
			echo '<option value="'.$xa['name'].'">'.$xa['name'].'</option>';
		}
	}
 ?>