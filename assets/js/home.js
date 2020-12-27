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
});


