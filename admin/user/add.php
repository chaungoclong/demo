<?php
   require_once '../../common.php';
   require_once '../include/header.php';
   
   if(!is_login() || !is_admin()) {
   	redirect('admin/form_login.php');
   }
   
   require_once '../include/sidebar.php';
   require_once '../include/navbar.php';
   
   // trang trước
   $prevLink = isset($_GET['from']) ? $_GET['from'] : "index.php";
   //vd($customer);
   ?>
<!-- main content -row -->
<div class="main_content bg-white row m-0 pt-4">
   <div class="col-12">
      <form action="	" method="POST" id="user_add_form">
         <!-- previous link -->
         <input type="hidden" name="prevLink" value="<?= $prevLink; ?>">
         <div class="row m-0">
            <div class="col-12">
               <h5>THÔNG TIN NHÂN VIÊN</h5>
               <p class="mb-4">Quản lý thông tin nhân viên</p>
               <hr>
            </div>
         </div>

         <div class="row m-0">
            <div class="col-9">
               <div  id="backErr" class="alert-danger"></div>
               <!-- uname -->
               <div class="form-group mb-3">
                  <div class="input-group">
                     <div class="input-group-prepend">
                        <label for="uname" class="input-group-text">
                        <i class="fas fa-user fa-lg"></i>
                        </label>
                     </div>
                     <input type="text" id="uname" name="uname" class="form-control" placeholder="name">
                  </div>
                  <div id="unameErr" class="alert-danger">
                  </div>
               </div>
               <!-- name -->
               <div class="form-group mb-3">
                  <div class="input-group">
                     <div class="input-group-prepend">
                        <label for="name" class="input-group-text">
                        <i class="fas fa-user fa-lg"></i>
                        </label>
                     </div>
                     <input type="text" id="name" name="name" class="form-control" placeholder="name">
                  </div>
                  <div id="nameErr" class="alert-danger">
                  </div>
               </div>
             
               <!-- date of birth -->
               <div class="form-group mb-3">
                  <div class="input-group">
                     <div class="input-group-prepend">
                        <label for="dob" class="input-group-text">
                        <i class="fas fa-calendar fa-lg"></i>
                        </label>
                     </div>
                     <input type="text" id="dob" name="dob" class="form-control" placeholder="dd-mm-yyyy" autocomplete="off">
                     <script>
                        /**
                        *input: return input tag
                        *inst: an object that including datepicker
                        *dpDiv : a attribute of inst , it is datepicker
                        */
                        
                        $(document).ready(function() {
                        	$('#dob').datepicker({
                        		dateFormat: 'dd-mm-yy',
                        		yearRange: "-100:2100",
                        		changeMonth: true,
                        		changeYear: true,
                        		beforeShow: function (input, inst) {
                        			$(".ui-datepicker-month").insertAfter(".ui-datepicker-year");
                        			console.log(input, inst);
                        			setTimeout(function () {
                        				inst.dpDiv.css({
                        					top: 310,
                        					left: 280
                        				});
                        			},0);
                        		}
                        	});
                        	$('#dob').change(function() {
                        		console.log($(this).val());
                        	})
                        });
                     </script>
                  </div>
                  <div id="dobErr" class="alert-danger">
                  </div>
               </div>
               <!-- gender -->
               <div class="form-group mb-3">
                  <div class="custom-control custom-radio custom-control-inline">
                     <input type="radio" id="male" name="gender" class="custom-control-input" value="1"
                        >
                     <label for="male" class="custom-control-label">Nam</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                     <input type="radio" id="female" name="gender" class="custom-control-input" value="0"
                        >
                     <label for="female" class="custom-control-label">Nữ</label>
                  </div>
                  <div id="genderErr" class="alert-danger"></div>
               </div>
               <!-- email -->
               <div class="form-group mb-3">
                  <div class="input-group">
                     <div class="input-group-prepend">
                        <label for="email" class="input-group-text">
                        <i class="fas fa-envelope fa-lg"></i>
                        </label>
                     </div>
                     <input type="text" id="email" name="email" class="form-control" placeholder="email">
                  </div>
                  <div id="emailErr" class="alert-danger">
                  </div>
               </div>

               <!-- password -->
               <div class="form-group mb-3">
                  <div class="input-group">
                     <div class="input-group-prepend">
                        <label for="pwdRegister" class="input-group-text">
                           <i class="fas fa-lock fa-lg"></i>
                        </label>
                     </div>
                     <input type="text" id="pwdRegister" name="pwdRegister" class="form-control" placeholder="password">   
                  </div>
                  <div id="pwdRegisterErr" class="alert-danger"></div>
               </div>

               <!-- retype password -->
               <div class="form-group mb-3">
                  <div class="input-group">
                     <div class="input-group-prepend">
                        <label for="rePwdRegister" class="input-group-text">
                           <i class="fas fa-lock fa-lg"></i>
                        </label>
                     </div>
                     <input type="text" id="rePwdRegister" name="rePwdRegister" class="form-control" placeholder="password">  
                  </div>
                  <div id="rePwdRegisterErr" class="alert-danger"></div>
               </div>

               <!-- phone -->
               <div class="form-group mb-3">
                  <div class="input-group">
                     <div class="input-group-prepend">
                        <label for="phone" class="input-group-text">
                        <i class="fas fa-phone-alt fa-lg"></i>
                        </label>
                     </div>
                     <input type="text" id="phone" name="phone" class="form-control" placeholder="phone">
                  </div>
                  <div id="phoneErr" class="alert-danger">
                  </div>
               </div>

               <!-- active -->
				<div class="custom-control custom-switch mb-3">
					<input
					type="checkbox"
					id="active"
					name="active"
					class="custom-control-input"
               checked
					>
					<label for="active" class="custom-control-label">Trạng thái</label>
				</div>

				<!-- role -->
				<div class="form-group d-flex flex-column">
					<label for="role" class="">QUYỀN:</label>
					<select id="role" name="role" class="mb-3 custom-select w-25">
						<option value="1" >superAdmin</option>
						<option value="2" selected="">Amin</option>
					</select>
				</div>

               <!-- ADD button -->
               <button class="btn_user_update btn btn-primary btn-block mb-3">LƯU</button>
            </div>
            <div class="col-3">
               <div class="upload w-100 bg-faded mb-3 text-center">
                  <!-- avatar edit -->
                  <div id="user_avatar_edit" class="mb-2">
                     <img src="" width="100%" class="img-thumbnail">
                  </div>
                  <h5>CHỌN ẢNH</h5>
                  <!-- input file avatar -->
                  <input type="file" id="avatar" name="avatar" class="form-control" style="overflow: hidden;">
               </div>
               <script>
                  $(function() {
                  	//hiển thị ảnh khi chọn
                  	$('#avatar').on('change', function() {
                  		showImg(this, "#user_avatar_edit", 0);
                  	});
                  });
               </script>
               <div id="avatarErr" class="alert-danger"></div>
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
   	$(document).on('submit', "#user_add_form", function(e) {
   		e.preventDefault();
         
         addUser();

   	});
   });
</script>