<?php
require_once '../../common.php';
require_once '../include/header.php';

if(!is_login() || !is_admin()) {
  redirect('admin/form_login.php');
}

require_once '../include/sidebar.php';
require_once '../include/navbar.php';


// danh sách các thể loại
$listCategory = db_fetch_table('db_category', 0);
?>


<!-- main content -row -->
<div class="main_content bg-white row m-0 pt-4">

   <div class="col-12">
      <div class="row m-0">
       <div class="col-12">
         <!-- tiêu đề -->
         <div class="d-flex justify-content-between align-items-center mb-2">
          <a class="" onclick="javascript:history.go(-1)" style="cursor: pointer;">
            <i class="fas fa-angle-left"></i> TRỞ LẠI
          </a>
        </div>
      </div>
      </div>
   </div>

   <div class="col-12 mb-5">
      <form action="	" method="POST" id="slide_add_form" enctype="multipart/form-data">
        

         <div class="row m-0">
            <div class="col-12">
               <div  id="backErr" class="alert-danger"></div>

               <!-- danh mục slide -->
               <div class="form-group">
                  <label for="cat"><strong>Danh mục:</strong></label>

                  <select name="cat" class="custom-select" id="cat">
                    <option value="0" disabled hidden selected="">Chọn danh mục</option>
                    <?php foreach ($listCategory as $key => $category): ?>
                      <option value="<?= $category['cat_id']; ?>">
                        <?= $category['cat_name']; ?>
                      </option>
                    <?php endforeach ?>
                  </select>

                  <div class="alert-danger" id="catErr"></div>
               </div>

               <!-- đường link đi đến khi nhấn vào slide -->
               <div class="form-group">
                <label for="link"><strong>Đi đến:</strong></label>
                <input type="text" class="form-control" placeholder="link..." name="link" id="link">
                <div class="alert-danger" id="linkErr"></div>
              </div>

             <!-- ảnh đại diện -->
            <div class="form-group">
               <label for="slide"><strong>Ảnh slide:</strong></label>
               <input type="file" name="slide[]" id="slide" multiple>

               <div class="previewSlide"></div>
               <script>
                  $(document).on('change', '#slide', function() {
                    showImg(this, ".previewSlide");
                  });
               </script>
               <div class="alert-danger" id="slideErr"></div>
            </div>

            <button class="btn_add_sld btn btn-block btn-success"><strong>THÊM</strong></button>
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
   	$(document).on('submit', "#slide_add_form", function(e) {
   		e.preventDefault();
        // validateSlideAdd();
        addSlide();

      });
   });
</script>