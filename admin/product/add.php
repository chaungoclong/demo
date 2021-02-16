<?php
require_once '../../common.php';
require_once '../include/header.php';

if(!is_login() || !is_admin()) {
  redirect('admin/form_login.php');
}

require_once '../include/sidebar.php';
require_once '../include/navbar.php';

// danh sách hãng
$listBrand = db_fetch_table("db_brand", 0);

// danh sách thể loại
$listCategory = db_fetch_table("db_category", 0);
?>

<!-- main content -row -->
<div class="main_content bg-white row m-0 pt-4">

   <div class="col-12">
      <div class="row m-0">
         <div class="col-12">
            <h5>THÊM SẢN PHẨM</h5>
            <p class="mb-4">Thêm sản phẩm mới</p>
            <hr>
         </div>
      </div>
   </div>

   <div class="col-12 mb-5">
      <form action="	" method="POST" id="product_add_form" enctype="multipart/form-data">

         <div class="row m-0">
            <div class="col-12">
               <div  id="backErr" class="alert-danger"></div>

               <!-- tên sản phẩm -->
               <div class="form-group">
                  <label for="name"><strong>Tên sản phẩm:</strong></label>
                  <input type="text" class="form-control" name="name" id="name" required>
                  <div class="alert-danger" id="nameErr"></div>
               </div>

               <div class="form-row">

                  <!-- thể loại -->
                  <div class="form-group col-6">
                    <label for="category"><strong>Thể loại:</strong></label>
                    <select name="category" id="category" class="custom-select">
                        <option value="0" disabled hidden selected="">Chọn thể loại</option>
                       <?php foreach ($listCategory as $key => $category): ?>
                          <option value="<?= $category['cat_id']; ?>">
                             <?= $category['cat_name']; ?>
                          </option>
                       <?php endforeach ?>
                    </select>
                    <div class="alert-danger" id="categoryErr"></div>
                 </div>

                 <!-- hãng -->
                 <div class="form-group col-6">
                    <label for="brand"><strong>Hãng:</strong></label>
                    <select name="brand" id="brand" class="custom-select">
                        <option value="0" disabled hidden selected="">Chọn hãng</option>
                       <?php foreach ($listBrand as $key => $brand): ?>
                          <option value="<?= $brand['bra_id']; ?>">
                             <?= $brand['bra_name']; ?>
                          </option>
                       <?php endforeach ?>
                    </select>
                    <div class="alert-danger" id="brandErr"></div>
                 </div>
             </div>

             <!-- ảnh đại diện -->
             <div class="form-row">
                <div class="form-group col-6">
                   <label for="image"><strong>Ảnh sản phẩm:</strong></label>
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

             <!-- ảnh chi tiết -->
             <div class="form-group">
                <label for="library"><strong>Ảnh chi tiết:</strong></label>
                <input type="file" name="library[]" id="library" multiple="">

                <div class="previewLibrary d-flex flex-wrap"></div>
                <script>
                   $(document).on('change', '#library', function() {
                     showImg(this, ".previewLibrary", 1);
                   });
                </script>
                 <div class="alert-danger" id="libraryErr"></div>
             </div>

             <!--  -->
            <div class="form-row">
                <div class="form-group col-4">
                   <label for="price"><strong>Giá:</strong></label>
                   <input type="number" class="form-control" name="price" id="price">
                   <div class="alert-danger" id="priceErr"></div>
               </div>
               <div class="form-group col-4">
                   <label for="quantity"><strong>Số lượng:</strong></label>
                   <input type="number" class="form-control" name="quantity" id="quantity">
                   <div class="alert-danger" id="quantityErr"></div>
               </div>
               <div class="form-group col-4">
                   <label for="color"><strong>Màu sắc:</strong></label>
                   <input type="text" class="form-control" name="color" id="color">
                   <div class="alert-danger" id="colorErr"></div> 
               </div>
            </div>

            <!-- mô tả chi tiết -->
            <div class="form-group">
               <label for="desc"><strong>Mô tả chi tiết:</strong></label>
               <textarea name="desc" id="desc"></textarea>
               <script>
                  CKEDITOR.replace( 'desc', {
                      filebrowserBrowseUrl: '../../dist/ckfinder/ckfinder.html',
                      filebrowserImageBrowseUrl: '../../dist/ckfinder/ckfinder.html?Type=Images',
                      filebrowserUploadUrl: '../../dist/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                      filebrowserImageUploadUrl: '../../dist/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                      filebrowserWindowWidth : '1000',
                      filebrowserWindowHeight : '700'
                  });
               </script>
               <div class="alert-danger" id="descErr"></div>
            </div>

            <!-- Thông số sản phẩm -->
            <div class="form-group">
               <label for="detail"><strong>Thông số sản phẩm:</strong></label>
               <textarea name="detail" id="detail"></textarea>
               <script>
                  CKEDITOR.replace( 'detail', {
                      filebrowserBrowseUrl: '../../dist/ckfinder/ckfinder.html',
                      filebrowserImageBrowseUrl: '../../dist/ckfinder/ckfinder.html?Type=Images',
                      filebrowserUploadUrl: '../../dist/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                      filebrowserImageUploadUrl: '../../dist/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                      filebrowserWindowWidth : '1000',
                      filebrowserWindowHeight : '700'
                  });
               </script>
               <div class="alert-danger" id="detailErr"></div> 
            </div>

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
   	$(document).on('submit', "#product_add_form", function(e) {
   		e.preventDefault();

      addProduct();

      });
   });
</script>