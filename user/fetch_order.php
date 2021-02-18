<?php 
	require_once '../common.php';
	if(!empty($_POST['action']) && $_POST['action'] == "fetch") {
		$output = ["orders"=>"", "pagination"=>""];

		if(!empty($_POST['cusID'])) {
			$cusID = (int)$_POST['cusID'];

			// lấy danh sách các đơn hàng của khách hàng
			$getOrderSQL = "SELECT * FROM db_order WHERE cus_id = ?";
			$param = [$cusID];
			$format = "i";

			// tìm kiếm theo id đơn hàng
			$q = !empty($_POST['q']) ? $_POST['q'] : "%%";
			$getOrderSQL .= " AND CONCAT(or_id) LIKE(?)";
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
							<a type="button"
							class="btn_cancel btn btn-danger text-white"
							id="btn_cancel_' . $order['or_id'] . '"
							data-order-id="' . $order['or_id'] . '"
							>
							 Hủy
							</a>
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
								create_link(base_url('user/order_detail.php'), ['orid'=>$order['or_id']]) 
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

			$pagination = paginateAjax($totalPage, $currentPage);
			$output = ["orders"=>$orders, "pagination"=>$pagination];
		}

		echo json_encode($output);
	}