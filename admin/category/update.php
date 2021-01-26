<?php
require_once '../../common.php';
require_once '../include/header.php';

if(!is_login() || !is_admin()) {
  redirect('admin/form_login.php');
}

require_once '../include/sidebar.php';
require_once '../include/navbar.php';


// thể loại
$categoryID = data_input(input_get("catid"));
$category   = getCategoryByID($categoryID);
?>

<!-- main content -row -->
<div class="main_content bg-white row m-0 pt-4">

   <div class="col-12">
      <div class="row m-0">
         <div class="col-12">
            <h5>THÊM DANH MỤC</h5>
            <p class="mb-4">Thêm danh mục mới</p>
            <hr>
         </div>
      </div>
   </div>

   <div class="col-12 mb-5">
      <form action="	" method="POST" id="category_edit_form" enctype="multipart/form-data">
         <!-- previous link -->
         <input type="hidden" name="catID" value = "<?= $category['cat_id']; ?>">
         <div class="row m-0">
            <div class="col-12">
               <div  id="backErr" class="alert-danger"></div>

               <!-- tên sản phẩm -->
               <div class="form-group">
                  <label for="name"><strong>Tên danh mục:</strong></label>
                  <input type="text" class="form-control" name="name" id="name" value="<?= $category['cat_name']; ?>">
                  <div class="alert-danger" id="nameErr"></div>
               </div>

             <!-- ảnh đại diện -->
             <div class="form-row">
                <div class="form-group col-6">
                   <label for="image"><strong>Logo danh mục:</strong></label>
                   <input type="file" name="image" id="image">
                   <input type="hidden" name="oldImage" value="<?= $category['cat_logo']; ?>">

                   <div class="previewImage">
                     <img src="../../image/<?= $category['cat_logo']; ?>" class="img-fluid">
                   </div>
                   <script>
                      $(document).on('change', '#image', function() {
                        showImg(this, ".previewImage", 0);
                      });
                   </script>
                   <div class="alert-danger" id="imageErr"></div>
                </div>
             </div>

            <!-- trạng thái -->
            <div class="custom-control custom-switch mb-3">
               <input
               type  ="checkbox"
               id    ="active"
               name  ="active"
               class ="custom-control-input"
               <?= $category['cat_active'] ? "checked" : ""; ?>
               >
               <label for="active" class="custom-control-label">Trạng thái</label>
            </div>

            <button class="btn_add_cat btn btn-block btn-success"><strong>LƯU</strong></button>
              
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
   	$(document).on('submit', "#category_edit_form", function(e) {
   		e.preventDefault();
         editCategory();

      });
   });
</script>