<?php
require_once '../../common.php';
require_once '../include/header.php';

if(!is_login() || !is_admin()) {
  redirect('admin/form_login.php');
}

require_once '../include/sidebar.php';
require_once '../include/navbar.php';

?>

<!-- main content -row -->
<div class="main_content bg-white row m-0 pt-4">

   <div class="col-12">
      <div class="row m-0">
         <div class="col-12 mb-3">
          <a class="" onclick="javascript:history.go(-1)" style="cursor: pointer;">
            <i class="fas fa-angle-left"></i> TRỞ LẠI
          </a>
         </div>
      </div>
   </div>

   <div class="col-12 mb-5">
      <form action="	" method="POST" id="news_add_form" enctype="multipart/form-data">

         <div class="row m-0">
            <div class="col-12">
               <div  id="backErr" class="alert-danger"></div>

               <!-- tiêu đề bài viết -->
               <div class="form-group">
                  <label for="title"><strong>Tiêu đề bài viết:</strong></label>
                  <textarea type="text" name="title" id="title"></textarea>
                  <div class="alert-danger" id="titleErr"></div>

                  <script>
                    CKEDITOR.replace('title', {
                      height: 100
                    });
                  </script>
               </div>

               <!-- mô tả -->
               <div class="form-group">
                  <label for="title"><strong>Mô tả:</strong></label>
                  <textarea type="text" name="desc" id="desc"></textarea>
                  <div class="alert-danger" id="descErr"></div>

                   <script>
                    CKEDITOR.replace('desc', {
                      height: 100
                    });
                  </script>
               </div>

               <!-- ảnh -->
               <div class="form-row">
                  <div class="form-group col-6">
                     <label for="image"><strong>Ảnh bài viết:</strong></label>
                     <input type="file" name="image" id="image">

                     <div class="previewImage"></div>
                     <script>
                        $(document).on('change', '#image', function() {
                          showImg(this, ".previewImage", 0);
                        });
                     </script>
                     <div class="alert-danger" id="imageErr"></div>
                  </div>
               </div>

               <!-- nội dung bài viết -->
              <div class="form-group">
                 <label for="content"><strong>Nội dung bài viết:</strong></label>
                 <textarea name="content" id="content"></textarea>
                 <script>
                    CKEDITOR.replace( 'content', {
                        filebrowserBrowseUrl: '../../dist/ckfinder/ckfinder.html',
                        filebrowserImageBrowseUrl: '../../dist/ckfinder/ckfinder.html?Type=Images',
                        filebrowserUploadUrl: '../../dist/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                        filebrowserImageUploadUrl: '../../dist/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                        filebrowserWindowWidth : '1000',
                        filebrowserWindowHeight : '700'
                    });
                 </script>
                 <div class="alert-danger" id="contentErr"></div> 
              </div>

              <!-- tác giả -->
              <div class="form-group">
                <label for="auth"><strong>Tác giả:</strong></label>
                <input type="text" name="auth" id="auth" class="form-control" value="<?= $user['ad_name']; ?>">
                <div class="alert-danger" id="authErr"></div>
              </div>

              <!-- trạng thái -->
              <div class="custom-control custom-switch mb-3">
                 <input
                 type  ="checkbox"
                 id    ="active"
                 name  ="active"
                 class ="custom-control-input"
                 checked
                 >
                 <label for="active" class="custom-control-label">Trạng thái</label>
              </div>

                 
            </div>

            <button class="btn_add_pro btn btn-block btn-success"><strong>THÊM</strong></button>
              
         </div>
      </div>
   </div>
</form>
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
   	$(document).on('submit', "#news_add_form", function(e) {
   		e.preventDefault();
         // //validateNewsAdd();
         // console.log($(this).serializeArray());
         addNews();

      });
   });
</script>