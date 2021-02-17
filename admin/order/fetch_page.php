<?php 
	require_once '../../common.php';
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
		$orPerPage = 5;
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
						$showStatus = "<span class='badge badge-pill badge-primary p-1'>đang chờ xác nhận</span>";
						break;
					case 1:
						$showStatus = "<span class='badge badge-pill badge-success p-1'>đã xác nhận</span>";
						break;
					case 2:
						$showStatus = "<span class='badge badge-pill badge-danger p-1'>đã hủy</span>";
						break;
					default:
						$showStatus = "<span class='badge badge-pill badge-secondary p-1'>không xác định</span>";
						break;
				}

				// hiển thị nút duyệt, hủy
				$btnOption = "";
				if($order['or_status'] == 0) {
					$btnOption .= '  
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
					';
				}

				$orders .= '   
				<tr>
					<!-- mã -->
					<td>' . $order['or_id'] . '</td>

					<!-- ngày đặt -->
					<td>' . strToTimeFormat($order['or_create_at'], "d-m-Y") . '</td>

					<!-- trạng thái  -->
					<td id="status_order_' . $order['or_id'] . '">	
						' . $showStatus . '
					</td>

					<!-- người đặt -->
					<td>
						<p><strong class="mr-1">Tên:</strong>' . $order['cus_name'] . '</p>
						<p><strong class="mr-1">Địa chỉ:</strong>' . $order['cus_address'] . '</p>
						<p><strong class="mr-1">SĐT:</strong>' . $order['cus_phone'] . '</p>
					</td>

					<!-- người nhận -->
					<td>
						<p><strong class="mr-1">Tên:</strong>' . $order['receiver_name'] . '</p>
						<p><strong class="mr-1">Địa chỉ:</strong>' . $order['receiver_add'] . '</p>
						<p><strong class="mr-1">SĐT:</strong>' . $order['receiver_phone'] . '</p>
					</td>

					<!-- xem -->
					<td>
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
					<td>
						' . $btnOption . '
					</td>

				</tr>
				
				';
			}
		}
		
		// nút phân trang
		$pagination = paginateAjax($totalPage, $currentPage);

		// trả về danh sách đơn hàng(html) và nút phâm trang(html)
		$output = ['orders'=>$orders, 'pagination'=>$pagination];
		echo json_encode($output);	
	}