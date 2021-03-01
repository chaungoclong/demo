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
		
		// đếm số lượng các loại đơn hàng
		$countSQL = "SELECT COUNT(IF(or_status = 1, 1, NULL)) as success,  COUNT(IF(or_status = 0, 1, NULL)) AS pending,  COUNT(IF(or_status = 2, 1, NULL)) AS fail FROM db_order";
		$resultCount = s_row($countSQL);
		
		// nút phân trang
		$pagination = paginateAjax($totalPage, $currentPage);

		// trả về danh sách đơn hàng(html) và nút phâm trang(html)
		$output = ['orders'=>$orders, 'pagination'=>$pagination, 'count'=>$resultCount];
		echo json_encode($output);	
	}