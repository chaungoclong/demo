$(function() {
    //thay đổi ảnh
    $(document).on('click', '.small_img', function() {
        $('.big_img').attr("src", $(this).attr("src"));
    });

    //giảm số lượng
    $(document).on('click', '.minus', function() {
        let input = $(this).parent().find('input');

        let value = parseInt(input.val());
        if (isNaN(value)) {
            input.val(1);
        } else if (value - 1 >= 1) {
            input.val(--value);
        }
    });

    //tăng số lượng
    $(document).on('click', '.plus', function() {
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
    $(document).on('click', '.btn_add_cart_out', function() {
        let proID = $(this).data('pro-id');
        let quantity = 1;
        let action = "add";
        let data = { proid: proID, quantity: quantity, action: action };

        //lấy số lượng sản phẩm 
        let qtyPro = parseInt(
            sendAJax(
                'get_cart.php',
                'post',
                'text', { proid: proID, action: "pro_qty" }
            )
        );

        //số lượng sản phẩm trong giỏ hàng
        let qtyInCart = parseInt(
            sendAJax(
                'get_cart.php',
                'post',
                'text', { proid: proID, action: "pro_cart_qty" }
            )
        );

        //nếu số lượng sản phẩm này thêm vào + số lượng sản phẩm này trong giỏ hàng 
        //> số lượng sản phẩm này in ra thông báo lỗi
        //nếu không thêm sản phẩm vào giỏ hàng
        if (quantity + qtyInCart > qtyPro) {
            alert("SỐ LƯỢNG SẢN PHẨM KHÔNG ĐỦ");
        } else {
            let sendCart = $.ajax({
                url: "cart.php",
                method: "POST",
                data: data,
                dataType: "json"
            });

            //thành công
            sendCart.done(function(res) {
                alert(res.notice);
                if (res.totalItem > 0) {
                    $('#shoppingCartIndex').text(res.totalItem);
                    $('#modal_cart').show().find('.badge').text(res.totalItem);
                } else {
                    $('#shoppingCartIndex').text(0);
                }
            });

            //thất bại
            sendCart.fail(function(a, b, c) {
                console.log(a, b, c);
            });
        }

    });

});

function fetch_cart() {
    let content = sendAJax(
        'cart.php',
        'post',
        'json'
    );

    if (content.notice != "") {
        alert(content.notice);
    }
    console.log('ok:');
    console.log(content.totalItem);
    $('#shopping-cart .card-body').html(content.html);
    if (content.totalItem > 0) {
        $('#shoppingCartIndex').text(content.totalItem);
        $('#modal_cart').show().find('.badge').text(content.totalItem);
        $('.btn_check_out').show();
    } else {
        $('#shoppingCartIndex').text(0);
        $('#modal_cart').hide();
        $('.btn_check_out').hide();
    }
}