$(document).ready(function() {
    //dropdown hover
    var dropdown = $('#navbar .dropdown, #account');
    $(dropdown).hover(function(e) {
        console.log(e);
        $(this).find('.dropdown-menu').slideToggle(0);
    });
});

