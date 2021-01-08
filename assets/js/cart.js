$(function() {
    //thay đổi ảnh
    $('.small_img').on('click', function() {
        $('.big_img').attr("src", $(this).attr("src"));
    });

    //giảm số lượng
    $('.minus').on('click', function() {
        let input = $(this).parent().find('input');

        let value = parseInt(input.val());
        if (isNaN(value)) {
            input.val(1);
        } else if (value - 1 >= 1) {
            input.val(--value);
        }
    });

    //tăng số lượng
    $('.plus').on('click', function() {
        let input = $(this).parent().find('input');
        let value = parseInt(input.val());
        if (isNaN(value)) {
            input.val(1);
        } else if (value + 1 <= 10) {
            input.val(++value);
        }
    });

    //validate số lượng

    $(document).on('input', '.quantity', function() {
        console.log("qty");
        let value = parseInt(this.value);
        if (!isNaN(value)) {
            if (value <= 0) {
                this.value = 1;
            } else if (value > 10) {
                this.value = 10;
            } else {
            	this.value = value;
            }
        } else {
            this.value = 1;
        }
    });

    //thêm sản phẩm ở các trang khác trang chi tiết sản phẩm
    $('.btn_add_cart_out').on('click', function() {
        let proID = $(this).data('pro-id');
        let quantity = 1;
        let action = "add";
        let data = {proid:proID, quantity:quantity, action:action};

        let sendCart = $.ajax({
            url: "cart.php",
            method: "POST",
            data: data,
            dataType: "json"
        });

            //thành công
            sendCart.done(function(res) {
                alert(res.notice);
                if(res.totalItem > 0) {
                    $('#shoppingCartIndex').text(res.totalItem);
                } else {
                    $('#shoppingCartIndex').text(0);
                }
            })

            //thất bại
            sendCart.fail(function(a, b, c) {
                console.log(a, b, c);
            })
        });

})