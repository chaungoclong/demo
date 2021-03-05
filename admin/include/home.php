<!-- main content -row -->
<div class="main_content bg-white row m-0 pt-4">
  <!-- xin chào -->
  <!-- row -->
  <div class="row m-0 w-100 mb-4">
    <!-- col -->
    <div class="col-12">
      <div class="card card-body shadow">
        <?php 
          $user = getUserById($_SESSION['user_token']['id']);
         ?>
        <h3>Xin chào <?= $user['ad_name']; ?></h3>
        <h5>chào mừng bạn đến với trang quản trị</h5>
      </div>
    </div>
    <!-- /col -->
  </div>
  <!-- /row -->
  <div class="row m-0 w-100">
    <div class="col-12">
     <h5 class="text-primary">LỐI TẮT</h5>
     <hr>
    </div>
    <div class="col-12">
      <div class="card-deck mb-5">
        <div class="card card-body shadow text-center">
          <h5 class="badge badge-success badge-pill p-2 shadow">SẢN PHẨM</h5>
          <h2><?= s_cell("SELECT count(*) from db_product"); ?></h2>
          <a href="product/" class="badge badge-primary"><i>Xem</i></a>
        </div>

        <div class="card card-body shadow text-center">
          <h5 class="badge badge-warning badge-pill p-2 shadow">HÃNG</h5>
          <h2><?= s_cell("SELECT count(*) from db_brand"); ?></h2>
          <a href="brand/" class="badge badge-primary"><i>Xem</i></a>
        </div>

        <div class="card card-body shadow text-center">
          <h5 class="badge badge-info badge-pill p-2 shadow">DANH MỤC</h5>
          <h2><?= s_cell("SELECT count(*) from db_category"); ?></h2>
          <a href="category/" class="badge badge-primary"><i>Xem</i></a>
        </div>

        <div class="card card-body shadow text-center">
          <h5 class="badge badge-secondary badge-pill p-2 shadow">ĐƠN HÀNG</h5>
          <h2><?= s_cell("SELECT count(*) from db_order"); ?></h2>
          <a href="order/" class="badge badge-primary"><i>Xem</i></a>
        </div>
        <div class="card card-body shadow text-center">
          <h5 class="badge badge-danger badge-pill p-2 shadow">NHÂN VIÊN</h5>
          <h2><?= s_cell("SELECT count(*) from db_admin"); ?></h2>
          <a href="user/" class="badge badge-primary"><i>Xem</i></a>
        </div>
        <div class="card card-body shadow text-center">
          <h5 class="badge badge-dark badge-pill p-2 shadow">KHÁCH HÀNG</h5>
          <h2><?= s_cell("SELECT count(*) from db_customer"); ?></h2>
          <a href="customer" class="badge badge-primary"><i>Xem</i></a>
        </div>
      </div>
    </div>
  </div>


  <div class="row m-0 w-100 my-3 py-3">
    <div class="col-12 w-100">
       <h5 class="text-primary">THỐNG KÊ DOANH THU</h5>
      <hr>
      <div class="d-flex justify-content-between align-items-center">

        <!-- tìm kiếm theo khoảng -->
        <form class="form-inline mr-5 border p-1 bg-light" id="range_time">
          <input type="text" class="form-control mr-sm-2" placeholder="từ ngày(dd-mm-yy)" id="from_day" required autocomplete="off">

          <input type="text" class="form-control mr-sm-2" placeholder="đến ngày(dd-mm-yy)" id="to_day" required autocomplete="off">

          <!-- lưu trữ chế độ tìm theo thời gian(theo khoảng hay tìm kiếm nhanh có sẵn) -->
          <input type="hidden" id="mode_filter" value="0">
          <button type="submit" class="btn btn-warning"><i class="fas fa-filter text-danger"></i></button>
        </form>

        <!-- tìm kiếm nhanh có sẵn -->

        <div class="bg-light border p-1 d-flex align-items-center">
          <label class="d-inline-block m-0 mr-1 text-primary"><strong>CHỌN NHANH: </strong></label>
          <select id="quick_search" class="custom-select d-inline-block" style="width: 135px;">
            <option value="" hidden>Lựa chọn</option>
            <option value="day" selected>Hôm nay</option>
            <option value="week">Tuần này</option>
            <option value="month">Tháng này</option>
          </select>
        </div>

        <script>
          $(function() {
            $('#from_day, #to_day').datepicker({
              dateFormat: "dd-mm-yy"
            });

            $(document).on('change', '#from_day', function() {
              console.log($(this).val());
            });

              // lọc from - to
            $(document).on('submit', '#range_time', function(e) {
              e.preventDefault();

              // thay đổi chế độ tìm thành tìm theo khoảng thời gian
              $('#mode_filter').val("1");

              // đặt giá trị của quick search = '' -> hiện chữ lựa chọn -> mục đích:
              // khi người dùng tìm kiếm theo khoảng nếu không đặt giá trị của quick search về '' thì khi người dùng quay lại tìm kiếm nhanh sẽ phải lựa chọn lựa chọn khác với lựa chọn đang hiển thị thì mới tìm kiếm được(lý do: không có nút nhấn để tìm kiếm cho quick search vì thực hiện tìm kiếm bằng cách bắt sự kiện change)
              // -> kết luận: phải đặt giá trị của quick search về '' khi tìm kiếm theo khoảng để có thể tìm kiếm nhanh ngay khi quay lại từ tìm kiếm theo khoảng.
              $('#quick_search').val('');
              let from_day_str = $('#from_day').val();
              let to_day_str   = $('#to_day').val();
              let from_day     = Date.parse(formatDate(from_day_str));
              let to_day       = Date.parse(formatDate(to_day_str));
              let current_time = Date.parse(new Date());

              if(!isDate(formatDate(from_day_str)) || !isDate(formatDate(to_day_str))) {
                  alert("Ngày sai định dạng");
              } else if(to_day < from_day) {
                  alert("Ngày sau phải lớn hơn hoặc bằng ngày trước");
             } else if(from_day > current_time && to_day > current_time) {
                 alert("Thời gian chưa xảy ra");
             } else {
                 // fetch_page
                 fetchPage(1);
             }
            });

            // lọc nhanh
            $(document).on('change', "#quick_search", function() {
                $('#mode_filter').val("0");
                fetchPage(1);
            });
          });
        </script>
      </div>
      <hr>

      <!-- biểu đồ -->
      <div class="box-chart">
         <div id="salesChart"  class="mb-5" style="width: 100%; height: 400px;"></div>
         <div class="row">
           <div class="col-4">
              <div id="orderChart"  class="mb-5" style="width: 100%; height: 400px;"></div>
           </div>
           <div class="col-4">
             <div id="productSoldChart"  class="mb-5" style="width: 100%; height: 400px;"></div>
           </div>
           <div class="col-4">
             <div id="sumSalesChart"  class="mb-5" style="width: 100%; height: 400px;"></div>
           </div>
         </div>
      </div>

      <!-- BẢNG THỐNG KÊ  -->
      <h5 class="text-primary">BẢNG THỐNG KÊ</h5>
      <hr>
      <div class="statistical_table d-flex mb-5">
        <table class="table table-hover table-bordered mr-2">
          <caption style="caption-side:top;">ĐƠN HÀNG</caption>
          <tr>
            <td>Số đơn hoàn thành</td>
            <td id="so_don_hoan_thanh"></td>
          </tr>
          <tr>
            <td>Số đơn đang chờ</td>
            <td id="so_don_dang_cho" id="so_don_dang_cho"></td>
          </tr>
          <tr>
            <td>Số đơn đã hủy</td>
            <td id="so_don_da_huy"></td>
          </tr>
        </table>

        <table class="table table-hover table-bordered mr-2">
          <caption style="caption-side:top;">SẢN PHẨM</caption>
          <tr>
            <td>Số sản phẩm đã bán</td>
            <td id="so_sp_da_ban"></td>
          </tr>
          <tr>
            <td>Số sản phẩm sắp bán</td>
            <td id="so_sp_sap_ban"></td>
          </tr>
          <tr>
            <td>Số sản phẩm bán hụt</td>
            <td id="so_sp_ban_hut"></td>
          </tr>
        </table>


        <table class="table table-hover table-bordered">
          <caption style="caption-side:top;">DOANH THU</caption>
          <tr>
            <td>Doanh thu đã nhận</td>
            <td id="doanh_thu_da_nhan"></td>
          </tr>
          <tr>
            <td>Doanh thu dự kiến</td>
            <td id="doanh_thu_du_kien"></td>
          </tr>
          <tr>
            <td>Doanh thu bị mất</td>
            <td id="doanh_thu_bi_mat"></td>
          </tr>
        </table>
      </div>

      <!-- danh sách đơn hàng lọc theo thời gian -->
      <h5 class="text-primary">THỐNG KÊ CHI TIẾT</h5>
      <hr>
      <div class="box_order">
        <!-- thanh tìm kiếm -->
        <div class="row m-0 mb-3">
          <div class="col-12 p-2 d-flex justify-content-between align-items-center bg-light border">
            <!-- lọc-->
            <div class="filter d-flex">
              <!-- sắp xếp -->
              <select id="sort" class="custom-select mr-3">
                <option value="1" selected>Ngày tạo: Mới nhất</option>
                <option value="2">Ngày tạo: Cũ nhất</option>
              </select>

              <!-- tìm kiếm tên , id đơn hàng -->
              <input type="text" class="form-control" id="search" placeholder="Search...">
            </div>

            <!-- số hàng hiển thị -->
            <div class="d-flex align-items-center">

              <?php $option = [5, 10, 25, 50, 100]; ?>
              <select class="custom-select" id="number_of_rows" data-toggle="tooltip" title="số hàng hiển thị">
                <?php foreach ($option as $key => $each): ?>
                  <option value="<?= $each; ?>"> <?= $each; ?> </option>
                <?php endforeach ?>
              </select>
            </div>
          </div>
        </div>

        

        <ul class="nav nav-tabs px-2" role="tablist" id="list_name_tab">
          <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#all" data-status="all">TẤT CẢ <span class="badge badge-secondary" id="count_all">10</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#pending" data-status="pending">ĐANG CHỜ <span class="badge badge-primary" id="count_pending">10</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#success" data-status="success">ĐÃ XỬ LÝ <span class="badge badge-success" id="count_success">10</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#fail" data-status="fail">ĐÃ HỦY <span class="badge badge-danger" id="count_fail">10</span></a>
          </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
          <div class="tab-pane p-2 active" id="all">
            <table class="table table-hover table-bordered" style="font-size: 15px;">
              <thead class="thead-light">
                <tr>
                  <th class="align-middle">ID</th>
                  <th class="align-middle">NGÀY ĐẶT</th>
                  <th class="align-middle">TỔNG TIỀN</th>
                  <th class="align-middle">TRẠNG THÁI</th>
                  <th class="align-middle">NGƯỜI ĐẶT</th>
                  <th class="align-middle">NGƯỜI NHẬN</th>
                  <th class="align-middle">XEM</th>
                  <th class="align-middle">TÙY CHỌN</th>
                </tr>
              </thead>

              <tbody class="list_order">
              </tbody>
            </table>
            <div class="page"></div>
          </div>

          <!-- ĐANG CHỜ -->
          <div class="tab-pane p-2" id="pending">
            <table class="table table-hover table-bordered" style="font-size: 15px;">
              <thead class="thead-light">
                <tr>
                  <th class="align-middle">ID</th>
                  <th class="align-middle">NGÀY ĐẶT</th>
                  <th class="align-middle">TỔNG TIỀN</th>
                  <th class="align-middle">TRẠNG THÁI</th>
                  <th class="align-middle">NGƯỜI ĐẶT</th>
                  <th class="align-middle">NGƯỜI NHẬN</th>
                  <th class="align-middle">XEM</th>
                  <th class="align-middle" width="115px">HÀNH ĐỘNG</th>
                </tr>
              </thead>

              <tbody class="list_order">
              </tbody>
            </table>
            <div class="page"></div>
          </div>

          <!-- ĐÃ XỬ LÝ -->
          <div class="tab-pane p-2" id="success">
            <table class="table table-hover table-bordered" style="font-size: 15px;">
              <thead class="thead-light">
                <tr>
                  <th class="align-middle">ID</th>
                  <th class="align-middle">NGÀY ĐẶT</th>
                  <th class="align-middle">TỔNG TIỀN</th>
                  <th class="align-middle">TRẠNG THÁI</th>
                  <th class="align-middle">NGƯỜI ĐẶT</th>
                  <th class="align-middle">NGƯỜI NHẬN</th>
                  <th class="align-middle">XEM</th>
                  <th class="align-middle">TÙY CHỌN</th>
                </tr>
              </thead>

              <tbody class="list_order">
              </tbody>
            </table>
            <div class="page"></div>
          </div>

          <!-- ĐÃ HỦY -->
          <div class="tab-pane p-2" id="fail">
            <table class="table table-hover table-bordered" style="font-size: 15px;">
              <thead class="thead-light">
                <tr>
                  <th class="align-middle">ID</th>
                  <th class="align-middle">NGÀY ĐẶT</th>
                  <th class="align-middle">TỔNG TIỀN</th>
                  <th class="align-middle">TRẠNG THÁI</th>
                  <th class="align-middle">NGƯỜI ĐẶT</th>
                  <th class="align-middle">NGƯỜI NHẬN</th>
                  <th class="align-middle">XEM</th>
                  <th class="align-middle">TÙY CHỌN</th>
                </tr>
              </thead>

              <tbody class="list_order">
              </tbody>
            </table>
            <div class="page"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<!-- /right-col -->
</div>
<!-- /wrapper -row -->
</main>
</body>
</html>
<script>
  $(function() {

    channel.bind('check_out', function(data) {
      fetchPage(1);
    });

    channel.bind('cancel_order', function(data) {
      fetchPage(1);
    });

    fetchPage(1);

    // lấy danh sách đơn hàng khi nhập tìm kiếm
    $(document).on('input', '#search', function() {
      fetchPage(1);
    });

    // lấy danh sách đơn hàng khi sắp xếp
    $(document).on('change', '#sort', function() {
      fetchPage(1);
    });

    // lấy danh sách đơn hàng khi thay đổi số hàng hiển thị
    $(document).on('change', '#number_of_rows', function() {
      fetchPage(1);
    });

    // lấy danh sách đơn hàng khi chuyển tab
    $(document).on('click', '#list_name_tab .nav-item', function() {
      fetchPage(1);
    });

    // lấy danh sách đơn hàng khi chuyển trang
    $(document).on('click', '.page-item', function() {
      let currentPage = parseInt($(this).data("page-number"));
      if(isNaN(currentPage)) {
        currentPage = 1;
      }
      fetchPage(currentPage);
      $('html, body').scrollTop($('.box_order').offset().top);
    });

    // duyệt 1 đơn hàng
    $(document).on('click', '.btn_confirm', function() {
      changeStatus(this.id, "confirm");
      sendEmailWhenChangeStatusOrder($(this).data('order-id'), 'email_ad_confirm_order');
    });

    // hủy 1 đơn hàng
    $(document).on('click', '.btn_cancel', function() {
      changeStatus(this.id, "cancel");
      sendEmailWhenChangeStatusOrder($(this).data('order-id'), 'email_ad_cancel_order');
    });
  });

  // hàm lấy danh sách đơn hàng
  function fetchPage(currentPage = 1) {
    // console.log($('#number_of_rows').val());
    let q = "%" + $('#search').val().trim() + "%";
    let sort = $('#sort').val();
    let status = $('#list_name_tab .nav-link.active').data('status');
    let numRows = $('#number_of_rows').val();
    let action = "fetch";
    let from_day = "";
    let to_day = "";
    let quick_search = "";
    let mode = $('#mode_filter').val();

    if(mode == 1) {
      from_day = $('#from_day').val();
      to_day = $('#to_day').val();
    } else {
      quick_search = $('#quick_search').val();
    }

    // dữ liệu gửi sang server
    let data = {
      q : q, status: status, numRows: numRows, sort: sort, currentPage: currentPage, action: action
      , quick_search: quick_search, from_day: from_day, to_day: to_day
    };

    // dữ liệu nhận về
    let result = sendAJax("fetch_page.php", "post", "json", data);
    $('.list_order').html(result.orders);
    $('.page').html(result.pagination);

    // HIỂN THỊ BẢNG THỐNG KÊ TỔNG QUÁT
    let count = result.count;
    let formatCurrency = new Intl.NumberFormat('vi-VN', {style: 'currency', currency: 'VND'});
    let keys = Object.keys(count);

    // in dữ liệu vào từng bảng
    $(keys).each(function() {
      if(this == "doanh_thu_da_nhan" || this == "doanh_thu_du_kien" || this == "doanh_thu_bi_mat") {
         $(`#${this}`).text(formatCurrency.format(count[this]));
      } else {
        $(`#${this}`).text(count[this]);
      }
    });

    // VẼ BIẾU ĐỒ
    let dataSales = result.dataSales; 
    let drawn = [
        ["Ngày", "đã nhận", "đã mất"]
    ];

    // xử lý dữ liệu
    $(dataSales).each(function() {

      // danh sách giá trị của object -> array
      let eachRow =  Object.values(this);

       // thay null = 0
      eachRow = eachRow.map(val=>{
        return val === null ? 0 : val;
      });

      // thêm hàng vào bảng
      drawn.push(eachRow);
    });
    console.log(drawn.length);
    console.log(drawn);
    if(drawn.length == 1) {
      drawn.push(["0", 0, 0, 0]);
    }
   
   // vẽ biểu đồ
   google.charts.load('current', {'packages':['corechart']});
   google.charts.setOnLoadCallback(drawSalesChart);
   google.charts.setOnLoadCallback(drawOrderChart);
   google.charts.setOnLoadCallback(drawProductSoldChart);
   google.charts.setOnLoadCallback(drawSumSalesChart);

   function drawSalesChart() {
    var data = google.visualization.arrayToDataTable(drawn);

    var options = {
      title: 'Doanh thu',
      hAxis: {title: 'Thời gian',  titleTextStyle: {color: '#333'}},
      vAxis: {minValue: 0}
    };

    var chart = new google.visualization.AreaChart($('#salesChart')[0]);
    chart.draw(data, options);
  }

  function drawOrderChart() {
    let so_don_hoan_thanh = !isNaN(parseInt(count['so_don_hoan_thanh'])) ? parseInt(count['so_don_hoan_thanh']) : 0 ;
    let so_don_da_huy     = !isNaN(parseInt(count['so_don_da_huy'])) ? parseInt(count['so_don_da_huy']) : 0 ;
    let so_don_dang_cho   = !isNaN(parseInt(count['so_don_dang_cho'])) ? parseInt(count['so_don_dang_cho']) : 0 ;
    var data = google.visualization.arrayToDataTable([
      ['Task', 'Hours per Day'],
      ['Hoàn thành',     so_don_hoan_thanh],
      ['Đang chờ',      so_don_dang_cho],
      ['Đã hủy',  so_don_da_huy]
      ]);

    var options = {
          title: 'Cơ cấu đơn hàng',
          pieHole: 0.4,
    };

    var chart = new google.visualization.PieChart($('#orderChart')[0]);
    chart.draw(data, options);
  }

  function drawProductSoldChart() {
    let so_sp_da_ban = !isNaN(parseInt(count['so_sp_da_ban'])) ? parseInt(count['so_sp_da_ban']) : 0 ;
    let so_sp_ban_hut     = !isNaN(parseInt(count['so_sp_ban_hut'])) ? parseInt(count['so_sp_ban_hut']) : 0 ;
    let so_sp_sap_ban   = !isNaN(parseInt(count['so_sp_sap_ban'])) ? parseInt(count['so_sp_sap_ban']) : 0 ;
    var data = google.visualization.arrayToDataTable([
      ['Task', 'Hours per Day'],
      ['Đã bán',     so_sp_da_ban],
      ['Sắp bán',      so_sp_sap_ban],
      ['Bị hụt',  so_sp_ban_hut]
      ]);

    var options = {
          title: 'Cơ cấu sản phẩm',
          pieHole: 0.4,
    };

    var chart = new google.visualization.PieChart($('#productSoldChart')[0]);
    chart.draw(data, options);
  }

  function drawSumSalesChart() {
    let doanh_thu_da_nhan = !isNaN(parseInt(count['doanh_thu_da_nhan'])) ? parseInt(count['doanh_thu_da_nhan']) : 0 ;
    let doanh_thu_bi_mat     = !isNaN(parseInt(count['doanh_thu_bi_mat'])) ? parseInt(count['doanh_thu_bi_mat']) : 0 ;
    let doanh_thu_du_kien   = !isNaN(parseInt(count['doanh_thu_du_kien'])) ? parseInt(count['doanh_thu_du_kien']) : 0 ;
    var data = google.visualization.arrayToDataTable([
      ['Task', 'Hours per Day'],
      ['Đã nhận',     doanh_thu_da_nhan],
      ['Dự kiến',      doanh_thu_du_kien],
      ['Bị mất',  doanh_thu_bi_mat]
      ]);

    var options = {
          title: 'Cơ cấu doanh thu',
          pieHole: 0.4,
    };

    var chart = new google.visualization.PieChart($('#sumSalesChart')[0]);
    chart.draw(data, options);
  }
   
    // đặt giá trị cho các nút hiển thị số lượng các loại đơn hàng
    let num_pending = parseInt(result.count['so_don_dang_cho']);
    num_pending = !isNaN(num_pending) ? num_pending : 0;

    let num_success = parseInt(result.count['so_don_hoan_thanh']);
    num_success = !isNaN(num_success) ? num_success : 0;

    let num_fail = parseInt(result.count['so_don_da_huy']);
    num_fail = !isNaN(num_fail) ? num_fail : 0;

    $('#count_all').text(num_pending + num_success + num_fail);
    $('#count_pending').text(num_pending);
    $('#count_success').text(num_success);
    $('#count_fail').text(num_fail);
  }

  // hàm thay đổi trạng thái của đơn hàng
  function changeStatus(btnID, action) {
    let orID = $(`#${btnID}`).data('order-id');
    let data = {orID: orID, action: action};
    let result = sendAJax("order/process_order.php", "post", "json", data);
    if(!result.ok) {
      alert("KHÔNG THỂ CẬP NHẬT TRẠNG THÁI ĐƠN HÀNG");
    }
    // cập nhật lại sau khi thay đổi
    let currentPage = parseInt($('li.page-item.active').data('page-number'));
    if(isNaN(currentPage)) {
      currentPage = 1;
    };
    fetchPage(currentPage);
  }

  function sendEmailWhenChangeStatusOrder(orderID, action) {
    let data = {orderID: orderID, action: action};
    let url = '<?= base_url("admin/email/process_email.php"); ?>';
    $.post(url, data);
  }
</script> 