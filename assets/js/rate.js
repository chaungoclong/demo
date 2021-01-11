/**
 * [fetchRate hàm lấy về thông tin của các đánh giá]
 * @param  {[type]} proID [description]
 * @return {json}       [trả về mã html của các đánh giá, 
 * tổng số lượng đánh giá của sản phẩm này, tổng số sao,
 * số sao trung bình]
 */
function fetchRate(proID) {
    let data = { proID: proID, action: "fetch_rate" };
    let result = sendAJax(
        'process_rate.php',
        'post',
        'json',
        data
    );
    if (result) {
        $('.show_rate').html(result.html);
        $('.qtyRate').text(result.numRate);
        if(result.numRate > 0) {
        	$('.rateAvg').html(result.resultAvgStar);
        }
    }
}

//hàm kiểm tra đánh giá của người dùng cusID  về sản phẩm proID đã tồn tại hay chưa
function rateExist(cusID, proID, action) {
	let result = sendAJax(
		'process_rate.php',
		'post',
		'text',
		{cusID:cusID, proID:proID, action:action}
	);
	if(result == '1'){
		return true;
	}
	return false;
}


//hàm thêm , cập nhật đánh giá (chuyển đổi thông qua biến action)
function setRate(cusID, proID, rateValue, rateContent, action) {
	let result = sendAJax(
		"process_rate.php",
		"post",
		"json",
		{
			cusID:cusID,
			proID:proID,
			rateValue:rateValue,
			rateContent:rateContent,
			action:action
		}
	);
	return result;
}