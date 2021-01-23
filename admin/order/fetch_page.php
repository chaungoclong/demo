<?php 

	/**
	 * 	==============================TRANG LÀM MỚI====================================
	 * 	Mô tả: làm mới nội dung bảng ở trang index sau khi có 1 hàng thay đổi(cập nhật, xóa)
	 * 	Hoạt động:
	 * 	 - nhận dữ liệu gửi sang từ ajax -> kiểm tra có action = "fetch"
	 * 	 - kiểm tra có biến yêu cầu tìm kiếm:
	 * 	 	+ rỗng => lấy hết danh sách kết quả có thể lấy
	 * 	 	+ không rỗng => lấy hết danh sách kết quả theo yêu cầu
	 * 	 - chia trang
	 * 	 	+ xác định trang hiện tại thông qua biến $_POST['currentPage'](để khi làm mới vẫn giữ được đúng trang 	ban đầu)
	 * 	 	+ tạo đường link của trang yêu cầu
	 * 	 	+ phân trang bằng hàm 
	 *   - lấy danh sách kết quả sau khi chia trang
	 *   	+ có tìm kiếm : lấy danh sách kết quả thỏa mãn yêu cầu tìm kiếm sau khi phân trang
	 *   	+ không có tìm kiếm: lấy danh sách kết quả sau khi phân trang
	 * 
	 */
	



	require_once '../../common.php';
	if(isset($_POST['action']) && $_POST['action'] == "fetch") {
		$html = '';

		$q = data_input(input_get('q'));
			$key = "%" . $q . "%";

			if($q != "") {
				$searchSQL = "
				SELECT db_order.*, db_customer.cus_name, db_customer.cus_address, db_customer.cus_phone
				FROM db_order JOIN db_customer
				ON db_order.cus_id = db_customer.cus_id
				WHERE 
			 		db_order.receiver_name LIKE(?)
				";

				$param = [$key];
				$listOrder = db_get($searchSQL, 1, $param, "s");
			} else {

				$listOrder = getListOrder();
			}
			

			// chia trang
			$totalOrder = $listOrder->num_rows;
			$orderPerPage = 5;
			$currentPage = isset($_POST['currentPage']) ? $_POST['currentPage'] : 1;
			$currentLink = create_link(base_url("admin/order/index.php"), ["page"=>'{page}', 'q'=>$q]);
			$page = paginate($currentLink, $totalOrder, $currentPage, $orderPerPage);

			// danh sách đơn hàng sau khi chia trang
			if($q != "") {
				$searchResultSQL = $searchSQL . " LIMIT ? OFFSET ?";
				$param = [$key, $page['limit'], $page['offset']];

				// danh sách  khi tìm kiếm đơn hàng sau chia chia trang
				$listOrderPaginate = db_get($searchResultSQL, 1, $param, "sii");
			} else {

				$listOrderPaginate = getListOrder($page['limit'], $page['offset']);
			}

			
			$totalOrderPaginate = $listOrderPaginate->num_rows;

			// số thứ tự
			$stt = 1 + (int)$page['offset'];

		$html .= ' 
			<table class="table table-hover table-bordered" style="font-size: 13px;">
				<tr>
					<th>STT</th>
					<th>Mã</th>
					<th>Ngày đặt</th>
					<th>Trạng thái</th>
					<th>Người đặt</th>
					<th>Người nhận</th>
					<th>Địa chỉ giao hàng</th>
					<th>Xem</th>
					<th>Hành động</th>
				</tr>
		';

		// in các đơn hàng
		
		if ($totalOrderPaginate > 0) {
			foreach ($listOrderPaginate as $key => $order) {

				$status = $order['or_status'];
				$showStatus = "";
				switch ($status) {
					case 0:
						$showStatus = "đang chờ xác nhận";
						break;
					case 1:
						$showStatus = "đã xác nhận";
						break;
					case 2:
						$showStatus = "đang chờ hủy";
						break;
					case 3:
						$showStatus = "đã hủy";
						break;
					
					default:
						echo "đang chờ xác nhận";
						break;
				}

				$button = "";
				if($order['or_status'] == 0) {
					$button .= '  
						<button
						class="btn_confirm btn btn-primary"
						id="btn_confirm_' . $order['or_id'] . '"
						data-order-id="' . $order['or_id'] . '"
						>
						Xác nhận
						</button>
					';
				} else if($order['or_status'] == 2){
					$button .= '  
						<button
						class="btn_cancel btn btn-primary"
						id="btn_cancel_' . $order['or_id'] . '"
						data-order-id="' . $order['or_id'] . '"
						>
						Hủy
						</button>
					';
				}

				$html .= '   
				<tr>
					<!-- stt -->
					<td>' . $stt++ . '</td>

					<!-- mã -->
					<td>' . $order['or_id'] . '</td>

					<!-- ngày đặt -->
					<td>' . strToTimeFormat($order['or_create_at'], "H:i:s d-m-Y") . '</td>

					<!-- trạng thái  -->
					<td id="status_order_' . $order['or_id'] . '">	
						' . $showStatus . '
					</td>

					<!-- người đặt -->
					<td>
						' . $order['cus_name'] . '
					</td>

					<!-- người nhận -->
					<td>
						' . $order['receiver_name'] . '
					</td>

					<!-- địa chỉ giao hàng -->
					<td>
						' . $order['receiver_add'] . '
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
							XEM
						</a>
					</td>

					<!-- hành động -->
					<td>
						' . $button . '
					</td>

				</tr>
				
				';
			}
		}
		$html .= '   
			</table>
		';

		$html .= $page['html'];

		echo $html;
				
	}