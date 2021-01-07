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

})