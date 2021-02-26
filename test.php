<?php 
require_once 'common.php';
require_once 'include/header.php';
$con = new mysqli('localhost', 'root', '', 'don_vi_hanh_chinh');
 ?>
<!DOCTYPE html>
<html>
<body>

	<!-- <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
	<script>

		Pusher.logToConsole = true;
		var pusher = new Pusher('73ef9c76d34ce11d7557', {
			cluster: 'ap1'
		});
		var channel = pusher.subscribe('my-channel');
		channel.bind('my-event', function(data) {
			alert(JSON.stringify(data));
		});
	</script> -->

	<select name="tinh" id="tinh">
		<option value="" hidden>Tỉnh/Thành phố</option>
		<?php $ds_tinh = $con->query("select * from db_tinh"); ?>
		<?php if ($ds_tinh->num_rows): ?>
			<?php while($tinh = $ds_tinh->fetch_assoc()): ?>
				<option value="<?= $tinh['name'] ?>" data-id-tinh="<?= $tinh['matp'] ?>"> <?= $tinh['name'] ?> </option>
			<?php endwhile ?>
		<?php else: ?>
			<option value="">Không có dữ liệu</option>
		<?php endif ?>
	</select>

	<select name="huyen" id="huyen">
		<option value="">Quận/Huyện</option>
	</select>

	<select name="xa" id="xa">
		<option value="">Phường/Xã</option>
	</select>

	<script>
		$(function() {
			$(document).on('change', '#tinh', function() {
				let id_tinh = $(this).find('option:selected').data('id-tinh');
				$('#huyen').load('fetch_unit.php', {id_tinh: id_tinh}, function() {
					$('#xa').html("<option value=''>Phường/Xã</option>");
				});
			}); 

			$(document).on('change', '#huyen', function() {
				let id_huyen = $(this).find('option:selected').data('id-huyen');
				$('#xa').load('fetch_unit.php', {id_huyen: id_huyen});
			}); 

			$(document).on('click', function() {
				console.log($('#tinh').val() + "-" + $('#huyen').val() + "-" + $('#xa').val());
			})
		});

		function huyen(id_tinh) {
			if(id_tinh) {
				$.ajax({
					url: "fetch_unit.php", 
					type: "post", 
					dataType: "html",
					data: {id_tinh: id_tinh}
				})
				.done(function(res) {
					$('#huyen').html(res);
					$('#xa').html("<option value=''>Phường/Xã</option>");
				})
			}
		}

		function xa(id_huyen) {
			if(id_huyen) {
				$.ajax({
					url: "fetch_unit.php",
					type: 'post',
					data: {id_huyen: id_huyen},
					dataType: 'html'
				})
				.done(function(res) {
					$('#xa').html(res);
				})
			}
		}
	</script>


</body>
</html>
