<!-- right-col -->
<div class="right_col bg-faded p-0">
  <!--navbar row -->
  <div class="navtop row m-0 bg-dark sticky-top">
    <!-- col -->
    <div class="col-7 d-flex align-items-center justify-content-end w-25">
    </div>
    <!-- /col -->
    <!-- col -->
    <div class="col-5">
      <ul id="action_icon" class="nav d-flex justify-content-end align-items-center">
        <!-- notice -->
        <li class="notice_box nav-item dropdown shadow">
          <a href="" class="nav-link dropdown-toggle d-flex align-items-center" data-toggle="dropdown">
            <span class="notice_index badge badge-pill badge-danger position-absolute"></span>
            <i class="fas fa-bell fa-2x mr-2"></i>
            THÔNG BÁO
          </a>
          <!-- notice dropdown -->
          <div class="notice_dropdown dropdown-menu shadow" style="background: #a8d0e6;">
          </div>
        </li>
        <!-- /notice -->
        <!-- account -->
        <?php 
          $user = getUserById($_SESSION['user_token']['id']);
         ?>
        <li class="account_box nav-item dropdown">
          <a href="" class="nav-link dropdown-toggle" data-toggle="dropdown">
            <img src="<?= base_url('image/' . $user['ad_avatar']); ?>" alt="" width="30px" height="30px" class="acc_img_sidebar" style="border-radius: 50%;">
            <span class="acc_name_sidebar"><?= $user['ad_name']; ?></span>
          </a>
          <!-- account dropdown -->
          <div class="account_dropdown dropdown-menu shadow">
            <a href="<?= base_url('admin/profile.php'); ?>" class="dropdown-item">
              <i class="fas fa-user"></i>
              Tài khoản của tôi
            </a>
            <a href="<?= base_url('admin/log_out.php'); ?>" class="dropdown-item">
              <i class="fas fa-sign-out-alt"></i>
              Đăng xuất
            </a>
          </div>
        </li>
        <!-- /account -->
      </ul>
    </div>
    <!-- /col -->
  </div>
  <!-- /row -->
  <script>
    $(function() {
      fetchNotify();

      // lấy thông báo khi có đơn hàng mới (người dùng trang khách gửi lên)
      channel.bind_global(function(data) {
        fetchNotify();
      });

      // thay đổi trạng thái của 1 thông báo thành đã đọc khi nhấn vào nó
      $(document).on('click', '.notice_item', function(e) {
          readed($(this).data('notify-id'));
        });
     });

    // hàm lấy thông báo
    function fetchNotify() {
      let url = '<?= base_url('admin/notification/fetch_notification.php'); ?>';
      let result = sendAJax(url, 'post', 'json', {action: "fetch"});

      $('.notice_dropdown').html(result.html);
      $('.notice_index').text(result.unread);
    }

    // hàm thay đổi trạng thái của thông báo
    function readed(id) {
      let data = {id: id, action: "read"};
      let url = '<?= base_url('admin/notification/process_notification.php'); ?>';
      let result = sendAJax(url, 'post', 'json', data);

      if(result.status) {
        fetchNotify();
      }
    }
  </script>