$(document).ready(function() {
    //dropdown hover
    var dropdown = $('#navbar .dropdown, #account');
    $(dropdown).hover(function(e) {
        console.log(e);
        $(this).find('.dropdown-menu').slideToggle(0);
    });

    //search
    $("#boxSearch").on("input", function() {
    	var data = $(this).val();
    	var keyWord = $(this).serialize();
    	if(data) {
    		$.ajax({
    			url: "search_live.php",
    			type: "GET",
    			data: keyWord,
    			dataType: "html",
    			success: function(res) {
    				$("#ajaxSearch").html(res);
    			}
    		});
    	} else {
    		$("#ajaxSearch").html("");
    	}
    });

    //hiển thị ảnh khi chọn
    $('#avatar').on('change', function() {
       $('#showImg').html("");
       showImg(this, "#showImg", ".custom-file-label");
   });
});


    //hàm hiển thị ảnh khi chọn
    function showImg(input, storeImg, storeNameImg) {
            //lấy số lượng file trong thẻ input
            var numberFile = input.files.length;
            // console.log(numberFile);

            //đọc từng file
            for(let i = 0; i < numberFile; ++i) {
                //tạo đối tượng đọc file
                var reader = new FileReader();

              /**
               * [onload description]
               * khi xảy ra sự kiện load lấy kết quả tại vị trí xảy ra sự kiện
               * tạo một thẻ phần tử 'img' để hiển thị
               * gán đường dẫn của hình ảnh bằng kết quả lấy được
               * thêm hình ảnh vào nơi cần thêm
               * 
               */
               reader.onload = function(e) {
                var url  = e.target.result;
                var name = input.files[i].name;
                var img  = $('<img >');
                img.attr('src', url);
                $(storeNameImg).text(name);
                $(storeImg).append(img);
            }

                //trả kết quả của từng phần tử file dưới dạng url
                reader.readAsDataURL(input.files[i]);
            }
        }

