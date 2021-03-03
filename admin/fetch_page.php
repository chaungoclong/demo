<?php 
	require_once '../common.php';
	if(isset($_POST['action']) && $_POST['action'] == "fetch") {
		$getOrderSQL = "
		SELECT db_order.*, db_customer.cus_name, db_customer.cus_address, db_customer.cus_phone
		FROM db_order JOIN db_customer
		ON db_order.cus_id = db_customer.cus_id
		WHERE 1";
		$param = [];
		$format = "";

		// tìm kiếm theo mã đơn hàng, thông tin người nhận, thông tin người đặt, ngày đặt
		$q = !empty($_POST['q']) ? $_POST['q'] : "%%";
		$getOrderSQL .= " AND CONCAT(
		db_customer.cus_name, db_customer.cus_address, db_customer.cus_phone, 
		db_order.receiver_name, db_order.receiver_add, db_order.receiver_phone, db_order.or_id, db_order.or_create_at
		) LIKE(?)";
		$param[] = $q;
		$format .= "s";

		// tìm kiếm theo khoảng thời gian
		$from_day = !empty($_POST['from_day']) ? formatDate($_POST['from_day']) : "";
		$to_day   = !empty($_POST['to_day']) ? formatDate($_POST['to_day']) : "";

		if($from_day && $to_day) {
			$getOrderSQL .= " AND DATE(db_order.or_update_at) BETWEEN DATE(?) AND DATE(?)";
			$param = [...$param, $from_day, $to_day];
			$format .= "ss";
		}

		// tìm kiếm nhanh theo khoảng thời gian cụ thể
		$quick_search = !empty($_POST['quick_search']) ? $_POST['quick_search'] : "";
		switch ($quick_search) {
			case 'day':
				$getOrderSQL .= " AND DATE(db_order.or_update_at) = CURDATE()";
				break;
			case 'week':
				$getOrderSQL .= " AND YEARWEEK(db_order.or_update_at, 1) = YEARWEEK(CURDATE(), 1)";
				break;
			case 'month':
				$getOrderSQL .= " AND MONTH(db_order.or_update_at) = MONTH(CURDATE()) AND YEAR(db_order.or_update_at) = YEAR(CURDATE())";
				break;

			default:
				break;
		}
		

		// tìm kiếm theo trạng thái
		$status = !empty($_POST['status']) ? $_POST['status'] : "all";
		switch ($status) {
			case 'all':
				break;
			case 'pending':
				$getOrderSQL .= " AND db_order.or_status = 0";
				break;
			case 'success':
				$getOrderSQL .= " AND db_order.or_status = 1";
				break;
			case 'fail':
				$getOrderSQL .= " AND db_order.or_status = 2";
				break;
			default:
				break;
		}

		// sắp xếp
		$sort = !empty($_POST['sort']) ? (int)$_POST['sort'] : 1;
		switch ($sort) {
			case 1:
				$getOrderSQL .= " ORDER BY db_order.or_create_at DESC";
				break;
			case 2:
				$getOrderSQL .= " ORDER BY db_order.or_create_at ASC";
				break;
			default:
				$getOrderSQL .= " ORDER BY db_order.or_create_at DESC";
				break;
		}

		$listOrder = db_get($getOrderSQL, 0, $param, $format);
		$totalOrder = count($listOrder);

		// chia trang
		$orPerPage = !empty($_POST['numRows']) ? (int)$_POST['numRows'] : 5;
		$totalPage = ceil($totalOrder / $orPerPage);
		$currentPage = !empty($_POST['currentPage']) ? (int)$_POST['currentPage'] : 1;
		$currentPage = $currentPage > $totalPage ? $totalPage : $currentPage;
		$offset = ($currentPage - 1) * $orPerPage;

		$getOrderSQL .= " LIMIT ? OFFSET ?";
		$param = [...$param, $orPerPage, $offset];
		$format .= "ii";
		$listOrder = db_get($getOrderSQL, 0, $param, $format);

		$orders = "";
		// in các đơn hàng
		
		if ($totalOrder > 0) {
			foreach ($listOrder as $key => $order) {
				$status = $order['or_status'];
				$showStatus = $btnOption = "";

				// hiển thị trạng thái đơn hàng
				switch ($status) {
					case 0:
						$showStatus = "<span class='badge badge-primary p-1' style='width:70px;'>chờ duyệt</span>";
						break;
					case 1:
						$showStatus = "<span class='badge badge-success p-1' style='width:70px;'>đã duyệt</span>";
						break;
					case 2:
						$showStatus = "<span class='badge badge-danger p-1' style='width:70px;'>đã hủy</span>";
						break;
					default:
						$showStatus = "<span class='badge badge-secondary p-1' style='width:70px;'>không xác định</span>";
						break;
				}

				// hiển thị nút duyệt, hủy
				$btnOption = "";
				if($order['or_status'] == 0) {
					$btnOption .= '  
						<div class="btn-group" role="group">
							<button
							class="btn_confirm btn btn-primary"
							id="btn_confirm_' . $order['or_id'] . '"
							data-order-id="' . $order['or_id'] . '"
							>
							Duyệt
							</button>
							<button
							class="btn_cancel btn btn-danger"
							id="btn_cancel_' . $order['or_id'] . '"
							data-order-id="' . $order['or_id'] . '"
							>
							Hủy
							</button>
						</div>
					';
				}

				$orders .= '   
				<tr>
					<!-- mã -->
					<td class="align-middle">' . $order['or_id'] . '</td>

					<!-- ngày đặt -->
					<td class="align-middle">' . strToTimeFormat($order['or_create_at'], "d-m-Y") . '</td>

					<td class="align-middle text-info">' .number_format(getTotalMoneyAnOrder($order['or_id'])). ' &#8363;</td>

					<!-- trạng thái  -->
					<td id="status_order_' . $order['or_id'] . '" class="align-middle">	
						' . $showStatus . '
					</td>

					<!-- người đặt -->
					<td class="align-middle">
						<p><strong class="mr-1">Tên:</strong>' . $order['cus_name'] . '</p>
						<p><strong class="mr-1">Địa chỉ:</strong>' . $order['cus_address'] . '</p>
						<p><strong class="mr-1">SĐT:</strong>' . $order['cus_phone'] . '</p>
					</td>

					<!-- người nhận -->
					<td class="align-middle">
						<p><strong class="mr-1">Tên:</strong>' . $order['receiver_name'] . '</p>
						<p><strong class="mr-1">Địa chỉ:</strong>' . $order['receiver_add'] . '</p>
						<p><strong class="mr-1">SĐT:</strong>' . $order['receiver_phone'] . '</p>
					</td>

					<!-- xem -->
					<td class="align-middle">
						<a 
						href="
							' . 
							create_link(base_url('admin/order/order_detail.php'), ['orid'=>$order['or_id']]) 
							. '
						" 
						class="btn btn-success"
						>
							Xem
						</a>
					</td>

					<!-- hành động -->
					<td class="align-middle">
						' . $btnOption . '
					</td>

				</tr>
				
				';
			}
		}
		
		// LẤY THÔNG TIN TỔNG QUAN VỀ ĐƠN HÀNG
		$param = [];
		$format = "";
		$resultCount = "";
		$countOrderSQL = "
			SELECT
			sum(if(db_order.or_status = 1, 1, 0)) as so_don_hoan_thanh,
			sum(if(db_order.or_status = 0, 1, 0)) as so_don_dang_cho,
			sum(if(db_order.or_status = 2, 1, 0)) as so_don_da_huy
			from db_order where 1
			";

		$countSalesSQL = "  
			SELECT
			sum(if(db_order.or_status = 1, db_order_detail.amount * db_order_detail.price, 0)) as doanh_thu_da_nhan,
			sum(if(db_order.or_status = 0, db_order_detail.amount * db_order_detail.price, 0)) as doanh_thu_du_kien,
			sum(if(db_order.or_status = 2, db_order_detail.amount * db_order_detail.price, 0)) as doanh_thu_bi_mat
			from db_order join db_order_detail on db_order.or_id = db_order_detail.or_id
			where 1
		";

		$countProductSoldSQL = "  
			SELECT
			sum(if(db_order.or_status = 1, db_order_detail.amount, 0)) as so_sp_da_ban,
			sum(if(db_order.or_status = 0, db_order_detail.amount, 0)) as so_sp_sap_ban,
			sum(if(db_order.or_status = 2, db_order_detail.amount, 0)) as so_sp_ban_hut
			from db_order join db_order_detail on db_order.or_id = db_order_detail.or_id
			where 1
		";

		if($from_day && $to_day) {
			$countOrderSQL .= " AND DATE(db_order.or_update_at) BETWEEN DATE(?) AND DATE(?)";
			$countSalesSQL .= " AND DATE(db_order.or_update_at) BETWEEN DATE(?) AND DATE(?)";
			$countProductSoldSQL .= " AND DATE(db_order.or_update_at) BETWEEN DATE(?) AND DATE(?)";

			$param = [...$param, $from_day, $to_day];
			$format .= "ss";
		}

		if($quick_search) {
			switch ($quick_search) {
				case 'day':
					$countOrderSQL .= " AND DATE(db_order.or_update_at) = CURDATE()";
					$countProductSoldSQL .= " AND DATE(db_order.or_update_at) = CURDATE()";
					$countSalesSQL .= " AND DATE(db_order.or_update_at) = CURDATE()";
				break;
				case 'week':
					$countOrderSQL .= " AND YEARWEEK(db_order.or_update_at, 1) = YEARWEEK(CURDATE(), 1)";
					$countProductSoldSQL .= " AND YEARWEEK(db_order.or_update_at, 1) = YEARWEEK(CURDATE(), 1)";
					$countSalesSQL .= " AND YEARWEEK(db_order.or_update_at, 1) = YEARWEEK(CURDATE(), 1)";
				break;
				case 'month':
					$countOrderSQL .= " AND MONTH(db_order.or_update_at) = MONTH(CURDATE()) AND YEAR(db_order.or_update_at) = YEAR(CURDATE())";
					$countProductSoldSQL .= " AND MONTH(db_order.or_update_at) = MONTH(CURDATE()) AND YEAR(db_order.or_update_at) = YEAR(CURDATE())";
					$countSalesSQL .= " AND MONTH(db_order.or_update_at) = MONTH(CURDATE()) AND YEAR(db_order.or_update_at) = YEAR(CURDATE())";
				break;

				default:
				break;
			}
		}

		
		$orderCount = s_row($countOrderSQL, $param, $format);
		$productSoldCount = s_row($countProductSoldSQL, $param, $format);
		$salesCount = s_row($countSalesSQL, $param, $format);

		$resultCount = array_merge($orderCount, $productSoldCount, $salesCount);



		// LẤY DỮ LIỆU CHI TIẾT ĐỂ VẼ BIỂU ĐỒ VỀ DOANH THU THEO TỪNG NGÀY TRONG MỘT KHOẢNG THỜI GIAN
		$param = [];
		$format = "";

		// lấy danh sách các ngày có sự thay đổi về doanh thu
		$getSalesByDaySQL = "  
		SELECT 
		date(db_order.or_update_at) as thoi_gian,
		sum(if(db_order.or_status = 1, db_order_detail.amount * db_order_detail.price, 0)) as doanh_thu_da_nhan,
		
		sum(if(db_order.or_status = 2, db_order_detail.amount * db_order_detail.price, 0)) as doanh_thu_bi_mat
		from db_order join db_order_detail on db_order.or_id = db_order_detail.or_id
		where 1
		";

		// lấy danh sách các ngày không có sự thay đổi về doanh thu
		$getDayNotChangeSQL = 'SELECT DISTINCT date(or_create_at) as tg from db_order where date(or_create_at) not in(select date(or_update_at) from db_order )';

		if($from_day && $to_day) {
			$getSalesByDaySQL .= " AND DATE(db_order.or_update_at) BETWEEN DATE(?) AND DATE(?)";
			$getDayNotChangeSQL .= " AND DATE(db_order.or_create_at) BETWEEN DATE(?) AND DATE(?)";
			$param = [...$param, $from_day, $to_day];
			$format .= "ss";
		}

		if($quick_search) {
			switch ($quick_search) {
				case 'day':
					$getSalesByDaySQL .= " AND DATE(db_order.or_update_at) = CURDATE()";
					$getDayNotChangeSQL .= " AND DATE(db_order.or_create_at) = CURDATE()";
				break;
				case 'week':
					$getSalesByDaySQL .= " AND YEARWEEK(db_order.or_update_at, 1) = YEARWEEK(CURDATE(), 1)";
					$getDayNotChangeSQL .= " AND YEARWEEK(db_order.or_create_at, 1) = YEARWEEK(CURDATE(), 1)";
				break;
				case 'month':
					$getSalesByDaySQL .= " AND MONTH(db_order.or_update_at) = MONTH(CURDATE()) AND YEAR(db_order.or_update_at) = YEAR(CURDATE())";
					$getDayNotChangeSQL .= " AND MONTH(db_order.or_create_at) = MONTH(CURDATE()) AND YEAR(db_order.or_create_at) = YEAR(CURDATE())";
				break;

				default:
				break;
			}
		}
		$getSalesByDaySQL .= " group by(date(db_order.or_update_at)) order by date(db_order.or_update_at) ASC";
		// echo $getSalesByDaySQL;

		// TRỤC THỜI GIAN = TẤT CẢ THỜI GIAN ĐẶT và THỜI GIAN UPDATE (tìm kiếm theo khoảng thời gian update vì thời gian update được đặt mặc định bằng thời gian hiện tại -> nếu chưa cập nhật thì = thời gian đặt)
		// danh sách các ngày có sự biến động về doanh thu(nhận được, mất đi) kèm doanh thu(nhận được, mất đi)
		$dataSales = db_get($getSalesByDaySQL, 0, $param, $format);

		// danh sách các ngày không có sự biến động về doanh thu: không có đơn hàng được xác nhận hoặc hủy
		
		$dayNotChange = db_get($getDayNotChangeSQL, 0, $param, $format);
		
		// cộng thêm dữ liệu những ngày không có sự thay đổi về doanh thu
		foreach ($dayNotChange as $key => $value) {
			$dataSales[] = ['thoi_gian'=>$value['tg'], 'doanh_thu_da_nhan'=>0, 'doanh_thu_da_mat'=>0];
		}

		// sắp xếp dữ liệu trả về theo thời gian tăng dần
		usort($dataSales, function($a, $b) {
			return strtotime($a['thoi_gian']) - strtotime($b['thoi_gian']);
		});
		
		// nút phân trang
		$pagination = paginateAjax($totalPage, $currentPage);


		// trả về danh sách đơn hàng(html) và nút phâm trang(html)
		$output = [
			'orders'     =>$orders,
			'pagination' =>$pagination,
			'count'      =>$resultCount, 
			'dataSales'  =>$dataSales
		];
		echo json_encode($output);	
	}