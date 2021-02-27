<?php
require_once '../../common.php';
require_once '../include/header.php';

if(!is_login() || !is_admin()) {
  redirect('admin/form_login.php');
}

require_once '../include/sidebar.php';
require_once '../include/navbar.php';

$sldID = data_input(input_get('sldid'));
if(int($sldID) === false) {
  die("<h1 class='text-center mt-5 text-danger'>KHÔNG TÌM THẤY TRANG</h1>");
}
// slide cần sửa
$slide = getSlideByID($sldID);
if(!$slide) {
  die("<h1 class='text-center mt-5 text-danger'>KHÔNG TÌM THẤY SLIDE</h1>");
}

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
            <a class="" onclick="window.location='<?= base_url('admin/slider/'); ?>'" style="cursor: pointer;">
              <i class="fas fa-angle-left"></i> TRỞ LẠI
            </a>
          </div>
         </div>
      </div>
   </div>

   <div class="col-12 mb-5">
      <form action="  " method="POST" id="slide_edit_form" enctype="multipart/form-data">

         <!-- id slide -->
         <input type="hidden" name="sldID" id="sldID" value="<?= $slide['sld_id']; ?>">
         

         <div class="row m-0">
            <div class="col-12">
               <div  id="backErr" class="alert-danger"></div>

               <!-- tên slide -->
               <div class="form-group">
                  <label for="cat"><strong>Danh mục:</strong></label>

                  <select name="cat" class="custom-select" id="cat">
                    <option value="0" disabled hidden selected="">Chọn danh mục</option>
                    <?php foreach ($listCategory as $key => $category): ?>
                      <option 
                        value="<?= $category['cat_id']; ?>" 
                        <?= $category['cat_id'] == $slide['cat_id'] ? "selected" : ""; ?>
                      >
                        <?= $category['cat_name']; ?>
                      </option>
                    <?php endforeach ?>
                  </select>

                  <div class="alert-danger" id="catErr"></div>
               </div>

               <!-- đường link đi đến khi nhấn vào slide -->
               <div class="form-group">
                <label for="link"><strong>Đi đến:</strong></label>
                <input type="text" class="form-control" placeholder="link..." name="link" id="link" value="<?= $slide['sld_link']; ?>">
                <div class="alert-danger" id="linkErr"></div>
              </div>

             <!-- ảnh đại diện -->
            <div class="form-group">
               <label for="slide"><strong>Ảnh slide:</strong></label>
               <input type="file" name="slide[]" id="slide">

               <!-- old Slide -->
               <input type="hidden" name="oldSlide" value="<?= $slide['sld_image']; ?>">

               <div class="previewSlide">
                 <img src="../../image/<?= $slide['sld_image']; ?>" alt="">
               </div>
               <script>
                  $(document).on('change', '#slide', function() {
                    showImg(this, ".previewSlide", 1);
                  });
               </script>
               <div class="alert-danger" id="slideErr"></div>
            </div>

            <!-- vị trí -->
            <div class="form-group">
              <label for="pos"><strong>Vị trí:</strong></label>
              <br>
              <?php $maxPos = (int)lastPostion(); ?>

              <!-- vị trí cũ -->
              <input type="hidden" name="oldPos" value="<?= $slide['sld_pos'] ?>">

              <!-- vị trí mới -->
              <select name="newPos" id="newPos" class="custom-select w-25">
                <?php for ($i = 1; $i <= $maxPos ; $i++): ?> 
                  <option value="<?= $i; ?>" <?= $i == $slide['sld_pos'] ? "selected" : ""; ?>>
                    <?= $i; ?>
                  </option>
                <?php endfor ?>
              </select>
            </div>


            <button class="btn_add_sld btn btn-block btn-success"><strong>LƯU</strong></button>
              
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
    $(document).on('submit', "#slide_edit_form", function(e) {
      e.preventDefault();
        // validateSlideAdd();
         editSlide();

      });
   });
</script>