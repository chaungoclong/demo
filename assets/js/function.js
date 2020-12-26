$(document).ready(function() {
    /*slide*/
    $('#banner').carousel({interval:500});
    //chuyển tiếp slide sau 500ms
    $('#banner').carousel('cycle');
    //đi qua các slide từ trái sang phải
    var index = $('.id'); //danh sách chỉ mục

    for(let i = 0; i < index.length; ++i) {
        $(index[i]).on('click', () => {
            $('#banner').carousel(i);
        })
    }
    //gán sự kiện khi click các chỉ mục

    $('.carousel-control-prev').on('click', () => {
        $('#banner').carousel('prev');
    });
    //gán sự kiện khi click vào nút prev

    $('.carousel-control-next').on('click', () => {
        $('#banner').carousel('next');
    });
    //gán sự kiện khi click vào nút next
});

/**
 * [dropdown description]
 * @param  {[type]} btn  [nút nhấn để menu xổ ra]
 * @param  {[type]} menu [menu dropdown]
 */
 function dropdown(btn, menu) {
     $(document).on('click',function(e) {
         if(e.target === btn || btn.has(e.target).length !== 0) {
             menu.slideToggle('fast');
         } else {
             menu.slideUp('fast');
         } 
     });
 }
