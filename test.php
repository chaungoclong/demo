<?php 
include_once 'common.php';
include_once 'include/header.php';
	// db_connect();
	// $kq = get_list("select * from db_category");
	// var_dump($kq);
	// // update("db_category", ["cat_name" => "ĐIỆN THOẠI"], "cat_id = 1");
	// $id = 1;
	// $username = "long";
	// $role = 3;
	// set_login($id, $username, $role);
	// var_dump(get_session("user_token"));
	// getUserById($id);
	//set_logout();
	// $id = 1;
	// $num = fetch_tbl('db_customer', 2);
	// var_dump($num);
	// echo $num->num_rows;
	// 
	// $time = time();
	// echo date("Y", $time);
	// echo "<br>";
	// echo date("m", $time);
	// echo "<br>";
	// echo date("d", $time);
	// echo "<br>";
	// echo date("H", $time);
	// echo "<br>";
	// echo date("i", $time);
	// echo "<br>";
	// echo date("s", $time);
	// echo "leap year";
	// echo leap_Year(2016);
	// echo "<br>";
	// $year = date("Y");
	// echo $year;
	// echo "<br>";
	// echo (new DateTime)->format("Y");
	// echo "<br>";
	// echo leap_Year(date("Y"));
	// echo "<br>";
	// $time = strtotime("3 October 2005");
	// echo $time;
	// echo "<br>";
	// echo read_date($time);

	// $result = fetch_tbl("db_product");
	// $row = get_display([1, 2, 3], 4);
	// var_dump($row);
	// echo gettype($result);
	// echo fetch_tbl("db_product", 0);
	// $link = "http://localhost/do_an_1/product.php";
	// $currentLink = create_link(base_url("product.php"), ["page"=>"{page}"]);
	// //paginate($currentLink, 20, 1, 4);
	// //echo str_replace("page", "123", $currentLink);
	// echo "hello";
	// 
	// $test = db_get('select pro_id from db_product where cat_id = ? and bra_id = 3', [1], 2);
	// var_dump($test);
	// echo "<br><br><br>";

	// $test = s_row('select * from db_product where cat_id = ? and bra_id = 3', [1]);
	// var_dump($test);
	// echo "<br><br><br>";

	// $test = s_cell('select pro_name from db_product where cat_id = ? and bra_id = 3', [1]);
	// var_dump($test);

	// $test = db_run("update db_product set pro_price = ? where pro_id = ?", 123, 5);
	// echo $test;
	// $test = get_session("cart");
	// vd($test);

?>
<!-- <script>
$(function() {
		//xử lý chọn sao
		$('.starr').on('click', function() {

			//danh sách sao
			let listStar = $('.starr');

			//giá trị của lần chọn trước
			let valuePrev = $('#rateValue').val();

			//giá trị của lần chọn này
			let value = $(this).data('rate');

			//thay đổi giá trị trong thẻ input
			$('#rateValue').val(value);
			
			//bỏ trạng thái được chọn của tất cả các sao
			for(let i = 0; i < 5; ++i) {
				$(listStar[i]).removeClass('star_selected');
			}

			//đặt trạng thái được chọn cho các sao từ 0 -> value
			for(let i = 0; i < value; ++i) {
				$(listStar[i]).addClass('star_selected');
			}
		});

		//xử lý đánh giá
		$('#sendRate').on('click', function(e) {
			e.preventDefault();
			let checkLogin = <?= is_login() ? 1 : 0; ?>;
			if(checkLogin == 0) {
				alert('ban chua dang nhap');
			} else {
				let cusID = <?= $_SESSION['user_token']['id']; ?>;
				console.log(cusID);
				let proID = <?= $product['pro_id']; ?>;
				console.log(proID);
				let rateContent = $('#rateContent').val();
				console.log(rateContent);
				let rateValue = $('#rateValue').val();
				console.log(rateValue);

				if(!rateValue) {
					alert('vui long chon so sao');
				} else if(!rateContent) {
					alert('vui long viet it nhat 80 tu');
				} else {
					/**
					 * #mỗi người chỉ được đánh giá về 1 sản phẩm khi đã mua sản phẩm đó
					 * #mỗi người chỉ có 1 đánh giá về 1 sản phẩm nên trước khi đưa ra hành động
					 * cập nhật hay thêm đánh giá thì kiểm tra đánh giá của người cusID về sản phẩm proID
					 * đã tồn tại chưa
					 */
					let action = "rate_exist";
					let data = {cusID:cusID, proID:proID, action:action};
					let rateExist = sendAJax( 
						"process_rate.php",
						"post",
						"text", 
						data
					);
		
					//nếu đánh giá đã tồn tại thì có cập nhật không
					if(rateExist == "1") {
						console.log('exist'+ rateExist);
						let updateOK = confirm("ban co muon cap nhat danh gia");
						if(updateOK) {
							let action = "update_rate";
							let data = {
								cusID:cusID,
								proID:proID,
								rateContent:rateContent,
								rateValue:rateValue,
								action:action
							};

							let sendAjax = sendAJax(
								"process_rate.php",
								"post",
								"json",
								data
								);
							alert(sendAjax.msg);
						}
					} else {
						let action = "add_rate";
						let data = {
							cusID:cusID,
							proID:proID,
							rateContent:rateContent,
							rateValue:rateValue,
							action:action
						};

						let sendAjax = sendAJax(
							"process_rate.php",
							"post",
							"json",
							data
							);
						alert(sendAjax.msg);
					}
				}
			}
		});
		
	});
</script> -->
<?php if(isset($_POST['test'])) {
	vd($_POST['test']);
} ?>
<form action="" method='post'>
	<textarea name="test" id="" cols="30" rows="10">
	</textarea>
	<script>
		CKEDITOR.replace('test');
	</script>
	<button type="submit">send</button>
</form>

